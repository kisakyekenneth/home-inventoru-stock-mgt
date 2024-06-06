<?php

/**
 * Plugin Name: NJHM Portal plugin
 * Description: New Jerusalem Healing Ministries Portal
 * Version:     1.0.0
 * Author:      Kenneth Kisakye
 * Author URI:  https:kennethkisakye.com
 * Text Domain: njhm-portal-plugin
 */


namespace Njhm\Church;


/**
 *
 * Main Plugin class
 */
class New_Jerusalem_Hm
{

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var $_instance object The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return New_Jerusalem_Hm An instance of the class.
     * @since 1.2.0
     * @access public
     *
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Include Plugin files
     *
     * Register Plugin Required Files
     *
     * @access public
     */
    public function register_includes()
    {
        require_once(NJHM_DIR . '/vendor/autoload.php');
        require_once(NJHM_DIR . '/includes/class-scripts.php');

        require_once(NJHM_DIR . '/includes/class-manage-users.php');
        require_once(NJHM_DIR . '/includes/class-manage-settings.php');

        require_once(NJHM_DIR . '/includes/class-manage-choir.php');
        require_once(NJHM_DIR . '/includes/class-manage-user-account.php');

        require_once(NJHM_DIR . '/includes/class-hook-registry.php');
    }

    /**
     * Plugin Constants
     *
     * Register plugin required constants
     *
     * @access public
     */
    function define_constants()
    {
        define('NJHM_DIR', __DIR__);
        define('NJHM_FILE', __FILE__);
        define('NJHM_URL', plugin_dir_url(__FILE__));
    }

    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @access public
     */
    public function __construct()
    {

        //Define Constants
        $this->define_constants();

        //Register Includes
        $this->register_includes();
    }
}

// Instantiate Plugin Class
New_Jerusalem_Hm::instance();
