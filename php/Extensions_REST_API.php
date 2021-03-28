<?php

/**
 * Custom REST API Extension
 *
 * This file is used to provide WP REST-API return values for extension exceptions and checks
 *
 * @package     Inofficial Feed Them Social ReactJS extension
 * @copyright   Copyright (c) 2021
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

/**
 */
class Extensions_REST_API extends fts_React_extension {

    /**
     * Construct
     *
     *
     * @since 1.0.0
     */
    public function __construct($parentFolder) {
        $this->plugin = new stdClass;
        $this->plugin->parentFolder = $parentFolder;
        
        if (class_exists('Feed_Them_Social')) {
            // Feed Them Social Custom Extension
            include_once $this->plugin->parentFolder. self::$base_root .'Extension_FTS_Instagram_Feed.php';
            $this->extension_instagram_feed = new feedthemsocial\Extension_FTS_Instagram_Feed();
        }
        
        add_action( 'wp_enqueue_scripts', array($this,'wp_fts_extension_instagram_feed_everywhere' ));
        
        // Add REST hook
        add_action('rest_api_init', array($this, 'prefix_register_fts_data_routes'));
    }
    
    public function wp_fts_extension_instagram_feed_everywhere(){
        $script_name = "social-media-carousel";
                                wp_enqueue_script(
        /* Name of the script*/         $script_name,            
        /* Full URL of the script*/     plugins_url(parent::$plugin_script_Url . $script_name . parent::$script_file_ending, __FILE__),      
        /* optional script depends.*/   array(),                               
        /* script version */            $this->get_my_file_version(self::$base_root . parent::$plugin_script_Url . $script_name . parent::$script_file_ending),
        /* enqueue $in_footer. */       true);
        
        // Simple nonce Cookie for instgram Images
        if (class_exists('Feed_Them_Social')) {
            wp_localize_script($script_name, 'FTSInstaExtension', array(
                    'root' => esc_url_raw( rest_url('instagram/data/v1/meta-and-links')),
                    'nonce' => wp_create_nonce( 'wp_rest' )
                )
            );
        }
    }

    /**
     * 
     * Public REST APIs for Instagram Data
     * https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/
     */

    /**
     * This is a callback function that embeds a phrase in a WP_REST_Response
     * @param type $request
     * @return type
     */
    public function get_endpoint_instagram_meta_and_links($request) {
        // Determine if there is a nonce.
        $nonce = null;
	if ( isset( $_REQUEST['_wpnonce'] ) ) {
		$nonce = $_REQUEST['_wpnonce'];
	}   
        
        // Check and validate the nonce.
	$result = wp_verify_nonce( $nonce, 'wp_rest' );
        if ( ! $result ) {
            return new WP_Error( 'rest_cookie_invalid_nonce', __( 'Cookie nonce is invalid' ), array( 'status' => 403 ) );
	}
        
        if(!isset($this->extension_instagram_feed)) {
            return new \WP_Error('I am a teapot', esc_html__('Parent Plugin not available.', 'wp-fts-instagram'), array('status' => 418));
        }
        
        $pics_count = 12;
        if (isset($request['filter'])) {
            $pics_count = $request['filter'];
        }
        
        return $this->extension_instagram_feed->get_instagram_meta_and_links($pics_count);
    }

    /**
     * This function contains the arguments for available filters of instagram data endpoints
     * => number of images
     * @return string
     */
    private function prefix_get_number_of_pics() {
        $args = array();
        // schema for the filter argument
        $args['filter'] = array(
            'description' => esc_html__('The filter parameter is used to set the number of requested images', 'wp-lj-instagram'),
            'type' => 'integer',
            'enum' => array('pic-count'),
        );
        return $args;
    }

    /**
     * HTTP GET
     * 1. This function is used to register the routes for instagram data endpoints
     *      returns json with instagram data
     * 
     * 2 .This function is used to register the routes for additional svg map data endpoints
     *      returns json with all active countries and corresponding links 
     *  
     * 
     */
    public function prefix_register_fts_data_routes() {        
        if (class_exists('Feed_Them_Social')) {
            register_rest_route('instagram/data/v1', '/meta-and-links', array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array($this, 'get_endpoint_instagram_meta_and_links'),
                'args' => $this->prefix_get_number_of_pics(),
                /*'validate_callback' => array($this, 'prefix_filter_arg_validate_callback'),*/
            ));
        }        
    }
}

//end class
?>
