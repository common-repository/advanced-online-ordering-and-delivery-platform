<?php

/**
 * 
 * Class to make API request to Buy now depot server
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @author BuyNowDepot
 *
 */

class Bnd_Flex_Order_delivery_Bnd_Client
{
    private $url_api;
    private $apiEnv;
    private $apiRegion;
    private $apiKey;
    private $accessToken;
    private $apiUrls;
    private $merchantId;
    private $respository;
    
    function __construct() {
        $this->init();
        $this->url_api = array(
            "us"=> array(
                "production"=> "https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/api/v1",
                "sandbox"=> "https://buynowdepot.com/is3M5mBEKLKHqWhq/api/v1",
                //"sandbox": "http://localhost/aoodbackend/api/v1"
            ),
            "eu"=> array(
                "production"=> "https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/api/v1",
                "sandbox"=>"https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/api/v1"
            )
        );
        $this->repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
        $this->loadKeyAndToken();
    }
    
    function init() {
        $this->apiUrls = array( 
            'addDeliveryZone'=> '/merchant-delivery-zone.json',
            'listDeliveryZones'=> '/merchant-delivery-zone/list-delivery-zones/{mid}.json',
            'getDriverDetails'=>'/order-delivery/available-drivers/{mid}/{eid}.json',
            'getDeliveryDetail'=>'/order-delivery/delivery-detail/{mid}/{oid}.json',
            'getLocationData'=>'/delivery-tracker/get-location/{mid}/{oid}/{ctime}.json',
            'merchantSettings'=>'/merchant-settings.json',
            'getMerchantSettings'=>'/merchant-settings.json?mid={mid}',
            'cloverUpdates'=>'/webhook-data.json?merchant={mid}&lastSync={syncTime}');
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
        $endPoint = $this->getBndApiUrl()."authtoken?license_key=".$this->apiKey;
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
    
    public function callAPI($apiName, $method, $params, $data) {
        $apiUrl = $this->apiUrls[$apiName];
        foreach($params as $key => $value) {
            $apiUrl = str_replace('{'.$key.'}', $value, $apiUrl);
        }
        $endPoint = $this->getBndApiUrl().$apiUrl;
        if ($method ==="GET") {
            return $this->sendGetRequest($endPoint, $this->accessToken);
        }
        if ($method ==="POST") {
            return $this->sendPostRequest($endPoint, json_encode($data), $this->accessToken);
        }
        if ($method ==="PUT") {
            return $this->sendPutRequest($endPoint, json_encode($data), $this->accessToken);
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
    
    public function sendPutRequest($url, $jsonData, $token) {
        $headers = array('Content-Type' => 'application/json', 'Accept'=>'application/json');
        if ($token) {
            $headers["Authorization"]="Bearer ".$token;
        }
        $response = wp_remote_request( $url, array(
            'method' => 'PUT',
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
    
    public function saveDeliveryZone($zoneData) {
        $response = $this->callAPI("addDeliveryZone","POST",array(),$zoneData);
        return $response;
    }
    
    public function saveMerchantSettings($settingsData) {
        $response = $this->callAPI("merchantSettings","POST",array(),$settingsData);
        return $response["content"];
    }
    
    public function getMerchantSettings() {
        $BndSettings = (array)get_option("bnd_settings");
        $params = array("mid"=> $BndSettings["merchant_id"]);
        return $this->callAPI("getMerchantSettings","GET",$params,array());
    }
    
    public function saveDeliverySetup($deliverySetupData) {
        $response = $this->callAPI("merchantSettings","POST",array(),$deliverySetupData);
        if ($response["error"]==false) {
            return $response["content"];
        }
        else {
            return array("status"=>"error", content=>"Setup can't be saved");
        }
    }
    
    public function getDriverDetails($employeeId)  {
        $BndSettings = (array)get_option("bnd_settings");
        $params = array("mid"=> $BndSettings["merchant_id"],"eid"=>$employeeId);
        $response = $this->callAPI("getDriverDetails", "GET", $params, array());
        if ($response["error"] == false) {
            $driverDetails = $response["content"];
            return array(
                "status"=> "success",
                "content"=> $driverDetails,
            );
        } else {
            return array("status"=> "error", "message"=> "Error during delivery zone import");
        }
    }
    
    public function getDeliveryDetail($orderId)  {
        $BndSettings = (array)get_option("bnd_settings");
        $params = array("mid"=> $BndSettings["merchant_id"],"oid"=>$orderId);
        $response = $this->callAPI("getDeliveryDetail", "GET", $params, array());
        if ($response["error"] == false) {
            $deliveryDetail = response["content"];
            return array(
                "status"=> "success",
                "content"=> $deliveryDetail,
            );
        } else {
            return array("status"=> "error", "message"=> "Error during delivery zone import");
        }
    }
    
    public function getLocationData($orderId, $captureTime)  {
        $BndSettings = (array)get_option("bnd_settings");
        $params = array("mid"=> $BndSettings["merchant_id"],"oid"=>$orderId, "ctime"=>$captureTime);
        $response = $this->callAPI("getLocationData", "GET", $params, array());
        if ($response["error"] == false) {
            $locationDetail = $response["content"];
            return array(
                "status"=> "success",
                "content"=> $locationDetail,
            );
        } else {
            return array("status"=> "error", "message"=> "Error during delivery zone import");
        }
    }
    
    public function getCloverUpdates($lastSyncTime)  {
        $BndSettings = (array)get_option("bnd_settings");
        $params = array("mid"=> $BndSettings["merchant_id"],"syncTime"=>$lastSyncTime);
        $response = $this->callAPI("cloverUpdates", "GET", $params, array());
        if (response["error"] == false) {
            $cloverUpdates = $response["content"];
            return array(
                "status"=> "success",
                "content"=> $cloverUpdates,
            );
        } else {
            return array("status"=> "error", "message"=> "Error during getting clover updates");
        }
    }
    
    public function importDeliveryZones()  {
        $BndSettings = (array)get_option("bnd_settings");
        $params = array("mid"=> $BndSettings["merchant_id"]);
        $response = $this->callAPI("listDeliveryZones", "GET", $params, array());
        if ($response["error"] == false) {
            $deliveryZones = $response["content"];
            $saved = $this->repository->saveDeliveryZones($deliveryZones);
            if ($saved["status"]=="success") {
                return array(
                    "status"=> "success",
                    "message"=> "Delivery zones imported",
                );
            }
            else {
                return array("status"=> "error", "message"=> "Error during delivery zone save");
            }
        } else {
            return array("status"=> "error", "message"=> "Error during delivery zone import");
        }
    }
}