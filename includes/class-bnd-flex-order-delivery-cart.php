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

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * bnd_Cart Class
 *
 * @since 1.0
 */
class Bnd_Flex_Order_Delivery_Cart {
	/**
	 * Cart contents
	 *
	 * @var array
	 * @since 1.0
	 */
	public $contents = array();

	/**
	 * Details of the cart contents
	 *
	 * @var array
	 * @since 1.0
	 */
	public $details = array();

	/**
	 * Cart Quantity
	 *
	 * @var int
	 * @since 1.0
	 */
	public $quantity = 0;

	/**
	 * Subtotal
	 *
	 * @var float
	 * @since 1.0
	 */
	public $subtotal = 0.00;

	/**
	 * Total
	 *
	 * @var float
	 * @since 1.0
	 */
	public $total = 0.00;

	/**
	 * Fees
	 *
	 * @var array
	 * @since 1.0
	 */
	public $fees = array();

	/**
	 * Tax
	 *
	 * @var float
	 * @since 1.0
	 */
	public $tax = 0.00;

	/**
	 * Purchase Session
	 *
	 * @var array
	 * @since 1.0
	 */
	public $session;

	/**
	 * Discount codes
	 *
	 * @var array
	 * @since 1.0
	 */
	public $discounts = array();

	/**
	 * Cart saving
	 *
	 * @var bool
	 * @since 1.0
	 */
	public $saving;

	/**
	 * Saved cart
	 *
	 * @var array
	 * @since 1.0
	 */
	public $saved;

	/**
	 * Has discount?
	 *
	 * @var bool
	 * @since 1.0
	 */
	public $has_discounts = null;
	public $logged_in_user = null;
	public $delivery_address = array();
	public $merchant_address = array();
	//public $user_addresses = array();
	public $payment_details = array();	
	public $order_type = "delivery";

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->get_contents_from_session();
	}

	/**
	 * Populate the cart with the data stored in the session
	 *
	 * @since 1.0
	 * @return void
	 */
	public function get_contents_from_session() {
		$cart_content = Bnd_Flex_Order_Delivery_Session::instance()->get( 'bnd_cart_content' );
		$this->contents = isset($cart_content)?$cart_content:array();
		$cart_details = Bnd_Flex_Order_Delivery_Session::instance()->get( 'bnd_cart_details' );
		$this->details = isset($cart_details)?$cart_details:array();
		$delivery_address = Bnd_Flex_Order_Delivery_Session::instance()->get('bnd_delivery_address' );
		if (!empty($delivery_address)){
		  $this->delivery_address = $delivery_address;
		}
		$this->order_type=empty(Bnd_Flex_Order_Delivery_Session::instance()->get('order_type'))?"delivery":Bnd_Flex_Order_Delivery_Session::instance()->get('order_type');
	}

	/**
	 * Populate the discounts with the data stored in the session.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function get_discounts_from_session() {
	    $discounts = Bnd_Flex_Order_Delivery_Session::instance()->get( 'cart_discounts' );
		$this->discounts = $discounts;
	}

	/**
	 * Get cart contents
	 *
	 * @since 1.0
	 * @return array List of cart contents.
	 */
	public function get_contents() {
		return $this->contents;
	}
	
	/**
	 * Get cart contents
	 *
	 * @since 1.0
	 * @return array List of cart contents.
	 */
	public function get_details() {
	    return $this->details;
	}

	/**
	 * Get cart contents details
	 *
	 * @since 1.0
	 * @return array
	 */
	public function populate_contents_details() {

		/*if ( empty( $this->contents ) ) {
			return array();
		}*/
		$this->merchant_address = (array)Bnd_Flex_Order_Delivery_Container::instance()->getDb()->getMerchantAddress();
		$bnd_options = (array)get_option( 'bnd_settings' );
		$details = array();
		$cart_subtotal=0;
		$cart_tax=0;
		$cart_fees=0;
		$cart_discount=0;
		$cart_total=0;
		$item_count=0;
		$lineItems = array();
		$db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
		if (!empty($this->contents)) {
    		foreach ( $this->contents as $key => $data ) {
    		    $lineItem = array();
    			if( empty($data['item']) )
    				return;
    			//$data['quantity'] = max( 1, $data['quantity'] ); // Force quantity to 1
    			$item = $db->getItemByCloverId($data["item"]);
    			$lineItem["key"]=$key;
    			$lineItem["clid"]=$item->clid;
    			$lineItem["name"]=$item->name;
    			$lineItem["image_url"]=buynowdepot_get_image_url($db->getDefaultItemImage($item->clid)->image_url);
    			$lineItem["quantity"]=$data['quantity'];
    			$item_count+=1;
    			$modifiers =  array();
    			$price = $item->price;
    			$modstr = "";
    			if (isset($data['modifiers']) && !empty($data['modifiers'])) {
    			    $modlist = explode(",",$data['modifiers']);
    			    foreach($modlist as $moditem) {
    			        $modifier = $db->getModifier($moditem);
    			        array_push($modifiers, $modifier);
    			        $price+=$modifier->price;
    			        $modstr.=$modifier->name.", ";
    			    }
    			    $modstr = rtrim(trim($modstr),',');
    			    $lineItem["modifiers"]=$modstr;
    			}
    			$lineItem["modlist"]=$modifiers;
    			$lineItem["instructions"]=$data["instructions"];
    			$lineItem["price"]=$item->price;
    			$lineItem["subtotal_per_item"]=$price;
    			$price = $price* $data['quantity'];
    			$lineItem["subtotal"]=$price;
    			$cart_subtotal+=$price;
    			// Subtotal for tax calculation must exclude fees that are greater than 0. See $this->get_tax_on_fees()
    			$tax_rates = $db->getItemTaxRate($item->clid);
    			$lineItem["tax_rates"] = $tax_rates;
    			$tax=0.0;
    			//$tax_per_item=0.0;
    			$item_tax_rate =0;
    			foreach ($tax_rates as $tr) {
    			    if ($tr->tax_rate > 0) {
    			        $tax = $tax+ ($price * $tr->tax_rate/10000000);
    			        //$tax_per_item += $lineItem["price"]* $tr->tax_rate/10000000;
    			        $item_tax_rate+=$tr->tax_rate;
    			    }
    			}
    			$lineItem["item_tax_rate"] = $item_tax_rate;
    			$lineItem["item_tax"] = $tax;
    			//$lineItem["tax_per_item"] = $tax_per_item;
    			$cart_tax+=$tax;
    			//calculate service charge
    			$fees =0.0;
    			$discount=0;
    			/*if(isset($bnd_options['service_fees_value']) && $bnd_options['service_fees_value']>0)
    			{
    			    if(isset($bnd_options['service_fees_type']) && $bnd_options['service_fees_type'] == "percent")
    			    {
    			        $fees = floatval($bnd_options['service_fees_value'])*$price/100;
    			    }
    			    else {
    			        $fees = floatval($bnd_options['service_fees_value']);
    			    }
    			}*/
    			//$lineItem["fees_per_item"] = $fees;
    			//$fees=$fees* $data['quantity'];
    			//$discount=0;
    			$lineItem["fees"]=$fees;
    			//$lineItem["discount_per_item"]=$discount;
    			//$discount=$discount* $data['quantity'];
    			$lineItem["discount"]=$discount;
    			$cart_fees+=$fees;
    			//$cart_discount+=$discount;
    			$total = $price;
    			//$lineItem["total_per_item"]=$lineItem["price"]+$lineItem["tax_per_item"]+$lineItem["fees_per_item"]-$lineItem["discount_per_item"];
    			$lineItem["total"]=$total;
    			$cart_total +=$total;
    			array_push($lineItems,$lineItem);
    		}
		}
		$couponData = Bnd_Flex_Order_Delivery_Session::instance()->get("coupon_data");
		$details["has_coupon"] = false;
		$tax_discount =0.0;
		if ($couponData) {
		    if ($couponData["discount_type"]=="Amount") {
		        $cart_discount = abs($couponData["value"]*100);
		    }
		    else {
		        $cart_discount = abs($cart_subtotal*$couponData["value"]/100);
		    }
		    $details["has_coupon"] = true;
		    $details["coupon_code"] = $couponData["code"];
		    foreach ($lineItems as $lineItems) {
		        if ($lineItem["item_tax_rate"] > 0) {
		            $tax_discount = $tax_discount+ ($cart_discount * $lineItem["item_tax_rate"]/10000000);
		        }
		    }
		}
		$delivery_charge=0.0;
		$delivery_charge_tax=0.0;
		$merchantPostCode = $this->merchant_address["zip"];
		$all_tax_rates = $db->getAllModels("tax_rate",array());
		if ($this->delivery_address) {
		  $deliveryPostCode = $this->delivery_address["zip"];
		  $distance = $this->getDistance($merchantPostCode, $deliveryPostCode);
		  $latlng = get_lat_lng($deliveryPostCode);
		  if ($latlng!=-1) {
    		  $delivery_charge = abs($this->getDeliveryCharge($distance, $latlng));	
    		  foreach ($all_tax_rates as $tr) {
    		      if ($tr->tax_rate > 0) {
    		          $delivery_charge_tax = $delivery_charge_tax+ ($delivery_charge * $tr->tax_rate/10000000);
    		          //$tax_per_item += $lineItem["price"]* $tr->tax_rate/10000000;
    		      }
    		  }
    		  $cart_tax = $cart_tax+$delivery_charge_tax;
		  }
		}
		//compute service charge
		$service_charge=0.0;
		$service_charge_tax=0.0;
		$service_charge_name = "";
		if ($this->merchant_address["service_charge_enabled"]) {
		    $service_charge = abs($cart_subtotal * $this->merchant_address["service_charge_percent"] /100);
		    foreach ($all_tax_rates as $tr) {
		        if ($tr->tax_rate > 0) {
		            $service_charge_tax = abs($service_charge_tax+ ($service_charge * $tr->tax_rate/10000000));
		            //$tax_per_item += $lineItem["price"]* $tr->tax_rate/10000000;
		        }
		    }
		    $cart_tax = $cart_tax+$service_charge_tax;
		    $service_charge_name = $this->merchant_address["service_charge_name"];
		}
		//compute tip
		$tip_details = Bnd_Flex_Order_Delivery_Session::instance()->get("tip_details");
		$tip_amount=0;
		if (isset($tip_details) && $tip_details) {
		    if ($tip_details["tip_type"]=="percent") {
		        $tip_amount = abs($cart_subtotal * $tip_details["tip_value"]/100);
		    }
		    else {
		        $tip_amount = abs(($tip_details["tip_value"])?$tip_details["tip_value"]*100:0.0);
		    }
		}
		$details["lineItems"]=$lineItems;
		$total_tax = abs($cart_tax-$tax_discount);
		$total_charge =($cart_total+$total_tax-$cart_discount+$delivery_charge+$service_charge+$tip_amount);
		$totals = array(
		    "subtotal"=>$cart_subtotal, 
		    "total_tax"=> $total_tax,
		    "total_fees"=>$service_charge, 
		    "service_charge_name"=>$service_charge_name, 
		    "total_discount"=>$cart_discount, 
		    "total"=>$total_charge, 
		    "delivery_charge"=>$delivery_charge, 
		    "item_count"=>$item_count, 
		    "tip"=>$tip_amount,
		    "tip_type"=>($tip_details)?$tip_details["tip_type"]:"amount",
		    "tip_value"=>($tip_details)?$tip_details["tip_value"]:0
		);
        $details["total"]=$totals;
        $details["delivery_address"] = $this->delivery_address;
        //$details["user_addresses"] = $this->user_addresses;
        $details["merchant_address"] = $this->merchant_address;
        $details["order_type"] = $this->order_type;
		$this->details = $details;
		return $this->details;
	}

	public function getDistance($postcode1, $postcode2) {
	    $request = "https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyA6uH3fXrg4gV-Hb2h-DqfeW8IM_YO5RnE&origins=" . $postcode1 . "&destinations=" . $postcode2 . "&mode=driving&language=en-US&sensor=false&units=imperial";
	    $distdata = file_get_contents($request);
	    
	    // Put the data into an array for easy access
	    $distances = json_decode($distdata, true);
	    
	    // Do some error checking, first for if the response is ok
	    $status = $distances["status"];
	    $row_status = $distances["rows"][0]["elements"][0]["status"];
	    
	    if ($status == "OK" && $row_status == "OK") {
	        
	        // Calculate the distance in miles
	        $distance = $distances["rows"][0]["elements"][0]["distance"]["value"];
	        $distance_miles = round($distance * 0.621371192/1000, 2);
            return $distance_miles;
	        
	    } else {
	        return -1;
	    }
	}
	/**
	 * Get Discounts.
	 *
	 * @since 1.0
	 * @return array $discounts The active discount codes
	 */
	public function get_discounts() {
		$this->get_discounts_from_session();
		$this->discounts = ! empty( $this->discounts ) ? explode( '|', $this->discounts ) : array();
		return $this->discounts;
	}
	
	public function getDeliveryCharge($distance, $latlng) {
	    $delivery_charge=0.0;
	    $bnd_options = (array)get_option( 'bnd_settings' );
	    $delivery_info = $bnd_options["delivery_info"];
	    $deliveryInfoData = json_decode($delivery_info);
	    if ($deliveryInfoData->delivery_mode=="fixed") {
	        $delivery_charge = $deliveryInfoData->fixed_delivery_fee*100;
	    }
	    if ($deliveryInfoData->delivery_mode=="distance") {
	        $distance1 = $deliveryInfoData->delivery_distance_1;
	        $fee1 = $deliveryInfoData->delivery_fee_1;
	        $distance2 = $deliveryInfoData->delivery_distance_2;
	        $fee2 = $deliveryInfoData->delivery_fee_2;
	        $distance3 = $deliveryInfoData->delivery_distance_3;
	        $fee3 = $deliveryInfoData->delivery_fee_3;
	        $distance4 = $deliveryInfoData->delivery_distance_4;
	        $fee4 = $deliveryInfoData->delivery_fee_4;
	        if ($distance1 && $distance <$distance1) {
	            $delivery_charge=$fee1*100;
	        }
	        else if ($distance2 && $distance <$distance2) {
	            $delivery_charge=$fee2*100;
	        }
	        else if ($distance3 && $distance <$distance3) {
	            $delivery_charge=$fee3*100;
	        }
	        else if ($distance4 && $distance <$distance4) {
	            $delivery_charge=$fee4*100;
	        }
	    }
	    if ($deliveryInfoData->delivery_mode=="zone") {
	        $db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
	        $zones = $db->getAllModels("delivery_zone");
	        $point = $latlng["lat"]." ".$latlng["lng"];
	        foreach($zones as $zone) {
	            if ($zone->zone_type =="rectangle") {
	                $area_map = str_replace("&quot;","\"",$zone->area_map);
	                $map = (array)json_decode($area_map);
	                $v1 = $map["west"]." ".$map["north"];
	                $v2 = $map["east"]." ".$map["north"];
	                $v3 = $map["east"]." ".$map["south"];
	                $v4 = $map["west"]." ".$map["south"];
	                $polygon = array($v1,$v2,$v3,$v4);
	                $poly = pointInPolygon($point, $polygon);
	                if ($poly){
	                    $delivery_charge = $zone->delivery_fee*100;
	                }
	                else {
	                    $delivery_charge = $zone->outside_fee*100;
	                }
	            }
	        }
	    }
	    return $delivery_charge;
	}
	
	public function addItem($params) {
	    $key='';
	    if (isset($params["key"]) && $params["key"]) {
	        $key = $params["key"];
	    }
	    else {
	       $key = uniqid();
	    }
	    $this->contents[$key]= $params;
	    $this->populate_contents_details();
	}
	
	public function updateItemQuantity($key, $quantity) {
	    if (!isset($key)){
	        return buynowdepot_get_error("001");
	    }
	    $this->contents[$key]["quantity"]= $quantity;
	    $this->populate_contents_details();
	}
	
	public function updateItem($key, $params) {
	    $key = uniqid();
	    $this->contents[$key]= $params;
	    $this->populate_contents_details();
	}
	
	public function getItemCount() {
	    $cart = $this->get_contents();
	    $quantities = 0;
        foreach ( $cart as $key=>$item ) {
            $quantities+=$item["quantity"];
        }
        return $quantities;
	}

	/**
	 * Checks if the cart is empty
	 *
	 * @since 1.0
	 * @return boolean
	 */
	public function is_empty() {
		return 0 === sizeof( $this->contents );
	}

	/**
	 * Remove from cart
	 *
	 * @since 1.0
	 *
	 * @param int $key Cart key to remove. This key is the numerical index of the item contained within the cart array.
 	 * @return array Updated cart contents
	 */
	public function remove( $key ) {

		$cart = $this->get_contents();

		do_action( 'bnd_pre_remove_from_cart', $key );

		if ( ! is_array( $cart ) ) {
			return true; // Empty cart
		} else {
			$item_id = isset( $cart[ $key ]['id'] ) ? $cart[ $key ]['id'] : null;
			unset( $cart[ $key ] );
		}

		$this->contents = $cart;
		$this->update_cart();

		do_action( 'bnd_post_remove_from_cart', $key, $item_id );

		buynowdepot_clear_errors();

		return $this->contents;
	}

	/**
	 * Generate the URL to remove an item from the cart.
	 *
	 * @since 1.0
	 *
	 * @param int $cart_key Cart item key
 	 * @return string $remove_url URL to remove the cart item
	 */
	public function remove_item_url( $cart_key ) {
		global $wp_query;

		if ( defined( 'DOING_AJAX' ) ) {
			$current_page = buynowdepot_get_checkout_uri();
		} else {
			$current_page = buynowdepot_get_current_page_url();
		}

		$remove_url = bnd_add_cache_busting( add_query_arg( array( 'cart_item' => $cart_key, 'bnd_action' => 'remove' ), $current_page ) );

		return apply_filters( 'bnd_remove_item_url', $remove_url );
	}

	/**
	 * Generate the URL to remove a fee from the cart.
	 *
	 * @since 1.0
	 *
	 * @param int $fee_id Fee ID.
	 * @return string $remove_url URL to remove the cart item
	 */
	public function remove_fee_url( $fee_id = '' ) {
		global $post;

		if ( defined('DOING_AJAX') ) {
			$current_page = buynowdepot_get_checkout_uri();
		} else {
			$current_page = buynowdepot_get_current_page_url();
		}

		$remove_url = add_query_arg( array( 'fee' => $fee_id, 'bnd_action' => 'remove_fee', 'nocache' => 'true' ), $current_page );

		return apply_filters( 'bnd_remove_fee_url', $remove_url );
	}

	/**
	 * Empty the cart
	 *
	 * @since 1.0
	 * @return void
	 */
	public function empty_cart() {
		// Remove cart contents.
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'bnd_cart_content', NULL );
		// Remove all cart details.
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'bnd_cart_details', NULL );
		// Remove any resuming payments.
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'bnd_user_addresses', NULL );
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'bnd_delivery_address', NULL );
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'tip_details',NULL);
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'coupon_data',NULL);
		$this->contents = array();
		do_action( 'bnd_empty_cart' );
	}

	/**
	 * Remove discount from the cart
	 *
	 * @since 1.0
	 * @return array Discount codes
	 */
	public function remove_discount( $code = '' ) {
		if ( empty( $code ) ) {
			return;
		}

		if ( $this->discounts ) {
			$key = array_search( $code, $this->discounts );

			if ( false !== $key ) {
				unset( $this->discounts[ $key ] );
			}

			$this->discounts = implode( '|', array_values( $this->discounts ) );

			// update the active discounts
			Bnd_Flex_Order_Delivery_Session::instance()->set( 'cart_discounts', $this->discounts );
		}

		do_action( 'bnd_cart_discount_removed', $code, $this->discounts );
		do_action( 'bnd_cart_discounts_updated', $this->discounts );

		return $this->discounts;
	}

	/**
	 * Remove all discount codes
	 *
	 * @since 1.0
	 * @return void
	 */
	public function remove_all_discounts() {
		Bnd_Flex_Order_Delivery_Session::instance()->set( 'cart_discounts', null );
		do_action( 'bnd_cart_discounts_removed' );
	}

	/**
	 * Shows the fully formatted cart discount
	 *
	 * @since 1.0
	 *
	 * @param bool $echo Echo?
	 * @return string $amount Fully formatted cart discount
	 */
	public function display_cart_discount( $echo = false ) {
		$discounts = $this->get_discounts();

		if ( empty( $discounts ) ) {
			return false;
		}

		$discount_id  = buynowdepot_get_discount_id_by_code( $discounts[0] );
		$amount       = bnd_format_discount_rate( buynowdepot_get_discount_type( $discount_id ), buynowdepot_get_discount_amount( $discount_id ) );

		if ( $echo ) {
			echo $amount;
		}

		return $amount;
	}

	/**
	 * Checks to see if an item is in the cart.
	 *
	 * @since 1.0
	 *
	 * @param int   $fooditem_id Download ID of the item to check.
 	 * @param array $options
	 * @return bool
	 */
	public function is_item_in_cart($params) {
		$cart = $this->get_contents();

		$ret = false;

		if ( is_array( $cart ) ) {
			foreach ( $cart as $item ) {
				if ( $item['id'] == $fooditem_id ) {
					if ( isset( $options['price_id'] ) && isset( $item['options']['price_id'] ) ) {
						if ( $options['price_id'] == $item['options']['price_id'] ) {
							$ret = true;
							break;
						}
					} else {
						$ret = true;
						break;
					}
				}
			}
		}
		return (bool) apply_filters( 'bnd_item_in_cart', $ret, $fooditem_id, $options );
	}


	/**
	 * Get Cart Items Subtotal.
	 *
	 * @since 1.0
	 *
	 * @param array $items Cart items array
 	 * @return float items subtotal
	 */
	public function get_cart_totals() {

		$totals = array();
		$subtotal=0.0;
		$tax =0.0;
		$total=0.0;
		$fees = 0.0;
		$discounts=0.0;
		if ( is_array( $this->details ) && ! empty( $this->details ) ) {
		    foreach($this->details as $key=>$value) {
		        $subtotal+=$value["subtotal"];
		        $tax+=$value["tax"];
		        $total+=$value["total"];
		        $fees+=$value["fees"];
		        $discounts+=$value["discounts"];
		    }
		}
		$totals["subtotal"]=$subtotal;
		$totals["tax"]=$tax;
		$totals["total"]=$total;
		$totals["fees"]=$fees;
		$totals["discounts"]=$discounts;
		return $totals;
	}

	/**
	 * Get Discountable Subtotal.
	 *
	 * @since 1.0
	 * @return float Total discountable amount before taxes
	 */
	public function get_discountable_subtotal( $code_id ) {
		$cart_items = $this->get_contents_details();
		$items      = array();

		$excluded_products = buynowdepot_get_discount_excluded_products( $code_id );

		if ( $cart_items ) {
			foreach( $cart_items as $item ) {
				if ( ! in_array( $item['id'], $excluded_products ) ) {
					$items[] =  $item;
				}
			}
		}

		$subtotal = $this->get_items_subtotal( $items );

		return apply_filters( 'buynowdepot_get_cart_discountable_subtotal', $subtotal );
	}

	/**
	 * Get Discounted Amount.
	 *
	 * @since 1.0
	 *
	 * @param bool $discounts Discount codes
	 * @return float|mixed|void Total discounted amount
	 */
	public function get_discounted_amount( $discounts = false ) {

		$amount = 0.00;
		$items  = $this->get_contents_details();

		if ( $items ) {
			$discounts = wp_list_pluck( $items, 'discount' );

			if ( is_array( $discounts ) ) {
				$discounts = array_map( 'floatval', $discounts );
				$amount    = array_sum( $discounts );
			}
		}

		return apply_filters( 'buynowdepot_get_cart_discounted_amount', $amount );
	}

	/**
	 * Get Cart Subtotal.
	 *
	 * Gets the total price amount in the cart before taxes and before any discounts.
	 *
	 * @since 1.0
	 *
	 * @return float Total amount before taxes
	 */
	public function get_subtotal() {
		$items    = $this->get_contents_details();
		$subtotal = $this->get_items_subtotal( $items );
		return apply_filters( 'buynowdepot_get_cart_subtotal', $subtotal );
	}

	/**
	 * Subtotal (before taxes).
	 *
	 * @since 1.0
	 * @return float Total amount before taxes fully formatted
	 */
	public function subtotal() {
		return esc_html( bnd_currency_filter( bnd_format_amount( buynowdepot_get_cart_subtotal() ) ) );
	}


	/**
	 * Get Total Cart Amount.
	 *
	 * @since 1.0
	 *
	 * @param bool $discounts Array of discounts to apply (needed during AJAX calls)
	 * @return float Cart amount
	 */
	public function get_total( $discounts = false ) {

		$subtotal     = (float) $this->get_subtotal();
		$discounts    = (float) $this->get_discounted_amount();
		$fees         = (float) $this->get_total_fees();
		$cart_tax     = (float) $this->get_tax();
		$total_wo_tax = $subtotal - $discounts + $fees;
		$total        = $subtotal - $discounts + $cart_tax + $fees ;

		if ( $total < 0 || ! $total_wo_tax > 0 ) {
			$total = 0.00;
		}

		$this->total = (float) apply_filters( 'buynowdepot_get_cart_total', $total );
		return round( $this->total, 2 );
	}

	/**
	 * Fully Formatted Total Cart Amount.
	 *
	 * @since 1.0
	 *
	 * @param bool $echo
	 * @return mixed|string|void
	 */
	public function total( $echo ) {
		$total = apply_filters( 'bnd_cart_total', bnd_currency_filter( bnd_format_amount( $this->get_total() ) ) );

		if ( ! $echo ) {
			return $total;
		}

		echo $total;
	}

	/**
	 * Get Cart Fee Total
	 *
	 * @since 1.0
	 * @return double
	 */
	public function get_total_fees() {
		$fee_total = 0.00;

		foreach ( $this->get_fees() as $fee ) {

			// Since fees affect cart item totals, we need to not count them towards the cart total if there is an association.
			if ( ! empty( $fee['fooditem_id'] ) ) {
				continue;
			}

			$fee_total += $fee['amount'];
		}

		return apply_filters( 'buynowdepot_get_fee_total', $fee_total, $this->fees );
	}

	/**
	 * Get the price ID for an item in the cart.
	 *
	 * @since 1.0
	 *
	 * @param array $item Item details
	 * @return string $price_id Price ID
	 */
	public function get_item_price_id( $item = array() ) {

		if ( isset( $item['item_number'] ) ) {
			$price_id = isset( $item['item_number']['options']['price_id'] ) ? $item['item_number']['options']['price_id'] : null;
		} else if ( isset( $item['options'] ) ) {
			$price_id = isset( $item['options']['price_id'] ) ? $item['options']['price_id'] : null;
		} else {
			$price_id = isset( $item['price_id'] ) ? $item['price_id'] : null;
		}

		return $price_id;
	}

	/**
	 * Get the price name for an item in the cart.
	 *
	 * @since 1.0
	 *
	 * @param array $item Item details
	 * @return string $name Price name
	 */
	public function get_item_price_name( $item = array() ) {

		$price_id = (int) $this->get_item_price_id( $item );
		$prices   = buynowdepot_get_variable_prices( $item['id'] );
		$name     = ! empty( $prices[ $price_id ] ) ? $prices[ $price_id ]['name'] : '';

		return apply_filters( 'buynowdepot_get_cart_item_price_name', $name, $item['id'], $price_id, $item );
	}

	/**
	 * Get the name of an item in the cart.
	 *
	 * @since 1.0
	 *
	 * @param array $item Item details
	 * @return string $name Item name
	 */
	public function get_item_name( $item = array() ) {

		$item_title = get_the_title( $item['id'] );

		if ( empty( $item_title ) ) {
			$item_title = $item['id'];
		}

		if ( bnd_has_variable_prices( $item['id'] ) && false !== buynowdepot_get_cart_item_price_id( $item ) ) {
			$item_title .= ' - ' . buynowdepot_get_cart_item_price_name( $item );
		}

		return apply_filters( 'buynowdepot_get_cart_item_name', $item_title, $item['id'], $item );
	}

	/**
	 * Get all applicable tax for the items in the cart
	 *
	 * @since 1.0
	 * @return float Total tax amount
	 */
	public function get_tax() {
		$cart_tax     = 0;
		$items        = $this->get_contents_details();

		if ( $items ) {

			$taxes = wp_list_pluck( $items, 'tax' );

			if ( is_array( $taxes ) ) {
				$cart_tax = array_sum( $taxes );
			}
		}

		//$cart_tax += $this->get_tax_on_fees();

		$subtotal = $this->get_subtotal();
		if ( empty( $subtotal ) ) {
			$cart_tax = 0;
		}

		$cart_tax = apply_filters( 'buynowdepot_get_cart_tax', bnd_sanitize_amount( $cart_tax ) );

		return $cart_tax;
	}

	/**
	 * Gets the total tax amount for the cart contents in a fully formatted way
	 *
	 * @since 1.0
	 *
	 * @param boolean $echo Decides if the result should be returned or not
	 * @return string Total tax amount
	 */
	public function tax( $echo = false ) {
		$cart_tax = $this->get_tax();
		$cart_tax = bnd_currency_filter( bnd_format_amount( $cart_tax ) );

		$tax = max( $cart_tax, 0 );
		$tax = apply_filters( 'bnd_cart_tax', $cart_tax );

		if ( ! $echo ) {
			return $tax;
		} else {
			echo $tax;
		}
	}

	/**
	 * Get tax applicable for fees.
	 *
	 * @since 1.0
	 * @return float Total taxable amount for fees
	 */
	public function get_tax_on_fees() {
		$tax  = 0;
		$fees = buynowdepot_get_cart_fees();

		if ( $fees ) {
			foreach ( $fees as $fee_id => $fee ) {
				if ( ! empty( $fee['no_tax'] ) || $fee['amount'] < 0 ) {
					continue;
				}

				/**
				 * Fees (at this time) must be exclusive of tax
				 */
				add_filter( 'bnd_prices_include_tax', '__return_false' );
				$tax += bnd_calculate_tax( $fee['amount'] );
				remove_filter( 'bnd_prices_include_tax', '__return_false' );
			}
		}

		return apply_filters( 'buynowdepot_get_cart_fee_tax', $tax );
	}
}
