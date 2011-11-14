<?php
/*
Plugin Name: HonyB Integration
Plugin URI: http://www.honyb.com/integration
Description: Provides HonyB Books to WordPress sites
Version: 0.1
Author: Joshua Jacobs
Author URI: http://joshuajacobs.name
License: GPL2
*/

/**
 * Loads JavaScript for this plugin
 */
function load_javascript() {
	wp_enqueue_script('colorbox.js', plugins_url('/colorbox/colorbox/jquery.colorbox-min.js', __FILE__), array('jquery'), '1.0');
	wp_enqueue_style('colorbox.css', plugins_url('/colorbox/example1/colorbox.css', __FILE__));
	wp_enqueue_script('honyb', plugins_url('/honyb.js', __FILE__), array('jquery'), '1.0');
}

add_action('wp_enqueue_scripts', 'load_javascript');


// [honyb sku="9781234567890"]
function honyb_tag( $atts ) {
	extract(shortcode_atts( array('sku' => ''), $atts));

	//return "<div class='honyb-section'>Buy from Honyb <span clss='sku'>{$sku}</span></div>";
	return "<div class='honyb-popup'><a href='http://www.honyb.com/' target='_blank'>Buy from Honyb <span clss='sku'>{$sku}</span></a></div>";
}

add_shortcode('honyb', 'honyb_tag');
