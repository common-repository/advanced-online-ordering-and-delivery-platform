<?php
/**
 * Order type operations
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */
class Bnd_Flex_Order_Delivery_API_Order_type extends Bnd_Flex_Order_Delivery_API_Base
{
    // Here initialize our namespace and resource name.
    public function __construct() {
        parent::__construct();
        $this->table_name="bnd_order_type";
        $this->model_name="order-type";
        $this->field_definitions = array(
            "id"=>array("id"=>1, "read"=>1),
            "clid"=>array("read"=>1),
            "label"=>array("read"=>1,"edit"=>1,"search"=>1),
            "taxable"=>array("read"=>1,"edit"=>1),
            "is_default"=>array("read"=>1,"edit"=>1),
            "filter_categories"=>array("read"=>1,"edit"=>1),
            "is_hidden"=>array("read"=>1,"edit"=>1),
            "fee"=>array("read"=>1,"edit"=>1),
            "min_order_amount"=>array("read"=>1,"edit"=>1),
            "max_order_amount"=>array("read"=>1,"edit"=>1),
            "max_radius"=>array("read"=>1,"edit"=>1),
            "avg_order_time"=>array("read"=>1,"edit"=>1),
            "hours_available"=>array("read"=>1),
            "categories"=>array("read"=>1)
        );
    }
}
?>