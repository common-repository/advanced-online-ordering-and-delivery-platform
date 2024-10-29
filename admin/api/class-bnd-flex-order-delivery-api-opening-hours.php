<?php
/**
 * Opening hours operations
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */

class Bnd_Flex_Order_Delivery_API_Opening_hours extends Bnd_Flex_Order_Delivery_API_Base
{
    // Here initialize our namespace and resource name.
    public function __construct() {
        parent::__construct();
        $this->table_name="bnd_opening_hours";
        $this->model_name="opening-hours";
        $this->field_definitions = array(
            "id"=>array("id"=>1, "read"=>1),
            "clid"=>array("read"=>1),
            "name"=>array("read"=>1,"edit"=>1,"search"=>1),
            "alternate_name"=>array("read"=>1,"edit"=>1,"search"=>1),
            "description"=>array("read"=>1,"edit"=>1,"search"=>1),
            "sort_order"=>array("read"=>1,"edit"=>1),
            "image_link"=>array("read"=>1,"edit"=>1),
            "display"=>array("read"=>1,"edit"=>1),
        );
    }
}
?>