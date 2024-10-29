<?php
/**
 * This class performs business logic level interactions
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
class Bnd_Flex_Order_Delivery_Repository {

    private $model;
    
    /**
     * Get the blog url for cdn purpose
     * @var bool
     */
    private $blogUrl;
    
    private $settings;


    function __construct() {
        $this->model = new Bnd_Flex_Order_Delivery_Db();
        $this->settings = (array) get_option("bnd_settings");
    }
    
    public function addItemToCart($params) {
        $item_clid      = $params['item'];
        $quantity       = (intval($params['quantity'])>1)?intval($params['quantity']):1;
        $params['quantity']=$quantity;   
        $item = $this->model->getItemByCloverId($item_clid);
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        if($item){
            $stock_response = $this->checkStock($item, $quantity);
            if ($stock_response["status"]=="success") {
                $cart = new Bnd_Flex_Order_Delivery_Cart();
                $cart->addItem($params);
                $session->set("bnd_cart_content",$cart->get_contents());
                $session->set("bnd_cart_details",$cart->get_details());
                $response = array(
                    'status'	=> 'success',
                    'message'      => buynowdepot_get_message("item_added",array("name"=>$item->name, "quantity"=>$quantity)),
                    'data' => $cart->get_details()
                );
            }
            else {
                return $stock_response;
            }
        } else {           
            $response = array(
                'status'	=> 'error',
                'message'   => 'Item not found in database, please refresh the page'
            );
            
        }
        return $response;
    }
   
    
    public function updateQuantity($key, $quantity) {
        
        $cart_content = Bnd_Flex_Order_Delivery_Session::instance()->get('bnd_cart_content');
        if (isset($cart_content) && $quantity > 0){
            
            $item_clid = "";
            if (empty($cart_content[$key])) {
                return array("status"=>"error", "message"=>buynowdepot_get_message("item_not_in_cart"));
            }
            $item_clid = $cart_content[$key]["item"];
            $item = $this->model->getItemByCloverId($item_clid);
            $stock_response = $this->checkStock($item, $quantity);
            if ($stock_response["status"]=="success") {
                $cart_content[$key]["quantity"]=$quantity;
                $session = Bnd_Flex_Order_Delivery_Session::instance();
                $session->set("bnd_cart_content",$cart_content);
                $cart = new Bnd_Flex_Order_Delivery_Cart();
                $cart->populate_contents_details();
                $session->set("bnd_cart_details",$cart->get_details());
                $response = array(
                    'status'	=> 'success',
                    'message'      => buynowdepot_get_message("item_quantity_updated",array("name"=>$item->name, "quantity"=>$quantity)),
                    'data' => $cart->get_details()
                );
            }
            else {
                return $stock_response;
            }
            return $response;
        }
        else
        {
            $response = array(
                'status'	=> 'error',
                'message'   => 'Item not found'
            );
            return $response;
        }
    }
    
    public function deleteItemFromCart($key) {
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        $cart_content = $session->get('bnd_cart_content');
        if (isset($cart_content)){           
            $item_clid = "";
            if (empty($cart_content[$key])) {
                return array("status"=>"error", "message"=>buynowdepot_get_message("item_not_in_cart"));
            }
            unset($cart_content[$key]);           
            $session->set("bnd_cart_content",$cart_content);
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            $response = array(
                'status'	=> 'success',
                'message'      => buynowdepot_get_message("item_deleted",array("name"=>$item->name)),
                'data' => $cart->get_details()
            );
            return $response;
        }
        else
        {
            $response = array(
                'status'	=> 'error',
                'message'   => 'Item not found'
            );
            return $response;
        }
    }
    
    public function getCartDetails() {
        $cart = new Bnd_Flex_Order_Delivery_Cart();
        $cart->populate_contents_details();
        return $cart->details;
    }
    
    public function checkStock($item, $quantity) {
        $isStockTrackingEnabled = buynowdepot_get_option("track_stock");
        if($isStockTrackingEnabled)
        {
            $itemStocks = $this->api->getItemStocks();
            $itemStock  = $this->getItemStock($itemStocks,$item->clid);
            if($itemStock != false && isset($itemStock->stockCount) && $itemStock->stockCount==0 && ($quantity>$itemStock->stockCount))
            {                   
                $response = array(
                    'status'	=> 'error',
                    'message'   => buynowdepot_get_message("low_on_stock",array("quantity"=>$itemStock->stockCount))
                );
                return $response;
            }
        }
        return array("status"=>"success");
    }
    
    public function getOrderDetails($clid) {
        $response = array();
        $order = $this->model->getByCloverId("order", $clid);
        if (!$order) return $response;
        $lineItems = $this->model->getOrderLineItems($order->order_number);
        $items = array();
        foreach($lineItems as $line) {
            $item = $this->model->getByCloverId("item", $line->item_clid);
            $modifications = $line->modification_ids;
            $mdarray = explode(",",$modifications);
            $modifications = array();
            foreach($mdarray as $mod) {
                array_push($modifications, $this->model->getByCloverId("modifier", $mod));
            }
            $lineData = array("line"=>$line, "item"=>$item, "modifications"=>$modifications, "price"=>$item->price);
            array_push($items, $lineData);
        }
        $response["order"]=$order;
        $response["lineItems"]=$items;
        return $response;
    }
    
    public function getOrdersForUser() {
        $all_orders = array();
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $orders = $this->model->getAllModels("order",array("user_login"=>$user->data->user_email ));
            foreach($orders as $order) {
                $order_array = array();
                $lineItems = $this->model->getOrderLineItems($order->order_number);
                $items = array();
                foreach($lineItems as $line) {
                    $item = $this->model->getByCloverId("item", $line->item_clid);
                    $modifications = $line->modification_ids;
                    $mdarray = explode(",",$modifications);
                    $modifications = array();
                    foreach($mdarray as $mod) {
                        array_push($modifications, $this->model->getByCloverId("modifier", $mod));
                    }
                    $lineData = array("line"=>$line, "item"=>$item, "modifications"=>$modifications, "price"=>$item->price);
                    array_push($items, $lineData);
                }
                $customer = $this->model->getOrderCustomer($order->order_number);
                $order_array["order"]=$order;
                $order_array["lineItems"]=$items;
                $order_array["customer"]=$customer;
                array_push($all_orders, $order_array);
            }
        }
        return $all_orders;
    }
    
    public function getRecentOrders($limit) {
        $response = array();
        $orders = $this->model->getRecentOrders($limit);   
        $all_orders = array();
        foreach($orders as $order) {
            $order_array = array();
            $lineItems = $this->model->getOrderLineItems($order->order_number);
            $items = array();
            foreach($lineItems as $line) {
                $item = $this->model->getByCloverId("item", $line->item_clid);
                $modifications = $line->modification_ids;
                $mdarray = explode(",",$modifications);
                $modifications = array();
                foreach($mdarray as $mod) {
                    array_push($modifications, $this->model->getByCloverId("modifier", $mod));
                }
                $lineData = array("line"=>$line, "item"=>$item, "modifications"=>$modifications, "price"=>$item->price);
                array_push($items, $lineData);
            }
            $customer = $this->model->getOrderCustomer($order->order_number);
            $order_array["order"]=$order;
            $order_array["lineItems"]=$items;
            $order_array["customer"]=$customer;
            array_push($all_orders, $order_array);
        }
        $response["orders"]=$all_orders;
        return $response;
    }
    
    public function getOrdersByWeek() {
        $timestamp = strtotime('-6 days');
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            array_push($days,strftime('%d/%m', $timestamp));
            $timestamp = strtotime('+1 day', $timestamp);
        }
        $orders = $this->model->getOrdersByWeek();
        $order_array = array();
        foreach($days as $day) {
            $dayInOrder=false;
            foreach($orders as $order) {                
                if ($day == $order->odate) {
                    array_push($order_array,$order->count);
                    $dayInOrder=true;
                    break;
                }
            }
            if(!$dayInOrder) {
                array_push($order_array,0);
            }
        }
        return array("labels"=>$days,"values"=>$order_array);
    }
    
    public function getRevenueByWeek() {
        $timestamp = strtotime('-6 days');
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            array_push($days,strftime('%d/%m', $timestamp));
            $timestamp = strtotime('+1 day', $timestamp);
        }
        $revenues = $this->model->getRevenueByWeek();
        $revenue_array = array();
        foreach($days as $day) {
            $dayInOrder=false;
            foreach($revenues as $revenue) {
                if ($day == $revenue->odate) {
                    array_push($revenue_array,$revenue->total/100);
                    $dayInOrder=true;
                    break;
                }
            }
            if(!$dayInOrder) {
                array_push($revenue_array,0);
            }
        }
        return array("labels"=>$days,"values"=>$revenue_array);
    }
    
    public function getCategoriesChartData() {
        $datas = $this->model->getBestSellerCategories();
        $labels = array();
        $values = array();
        foreach($datas as $data) {
            array_push($labels, $data->name);
            array_push($values, $data->total);
        }
        return array("labels"=>$labels,"values"=>$values);
    }
    
    public function getBestSellers() {
        $datas = $this->model->getBestSellers();
        $seller_array = array();
        foreach($datas as $data) {
            $item = $this->model->getItemDetails($data->item_clid);
            array_push($seller_array, array("item"=>$item[0]->item_name, "count"=>$data->total, "category"=>$item[0]->category_name));
        }
        return $seller_array;
    }
    
    public function getCategoriesItems($params )
    {
        $response = array();
        $cats = $this->model->getCategories();
        if($cats) {      
            foreach ($cats as $cat) {
                if($cat->display == "1") {
                    $c = array(
                        "id"=>$cat->id,
                        "clid"=>$cat->clid,
                        "name"=> "",
                        "description"   => (isset($cat->description))?stripslashes($cat->description):"",
                        "image_link"=>buynowdepot_get_image_url($cat->image_link)
                    );
                    $useAlternateNames = $this->settings["use_alternate_name"];
                    if($useAlternateNames && isset($cat->alternate_name) && $cat->alternate_name!==""){
                        $c["name"]=stripslashes($cat->alternate_name);
                    } else {
                        $c["name"]=stripslashes($cat->name);
                    }
                    $track_stock=false;
                    $catAvailable = true;
                    $c["available"] = $catAvailable;
                    if(isset($params["with_items"])) {
                        $c['items'] = array();
                        $categoryItems = $this->model->getItemsByCategory($cat->clid);
                        if(isset($categoryItems)) {
                            $c["item_count"]= count($categoryItems);
                            $total_price=0;
                            foreach ($categoryItems as $item) {
                                if(!$item)
                                    continue;
                                  
                                    $final_item = array();                                   
                                    if($track_stock)
                                        $itemStock = self::getItemStock($itemStocks,$item->clid);
                                    else
                                        $itemStock = false;
                                                                                        
                                     if($item->quantity == 1 || ($track_stock == true && $itemStock != false && isset($itemStock->stockCount)  && $itemStock->stockCount < 1))
                                     {
                                            if(isset($this->settings["track_stock_hide_items"]) && $this->settings["track_stock_hide_items"] === "on"){
                                                continue;
                                            }
                                            $final_item['stockCount'] = "out_of_stock";
                                        } else {
                                            if(isset($itemStock->stockCount))
                                                $final_item['stockCount'] = $itemStock->stockCount;
                                                else
                                                    $final_item['stockCount'] = ($track_stock)?"tracking_stock":"not_tracking_stock";
                                        }
                                        $final_item["clid"]=$item->clid;
                                        $final_item["name"]           =   stripslashes($item->name);
                                        $final_item["alternate_name"] =   stripslashes($item->alternate_name);
                                        $final_item["description"]    =   stripslashes($item->description);
                                        $final_item["price"]          =   '$'.number_format(($item->price/100), 2);
                                        $final_item["price_val"]          =  $item->price;
                                        $final_item["price_type"]     =   $item->price_type;
                                        $final_item["price_unit"]      =   $item->price_unit;
                                        $final_item["label"]      =   $item->label;
                                        $final_item["sort_order"]     =   intval($item->sort_order);
                                        $final_item["has_modifiers"]  =   ($this->model->itemHasModifiers($item->clid)->total>0)?true:false;
                                        $final_item["tags"]  =   $this->getItemTags($item->clid);
                                        $final_item["image_link"] = buynowdepot_get_image_url($this->model->getDefaultItemImage($item->clid)->image_url);
                                        $final_item["modifiers"]=$this->getItemModifiersMain($item);
                                        $total_price+=$item->price;
                                        
                                        if($useAlternateNames  && isset($item->alternate_name) && $item->alternate_name!==""){
                                            $final_item["name"]=stripslashes($item->alternate_name);
                                        } else {
                                            $final_item["name"]=stripslashes($item->name);
                                        }
                                        array_push($c['items'],$final_item);
                                        
                                        if(isset($this->settings["track_stock_hide_items"]) && $this->settings["track_stock_hide_items"] === "on"){
                                            $count++;
                                            if($count === $limit){
                                                break;
                                            }
                                        }
                                }
                            }
                        }
                        array_push($response,$c);
                }
            }
        }
        // Return all of our post response data.
        return $response;
    }
    
    
    public function getItemModifiersAll($item) {
        $single_modifiers = array();
        $multiple_modifiers = array();
        $modifiersgroup = $this->model->getItemModifiersGroup($item->clid);
        foreach ($modifiersgroup as $mg) {
            $group_type="";
            if($mg->min_required==1 && $mg->max_allowed==1)
            {
                $group_type="single";
            }
            else {
                $group_type="multiple";
            }
            $modifiers = $this->model->getModifiers($mg->clid);
            $modifier_items = array();
            if( count($modifiers) == 0) continue;
            foreach ($modifiers as $moditem) {
                $price=0;
                if ($mg->show_by_default) {
                    $price = $item->price+$moditem->price;
                }
                else {
                    $price = $moditem->price;
                }
                array_push($modifier_items, array("id" => $moditem->id, "name" => $moditem->name, "price"=> number_format($price/100, 2), "gid"=>$mg->id));
            }
            if ($group_type=="single") {
                array_push($single_modifiers, array("id"=>$mg->id, "name"=>$mg->name, "modifiers"=>$modifier_items));
            }
            else {
                array_push($multiple_modifiers, array("id"=>$mg->id, "name"=>$mg->name, "modifiers"=>$modifier_items));
            }
        }
        return array("item"=>$item, "single_modifiers"=>$single_modifiers,"multi_modifiers"=>$multiple_modifiers);
    }
    
    public function getItemModifiersMain($item) {
        $modifiers_list = array();
        $modifiersgroup = $this->model->getDefaultItemModifiersGroup($item->clid);
        foreach ($modifiersgroup as $mg) {
            $group_type="";
            if($mg->min_required==1 && $mg->max_allowed==1)
            {
                $group_type="select";
            }
            else {
                $group_type="checkbox";
            }
            $modifiers = $this->model->getModifiers($mg->clid);
            $modifier_items = array();
            if( count($modifiers) == 0) continue;
            foreach ($modifiers as $moditem) {
                array_push($modifier_items, array("id" => $moditem->id, "name" => $moditem->name, "price"=>number_format(($item->price+$moditem->price)/100, 2), "gid"=>$mg->id));
            }
            $modifiers_list[$mg->name]=array("id"=>$mg->id, "group_type" => $group_type, "items"=>$modifier_items);
        }
        return $modifiers_list;
    }
    
    public function getItemTags($clid) {
        $tags = $this->model->getItemTags($clid);
        $newtags = array();
        $newtag = array();
        foreach($tags as $tag) {
            $newtag["clid"]=$tag->clid;
            $newtag["name"]=$tag->name;
            $image_url = esc_url(buynowdepot_get_image_url($tag->image_link));
            $headers = get_headers($image_url);
            if (strpos($headers[0],"404")) {
                $image_url = "";
            }
            $newtag["image_link"]=$image_url;
            array_push($newtags, $newtag);
        }
        return $newtags;
    }
    
    public function addAddress($params) {
        //add address to the session
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        //check if user is logged in already
        if (is_user_logged_in()) {
            global $wpdb;
            $user = wp_get_current_user();
            $user_id = $user->data->user_login;
            $result = $wpdb->insert("{$wpdb->prefix}bnd_user_address", array('first_name' => $params["first_name"],
                'last_name' => $params["last_name"],
                'address1' => $params["address1"],
                'address2' => $params["address2"],
                'address3' => $params["address3"],
                'city' => $params["city"],
                'state' => $params["state"],
                'zip' => $params["zip"],
                'country' => $params["country"],
                'email' => $params["email"],
                'phone_number' => $params["phone_number"],
                'address_type' => $params["address_type"],
                'is_default'=>0,
                'user_id' =>$user_id
            ),array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%s'));
            if ($result) {
                $response = array(
                    'status'	=> 'success',
                    'message'      => buynowdepot_get_message("address_added"),
                );
            }
            else {
                $response = array(
                    'status'	=> 'error',
                    'message'      => "Error while adding address",
                );
            }
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            $response["data"]=$cart->get_details();
            return $response;
        }
        else {
            $params["id"]=uniqid();
            $session->set("bnd_delivery_address",$params);
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            $response = array(
                'status'	=> 'success',
                'message'      => buynowdepot_get_message("address_added"),
                'data' => $cart->get_details()
            );
            return $response;
        }
    }
    
    public function getAddress($params) {
        //check if user is logged in already
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $addresses = $this->model->getAllModels("user_address", array("user_id"=>$user->data->user_email));
            if (!empty($params["id"])) {
                foreach ($addresses as $address) {
                    if ($address->id==$params["id"]) {
                        return wp_send_json($address);
                    }
                }
            }
        }
        else {
            //update existing address to the session
            $session = Bnd_Flex_Order_Delivery_Session::instance();
            $address = $session->get("bnd_delivery_address");
            if (isset($address)) {
                return wp_send_json($address);
            }
        }
        return wp_send_json(array());
    }
    
    public function updateAddress($params) {
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        //check if user is logged in already
        if (is_user_logged_in()) {
            global $wpdb;
            $result = $wpdb->update("{$wpdb->prefix}bnd_user_address", array(
                'first_name' => $params["first_name"],
                'last_name' => $params["last_name"],
                'address1' => $params["address1"],
                'address2' => $params["address2"],
                'address3' => $params["address3"],
                'city' => $params["city"],
                'state' => $params["state"],
                'zip' => $params["zip"],
                'country' => $params["country"],
                'email' => $params["email"],
                'phone_number' => $params["phone_number"],
                'address_type' => $params["address_type"]
            ), array("id"=>$params["id"]));
            if ($result) {
                $response = array(
                    'status'	=> 'success',
                    'message'      => buynowdepot_get_message("address_updated"),
                );
            }
            else {
                $response = array(
                    'status'	=> 'error',
                    'message'      => "Error while updating address",
                );
            }
            $session->set("bnd_delivery_address", $params);
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            $response["data"]=$cart->get_details();
            return $response;
        }
        else {
            //update existing address to the session
            $session = Bnd_Flex_Order_Delivery_Session::instance();
            $session->set("bnd_delivery_address", $params);
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            $response = array(
                'status'	=> 'success',
                'message'      => buynowdepot_get_message("address_updated"),
                'data' => $cart->get_details()
            );
            return $response;
        }
    }
    
    public function selectAddress($params) {
        //check if user is logged in already
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        if (is_user_logged_in()) {
            //add address to the database
            $user = wp_get_current_user();
            $addresses = $this->model->getAllModels("user_address", array("user_id"=> $user->data->user_email));
            if (!empty($params["id"])) {
                foreach ($addresses as $address) {
                    if ($address->id==$params["id"]) {
                        $session->set("bnd_delivery_address",$address);
                        break;
                    }
                }
            }
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $response = array(
                'status'	=> 'success',
                'message'      => buynowdepot_get_message("address_selected"),
                'data' => $cart->get_details()
            );
            return $response;
        }
        return  array(
            'status'	=> 'error',
            'message'      => "Address cannot be selected"
        );
    }
    
    public function removeAddress($params) {
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        //check if user is logged in already
        if (is_user_logged_in()) {
            global $wpdb;
            $result = $wpdb->delete("{$wpdb->prefix}bnd_user_address",  array("id"=>$params["id"]));
            if ($result) {
                $response = array(
                    'status'	=> 'success',
                    'message'      => buynowdepot_get_message("address_deleted"),
                );
            }
            else {
                $response = array(
                    'status'	=> 'error',
                    'message'      => "Error while delete address",
                );
            }
            $selected_address = $session->get("bnd_delivery_address");
            if ($selected_address["id"]==$params["id"]) {
                $session->set("bnd_delivery_address", array());
            }
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            $response["data"]=$cart->get_details();
            return $response;
        }
        else {
            //update existing address to the session
            $session->set("bnd_delivery_address",array());
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart->populate_contents_details();
            $response = array(
                'status'	=> 'success',
                'message'      => buynowdepot_get_message("address_removed"),
                'data' => $cart->get_details()
            );
            return $response;
        }
    }
    
    public function confirmPickup() {
        //update existing address to the session
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        $session->set("order_type", "pickup");
        $cart = new Bnd_Flex_Order_Delivery_Cart();
        $cart->populate_contents_details();
        $session->set("bnd_cart_details",$cart->get_details());
        $response = array(
            'status'	=> 'success',
            'message'      => buynowdepot_get_message("pickup_confirmed"),
            'data' => $cart->get_details()
        );
        return $response;
    }
    
    public function confirmDelivery() {
        //update existing address to the session
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        $session->set("order_type", "delivery");
        $cart = new Bnd_Flex_Order_Delivery_Cart();
        $cart->populate_contents_details();
        $session->set("bnd_cart_details",$cart->get_details());
        $response = array(
            'status'	=> 'success',
            'message'      => buynowdepot_get_message("delivery_confirmed"),
            'data' => $cart->get_details()
        );
        return $response;
    }
    
    public function saveCategories($categories) {
        global $wpdb;
        $wpdb->hide_errors();
        $count =0;
        $override = buynowdepot_get_option("override_online_category_update");
        foreach ($categories as $cat) {                      
            //check if category exists, if yes update otherwise insert
            $category = $this->model->getCategoryByCloverId($cat["id"]);
            if (isset($category)) {
                $result = $wpdb->update("{$wpdb->prefix}bnd_category", array(
                    'name' => $override?$cat["name"]:$category->name,
                    'sort_order' => $override?$cat["sortOrder"]:$category->sort_order,
                    'display' => $override?1:$category->display,
                    'image_link'=>$override?$cat["name"].'.jpg':$category->image_link
                ), array("clid"=>$cat["id"]));
            }
            else {
                $result = $wpdb->insert("{$wpdb->prefix}bnd_category", array(
                    'clid' => $cat["id"],
                    'name' => $cat["name"],
                    'sort_order' => $cat["sortOrder"],
                    'display' => 1,
                    'image_link'=>$cat["name"].'.jpg'
                ));
            }
            if ($result == 1)
                $count++;
        }
        return $count;
    }
    
    
    public function saveModifierGroups($modifierGroups) {
        global $wpdb;
        $wpdb->hide_errors();
        $count =0;
        foreach ($modifierGroups as $modg) {
            //check if category exists, if yes update otherwise insert
            $modfierGroup = $this->model->getModifierGroupByCloverId($modg["id"]);
            if (isset($modfierGroup)) {
                $result = $wpdb->update("{$wpdb->prefix}bnd_modifier_group", array(
                    'name' => $modg["name"],
                    'alternate_name' => $modg["alternateName"],
                    'sort_order' => $modg["sortOrder"],
                    'min_required' => isset($modg["minRequired"])?$modg["minRequired"]:0,
                    'max_allowed' => $modg["maxAllowed"]
                ), array("clid"=>$modg["id"]));               
            }
            else {
                $result =$wpdb->insert("{$wpdb->prefix}bnd_modifier_group", array(
                    'clid' =>$modg["id"],
                    'name' => $modg["name"],
                    'alternate_name' => $modg["alternateName"],
                    'sort_order' => $modg["sortOrder"],
                    'min_required' => isset($modg["minRequired"])?$modg["minRequired"]:0,
                    'max_allowed' => $modg["maxAllowed"],
                    'show_by_default' =>($modg["showByDefault"]==true)?1:0
                ));
            }
            if ($result == 1)
                $count++;
            $modifiers = $modg["modifiers"]["elements"];
            $this->saveModifiers($modifiers,$modg["id"]);
        }
        return $count;
    }

    public function saveModifiers($modifiers, $modgroup) {
        global $wpdb;
        $wpdb->hide_errors();
        
        foreach ($modifiers as $mod) {
            //check if category exists, if yes update otherwise insert
            $modfier = $this->model->getModifierByCloverId($mod["id"]);
            if (isset($modfier)) {
                $wpdb->update("{$wpdb->prefix}bnd_modifier", array(
                    'name' => $mod["name"],
                    'alternate_name' => $mod["alternateName"],
                    'sort_order' => $mod["sortOrder"],
                    'price' => $mod["price"],
                ), array("clid"=>$mod["id"]));
                
            }
            else {
                $wpdb->insert("{$wpdb->prefix}bnd_modifier", array(
                    'clid' =>$mod["id"],
                    'name' => $mod["name"],
                    'alternate_name' => $mod["alternateName"],
                    'sort_order' => $mod["sortOrder"],
                    'price' => $mod["price"],
                    'modifier_group_clid'=>$modgroup
                ));
            }
        }
    }
    
    public function saveTags($tags) {
        global $wpdb;
        $wpdb->hide_errors();
        $count=0;
        foreach ($tags as $tag) {
            //check if category exists, if yes update otherwise insert
            $currentTag = $this->model->getByCloverId("tag",$tag["id"]);
            if (isset($currentTag)) {
                $result = $wpdb->update("{$wpdb->prefix}bnd_tag", array(
                    'name' => $tag["name"],
                    'image_link' => $tag["name"].".jpg",
                ), array("clid"=>$tag["id"]));
                
            }
            else {
                $result = $wpdb->insert("{$wpdb->prefix}bnd_tag", array(
                    'clid' =>$tag["id"],
                    'name' => $tag["name"],
                    'image_link' => $tag["name"].".jpg",
                ));
            }
            if($result) {
                $count++;
            }
        }
        return $count;
    }
    
    public function saveItems($items) {
        global $wpdb;
        $wpdb->hide_errors(); 
        $count=0;
        foreach ($items as $item) {
            //check if category exists, if yes update otherwise insert
            $currentItem = $this->model->getByCloverId("item",$item["id"]);
            if (isset($currentItem)) {
                $result = $wpdb->update("{$wpdb->prefix}bnd_item", array(
                    'name' => trim($item["name"]),
                    'alternate_name' => trim($item["alternateName"]),
                    'price' => $item["price"],
                    'price_type' => $item["priceType"],
                    'price_unit' => $item["unitName"],
                    'cost' => $item["cost"],
                    'product_code' => $item["productCode"],
                    'sku' => $item["sku"],
                    'quantity' => $item["quantity"],
                    'is_hidden' => ($item["hidden"])?1:0,
                    'is_revenue' => $item["isRevenue"],
                    'sort_order' => $item["sortOrder"],
                    'default_tax_rate' => ($item["defaultTaxRates"] && $item["defaultTaxRates"]==true)?1:0
                ), array("clid"=>$item["id"]));
            }
            else {
                $result = $wpdb->insert("{$wpdb->prefix}bnd_item", array(
                    'clid' => $item["id"],
                    'name' => trim($item["name"]),
                    'alternate_name' => trim($item["alternateName"]),
                    'price' => $item["price"],
                    'price_type' => $item["priceType"],
                    'price_unit' => $item["unitName"],
                    'cost' => $item["cost"],
                    'product_code' => $item["productCode"],
                    'sku' => $item["sku"],
                    'quantity' => $item["quantity"],
                    'is_hidden' => ($item["hidden"])?1:0,
                    'is_revenue' => $item["isRevenue"],
                    'sort_order' => $item["sortOrder"],
                    'default_tax_rate' => ($item["defaultTaxRates"] && $item["defaultTaxRates"]==true)?1:0
                ));
            }
            if (isset($item["modifierGroups"])) {
                $itemModifierGroups = $item["modifierGroups"]["elements"];
                $this->saveModifierGroupItems($itemModifierGroups, $item);
            }
            if (isset($item["tags"])) {
                $itemTags = $item["tags"]["elements"];
                $this->saveItemTags($itemTags, $item);
            }
            if (isset($item["taxRates"])) {
                $itemTaxRates = $item["taxRates"]["elements"];
                $this->saveItemTaxRates($itemTaxRates, $item);
            }
            if (isset($item["categories"])) {
                $itemCategories = $item["categories"]["elements"];
                $this->saveItemCategories($itemCategories, $item);
            }
            //save default image
            $imageName = trim($item["name"]).".jpg";
            $imageNameQuery = str_replace("'","''",$imageName);
            $img = $this->model->getItemImageByName($imageNameQuery, $item["id"]);
            if (!isset($img) || empty($img)) {
                $wpdb->insert("{$wpdb->prefix}bnd_item_image", array(
                    'image_url' =>$imageName,
                    'is_default' =>1,
                    'is_enabled' =>1,
                    'item_clid' => $item["id"]
                ));
            }
            if ($result == 1)
                $count++;
        }
        return $count;
    }
    
    public function saveModifierGroupItems($itemModifierGroups, $item) {
        global $wpdb;
        $wpdb->hide_errors();
        
        foreach ($itemModifierGroups as $modg) {
            //check if category exists, if yes update otherwise insert
            $img = $this->model->getItemModifierGroupByIds($modg["id"], $item["id"]);
            if (!isset($img) || empty($img)) {
                $wpdb->insert("{$wpdb->prefix}bnd_modifier_group_item", array(
                    'modifier_group_clid' =>$modg["id"],
                    'item_clid' => $item["id"]
                ));            
            }
        }
    }
    
    public function saveItemCategories($itemCategories, $item) {
        global $wpdb;
        $wpdb->hide_errors();      
        foreach ($itemCategories as $cat) {
            //check if category exists, if yes update otherwise insert
            $ic = $this->model->getItemCategoryByIds($cat["id"], $item["id"]);
            if (!isset($ic) || empty($ic)) {
                $wpdb->insert("{$wpdb->prefix}bnd_item_category", array(
                    'category_clid' =>$cat["id"],
                    'item_clid' => $item["id"]
                ));
            }
        }
    }
    
    public function saveItemTags($itemTags, $item) {
        global $wpdb;
        $wpdb->hide_errors();       
        foreach ($itemTags as $tag) {
            //check if category exists, if yes update otherwise insert
            $it = $this->model->getItemTagByIds($tag["id"], $item["id"]);
            if (!isset($it) || empty($it)) {
                $wpdb->insert("{$wpdb->prefix}bnd_tag_item", array(
                    'tag_clid' =>$tag["id"],
                    'item_clid' => $item["id"]
                ));
            }
        }
    }
    
    public function saveItemTaxRates($itemRates, $item) {
        global $wpdb;
        $wpdb->hide_errors();
        foreach ($itemRates as $rate) {
            //check if category exists, if yes update otherwise insert
            $ir = $this->model->getItemTaxRateByIds($rate["id"], $item["id"]);
            if (!isset($ir) || empty($ir)) {
                $wpdb->insert("{$wpdb->prefix}bnd_item_tax_rate", array(
                    'tax_rate_clid' =>$rate["id"],
                    'item_clid' => $item["id"]
                ));
            }
        }
    }
    
    public function applyDiscount($params) {
        $couponData = $this->model->getCouponByCode($params["coupon"]);
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        $cart_details = $session->get("bnd_cart_details");
        $totalValue = $cart_details["total"]["subtotal"];
        if ($totalValue < $couponData->min_order_amount*100) {
            return array("status"=>"error", "message"=>"Order amount is less than the minimum value.");
        }
        else {
            $session->set("coupon_data", $couponData);
            $cart = new Bnd_Flex_Order_Delivery_Cart();
            $cart_details = $cart->populate_contents_details();
            $session->set("bnd_cart_details",$cart->get_details());
            return array("status"=>"success", "message"=> "Coupon code applied successfully", "data"=>$cart->get_details());
        }
    }
    
    public function applyTip($params) {
        $session = Bnd_Flex_Order_Delivery_Session::instance();
        $session->set("tip_details", $params);
        $cart = new Bnd_Flex_Order_Delivery_Cart();
        $cart->populate_contents_details();
        $session->set("bnd_cart_details",$cart->get_details());
        return array("status"=>"success", "message"=> "Tip amount applied successfully", "data"=>$cart->get_details());
    }
    
    public function saveTaxRates($taxrates) {
        global $wpdb;
        $wpdb->hide_errors();
        $count=0;
        foreach ($taxrates as $rate) {
            //check if category exists, if yes update otherwise insert
            $currentRate = $this->model->getTaxRateByCloverId($rate["id"]);
            if (isset($currentRate)) {
                $result = $wpdb->update("{$wpdb->prefix}bnd_tax_rate", array(
                    'name' => $rate["name"],
                    'tax_type' => $rate["taxType"],
                    'tax_rate' => $rate["rate"],
                    'tax_amount' => $rate["taxAmount"],
                    'is_default' => $rate["isDefault"],
                ), array("clid"=>$rate["id"]));
                
            }
            else {
                $result = $wpdb->insert("{$wpdb->prefix}bnd_tax_rate", array(
                    'clid' =>$rate["id"],
                    'name' => $rate["name"],
                    'tax_type' => $rate["taxType"],
                    'tax_rate' => $rate["rate"],
                    'tax_amount' => $rate["taxAmount"],
                    'is_default' => $rate["isDefault"]
                ));
            }
            if ($result==1)
                $count++;
        }
        return $count;
    }
    
    
    public function saveMerchant($merchant) {
        global $wpdb;
        $wpdb->hide_errors();
        $count = 0;
        $currentMerchant = $this->model->getByCloverId("merchant", $merchant["id"]);
        if (isset($currentMerchant)) {
            $count = $wpdb->update("{$wpdb->prefix}bnd_merchant", array(
                'name' => $merchant["name"],
                'address1' => $merchant["address"]["address1"],
                'address2' => $merchant["address"]["address2"],
                'address3' => $merchant["address"]["address3"],
                'city' => $merchant["address"]["city"],
                'country' => $merchant["address"]["country"],
                'state' => $merchant["address"]["state"],
                'phone_number'=> $merchant["address"]["phoneNumber"],
                'contact_email'=> $merchant["customerContactEmail"],
                'website'=> $merchant["website"],
                'zip' => $merchant["address"]["zip"],
            ), array("clid"=>$merchant["id"]));
            
        }
        else {
            $count= $wpdb->insert("{$wpdb->prefix}bnd_merchant", array(
                'clid' =>$merchant["id"],
                'name' => $merchant["name"],
                'address1' => $merchant["address"]["address1"],
                'address2' => $merchant["address"]["address2"],
                'address3' => $merchant["address"]["address3"],
                'city' => $merchant["address"]["city"],
                'country' => $merchant["address"]["country"],
                'state' => $merchant["address"]["state"],
                'zip' => $merchant["address"]["zip"],
                'phone_number'=> $merchant["address"]["phoneNumber"],
                'contact_email'=> $merchant["customerContactEmail"],
                'website'=> $merchant["website"],
            ));
        }
        $openingHours = $merchant["openingHours"];
        $this->saveOpeningHours($openingHours, "Merchant");
        return $count;
    }
    
    public function saveMerchantProperties($merchantProperties) {
        global $wpdb;
        $wpdb->hide_errors();
        $count = 0;
        $currentMerchant = $this->model->getByCloverId("merchant", $merchantProperties["merchantRef"]["id"]);
        if (isset($currentMerchant)) {
            $count = $wpdb->update("{$wpdb->prefix}bnd_merchant", array(
                'currency' => $merchantProperties["defaultCurrency"],
                'tips_enabled' => $merchantProperties[tipsEnabled]?1:0,
                'max_tip_percent' => $merchantProperties["maxTipPercentage"],
                'tip_rate_default' => $merchantProperties["tipRateDefault"],
                'group_line_items' => $merchantProperties["groupLineItems"],
                'vat_enabled' => $merchantProperties["vat"]?1:0,
                'vat_name' => $merchantProperties["vatName"]
            ), array("clid"=>$merchantProperties["merchantRef"]["id"]));
            
        }
        return $count;
    }
    
    public function saveMerchantServiceCharge($merchantId, $merchantService) {
        global $wpdb;
        $wpdb->hide_errors();
        $count = 0;
        $currentMerchant = $this->model->getByCloverId("merchant", $merchantId);
        if (isset($currentMerchant)) {
            $count = $wpdb->update("{$wpdb->prefix}bnd_merchant", array(
                'service_charge_id' => $merchantService["id"],
                'service_charge_enabled' => $merchantService[enabled]?1:0,
                'service_charge_name' => $merchantService["name"],
                'service_charge_percent' => $merchantService["percentage"],
                'service_charge_decimal' => $merchantService["percentageDecimal"],
            ), array("clid"=>$merchantId));
            
        }
        return $count;
    }
    
    public function saveOpeningHours($openingHours, $recordType) {
        global $wpdb;
        $wpdb->hide_errors();
        $count = 0;
        $currentOpeningHours = $this->model->getByCloverId("opening_hours", $openingHours["0"]["id"]);
        if (isset($currentOpeningHours)) {          
            $count=$wpdb->update("{$wpdb->prefix}bnd_opening_hours", array(
                'monday' => $openingHours["monday"]["elements"]["start"]."-".$openingHours["monday"]["elements"]["end"],
                'tuesday' => $openingHours["tuesday"]["elements"]["start"]."-".$openingHours["tuesday"]["elements"]["end"],
                'wednesday' => $openingHours["wednesday"]["elements"]["start"]."-".$openingHours["wednesday"]["elements"]["end"],
                'thursday' => $openingHours["thursday"]["elements"]["start"]."-".$openingHours["thursday"]["elements"]["end"],
                'friday' => $openingHours["friday"]["elements"]["start"]."-".$openingHours["friday"]["elements"]["end"],
                'saturday' => $openingHours["saturday"]["elements"]["start"]."-".$openingHours["saturday"]["elements"]["end"],
                'sunday' => $openingHours["sunday"]["elements"]["start"]."-".$openingHours["sunday"]["elements"]["end"],
            ), array("clid"=>$currentOpeningHours));
            
        }
        else {
            $count=$wpdb->insert("{$wpdb->prefix}bnd_opening_hours", array(
                'clid' =>$openingHours["0"]["id"],
                'record_type'=>$recordType,
                'monday' => $openingHours["0"]["monday"]["elements"]["0"]["start"]."-".$openingHours["0"]["monday"]["elements"]["0"]["end"],
                'tuesday' => $openingHours["0"]["tuesday"]["elements"]["0"]["start"]."-".$openingHours["0"]["tuesday"]["elements"]["0"]["end"],
                'wednesday' => $openingHours["0"]["wednesday"]["elements"]["0"]["start"]."-".$openingHours["0"]["wednesday"]["elements"]["0"]["end"],
                'thursday' => $openingHours["0"]["thursday"]["elements"]["0"]["start"]."-".$openingHours["0"]["thursday"]["elements"]["0"]["end"],
                'friday' => $openingHours["0"]["friday"]["elements"]["0"]["start"]."-".$openingHours["0"]["friday"]["elements"]["0"]["end"],
                'saturday' => $openingHours["0"]["saturday"]["elements"]["0"]["start"]."-".$openingHours["0"]["saturday"]["elements"]["0"]["end"],
                'sunday' => $openingHours["0"]["sunday"]["elements"]["0"]["start"]."-".$openingHours["0"]["sunday"]["elements"]["0"]["end"],
            ));
        }
        return $count;
    }
    
    public function saveOrderTypes($ordertypes) {
        global $wpdb;
        $wpdb->hide_errors();
        $count = 0;
        foreach ($ordertypes as $ot) {
            $currentOrderType = $this->model->getByCloverId("order_type", $ot["id"]);
            if (isset($currentOrderType)) {
                $result = $wpdb->update("{$wpdb->prefix}bnd_order_type", array(
                    'label' => $ot["label"],
                    'taxable' => $ot["taxable"],
                    'min_order_amount' => isset($ot["minOrderAmount"])?$ot["minOrderAmount"]:0,
                    'max_order_amount' => isset($ot["maxOrderAmount"])?$ot["maxOrderAmount"]:0,
                    'fee'=>$ot["fee"],
                    'max_radius'=>$ot["maxRadius"],
                    'is_default'=>$ot["isDefault"],
                    'filter_categories'=>$ot["filterCategories"],
                    'is_hidden'=>$ot["isHidden"],
                    'avg_order_time'=>$ot["averageOrderTime"],
                    'hours_available'=>$ot["hoursAvailable"],
                    'hours'=>$ot["hours"]["elements"],
                    'categories'=>$ot["categories"]["elements"]
                ), array("clid"=>$ot["id"]));
                
            }
            else {
                 $result = $wpdb->insert("{$wpdb->prefix}bnd_order_type", array(
                    'clid' => $ot["id"],
                    'label' => $ot["label"],
                    'taxable' => $ot["taxable"],
                    'min_order_amount' => isset($ot["minOrderAmount"])?$ot["minOrderAmount"]:0,
                    'max_order_amount' => isset($ot["maxOrderAmount"])?$ot["maxOrderAmount"]:0,
                    'fee'=>$ot["fee"],
                    'max_radius'=>$ot["maxRadius"],
                    'is_default'=>$ot["isDefault"],
                    'filter_categories'=>$ot["filterCategories"],
                    'is_hidden'=>$ot["isHidden"],
                    'avg_order_time'=>$ot["averageOrderTime"],
                    'hours_available'=>$ot["hoursAvailable"],
                    'hours'=>$ot["hours"]["elements"],
                    'categories'=>$ot["categories"]["elements"]
                ));
            }
            if ($result == 1)
                $count++;
        }
        return $count;
    }
    
    
    function saveOrder($orderDetails, $paymentType) {
        global $wpdb;
        //$wpdb->hide_errors();
        // begin transaction
        $noerror;
        $wpdb->query('START TRANSACTION');
        $dataOrder = array();
        $dataOrder["order_number"]=$orderDetails["order_number"];
        $dataOrder["total"]=$orderDetails["total"];
        $dataOrder["total_discount"]=$orderDetails["total_discount"];
        $dataOrder["total_service_charge"]=$orderDetails["total_fees"];
        $dataOrder["sub_total"]=$orderDetails["subtotal"];
        $dataOrder["total_tax"]=$orderDetails["total_tax"];
        $dataOrder["delivery_charge"]=$orderDetails["delivery_charge"];
        $dataOrder["note"]=$orderDetails["note"];
        $dataOrder["currency"]=$orderDetails["currency"];
        $dataOrder["order_status"]=0;
        $dataOrder["payment_state"]=0;
        $dataOrder["payment_type"]=$paymentType;
        $dataOrder["order_type"]=$orderDetails["order_type"];
        $dataOrder["user_login"]=$orderDetails["user_login"];
        $dataOrder["customer_ref"]=$orderDetails["customer_id"];
        $resultOrder = $wpdb->insert("{$wpdb->prefix}bnd_order", $dataOrder);
        foreach($orderDetails["lineItems"] as $lineItem) {
            $modification_ids='';
            foreach($lineItem["modlist"] as $modifier) {
                $modification_ids.=$modifier["clid"].",";
            }
            $dataItem = array();
            $dataItem["order_number"]=$orderDetails["order_number"];
            $dataItem["item_clid"]=$lineItem["clid"];
            $dataItem["price"]=$lineItem["price"]*$lineItem["quantity"];
            $dataItem["price_with_modification"]=$lineItem["subtotal"];
            $dataItem["modification_ids"]=$modification_ids;
            $dataItem["discount_amount"]=$lineItem["discount"];
            $dataItem["quantity"]=$lineItem["quantity"];
            $dataItem["instructions"]=$lineItem["instructions"];
            $resultItem = $wpdb->insert("{$wpdb->prefix}bnd_order_line_item", $dataItem);     
        }
        $customer = $orderDetails["customer"];
        if ($customer!=null) {
            $customerItem = array();
            $customerItem["clid"]=$orderDetails["customer_id"];
            $customerItem["order_number"]=$orderDetails["order_number"];
            $customerItem["first_name"]=$customer["first_name"];
            $customerItem["last_name"]=$customer["last_name"];
            $customerItem["address_1"]=$customer["address1"];
            $customerItem["address_2"]=$customer["address2"];
            $customerItem["address_3"]=$customer["address3"];
            $customerItem["city"]=$customer["city"];
            $customerItem["country"]=$customer["country"];
            $customerItem["email"]=$customer["email"];
            $customerItem["phone_number"]=$customer["phone_number"];
            $customerItem["state"]=$customer["state"];
            $customerItem["zip"]=$customer["zip"];
            $customerItem["customer_type"]=$customer["customer_type"];
            $customerItem["user_login"]=$orderDetails["user_login"];
            $resultCustomer = $wpdb->insert("{$wpdb->prefix}bnd_order_customer", $customerItem);
        }
        $resultPayment = 1;
        if ($paymentType=="online") {
            $paymentData = array();
            $paymentData["currency"]=$orderDetails["currency"];
            $paymentData["amount"]=$orderDetails["total"];
            $paymentData["tax_amount"]=$orderDetails["total_tax"];
            $paymentData["result"]="pending";
            $paymentData["order_number"]=$orderDetails["order_number"];
            $resultPayment = $wpdb->insert("{$wpdb->prefix}bnd_order_payment", $paymentData);
        }
        if ($resultOrder && $resultItem && $resultCustomer && $resultPayment) {
            $wpdb->query('COMMIT');
            return array("status"=>'success');
        }
        else {
            $wpdb->query('ROLLBACK');
            return array("status"=>'failure', 'error'=>$wpdb->last_error);
        }
        //
    }
    
    function updatePaymentDetails($orderNumber, $payment) {
        global $wpdb;
        $paymentData = array();
        $paymentData["clid"]=$payment["charge"];
        $paymentData["ext_payment_id"]=$payment["ref_num"];
        $seconds = $payment["created"] / 1000;
        $paymentData["created_time"]=date('Y-m-d H:i:s',$seconds);
        $paymentData["result"]=$payment["status"];
        $paymentData["order_clid"]=$payment["id"];
        $resultPayment = $wpdb->update("{$wpdb->prefix}bnd_order_payment", $paymentData, array("order_number"=>$orderNumber));
        if ($resultPayment) {
            return array("status"=>'success', "message"=>"Payment information saved succesfully");
        }
        else {
            error_log("Payment details could not be saved :".$wpdb->last_error);
            return array("status"=>'success', "message"=>"Payment details could not be saved");
        }
    }
    
    function updateOrdersFromClover($orders) {
        global $wpdb;
        $wpdb->hide_errors();
        $count =0;
        foreach ($orders as $odr) {
            //check if category exists, if yes update otherwise insert
            $order = $this->model->getByCloverId("order", $odr["id"]);
            if (isset($order)) {
                $order_status=1;
                if ($order->status=="paid") {
                    $order_status=2;
                }
                $result = $wpdb->update("{$wpdb->prefix}bnd_order", array(
                    'order_status' => $order_status,
                ), array("clid"=>$odr["id"]));
            }
            /*
            else {
                $result = $wpdb->insert("{$wpdb->prefix}bnd_category", array(
                    'clid' => $cat["id"],
                    'name' => $cat["name"],
                    'sort_order' => $cat["sortOrder"],
                    'display' => 1,
                    'image_link'=>$cat["name"].'.jpg'
                ));
            }*/
            if ($result == 1)
                $count++;
        }
        return $count;
    }
    
    function syncOrders($newSyncTime) {
        global $wpdb;
        $syncDetails = $this->model->getAllModels("data_sync", array("sync_enabled"=>1, "model_name"=>"orders"));
        if (isset($synDetails)) {
            $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
            $response = $cloverClient->syncOrders($syncDetails[0]->last_sync_time);
            if ($response["error"]==false) {
                $orderDetails = $response["content"];
                $this->updateOrdersFromClover($orderDetails);
                $this->updateSyncTime('orders', $newSyncTime);
                error_log($orderDetails["message"]);
            } else {
                error_log("Order details could not be updated :".$response["content"]);
            }
        }
    }
    
    function syncItems($newSyncTime) {
        error_log("Syncing categories/items/modifier groups");
        $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
        $syncDetails = $this->model->getAllModels("data_sync", array("sync_enabled"=>1,"model_name"=>"categories"));
        if (isset($syncDetails)) {
            $response = $cloverClient->syncCategories($syncDetails[0]->last_sync_time);
            if ($response["error"]==false) {
                $categoryDetails = $response["content"];
                $count = $this->saveCategories($categoryDetails);
                $this->updateSyncTime('categories', $newSyncTime);
                error_log("".$count." categories updated");
            } else {
                error_log("category could not be updated :".$response["content"]);
            }
        }
        $syncDetails = $this->model->getAllModels("data_sync", array("sync_enabled"=>1,"model_name"=>"items"));
        if (isset($syncDetails)) {
            $response = $cloverClient->syncItems($syncDetails[0]->last_sync_time);
            if ($response["error"]==false) {
                $itemDetails = $response["content"];
                $count = $this->saveCategories($itemDetails);
                $this->updateSyncTime('items', $newSyncTime);
                error_log("".$count." items updated");
            } else {
                error_log("items could not be updated :".$response["content"]);
            }
        }
        $syncDetails = $this->model->getAllModels("data_sync", array("sync_enabled"=>1,"model_name"=>"modifier_groups"));
        if (isset($syncDetails)) {
            $response = $cloverClient->syncModifierGroups($syncDetails[0]->last_sync_time);
            if ($response["error"]==false) {
                $modifierDetails = $response["content"];
                $count = $this->syncModifierGroups($modifierDetails);
                $this->updateSyncTime('modifier_groups', $newSyncTime);
                error_log("".$count." modifier_groups updated");
            } else {
                error_log("modifier_groups could not be updated :".$response["content"]);
            }
        }
    }
    function syncMerchant($newSyncTime) {
        error_log("Syncing merchant");
    }
    function syncOpeningHours() {
        error_log("Syncing opening hours");
    }
    function syncOrderTypes($newSyncTime) {
        error_log("Syncing items");
    }
    function syncTaxRates($newSyncTime) {
        error_log("Syncing tax rates");
    }
    
    function updateSyncTime($model, $newSyncTime) {
        global $wpdb;
        $count = $wpdb->update("{$wpdb->prefix}bnd_data_sync", array(
            'last_sync_time' => $newSyncTime,
        ), array("model_name"=>$model));
    }
}