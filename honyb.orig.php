<?php
/*
Plugin Name: HonyB Integration
Plugin URI: http://www.honyb.com/integration
Description: Provides HonyB Books to WordPress sites
Version: 0.3
Author: Joshua Jacobs
Author URI: http://joshuajacobs.name
License: GPL2
*/

/**
 * Loads JavaScript for this plugin
 */
function load_javascript() {
    wp_enqueue_script('honyb', plugins_url('/honyb.js', __FILE__), array('jquery'), '1.0');
}

add_action('wp_enqueue_scripts', 'load_javascript');


function honyb_head() {
    ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.honyb-embed').honyb({
            affiliate_key:'boston-review',
            width:'750px',
            height:'70%'
        });
    });
</script>
<?
}

add_action('wp_head', 'honyb_head');


function honyb_admin() {

}

add_action('admin_init', 'honyb_admin');


/**
 * [honyb sku="9781234567890" title="The Are of War"]
 * @param $atts Array the attributes provided: sku, title
 * @return string
 */
function honyb_tag($atts) {
    extract(shortcode_atts(array('sku' => '', 'float' => '', 'view' => 'product-brief'), $atts));

    /** @var $sku String */
    /** @var $float String */
    /** @var $view String */
    return "<div class='honyb-embed {$float}' data-sku='{$sku}' data-view='{$view}'></div>";
}

add_shortcode('honyb', 'honyb_tag');
