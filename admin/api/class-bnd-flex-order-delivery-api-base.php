<?php
/**
 * Base API for all admin CRUD operations
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */
class Bnd_Flex_Order_Delivery_API_Base
{

    protected $conn;
    protected $model;
    protected $client;
    protected $table_name;
    protected $model_name;
    protected $default_order;
    protected $field_definitions;

    // Here initialize our namespace and resource name.
    public function __construct() {
        global $wpdb;
        $this->conn = $wpdb;
        $this->client              = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
        $this->model              = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
        $this->default_order="id";
    }
    
    
    // used to export records to csv
    public function export_CSV(){
        
        //select all data
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        //this is how to get number of rows returned
        $num = $stmt->rowCount();
        
        $out = "ID,Name,Description,Created,Modified\n";
        
        if($num>0){
            //retrieve our table contents
            //fetch() is faster than fetchAll()
            //http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            /*
             while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
             //extract row
             //this will make $row['name'] to
             //just $name only
             extract($row);
             $out.="{$id},\"{$name}\",\"{$description}\",{$created},{$modified}\n";
             }*/
        }
        
        return $out;
    }
    
    // delete selected categories
    public function deleteSelected($ids){
        
        $in_ids = str_repeat('?,', count($ids) - 1) . '?';
        
        // query to delete multiple records
        $query = "DELETE FROM " . $this->table_name . " WHERE id IN ({$in_ids})";
        
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute($ids)){
            return true;
        }else{
            return false;
        }
    }
    
    public function readOne($id){

        $query = "SELECT *
				FROM " . $this->table_name . "
				WHERE id = ?
				LIMIT 0,1";
        
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
        
        // bind selected record id
        $stmt->bindParam(1, $this->id);
        
        // execute the query
        $stmt->execute();
        
        // get record details
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // assign values to object properties
        $this->name = $row['name'];
        $this->description = $row['description'];
    }
    
    public function update(){
        $data=array();
        $idval = '';
        foreach ($_POST as $key => $value) {
            if ($key=="id") {
                $idval = sanitize_text_field($value);
            }
            else {
                $data[htmlspecialchars($key)]=stripslashes(htmlspecialchars($value));
            }
        }
        $where = is_numeric($idval)?array("id"=>$idval):array("clid"=>$idval);
        $result = $this->conn->update("{$this->conn->prefix}{$this->table_name}",  $data,  $where );
        if($result){
            return wp_send_json(array("status"=>"success","message"=>"Record updated"));
        }
        else {
            return wp_send_json(array("status"=>"error","message"=>"error during update", "data"=>-1));
        }
    }
    
    public function updateField(){
        $params = buynowdepot_get_post_array();
        $clid = $params["pk"];
        $fieldName = $params["name"];
        $val = $params["value"];
        $where = is_numeric($clid)?array("id"=>$clid):array("clid"=>$clid);
        $result = $this->conn->update("{$this->conn->prefix}{$this->table_name}",
        array(
            $fieldName => $val
        ),
        $where
        );
        if($result){
            return wp_send_json(array("status"=>"success","message"=>"Record updated", "data"=>$val));
        }
        else {
            return wp_send_json(array("status"=>"error","message"=>"Error during update", "data"=>-1));
        }
    }
    
    public function delete(){
        $params = buynowdepot_get_post_array();
        $clid = $params["id"];
        $where = is_numeric($clid)?array("id"=>$clid):array("clid"=>$clid);
        $result = $this->conn->delete("{$this->conn->prefix}{$this->table_name}",$where);
        if($result){
            return wp_send_json(array("status"=>"success","message"=>"Record deleted", "data"=>$result));
        }
        else {
            return wp_send_json(array("status"=>"error","message"=>"Error during delete", "data"=>-1));
        }
    }
    
    public function create(){
        $data=array();
        foreach ($_POST as $key => $value) {
            $data[htmlspecialchars($key)]=stripslashes(htmlspecialchars($value));
        }
        $result = $this->conn->insert("{$this->conn->prefix}{$this->table_name}",  $data);
        if($result){
            return wp_send_json(array("status"=>"success","message"=>"Record inserted"));
        }
        else {
            return wp_send_json(array("status"=>"error","message"=>"Error during insert", "data"=>-1));
        }
    }
    
    // get search results with pagination
    public function searchPaging($page){
        include_once plugin_dir_path( __FILE__ ) .'bnd-flex-order-delivery-api-core.php';
        // search category based on search term
        $search_term = isset($_GET['keywords']) ? sanitize_text_field($_GET['keywords']) : "";
        // search query
        $searchfields = $this->get_search_fields();
        $query = "SELECT *  FROM {$this->conn->prefix}{$this->table_name} WHERE 1=0 ";
        foreach ($searchfields as $key) {
            $query = $query." || {$key} like '%{$search_term}%'";
        }
        $query= $query." ORDER BY {$this->default_order} ASC LIMIT {$from_record_num}, {$records_per_page}";
        $results =  $this->conn->get_results($query);
        if (count($results)>0) {
            $results = $this->formatList($results);
            $utilities = new Utilities();
            $total_rows=$this->count();
            $page_url=get_rest_url().'bnd-rest-api/'.$this->model_name.'/search_paging?';
            $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
            $model_arr=array();
            $model_arr["records"]=$results;
            $model_arr["paging"]=$paging;
            return $this->applyTemplate($this->model_name."/read-paging",$model_arr);
        }
        else {
            return $this->applyTemplate($this->model_name."/read-paging",array("message" => "No records found."));
        }
    }
    
    // count all categories
    public function count(){
        
        // query to count all data
        $query = "SELECT count(*) as total_rows FROM {$this->conn->prefix}{$this->table_name}";
        // prepare query statement
        $result = $this->conn->get_row($query);     
        return $result->total_rows;
    }
    
    // count all categories with search term
    public function countSearch($keywords){
        
        // search query
        $query = "SELECT COUNT(*) as total_rows FROM categories WHERE name LIKE ? OR description LIKE ?";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind search term
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        
        $stmt->execute();
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_rows = $row['total_rows'];
        
        return $total_rows;
    }
    
    // read all with paging
    public function readPaging($page){
        
        include_once plugin_dir_path( __FILE__ ).'/bnd-flex-order-delivery-api-core.php';
        
        //select all data
        $query = "SELECT * FROM {$this->conn->prefix}{$this->table_name} ORDER BY {$this->default_order} LIMIT {$from_record_num},{$records_per_page}";
        $results =  $this->conn->get_results($query);
        if (count($results)>0) {
            $results = $this->formatList($results);
            $utilities = new Utilities();
            $total_rows=$this->count();
            $page_url=get_rest_url().'bnd-rest-api/'.$this->model_name.'/read_paging?';
            $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
            $model_arr=array();
            $model_arr["records"]=$results;
            $model_arr["paging"]=$paging;
            return $this->applyTemplate($this->model_name."/read-paging",$model_arr);            
        }
        else {
            return $this->applyTemplate($this->model_name."/read-paging",array("message" => "No records found."));
        }
    }
    
    // used by select drop-down list
    public function read(){
        //select all data
        $query = "SELECT * FROM {$this->conn->prefix}{$this->table_name}";
        $results =  $this->conn->get_results($query);
        if (count($results)>0) {
            $results = $this->formatList($results);
            return $this->applyTemplate($this->model_name."/read",$results);
        }
        else {
            return $this->applyTemplate($this->model_name."/read",array("message" => "No records found."));
        }
    }
    
    // search without pagination
    public function searchAll_WithoutPagination($keywords){
        $searchfields = $this->get_search_fields();
        $query = "SELECT *  FROM {$this->conn->prefix}{$this->table_name} WHERE 1=0 ";
        foreach ($searchfields as $key) {
            $query = $query." || {$key} like '%{$keywords}%'";
        }
        $query= $query." ORDER BY id ASC";
        $results =  $this->conn->get_results($query);
        if (count($results)>0) {
            return $results;
        }
        else {
            return array("message" => "No records found.");
        }
    }
    
    // used to read category name by its ID
    function readFieldById($field, $id){
        
        $query = "SELECT $field FROM {$this->conn->prefix}{$this->table_name} WHERE id = {$id} limit 0,1";
        $results =  $this->conn->get_results($query);
        if (count($results)>0) {
            return wp_send_json($results);
        }
        else {
            return wp_send_json(
                array("message" => "No records found.")
                );
        }
    }
    
    function get_read_fields() {
        $readfields = array();
        foreach($this->field_definitions as $key=> $value) {
            if (isset($value["read"])) {
                array_push($readfields,$key);
            }
        }
        return $readfields;
    }

    function get_search_fields() {
        $searchfields = array();
        foreach($this->field_definitions as $key=> $value) {
            if (isset($value["search"])) {
                array_push($searchfields,$key);
            }
        }
        return $searchfields;
    }
    
    function get_update_fields() {
        $updatefields = array();
        foreach($this->field_definitions as $key=> $value) {
            if (isset($value["edit"])) {
                array_push($updatefields,$key);
            }
        }
        return $updatefields;
    }
    
    function get_id_field() {
        foreach($this->field_definitions as $key=> $value) {
            if (isset($value["id"])) {
               return $key;
            }
        }
        return null;
    }
    
    function formatList($list){
        return $list;
    }
    
    function formatRecord($record) {
        return $record;
    }
    
    function applyTemplate($filename, $response = null, $keywords="") {
        if (is_array($response) && !empty($response)) {
            extract($response);
        }
        ob_start();
        include_once BUYNOWDEPOT_PLUGIN_DIR.'/admin/templates/'.$filename.".php";
        return ob_get_clean();
    }

}