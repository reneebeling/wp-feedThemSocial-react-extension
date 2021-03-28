<?php
/**
 * @package Inofficial Feed Them Social ReactJS extension
 * @version 1.0.0
 */
/*
  Plugin Name: Feed Them Social ReactJS extensions
  Plugin URI: https://github.com/reneebeling/wp-react-social-media-carousel
  Description: Used to display social media data fetched with [Feed Them Social](https://wordpress.org/plugins/feed-them-social/) backend and a carousel based on React and 
  Author: Rene Ebeling
  Version: 0.1.0
  Author URI: https://github.com/reneebeling
 */

/**
 * No access, return
 */
if (!defined('ABSPATH')) {
    die(-1);
}

/** Display verbose errors */
if ( ! defined( 'IMPORT_DEBUG' ) ) {
	define( 'IMPORT_DEBUG', WP_DEBUG );
}

/**
 *  Main Plugin Class
 */
class fts_React_extension {

    static $base_root = '/plugin-extensions/';
    static $plugin_script_Url = '/js/';
    static $script_file_ending = '.js';    
    /**
     * Class Constructor
     */
    public function __construct() {
        // Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name = 'wp-feedThemSocial-react-extension'; // Plugin Folder
        $this->plugin->displayName = 'Feed Them Social ReactJS extensions'; // Plugin Name
        $this->plugin->version = '1.0.0';
        $this->plugin->folder = plugin_dir_path(__FILE__);
        $this->plugin->url = plugin_dir_url(__FILE__);
        
        include_once $this->plugin->folder . self::$base_root . 'Extensions_REST_API.php';
        
        if (class_exists('Extensions_REST_API')) {
            // Custom REST API Extensions            
            $this->_Extensions_REST_API= new Extensions_REST_API($this->plugin->folder);
        }
    }    
    
    /**
     * Get the current version of the script based on the file time
     * @param type $relative_file_path
     * @return type
     */
    public function get_my_file_version($relative_file_path){
        return date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . $relative_file_path));
    }

    /**
     * Registers the plugin with WordPress.
     */
    public static function register()
    {
        $plugin = new self();
    }

}

/**
 * Register, if class exists based on:
 * https://carlalexander.ca/singletons-in-wordpress/
 */
if (class_exists('fts_React_extension')) {
    fts_React_extension::register();
}
