<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/*
 * Making WPDB as global
 * to access database information.
 */
global $wpdb;

/*
 * @var $table_name
 * name of table to be dropped
 * prefixed with $wpdb->prefix from the database
 */
$table_names_array = array(
    "bnd_country",
    "bnd_data_sync",
    "bnd_delivery_zone",
    "bnd_discount_coupon",
    "bnd_sequence",
    "bnd_tag_item",
    "bnd_tag",
    "bnd_modifier",
    "bnd_modifier_group_item",
    "bnd_modifier_group",
    "bnd_option_item",
    "bnd_option",
    "bnd_attribute",
    "bnd_item_group",
    "bnd_item_tax_rate",
    "bnd_item_category",
    "bnd_item_image",
    "bnd_item",
    "bnd_category",
    "bnd_merchant",
    "bnd_message_template",
    "bnd_opening_hours",
    "bnd_order_customer",
    "bnd_order_line_item",
    "bnd_order_payment",
    "bnd_order",
    "bnd_order_type",
    "bnd_tax_rate",
    "bnd_user_address",
    "bnd_notification",
	"bnd_customer_profile"
);
$page_names_array = array(
    "bnd-login",
    "bnd-forgot-password",
    "bnd-my-order",
    "bnd-profile",
    "bnd-profile-address",
    "bnd-signup",
    "bnd-verification",
    "bnd-logout",
    "bnd-coming-soon",
    "bnd-contact-us",
    "bnd-faq",
    "bnd-maintenance",
    "bnd-not-found",
    "bnd-privacy",
    "bnd-terms",
    "bnd-favorites",
    "bnd-menuhome",
    "bnd-menuitems",
    "bnd-cart-display",
    "bnd-offers",
    "bnd-search",
    "bnd-checkout",
    "bnd-order-status",
    "bnd-successful",
    "bnd-trackorder"
);

foreach($table_names_array as $table) {
    // drop the table from the database.
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}{$table}" );
}

foreach($page_names_array as $page) {
    $post = get_page_by_path($page);
    wp_delete_post($post->ID, true);
}
delete_option("bnd_settings");