<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * @link              https://buynowdepot.com
 * @since             1.0.0
 * @package    Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/admin
 * @author     BuyNowDepot <admin@buynowdepot.com>
 */
class Bnd_Flex_Order_Delivery_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bnd_flex_order_delivery    The ID of this plugin.
	 */
	private $bnd_flex_order_delivery;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	private $rest_namespace="bnd-rest-api";

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $bnd_flex_order_delivery       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bnd_flex_order_delivery, $version ) {

		$this->bnd_flex_order_delivery = $bnd_flex_order_delivery;
		$this->version = $version;

	}
	
	/**
	 * 
	 *  Prepare admin menu.
	 *
	 * @since    1.0.0
	 * @param      string    $bnd_flex_order_delivery       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function add_admin_menu() {
	    $icon_url =  plugin_dir_url(dirname(__FILE__))."admin/assets/img/launcher.png";
	    add_menu_page('Settings page', 'Buy Now Depot', 'manage_options', 'bnd_flex_order_delivery_index', array($this, 'page_admin_dashboard'),$icon_url);
	    add_submenu_page('bnd_flex_order_delivery_index', 'Settings', 'Settings', 'manage_options', 'bnd_flex_order_delivery_index', array($this, 'page_admin_dashboard'));
	}
	
	/**
	 * Register the options.
	 *
	 * @since    1.0.0
	 */
	public function register_config() {
	    register_setting('bnd_flex_order_delivery_config', 'bnd_flex_order_delivery_config');
	}

	
	public function page_admin_dashboard()
	{
	    require_once plugin_dir_path( dirname(__FILE__)).'admin/partials/bnd-flex-order-delivery-admin-display.php';	    
	}
	
	// Register our routes.
	public function register_rest_routes() {
	    
	    $adminModels = Bnd_Flex_Order_Delivery_Container::instance()->get_admin_models();
	    foreach($adminModels as $adminModel=> $data) {
	        $className = "Bnd_Flex_Order_Delivery_API_".$data[0];
	        $class = new $className;
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/read", array(
	            array(
	                'methods'   => 'GET',
	                'callback'  => array( $class, 'read' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/create", array(
	            array(
	                'methods'   => 'POST',
	                'callback'  => array( $class, 'create' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/delete_selected", array(
	            array(
	                'methods'   => 'POST,DELETE',
	                'callback'  => array( $class, 'deleteSelected' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/delete", array(
	            array(
	                'methods'   => 'POST,DELETE',
	                'callback'  => array( $class, 'delete' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/read_paging", array(
	            array(
	                'methods'   => 'GET',
	                'callback'  => array( $class, 'readPaging' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/search_paging", array(
	            array(
	                'methods'   => 'GET',
	                'callback'  => array( $class, 'searchPaging' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/search", array(
	            array(
	                'methods'   => 'GET',
	                'callback'  => array( $class, 'search' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/update", array(
	            array(
	                'methods'   => 'PUST,POST,DELETE',
	                'callback'  => array( $class, 'update' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	        register_rest_route( $this->rest_namespace, '/'.$adminModel."/update_field", array(
	            array(
	                'methods'   => 'POST,PUT',
	                'callback'  => array( $class, 'updateField' ),
	                'permission_callback' => '__return_true'
	            )
	        ) );
	    }
	    //rest route for loading templates 
	    register_rest_route( $this->rest_namespace, '/load_template', array(
	        array(
	            'methods'   => 'GET',
	            'callback'  => array( $this, 'bnd_admin_load_template' ),
	            'permission_callback' => '__return_true'
	        )
	    ) );
	    register_rest_route( $this->rest_namespace, '/run_query', array(
	        array(
	            'methods'   => 'GET',
	            'callback'  => array( $this, 'bnd_admin_run_query' ),
	            'permission_callback' => '__return_true'
	        )
	    ) );
	    register_rest_route( $this->rest_namespace, '/save_sort_order', array(
	        array(
	            'methods'   => 'POST',
	            'callback'  => array( $this, 'bnd_admin_save_sort_order' ),
	            'permission_callback' => '__return_true'
	        )
	    ) );
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bnd_Flex_Order_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bnd_Flex_Order_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	    wp_enqueue_style( 'wp-jquery-ui-dialog');
	    wp_register_style( 'bnd-fonts-awesome', plugin_dir_url(dirname(__FILE__))."assets/js/vendor/font-awesome/css/all.min.css" ,array(), $this->version);
	    wp_enqueue_style( 'bnd-fonts-awesome' );
	    //wp_register_style( 'bnd-jquery-ui', plugin_dir_url(__FILE__)."assets/js/jquery-ui/jquery-ui.css" ,array(), $this->version);
	    //wp_enqueue_style( 'bnd-jquery-ui' );
	    //wp_register_style( 'bnd-jquery-ui-theme', plugin_dir_url(__FILE__)."assets/js/jquery-ui/jquery-ui.theme.css" ,array(), $this->version);
	    //wp_enqueue_style( 'bnd-jquery-ui-theme' );
	    wp_register_style( 'bnd-bootstrap' ,plugin_dir_url(dirname(__FILE__))."assets/js/vendor/bootstrap/css/bootstrap.min.css" ,array(), $this->version);
	    wp_enqueue_style( 'bnd-bootstrap' );
	    wp_register_style( 'bnd-bootstrap-editable' ,plugin_dir_url(__FILE__)."assets/js/bootstrap-editable/css/bootstrap-editable.css" ,array(), $this->version);
	    wp_enqueue_style( 'bnd-bootstrap-editable' );
	    wp_register_style( 'bnd-main' ,plugin_dir_url(__FILE__)."assets/css/main.css" ,array(), $this->version);
	    wp_enqueue_style( 'bnd-main' );
	    wp_register_style( 'bnd-plugin' ,plugin_dir_url(__FILE__)."assets/css/bnd-flex-order-delivery-admin.css" ,array(), $this->version);
	    wp_enqueue_style( 'bnd-plugin' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bnd_Flex_Order_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bnd_Flex_Order_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	    wp_enqueue_script("jquery");
	    wp_enqueue_script('jquery-ui-core');
	    //wp_enqueue_media();
	    wp_enqueue_script( 'media-upload' );
	    wp_enqueue_media();

	    wp_enqueue_script('bnd-jquery-validate',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/jquery/jquery.validate.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-bootstrap',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/bootstrap/js/bootstrap.bundle.min.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-bootstrap-editable',  plugin_dir_url(dirname(__FILE__))."admin/assets/js/bootstrap-editable/js/bootstrap-editable.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-ckedit',  plugin_dir_url(dirname(__FILE__))."admin/assets/js/ckeditor/ckeditor.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-moment',  plugin_dir_url(dirname(__FILE__))."admin/assets/js/moment.min.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-bootbox',  plugin_dir_url(dirname(__FILE__))."assets/js/vendor/bootbox.all.min.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-Chart',   plugin_dir_url(dirname(__FILE__))."admin/assets/js/Chart.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-admin-main',  plugin_dir_url(dirname(__FILE__))."admin/assets/js/bnd-flex-order-delivery-admin.js", array("jquery"), false, false);
	    wp_enqueue_script('bnd-admin-app-model',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/model-display.js", array("jquery"), false, true);
	    wp_enqueue_script('bnd-admin-app-inventory',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/inventory.js", array("bnd-admin-main"), false, true);
	    wp_enqueue_script('bnd-admin-app-connection',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/connections.js", array("bnd-admin-main"), false, true);
	    wp_enqueue_script('bnd-admin-app-merchant',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/merchant.js", array("bnd-admin-main"), false, true);
	    wp_enqueue_script('bnd-admin-app-delivery',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/delivery.js", array("bnd-admin-main"), false, true);
	    wp_enqueue_script('bnd-admin-app-bndclient',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/buynowdepot.js", array("bnd-admin-main"), false, true);
	    wp_enqueue_script('bnd-admin-app-bndlogin',  plugin_dir_url(dirname(__FILE__))."admin/assets/app/bndlogin.js", array("bnd-admin-main"), false, true);
	    
	    wp_enqueue_script('bnd-admin-qrcode',  plugin_dir_url(dirname(__FILE__))."admin/assets/js/qrcode.js", array("bnd-admin-main"), false, false);
	    $BndSettings = (array)get_option("bnd_settings");
	    if ($BndSettings["google_maps_api_key"]!="") {
	       wp_enqueue_script('bnd-google-map', "https://maps.googleapis.com/maps/api/js?key=".$BndSettings["google_maps_api_key"]."&libraries=drawing&v=3", array("jquery"), false, true);
	    }
	}
	
	
	public function bnd_admin_save_key() {
	    $params = buynowdepot_get_post_array();
	    $BndSettings = (array)get_option("bnd_settings");
	    $BndSettings["api_key"] = $params["key"];
	    update_option("bnd_settings", $BndSettings);
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    $status = $cloverClient->requestAccessToken();
	    if ($status == true) {
	        return wp_send_json(array("status"=>"success", "message"=>"Your request was successful. You are succesfully connected to your account."));
	    }
	    else {
	        $BndSettings["api_key"] = "";
	        update_option("bnd_settings", $BndSettings);
	        return wp_send_json(array("status"=>"error", "message"=>"There was an error connecting to your account. Please check if you have a valid API key"));
	    }
	}
	
	public function bnd_admin_save_license() {
	    $params = buynowdepot_get_post_array();
	    $BndSettings = (array)get_option("bnd_settings");
	    $BndSettings["merchant_login"] = $params["merchant_email"];
	    error_log(json_encode($BndSettings));
	    update_option("bnd_settings", $BndSettings);
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    $status = $cloverClient->requestTokenByEmail();
	    return wp_send_json(array("status"=>"success", "message"=>"Your request was successful. Website has been registered successfully."));
	}
	
	public function bnd_admin_remove_key() {
	    $BndSettings = (array)get_option("bnd_settings");
	    $BndSettings["api_key"] = "";
	    update_option("bnd_settings", $BndSettings);
	    return wp_send_json(array("status"=>"success", "message"=>"Your API key has been remove from configuration"));
	}
	
	public function bnd_admin_save_delivery() {
	    $params = buynowdepot_get_post_array();
	    $BndSettings = (array)get_option("bnd_settings");
	    $BndSettings["delivery_info"] = json_encode($params);
	    update_option("bnd_settings", $BndSettings);
	    return wp_send_json(array("status"=>"success", "message"=>"Delivery information updated successfully"));
	}
	
	public function bnd_admin_save_configuration() {
	    $params = buynowdepot_get_post_array();
	    $BndSettings = (array)get_option("bnd_settings");
	    foreach($params as $key => $value) {
	        $BndSettings[$key]=$value;
	    }
	    update_option("bnd_settings", $BndSettings);
	    return wp_send_json(array("status"=>"success", "message"=>"Configuration information saved successfully"));
	}
	
	public function bnd_admin_import_categories() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importCategories());
	}
	
	public function bnd_admin_import_modifier_groups() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importModifierGroups());
	}
	
	public function bnd_admin_import_items() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importItems());
	}
	
	public function bnd_admin_import_items_by_category() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    $db = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
	    $categories = $db->getAllModels("category");
	    $count=0;
	    foreach ($categories as $category) {
	        $response = $cloverClient->importItemsByCategory($category->clid);
	        error_log(json_encode($response));
	        if ($response["status"]=="success") {
	            $count+=$response["count"];
	        }
	    }
	    return wp_send_json(array("status"=>"success", "message"=>"Items imported", "count"=>$count));
	}
	
	public function bnd_admin_import_tax_rates() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importTaxRates());
	}
	
	public function bnd_admin_import_item_tags() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importTags());
	}
	public function bnd_admin_import_item_groups() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json(array("status"=>"success","message"=>"Item groups imported","count"=>0));
	    
	}
	public function bnd_admin_import_attributes() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json(array("status"=>"success","message"=>"Attributes imported","count"=>0));
	}
	
	public function bnd_admin_import_merchant() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    $merchant_message = $cloverClient->importMerchant();
	    $merchant_prop_message = $cloverClient->importMerchantProperties();
	    $merchant_service_charge = $cloverClient->importMerchantServiceCharge();
	    //Create dummy service Items
	    $serviceItem = $cloverClient->getSingleItem("Service Charge");
	    $items = $serviceItem["content"]["elements"];
	    if (empty($items)) {
	        $item = array(
	            "id"=>"service_fees",
	            "hidden"=>true,
	            "name"=>"Service Charge",
	            "price"=>0,
	            "priceType"=>"VARIABLE",
	            "defaultTaxRates"=>true
	        );
	        $cloverClient->createDummyItem($item);
	    }
	    $deliveryItem = $cloverClient->getSingleItem("Delivery Charge");
	    $itemd = $deliveryItem["content"]["elements"];
	    if (empty($itemd)) {
	        $item = array(
	            "id"=>"delivery_fees",
	            "hidden"=>true,
	            "name"=>"Delivery Charge",
	            "price"=>0,
	            "priceType"=>"VARIABLE",
	            "defaultTaxRates"=>true
	        );
	        $cloverClient->createDummyItem($item);
	    }
	    $tipItem = $cloverClient->getSingleItem("Tip");
	    $itemt = $tipItem["content"]["elements"];
	    if (empty($itemt)) {
	        $item = array(
	            "id"=>"tip_charge",
	            "hidden"=>true,
	            "name"=>"Tip",
	            "price"=>0,
	            "priceType"=>"VARIABLE",
	            "defaultTaxRates"=>false
	        );
	        $cloverClient->createDummyItem($item);
	    }
	    return wp_send_json($merchant_message);
	}
	
	public function bnd_admin_import_opening_hours() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importOpeningHours());
	}
	
	public function bnd_admin_import_order_types() {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->importOrderTypes());
	}
	
	public function bnd_admin_send_order($order_details) {
	    $cloverClient = Bnd_Flex_Order_Delivery_Container::instance()->getCloverClient();
	    return wp_send_json($cloverClient->sendOrder());
	}
	
	public function bnd_admin_load_template() {
	    $templateName = sanitize_text_field($_GET["tn"]);
	    ob_start();
	    include_once BUYNOWDEPOT_PLUGIN_DIR . '/admin/templates/'.$templateName.".php";
	    return ob_get_clean();
	}
	
	public function bnd_admin_run_query() {
	    $actionName = sanitize_text_field($_GET["action"]);
	    $className = "Bnd_Flex_Order_Delivery_API_Generic";
	    $class = new $className;
	    return call_user_func(array($class, $actionName), $_GET);
	}
	
	public function bnd_admin_save_sort_order() {
	    $className = "Bnd_Flex_Order_Delivery_API_Generic";
	    $class = new $className;
	    return call_user_func(array($class, "saveSortOrder"), $_POST);
	}
	
	/**
	 * This function runs every minutes and sync data from clover
	 */
	public function bnd_sync_clover_minute() {
	    error_log("Running 5 minute function at ".time());
	    $current_time = date('Y-m-d H:i:s');
	    $repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
	    $repository->syncOrders($current_time);
	    $repository->syncItems($current_time);
	}
	
	/**
	 * This function runs every minutes and sync data from clover
	 */
	public function bnd_sync_clover_daily() {
	    error_log("Running this daily function at ".time());
	    $repository = Bnd_Flex_Order_Delivery_Container::instance()->getRepository();
	    $current_time = date('Y-m-d H:i:s');
	    $repository->syncMerchant($current_time);
	    $repository->syncOpeningHours($current_time);
	    $repository->syncOrderTypes($current_time);
	    $repository->syncTaxRates($current_time);
	}
}
