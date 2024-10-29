<?php
/**
 * This class performs all database related interactions
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
 class Bnd_Flex_Order_Delivery_Db {

    public $bnddb;


    function __construct() {
        global $wpdb;
        $this->bnddb = $wpdb;
    }
    
    /**
     * Get 
     * @param array $category_ids
     */
    function getCategories($category_ids =null){
        $in_cat='';
        if ($category_ids!=null && count($category_ids==0)) {
            $in_cat = "(".(implode(",", $category_ids)).")";
        }
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_category ".(!empty($in_cat)?"WHERE id in ".$in_cat:"")."ORDER BY sort_order");
    }
    
    function getItems() {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_item");
    }
    function getCurrentMerchant() {
        $result = $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_merchant");
        if ($result) {
            return $result[0];
        }
        else return null;
    }
    
    function getOpeningHours() {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_opening_hours");
    }
    
    function getCategoryByCloverId($clid) {
        $clid = esc_sql($clid);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_category c
                                    WHERE c.clid = '{$clid}'
                                    ");
    }
    function getModifierGroupByCloverId($clid) {
        $clid = esc_sql($clid);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_modifier_group mg
                                    WHERE mg.clid = '{$clid}'
                                    ");
    }
    function getCategory($id) {
        $clid = esc_sql($id);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_category c
                                    WHERE c.id = '{$id}'
                                    ");
    }
    
    function getMerchantAddress() {
        return $this->bnddb->get_row("SELECT * FROM {$this->bnddb->prefix}bnd_merchant m");
    }

    function getUserAddresses($user_id) {
        $user_id = esc_sql($user_id);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_user_address ua
                                    WHERE ua.user_id = '{$user_id}'
                                    ");
    }
    
    function getNextSequence($name) {
        $result =  $this->bnddb->get_row("SELECT current_val FROM {$this->bnddb->prefix}bnd_sequence where name='{$name}'");
        $currentVal = -1;
        if (isset($result)) {
            $currentVal = $result->current_val;
            $this->bnddb->update(
                "{$this->bnddb->prefix}bnd_sequence", 
                array('current_val' => $currentVal+1),
                array('name'=>$name)
                );
        }
        return $currentVal;
    }
    
    function getCountries() {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_country");
    }
    
    function getItem($id) {
        $clid = esc_sql($id);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item i
                                    WHERE i.id = '{$clid}'
                                    ");
    }
    
    function getItemTagByIds($tagid, $itemid) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_tag_item i
                                    WHERE i.tag_clid = '{$tagid}' and i.item_clid='{$itemid}'
                                    ");
    }
    
    function getItemModifierGroupByIds($mgid, $itemid) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_modifier_group_item i
                                    WHERE i.modifier_group_clid = '{$mgid}' and i.item_clid='{$itemid}'
                                    ");
    }
    
    function getItemTaxRateByIds($rateid, $itemid) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item_tax_rate i
                                    WHERE i.tax_rate_clid = '{$rateid}' and i.item_clid='{$itemid}'
                                    ");
    }
    
    function getItemCategoryByIds($catid, $itemid) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item_category i
                                    WHERE i.category_clid = '{$catid}' and i.item_clid='{$itemid}'
                                    ");
    }
    
    function getItemImageByName($imageName, $itemid) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item_image i
                                    WHERE i.image_url = '{$imageName}' and i.item_clid='{$itemid}'
                                    ");
    }
    
    function getItemByCloverId($clid) {
        $clid = esc_sql($clid);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item i
                                    WHERE i.clid = '{$clid}'
                                    ");
    }
    
    function getByCloverId($type, $clid, $orderby=null) {
        $clid = esc_sql($clid);
        $order = ($orderby!=null)?" ORDER BY ".$orderby:"";
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_".$type." t
                                    WHERE t.clid = '{$clid}' {$order}
                                    ");
    }
    
    function getModelById($model, $id) {
        $clid = esc_sql($id);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_".$model." m
                                    WHERE m.id = '{$id}'
                                    ");
    }
    
    function getAllModels($model, $where=array()) {
        $wstr ="";
        if (!empty($where)) {
            $wstr = " WHERE 1=1";
            foreach($where as $condition=>$value) {
                if (is_numeric($value)) {
                    $wstr.= " and ".$condition."=".$value;
                }
                else {
                    $wstr.= " and ".$condition."='".$value."'";
                }
            }
        }
        return $this->bnddb->get_results("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_".$model." m ".$wstr);
    }
    
    function getTaxRateByCloverId($clid) {
        $clid = esc_sql($clid);
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_tax_rate r
                                    WHERE r.clid = '{$clid}'
                                    ");
    }
    
    function getItemsByCategory($catid){
        $sql = "SELECT *
                FROM {$this->bnddb->prefix}bnd_item_category ic, {$this->bnddb->prefix}bnd_item i
                WHERE ic.category_clid='{$catid}' and ic.item_clid=i.clid order by i.sort_order";
        $result  = $this->bnddb->get_results($sql);
        return $result;
    }
    
    function getCategoriesByItem($itemid){
        $sql = "SELECT *
                FROM {$this->bnddb->prefix}bnd_item_category ic, {$this->bnddb->prefix}bnd_category c
                WHERE ic.item_clid='{$itemid}' and ic.category_clid=c.clid order by c.sort_order";
        $result  = $this->bnddb->get_results($sql);
        return $result;
    }
    
    function getModifierGroupByItem($itemid){
        $sql = "SELECT *
                FROM {$this->bnddb->prefix}bnd_modifier_group_item mgi, {$this->bnddb->prefix}bnd_modifier_group mg
                WHERE mgi.item_clid='{$itemid}' and mgi.modifier_group_clid=mg.clid order by mg.sort_order";
        $result  = $this->bnddb->get_results($sql);
        return $result;
    }
    
    function getMessageTemplateByName($name) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_message_template where name = '{$name}'");
    }
    
    function getItemByName($name) {
        return $this->bnddb->get_row("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item where name = '{$name}'");
    }
    
    function getItemTaxRate($clid)
    {
        $item = $this->getItemByCloverId($clid);
        if($item->default_tax_rate){
            $taxes = $this->bnddb->get_results("SELECT clid,tax_rate, name, tax_amount
                                    FROM {$this->bnddb->prefix}bnd_tax_rate t
                                    WHERE t.is_default = 1
                                    ");
            return $taxes;
        }
        else
        {
            $taxes = $this->bnddb->get_results("SELECT clid,tax_rate,name, tax_amount FROM {$this->bnddb->prefix}bnd_item_tax_rate itr,{$this->bnddb->prefix}bnd_tax_rate tr
                                          WHERE itr.tax_rate_clid=tr.clid
                                          AND itr.item_clid='{$clid}'
                                    ");
            return $taxes;
        }
    }

    function getModifiers($clid_group)
    {
        $clid_group = esc_sql($clid_group);

        return $this->bnddb->get_results("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_modifier m
                                    WHERE m.modifier_group_clid = '{$clid_group}' ORDER BY m.sort_order
                                    ");
    }
    
    
    function getDefaultItemModifiersGroup($item)
    {
        $item = esc_sql($item);

        return $this->bnddb->get_results("SELECT mg.*
                                    FROM `{$this->bnddb->prefix}bnd_modifier_group_item` img,  `{$this->bnddb->prefix}bnd_modifier_group` mg
                                    WHERE mg.clid=img.modifier_group_clid AND mg.show_by_default='1'
                                    AND img.item_clid = '{$item}'
                                    ORDER BY mg.sort_order
                                    ");
    }
    
    function getItemModifiersGroup($item){
        $item = esc_sql($item);
        return $this->bnddb->get_results("SELECT mg.*
                                    FROM `{$this->bnddb->prefix}bnd_modifier_group_item` img,  `{$this->bnddb->prefix}bnd_modifier_group` mg
                                    WHERE mg.clid=img.modifier_group_clid
                                    AND img.item_clid = '{$item}'
                                    ORDER BY mg.sort_order
                                    ");
    }

    function itemHasModifiers($item)
    {
        $item = esc_sql($item);

        return $this->bnddb->get_row("SELECT count(*) as total
                                    FROM `{$this->bnddb->prefix}bnd_modifier_group_item` img, `{$this->bnddb->prefix}bnd_modifier_group` mg, `{$this->bnddb->prefix}bnd_modifier` m
                                    WHERE img.modifier_group_clid = mg.clid AND img.item_clid = '{$item}' AND mg.clid=m.modifier_group_clid AND mg.show_by_default='1'
                                    ");
    }

    function getModifier($id) {
        $id = esc_sql($id);
        return $this->bnddb->get_row("SELECT *
                                        FROM `{$this->bnddb->prefix}bnd_modifier` m
                                        WHERE m.id = '{$id}'
                                        ");
    }
    function getModifierByCloverId($clid) {
        $clid = esc_sql($clid);
        
        return $this->bnddb->get_row("SELECT *
                                        FROM `{$this->bnddb->prefix}bnd_modifier` m
                                        WHERE m.clid = '{$clid}'
                                        ");
    }
    function getItemsWithVariablePrice() {
        return $this->bnddb->get_results("SELECT *
                                        FROM `{$this->bnddb->prefix}bnd_item` 
                                        WHERE price_type = 'VARIABLE'
                                        ");
    }
    
    function getCouponByCode($code)
    {
        return $this->bnddb->get_row("SELECT * FROM {$this->bnddb->prefix}bnd_discount_coupon where status=1 and code='{$code}'");
    }
    
    function getOrderTypes()
    {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_order_type where is_hidden=0 order by id");
    }
    
    function getDataSyncs()
    {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_data_sync order by id");
    }
    
    function getOrderLineItems($order_number) {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_order_line_item where order_number='{$order_number}'");
    }
    
    function getOrderCustomer($order_number) {
        return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_order_customer where order_number='{$order_number}'");
    }
   

    function updateOrderTypes($clid,$status) {
        $clid = esc_sql($clid);
        $st = ($status == "true")? 1:0;

        return $this->bnddb->update("{$this->bnddb->prefix}bnd_order_types",
                                array(
                                    'status' => $st
                                ),
                                array( 'ot_clid' => $clid )
        );
    }
  
    function updateOrderStatus($order_number,$data) {
        return $this->bnddb->update("{$this->bnddb->prefix}bnd_order",
        $data,
        array( 'order_number' => $order_number )
        );   
    }

    function deleteCategory($clid) {
        $clid = esc_sql($clid);
        return $this->bnddb->delete("{$this->bnddb->prefix}bnd_category",
                                array( 'clid' => $clid )
        );
    }
    
    function deleteModifierGroup($clid)
    {
        $clid = esc_sql($clid);
        if( $clid== "" ) return;
        $this->bnddb->query('START TRANSACTION');
        $this->bnddb->delete("{$this->bnddb->prefix}bnd_modifier",array('modifier_group_clid'=>$clid));
        $this->bnddb->delete("{$this->bnddb->prefix}bnd_modifier_group_item",array('modifier_group_clid'=>$clid));
        $res = $this->bnddb->delete("{$this->bnddb->prefix}bnd_modifier_group",array('clid'=>$clid));
        if($res)
        {
            $this->bnddb->query('COMMIT'); // if the item Inserted in the DB
        }
        else {
            $this->bnddb->query('ROLLBACK'); // // something went wrong, Rollback
        }
        return $res;

    }

    function deleteModifier($clid)
    {
        $clid = esc_sql($clid);
        if( $clid== "" ) return;
        return $this->bnddb->delete("{$this->bnddb->prefix}bnd_modifier",array('clid'=>$clid));

    }

    
    function updateOrder($clid,$ref){
        $clid      = esc_sql($clid);
        $ref       = esc_sql($ref);
        return $this->bnddb->update(
                        "{$this->bnddb->prefix}bnd_order",
                        array(
                            'paid' => 1,
                            'refpayment' => $ref
                        ),
                        array( 'clid' => $clid )
                    );
    }

    function CountCategories() {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_category");
    }

    function CountLabels()
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_tag");
    }

    function CountTaxes()
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_tax_rate");
    }

    function CountItems()
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_item");
    }
    function CountModifiers()
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_modifier_group WHERE clid in (SELECT modifier_group_clid from {$this->bnddb->prefix}bnd_modifier)");
    }
    function CountModifierGroups()
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_modifier_group");
    }
    function CountModifier($group)
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_modifier where modifier_group_clid = '{$group}'");
    }
    function CountOrderTypes()
    {
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_order_types");
    }
    
    function countOrders($order_status)
    {
        $order_status =  esc_sql($order_status);
        return $this->bnddb->get_results("SELECT count(*) as Count FROM {$this->bnddb->prefix}bnd_order where order_status={$order_status}");
    }
   
    function getRecentOrders($limit)
    {
        $limit = esc_sql($limit);
        
        if($limit==0 || $limit<0)
            $limit = 5;
            return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_order ORDER by created_time desc limit ".$limit);
    }
    
    function getNotifications($limit)
    {
        $limit = esc_sql($limit);    
        if($limit==0 || $limit<0)
            $limit = 5;
            return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_notification where status=1 ORDER by notification_time desc limit ".$limit);
    }
    
    function getActivityLog($limit)
    {
        $limit = esc_sql($limit);
        if($limit==0 || $limit<0)
            $limit = 10;
            return $this->bnddb->get_results("SELECT * FROM {$this->bnddb->prefix}bnd_notification ORDER by notification_time desc limit ".$limit);
    }
    /*
     * Manage Item's image
     */
    function getItemWithImage($clid)
    {
        $clid = esc_sql($clid);
        return $this->bnddb->get_results("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item items
                                    LEFT JOIN {$this->bnddb->prefix}bnd_item_image images
                                    ON items.clid=images.item_clid
                                    WHERE items.clid = '{$clid}'
                                    ");
    }

    function getItemImages($clid)
    {
        $clid = esc_sql($clid);
        return $this->bnddb->get_results("SELECT *
                                    FROM {$this->bnddb->prefix}bnd_item_image images
                                    WHERE images.item_clid = '{$clid}'
                                    ");
    }
    function getItemTags($clid)
    {
        $clid = esc_sql($clid);
        return $this->bnddb->get_results("SELECT tg.clid, tg.name, tg.image_link
                                    FROM {$this->bnddb->prefix}bnd_tag tg
                                    INNER JOIN {$this->bnddb->prefix}bnd_tag_item ti
                                    ON ti.tag_clid=tg.clid
                                    WHERE ti.item_clid = '{$clid}'");
    }
    
    function getDefaultItemImage($clid)
    {
        $clid = esc_sql($clid);
        return $this->bnddb->get_row("SELECT image_url
                                    FROM {$this->bnddb->prefix}bnd_item_image images
                                    WHERE images.item_clid = '{$clid}' order by images.is_default desc limit 1
                                    ");
    }
   
    
    function getOrdersByWeek() {
        $datetimestr = strftime('%Y-%m-%d 00:00:00', strtotime('-6 day'));
        $query = "select distinct DATE_FORMAT(created_time,'%d/%m') odate, SUBSTRING(DATE_FORMAT(created_time,'%W'),1,3) AS weekday, COUNT(*) as count from {$this->bnddb->prefix}bnd_order where created_time>'".$datetimestr."' group by odate order by odate";
        return $this->bnddb->get_results($query);
    }
    
    function getRevenueByWeek() {
        $datetimestr = strftime('%Y-%m-%d 00:00:00', strtotime('-6 day'));
        $query = "select distinct DATE_FORMAT(created_time,'%d/%m') odate, sum(total) as total from {$this->bnddb->prefix}bnd_order where created_time>'".$datetimestr."' group by odate order by odate";
        return $this->bnddb->get_results($query);
    }
    
    function getBestSellers() {
        $query = "SELECT item_clid, sum(quantity) AS total FROM {$this->bnddb->prefix}bnd_order_line_item GROUP BY item_clid ORDER BY SUM(quantity) DESC LIMIT 5";
        return $this->bnddb->get_results($query);
    }
    function getBestSellerCategories() {
        $query = "SELECT distinct ct.clid, ct.name, sum(quantity) AS total FROM {$this->bnddb->prefix}bnd_order_line_item ol, {$this->bnddb->prefix}bnd_item_category ic, {$this->bnddb->prefix}bnd_category ct where ol.item_clid=ic.item_clid and ic.category_clid=ct.clid  GROUP BY ct.clid ORDER BY SUM(quantity) DESC LIMIT 10";
        return $this->bnddb->get_results($query);
    }
    
    function getItemDetails($item) {
        $query = "SELECT distinct it.name as item_name,ct.name as category_name FROM {$this->bnddb->prefix}bnd_item it, {$this->bnddb->prefix}bnd_category ct, {$this->bnddb->prefix}bnd_item_category ic where it.clid='{$item}' and it.clid=ic.item_clid and ic.category_clid=ct.clid  ";
        return $this->bnddb->get_results($query);
    }
    
    function getCustomer($email)
    {
        $email = esc_sql($email);
        return $this->bnddb->get_row("SELECT * FROM {$this->bnddb->prefix}bnd_customer_profile where  email='{$email}'");
    }

    function addCustomer($customer) {
        global $wpdb;
        $wpdb->hide_errors();
        $result = $wpdb->insert("{$wpdb->prefix}bnd_customer_profile", array(
            'first_name' => isset($customer["first_name"])?$customer["first_name"]:"",
            'last_name' => isset($customer["last_name"])?$customer["last_name"]:"",
            'mobile_number' => isset($customer["mobile_number"])?$customer["mobile_number"]:"",
            'email' => $customer["email"],
            'status' => $customer["status"]
        ));
        return $result;
    }
    
    function updateCustomer($customer) {
        global $wpdb;
        $wpdb->hide_errors();
        $count =0;
        //check if customer exists, if yes update otherwise insert
        $cusotmer = $this->getAllModels("customer_profile", array("email"=>$customer["email"]));
        if (isset($cusotmer)) {
            $result = $wpdb->update("{$wpdb->prefix}bnd_customer_profile", array(
                'first_name' => isset($customer["first_name"])?$customer["first_name"]:"",
                'last_name' => isset($customer["last_name"])?$customer["last_name"]:"",
                'mobile_number' => isset($customer["mobile_number"])?$customer["mobile_number"]:"",
            ), array("email"=>$customer["email"]));
        }
        if ($result == 1) {
            $count++;
        }
        return $count;
    }
}