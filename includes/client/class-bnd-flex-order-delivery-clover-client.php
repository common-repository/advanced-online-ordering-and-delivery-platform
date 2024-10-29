<?php

/**
 * 
 * Class to make API request to clover server
 *  * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @author BuyNowDepot
 *
 */

class Bnd_Flex_Order_delivery_Clover_Client
{
    public const CLOVER_SANDBOX="https://clover-sandbox.buynewdepot.com/";
    public const CLOVER_PROD="https://https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/";
    public const CLOVER_DEV="http://localhost/buynowdepotapi/public/en/cloverapi/callapi";

    private $url_api;
    private $url_clover_platform;
    private $url_clover_tokenize;
    private $url_clover_ecommerce;
    private $url_clover_checkout;
    
    private $apiEnv;
    private $apiRegion;
    private $apiKey;
    private $accessToken;
    private $apiUrls;
    private $merchantId;
    private $respository;
    
    function __construct() {
        $this->init();
        $this->url_api = array("production"=>"https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip", "sandbox"=>"https://buynowdepot.com/is3M5mBEKLKHqWhq");
        $this->url_clover=array("us" => array("production"=>"https://clover.com", "sandbox"=>"https://sandbox.dev.clover.com/"),
            "eu" => array("production"=>"https://eu.clover.com", "sandbox"=>"https://sandbox.dev.clover.com/"));
        $this->url_clover_platform=array("us" => array("production"=>"https://api.clover.com", "sandbox"=>"https://apisandbox.dev.clover.com"),
            "eu" =>array("production"=>"https://api.eu.clover.com", "sandbox"=>"https://apisandbox.dev.clover.com"));
        $this->url_clover_tokenize=array("us" => array("production"=>"https://token.clover.com", "sandbox"=>"https://token-sandbox.dev.clover.com"),
            "eu" =>array("production"=>"https://token.eu.clover.com", "sandbox"=>"https://token-sandbox.dev.clover.com"));
        $this->url_clover_ecommerce=array("us" => array("production"=>"https://scl.clover.com", "sandbox"=>"https://scl-sandbox.dev.clover.com"),
            "eu" =>array("production"=>"https://scl.eu.clover.com", "sandbox"=>"https://scl-sandbox.dev.clover.com"));
        $this->url_clover_checkout=array("us" => array("production"=>"https://checkout.clover.com", "sandbox"=>"https://checkout.sandbox.dev.clover.com"),
            "eu" =>array("production"=>"https://checkout.clover.com", "sandbox"=>"https://checkout.sandbox.dev.clover.com"));
        $this->url_cardpointe_checkout=array("us" => array("production"=>"https://fts.cardconnect.com/cardconnect/rest", "sandbox"=>"https://fts-uat.cardconnect.com/cardconnect/rest"),
            "eu" =>array("production"=>"https://fts.cardconnect.com/cardconnect/rest", "sandbox"=>"https://fts-uat.cardconnect.com/cardconnect/rest"));
        $this->repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
        $this->loadKeyAndToken();
    }
    
    function init() {
        $this->apiUrls = array( 
            'merchant'             => '/v3/merchants/{mid}?expand=address,openingHours,orderTypes',
            'merchant_address'         => '/v3/merchants/{mid}/address',
            'merchant_gateway'     => '/v3/merchants/{mid}/gateway',
            'merchant_properties'     => '/v3/merchants/{mid}/properties',
            'merchant_service_charge'     => '/v3/merchants/{mid}/default_service_charge',
            'merchant_order_types'     => '/v3/merchants/{mid}/order_types?expand=categories,hours',
            'merchant_system_order_types'     => '/v3/merchants/{mid}/system_order_types',
            'merchant_roles'     => '/v3/merchants/{mid}/roles',
            'merchant_tenders'     => '/v3/merchants/{mid}/tenders',
            'merchant_opening_hours'     => '/v3/merchants/{mid}/opening_hours',
            'merchant_cash_events'     => '/v3/merchants/{mid}/cash_events',
            'customers'     => '/v3/merchants/{mid}/customers?expand=emailAddresses,phoneNumbers&filter=firstName={firstName}&filter=lastName={lastName}&filter=emailAddress={emailAddress}&filter=phoneNumber={phoneNumber}',
            'customers_phone'     => '/v3/merchants/{mid}/customers/{cid}/phone_numbers',
            'customers_email_addresses'     => '/v3/merchants/{mid}/customers/{cid}/email_addresses',
            'customers_addresses'     => '/v3/merchants/{mid}/customers/{cid}/addresses',
            'customers_cards'     => '/v3/merchants/{mid}/customers/{cid}/cards',
            'customer_create'     => '/v3/merchants/{mid}/customers',
            'items'     => '/v3/merchants/{mid}/items?expand=tags,categories,taxRates,modifierGroups,itemStock,options',
            'items_by_category'     => '/v3/merchants/{mid}/categories/{cid}/items?expand=tags,categories,taxRates,modifierGroups,itemStock,options',
            'items_create'     => '/v3/merchants/{mid}/items',
            'get_item'     => '/v3/merchants/{mid}/items?filter=name+LIKE%25{iid}%25',
            'item_stocks'     => '/v3/merchants/{mid}/item_stocks',
            'item_groups'     => '/v3/merchants/{mid}/item_groups',
            'tags'     => '/v3/merchants/{mid}/tags',
            'tax_rates'     => '/v3/merchants/{mid}/tax_rates',
            'categories'     => '/v3/merchants/{mid}/categories',
            'modifier_groups'     => '/v3/merchants/{mid}/modifier_groups?expand=modifiers&offset={offset}',
            'modifiers'     => '/v3/merchants/{mid}/modifiers',
            'attributes'     => '/v3/merchants/{mid}/attributes',
            'options'     => '/v3/merchants/{mid}/options',
            'discounts'     => '/v3/merchants/{mid}/discounts',
            'tax_rules'     => '/v3/merchants/{mid}/tax_rules',
            'create_atomic_order'     => '/v3/merchants/{mid}/atomic_order/orders',
            'update_order_customer'   => '/v3/merchants/{mid}/orders/{oid}?expand=customers',
            'orders_sync'     => '/v3/merchants/{mid}/orders?filter=modifiedTime>={last_sync_time}',
            'categories_sync'     => '/v3/merchants/{mid}/categories?filter=modifiedTime>={last_sync_time}',
            'merchant_sync'     => '/v3/merchants/{mid}?filter=modifiedTime>={last_sync_time}',
            'items_sync'     => '/v3/merchants/{mid}/items?expand=tags,categories,taxRates,modifierGroups,itemStock,options&filter=modifiedTime>={last_sync_time}',
            'modifier_groups_sync'     => '/v3/merchants/{mid}/modifier_groups?expand=modifiers&filter=modifiedTime>={last_sync_time}');
    }
    
    function loadKeyAndToken() {
        $BndSettings = (array)get_option("bnd_settings");
        if (isset($BndSettings['api_env'])) {
            $this->apiEnv = $BndSettings['api_env'];
        }
        if (isset($BndSettings['api_region'])) {
            $this->apiRegion = $BndSettings['api_region'];
        }
        if (isset($BndSettings['api_key'])) {
            $this->apiKey = $BndSettings['api_key'];
        } 
        if (isset($BndSettings['access_token'])) {
            $this->accessToken = $BndSettings['access_token'];
        } 
        if (isset($BndSettings['merchant_id'])) {
            $this->merchantId = $BndSettings['merchant_id'];
        } 
    }
    
    public function requestAccessToken(){
        $BndSettings = (array)get_option("bnd_settings");
        $merchant_email = $BndSettings["merchant_login"];
        $endPoint = $this->getBndApiUrl()."/cloverapi/authtoken?merchant_email="+$merchant_email+"&license_key=".$this->apiKey;
        $response = $this->sendGetRequest($endPoint,"");
        if ($response["error"]==false) {
            $responseContent = $response["content"];
            if(isset($responseContent["access_token"])){
                $BndSettings = (array)get_option("bnd_settings");
                $this->accessToken =  $responseContent["access_token"];
                $this->merchantId =  $responseContent["merchant_id"];
                $BndSettings["access_token"] =  $responseContent["access_token"];
                $BndSettings["merchant_id"] =  $responseContent["merchant_id"];
                $BndSettings["payment_key"] =  $responseContent["payment_key"];
                $BndSettings["transaction_mid"] =  $responseContent["transaction_mid"];
                $BndSettings["cp_username"] =  $responseContent["cp_username"];
                $BndSettings["cp_password"] =  $responseContent["cp_password"];
                update_option("bnd_settings", $BndSettings);
                return true;
            }
        } else {
            error_log($response["content"]);
            if($this->debugMode){
                echo "Something went wrong when getting access-token:".$response["content"];
            }
        }
        return false;
    }
    
    public function requestTokenByEmail(){
        $BndSettings = (array)get_option("bnd_settings");
        $merchant_email = $BndSettings["merchant_login"];
        $endPoint = $this->getBndApiUrl()."/api/v1/merchant-access/searchByMail.json?email="+$merchant_email;
        $response = $this->sendGetRequest($endPoint,"");
        if ($response && strlen($response)>0) {
            $responseContent = $response[0];
            if(isset($responseContent["access_token"])){
                $BndSettings = (array)get_option("bnd_settings");
                $this->accessToken =  $responseContent["access_token"];
                $this->merchantId =  $responseContent["merchant_id"];
                $BndSettings["access_token"] =  $responseContent["access_token"];
                $BndSettings["merchant_id"] =  $responseContent["merchant_id"];
                $BndSettings["payment_key"] =  $responseContent["payment_key"];
                $BndSettings["transaction_mid"] =  $responseContent["transaction_mid"];
                $BndSettings["cp_username"] =  $responseContent["cp_username"];
                $BndSettings["cp_password"] =  $responseContent["cp_password"];
                update_option("bnd_settings", $BndSettings);
                return true;
            }
        } 
    }
    
    public function callAPI($apiName, $method, $params, $data) {
        $apiUrl = $this->apiUrls[$apiName];
        foreach($params as $key => $value) {
            $apiUrl = str_replace('{'.$key.'}', $value, $apiUrl);
        }
        $endPoint = $this->getCloverApiUrl().$apiUrl;
        if ($method ==="GET") {
            return $this->sendGetRequest($endPoint, $this->accessToken);
        }
        if ($method ==="POST") {
            return $this->sendPostRequest($endPoint, json_encode($data), $this->accessToken);
        }
    }
    
    public function sendPostRequest($url, $jsonData, $token) {
        $headers = array('Content-Type' => 'application/json', 'Accept'=>'application/json');
        if ($token) {
            $headers["Authorization"]="Bearer ".$token;
        }
        $response = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'body' => $jsonData,
            'cookies' => array()));
        
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return array("error"=>true, "content"=>$error_message);
        } else {
            $status = wp_remote_retrieve_response_code( $response );
            $content =  wp_remote_retrieve_body( $response );
            if($status === 200 ){
                return array("error"=>false, "content"=>json_decode($content,true));
            } else {
                return array("error"=>true, "content"=>json_decode($content,true));
            }
        }
    }
    
    
    public function sendGetRequest($url, $token) {
        
        $headers = array('Content-Type' => 'application/json', 'Accept'=>'application/json');
        if ($token != "") {
            $headers["Authorization"]="Bearer ".$token; 
        }
        $response = wp_remote_get( $url, array(
            'timeout' => 45,
            'blocking' => true,
            'headers' => $headers,
            'cookies' => array()));
        
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return array("error"=>true, "content"=>$error_message);
        } else {
            $status = wp_remote_retrieve_response_code( $response );
            $content =  wp_remote_retrieve_body( $response );
            if($status === 200 ){
                return array("error"=>false, "content"=>json_decode($content,true));
            }
            else {
                return array("error"=>true, "content"=>json_decode($content,true));
            }
        }
    }

    /*
     * This functions import data from Clover POS and call the save functions
     * for example : getCategories get JSON object of categories from Clover POS and call the function save_categories
     * to save the this categories in Wordpress DB
     * Updated to use the new API based on jwt tokens
     * Jan 2021
     */
    public function importCategories() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("categories", "GET", $params, array());
        if ($response["error"]==false) {
            $categories = $response["content"]["elements"];
            $saved = $this->repository->saveCategories($categories);
            return array("status"=>"success", "message"=>"Categories imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importModifierGroups($offset=0) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "offset"=>$offset);
        $response = $this->callAPI("modifier_groups", "GET", $params, array());
        if ($response["error"]==false) {
            $modifierGroups = $response["content"]["elements"];
            $saved = $this->repository->saveModifierGroups($modifierGroups);
            if (count($modifierGroups)==100) {
                $offset=$offset+100;
                return $this->importModifierGroups($offset);
            }
            return array("status"=>"success", "message"=>"Modifier groups imported", "count"=>($offset+count($modifierGroups)));
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importItems() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("items", "GET", $params, array());
        if ($response["error"]==false) {
            $items = $response["content"]["elements"];
            $saved = $this->repository->saveItems($items);
            return array("status"=>"success", "message"=>"Items imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importItemsByCategory($cid) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $params["cid"] = $cid;
        $response = $this->callAPI("items_by_category", "GET", $params, array());
        if ($response["error"]==false) {
            $items = $response["content"]["elements"];
            $saved = $this->repository->saveItems($items);
            return array("status"=>"success", "message"=>"Items imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import", "count"=>0);
        }
    }
    
    public function importTags() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("tags", "GET", $params, array());
        if ($response["error"]==false) {
            $tags = $response["content"]["elements"];
            $saved = $this->repository->saveTags($tags);
            return array("status"=>"success", "message"=>"Labels imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    
    public function importTaxRates() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("tax_rates", "GET", $params, array());
        if ($response["error"]==false) {
            $rates = $response["content"]["elements"];
            $saved = $this->repository->saveTaxRates($rates);
            return array("status"=>"success", "message"=>"Tax rates imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importMerchant() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("merchant", "GET", $params, array());
        if ($response["error"]==false) {
            $merchant = $response["content"];
            $saved = $this->repository->saveMerchant($merchant);
            return array("status"=>"success", "message"=>"Merchant imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importMerchantProperties() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("merchant_properties", "GET", $params, array());
        if ($response["error"]==false) {
            $merchant = $response["content"];
            $saved = $this->repository->saveMerchantProperties($merchant);
            return array("status"=>"success", "message"=>"Merchant Properties saved", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importMerchantServiceCharge() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("merchant_service_charge", "GET", $params, array());
        if ($response["error"]==false) {
            $merchant = $response["content"];
            $saved = $this->repository->saveMerchantServiceCharge(buynowdepot_get_option("merchant_id"),$merchant);
            return array("status"=>"success", "message"=>"Merchant service charge saved", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function importOpeningHours() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("merchant_opening_hours", "GET", $params, array());
        if ($response["error"]==false) {
            $openingHours = $response["content"]["elements"];
            $saved = $this->repository->saveOpeningHours($openingHours,"Merchant");
            return array("status"=>"success", "message"=>"Opening hours imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    
    public function createDummyItem($item) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("items_create", "POST", $params, $item);
        return $response;
    }
    
    public function getSingleItem($id) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "iid"=>$id);
        $response = $this->callAPI("get_item", "GET", $params, array());
        return $response;
    }
    
    public function submitOrder($order) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("create_atomic_order", "POST", $params, $order);
        return $response;
    }
    
    public function submitPaymentForOrder($payment, $orderId) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $url = $this->getECommerceUrl()."/v1/orders/".$orderId."/pay";
        $response = $this->sendPostRequest($url, json_encode($payment), $this->accessToken);
        return $response;
        
    }
    
    public function submitCardPointePaymentForOrder($payment, $orderId) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $url = $this->getECommerceUrl()."/v1/orders/".$orderId."/pay";
        $response = $this->sendPostRequest($url, json_encode($payment), $this->accessToken);
        return $response;
        
    }
    
    public function getCustomer($firstName, $lastName, $email, $phone) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "firstName"=>$firstName, "lastName"=>$lastName, "emailAddress"=>$email, "phoneNumber"=>$phone);
        $response = $this->callAPI("customers", "GET", $params, array());
        return $response;
    }
    
    public function createCustomer($customer) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("customer_create", "POST", $params, $customer);
        return $response;
    }
    
    public function updateOrderCustomer($customer, $orderId) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "oid"=>$orderId);        
        $response = $this->callAPI("update_order_customer", "POST", $params, $customer);
        return $response;
    }
    
    
    public function syncOrders($syncTime) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "last_sync_time"=>$syncTime);
        $response = $this->callAPI("orders_sync", "GET", $params, array());
        return $response;
    }
    public function syncCategories($syncTime) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "last_sync_time"=>$syncTime);
        $response = $this->callAPI("categories_sync", "GET", $params, array());
        return $response;
    }
    public function syncItems($syncTime) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "last_sync_time"=>$syncTime);
        $response = $this->callAPI("items_sync", "GET", $params, array());
        return $response;
    }
    public function syncModifierGroups($syncTime) {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"), "last_sync_time"=>$syncTime);
        $response = $this->callAPI("modifier_groups_sync", "GET", $params, array());
        return $response;
    }
    
    public function importOrderTypes() {
        $params = array("mid"=>buynowdepot_get_option("merchant_id"));
        $response = $this->callAPI("merchant_order_types", "GET", $params, array());
        if ($response["error"]==false) {
            $ordertypes = $response["content"]["elements"];
            $saved = $this->repository->saveOrderTypes($ordertypes);
            return array("status"=>"success", "message"=>"Order types imported", "count"=>$saved);
        } else {
            return array("status"=>"error", "message"=>"Error during import");
        }
    }
    
    public function getBndApiUrl() {
        return $this->url_api[$this->apiEnv];
    }
    public function getCloverApiUrl() {
        return $this->url_clover_platform[$this->apiRegion][$this->apiEnv];
    }
    public function getCheckoutUrl() {
        return $this->url_clover_checkout[$this->apiRegion][$this->apiEnv];
    }
    public function getECommerceUrl() {
        return $this->url_clover_ecommerce[$this->apiRegion][$this->apiEnv];
    }
    public function getTokenizeUrl() {
        return $this->url_clover_tokenize[$this->apiRegion][$this->apiEnv];
    }
    public function getCardPointeUrl() {
        return $this->url_cardpointe_checkout[$this->apiRegion][$this->apiEnv];
    }
}