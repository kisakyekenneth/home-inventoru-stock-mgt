<?php

/**
 * Plugin Name: Kisozi Sales
 * Description: Kisozi General Motors Spare Parts 
 * Version:     2.0.0
 * Author:      Catherine Akoth
 * Author URI:  https:catherine.akoth.com
 * Text Domain: kisozi
 */


namespace Kanzu\MakSPH;


/**
 *
 * Main Plugin class
 */
class Kanzu_MakSPH
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
     * @return Kanzu_MakSPH An instance of the class.
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
        require_once(KANZU_MAKSPH_DIR . '/vendor/autoload.php');
        require_once(KANZU_MAKSPH_DIR . '/includes/class-scripts.php');

        require_once(KANZU_MAKSPH_DIR . '/includes/class-manage-users.php');
        require_once(KANZU_MAKSPH_DIR . '/includes/class-manage-settings.php');

        require_once(KANZU_MAKSPH_DIR . '/includes/class-manage-grant.php');
        require_once(KANZU_MAKSPH_DIR . '/includes/class-manage-user-account.php');

        require_once(KANZU_MAKSPH_DIR . '/includes/class-hook-registry.php');
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
        define('KANZU_MAKSPH_DIR', __DIR__);
        define('KANZU_MAKSPH_FILE', __FILE__);
        define('KANZU_MAKSPH_URL', plugin_dir_url(__FILE__));
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
Kanzu_MakSPH::instance();
