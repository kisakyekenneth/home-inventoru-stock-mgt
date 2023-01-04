<?php

namespace Kanzu\MakSPH;

use eftec\bladeone\BladeOne;

class Scripts
{
    public function __construct()
    {
        $this->version = '0.0.0.4';
    }

    public function register_scripts()
    {
        $this->enqueue_styles();
        $this->enqueue_scripts();
        $this->localise_data();
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('jquery-ui', KANZU_MAKSPH_URL . '/assets/css/jquery-ui.css');
        wp_enqueue_style('maksph-css', KANZU_MAKSPH_URL . '/assets/css/styles.css');
        wp_enqueue_style('select2-css', KANZU_MAKSPH_URL . '/assets/css/select2.min.css');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-validate', KANZU_MAKSPH_URL . '/assets/js/jquery.validate.js');
        wp_enqueue_script('maksph-js', KANZU_MAKSPH_URL . '/assets/js/scripts.js?version=' . $this->version, ['jquery-ui-datepicker', 'jquery-ui-dialog']);
    }

    public function localise_data()
    {
        wp_localize_script('maksph-js', 'kanzuMaksph', ['ajaxUrl' => admin_url('admin-ajax.php'), 'grantDetailUrl' => get_site_url() . '']);
    }
}
