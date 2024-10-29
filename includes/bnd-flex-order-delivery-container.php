<?php

/**
 *
 * This class holds a reference to all types of utility classes in system
 * so that multiple instances of a class is not created unnecessarily.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
class Bnd_Flex_Order_Delivery_Container 
{
    protected $pagelist;
    protected $errorlist;
    protected $plugin;
    protected $cloverClient;
    protected $adminModels;
    protected $emailClient;
    
    private static $instance;
    
    private $repository;
    private $model;
    
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Bnd_Flex_Order_Delivery_Container ) ) {
            self::$instance = new Bnd_Flex_Order_Delivery_Container;
        }
        self::$instance->define_pagelist();
        self::$instance->define_admin_models();
        self::$instance->define_errorlist();
        return self::$instance;
    }
    
    private function define_pagelist() {
        $this->pagelist = array(
            "bnd-login" => array("Login", "bnd_login", "Account", true),
            "bnd-forgot-password" => array("Forgot Password", "bnd_forgot_password","Account", true),
            "bnd-register-verify" => array("Verification Pending", "bnd_register_verify","Account", true),
            "bnd-verify-success" => array("Verification Success", "bnd_verify_success","Account", true),
            "bnd-my-order" => array("My Orders", "bnd_my_order","Account", false ),
            "bnd-profile" => array("Profile", "bnd_profile", "Account", false),
            "bnd-profile-address" => array("Profile Address", "bnd_profile_address", "Account", false),
            "bnd-signup" => array("Sign Up", "bnd_signup", "Account", true),
            "bnd-verification" => array("Verification", "bnd_verification", "Account", true),
            "bnd-logout" => array("Track Your Order", "bnd_logout", "Account", true),
            "bnd-coming-soon" => array("Coming Soon", "bnd_coming_soon", "General", true),
            "bnd-contact-us" => array("Contact Us", "bnd_contact_us", "General", true),
            "bnd-faq" => array("FAQs", "bnd_faq", "Account", true),
            "bnd-maintenance" => array("Site Maintenance", "bnd_maintenance", "General", true),
            "bnd-not-found" => array("Page Not Found", "bnd_not_found", "General", true),
            "bnd-privacy" => array("Privacy Policy", "bnd_privacy", "General", true),
            "bnd-terms" => array("Terms", "bnd_terms" ,"General", true),
            "bnd-menuhome" => array("Latest Menu", "bnd_menuhome", "Menu", true),
            "bnd-menuitems" => array("Menu Items", "bnd_menuitems","Menu", true),
            "bnd-cart-display" => array("My Cart", "bnd_cart_display","Menu", true),
            "bnd-offers" => array("Latest Offers", "bnd_offers", "Menu", true),
            "bnd-search" => array("Search Menu", "bnd_search", "Menu", true),
            "bnd-checkout" => array("Checkout", "bnd_checkout", "Order", true),
            "bnd-order-status" => array("Order Status", "bnd_order_status", "Order", false),
            "bnd-successful" => array("Successful", "bnd_successful", "Order", true),
            "bnd-trackorder" => array("Track Your Order", "bnd_trackorder", "Order", true),         
        );
    }
    
    private function define_admin_models() {
        $this->adminModels = array(
            "category"=>array("Category", "bnd_category", "Category"),
            "item"=>array("Item","bnd_item", "Item"),
            "modifier-group"=>array("Modifier_Group","bnd_modifier_group", "Modifier Group"),
            "option"=>array("Option","bnd_option", "Option"),
            "tax-rate"=>array("Tax_Rate","bnd_tax_rate", "Tax Rate"),
            "order-type"=>array("Order_Type","bnd_order_type", "Order Type"),
            "opening-hours"=>array("Opening_Hours","bnd_order_type", "Order Type"),
            "merchant"=>array("Merchant","bnd_merchant", "Merchant"),
            "item-group"=>array("Item_Group","bnd_item_group", "Item Group"),
            "attribute"=>array("Attribute","bnd_attribute", "Attribute"),
            "item-image"=>array("Item_Image","bnd_item_image", "Item Image"),
            "order"=>array("Order","bnd_order", "Order"),
            "order-payment"=>array("Order_Payment","bnd_order_payment", "Order Payment"),
            "discount-coupon"=>array("Discount_Coupon","bnd_discount_coupon", "Discount Coupon"),
            "delivery-zone"=>array("Delivery_Zone","bnd_delivery_zone", "Delivery Zone"),
            "data-sync"=>array("Data_Sync","bnd_data_sync", "Data Sync"),
            "message-template"=>array("Message_Template","bnd_message_template", "Message Template"),
            );
    }
    
    private function define_errorlist() {
        $this->errorlist = array(
            "item_not_in_cart" =>array("Item does not exist in the cart"),
            "quantity_not_updated" => array("Unable to update quantity"),
            "item_low_on_stock" => array("Item is low on stock, please use another item, available quantity {quantity}"),
            "item_added" => array("{quantity} {name} added to the cart"),
            "item_deleted" => array("{name} removed from the cart"),
            "address_added" => array("New address has been added to the user"),
            "address_updated" => array("Address updated successfully"),
            "address_removed" => array("Address removed successfully"),
            "address_selected" => array("New address has been selected"),
            "pickup_confirmed" => array("You are going to pickup"),
            "delivery_confirmed" => array("item will be delivered to your chosen location"),
        );
    }
    public function get_page_list() {
        return $this->pagelist;
    }
    
    public function get_admin_models() {
        return $this->adminModels;
    }
    
    public function isStockTrackingEnabled()
    {
        $BndSettings = (array)get_option("bnd_settings");
        if (isset($BndSettings["track_stock"]) && $BndSettings["track_stock"] == "1") {
            return true;
        } else {
            return false;
        }
    }
    
    public function getRepository() {
        if (!$this->repository) {
            $this->repository = new Bnd_Flex_Order_Delivery_Repository();
        }
        return $this->repository;
    }
    
    public function getDb() {
        if (!$this->model) {
            $this->model = new Bnd_Flex_Order_Delivery_Db();
        }
        return $this->model;
    }
    
    public function getEmailClient() {
        if (!$this->emailClient) {
            $this->emailClient = new Bnd_Flex_Order_Delivery_Email_Client();
        }
        return $this->emailClient;
    }
    
    public function getCloverClient() {
        if (!$this->cloverClient) {
            $this->cloverClient = new Bnd_Flex_Order_Delivery_Clover_Client();
        }
        return $this->cloverClient;
    }
    
    public function get_message($code, $params=null) {
        $message =  $this->errorlist[$code];
        if ($params && !empty($params)) {
            foreach($params as $key=>$value) {
                $message = str_replace("{".$key."}", $value, $message);
            }
        }
        return $message;
    }
}
