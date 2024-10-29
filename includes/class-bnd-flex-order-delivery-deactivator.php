<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
 class Bnd_Flex_Order_Delivery_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        self::remove_scheduled_events();
	}

	public static function remove_scheduled_events(){
	    wp_clear_scheduled_hook( 'bnd_sync_clover_minute' );
	    wp_clear_scheduled_hook( 'bnd_sync_clover_daily' );
	}
}
