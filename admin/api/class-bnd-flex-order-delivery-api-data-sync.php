<?php
/**
 * Data-sync operations
 * 
 * � Copyright 2021  Website Experts Inc./ DBA Buy Now Depot�
 * Trademark Registered Number:  5,442,029
 * @author            BuyNowDepot
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package           Bnd_Flex_Order_Delivery/api
 */
class Bnd_Flex_Order_Delivery_API_Data_Sync extends Bnd_Flex_Order_Delivery_API_Base
{
    // Here initialize our namespace and resource name.
    public function __construct() {
        parent::__construct();
        $this->table_name="bnd_data_sync";
        $this->model_name="data-sync";
    }
}
?>