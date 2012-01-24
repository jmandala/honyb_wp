<?php
/*
Plugin Name: HonyB Integration
Plugin URI: http://www.honyb.com/integration
Description: Provides HonyB Books to WordPress sites
Version: 0.2
Author: Joshua Jacobs
Author URI: http://joshuajacobs.name
License: GPL2
*/

/**
 * Loads JavaScript for this plugin
 */
function load_javascript() {
	wp_enqueue_script('colorbox.js', plugins_url('/colorbox/colorbox/jquery.colorbox-min.js', __FILE__), array('jquery'), '1.0');
	wp_enqueue_style('colorbox.css', plugins_url('/colorbox/honyb/colorbox.css', __FILE__));
    wp_enqueue_style('honyb.css', plugins_url('/honyb.css', __FILE__));
	wp_enqueue_script('honyb', plugins_url('/honyb.js', __FILE__), array('jquery'), '1.0');
}

add_action('wp_enqueue_scripts', 'load_javascript');

/**
 * [honyb sku="9781234567890" title="The Are of War"]
 * @param $atts Array the attributes provided: sku, title
 * @return string
 */
function honyb_tag($atts)
{
    extract(shortcode_atts(array('sku' => '', 'title' => ''), $atts));

    /** @var $sku String */
    /** @var $title String */
    return "<div class='honyb'><div class='honyb-popup button orange' data-sku='{$sku}'>Buy {$title}!</div><div class='info'>Buying direct supports this website!</div></div>";
}

add_shortcode('honyb', 'honyb_tag');
