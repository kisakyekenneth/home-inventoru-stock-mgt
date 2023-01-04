<?php

namespace Kanzu\MakSPH;

use eftec\bladeone\BladeOne;
use Shuchkin\SimpleXLSXGen;

class ManageGrants
{
    function get_user_reports()
    {
        $views     = KANZU_MAKSPH_DIR . '/templates/manage-grants/';
        $cache     = KANZU_MAKSPH_DIR . '/templates_cache/';
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


            $html      = $blade->run('manage-grant-details', $data);
        } else {
            $data['sales'] = [];

            $args = [
                'numberposts' => -1,
                'post_type' => 'kisozi-sales',
            ];

            $sales = get_posts($args);

            foreach ($sales as $key => $sale) {
                $sales_id = $sale->ID;
                $client_telephone =    get_post_meta($sale->ID, '_client_telephone', true);
                $particular =  get_post_meta($sale->ID, '_particular', true);
                $rate =  get_post_meta($sale->ID, '_rate', true);
                $amount_paid = get_post_meta($sale->ID, '_amount_paid', true);
                $quantity =   get_post_meta($sale->ID, '_quantity', true);
                $client_name =  get_post_field('post_title', $sale->ID);
                $sale_date = get_post_meta($sale->ID, '_sale_date', true);

                $data['sales'][] = [
                    'id' => $sales_id,
                    'client_name' => $client_name,
                    'client_telephone' => $client_telephone,
                    'particular' => $particular,
                    'rate' => $rate,
                    'quantity' => $quantity,
                    'amount_paid' => $amount_paid,
                    'date' => $sale_date

                ];
            }
            $html      = $blade->run('manage-new-grants', $data);
        }

        echo $html;
    }

    function save_new_sales()
    {
        if (wp_verify_nonce($_POST['maksph_new_grant_nonce_field'], 'maksph_new_grant_nonce')) {
            $client_name = sanitize_text_field($_POST['client_name']);
            $client_telephone = sanitize_text_field($_POST['client_telephone']);
            $particular = sanitize_text_field($_POST['particular']);
            $rate = sanitize_text_field($_POST['rate']);
            $amount_paid = sanitize_text_field($_POST['amount_paid']);
            $quantity = sanitize_text_field($_POST['quantity']);
            $total_price = $quantity * $rate;
            $balance = $total_price - $amount_paid;

            $details =  [
                'post_title' => $client_name,
                'post_status' => 'publish',
                'post_content' => $client_name .  ' Details',
                'post_type' => 'kisozi-sales',
                'post_author' => get_current_user_id()
            ];

            $post_id = wp_insert_post($details);
            update_post_meta($post_id, '_client_telephone', $client_telephone);
            update_post_meta($post_id, '_particular', $particular);
            update_post_meta($post_id, '_rate', $rate);
            update_post_meta($post_id, '_amount_paid', $amount_paid);
            update_post_meta($post_id, '_quantity', $quantity);
            update_post_meta($post_id, '_total_price', $total_price);
            update_post_meta($post_id, '_balance', $balance);
            update_post_meta($post_id, '_sale_date', date('d-m-Y'));

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    function update_grant_details()
    {
        if (wp_verify_nonce($_POST['maksph_update_grant_nonce_field'], 'maksph_update_grant_nonce')) {
            $client_name = sanitize_text_field($_POST['client_name']);
            $client_telephone = sanitize_text_field($_POST['client_telephone']);
            $particular = sanitize_text_field($_POST['particular']);
            $rate = sanitize_text_field($_POST['rate']);
            $amount_paid = sanitize_text_field($_POST['amount_paid']);
            $quantity = sanitize_text_field($_POST['quantity']);

            $sales_id = sanitize_text_field($_POST['grant_id']);

            $details =  [
                'ID'           => $sales_id,
                'post_title' => $client_name,
                'post_content' => $client_name,
            ];

            wp_update_post($details);
            update_post_meta($sales_id, '_client_telephone', $client_telephone);
            update_post_meta($sales_id, '_particular', $particular);
            update_post_meta($sales_id, '_rate', $rate);
            update_post_meta($sales_id, '_amount_paid', $amount_paid);
            update_post_meta($sales_id, '_quantity', $quantity);

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

    function update_subcontract_details()
    {
        if (wp_verify_nonce($_POST['maksph_update_subcontract_nonce_field'], 'maksph_update_subcontract_nonce')) {
            $institution = sanitize_text_field($_POST['institution']);
            $principal = sanitize_text_field($_POST['principal']);
            $_funder = sanitize_text_field($_POST['_funder']);
            $_fund_amount = sanitize_text_field($_POST['_fund_amount']);
            $_start_date = sanitize_text_field($_POST['_start_date']);
            $_end_date = sanitize_text_field($_POST['_end_date']);
            $_issue_date = sanitize_text_field($_POST['_issue_date']);
            $_fund_currency = sanitize_text_field($_POST['_fund_currency']);
            $source_country = sanitize_text_field($_POST['source_country']);
            $subcontract_id = sanitize_text_field($_POST['subcontract_id']);

            update_post_meta($subcontract_id, '_principal', $principal);
            update_post_meta($subcontract_id, '_funder', $_funder);
            update_post_meta($subcontract_id, '_fund_amount', $_fund_amount);
            update_post_meta($subcontract_id, '_start_date', $_start_date);
            update_post_meta($subcontract_id, '_end_date', $_end_date);
            update_post_meta($subcontract_id, '_issue_date', $_issue_date);
            update_post_meta($subcontract_id, '_fund_currency', $_fund_currency);
            update_post_meta($subcontract_id, '_source_country', $source_country);
            update_post_meta($subcontract_id, '_institution', $institution);

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    function display_grant_details($grant_id)
    {
        $views     = KANZU_MAKSPH_DIR . '/templates/manage-grants/';
        $cache     = KANZU_MAKSPH_DIR . '/templates_cache/';
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
}
