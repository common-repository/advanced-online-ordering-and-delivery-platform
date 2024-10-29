<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
class Bnd_Flex_Order_Delivery_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bnd_flex_order_delivery    The ID of this plugin.
	 */
	private $bnd_flex_order_delivery;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * repository class for all database operations
	 * @var 
	 */
	private $repository;
	
	/**
	 * repository class for all database operations
	 * @var
	 */
	private $model;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $bnd_flex_order_delivery       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bnd_flex_order_delivery, $version ) {

		$this->bnd_flex_order_delivery = $bnd_flex_order_delivery;
		$this->version = $version;
		add_filter( 'get_custom_logo', array($this,'change_logo_class') );
		add_filter( 'body_class', function( $classes ) {
		    return array_merge( $classes, array( 'fixed-top-bar' ) );
		} );
		$this->repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
		$this->model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
	}

	
	
	public function change_logo_class( $html ) {
	    
	    $html = str_replace( 'custom-logo', 'img-fluid', $html );
	    $html = str_replace( 'custom-logo-link', 'brand-wrap mb-0', $html );
	    
	    return $html;
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bnd_Flex_Order_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bnd_Flex_Order_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	    global $post;
	    
	    if (!$post) {
	        return $template;
	    }
	    if (strpos($post->post_name, "bnd") === 0) {
            wp_enqueue_style("jquery-ui-dialog");
            
            wp_register_style( 'bnd-bootstrap' ,plugin_dir_url(dirname(__FILE__))."assets/js/vendor/bootstrap/css/bootstrap.min.css" ,array(), $this->version);
            wp_enqueue_style( 'bnd-bootstrap' );
            
            wp_register_style( 'bnd-slick' ,plugin_dir_url(dirname(__FILE__))."assets/js/vendor/slick/slick.min.css" ,array(), $this->version);
            wp_enqueue_style( 'bnd-slick' );
            wp_register_style( 'bnd-slick-theme' ,plugin_dir_url(dirname(__FILE__))."assets/js/vendor/slick/slick-theme.min.css" ,array(), $this->version);
            wp_enqueue_style( 'bnd-slick-theme' );
            wp_register_style( 'bnd-fonts-awesome', plugin_dir_url(dirname(__FILE__))."assets/js/vendor/font-awesome/css/all.min.css" ,array(), $this->version);
            wp_enqueue_style( 'bnd-fonts-awesome' );
    
            wp_register_style( 'bnd-jquery-selectBox', plugin_dir_url(dirname(__FILE__))."assets/js/vendor/jquery/jquery.selectBox.css",array(), $this->version);
            wp_enqueue_style( 'bnd-jquery-selectBox' );
            
            wp_register_style( 'bnd-jquery-feather', plugin_dir_url(dirname(__FILE__))."assets/js/vendor/icons/feather.css",array(), $this->version);
            wp_enqueue_style( 'bnd-jquery-feather' );
            
            wp_register_style( 'bnd-sidebar', plugin_dir_url(dirname(__FILE__))."assets/js/vendor/sidebar/demo.css",array(), $this->version);
            wp_enqueue_style( 'bnd-sidebar' );
            
    		wp_enqueue_style( $this->bnd_flex_order_delivery, plugin_dir_url( __FILE__ ) . 'css/bnd-flex-order-delivery-public.css', array(), $this->version, 'all' );
    		wp_register_style( 'bnd-style', plugin_dir_url(dirname(__FILE__))."templates/flexmenu/css/style.css" , array(), $this->version, 'all' );
    	    wp_enqueue_style( 'bnd-style' ,99);
    	    wp_register_style( 'bnd-theme', plugin_dir_url(dirname(__FILE__))."templates/flexmenu/css/theme-red.css" , array(), $this->version, 'all' );
    	    wp_enqueue_style( 'bnd-theme' );
    		wp_register_style( 'bnd-custom', plugin_dir_url(dirname(__FILE__))."templates/flexmenu/css/custom.css" , array(), $this->version, 'all' );
    		wp_enqueue_style( 'bnd-custom' );
    		
	    }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bnd_Flex_Order_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bnd_Flex_Order_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	    wp_enqueue_script("jquery");
	    wp_enqueue_script("jquery-ui-dialog");
	    //wp_enqueue_media();
	    wp_enqueue_script('bnd-bootstrap',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/bootstrap/js/bootstrap.bundle.min.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-bootbox',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/bootbox.all.min.js", array("jquery"), false, true);
	    wp_enqueue_script('bnd-selectbox',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/jquery/jquery.selectBox.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-validate',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/jquery/jquery.validate.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-slick',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/slick/slick.min.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-font-awesome',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/font-awesome/js/all.min.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-sidebar',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/sidebar/hc-offcanvas-nav.js", array("jquery"), false, false);	    
	    wp_enqueue_script('bnd-public-js', plugin_dir_url( dirname(__FILE__) ) . 'public/js/bnd-flex-order-delivery-public.js', array( 'jquery' ), $this->version, false );
	    $clover_client = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    wp_enqueue_script('bnd-clover', $clover_client->getCheckoutUrl()."/sdk.js", array( 'jquery' ), $this->version, false );
		wp_enqueue_script('bnd-manuitems',  plugin_dir_url( dirname(__FILE__) ) . 'templates/flexmenu/js/menuitems.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('bnd-main',  plugin_dir_url( dirname(__FILE__) ) . 'templates/flexmenu/js/bnd.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script('bnd-cardpointe',  plugin_dir_url( dirname(__FILE__) ) . 'templates/flexmenu/js/cardpointe.js', array( 'jquery' ), $this->version, true );
		$params = array(
		    "bnd_rest_url"=> get_rest_url(),
		    "bnd_ajax_url"=> admin_url( 'admin-ajax.php', isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' ),
		    "bnd_theme_url"=> buynowdepot_get_theme_url(),
		    "bnd_image_url"=>buynowdepot_get_theme_url()."/img",
		    "bnd_base_url"=>get_home_url()
		    );		
		wp_localize_script("bnd-public-js", "bnd_params",$params); 
	}
	
	
	/**
	 * Add to Cart
	 * @since    1.0.0
	 */
	public function bnd_add_to_cart() {
	    $params=array();
	    $params["key"]=sanitize_text_field($_POST['key']);
	    $params["item"]=sanitize_text_field($_POST['item']);
	    $params["quantity"]=sanitize_text_field($_POST['quantity']);
	    $params["modifiers"]=sanitize_text_field($_POST['modifiers']);
	    $params["instructions"]=sanitize_text_field($_POST['instructions']);
	    $response = $this->repository->addItemToCart($params);   
	    $this->updateDisplay($response);
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_display_cart() {
	    $session = Bnd_Flex_Order_Delivery_Session::instance();
	    $cart = new Bnd_Flex_Order_Delivery_Cart();
	    $cart->populate_contents_details();
	    $session->set("bnd_cart_details",$cart->get_details());
	    $cartdata = $session->get("bnd_cart_details");
	    $html =  $this->displayCart($cartdata);
	    wp_send_json_success( array('page'=>$html) );
	}
	
	public function bnd_display_cart_items() {
	    $session = Bnd_Flex_Order_Delivery_Session::instance();
	    $cartdata = $session->get("bnd_cart_details");
	    $html =  $this->displayCartItems($cartdata);
	    wp_send_json_success( array('cart_list'=>$html) );
	}
	
	public function bnd_display_address() {
	    $session = Bnd_Flex_Order_Delivery_Session::instance();
	    $cart = new Bnd_Flex_Order_Delivery_Cart();
	    $cart->populate_contents_details();
	    $session->set("bnd_cart_details",$cart->get_details());
	    $cartdata = $session->get("bnd_cart_details");
	    $html =  $this->displayAddress($cartdata);
	    wp_send_json_success( array('page_address'=>$html) );
	}
	
	public function bnd_display_profile_address() {
	    $session = Bnd_Flex_Order_Delivery_Session::instance();
	    $cart = new Bnd_Flex_Order_Delivery_Cart();
	    $cart->populate_contents_details();
	    $session->set("bnd_cart_details",$cart->get_details());
	    $cartdata = $session->get("bnd_cart_details");
	    $html =  $this->displayProfileAddress($cartdata);
	    wp_send_json_success( array('page_address'=>$html) );
	}
	
	/**
	 * Update the quantity
	 * @since    1.0.0
	 */
	public function bnd_update_quantity() {
	    $key=sanitize_text_field($_POST['key']);
	    $quantity=sanitize_text_field($_POST['quantity']);
        $response = $this->repository->updateQuantity($key, $quantity);
        $this->updateDisplay($response);
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_add_address() {
	    $params = buynowdepot_get_post_array();
	    if (!isset($params["id"]) || $params["id"]=="") {
	       $response = $this->repository->addAddress($params);
	    }
	    else {
	        $response = $this->repository->updateAddress($params);
	    }
	    $this->updateDisplay($response);
	}
	
	public function bnd_add_profile_address() {
	    $params = buynowdepot_get_post_array();
	    if (!isset($params["id"]) || $params["id"]=="") {
	        $response = $this->repository->addAddress($params);
	    }
	    else {
	        $response = $this->repository->updateAddress($params);
	    }
	    $html =  $this->displayProfileAddress($response);
	    wp_send_json_success( array('page_address'=>$html) );
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_save_profile() {
	    $params = buynowdepot_get_post_array();
	    $user = wp_get_current_user();
	    $params["email"]=$user->user_email;
	    $response = $this->model->updateCustomer($params);
	    if ($response) {
	       wp_send_json(array("status"=>"success","message"=>"Record updated successfully", "data"=>$params));
	    }else {
	       wp_send_json(array("status"=>"error","message"=>"Record not updated"));
	    }
	}
	
	public function bnd_save_password() {
	    $params = buynowdepot_get_post_array();
	    if ($params['new_password']!=$params['confirm_password']) {
	        wp_send_json(array("status"=>"error","message"=>"Passwords do not match"));
	    }
	    $user = wp_get_current_user();
	    wp_set_password($params['new_password'], $user->ID );
	    wp_send_json(array("status"=>"success","message"=>"Password updated successfully"));

	}
	
	public function bnd_edit_address() {
	    $params = buynowdepot_get_post_array();
	    $addressdata = $this->repository->getAddress($params);
	    wp_send_json_success( $addressdata);
	}
	
	public function bnd_edit_profile_address() {
	    $params = buynowdepot_get_post_array();
	    $addressdata = $this->repository->getAddress($params);
	    wp_send_json_success( $addressdata);
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_select_address() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->selectAddress($params);
	    $this->updateDisplay($response);
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_update_address() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->updateAddress($params);
	    $this->updateDisplay($response);
	}
	
	public function bnd_update_profile_address() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->updateAddress($params);
	    $html =  $this->displayProfileAddress($response);
	    wp_send_json_success( array('page_address'=>$html) );
	}
	
	public function bnd_apply_discount() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->applyDiscount($params);
	    $this->updateDisplay($response);
	}
	
	public function bnd_apply_tip() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->applyTip($params);
	    $this->updateDisplay($response);
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_remove_address() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->removeAddress($params);
	    $this->updateDisplay($response);
	}
	
	public function bnd_remove_profile_address() {
	    $params = buynowdepot_get_post_array();
	    $response = $this->repository->removeAddress($params);
	    $html =  $this->displayProfileAddress($response);
	    wp_send_json_success( array('page_address'=>$html) );
	}
	
	/**
	 * Get the Cart
	 * @since    1.0.0
	 */
	public function bnd_confirm_pickup() {
	    $response = $this->repository->confirmPickup();
	    $this->updateDisplay($response);
	}

	public function bnd_confirm_delivery() {
	    $response = $this->repository->confirmDelivery();
	    $this->updateDisplay($response);
	}
	/**
	 * Update the Special Instruction for one item
	 * @since    1.0.6
	 */
	public function bnd_UpdateSpecial_ins() {
	    
	    $cart_line_id   = sanitize_text_field($_POST['item']);
	    $special_ins = sanitize_text_field($_POST['special_ins']);
	    
	    if(!$this->session->isEmpty("items",$cart_line_id)){
	        $cartLine = $this->session->get("items",$cart_line_id);
	        $cartLine['special_ins'] = $special_ins ;
	        $this->session->set($cartLine,"items",$cart_line_id);
	        $response = array(
	            'status'	=> 'success',
	        );
	        wp_send_json($response);
	    }
	    else
	    {
	        $response = array(
	            'status'	=> 'error',
	            'message'   => 'Item not found'
	        );
	        wp_send_json($response);
	    }
	}
	/**
	 * Get More options for an item in the cart
	 * @since    1.0.6
	 */
	public function bnd_GetitemInCartOptions() {
	    
	    $cart_line_id  = sanitize_text_field($_POST['item']);
	    
	    if(!$this->session->isEmpty("items",$cart_line_id)){
	        $cartLine = $this->session->get("items",$cart_line_id);
	        $special_ins = $cartLine['special_ins'];
	        $qte = $cartLine['quantity'];
	        $response = array(
	            'status'	=> 'success',
	            'special_ins'	=> $special_ins,
	            'quantity'	=> $qte
	        );
	        wp_send_json($response);
	    }
	    else
	    {
	        $response = array(
	            'status'	=> 'error',
	            'message'   => 'Item not found'
	        );
	        wp_send_json($response);
	    }
	}
	/**
	 * Delete Item from the cart
	 * @since    1.0.0
	 */
	public function bnd_delete_from_cart() {
	    $key=sanitize_text_field($_POST['key']);
	    $response = $this->repository->deleteItemFromCart($key);
	    $this->updateDisplay($response);
	}
	
	public function bnd_complete_order_payment()
	{
	    if(isset($_POST) && isset($_POST['_wpnonce'])){
	        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'paymentForm' ) ) {
	            $response =  array(
	                'status'	=> 'Error',
	                'message'=> "Unauthorized or session is expired please refresh the page"
	            );
	            wp_send_json($response);
	        }
	        $BndSettings = (array)get_option("bnd_settings");
	        $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	        $order = $this->prepareOrderFromCart();
	        //save order details in the DB
	        $orderDB = $order["orderDB"];
	        $orderClover = $order["orderClover"];
	        $resultInDB = $this->repository->saveOrder($orderDB, "online");
	        if ($resultInDB["status"]=="success") {
    	        $orderResult = $cloverClient->submitOrder($orderClover);	        
    	        if ($orderResult["error"]==false) {
    	            $orderId=$orderResult["content"]["id"];
    	            $this->updateOrderCustomer($orderClover["orderCart"]["customers"], $orderId);
    	            $data = array("clid"=>$orderId, "order_status"=>1);
    	            $this->model->updateOrderStatus($orderDB["order_number"], $data);
    	            //$resultInDb = $this->repository->UpdateOrderStatus($order);
    	            /*
    	            $payment = array(
    	                "amount"=>floatval($orderResult["content"]["total"]),
    	                "currency"=>$orderResult["content"]["currency"],
    	                "source"=>$_POST["cloverToken"],
    	                "ecomind"=>"ecom"
    	            );*/
    	            $payment = array(
    	                "amount"=> floatval($orderResult["content"]["total"]),
    	                "currency"=> $orderResult["content"]["currency"],
    	                "merchid"=> $BndSettings["transaction_mid"],
    	                "orderid"=> $orderResult["content"]["id"],
    	                "account"=>sanitize_text_field($_POST["mytoken"]),
    	                "expiry"=>sanitize_text_field($_POST["paymentExpiryDateMM"]).sanitize_text_field($_POST["paymentExpiryDateYY"]),
    	                "cvv2"=>sanitize_text_field($_POST["paymentCVV"]),
    	                "ecomind"=>"E",
    	                "capture"=>'y'
    	            );
    	            //$result = $cloverClient->submitPaymentForOrder($payment, $orderResult["content"]["id"]);
    	            $result = $cloverClient->submitCardPointePaymentForOrder($payment, $orderResult["content"]["id"]);
    	            if ($result["error"]==false) {
        	            $response =  array(
        	                'status'	=> 'success',
        	                'message'=> "Order was completed successfully",
        	                'data' => $data
        	            );
        	            $datap = array("order_status"=>2);
        	            $this->model->updateOrderStatus($orderDB["order_number"], $datap);
        	            $resultPayment = $this->repository->updatePaymentDetails($orderDB["order_number"], $result["content"]);
        	            $cart = new Bnd_Flex_Order_Delivery_Cart();
        	            $address = Bnd_Flex_Order_Delivery_Session::instance()->get("bnd_delivery_address");
        	            $to = "";
        	            if ($address) {
        	                $to = $address["email"];
        	                $this->sendOrderEmail($to, $data["clid"]);
        	            }
        	            Bnd_Flex_Order_Delivery_Session::instance()->set("order_number",$data["clid"]);
        	            $cart->empty_cart();
        	            wp_send_json($response);
    	            }
    	            else {
    	                $response =  array(
    	                    'status'	=> 'failure',
    	                    'message'=> "Order could not be completed. Payment failure",
    	                    'data' => $data
    	                );
    	                $datap = array("order_status"=>4);
    	                $this->model->updateOrderStatus($orderDB["order_number"], $datap);
    	                wp_send_json($response);
    	            }
    	        }
    	        else {
    	            wp_send_json(array(
    	                'status'	=> 'error',
    	                'message'=> "Unable to complete order. Please try again later"
    	            ));
    	        }
	        }
	        else {
	            wp_send_json(array(
	                'status'	=> 'error',
	                'message'=> "Unable to complete order:".$resultInDB["error"]." Please try again later"
	            ));
	        }
	    }
	}
	
	public function bnd_complete_order()
	{
	    if(isset($_POST) && isset($_POST['_wpnonce'])){
	        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'paymentForm' ) ) {
	            $response =  array(
	                'status'	=> 'Error',
	                'message'=> "Unauthorized or session is expired please refresh the page"
	            );
	            wp_send_json($response);
	        }
	        $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	        $order = $this->prepareOrderFromCart();
	        $orderDB = $order["orderDB"];
	        $orderClover = $order["orderClover"];
	        $resultInDB = $this->repository->saveOrder($orderDB, "cash");
	        if ($resultInDB["status"]=="success") {
    	        $orderResult = $cloverClient->submitOrder($orderClover);
    	        if ($orderResult && $orderResult["error"]==false) {
    	            $orderId=$orderResult["content"]["id"];
    	            $this->updateOrderCustomer($orderClover["orderCart"]["customers"], $orderId);
    	            $data = array("clid"=>$orderId, "order_status"=>1);
    	            $this->model->updateOrderStatus($orderDB["order_number"], $data);
    	            $response =  array(
    	                'status'	=> 'success',
    	                'message'=> "Order was completed successfully",
    	                'data' => $data
    	            );
    	            $cart = new Bnd_Flex_Order_Delivery_Cart();
    	            $address = Bnd_Flex_Order_Delivery_Session::instance()->get("bnd_delivery_address");
    	            $to = "";
    	            if ($address) {
    	               $to = $address["email"]; 
    	               $this->sendOrderEmail($to, $data["clid"]);
    	            }
    	            Bnd_Flex_Order_Delivery_Session::instance()->set("order_number",$data["clid"]);
    	            $cart->empty_cart();
    	            wp_send_json($response);
    	        }
    	        else {
    	            wp_send_json(array(
    	                'status'	=> 'error',
    	                'message'=> "Unable to complete order. Please try again later"
    	            ));
    	        }
	        }
	        else {
	            wp_send_json(array(
	                'status'	=> 'error',
	                'message'=> "Unable to complete order:".$resultInDB["error"]." Please try again later"
	            ));
	        }
	    }
	}
	
	private function prepareOrderFromCart() {
	    
	    $BndSettings = (array)get_option('bnd_settings');
	    $cart = new Bnd_Flex_Order_Delivery_Cart();
	    $cart_details = $cart->get_details();
	    $merchant = $cart_details["merchant_address"];
	    $serviceFeeName = "Service Fee";
	    $deliveryFeeName = "Delivery Fee";
	    
	    $orderDB = array();
	    /* Get the names on receipt of Service Charge and delivery charge */
	    if(isset($BndSettings['service_fees_name']) && $BndSettings['service_fees_name']!=""){
	        $serviceFeeName = $BndSettings['service_fees_name'];	        
	    }
	    if(isset($BndSettings['delivery_fees_name']) && $BndSettings['delivery_fees_name'] != "") {
            $deliveryFeeName = $BndSettings['delivery_fees_name'];
	    }
        $deliveryFeeDetail='';
        $deliveryFee = $cart_details["total"]["delivery_charge"];
        $deliveryFeeDetail=array();
        if ($deliveryFee > 0) {
            $deliveryItem = $this->model->getItemByName("Delivery Charge");
            if ($deliveryItem) {
                $deliveryFeeDetail = array(
                    'item'=>array("id"=>$deliveryItem->clid),
                    'name'=>$deliveryItem->name,
                    'price'=>$deliveryFee,
                    'quantity'=>1,
                    'special_ins'=>'',
                    'tax_rate'=>array(),
                    'modifiers'=>array()
                );
            }
            //$this->session->set($delivery_fees_cartLine,"items","delivery_fees");
        }
        $serviceFee = $cart_details["total"]["total_fees"];
        $serviceFeeDetail = '';
        if ($serviceFee > 0) {
            $serviceItem = $this->model->getItemByName("Service Charge");
            if ($serviceItem) {
                $serviceFeeDetail = array(
                    'item'=>array("id"=>$serviceItem->clid),
                    'name'=>$cart_details["total"]["service_charge_name"],
                    'price'=>$serviceFee,
                    'quantity'=>1,
                    'special_ins'=>'',
                    'tax_rate'=>array(),
                    'modifiers'=>array()
                );
            }
        }
        //prepare Tip
        $tip = $cart_details["total"]["tip"];
        $tipDetail = '';
        if ($tip > 0) {
            $tipItem = $this->model->getItemByName("Tip");
            if ($tipItem) {
                $tipDetail = array(
                    'item'=>array("id"=>$tipItem->clid),
                    'name'=>$tipItem->name,
                    'price'=>$tip,
                    'quantity'=>1,
                    'special_ins'=>'',
                    'tax_rate'=>array(),
                    'modifiers'=>array()
                );
            }
        }
        
        //prepare discount
        $discountAmount = $cart_details["total"]["total_discount"];
        $discounts = array();
        if ($discountAmount >0) {
           $discount = array("name"=>"Coupon discount", "amount"=>-1*$discountAmount);
           array_push($discounts, $discount);
        }
        /*
        $taxRateDetail = $cart_details["total"]["total_tax"];
        if ($taxRateDetail > 0) {
            $taxRate = array(
                'item'=>array(
                    "id"=>"total_tax",
                    "name"=>"VAT",
                    "price"=>$taxRateDetail),
                'quantity'=>1,
                'special_ins'=>'',
                'tax_rate'=>array(),
                'modifiers'=>array()
            );
        }*/
        $order_note="";
        if ($cart_details["order_type"]=="delivery") {
            $delivery_address = $cart_details["delivery_address"];
            $order_note.=$delivery_address["name"]."\n".$delivery_address["address1"]."\n".$delivery_address["address2"]."\n".$delivery_address["address3"]."\n".$delivery_address["city"]."\n".$delivery_address["state"]."\n".$delivery_address["zip"]."\n".$delivery_address["country"];
        }
        $orderPrefix = $BndSettings["order_prefix"];
        $nextOrderNumber = $orderPrefix.$this->model->getNextSequence("ORDER");
        $orderTypeId = buynowdepot_get_option("order_type_delivery");
        $orderType;
        if (isset($orderTypeId) && $orderTypeId!=="DEFAULT") {
            $orderType = $this->model->getByCloverId("order_type",$orderTypeId);
        }
        $lineItems = array();
        foreach($cart_details["lineItems"] as $line) {
            $modifications = array();
            if (isset($line["modlist"])) {
                foreach($line["modlist"] as $mod) {
                    $modification = array(
                        "id" => $mod["clid"],
                        "name"=>$mod["name"],
                        "amount"=>$mod["price"],
                        "modifier"=>array("id"=>$mod["clid"])
                    );
                    array_push($modifications, $modification);
                }
            }
            $quantity = $line["quantity"];
            for ($i=0; $i< $quantity; $i++) {
                $lineItem = array(
                    "item"=>array("id"=>$line["clid"]),
                    "name"=>$line["name"],
                    "price"=>$line["price"],
                    "priceWithModifiers"=>$line["subtotal_per_item"],
                    "note" =>$line["instructions"],
                    "modifications"=>$modifications
                );
                array_push($lineItems,$lineItem);
            }
        }
        if ($serviceFeeDetail) {
            array_push($lineItems, $serviceFeeDetail);
        }
        if ($deliveryFeeDetail) {
            array_push($lineItems, $deliveryFeeDetail);
        }
        if ($tipDetail) {
            array_push($lineItems, $tipDetail);
        }
        //array_push($lineItems,$serviceFeeDetail);
        //array_push($lineItems,$deliveryFeeDetail);
        //array_push($lineItems,$taxRate);
        //check customer details
        $customer = $this->checkCustomer($cart_details["delivery_address"]);
        $customers=array();
        array_push($customers, $customer);
        $orderCart = array(
            "currency"=>$merchant["currency"],
            "total"=>$cart_details["total"]["total"],
            "note" =>$order_note,
            "groupLineItems"=>(isset($merchant["group_line_items"]) && $merchant["group_line_items"]==1)?true:false,
            "printable"=>true,
            "lineItems"=>$lineItems,
            "discounts"=>$discounts,
            "customers"=>$customers
        );
        $user=null;
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
        }
        $orderDB = array(
            "order_number"=>$nextOrderNumber,
            "currency"=>$merchant["currency"],
            "total"=>$cart_details["total"]["total"],
            "subtotal"=>$cart_details["total"]["subtotal"],
            "total_tax"=>$cart_details["total"]["total_tax"],
            "total_fees"=>$cart_details["total"]["total_fees"],
            "delivery_charge"=>$cart_details["total"]["delivery_charge"],
            "total_discount"=>$cart_details["total"]["total_discount"],
            "total_tip"=>$cart_details["total"]["tip"],
            "order_type"=>($cart_details["order_type"]),
            "note" =>$order_note,
            "lineItems"=>$cart_details["lineItems"],
            "customer"=>$cart_details["delivery_address"],
            "user_login"=>$user->data->user_email,
            "customer_id"=>$customer["id"]
        );
        $order = array("orderClover"=>array("orderCart"=>$orderCart), "orderDB"=>$orderDB);
        return $order;
	}

	public function buynowdepot_get_modifiers() {
	    $itemId = sanitize_text_field($_GET["item"]);
	    $modId = sanitize_text_field($_GET["mod"]);
	    $item = $this->model->getItemByCloverId($itemId);
	    $response = $this->repository->getItemModifiersAll($item);
	    $response["modifier"]=$modId;
	    $response["key"]="";
	    $html = $this->displayAddOn($response);
	    wp_send_json_success( array('page'=>$html) );
	    
	}
	
	public function buynowdepot_edit_modifiers() {
	    $key = sanitize_text_field($_GET["key"]);
	    $session = Bnd_Flex_Order_Delivery_Session::instance();
	    $cart_content = $session->get('bnd_cart_content');
	    if (isset($cart_content)){
	        if (empty($cart_content[$key])) {
	            return array("status"=>"error", "message"=>buynowdepot_get_message("item_not_in_cart"));
	        }
	        $item_data = $cart_content[$key];
	        $item = $this->model->getItemByCloverId($item_data["item"]);
	        $response = $this->repository->getItemModifiersAll($item);
	        $response["selected_modifiers"] = $item_data;
	        $response["key"]=$key;
	        $html = $this->editAddOn($response);
	        wp_send_json_success(array("page"=>$html));
	    }
	    else
	    {
	        $response = array(
	            'status'	=> 'error',
	            'message'   => 'Item not found'
	        );
	        wp_send_json_success($response);
	    }
	}
	
	function checkCustomer($address) {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    $response = $cloverClient->getCustomer($address["first_name"], $address["last_name"], $address["email"], $address["phone_number"]);
	    $customers = $response["content"]["elements"];
	    $customer = array();
	    if (!empty($customers)) {
	        $customer=array("id"=>$customers[0]["id"]);
	    }
	    else {
	        $cust=array(
	            "firstName"=>$address["first_name"],
	            "lastName"=>$address["last_name"],
	            "emailAddresses"=>array(array("emailAddress"=>$address["email"])),
	            "phoneNumbers"=>array(array("phoneNumber"=>$address["phone_number"])),
	            "addresses"=>array(array(
	                "address1"=>$address["address1"],
	                "address2"=>$address["address2"],
	                "address3"=>$address["address3"],
	                "city"=>$address["city"],
	                "country"=>$address["country"],
	                "state"=>$address["state"],
	                "zip"=>$address["zip"],
	                "phoneNumber"=>$address["phone_number"],
	            ))
	        );
	        $response = $cloverClient->createCustomer($cust);
	        $cloverCust = $response["content"];
	        $customer=array("id"=>$cloverCust["id"]);
	    }
	    return $customer;
	}
	
	function updateOrderCustomer($customers, $orderId) {
	    $data=array("customers"=>$customers);
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    $response = $cloverClient->updateOrderCustomer($data, $orderId);
	    $customers = $response["content"]["elements"];
	    if (!empty($customers)) {
	        return false;
	    }
	    else {
	        return true;
	    }
	}
	
	function sendOrderEmail($to, $order) {
	    //Send email
	    $subject = 'Your order details';
	    $body = 'Dear customer, <br/> Thank you for your order. Your order no. is :'.$order.'.<br/>You can track your order <a href="'.buynowdepot_get_page_url("bnd-order-status").'&order_number='.$order.'">here</a>.';
	    $headers = array('Content-Type: text/html; charset=UTF-8');	    
	    wp_mail( $to, $subject, $body, $headers );
	}
	function sendMerchantEmail($to, $order) {
	    //Send email
	    $subject = 'Your order details';
	    $body = 'There was an order from the cusstomer. Your order no. is :'.$order.'.<br/>You can track your order <a href="'.buynowdepot_get_page_url("bnd-order-status").'&order_number='.$order.'">here</a>.';
	    $headers = array('Content-Type: text/html; charset=UTF-8');
	    wp_mail( $to, $subject, $body, $headers );
	}
	
	function updateProfileDisplay($response) {
	    if ($response["status"]=="success") {
	        $cartdata = $response["data"];
	        $html =  $this->displayCart($cartdata);
	        $response['page']=$html;
	        //$response['page_address']=$html_address;
	        //$response['cart_list']=$html_cart_list;
	    }
	    wp_send_json_success($response);
	}
	
	function updateDisplay($response) {
	    
	    if ($response["status"]=="success") {
	        $cartdata = $response["data"];
	        $html =  $this->displayCart($cartdata);
	        $response['page']=$html;
	        //$response['page_address']=$html_address;
	        //$response['cart_list']=$html_cart_list;
	    }
	    wp_send_json_success($response);
	}
	
	function displayCart($response) {
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR."templates/flexmenu/cart_section.php";
	    return ob_get_clean();
	}
	
	function displayCartItems($response) {
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR."templates/flexmenu/cart_item_list.php";
	    return ob_get_clean();
	}
	
	function displayAddress($response) {
	    $addresses = array();
	    if (is_user_logged_in()) {
	        $user = wp_get_current_user();
	        $addresses = $this->model->getAllModels("user_address", array("user_id"=> $user->data->user_email));
	    }
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR."templates/flexmenu/address_section.php";
	    return ob_get_clean();
	}
	
	function displayProfileAddress($response) {
	    $addresses = array();
	    if (is_user_logged_in()) {
	        $user = wp_get_current_user();
	        $addresses = $this->model->getAllModels("user_address", array("user_id"=> $user->data->user_email));
	    }
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR."templates/flexmenu/profile_address_section.php";
	    return ob_get_clean();
	}
	
	function displayAddOn($response) {
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR."templates/flexmenu/add_to_order.php";
	    return ob_get_clean();
	}
	
	function editAddOn($response) {
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR."templates/flexmenu/edit_modifier.php";
	    return ob_get_clean();
	}
}