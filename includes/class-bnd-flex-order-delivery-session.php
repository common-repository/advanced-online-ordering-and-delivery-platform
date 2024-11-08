<?php
/**
 * Bnd_Flex_Order_Delivery_Session Session
 *
 * This is a wrapper class for WP_Session / PHP $_SESSION and handles the storage of cart items, purchase sessions, etc.
 * � Copyright 2021  Website Experts Inc./ DBA Buy Now Depot�
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Bnd_Flex_Order_Delivery_Session Class
 *
 * @since 1.0
 */
final class Bnd_Flex_Order_Delivery_Session {

	/**
	 * Holds our session data
	 *
	 * @var array
	 * @access private
	 * @since 1.0
	 */
	private $session;


	/**
	 * Whether to use PHP $_SESSION or WP_Session
	 *
	 * @var bool
	 * @access private
	 * @since 1.0,1
	 */
	private $use_php_sessions = false;

	/**
	 * Session index prefix
	 *
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $prefix = '';


	private static $instance;


	public static function instance() {
	    
	    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Bnd_Flex_Order_Delivery_Session ) ) {
	        self::$instance = new Bnd_Flex_Order_Delivery_Session;
	    }
	    self::$instance->use_php_sessions = self::$instance->use_php_sessions();
	    
	    if( self::$instance->use_php_sessions ) {
	        
	        if( is_multisite() ) {
	            
	            self::$instance->prefix = '_' . get_current_blog_id();
	        }
	        add_action( 'init', array( self::$instance, 'maybe_start_session' ), -2 );
	        
	    } else {
	        
	        if( ! self::$instance->should_start_session() ) {
	            return;
	        }        
	        // Use WP_Session (default)	        
	        if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
	            define( 'WP_SESSION_COOKIE', 'bnd_wp_session' );
	        }
	        
	        add_filter( 'wp_session_expiration_variant', array( self::$instance, 'set_expiration_variant_time' ), 99999 );
	        add_filter( 'wp_session_expiration', array( self::$instance, 'set_expiration_time' ), 99999 );
	        
	    }
	    if ( empty( self::$instance->session ) && ! self::$instance->use_php_sessions ) {
	        add_action( 'plugins_loaded', array( self::$instance, 'init' ), -1 );
	    } else {
	        add_action( 'init', array( self::$instance, 'init' ), -1 );
	    }
	    return self::$instance;
	}
	
	/**
	 * Setup the WP_Session instance
	 *
	 * @since 1.0
	 * @return void
	 */
	function init() {
		if( $this->use_php_sessions ) {
			$this->session = isset( $_SESSION['bnd' . $this->prefix ] ) && is_array( $_SESSION['bnd' . $this->prefix ] ) ? $_SESSION['bnd' . $this->prefix ] : array();
		} else {
			$this->session = WP_Session::get_instance();
		}
		$use_cookie = $this->use_cart_cookie();
		$cart       = $this->get( 'bnd_cart' );
		$purchase   = $this->get( 'bnd_purchase' );
		if ( $use_cookie ) {
			if( ! empty( $cart ) || ! empty( $purchase ) ) {
				$this->set_cart_cookie();
			} else {
				$this->set_cart_cookie( false );
			}
		}
		return $this->session;
	}


	/**
	 * Retrieve session ID
	 *
	 * @since  1.0.0
	 * @return string Session ID
	 */
	function get_id() {
		return $this->session->session_id;
	}


	/**
	 * Retrieve a session variable
	 *
	 * @since 1.0
	 * @param string $key Session key
	 * @return mixed Session variable
	 */
	function get( $key ) {

		$key    = sanitize_key( $key );
		$return = false;
        $matches="";
		if ( isset( $this->session[ $key ] ) && ! empty( $this->session[ $key ] ) ) {
			if ( is_numeric( $this->session[ $key ] ) ) {
				$return = $this->session[ $key ];
			} else {
				$maybe_json = json_decode( $this->session[ $key ] );
				// Since json_last_error is PHP 5.3+, we have to rely on a `null` value for failing to parse JSON.
				if ( is_null( $maybe_json ) ) {
					$is_serialized = is_serialized( $this->session[ $key ] );
					if ( $is_serialized ) {
						$value = @unserialize( $this->session[ $key ] );
						$this->set( $key, (array) $value );
						$return = $value;
					} else {
						$return = $this->session[ $key ];
					}
				} else {
					$return = json_decode( $this->session[ $key ], true );
				}

			}
		}

		return $return;
	}

	/**
	 * Set a session variable
	 *
	 * @since 1.0
	 *
	 * @param string $key Session key
	 * @param int|string|array $value Session variable
	 * @return mixed Session variable
	 */
	function set( $key, $value ) {

		$key = sanitize_key( $key );
		if ( is_array( $value )  || is_object($value)) {
			$this->session[ $key ] = wp_json_encode( $value );
		} else {
			$this->session[ $key ] = esc_attr( $value );
		}
		if( $this->use_php_sessions ) {
			$_SESSION['bnd' . $this->prefix ] = $this->session;
		}
		return $this->session[ $key ];
	}

	/**
	 * Set a cookie to identify whether the cart is empty or not
	 *
	 * This is for hosts and caching plugins to identify if caching should be disabled
	 *
	 * @since 1.0
	 * @param bool $set Whether to set or destroy
	 * @return void
	 */
	function set_cart_cookie( $set = true ) {
		if( ! headers_sent() ) {
			if( $set ) {
				@setcookie( 'bnd_items_in_cart', '1', time() + 30 * 60, COOKIEPATH, COOKIE_DOMAIN, false );
			} else {
				if ( isset($_COOKIE['bnd_items_in_cart']) ) {
					@setcookie( 'bnd_items_in_cart', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );
				}
			}
		}
	}

	/**
	 * Force the cookie expiration variant time to 23 hours
	 *
	 * @since  1.0.0
	 * @param int $exp Default expiration (1 hour)
	 * @return int
	 */
	function set_expiration_variant_time( $exp ) {
		return ( 30 * 60 * 23 );
	}

	/**
	 * Force the cookie expiration time to 24 hours
	 *
	 * @since  1.0.0
	 * @param int $exp Default expiration (1 hour)
	 * @return int Cookie expiration time
	 */
	function set_expiration_time( $exp ) {
		return ( 30 * 60 * 24 );
	}

	/**
	 * Starts a new session if one hasn't started yet.
	 *
	 * @return boolean
	 * Checks to see if the server supports PHP sessions
	 * or if the BUYNOWDEPOT_USE_PHP_SESSIONS constant is defined
	 *
	 */
	function use_php_sessions() {

		$ret = false;

		// If the database variable is already set, no need to run autodetection
		$bnd_use_php_sessions = (bool) get_option( 'bnd_use_php_sessions' );
		if ( ! $bnd_use_php_sessions ) {
			// Attempt to detect if the server supports PHP sessions
			if( function_exists( 'session_start' ) ) {
				$this->set( 'bnd_use_php_sessions', 1 );
				if( $this->get( 'bnd_use_php_sessions' ) ) {
					$ret = true;
					// Set the database option
					update_option( 'bnd_use_php_sessions', true );
				}
			}
		} else {
			$ret = $bnd_use_php_sessions;
		}

		// Enable or disable PHP Sessions based on the BUYNOWDEPOT_USE_PHP_SESSIONS constant
		if ( defined( 'BUYNOWDEPOT_USE_PHP_SESSIONS' ) && BUYNOWDEPOT_USE_PHP_SESSIONS ) {
			$ret = true;
		} else if ( defined( 'BUYNOWDEPOT_USE_PHP_SESSIONS' ) && ! BUYNOWDEPOT_USE_PHP_SESSIONS ) {
			$ret = false;
		}
		return (bool) apply_filters( 'bnd_use_php_sessions', $ret );
	}

	/**
	 * Determines if a user has set the BUYNOWDEPOT_USE_CART_COOKIE
	 *
	 * @since  1.0.0
	 * @return bool If the store should use the bnd_items_in_cart cookie to help avoid caching
	 */
	function use_cart_cookie() {
		$ret = true;

		if ( defined( 'BUYNOWDEPOT_USE_CART_COOKIE' ) && ! BUYNOWDEPOT_USE_CART_COOKIE ) {
			$ret = false;
		}

		return (bool) apply_filters( 'bnd_use_cart_cookie', $ret );
	}

	/**
	 * Determines if we should start sessions
	 *
	 * @since 1.01
	 * @return bool
	 */
	function should_start_session() {

		$start_session = true;
		$blacklist=array();

		if( ! empty( $_SERVER[ 'REQUEST_URI' ] ) ) {

			$uri       = ltrim( $_SERVER[ 'REQUEST_URI' ], '/' );
			$uri       = untrailingslashit( $uri );

			if( in_array( $uri, $blacklist ) ) {
				$start_session = false;
			}

			if( false !== strpos( $uri, 'feed=' ) ) {
				$start_session = false;
			}

			if( is_admin() && false === strpos( $uri, 'wp-admin/admin-ajax.php' ) ) {
				// We do not want to start sessions in the admin unless we're processing an ajax request
				$start_session = false;
			}

			if( false !== strpos( $uri, 'wp_scrape_key' ) ) {
				// Starting sessions while saving the file editor can break the save process, so don't start
				$start_session = false;
			}

		}

		return apply_filters( 'bnd_start_session', $start_session );

	}

	/**
	 * Starts a new session if one hasn't started yet.
	 */
	function maybe_start_session() {

		if( ! $this->should_start_session() ) {
			return;
		}

		if( ! session_id() && ! headers_sent() ) {
			session_start();
		}
	}
}