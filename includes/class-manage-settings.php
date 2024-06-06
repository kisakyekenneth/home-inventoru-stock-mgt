<?php

namespace Njhm\Church;

class Manage_Settings
{
    public function register_posts()
    {
        register_post_type('kisozi-sales', array(
            'show_in_rest' => true,
            'supports' => array('title', 'editor'),
            'rewrite' => array('slug' => 'grants'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'kisozi_Sales',
                'add_new_item' => 'Add New sales',
                'edit_item' => 'Edit Sale',
                'all_items' => 'All Sales',
                'singular_name' => 'KisoziSale'
            ),
            'menu_icon' => 'dashicons-awards'
        ));

        register_post_type('customer-cpt', array(
            'show_in_rest' => true,
            'supports' => array('title', 'editor'),
            'rewrite' => array('slug' => 'customers'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Customers',
                'add_new_item' => 'Add New client',
                'edit_item' => 'Edit client',
                'all_items' => 'All clients',
                'singular_name' => 'client'
            ),
            'menu_icon' => 'dashicons-schedule'
        ));
    }
}
