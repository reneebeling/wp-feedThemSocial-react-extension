<?php

/**
 * Feed Them Social - Instagram Feed Custom REST API Extension
 *
 * This file is used to provide a WP REST-API for the Instagram Feeds
 *
 * @package     Inofficial Feed Them Social ReactJS extension
 * @copyright   Copyright (c) 2021
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace feedthemsocial;

/**
 * Class Extension_FTS_Instagram_Feed_REST
 *
 * @package feedthemsocial
 */
class Extension_FTS_Instagram_Feed extends feed_them_social_functions {

    /**
     * Construct
     *
     * Instagram Feed constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

    }
    
    /**
    * This is copied code from the actual Feed them Social Code in order to work with it in a more compact and testable format
    * @param type $request
    * @return type
    */
    public function handle_insta_data($pics_count, $instagram_id, $fts_instagram_access_token_final){
         $basic_cache = 'instagram_basic_cache' . $instagram_id . '_num' . $pics_count . '';
        // This only returns the next url and a list of media ids. We then have to loop through the ids and make a call to get each ids data from the API.
        $instagram_data_array['data'] = 'https://graph.instagram.com/' . $instagram_id . '/media?limit=' . $pics_count . '&access_token=' . $fts_instagram_access_token_final;

        // First we make sure the feed is not cached already before trying to run the Instagram API.
        if (false === $this->fts_check_feed_cache_exists($basic_cache)) {
            $instagram_basic_response = $this->fts_get_feed_json($instagram_data_array);

            $instagram_basic = json_decode($instagram_basic_response['data']);

            // We loop through the media ids from the above $instagram_basic_data_array['data'] and request the info for each to create an array we can cache.
            $instagram_basic_output = (object) ['data' => []];
            foreach ($instagram_basic->data as $media) {
                $media_id = $media->id;
                $instagram_basic_data_array['data'] = 'https://graph.instagram.com/' . $media_id . '?fields=caption,id,media_url,media_type,permalink,thumbnail_url,timestamp,username,children{media_url}&access_token=' . $fts_instagram_access_token_final;
                $instagram_basic_media_response = $this->fts_get_feed_json($instagram_basic_data_array);
                $instagram_basic_media = json_decode($instagram_basic_media_response['data']);
                $instagram_basic_output->data[] = $instagram_basic_media;
            }

            $insta_data = (object) array_merge((array) $instagram_basic, (array) $instagram_basic_output);
            if (!isset($_GET['load_more_ajaxing'])) {
                $this->fts_create_feed_cache($basic_cache, $insta_data);
            }
        } else {
            $insta_data = $this->fts_get_feed_cache($basic_cache);
        }

        foreach ($insta_data->data as $instgram_data_set) {
            if (isset($instgram_data_set->username)) {
                unset($instgram_data_set->username);
            }
        }
        return $insta_data->data;
    }

    /**
     * This is a callback function that embeds a phrase in a WP_REST_Response
     * @param type $request
     * @return type
     */
    public function get_instagram_meta_and_links($pics_count) {
        $fts_instagram_access_token_final = get_option('fts_instagram_custom_api_token');
        $instagram_id = get_option('fts_instagram_custom_id');

        if (!$fts_instagram_access_token_final || !$instagram_id) {
            return new \WP_Error('I am a teapot', esc_html__('Parent Plugin access data not available.', 'wp-fts-instagram'), array('status' => 418));
        } else
            if (!method_exists ($this, 'fts_check_feed_cache_exists') ||
            !method_exists ($this, 'fts_get_feed_json') ||
            !method_exists ($this, 'fts_create_feed_cache') ||
            !method_exists ($this, 'fts_get_feed_cache')) {
            return new \WP_Error('I am a teapot', esc_html__('Parent Plugin methods changed', 'wp-fts-instagram'), array('status' => 418));
        }
        $return_value = handle_insta_data($pics_count, $instagram_id, $fts_instagram_access_token_final);
        return rest_ensure_response($return_value);
    }
}

//end class
?>
