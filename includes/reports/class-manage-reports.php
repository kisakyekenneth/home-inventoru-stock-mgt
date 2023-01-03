<?php

namespace Kanzu\Uipe\Reports;

use eftec\bladeone\BladeOne;
use Shuchkin\SimpleXLSXGen;

class Manage_Reports
{
    public function render_applications_received()
    {
        $views     = KANZU_UIPE_DIR . '/templates/reports/';
        $cache     = KANZU_UIPE_DIR . '/templates_cache/';
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        $data = [
            'applicants' => []
        ];

        $incoming = get_posts([
            'numberposts' => -1,
            'post_type' => 'member_application',
            'meta_query' => [
                [
                    'key'       => '_kc_uipe_application_assessment_status',
                    'compare'   => 'NOT EXISTS'
                ],
                [
                    'key' => '_application_status',
                    'value' => 'paid'
                ]
            ]
        ]);

        //Retrieve level applications
        $in_progress = get_posts([
            'numberposts' => -1,
            'post_type' => 'member_application',
            'meta_query' => [
                [
                    'key'       => '_kc_uipe_application_assessment_status',
                    'value'     => 'assigned',
                    'compare'   => '='
                ],
                [
                    'key' => '_application_status',
                    'value' => 'paid'
                ]
            ]
        ]);

        $all_applications = array_merge($incoming, $in_progress);
        foreach ($all_applications as $application) {
            $user_id = get_post_field('post_author', $application->ID);
            $user = get_userdata($user_id);
            $eng_discipline = get_user_meta($user_id, '_kc_uipe_eng_displines', true);

            if (is_int(intval($eng_discipline))) {
                $term = get_term_by('id', $eng_discipline, 'eng-discipline');
                if ($term) {
                    $eng_discipline = $term->name;
                }
            }

            $eng_sub_discipline = get_user_meta($user_id, '_kc_uipe_eng_sub_displines', true);
            if (is_int(intval($eng_sub_discipline))) {
                $term = get_term_by('id', $eng_sub_discipline, 'eng-discipline');
                if ($term) {
                    $eng_sub_discipline = $term->name;
                }
            }

            $membership_level = get_post_field('post_title', get_post_meta($application->ID, '_kc_uipe_membership_level', true));
            $date = get_userdata(get_post_meta($application->ID, '_payment_completion_date', true));

            $assessment_status = get_post_meta($application->ID, '_kc_uipe_application_assessment_status', true);
            if (!$assessment_status){
                $assessment_status = 'pending';
            }

            $data['applicants'][] = [
                'id' => $application->ID,
                'names' => $user->first_name . ' ' . $user->last_name,
                'discipline' => $eng_discipline,
                'sub_discipline' => $eng_sub_discipline,
                'level' => $membership_level,
                'date' => $date,
                'assignment_status' => $assessment_status,
            ];
        }

        return $blade->run('applications-received', $data);
    }

    public function approved_member_register()
    {
        global $post;
        $views     = KANZU_UIPE_DIR . '/templates/reporting/';
        $cache     = KANZU_UIPE_DIR . '/templates_cache/';
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        return $blade->run('approved-member-register');
    }

    public function toggle_member_activation_status()
    {
        if (current_user_can('manage_options')) {
            if (isset($_POST['activation_status'])) {
                $activation_status = sanitize_text_field($_POST['activation_status']);
                $membership_id = sanitize_text_field($_POST['membership_id']);
                update_post_meta($membership_id, '_kc_uipe_activation_status', $activation_status);
            }
            if (isset($_POST['subscription_status'])) {
                $subscription_status = sanitize_text_field($_POST['subscription_status']);
                $membership_id = sanitize_text_field($_POST['membership_id']);
                update_post_meta($membership_id, '_kc_uipe_subscription_status', $subscription_status);
            }

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    public function filter_membership_register()
    {
        global $post;
        $views     = KANZU_UIPE_DIR . '/templates/reporting/';
        $cache     = KANZU_UIPE_DIR . '/templates_cache/';
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        $filter_membership_level =  sanitize_text_field($_POST['membership_class']);
        $filter_subscription =  sanitize_text_field($_POST['subscription']);
        $filter_activation_status =  sanitize_text_field($_POST['activation_status']);
        $action = 'display_register';
        $export_format = '';

        $data = $this->get_approved_members($filter_membership_level, $filter_activation_status, $filter_subscription, $action, $export_format);

        wp_send_json_success($blade->run('register', $data));
    }

    public function download_member_registry_report()
    {
        $report_date = date('l');

        $filter_membership_level =  sanitize_text_field($_POST['membership_class']);
        $filter_subscription =  sanitize_text_field($_POST['subscription']);
        $filter_activation_status =  sanitize_text_field($_POST['activation_status']);
        $export_format =  sanitize_text_field($_POST['exportFormat']);
        $action = 'download_xlsx';

        $file_name = $export_format == 'excel' ? 'Member-Register-' . $report_date . '.xlsx' : 'Member-Register-' . $report_date . '.csv';
        $upload_path = wp_upload_dir();
        $path = $upload_path['path'] . '/' . $file_name;

        $rows = $this->get_approved_members($filter_membership_level, $filter_activation_status, $filter_subscription, $action, $export_format);
        if ($export_format == 'excel') {
            SimpleXLSXGen::fromArray($rows)->saveAs($path);
        } else {
            $this->download_csv_file($rows, $path);
        }

        wp_send_json_success($upload_path['url'] . '/' . $file_name);
    }

    function download_csv_file($data, $path)
    {

        if (count($data) == 0) {
            return null;
        }
        ob_start();
        $df = fopen($path, 'w');
        $headers = [
            "#",
            "Applicant Names",
            "Membership class",
            "Activation status",
            "Subscription status",
            "Subscription start date",
            "Subscription end date",
        ];
        fputcsv($df, $headers);
        foreach ($data as $row) {
            fputcsv($df, $row);
        }
        fclose($df);

        return ob_get_clean();
    }

    function get_approved_members($filter_membership_level, $filter_activation_status, $filter_subscription, $action, $export_format)
    {
        $data['applicants'] = [];
        $rows = [];

        if ($export_format == 'excel') {
            $rows[] = [
                "#",
                "Applicant Names",
                "Membership class",
                "Activation status",
                "Subscription status",
                "Subscription start date",
                "Subscription end date",
            ];
        }

        $Membership_approvals = get_posts([
            'numberposts' => -1,
            'post_type' => 'uipe-membership',
            'meta_query' => [
                [
                    'key'       => '_kc_uipe_membership_level',
                    'value'     => $filter_membership_level,

                ],
                [
                    'key'       => '_kc_uipe_activation_status',
                    'value'     => $filter_activation_status,

                ],
                [
                    'key'       => '_kc_uipe_subscription_status',
                    'value'     => $filter_subscription,
                ]
            ]
        ]);

        $count_record = 0;

        foreach ($Membership_approvals as $Membership_approval) {
            $user_id = get_post_meta($Membership_approval->ID, '_kc_uipe_member_user_id', true);
            $activation_status = get_post_meta($Membership_approval->ID, '_kc_uipe_activation_status', true);
            $subscription_status = get_post_meta($Membership_approval->ID, '_kc_uipe_subscription_status', true);
            $application_id = get_post_meta($Membership_approval->ID, '_kc_uipe_application_id', true);
            $subscription_start_date = get_post_meta($Membership_approval->ID, '_kc_uipe_subscription_start_date', true);
            $subscription_end_date = get_post_meta($Membership_approval->ID, '_kc_uipe_subscription_end_date', true);
            $membership_class = get_post_field('post_title', get_post_meta($application_id, '_kc_uipe_membership_level', true));

            $data['applicants'][] = [
                'id' => $Membership_approval->ID,
                'user_id' => $user_id,
                'username' => get_user_name($user_id),
                'activation_status' => $activation_status,
                'subscription_status' => $subscription_status,
                'application_id' => $application_id,
                'subscription_end_date' => $subscription_end_date,
                'subscription_start_date' => $subscription_start_date,
                'membership_class' => $membership_class
            ];

            $rows[] = [
                $count_record += 1,
                get_user_name($user_id),
                $membership_class,
                $activation_status,
                $subscription_status,
                (new \DateTime($subscription_start_date))->format("d-m-Y"),
                (new \DateTime($subscription_end_date))->format("d-m-Y"),
            ];
        }

        if ($action == 'display_register') {
            return $data;
        } else {
            return $rows;
        }
    }
}
