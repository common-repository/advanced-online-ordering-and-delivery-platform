<?php
/**
 * Generic operations
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */
class Bnd_Flex_Order_Delivery_API_Generic
{

    protected $conn;
    protected $model;
    protected $repository;
    protected $table_name;
    protected $model_name;
    protected $field_definitions;

    // Here initialize our namespace and resource name.
    public function __construct() {
        global $wpdb;
        $this->conn = $wpdb;
        $this->repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
        $this->model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
    }
    
    function categoriesForItem($params) {
        $item_clid = $params["id"];
        $results = $this->model->getCategoriesByItem($item_clid);
        $responseType = $params["response"];
        if ($results) {
            return $this->format_response(array("status"=>"success","message"=>"Records found", "records"=>$results),$responseType);
        }
        else {
            return $this->format_response(array("status"=>"error","message"=>"No items found"),$responseType);
        }
    }
    
    function itemsForCategories($params) {
        $cat_clid = $params["cat"];
        $responseType = $params["response"];
        $category = $this->model->getByCloverId("category", $cat_clid);
        Bnd_Flex_Order_Delivery_Session::instance()->set("category", $category);
        $results = $this->model->getItemsByCategory($cat_clid);
        $items = array();
        if ($results) {
            foreach($results as $record) {
                $record->image_link=$this->model->getDefaultItemImage($record->clid)->image_url;
                array_push($items, $record);
            }
            return $this->format_response(array("status"=>"success","message"=>"Records found", "records"=>$items), $responseType);
        }
        else {
            return $this->format_response(array("status"=>"error","message"=>"No items found"), $responseType);
        }
    }
    
    function modifiersForGroup($params) {
        $clid = $params["id"];
        $responseType = $params["response"];
        $group = $this->model->getByCloverId("modifier_group", $clid);
        Bnd_Flex_Order_Delivery_Session::instance()->set("modifier-group", $group);
        $results = $this->model->getModifiers($clid);
        if ($results) {
            return $this->format_response(array("status"=>"success","message"=>"Records found", "records"=>$results), $responseType);
        }
        else {
            return $this->format_response(array("status"=>"error","message"=>"No items found"), $responseType);
        }
    }
    
    function modifierGroupForItem($params) {
        $item_clid = $params["id"];
        $results = $this->model->getModifierGroupByItem($item_clid);
        $responseType = $params["response"];
        if ($results) {
            return $this->format_response(array("status"=>"success","message"=>"Records found", "records"=>$results),$responseType);
        }
        else {
            return $this->format_response(array("status"=>"error","message"=>"No items found"),$responseType);
        }
    }
    
    function setDefaultImage($params) {
        $item_clid = $params["item"];
        $image_id = $params["id"];
        $allUpdate=array();
        $allUpdate["is_default"]=0;
        $count = $this->conn->update("{$this->conn->prefix}bnd_item_image",  $allUpdate,  array("item_clid"=>$item_clid) );
        $count = $this->conn->update("{$this->conn->prefix}bnd_item_image",  array("is_default"=>1),  array("id"=>$image_id));
        if ($count) {
            return $this->format_response(array("status"=>"success","message"=>"Default image updated", "records"=>$count));
        }
        else {
            return $this->format_response(array("status"=>"error","message"=>"Default could not be updated"));
        }
        
    }
    
    function saveSortOrder($params) {
        $totalCount=0;
        $modelName = $params["model"];
        foreach($params as $key => $val) {
            if ($key == "model") continue;
            $count = $this->conn->update("{$this->conn->prefix}bnd_".$modelName,  array("sort_order"=> $val),  array("clid"=>$key) );
            $totalCount+=$count;
        }
        if ($totalCount) {
            return wp_send_json(array("status"=>"success","message"=>"Sort order updated", "records"=>$totalCount));
        }
        else {
            return wp_send_json(array("status"=>"error","message"=>"No changes made to the sort order"));
        }
        
    }
    
    function format_response($response, $responseType=null) {
        if ($responseType) {
            if (is_array($response) && !empty($response)) {
                extract($response);
            }
            ob_start();
            include_once BUYNOWDEPOT_PLUGIN_DIR."admin/templates/".$responseType.".php";
            return ob_get_clean();
        }
        return json_encode($response);
    }
}