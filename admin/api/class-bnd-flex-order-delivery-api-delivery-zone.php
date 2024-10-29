<?php
/**
 * Delivery-zone operations
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */

class Bnd_Flex_Order_Delivery_API_Delivery_Zone extends Bnd_Flex_Order_Delivery_API_Base
{
    // Here initialize our namespace and resource name.
    public function __construct() {
        parent::__construct();
        $this->table_name="bnd_delivery_zone";
        $this->model_name="delivery-zone";
        $this->field_definitions = array(
            "id"=>array("id"=>1, "read"=>1),
            "name"=>array("read"=>1,"edit"=>1,"search"=>1),
            "fee_type"=>array("read"=>1,"edit"=>1,"search"=>1),
            "min_amount"=>array("read"=>1,"edit"=>1,"search"=>1),
            "delivery_fee"=>array("read"=>1,"edit"=>1),
            "area_map"=>array("read"=>1,"edit"=>1),
            "zone_type"=>array("read"=>1,"edit"=>1),
        );
    }
}
?>