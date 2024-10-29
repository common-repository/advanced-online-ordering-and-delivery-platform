<?php 
/**
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Hooks get actions, when present in the $_GET superglobal. Every action
 * present in $_GET is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @since 1.0.0
 * @return void
 */
function buynowdepot_get_actions() {
    $key = ! empty( $_GET['bnd_action'] ) ? sanitize_key( $_GET['bnd_action'] ) : false;
    if ( ! empty( $key ) ) {
        do_action( "bnd_{$key}" , $_GET );
    }
}
add_action( 'init', 'buynowdepot_get_actions' );

/**
 * Hooks post actions, when present in the $_POST superglobal. Every rpress_action
 * present in $_POST is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @since 1.0.0
 * @return void
 */
function bnd_post_actions() {
    $key = ! empty( $_POST['bnd_action'] ) ? sanitize_key( $_POST['bnd_action'] ) : false;
    if ( ! empty( $key ) ) {
        do_action( "bnd_{$key}", $_POST );
    }
}
add_action( 'init', 'bnd_post_actions' );


/**
 * if use_menu_home page variable is set to true the manu item page will be treated as the home page
 */
function bnd_set_front_page() {
    $BndSettings = (array) get_option("bnd_settings");
    if ($BndSettings["use_menu_homepage"]=="1") {
        // Use a static front page
        $menuItems = get_page_by_title( 'Menu Items' );
        update_option( 'page_on_front', $menuItems->ID );
        update_option( 'show_on_front', 'page' );
    }
}
add_action ('init', 'bnd_set_front_page');

add_filter('cron_schedules', 'add_custom_cron_intervals');


function add_custom_cron_intervals( $schedules ) {
    // $schedules stores all recurrence schedules within WordPress
    $schedules['five_minutes'] = array(
        'interval'	=> 300,	// Number of seconds, 600 in 10 minutes
        'display'	=> 'Once Every 5 Minutes'
    );
    $schedules['one_day'] = array(
        'interval'	=> 86400,	// Number of seconds, 600 in 10 minutes
        'display'	=> 'Once Every Day'
    );
    // Return our newly added schedule to be merged into the others
    return (array)$schedules;
}

add_action( 'template_redirect', 'check_logged_in_user' );

function check_logged_in_user() {
    global $wp;
    $current_slug = add_query_arg( array(), $wp->request );
    if ($current_slug!='' && $current_slug!=null) {
        $pageattr = Bnd_Flex_Order_Delivery_Container::instance()->get_page_list()[$current_slug];
        if (!$pageattr[3] && !is_user_logged_in() ) {
            wp_redirect(buynowdepot_get_page_url("bnd-login"));
            exit();
        }
    }
}
?>