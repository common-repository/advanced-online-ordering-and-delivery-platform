<?php
/**
 * Fired during plugin activation.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
class Bnd_Flex_Order_Delivery_Activator
{

    private $bnd_flex_order_delivery;

    public function __construct($bnd_flex_order_delivery)
    {
        $this->bnd_flex_order_delivery = $bnd_flex_order_delivery;
    }

    /**
     * Short Description.
     * (use period)
     *
     * Long Description.
     *
     * @since 1.0.0
     */
    public function activate($network_wide = false)
    {
        global $wpdb;
        if (is_multisite() && $network_wide) {
            foreach ($wpdb->get_col("SELECT blog_id FROM $wpdb->blogs LIMIT 100") as $blog_id) {
                switch_to_blog($blog_id);
                self::bnd_flex_order_delivery_install();
                restore_current_blog();
            }
        } else {
            self::bnd_flex_order_delivery_install();
        }
    }

    private function bnd_flex_order_delivery_install()
    {
        global $wpdb;
        // $wpdb->hide_errors();
        $wpdb->show_errors();
        self::prepare_database();
        self::populate_countries();
        self::prepare_default_pages();
        self::update_default_options();
        self::update_user_role();
        self::schedule_jobs();
    }

    private function prepare_database()
    {
        global $wpdb;
        /*
         * Merchant
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_sequence'") != "{$wpdb->prefix}bnd_sequence") {
            $sql_sequence = "CREATE TABLE {$wpdb->prefix}bnd_sequence (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		name varchar(255) NOT NULL,
                                current_val int(10) NULL,
                                PRIMARY KEY  (id)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
    
            $wpdb->query($sql_sequence);
            $wpdb->insert("{$wpdb->prefix}bnd_sequence", array(
                'name' => "ORDER",
                'current_val' => 1000
            ));
        }
        /*
         * Country
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_country'") != "{$wpdb->prefix}bnd_country") {
            $sql_country = "CREATE TABLE {$wpdb->prefix}bnd_country (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		name varchar(255) NOT NULL,
                                code varchar(100) NULL,
                                PRIMARY KEY  (id)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_country);
        }

        /*
         * Data sync
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_data_sync'") != "{$wpdb->prefix}bnd_data_sync") {
            $sql_data_sync = "CREATE TABLE {$wpdb->prefix}bnd_data_sync (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		model_name varchar(255) NOT NULL,
                                display_name varchar(255) NOT NULL,
                                sync_enabled int(4) NOT NULL,
                                last_sync_time datetime NULL,
                                PRIMARY KEY  (id)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_data_sync);
        }

        /*
         * Message Template
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_message_template'") != "{$wpdb->prefix}bnd_message_template") {
            $sql_message = "CREATE TABLE {$wpdb->prefix}bnd_message_template (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		name varchar(255) NOT NULL,
                                display_name varchar(255) NOT NULL,
                                template_text text NULL,
                                param_list varchar(500) NULL,
                                PRIMARY KEY  (id)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_message);
        }

        /*
         * Merchant
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_merchant'") != "{$wpdb->prefix}bnd_merchant") {
            $sql_merchant = "CREATE TABLE {$wpdb->prefix}bnd_merchant (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                contact_email varchar(255) NULL,
                        		address1 varchar(255) NULL,
                        		address2 varchar(255) NULL,
                        		address3 varchar(255) NULL,
                        		city varchar(50) NULL,
                                country varchar(100) NULL,
                                phone_number varchar(100) NULL,
                                state varchar(100) NULL,
                                zip varchar(100) NULL,
                                website varchar(255) NULL,
                                lat double NULL,
                                lng double NULL,
                                currency varchar(50) NULL,
                                tips_enabled int(4) NULL default 0,
                                max_tip_percent double NULL default 0,
                                tip_rate_default double NULL default 0,
                                group_line_items int(4) NULL default 1,
                                vat_enabled int(4) NULL default 0,
                                vat_name varchar(50) NULL,
                                service_charge_id varchar(100) NULL,
                                service_charge_enabled int(4) NULL default 0,
                                service_charge_name varchar(50) NULL,
                                service_charge_percent double NULL,
                                service_charge_decimal int(10) NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_merchant);
        }

        /*
         * Merchant Opening Hours
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_opening_hours'") != "{$wpdb->prefix}bnd_opening_hours") {
            $sql_opening_hours = "CREATE TABLE {$wpdb->prefix}bnd_opening_hours (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                                record_type varchar(50) NULL,
                        		clid varchar(100) NOT NULL,
                        		monday varchar(255) NOT NULL,
                                tuesday varchar(255) NULL,
                                wednesday varchar(255) NOT NULL,
                        		thursday varchar(255) NOT NULL,
                        		friday varchar(255) NULL,
                        		saturday varchar(255) NULL,
                        		sunday varchar(255) NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_opening_hours);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_user_address'") != "{$wpdb->prefix}bnd_user_address") {
            $sql_user_address = "CREATE TABLE {$wpdb->prefix}bnd_user_address (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                                user_id varchar(100) NOT NULL,
                        		first_name varchar(255) NOT NULL,
                                last_name varchar(255) NOT NULL,
                        		address1 varchar(255) NOT NULL,
                        		address2 varchar(255) NULL,
                        		address3 varchar(255) NULL,
                        		city varchar(50) NULL,
                                country varchar(100) NULL,
                                phone_number varchar(100) NULL,
                                email varchar(100) NULL,
                                state varchar(100) NULL,
                                zip varchar(100) NULL,
                                address_type varchar(100) NULL,
                                is_default int(4) NULL,
                        		PRIMARY KEY  (id)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_user_address);
        }

        /*
         * Category
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_category'") != "{$wpdb->prefix}bnd_category") {
            $sql_category = "CREATE TABLE {$wpdb->prefix}bnd_category (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                description varchar(500) NULL,
                                alternate_name varchar(255) NOT NULL,
                        		sort_order int(10) NOT NULL,
                        		display int NULL,
                        		items text NULL,
                        		image_link varchar(500) NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_name (name),
                                UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_category);
        }

        /*
         * item_group
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_item_group'") != "{$wpdb->prefix}bnd_item_group") {
            $sql_item_group = "CREATE TABLE {$wpdb->prefix}bnd_item_group (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                        		PRIMARY KEY  (id),
                                UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_item_group);
        }

        /*
         * Attribute
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_attribute'") != "{$wpdb->prefix}bnd_attribute") {
            $sql_attribute = "CREATE TABLE {$wpdb->prefix}bnd_attribute (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                item_group_clid varchar(100)  NOT NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_name (name),
                                UNIQUE KEY unique_clid (clid),
                                INDEX {$wpdb->prefix}fkbnd_attribute_item_group_idx (item_group_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_attribute_item_group 
                                FOREIGN KEY (item_group_clid)
                                REFERENCES {$wpdb->prefix}bnd_item_group (clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                        		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_attribute);
        }

        /*
         * Option
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_option'") != "{$wpdb->prefix}bnd_option") {
            $sql_option = "CREATE TABLE {$wpdb->prefix}bnd_option (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                attribute_clid varchar(100)  NOT NULL,
                        		PRIMARY KEY  (id),
                                UNIQUE KEY unique_clid (clid),
                                INDEX {$wpdb->prefix}fkbnd_option_attribute_idx (attribute_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_option_attribute
                                FOREIGN KEY (attribute_clid)
                                REFERENCES {$wpdb->prefix}bnd_attribute (clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_option);
        }

        /*
         * modifier_group
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_modifier_group'") != "{$wpdb->prefix}bnd_modifier_group") {
            $sql_modifier_group = "CREATE TABLE {$wpdb->prefix}bnd_modifier_group (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                alternate_name varchar(255) NULL,
                                min_required int(10) NULL,
                                max_allowed int(10) NULL,
                                show_by_default int(10) NULL,
                                sort_order int(10) NULL,
                        		PRIMARY KEY  (id),
                                UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_modifier_group);
        }

        /*
         * modifier
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_modifier'") != "{$wpdb->prefix}bnd_modifier") {
            $sql_modifier = "CREATE TABLE {$wpdb->prefix}bnd_modifier (
                        		id BIGINT(20) NOT NULL AUTO_INCREMENT,
                        		clid VARCHAR(100) NOT NULL,
                        		name VARCHAR(255) NOT NULL,
                                alternate_name VARCHAR(255) NULL,
                                price double NOT NULL DEFAULT 0,
                                sort_order INT(4) NULL DEFAULT 0,      
                                image_link VARCHAR(255) NULL,                              
                                modifier_group_clid VARCHAR(100)  NOT NULL,
                        		PRIMARY KEY  (id),
                                UNIQUE KEY unique_clid (clid),
                                INDEX {$wpdb->prefix}fkbnd_modifier_modifier_group_idx (modifier_group_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_modifier_modifier_group
                                FOREIGN KEY (modifier_group_clid)
                                REFERENCES {$wpdb->prefix}bnd_modifier_group (clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_modifier);
        }
        /*
         * tag
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_tag'") != "{$wpdb->prefix}bnd_tag") {
            $sql_tag = "CREATE TABLE {$wpdb->prefix}bnd_tag (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                image_link varchar(500) NOT NULL,
                        		PRIMARY KEY  (id),
                                UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_tag);
        }

        /*
         * tax_rate
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_tax_rate'") != "{$wpdb->prefix}bnd_tax_rate") {
            $sql_tag = "CREATE TABLE {$wpdb->prefix}bnd_tax_rate (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                                tax_type varchar(255) NULL,
                                tax_rate double NULL,
                                tax_amount double NULL,
                                is_default int(10) NULL,
                        		PRIMARY KEY  (id),
                                UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_tag);
        }

        /*
         * item
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_item'") != "{$wpdb->prefix}bnd_item") {
            $sql_item = "CREATE TABLE {$wpdb->prefix}bnd_item  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		name varchar(255) NOT NULL,
                        		alternate_name varchar(255) NULL,
                        		description varchar(255) NULL,
                        		price double NULL,
                                price_type varchar(50) NULL,
                                price_unit varchar(50) NULL,
                                default_tax_rate int(1) default 1,
                                cost double NULL,
                                product_code varchar(100) NULL,
                                sku varchar(100) NULL,
                                item_group_clid varchar(100) NULL,
                                quantity int(10) NULL,
                                label varchar(100) NULL,
                                is_hidden int(10) NULL,
                                is_revenue int(10) NULL,
                                sort_order int(4) NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_clid (clid)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_item);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_item_image'") != "{$wpdb->prefix}bnd_item_image") {
            $sql_item_image = "CREATE TABLE {$wpdb->prefix}bnd_item_image  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                                image_url varchar(500) NULL,
                        		item_clid varchar(100) NULL,
                        		is_default int(4) NULL,
                        		is_enabled int(4) NULL,
                        		PRIMARY KEY  (id),
                                INDEX {$wpdb->prefix}fkbnd_item_item_image_idx (item_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_item_item_image
                                FOREIGN KEY (item_clid)
                                REFERENCES {$wpdb->prefix}bnd_item(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_item_image);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_modifier_group_item'") != "{$wpdb->prefix}bnd_modifier_group_item") {
            $sql_modifier_group_item = "CREATE TABLE {$wpdb->prefix}bnd_modifier_group_item  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		modifier_group_clid varchar(100) NOT NULL,
                                item_clid varchar(100) NOT NULL,
                        		PRIMARY KEY  (id),
                                INDEX {$wpdb->prefix}fkbnd_modifier_group_item_item_idx (item_clid ASC),
                                INDEX {$wpdb->prefix}fkbnd_modifier_group_item_modgroup_idx (modifier_group_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_modifier_group_item_item
                                FOREIGN KEY (item_clid)
                                REFERENCES {$wpdb->prefix}bnd_item(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                                CONSTRAINT {$wpdb->prefix}fkbnd_modifier_group_item_modgroup
                                FOREIGN KEY (modifier_group_clid)
                                REFERENCES {$wpdb->prefix}bnd_modifier_group(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_modifier_group_item);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_option_item'") != "{$wpdb->prefix}bnd_option_item") {
            $sql_option_item = "CREATE TABLE {$wpdb->prefix}bnd_option_item  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		option_clid varchar(100) NOT NULL,
                                item_clid varchar(100) NOT NULL,
                        		PRIMARY KEY  (id),
                                INDEX {$wpdb->prefix}fkbnd_option_item_option_idx (option_clid ASC),
                                INDEX {$wpdb->prefix}fkbnd_option_item_item_item_idx (item_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_option_item_item
                                FOREIGN KEY (option_clid)
                                REFERENCES {$wpdb->prefix}bnd_option(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                                CONSTRAINT {$wpdb->prefix}fkbnd_option_item_item_item
                                FOREIGN KEY (item_clid)
                                REFERENCES {$wpdb->prefix}bnd_item(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_option_item);
        }
        
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_tag_item'") != "{$wpdb->prefix}bnd_tag_item") {

            $sql_tag_item = "CREATE TABLE {$wpdb->prefix}bnd_tag_item  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		tag_clid varchar(100) NOT NULL,
                                item_clid varchar(100) NOT NULL,
                        		PRIMARY KEY  (id)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_tag_item);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_item_tax_rate'") != "{$wpdb->prefix}bnd_item_tax_rate") {
            $sql_item_tax_rate = "CREATE TABLE {$wpdb->prefix}bnd_item_tax_rate  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		tax_rate_clid varchar(100) NOT NULL,
                                item_clid varchar(100) NOT NULL,
                        		PRIMARY KEY  (id)                          
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_item_tax_rate);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_item_category'") != "{$wpdb->prefix}bnd_item_category") {
            $sql_item_categories = "CREATE TABLE {$wpdb->prefix}bnd_item_category  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		category_clid varchar(100) NOT NULL,
                                item_clid varchar(100) NOT NULL,
                        		PRIMARY KEY  (id),
                                INDEX {$wpdb->prefix}fkbnd_item_category_category_idx (category_clid ASC),
                                INDEX {$wpdb->prefix}fkbnd_item_category_item_idx (item_clid ASC),
                                CONSTRAINT {$wpdb->prefix}fkbnd_item_category_category
                                FOREIGN KEY (category_clid)
                                REFERENCES {$wpdb->prefix}bnd_category(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION,
                                CONSTRAINT {$wpdb->prefix}fkbnd_item_category_item
                                FOREIGN KEY (item_clid)
                                REFERENCES {$wpdb->prefix}bnd_item(clid)
                                ON DELETE NO ACTION
                                ON UPDATE NO ACTION
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_item_categories);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_order_type'") != "{$wpdb->prefix}bnd_order_type") {
            $sql_order_type = "CREATE TABLE {$wpdb->prefix}bnd_order_type (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                        		label varchar(100) NOT NULL,
                                taxable int(4) NULL,
                                is_default int(4) NOT NULL,
                        		filter_categories int(4) NOT NULL,
                        		is_hidden int(4) NULL,
                        		fee double NULL,
                        		min_order_amount double NULL,
                                max_order_amount double NULL,
                                max_radius double(10,2) NULL,
                                avg_order_time int(10) NULL,
                                hours_available varchar(20),
                                hours text NULL,
                                categories text NULL,
                                is_custom int(4) NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_clid (clid)
                    		) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_order_type);
        }
        /*
         * Order
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_order'") != "{$wpdb->prefix}bnd_order") {
            $sql_order = "CREATE TABLE {$wpdb->prefix}bnd_order  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NULL,
                                order_number varchar(100) NOT NULL,
                        		currency varchar(20) NOT NULL,
                                sub_total double NOT NULL,
                        		total double NOT NULL,
                                balance double NULL,
                                ext_reference varchar(100) NULL,
                                payment_state varchar(20) NULL,
                                title varchar(100) NULL,
                                note varchar(255) NULL,
                                created_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                total_service_charge double NOT NULL DEFAULT 0,
                                total_tax double  NULL DEFAULT 0,
                                delivery_charge double  NULL DEFAULT 0,
                                total_discount double  NULL DEFAULT 0,
                                total_tip double  NULL DEFAULT 0,
                                order_status int(4) NOT NULL,
                                order_type varchar(50) NULL,
                                payment_type varchar(50) NULL,
                                user_login varchar(100) NULL,
                        		PRIMARY KEY  (id)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_order);
        }

        /*
         * Guest Customer
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_order_customer'") != "{$wpdb->prefix}bnd_order_customer") {
            $sql_guest_customer = "CREATE TABLE {$wpdb->prefix}bnd_order_customer  (
                        		id BIGINT(20) NOT NULL AUTO_INCREMENT,
                                clid VARCHAR(100) NOT NULL,
                				first_name VARCHAR(100) NOT NULL,
                				last_name VARCHAR(100) NOT NULL,
                				address_1 VARCHAR(255) NOT NULL,
                                address_2 VARCHAR(255) NULL,
                                address_3 VARCHAR(255) NULL,
                                city VARCHAR(50) NOT NULL,
                                country VARCHAR(100) NULL,
                                phone_number VARCHAR(100) NULL,
                                email VARCHAR(100) NOT NULL,
                                state VARCHAR(100) NULL,
                                zip VARCHAR(100) NULL,
                                customer_type VARCHAR(100) NULL,
                                order_number varchar(50) NOT NULL,
                                user_login varchar(100) NULL,
                        		PRIMARY KEY  (id)                 
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_guest_customer);
        }

        /*
         * Order payment
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_order_payment'") != "{$wpdb->prefix}bnd_order_payment") {
            $sql_order_payment = "CREATE TABLE {$wpdb->prefix}bnd_order_payment  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		clid varchar(100) NOT NULL,
                                currency varchar(20) NOT NULL,
                        		amount double NOT NULL,
                        		tip_amount double NULL,
                                tax_amount double NULL,
                                cashback_amount varchar(100) NULL,
                                ext_payment_id varchar(100) NULL,
                                result varchar(20) NULL,
                                note varchar(255) NULL,
                                created_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                order_clid varchar(100) NULL,
                                order_number varchar(100) NOT NULL,
                        		PRIMARY KEY  (id),
                        		UNIQUE KEY unique_clid (clid)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_order_payment);
        }

        /*
         * Order Line Item
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_order_line_item'") != "{$wpdb->prefix}bnd_order_line_item") {
            $sql_order_line_item = "CREATE TABLE {$wpdb->prefix}bnd_order_line_item  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                                item_clid varchar(100) NOT NULL,
                                order_number varchar(100) NOT NULL,
                        		price double NOT NULL,
                                price_with_modification double NULL,
                                modification_ids varchar(500) NULL,
                        		unit_quantity int(10)  NULL,
                                discount_amount double NULL,
                                instructions varchar(255) NULL,
                                quantity int(10),
                        		PRIMARY KEY  (id)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_order_line_item);
        }

        /*
         * Order Line Item
         */
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_delivery_zone'") != "{$wpdb->prefix}bnd_delivery_zone") {
            $sql_delivery_zone = "CREATE TABLE {$wpdb->prefix}bnd_delivery_zone  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		name varchar(100) NOT NULL,
                                min_amount double NOT NULL default 0,
                                fee_type varchar(50) NOT NULL,
                                delivery_fee double NOT NULL default 0,
                                outside_fee double NOT NULL default 0,
                        		area_map text NOT NULL,
                                zone_type varchar(50) NOT NULL,
                        		is_default int(4) NULL,
                                PRIMARY KEY  (id),
                                UNIQUE KEY unique_name (name)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_delivery_zone);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_discount_coupon'") != "{$wpdb->prefix}bnd_discount_coupon") {
            $sql_discount_coupon = "CREATE TABLE {$wpdb->prefix}bnd_discount_coupon  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		name varchar(100) NOT NULL,
                                code varchar(100) NOT NULL,
                                discount_type varchar(20) NOT NULL ,
                        		value double NOT NULL default 0,
                                min_order_amount double NOT NULL default 0,
                                start_date datetime NULL, 
                                end_date datetime NULL,
                                num_usage int(10) NOT NULL default 0,
                                current_count int(10) NOT NULL default 0,
                        		status int(4) NULL,
                                created_at datetime null,
                                PRIMARY KEY  (id),
                                UNIQUE KEY unique_code (code)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_discount_coupon);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_notification'") != "{$wpdb->prefix}bnd_notification") {
            $sql_notification = "CREATE TABLE {$wpdb->prefix}bnd_notification  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		notification_time datetime NOT NULL,
                                message varchar(500) NOT NULL,
                                status int(4) NOT NULL,
                                PRIMARY KEY  (id)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_notification);
        }
        if($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}bnd_customer_profile'") != "{$wpdb->prefix}bnd_customer_profile") {
            $sql_customer_profile = "CREATE TABLE {$wpdb->prefix}bnd_customer_profile  (
                        		id bigint(20) NOT NULL AUTO_INCREMENT,
                        		first_name varchar(100) NULL,
                                last_name varchar(100) NULL,
                                mobile_number varchar(100) NULL,
                                email varchar(100),
                                status int(4) NOT NULL,
                                PRIMARY KEY  (id)
                        	) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $wpdb->query($sql_customer_profile);
        }
    }

    private function prepare_default_pages()
    {
        $page_list = Bnd_Flex_Order_Delivery_Container::instance()->get_page_list();
        $current_options = (array) get_option('bnd_settings');
        foreach ($page_list as $key => $value) {
            $page_post = array_key_exists($key, $current_options) ? get_post($current_options[$key]) : false;
            if (empty($page_post)) {
                $new_post = wp_insert_post(array(
                    'post_title' => __($value[0], 'bnd-flex-order-delivery'),
                    'post_content' => '[' . $value[1] . ']',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => 'page',
                    'comment_status' => 'closed',
                    'post_name' => $key
                ));
                $current_options[$key] = $new_post;
            }
        }
        update_option('bnd_settings', $current_options);
    }

    private function populate_countries()
    {
        global $wpdb;
        $country_list = array(
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AF', 'Afghanistan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AL', 'Albania')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DZ', 'Algeria')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DS', 'American Samoa')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AD', 'Andorra')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AO', 'Angola')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AI', 'Anguilla')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AQ', 'Antarctica')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AG', 'Antigua and Barbuda')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AR', 'Argentina')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AM', 'Armenia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AW', 'Aruba')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AU', 'Australia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AT', 'Austria')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AZ', 'Azerbaijan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BS', 'Bahamas')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BH', 'Bahrain')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BD', 'Bangladesh')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BB', 'Barbados')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BY', 'Belarus')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BE', 'Belgium')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BZ', 'Belize')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BJ', 'Benin')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BM', 'Bermuda')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BT', 'Bhutan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BO', 'Bolivia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BA', 'Bosnia and Herzegovina')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BW', 'Botswana')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BV', 'Bouvet Island')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BR', 'Brazil')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IO', 'British Indian Ocean Territory')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BN', 'Brunei Darussalam')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BG', 'Bulgaria')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BF', 'Burkina Faso')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'BI', 'Burundi')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KH', 'Cambodia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CM', 'Cameroon')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CA', 'Canada')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CV', 'Cape Verde')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KY', 'Cayman Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CF', 'Central African Republic')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TD', 'Chad')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CL', 'Chile')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CN', 'China')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CX', 'Christmas Island')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CC', 'Cocos (Keeling) Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CO', 'Colombia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KM', 'Comoros')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CD', 'Democratic Republic of the Congo')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CG', 'Republic of Congo')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CK', 'Cook Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CR', 'Costa Rica')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'HR', 'Croatia (Hrvatska)')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CU', 'Cuba')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CY', 'Cyprus')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CZ', 'Czech Republic')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DK', 'Denmark')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DJ', 'Djibouti')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DM', 'Dominica')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DO', 'Dominican Republic')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TP', 'East Timor')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'EC', 'Ecuador')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'EG', 'Egypt')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SV', 'El Salvador')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GQ', 'Equatorial Guinea')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ER', 'Eritrea')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'EE', 'Estonia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ET', 'Ethiopia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FK', 'Falkland Islands (Malvinas)')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FO', 'Faroe Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FJ', 'Fiji')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FI', 'Finland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FR', 'France')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FX', 'France, Metropolitan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GF', 'French Guiana')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PF', 'French Polynesia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TF', 'French Southern Territories')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GA', 'Gabon')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GM', 'Gambia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GE', 'Georgia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'DE', 'Germany')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GH', 'Ghana')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GI', 'Gibraltar')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GK', 'Guernsey')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GR', 'Greece')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GL', 'Greenland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GD', 'Grenada')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GP', 'Guadeloupe')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GU', 'Guam')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GT', 'Guatemala')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GN', 'Guinea')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GW', 'Guinea-Bissau')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GY', 'Guyana')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'HT', 'Haiti')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'HM', 'Heard and Mc Donald Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'HN', 'Honduras')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'HK', 'Hong Kong')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'HU', 'Hungary')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IS', 'Iceland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IN', 'India')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IM', 'Isle of Man')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ID', 'Indonesia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IR', 'Iran (Islamic Republic of)')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IQ', 'Iraq')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IE', 'Ireland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IL', 'Israel')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'IT', 'Italy')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CI', 'Ivory Coast')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'JE', 'Jersey')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'JM', 'Jamaica')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'JP', 'Japan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'JO', 'Jordan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KZ', 'Kazakhstan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KE', 'Kenya')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KI', 'Kiribati')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KP', 'Korea, Democratic People''s Republic of')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KR', 'Korea, Republic of')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'XK', 'Kosovo')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KW', 'Kuwait')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KG', 'Kyrgyzstan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LA', 'Lao People''s Democratic Republic')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LV', 'Latvia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LB', 'Lebanon')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LS', 'Lesotho')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LR', 'Liberia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LY', 'Libyan Arab Jamahiriya')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LI', 'Liechtenstein')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LT', 'Lithuania')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LU', 'Luxembourg')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MO', 'Macau')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MK', 'North Macedonia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MG', 'Madagascar')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MW', 'Malawi')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MY', 'Malaysia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MV', 'Maldives')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ML', 'Mali')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MT', 'Malta')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MH', 'Marshall Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MQ', 'Martinique')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MR', 'Mauritania')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MU', 'Mauritius')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TY', 'Mayotte')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MX', 'Mexico')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'FM', 'Micronesia, Federated States of')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MD', 'Moldova, Republic of')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MC', 'Monaco')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MN', 'Mongolia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ME', 'Montenegro')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MS', 'Montserrat')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MA', 'Morocco')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MZ', 'Mozambique')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MM', 'Myanmar')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NA', 'Namibia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NR', 'Nauru')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NP', 'Nepal')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NL', 'Netherlands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AN', 'Netherlands Antilles')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NC', 'New Caledonia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NZ', 'New Zealand')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NI', 'Nicaragua')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NE', 'Niger')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NG', 'Nigeria')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NU', 'Niue')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NF', 'Norfolk Island')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'MP', 'Northern Mariana Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'NO', 'Norway')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'OM', 'Oman')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PK', 'Pakistan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PW', 'Palau')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PS', 'Palestine')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PA', 'Panama')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PG', 'Papua New Guinea')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PY', 'Paraguay')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PE', 'Peru')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PH', 'Philippines')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PN', 'Pitcairn')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PL', 'Poland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PT', 'Portugal')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PR', 'Puerto Rico')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'QA', 'Qatar')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'RE', 'Reunion')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'RO', 'Romania')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'RU', 'Russian Federation')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'RW', 'Rwanda')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'KN', 'Saint Kitts and Nevis')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LC', 'Saint Lucia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VC', 'Saint Vincent and the Grenadines')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'WS', 'Samoa')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SM', 'San Marino')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ST', 'Sao Tome and Principe')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SA', 'Saudi Arabia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SN', 'Senegal')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'RS', 'Serbia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SC', 'Seychelles')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SL', 'Sierra Leone')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SG', 'Singapore')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SK', 'Slovakia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SI', 'Slovenia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SB', 'Solomon Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SO', 'Somalia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ZA', 'South Africa')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GS', 'South Georgia South Sandwich Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SS', 'South Sudan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ES', 'Spain')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'LK', 'Sri Lanka')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SH', 'St. Helena')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'PM', 'St. Pierre and Miquelon')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SD', 'Sudan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SR', 'Suriname')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SJ', 'Svalbard and Jan Mayen Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SZ', 'Swaziland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SE', 'Sweden')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'CH', 'Switzerland')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'SY', 'Syrian Arab Republic')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TW', 'Taiwan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TJ', 'Tajikistan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TZ', 'Tanzania, United Republic of')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TH', 'Thailand')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TG', 'Togo')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TK', 'Tokelau')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TO', 'Tonga')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TT', 'Trinidad and Tobago')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TN', 'Tunisia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TR', 'Turkey')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TM', 'Turkmenistan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TC', 'Turks and Caicos Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'TV', 'Tuvalu')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'UG', 'Uganda')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'UA', 'Ukraine')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'AE', 'United Arab Emirates')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'GB', 'United Kingdom')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'US', 'United States')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'UM', 'United States minor outlying islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'UY', 'Uruguay')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'UZ', 'Uzbekistan')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VU', 'Vanuatu')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VA', 'Vatican City State')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VE', 'Venezuela')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VN', 'Vietnam')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VG', 'Virgin Islands (British)')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'VI', 'Virgin Islands (U.S.)')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'WF', 'Wallis and Futuna Islands')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'EH', 'Western Sahara')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'YE', 'Yemen')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ZM', 'Zambia')",
            "INSERT INTO {$wpdb->prefix}bnd_country (code,name) VALUES ( 'ZW', 'Zimbabwe')"
        );
        global $wpdb;
        foreach ($country_list as $country) {
            $wpdb->query($country);
        }
        $data_sync_list = array(
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'merchant', 'Merchant',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'opening_hours', 'Opening Hours',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'order_types', 'Order Types',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'categories', 'Categories',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'modifier_groups', 'Modifier Groups/Options',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'items', 'Items',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'item_groups', 'Item Groups',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'attributes', 'Attributes',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'item_tags', 'Item Tags',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'tax_rates', 'Tax Rates',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'orders', 'Orders',1,NOW())",
            "INSERT INTO {$wpdb->prefix}bnd_data_sync (model_name, display_name, sync_enabled, last_sync_time) VALUES ( 'payments', 'Payments',1,NOW())"
            
        );
        foreach ($data_sync_list as $sync) {
            $wpdb->query($sync);
        }

        $message_template_list = array(
            "INSERT INTO {$wpdb->prefix}bnd_message_template (name, display_name, template_text, param_list) VALUES ( 'order_mail_user', 'Order Mail (User)','Dear user, Thank you for ordering from {merchant}. <br/>Your order no. is : {order_num}. You can view and download your receipt here. <br/>You can track your delivery <a href=\"#\">here','merchant,order_num')",
            "INSERT INTO {$wpdb->prefix}bnd_message_template (name, display_name, template_text, param_list) VALUES ( 'order_mail_merchant', 'Order Mail (Merchant)','Dear {merchant}, A new order has been received from  {customer}. The order no. is : {order_num}.','merchant,order_num')",
            "INSERT INTO {$wpdb->prefix}bnd_message_template (name, display_name, template_text, param_list) VALUES ( 'user_registration_merchant', 'New User Registration','Dear {merchant}, A new user has been registered with {user_email}','merchant,user_email')",
            "INSERT INTO {$wpdb->prefix}bnd_message_template (name, display_name, template_text, param_list) VALUES ( 'user_registration_user', 'New User Registration','Dear {user_email}, Your registration was succesful. You can start ordering online now.','user_email')",
            "INSERT INTO {$wpdb->prefix}bnd_message_template (name, display_name, template_text, param_list) VALUES ( 'forgot_password_user', 'Forgot Password','Dear {user_email}, Please click on the link below to reset your password.','user_email')",
        );
        foreach ($message_template_list as $sync) {
            $wpdb->query($sync);
        }
    }
    
    private function update_user_role() {
        add_role(
            'unverified_user', //  System name of the role.
            __( 'Unverified User'  ), // Display name of the role.
            array(
                'read'  => true,
                'delete_posts'  => false,
                'delete_published_posts' => false,
                'edit_posts'   => false,
                'publish_posts' => false,
                'upload_files'  => false,
                'edit_pages'  => false,
                'edit_published_pages'  =>  false,
                'publish_pages'  => false,
                'delete_published_pages' => false, // This user will NOT be able to  delete published pages.
            )
         );
    }

    private function update_default_options()
    {
        $current_options = (array) get_option('bnd_settings');
        $default_options = array(
            array(
                "name" => "bnd_api_key",
                "value" => ""
            ),
            array(
                "name" => "payment_key",
                "value" => ""
            ),
            array(
                "name" => "logo_url",
                "value" => ""
            ),
            array(
                "name" => "delivery_info",
                "value" => ""
            ),
            array(
                "name" => "use_discount_coupon",
                "value" => 1
            ),
            array(
                "name" => "use_two_factor",
                "value" => 0
            ),
            array(
                "name" => "track_stock",
                "value" => 0
            ),
            array(
                "name" => "track_stock_hide_items",
                "value" => 0
            ),
            array(
                "name" => "cash_on_delivery",
                "value" => 1
            ),
            array(
                "name" => "minimum_order_time",
                "value" => 30
            ),
            array(
                "name" => "maximum_order_time",
                "value" => 3
            ),
            array(
                "name" => "order_prefix",
                "value" => "AOOD"
            ),
            array(
                "name" => "allow_future_order",
                "value" => 1
            ),
            array(
                "name" => "delivery_fees_name",
                "value" => "Delivery Charge"
            ),
            array(
                "name" => "orders_when_closed",
                "value" => 1
            ),
            array(
                "name" => "bnd_save_cards",
                "value" => "disabled"
            ),
            array(
                "name" => "bnd_save_cards_fees",
                "value" => "disabled"
            ),
            array(
                "name" => "service_fees_name",
                "value" => "Service Fee"
            ),
            array(
                "name" => "service_fees_type",
                "value" => "amount"
            ),
            array(
                "name" => "service_fees_value",
                "value" => "0"
            ),
            array(
                "name" => "use_alternate_name",
                "value" => "enabled"
            ),
            array(
                "name" => "show_order_number",
                "value" => "off"
            ),
            array(
                "name" => "cdn_url",
                "value" => ""
            ),
            array(
                "name" => "image_base_url",
                "value" => ""
            ),
            array(
                "name" => "order_type_delivery",
                "value" => "DEFAULT"
            ),
            array(
                "name" => "order_type_pickup",
                "value" => "DEFAULT"
            ),
            array(
                "name" => "default_country_code",
                "value" => "US"
            ),
            array(
                "name" => "api_env",
                "value" => "production"
            ),
            array(
                "name" => "api_region",
                "value" => "us"
            ),
            array(
                "name" => "google_maps_api_key",
                "value" => ""
            ),
            array(
                "name" => "use_menu_homepage",
                "value" => "0"
            ),
            array(
                "name" => "show_blank_categories",
                "value" => "0"
            ),
            array(
                "name" => "override_online_category_update",
                "value" => "0"
            ),
            array(
                "name" => "last_global_sync",
                "value" => "0"
            ),
            array(
                "name" => "delivery_setup",
                "value" => ""
            ),
            array(
                "name" => "tip_taxed",
                "value" => "0"
            ),
            array(
                "name" => "delivery_taxed",
                "value" => "0"
            ),
            array(
                "name" => "outside_zone_delivery",
                "value" => "0"
            )
        );

        foreach ($default_options as $default_option) {
            if (! isset($current_options[$default_option["name"]]))
                $current_options[$default_option["name"]] = $default_option["value"];
        }
        $current_version = get_option('bnd_plugin_version');
        if ($current_version == null) {
            $current_version  = "1.0.1";
        }
        $new_version = $this->bnd_flex_order_delivery->get_version();
        if($current_version == null || version_compare($current_version, $new_version,'<'))
        {
            if ($new_version == "2.0.0") {
                $current_options["transaction_mid"]="";
                $current_options["api_key"]="";
                $current_options["merchant_id"]="";
                $current_options["access_token"]="";
                $current_options["merchant_login"]="";
                $current_options["last_global_sync"]="0";
                $current_options["delivery_setup"]="";
                $current_options["tip_taxed"]="0";
                $current_options["delivery_taxed"]="0";
                $current_options["outside_zone_delivery"]="0";
            }
        }
        $current_options["bnd_plugin_version"]=$new_version;
        update_option('bnd_settings', $current_options);
    }
    
    private function schedule_jobs() {
        // Make sure this event hasn't been scheduled
        if( !wp_next_scheduled( 'bnd_sync_clover_minute' ) ) {
            // Schedule the event
            wp_schedule_event( time(), 'five_minutes', 'bnd_sync_clover_minute' );
        }
        // Make sure this event hasn't been scheduled
        if( !wp_next_scheduled( 'bnd_sync_clover_daily' ) ) {
            // Schedule the event
            wp_schedule_event( time(), 'one_day', 'bnd_sync_clover_daily' );
        }
    }
}
