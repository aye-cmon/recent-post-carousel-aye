<?php
/*
 * PlugIn Name: Recent Post Carousel
 * Description: A carousel to display recent posts.
 * Author: Aye Chan Mon
 * Version: 1.0
 */

defined('ABSPATH') || exit; 

add_action('wp_enqueue_scripts', 'recent_post_carousel_plugin_scripts');

//Enqueue CSS and JS on the front end
if (!function_exists('recent_post_carousel_plugin_scripts')):
    function recent_post_carousel_plugin_scripts() {
        wp_enqueue_style('recent-post-carousel-style', plugin_dir_url(__FILE__) . 'assets/css/recent-post-carousel.css');
        wp_enqueue_script('recent-post-carousel-script', plugin_dir_url(__FILE__) . 'assets/js/recent-post-carousel.js', array(), 1.0, true);
    }
endif;
?>