<?php
/*
Plugin Name: Honyb Embed
Plugin URI: http://www.honyb.com
Description: Provides shortcodes for embedding honyb.com books
Version: 0.4
Author: Joshua Jacobs
Author URI: http://joshuajacobs.name
License: GPL2
*/

// ------------------------------------------------------------------------
// REQUIRE MINIMUM VERSION OF WORDPRESS:                                               
// ------------------------------------------------------------------------
// THIS IS USEFUL IF YOU REQUIRE A MINIMUM VERSION OF WORDPRESS TO RUN YOUR
// PLUGIN. IN THIS PLUGIN THE WP_EDITOR() FUNCTION REQUIRES WORDPRESS 3.3 
// OR ABOVE. ANYTHING LESS SHOWS A WARNING AND THE PLUGIN IS DEACTIVATED.                    
// ------------------------------------------------------------------------
function requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.3", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'requires_wordpress_version' );

// ------------------------------------------------------------------------
// PLUGIN PREFIX:                                                          
// ------------------------------------------------------------------------
// A PREFIX IS USED TO AVOID CONFLICTS WITH EXISTING PLUGIN FUNCTION NAMES.
// WHEN CREATING A NEW PLUGIN, CHANGE THE PREFIX AND USE YOUR TEXT EDITORS 
// SEARCH/REPLACE FUNCTION TO RENAME THEM ALL QUICKLY.
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------
// HOOKS TO SETUP DEFAULT PLUGIN OPTIONS, HANDLE CLEAN-UP OF OPTIONS WHEN
// PLUGIN IS DEACTIVATED AND DELETED, INITIALISE PLUGIN, ADD OPTIONS PAGE.
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'honyb_add_defaults');
register_uninstall_hook(__FILE__, 'honyb_delete_plugin_options');
add_action('admin_init', 'honyb_init' );
add_action('admin_menu', 'honyb_add_options_page');
add_filter( 'plugin_action_links', 'honyb_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'honyb_delete_plugin_options')
// --------------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE USER DEACTIVATES AND DELETES THE PLUGIN. IT SIMPLY DELETES
// THE PLUGIN OPTIONS DB ENTRY (WHICH IS AN ARRAY STORING ALL THE PLUGIN OPTIONS).
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function honyb_delete_plugin_options() {
	delete_option('honyb_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'honyb_add_defaults')
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE PLUGIN IS ACTIVATED. IF THERE ARE NO THEME OPTIONS
// CURRENTLY SET, OR THE USER HAS SELECTED THE CHECKBOX TO RESET OPTIONS TO THEIR
// DEFAULTS THEN THE OPTIONS ARE SET/RESET.
//
// OTHERWISE, THE PLUGIN OPTIONS REMAIN UNCHANGED.
// ------------------------------------------------------------------------------

// Define default option settings
function honyb_add_defaults() {
	$tmp = get_option('honyb_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('honyb_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(   "affiliate_key" => "",
						"popup_width" => "750px",
						"popup_height" => "70%"
		);
		update_option('honyb_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'honyb_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function honyb_init(){
	register_setting( 'honyb_plugin_options', 'honyb_options', 'honyb_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'honyb_add_options_page');
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_menu' HOOK FIRES, AND ADDS A NEW OPTIONS
// PAGE FOR YOUR PLUGIN TO THE SETTINGS MENU.
// ------------------------------------------------------------------------------

// Add menu page
function honyb_add_options_page() {
	add_options_page(__('Honyb Setting'), __('Honyb Settings'), 'manage_options', __FILE__, 'honyb_render_form');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form
function honyb_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Honyb Embed Settings') ?></h2>
		<p><?php _e('Use the settings on this page to customize the honyb embed experience.')?></p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('honyb_plugin_options'); ?>
			<?php $options = get_option('honyb_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">

				<tr>
					<th scope="row"><?php _e('Enter Your Affiliate Key') ?></th>
					<td>
						<input type="text" size="57" name="honyb_options[affiliate_key]" value="<?php echo $options['affiliate_key']; ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Popup Width') ?></th>
					<td>
						<input type="text" size="57" name="honyb_options[popup_width]" value="<?php echo $options['popup_width']; ?>" />
					</td>
				</tr>

				<tr>
					<th scope="row"><?php _e('Popup Height') ?></th>
					<td>
						<input type="text" size="57" name="honyb_options[popup_height]" value="<?php echo $options['popup_height']; ?>" />
					</td>
				</tr>

			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function honyb_validate_options($input) {
	$input['affiliate_key'] =  wp_filter_nohtml_kses($input['affiliate_key']);
	$input['popup_width'] =  wp_filter_nohtml_kses($input['popup_width']);
	$input['popup_height'] =  wp_filter_nohtml_kses($input['popup_height']);
	return $input;
}

// Display a Settings link on the main Plugins page
function honyb_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$honyb_links = '<a href="'.get_admin_url().'options-general.php?page='. $file . '">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $honyb_links );
	}

	return $links;
}

/**
 * Loads JavaScript for this plugin
 */
function load_javascript() {
    wp_enqueue_script('honyb', plugins_url('/honyb.js', __FILE__), array('jquery'), '1.0');
}

add_action('wp_enqueue_scripts', 'load_javascript');


/**
 * Initializes the honyb embed
 */
function honyb_head() {
    $options = get_option('honyb_options');
    ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.honyb-embed').honyb({
            affiliate_key:'<?php $options['affiliate_key'] ?>',
            width:'<?php $options['popup_width'] ?>',
            height:'<?php $options['popup_height'] ?>'
        });
    });
</script>
<?
}

add_action('wp_head', 'honyb_head');

/**
 * Honyb Tag provides usage for embedding honyb books.
 *
 * [honyb sku="9781234567890" float="left" view="product-brief"]
 *
 * @param $atts Array the attributes provided: sku, float, view
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
