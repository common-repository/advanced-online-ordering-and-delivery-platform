<?php
/**
 * Discount coupon operations
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */
class Bnd_Flex_Order_Delivery_API_Discount_Coupon extends Bnd_Flex_Order_Delivery_API_Base
{
    // Here initialize our namespace and resource name.
    public function __construct() {
        parent::__construct();
        $this->table_name="bnd_discount_coupon";
        $this->model_name="discount-coupon";
        $this->field_definitions = array(
            "id"=>array("id"=>1, "read"=>1)
        );
    }
    
    public function create(){
        $data=array();
        foreach ($_POST as $key => $value) {
            $data[htmlspecialchars($key)]=stripslashes(htmlspecialchars($value));
        }
        $data["status"]=1;
        $startDate=strtotime($data["start_date"]);
        $endDate=strtotime($data["end_date"]);
        $data["start_date"]=date('Y/m/d',$startDate);
        $data["end_date"]=date('Y/m/d',$endDate);
        $data["current_count"]=0;
        $result = $this->conn->insert("{$this->conn->prefix}{$this->table_name}",  $data);
        if($result){
            return json_encode(array("status"=>"success","message"=>"Record inserted"));
        }
        else {
            return json_encode(array("status"=>"error","message"=>"Error during insert", "data"=>-1));
        }
    }
}
?>