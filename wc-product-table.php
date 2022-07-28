<?php

/**
 * Plugin Name:       Product Table for WooCommerce
 * Plugin URI:        https://woobuddies.com/plugins
 * Description:       Product Table for WooCommerce is a plugin that works with the most popular WooCommerce plugin, it give users to buy multiple products at a time without visiting separate product pages. Product Table for WooCommerce is a simple wordpress plugin. The plugin provides a Shortcode for displaying all products in a table where people can buy multiple products at a time. All settings are available inside the admin area.
 * Version:           1.0.0
 * Requires at least: 5.5
 * Requires PHP:      7.2
 * Author:            Woobuddies
 * Author URI:        https://woobuddies.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-product-table
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_PRODUCT_TABLE_VERSION', '1.0.0' );

function wptcd_load_textdomain() {
	load_plugin_textdomain( 'wc-product-table', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", "wptcd_load_textdomain" );

// Enqueue Front-end scripts
add_action( 'wp_enqueue_scripts', 'wptcd_enqueue_scripts', 99 );
function wptcd_enqueue_scripts() {
	// Load CSS
	wp_enqueue_style( 'wptcd-bootstrap-css', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'wptcd-dataTables-css', plugins_url( 'assets/css/jquery.dataTables.min.css', __FILE__ ) );
	wp_enqueue_style( 'wptcd-styles', plugins_url( 'assets/css/styles.css', __FILE__ ), '', time() );
	
	// Load JS
	wp_enqueue_script( 'wptcd-popper-js', plugins_url( 'assets/js/popper.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
	wp_enqueue_script( 'wptcd-bootstrap-js', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
	wp_enqueue_script( 'wptcd-datatables-js', plugins_url( 'assets/js/jquery.dataTables.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
	wp_enqueue_script( 'wptcd-add-to-cart-js', plugins_url( 'assets/js/wptcd-add-to-cart.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'wptcd-add-to-cart-js', 
		'wptcd_ajax_datas', 
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) 
	);
}

// Enqueue back-end scripts
add_action( 'admin_enqueue_scripts', 'wptcd_admin_enqueue_scripts', 99 );
function wptcd_admin_enqueue_scripts() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'wptcd-jquery-ui-styles', plugins_url( 'includes/admin/assets/css/jquery-ui.css', __FILE__ ), array(), '1.13.1' );
	wp_enqueue_style( 'wptcd-admin-styles', plugins_url( 'includes/admin/assets/css/styles.css', __FILE__ ) );
	wp_enqueue_script("jquery-ui-tabs");
	wp_enqueue_script( 'wptcd-custom-script', plugins_url( 'includes/admin/assets/js/script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	wp_localize_script(
		'wptcd-custom-script', 
		'wptcd_admin_datas', 
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) 
	);
}

/**
 * Including plugin files
 */
require plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-handling.php';
require plugin_dir_path( __FILE__ ) . 'includes/admin/admin-menu-page.php';

// Default plugin options
function wptcd_set_default_options(){
	$wptcd_settings_array 							= [];
	// Table design
	$wptcd_settings_array['tbl_design_type'] 		= 'default';
	// Table content
	$wptcd_settings_array['show_quick_view'] 		= 'no';
	$wptcd_settings_array['show_review_column'] 	= 'no';
	// Table controls
	$wptcd_settings_array['rows_per_page'] 			= 8;
	$wptcd_settings_array['add_to_cart_btn_title'] 	= 'Add to cart';
	$wptcd_settings_array['add_to_cart_btn_color'] 	= '#17a2b8';
	$wptcd_settings_array['show_search_box'] 		= 'no';
	$wptcd_settings_array['show_reset_btn'] 		= 'no';
	$wptcd_settings_array['show_mini_cart'] 		= 'no';
	// Updating default settings
	update_option( 'wptcd_settings_datas', $wptcd_settings_array );
}

/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, 'wptcd_plugin_activation_func' );
if ( ! function_exists( 'wptcd_plugin_activation_func' ) ) {
	function wptcd_plugin_activation_func() {
		// Saving our plugin current version
		add_option( "wc_product_table_version", WC_PRODUCT_TABLE_VERSION );

		// Set default table options
		wptcd_set_default_options();
	}
}

?>