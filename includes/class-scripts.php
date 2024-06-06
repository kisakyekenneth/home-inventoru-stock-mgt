<?php

namespace Njhm\Church;

use eftec\bladeone\BladeOne;

class Scripts
{
    private $version; // Declare the $version property
    public function __construct()
    {
        // $this->version = mt_rand(1, 9999);
        $this->version = '1.2.0';
    }

    public function register_scripts()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();
        $this->localise_data();
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('jquery-ui', NJHM_URL . '/assets/css/jquery-ui.css');
        wp_enqueue_style('maksph-css', NJHM_URL . '/assets/css/styles.css?version=' . $this->version);
        wp_enqueue_style('select2-css', NJHM_URL . '/assets/css/select2.min.css');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-validate', NJHM_URL . '/assets/js/jquery.validate.js');
        wp_enqueue_script('njhm-js', NJHM_URL . '/assets/js/scripts.js?version=' . $this->version, ['jquery-ui-datepicker', 'jquery-ui-dialog']);
    }

    public function localise_data()
    {
        wp_localize_script('njhm-js', 'newJHM', ['ajaxUrl' => admin_url('admin-ajax.php'), 'nhjmURL' => get_site_url() . '']);
    }
}
