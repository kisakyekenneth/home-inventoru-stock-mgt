<?php

namespace Njhm\Church;

use eftec\bladeone\BladeOne;
use Shuchkin\SimpleXLSXGen;

class ManageChoir
{
    function new_members()
    {
        $views     = NJHM_DIR . '/templates/choir/';
        $cache     = NJHM_DIR . '/templates_cache/';
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $data = [];

        $data['id'] = $_GET['id'] ?? '';

        if (isset($_GET['id'])) {
            $grant_id = sanitize_text_field($_GET['id']);
            $grant_name = get_the_title($grant_id);
            $principal =  get_post_meta($grant_id, '_grant_principal', true);
            $grant_funder =   get_post_meta($grant_id, '_grant_funder', true);
            $grant_fund_amount =   get_post_meta($grant_id, '_grant_fund_amount', true);
            $grant_start_date =  get_post_meta($grant_id, '_grant_start_date', true);
            $grant_end_date =   get_post_meta($grant_id, '_grant_end_date', true);
            $maksph_dept =  get_post_meta($grant_id, '_maksph_dept', true);
            $grant_fund_currency =  get_post_meta($grant_id, '_grant_fund_currency', true);
            $grant_issue_date =   get_post_meta($grant_id, '_grant_end_date', true);
            $grant_description =  get_post_meta($grant_id, '_grant_description', true);
            $source_country =  get_post_meta($grant_id, '_grant_fund_currency', true);
            $institution =  get_post_meta($grant_id, '_subcontracts', true);

            $maksph_grant_id = get_post_meta($grant_id, '_maksph_grant_id', true);

            $data['grant_data'][] = [
                'id' => $grant_id,
                'grant_name' => $grant_name,
                'principal' => $principal,
                'grant_funder' => $grant_funder,
                'grant_fund_amount' => $grant_fund_amount,
                'grant_start_date' => $grant_start_date,
                'grant_end_date' => $grant_end_date,
                'maksph_dept' => $maksph_dept,
                'grant_fund_currency' => $grant_fund_currency,
                'grant_issue_date' => $grant_issue_date,
                'grant_description' => $grant_description,
                'source_country' => $source_country,
                'institution' => $institution,
                'maksph_grant_id' => $maksph_grant_id,
            ];

            $data['subcontracts'] = [];

            $args = [
                'numberposts' => -1,
                'post_type' => 'grant-subcontract',
                'meta_query' => [
                    [
                        'key'       => '_parent_grant',
                        'value'     => $_GET['id'],
                    ],

                ]

            ];

            $subcontracts = get_posts($args);

            foreach ($subcontracts as $key => $subcontract) {
                $subcontract_id = $subcontract->ID;
                $principal = get_post_meta($subcontract->ID, '_principal', true);
                $_funder =  get_post_meta($subcontract->ID, '_funder', true);
                $_fund_amount = get_post_meta($subcontract->ID, '_fund_amount', true);
                $_start_date = get_post_meta($subcontract->ID, '_start_date', true);
                $_end_date =  get_post_meta($subcontract->ID, '_end_date', true);
                $_issue_date = get_post_meta($subcontract->ID, '_issue_date', true);
                $_fund_currency = get_post_meta($subcontract->ID, '_fund_currency', true);
                $source_country = get_post_meta($subcontract->ID, '_source_country', true);
                $institution = get_post_meta($subcontract->ID, '_institution', true);
                $sub_contract =  get_post_meta($subcontract->ID, '_subcontract_agreement', true);
                $due_deligence_form =  get_post_meta($subcontract->ID, '_due_diligence_assessment', true);

                $data['subcontracts'][] = [
                    'id' => $subcontract_id,
                    'principal' => $principal,
                    '_funder' => $_funder,
                    '_fund_amount' => $_fund_amount,
                    '_start_date' => $_start_date,
                    '_end_date' => $_end_date,
                    '_issue_date' => $_issue_date,
                    '_fund_currency' => $_fund_currency,
                    'source_country' => $source_country,
                    'institution' => $institution,
                    'sub_contract' => $sub_contract,
                    'due_deligence_form' => $due_deligence_form
                ];
            }


            $html      = $blade->run('member-details', $data);
        } else {
            $data['members'] = [];

            $args = [
                'numberposts' => -1,
                'post_type' => 'njhm-choir-member',
            ];

            $members = get_posts($args);

            foreach ($members as $key => $member) {
                $member_id = $member->ID;
                $member_name =    get_post_meta($member->ID, '_member_name', true);
                $joining_date =  get_post_meta($member->ID, '_joining_date', true);
                $telephone =  get_post_meta($member->ID, '_telephone', true);
                $residence = get_post_meta($member->ID, '_residence', true);
                $baptised =   get_post_meta($member->ID, '_baptised', true);
                $baptism_church = get_post_meta($member->ID, '_baptism_church', true);
                $marital_status = get_post_meta($member->ID, '_marital_status', true);

                $data['members'][] = [
                    'id' => $member_id,
                    'member_name' => $member_name,
                    'joining_date' => $joining_date,
                    'telephone' => $telephone,
                    'residence' => $residence,
                    'baptised' => $baptised,
                    'baptism_church' => $baptism_church,
                    'marital_status' => $marital_status

                ];
            }
            $html      = $blade->run('new-members', $data);
        }

        echo $html;
    }

    function njhm_new_choir_member()
    {
        if (wp_verify_nonce($_POST['njhm_new_member_nonce_field'], 'njhm_new_member_nonce')) {
            $member_name = sanitize_text_field($_POST['memberName']);
            $joining_date = sanitize_text_field($_POST['joining_date']);
            $telephone = sanitize_text_field($_POST['telephone']);
            $residence = sanitize_text_field($_POST['residence']);
            $baptised = sanitize_text_field($_POST['baptised']);
            $baptism_church = sanitize_text_field($_POST['baptismChurch']);
            $marital_status = sanitize_text_field($_POST['marital_status']);

            $details =  [
                'post_title' => $member_name,
                'post_status' => 'publish',
                'post_content' => $member_name .  ' Data',
                'post_type' => 'njhm-choir-member',
                'post_author' => get_current_user_id()
            ];

            $post_id = wp_insert_post($details);
            update_post_meta($post_id, '_member_name', $member_name);
            update_post_meta($post_id, '_joining_date', $joining_date);
            update_post_meta($post_id, '_telephone', $telephone);
            update_post_meta($post_id, '_residence', $residence);
            update_post_meta($post_id, '_baptised', $baptised);
            update_post_meta($post_id, '_baptism_church', $baptism_church);
            update_post_meta($post_id, '_marital_status', $marital_status);

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    function update_member_data()
    {
        if (wp_verify_nonce($_POST['njhm_update_member_nonce_field'], 'njhm_update_member_nonce')) {
            $post_id = sanitize_text_field($_POST['member_id']);
            $member_name = sanitize_text_field($_POST['memberName']);
            $joining_date = sanitize_text_field($_POST['joining_date']);
            $telephone = sanitize_text_field($_POST['telephone']);
            $residence = sanitize_text_field($_POST['residence']);

            $details =  [
                'ID'           => $post_id,
                'post_title' => $member_name,
                'post_content' => $member_name,
            ];

            wp_update_post($details);
            update_post_meta($post_id, '_member_name', $member_name);
            update_post_meta($post_id, '_joining_date', $joining_date);
            update_post_meta($post_id, '_telephone', $telephone);
            update_post_meta($post_id, '_residence', $residence);

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }


    function delete_grant_data()
    {
        if (isset($_POST['grant_id'])) {
            $grant_id = sanitize_text_field($_POST['grant_id']);
            wp_delete_post($grant_id);
            wp_send_json_success();
        }
    }

    public function download_grant_registry_report()
    {
        $report_date = date('l');

        $file_name = 'makSPH-grants-' . $report_date . '.xlsx';
        $upload_path = wp_upload_dir();
        $path = $upload_path['path'] . '/' . $file_name;

        $rows = $this->get_all_grants();

        SimpleXLSXGen::fromArray($rows)->saveAs($path);

        wp_send_json_success($upload_path['url'] . '/' . $file_name);
    }

    function get_all_grants()
    {
        $data['grants'] = [];
        $rows = [];

        $rows[] = [
            "#",
            'Grant ID',
            "Grant Name",
            "Grant Description",
            "Principal Investigator",
            "Funder",
            "Amount",
            "Issue date",
            "Start date",
            "End date",
            "Department",
            "Currency",
        ];

        $grants = get_posts([
            'numberposts' => -1,
            'post_type' => 'maksph-grant',
        ]);

        $count_record = 0;

        foreach ($grants as $grant) {
            $grant_id = $grant->ID;
            $grant_name = $grant->post_title;
            $principal =  get_post_meta($grant->ID, '_grant_principal', true);
            $grant_funder =   get_post_meta($grant->ID, '_grant_funder', true);
            $grant_fund_amount =   get_post_meta($grant->ID, '_grant_fund_amount', true);
            $grant_issue_date =  get_post_meta($grant->ID, '_grant_issue_date', true);
            $grant_start_date =  get_post_meta($grant->ID, '_grant_start_date', true);
            $grant_end_date =   get_post_meta($grant->ID, '_grant_end_date', true);
            $maksph_dept =  get_post_meta($grant->ID, '_maksph_dept', true);
            $grant_fund_currency =  get_post_meta($grant->ID, '_grant_fund_currency', true);
            $subcontracts =  get_post_meta($grant->ID, '_subcontracts', true);
            $grant_description =  get_post_meta($grant->ID, '_grant_description', true);

            $maksph_grant_id = get_post_meta($grant->ID, '_maksph_grant_id', true);


            $rows[] = [
                $count_record += 1,
                $maksph_grant_id,
                $grant_name,
                $grant_description,
                $principal,
                $grant_funder,
                $grant_fund_amount,
                $grant_issue_date,
                $grant_start_date,
                $grant_end_date,
                $maksph_dept,
                $grant_fund_currency
            ];
        }


        return $rows;
    }
    function save_subcontract()
    {
        if (wp_verify_nonce($_POST['maksph_subcontract_grant_nonce_field'], 'maksph_subcontract_grant_nonce')) {
            $institution = sanitize_text_field($_POST['institution']);
            $principal = sanitize_text_field($_POST['principal']);
            $_funder = sanitize_text_field($_POST['_funder']);
            $_fund_amount = sanitize_text_field($_POST['_fund_amount']);
            $_start_date = sanitize_text_field($_POST['_start_date']);
            $_end_date = sanitize_text_field($_POST['_end_date']);
            $_issue_date = sanitize_text_field($_POST['_issue_date']);
            $_fund_currency = sanitize_text_field($_POST['_fund_currency']);
            $source_country = sanitize_text_field($_POST['source_country']);
            $parent_grant_id = sanitize_text_field($_POST['_grant_id']);

            $details =  [
                'post_title' => 'SubContract',
                'post_status' => 'publish',
                'post_content' => "Grant SubContract Details",
                'post_type' => 'grant-subcontract',
                'post_author' => get_current_user_id()
            ];

            $post_id = wp_insert_post($details);
            update_post_meta($post_id, '_principal', $principal);
            update_post_meta($post_id, '_funder', $_funder);
            update_post_meta($post_id, '_fund_amount', $_fund_amount);
            update_post_meta($post_id, '_start_date', $_start_date);
            update_post_meta($post_id, '_end_date', $_end_date);
            update_post_meta($post_id, '_issue_date', $_issue_date);
            update_post_meta($post_id, '_fund_currency', $_fund_currency);
            update_post_meta($post_id, '_source_country', $source_country);
            update_post_meta($post_id, '_institution', $institution);
            update_post_meta($post_id, '_parent_grant', $parent_grant_id);

            wp_send_json_success(['id' => $post_id]);
        } else {
            wp_send_json_error();
        }
    }

    function display_grant_details($grant_id)
    {
        $views     = NJHM_DIR . '/templates/manage-grants/';
        $cache     = NJHM_DIR . '/templates_cache/';
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        $grant_name = get_the_title($grant_id);
        $principal =  get_post_meta($grant_id, '_grant_principal', true);
        $grant_funder =   get_post_meta($grant_id, '_grant_funder', true);
        $grant_fund_amount =   get_post_meta($grant_id, '_grant_fund_amount', true);
        $grant_start_date =  get_post_meta($grant_id, '_grant_start_date', true);
        $grant_end_date =   get_post_meta($grant_id, '_grant_end_date', true);
        $maksph_dept =  get_post_meta($grant_id, '_maksph_dept', true);
        $grant_fund_currency =  get_post_meta($grant_id, '_grant_fund_currency', true);
        $grant_issue_date =   get_post_meta($grant_id, '_grant_end_date', true);
        $grant_description =  get_post_meta($grant_id, '_grant_description', true);
        $source_country =  get_post_meta($grant_id, '_grant_fund_currency', true);
        $institution =  get_post_meta($grant_id, '_subcontracts', true);

        $maksph_grant_id = get_post_meta($grant_id, '_maksph_grant_id', true);

        $data['grant_data'][] = [
            'id' => $grant_id,
            'grant_name' => $grant_name,
            'principal' => $principal,
            'grant_funder' => $grant_funder,
            'grant_fund_amount' => $grant_fund_amount,
            'grant_start_date' => $grant_start_date,
            'grant_end_date' => $grant_end_date,
            'maksph_dept' => $maksph_dept,
            'grant_fund_currency' => $grant_fund_currency,
            'grant_issue_date' => $grant_issue_date,
            'grant_description' => $grant_description,
            'source_country' => $source_country,
            'institution' => $institution,
            'maksph_grant_id' => $maksph_grant_id
        ];


        $html      = $blade->run('manage-grant-details', $data);
        return $html;
    }

    public function upload_user_membership_files()
    {
        $response = [];
        if (!empty($_FILES["file_subcontract"]["name"])) {
            $subcontract_id = sanitize_key($_POST['subcontract_id']);
            $field_title = sanitize_text_field($_POST['title_subcontract']);
            $field_title = str_replace(['/', '\\'], ' or ', $field_title);
            $field_key = sanitize_text_field($_POST['key_subcontract']);
            $upload_file = "file_subcontract";
            $this->file_uploads($field_title, $subcontract_id, $field_key, $upload_file);
        }
        if (!empty($_FILES["file_diligence"]["name"])) {
            $subcontract_id = sanitize_key($_POST['subcontract_id']);
            $field_title = sanitize_text_field($_POST['title_diligence']);
            $field_title = str_replace(['/', '\\'], ' or ', $field_title);
            $field_key = sanitize_text_field($_POST['key_diligence']);
            $upload_file = "file_diligence";
            $this->file_uploads($field_title, $subcontract_id, $field_key, $upload_file);
        }

        if (!empty($_FILES["file"]["name"])) {
            $subcontract_id = sanitize_key($_POST['subcontract_id']);
            $field_title = sanitize_text_field($_POST['title']);
            $field_title = str_replace(['/', '\\'], ' or ', $field_title);
            $field_key = sanitize_text_field($_POST['key']);
            $upload_file = "file";
            $response[] = $this->file_uploads($field_title, $subcontract_id, $field_key, $upload_file);
        }




        wp_send_json_success($response);
    }

    public function file_uploads($field_title, $subcontract_id, $field_key, $upload_file)
    {
        //Upload file to server.
        $file_name = $field_title . '-' . $subcontract_id . '.' . pathinfo($_FILES[$upload_file]["name"])['extension'];
        $file_location = $this->get_site_upload_path() . $file_name;
        move_uploaded_file($_FILES[$upload_file]['tmp_name'], $file_location);

        //Store uploaded file details.
        $url = wp_upload_dir()['baseurl'] . '/subcontract_files/' . $file_name;
        $field_value = [
            'url' => $url,
            'file_name' => $file_name
        ];

        update_post_meta($subcontract_id, $field_key, $field_value);

        return $field_value;
    }

    public function get_site_upload_path()
    {
        $upload_dir = wp_upload_dir();
        $custom_download_path = $upload_dir['basedir'] . '/subcontract_files/';
        if (is_dir($custom_download_path)) {
            return $custom_download_path;
        }
        mkdir($custom_download_path);

        return $custom_download_path;
    }


    // Add this to your custom plugin or theme's functions.php
function custom_wpschoolpress_text_buffering_start() {
    ob_start([$this,'custom_wpschoolpress_text_replace']);

    return ob_get_clean();
}

function custom_wpschoolpress_text_replace($buffer) {
    // Replace the original text with your custom text
    $buffer = str_replace('Live as if you were to die tomorrow. Learn as if you were to live forever.', 'Your custom text', $buffer);
    return $buffer;
}

}
