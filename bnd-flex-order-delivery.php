<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Online Ordering and Delivery Platform
 * Plugin URI:        https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/
 * Description:       This plugin provides online menu for restaurants which are on clover platform.
 * Version:           2.0.0
 * Author:            BuyNowDepot
 * Author URI:        https://buynowdepot.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bnd-flex-order-delivery
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
define( 'BUYNOWDEPOT_FLEX_ORDER_DELIVERY_VERSION', '2.0.0' );

if ( ! defined( 'BUYNOWDEPOT_PLUGIN_FILE' ) ) {
    define( 'BUYNOWDEPOT_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'BUYNOWDEPOT_PLUGIN_DIR' ) ) {
    define( 'BUYNOWDEPOT_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'BUYNOWDEPOT_PLUGIN_URL' ) ) {
    define( 'BUYNOWDEPOT_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'BUYNOWDEPOT_TEMPLATE_DIR' ) ) {
    define( 'BUYNOWDEPOT_TEMPLATE_DIR',  BUYNOWDEPOT_PLUGIN_DIR . '/templates/' );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bnd-flex-order-delivery.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bnd-flex-order-delivery-activator.php
 */
function activate_bnd_flex_order_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bnd-flex-order-delivery-activator.php';
	$plugin = new Bnd_Flex_Order_Delivery();
	$activator = new Bnd_Flex_Order_Delivery_Activator($plugin);
	$activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bnd-flex-order-delivery-deactivator.php
 */
function deactivate_bnd_flex_order_delivery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bnd-flex-order-delivery-deactivator.php';
	Bnd_Flex_Order_Delivery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bnd_flex_order_delivery' );
register_deactivation_hook( __FILE__, 'deactivate_bnd_flex_order_delivery' );

register_theme_directory( dirname( __FILE__ ) . '/themes' );


function buynowdepot_get_theme_url() {
    return BUYNOWDEPOT_PLUGIN_URL."templates/flexmenu";
}

/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @since 1.0
 * @global Array of all the plugin Options
 * @return mixed
 */
function buynowdepot_get_option( $key = '', $default = false ) {
    $bnd_options = (array)get_option( 'bnd_settings' );
    $value = ! empty( $bnd_options[ $key ] ) ? $bnd_options[ $key ] : $default;
    return $value;
}

function buynowdepot_get_message($code, $params=null) {
    return Bnd_Flex_Order_Delivery_Container::instance()->get_message($code, $params);
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bnd_flex_order_delivery() {

	$plugin = new Bnd_Flex_Order_Delivery();
	$plugin->run();
}

run_bnd_flex_order_delivery();
