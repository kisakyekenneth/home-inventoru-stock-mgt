<?php

namespace Njhm\Church;

use eftec\bladeone\BladeOne;

class ManageUserAccount
{

    public function register_settings_section()
    {

        register_setting('general', '_kc_uipe_deactivated_account_message', 'sanitize_text_field');
        add_settings_section(
            '_kc_uipe_deactivated_account_message_section',
            esc_html__('Deactivate User Account', 'kanzucode'),
            array($this, 'settings_section_callback'),
            'general'
        );
        add_settings_field(
            '_kc_uipe_deactivated_account_message',
            esc_html__('Deactivated User Message', 'kanzucode'),
            array($this, 'settings_field_callback'),
            'general',
            '_kc_uipe_deactivated_account_message_section',
            array('label_for' => '_kc_uipe_deactivated_account_message')
        );
    }

    public function settings_section_callback($args)
    {
        echo '';
    }

    public function settings_field_callback($args)
    {
        $form_name = 'deactivated-user-message';
        $views     = NJHM_DIR . '/templates/user-account/';
        $cache     = NJHM_DIR . '/templates_cache/';
        $data      = [
            'label' => esc_attr($args['label_for']),
            'value' => esc_attr(get_option('_kc_uipe_deactivated_account_message')),
            'description' => 'Please enter message to show on login screen in case of Deactivated account',
        ];
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $html      = $blade->run($form_name, $data);
        echo $html;
    }

    public function check_user_blocked($user)
    {
        if (is_wp_error($user)) {
            return $user;
        }
        if (is_object($user) && isset($user->ID) && 'yes' === get_user_meta((int)$user->ID, sanitize_key('_kc_uipe_user_deactivated'), true)) {
            $error_message = get_option('_kc_uipe_deactivated_account_message');
            return new \WP_Error('deactivated', ($error_message) ? $error_message : __('Your account is Deactivated!', 'kanzucode'));
        } else {
            return $user;
        }
    }

    public function register_bulk_action($actions)
    {
        $actions['deactivate'] = esc_html__('Deactivate User', 'kanzucode');
        $actions['activate'] = esc_html__('Activate User', 'kanzucode');
        return $actions;
    }

    public function register_column_header($columns)
    {
        return array_merge(
            $columns,
            array('deactivated' => esc_html__('User Status', 'kanzucode'))
        );
    }

    public function output_column($output, $column_name, $user_id)
    {

        if ('deactivated' !== $column_name) return $output;
        $locked = get_user_meta($user_id, sanitize_key('_kc_uipe_user_deactivated'), true);
        return ('yes' === $locked) ? __('In Active', 'kanzucode') : __('Active', 'kanzucode');
    }

    public function process_lock_action($sendback, $current_action, $user_ids)
    {

        if ('deactivate' === $current_action) {
            $current_user_id = get_current_user_id();
            foreach ($user_ids as $user_id) {
                if ($user_id == $current_user_id) continue;
                update_user_meta((int)$user_id, sanitize_key('_kc_uipe_user_deactivated'), 'yes');
                update_user_meta($user_id, '_kc_uipe_is_user_verified', 'yes');
            }
        } elseif ('activate' === $current_action) {
            foreach ($user_ids as $user_id) {
                update_user_meta((int)$user_id, sanitize_key('_kc_uipe_user_deactivated'), '');
                update_user_meta($user_id, '_kc_uipe_is_user_verified', 'yes');
            }
        }
        return $sendback;
    }

    function show_user_account_status($user)
    {

        $form_name = 'user-account-status';
        $views     = NJHM_DIR . '/templates/user-account/';
        $cache     = NJHM_DIR . '/templates_cache/';
        $data      = ['_kc_uipe_user_deactivated' => get_user_meta($user->ID, sanitize_key('_kc_uipe_user_deactivated'), true)];
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $html      = $blade->run($form_name, $data);
        echo $html;
    }

    function update_user_account_status($user_id)
    {
        $_kc_uipe_user_deactivated = $_POST['kc-user-deactivated'];
        update_user_meta($user_id, '_kc_uipe_user_deactivated', $_kc_uipe_user_deactivated);
        update_user_meta($user_id, '_kc_uipe_is_user_verified', 'yes');
    }

    function set_email_content_type()
    {
        return "text/html";
    }

    function new_user_notification_email($wp_new_user_notification_email, $user, $blogname)
    {
        $form_name           = 'activate-account-email';
        $views               = NJHM_DIR . '/templates/user-account/';
        $cache               = NJHM_DIR . '/templates_cache/';
        $key                 = get_password_reset_key($user);
        $data                = [
            'site_name' => __(get_bloginfo('name'), 'kanzucode'),
            'site_description' => __(get_bloginfo('description'), 'kanzucode'),
            'site_url' =>  __(get_bloginfo('site_url'), 'kanzucode'),
            'username' => __($user->user_login, 'kanzucode'),
            'email' => __($user->user_email, 'kanzucode'),
            'name' => __($user->first_name . ' ' . $user->last_name, 'kanzucode'),
            'login_url' => wp_login_url(),
            'password_reset_link' => network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login')
        ];
        update_user_meta($user->ID, '_kc_uipe_user_deactivated', 'yes');
        update_user_meta($user->ID, '_kc_uipe_is_user_verified', 'no');
        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $html      = $blade->run($form_name, $data);
        $wp_new_user_notification_email['subject'] = __('Account Activation', 'kanzucode');
        $wp_new_user_notification_email['message'] = $html;
        return $wp_new_user_notification_email;
    }

    function after_password_reset_notification($user, $new_pass)
    {

        $form_name           = 'activated-account-email';
        $views               = NJHM_DIR . '/templates/user-account/';
        $cache               = NJHM_DIR . '/templates_cache/';
        $data                = [
            'site_name' => __(get_bloginfo('name'), 'kanzucode'),
            'site_description' => __(get_bloginfo('description'), 'kanzucode'),
            'site_url' =>  __(get_bloginfo('site_url'), 'kanzucode'),
            'username' => __($user->user_login, 'kanzucode'),
            'email' => __($user->user_email, 'kanzucode'),
            'name' => __($user->first_name . ' ' . $user->last_name, 'kanzucode'),
            'login_url' => wp_login_url(),
            'admin_email' => get_option('admin_email')
        ];

        $blade     = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        $html      = $blade->run($form_name, $data);
        if (get_user_meta($user->ID, '_kc_uipe_is_user_verified', true) != 'yes') {
            update_user_meta($user->ID, '_kc_uipe_user_deactivated', ' ');
            update_user_meta($user->ID, '_kc_uipe_is_user_verified', 'yes');
            wp_mail(
                $user->user_email,
                wp_specialchars_decode(sprintf(__('MakSPH Membership Account', 'kanzucode'), get_bloginfo('name'))),
                $html,
                ''
            );
        }
    }

    function user_complete_profile()
    {

        return get_home_url() . '/admin-dashboard';
    }
}
