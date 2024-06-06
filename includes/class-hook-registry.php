<?php

namespace Njhm\Church;

/**
 * Register hooks used in the plugin
 */
class Hook_Registry
{

    public function __construct()
    {
        $this->register_hooks();
    }

    public function register_hooks()
    {
        $scripts = new Scripts();
        $membership_registration = new Manage_Users();
        $manage_user_account      = new ManageUserAccount();
        $manage_settings = new Manage_Settings();
        $manage_choir = new ManageChoir();
        //Enqueue Styles and Scripts
        add_action('wp_enqueue_scripts', [$scripts, 'register_scripts']);

        // Add filter to redirect user to complete thier Profile after registration.
        add_filter('login_redirect', [$manage_user_account, 'user_complete_profile']);

        //Create dashboad shortcode.
        add_shortcode('new_member_register', [$manage_choir, 'new_members']);

        //Capture new grant details
        add_action('wp_ajax_njhm_new_choir_member', [$manage_choir, 'njhm_new_choir_member']);

        //Call to Update and Delete grant project
        add_action('wp_ajax_update_member_data', [$manage_choir, 'update_member_data']);
        add_action('wp_ajax_delete_grant_details', [$manage_choir, 'delete_grant_data']);

        //Call to download excel file
        add_action('wp_ajax_kc_download_grants_data', [$manage_choir, 'download_grant_registry_report']);

        //Manage file upload
        add_action('wp_ajax_kc_maksph_upload_files', [$manage_choir, 'upload_user_membership_files']);

        // Save subcontract Details
        add_action('wp_ajax_maksph_subcontract_grant', [$manage_choir, 'save_subcontract']);

        // Update subcontract Details
        add_action('wp_ajax_maksph_update_subcontracts', [$manage_choir, 'update_subcontract_details']);

        //Register Posts
        add_action('init', [$manage_settings, 'register_posts']);
    }

    /**
     * Fires once an attachment has been added.
     *
     * @param int $post_ID Attachment ID.
     */
    public function action_add_attachment(int $post_ID): void
    {
    }

    /**
     * Fires after WordPress has finished loading but before any headers are sent.
     *
     */
    public function action_init(): void
    {
    }
}

new Hook_Registry();
