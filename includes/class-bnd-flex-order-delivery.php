<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
 class Bnd_Flex_Order_Delivery {

   
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bnd_Flex_Order_Delivery_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $bnd_flex_order_delivery    The string used to uniquely identify this plugin.
	 */
	public $bnd_flex_order_delivery;
	
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	public $version;
	
	public $templates;
	
	public $session;

	//public $cart;
	
	public $container;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BUYNOWDEPOT_FLEX_ORDER_DELIVERY_VERSION' ) ) {
			$this->version = BUYNOWDEPOT_FLEX_ORDER_DELIVERY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_service_hooks();
		$this->register_template();
		$this->session = Bnd_Flex_Order_Delivery_Session::instance();
		//$this->cart  =   new Bnd_Flex_Order_Delivery_Cart();
		$this->contianer = Bnd_Flex_Order_Delivery_Container::instance();
		Bnd_Flex_Order_Delivery_Shortcodes::init();
	}
	
	private function register_template() {
	    
	   $this-> template = array(BUYNOWDEPOT_PLUGIN_DIR."themes/flexmenu/page.php", "BuyNowDepot Custom Template");   	    
	   add_filter("theme_page_templates", array($this, 'custom_template'));
	   add_filter("template_include", array($this, 'bnd_include_template'));   
	   //add_filter('single_template',  array($this, 'bnd_menu_page_template'));
	   //add_filter('stylesheet',array($this, 'loading_flexmenu_template'));
	   //add_filter('template',array($this, 'loading_flexmenu_style')); 
	   //switch_theme( 'flexmenu' );
	        
	}
	
	public function bnd_menu_page_template( $page_template )
	{
	    global $wp;
	    $current_slug = add_query_arg( array(), $wp->request );
	    if (strpos($current_slug,'bnd-')==0) {
	        $page_template = BUYNOWDEPOT_PLUGIN_DIR . 'themes/flexmenu/page.php';
	    }
	    return $page_template;
	}
	
	public function custom_template($templates) {
	    $templates = array_merge($templates, $this->template);
	    return $templates;
	}
	
	public function bnd_include_template( $template ) {
	    global $post;
	    
	    if (!$post) {
	        return $template;
	    }
	    if (strpos($post->post_name, "bnd") === 0) {
	        $template_name = BUYNOWDEPOT_PLUGIN_DIR."themes/flexmenu/index.php";
	        error_log($template);
	        return $template_name;
	    }
	    return $template;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bnd_Flex_Order_Delivery_Loader. Orchestrates the hooks of the plugin.
	 * - Bnd_Flex_Order_Delivery_i18n. Defines internationalization functionality.
	 * - Bnd_Flex_Order_Delivery_Admin. Defines all hooks for the admin area.
	 * - Bnd_Flex_Order_Delivery_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

	    /**
	     * This class is responsible for loading template pages
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/bnd-flex-order-delivery-container.php';
	    
	    /**
	     * This class is responsible for loading template pages
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/bnd-flex-order-delivery-functions.php';
	    
	    /**
	     * This class is responsible for get and post action
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/bnd-flex-order-delivery-actions.php';
	    
	    /**
	     * The class responsible for defining Html Session details
	     * of the plugin.
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/bnd-flex-order-delivery-account.php';
	    
	    /**
	     * The class responsible for defining Html Session details
	     * of the plugin.
	     */
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/bnd-flex-order-delivery-users.php';
	    
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bnd-flex-order-delivery-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bnd-flex-order-delivery-i18n.php';
		
		
		/**
		 * The class responsible for defining all short codes
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bnd-flex-order-delivery-shortcodes.php';
		
		
		/**
		 * The class responsible for defining Html Session details
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bnd-flex-order-delivery-session.php';
		
		/**
		 * The class responsible for defining shopping cart
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bnd-flex-order-delivery-cart.php';
				
	
		/**
		 * This class is responsible for making request with clover
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/client/class-bnd-flex-order-delivery-clover-client.php';
		
		/**
		 * This class is responsible for sending email messages
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/client/class-bnd-flex-order-delivery-email-client.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bnd-flex-order-delivery-admin.php';
		
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-bnd-flex-order-delivery-db.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'models/class-bnd-flex-order-delivery-repository.php';

		/**
		 * This class is responsible for API related to 
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/CardConnectRestClient.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bnd-flex-order-delivery-public.php';
		
		//register all API classes
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-base.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-generic.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-category.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-message-template.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-item.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-item-group.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-merchant.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-modifiergroup.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-option.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-order-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-order.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-order-payment.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-tax-rate.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-opening-hours.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-attribute.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-item-image.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-discount-coupon.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-delivery-zone.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/api/class-bnd-flex-order-delivery-api-data-sync.php';
		
		$this->loader = new Bnd_Flex_Order_Delivery_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bnd_Flex_Order_Delivery_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bnd_Flex_Order_Delivery_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_service_hooks() {
	    
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Bnd_Flex_Order_Delivery_Admin( $this->get_bnd_flex_order_delivery(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		//register admin menus
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_config' );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'register_rest_routes' );
		//$this->loader->add_action( 'admin_bar_menu', $plugin_admin, 'toolbar_link_to_settings',999 );
		
		$this->loader->add_action( 'wp_ajax_bnd_admin_save_key', $plugin_admin, 'bnd_admin_save_key');
		$this->loader->add_action( 'wp_ajax_bnd_admin_save_license', $plugin_admin, 'bnd_admin_save_license');
		$this->loader->add_action( 'wp_ajax_bnd_admin_remove_key', $plugin_admin, 'bnd_admin_remove_key');
		$this->loader->add_action( 'wp_ajax_bnd_admin_save_delivery', $plugin_admin, 'bnd_admin_save_delivery');
		$this->loader->add_action( 'wp_ajax_bnd_admin_save_configuration', $plugin_admin, 'bnd_admin_save_configuration');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_categories', $plugin_admin, 'bnd_admin_import_categories');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_modifier_groups', $plugin_admin, 'bnd_admin_import_modifier_groups');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_items', $plugin_admin, 'bnd_admin_import_items');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_items_by_category', $plugin_admin, 'bnd_admin_import_items_by_category');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_merchant', $plugin_admin, 'bnd_admin_import_merchant');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_opening_hours', $plugin_admin, 'bnd_admin_import_opening_hours');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_order_types', $plugin_admin, 'bnd_admin_import_order_types');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_tax_rates', $plugin_admin, 'bnd_admin_import_tax_rates');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_item_tags', $plugin_admin, 'bnd_admin_import_item_tags');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_item_groups', $plugin_admin, 'bnd_admin_import_item_groups');
		$this->loader->add_action( 'wp_ajax_bnd_admin_import_attributes', $plugin_admin, 'bnd_admin_import_attributes');
		$this->loader->add_action( 'wp_ajax_bnd_admin_send_order', $plugin_admin, 'bnd_admin_send_order');
		$this->loader->add_action( 'wp_ajax_bnd_admin_load_template', $plugin_admin, 'bnd_admin_load_template');
		//Scheduled jobs
		$this->loader->add_action('bnd_sync_clover_minute',$plugin_admin, 'bnd_sync_clover_minute' );
		$this->loader->add_action('bnd_sync_clover_daily', $plugin_admin, 'bnd_sync_clover_daily' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bnd_Flex_Order_Delivery_Public( $this->get_bnd_flex_order_delivery(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' ,11);
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'wp_ajax_buynowdepot_get_modifiers', $plugin_public, 'buynowdepot_get_modifiers');
		$this->loader->add_action( 'wp_ajax_nopriv_buynowdepot_get_modifiers', $plugin_public, 'buynowdepot_get_modifiers');
		
		$this->loader->add_action( 'wp_ajax_buynowdepot_edit_modifiers', $plugin_public, 'buynowdepot_edit_modifiers');
		$this->loader->add_action( 'wp_ajax_nopriv_buynowdepot_edit_modifiers', $plugin_public, 'buynowdepot_edit_modifiers');
		
		//Add item to cart
		$this->loader->add_action( 'wp_ajax_bnd_add_to_cart', $plugin_public, 'bnd_add_to_cart');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_add_to_cart', $plugin_public, 'bnd_add_to_cart');
		
		//Delete Item form cart
		$this->loader->add_action( 'wp_ajax_bnd_delete_from_cart', $plugin_public, 'bnd_delete_from_cart');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_delete_from_cart', $plugin_public, 'bnd_delete_from_cart');
		
		//Display cart items
		$this->loader->add_action( 'wp_ajax_bnd_display_cart', $plugin_public, 'bnd_display_cart');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_display_cart', $plugin_public, 'bnd_display_cart');
		
		//Display cart items
		$this->loader->add_action( 'wp_ajax_bnd_display_cart_items', $plugin_public, 'bnd_display_cart_items');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_display_cart_items', $plugin_public, 'bnd_display_cart_items');
		
		//Display address
		$this->loader->add_action( 'wp_ajax_bnd_display_address', $plugin_public, 'bnd_display_address');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_display_address', $plugin_public, 'bnd_display_address');
		
		//Update cart item
		$this->loader->add_action( 'wp_ajax_bnd_update_cart', $plugin_public, 'bnd_update_cart');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_update_cart', $plugin_public, 'bnd_update_cart');
		
		//Update item quantity
		$this->loader->add_action( 'wp_ajax_bnd_update_quantity', $plugin_public, 'bnd_update_quantity');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_update_quantity', $plugin_public, 'bnd_update_quantity');
		
		//Add new address
		$this->loader->add_action( 'wp_ajax_bnd_add_address', $plugin_public, 'bnd_add_address');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_add_address', $plugin_public, 'bnd_add_address');
		
		$this->loader->add_action( 'wp_ajax_bnd_save_profile', $plugin_public, 'bnd_save_profile');
		$this->loader->add_action( 'wp_ajax_bnd_save_password', $plugin_public, 'bnd_save_password');
		//Update address 
		$this->loader->add_action( 'wp_ajax_bnd_edit_address', $plugin_public, 'bnd_edit_address');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_edit_address', $plugin_public, 'bnd_edit_address');
		$this->loader->add_action( 'wp_ajax_bnd_update_address', $plugin_public, 'bnd_update_address');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_update_address', $plugin_public, 'bnd_update_address');
		
		//Add new address
		$this->loader->add_action( 'wp_ajax_bnd_display_profile_address', $plugin_public, 'bnd_display_profile_address');
		$this->loader->add_action( 'wp_ajax_bnd_default_profile_address', $plugin_public, 'bnd_default_profile_address');
		$this->loader->add_action( 'wp_ajax_bnd_add_profile_address', $plugin_public, 'bnd_add_profile_address');
		$this->loader->add_action( 'wp_ajax_bnd_edit_profile_address', $plugin_public, 'bnd_edit_profile_address');
		$this->loader->add_action( 'wp_ajax_bnd_update_profile_address', $plugin_public, 'bnd_update_profile_address');
		$this->loader->add_action( 'wp_ajax_bnd_remove_profile_address', $plugin_public, 'bnd_add_profile_address');
		
		//remove address
		$this->loader->add_action( 'wp_ajax_bnd_remove_address', $plugin_public, 'bnd_remove_address');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_remove_address', $plugin_public, 'bnd_remove_address');
		
		//select address
		$this->loader->add_action( 'wp_ajax_bnd_select_address', $plugin_public, 'bnd_select_address');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_select_address', $plugin_public, 'bnd_select_address');
		
		//Update pickup address
		$this->loader->add_action( 'wp_ajax_bnd_confirm_pickup', $plugin_public, 'bnd_confirm_pickup');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_confirm_pickup', $plugin_public, 'bnd_confirm_pickup');
		
		//Update pickup address
		$this->loader->add_action( 'wp_ajax_bnd_confirm_delivery', $plugin_public, 'bnd_confirm_delivery');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_confirm_delivery', $plugin_public, 'bnd_confirm_delivery');
		
		//Order with payment
		$this->loader->add_action( 'wp_ajax_bnd_complete_order_payment', $plugin_public, 'bnd_complete_order_payment');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_complete_order_payment', $plugin_public, 'bnd_complete_order_payment');
		
		//Order without payment
		$this->loader->add_action( 'wp_ajax_bnd_complete_order', $plugin_public, 'bnd_complete_order');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_complete_order', $plugin_public, 'bnd_complete_order');
		
		$this->loader->add_action( 'wp_ajax_bnd_apply_discount', $plugin_public, 'bnd_apply_discount');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_apply_discount', $plugin_public, 'bnd_apply_discount');
		
		$this->loader->add_action( 'wp_ajax_bnd_apply_tip', $plugin_public, 'bnd_apply_tip');
		$this->loader->add_action( 'wp_ajax_nopriv_bnd_apply_tip', $plugin_public, 'bnd_apply_tip');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_bnd_flex_order_delivery() {
	    return $this->bnd_flex_order_delivery;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bnd_Flex_Order_Delivery_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
