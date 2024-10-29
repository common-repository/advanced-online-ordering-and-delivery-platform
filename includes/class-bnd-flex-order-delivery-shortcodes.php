<?php
/**
 * Shortcodes
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
defined('ABSPATH') || exit();

/**
 *  Shortcodes class.
 */
class Bnd_Flex_Order_Delivery_Shortcodes
{

    /**
     * Init Shortcodes.
     */
    public static function init()
    {
        //add_shortcode(apply_filters("menu_content_tag", "menu_content"), __CLASS__ . '::menu_content');
        $page_list = Bnd_Flex_Order_Delivery_Container::instance()->get_page_list();
        foreach ($page_list as $key=>$values) {
            add_shortcode(apply_filters("short_code_tag_".$values[1],$values[1]), __CLASS__.'::render_shortcode');
        }
    }

    public static function bnd_menu($atts)
    {
        return self::shortcode_wrapper(array(
            'Bnd_Flex_Order_Delivery_Shortcode_Menu',
            'output'
        ), $atts);
    }
    
    public static function render_shortcode($atts, $content=null, $shortcode)
    {
        $redirect = '';
        
        extract(shortcode_atts(array(
            'redirect' => $redirect
        ), $atts, $shortcode));       
        ob_start();
        $shortcode = "flexmenu/".str_replace("bnd_","",$shortcode);
        buynowdepot_get_template_part($shortcode);
        
        return apply_filters( $shortcode, ob_get_clean() );
    }
    
    public static function menu_content($atts, $shortcode, $template)
    {
        $redirect = '';
        
        extract(shortcode_atts(array(
            'redirect' => $redirect
        ), $atts, $shortcode));
        
        ob_start();
        
        buynowdepot_get_template_part( $template);
        
        return apply_filters( $shortcode, ob_get_clean() );
    }

    /**
     * Shortcode Wrapper.
     *
     * @param string[] $function
     *            Callback function.
     * @param array $atts
     *            Attributes. Default to empty array.
     * @param array $wrapper
     *            Customer wrapper data.
     *            
     * @return string
     */
    public static function shortcode_wrapper($function, $atts = array(), $wrapper = array(
            'class'  => '',
            'before' => null,
            'after'  => null,
        ))
    {
        ob_start();

        // @codingStandardsIgnoreStart
        echo empty($wrapper['before']) ? '<div class=" ' . apply_filters('_container_class', esc_attr($wrapper['class'])) . '">' : $wrapper['before'];
        call_user_func($function, $atts);
        echo empty($wrapper['after']) ? '</div>' : $wrapper['after'];
        // @codingStandardsIgnoreEnd

        return ob_get_clean();
    }

    /**
     * Item Cart Shortcode
     *
     * Show the shopping cart.
     *
     * @since 1.0
     * @param array $atts
     *            Shortcode attributes
     * @param string $content
     * @return string
     */
    public static function fooditem_cart($atts = array(), $content = null)
    {
        return bnd_shopping_cart();
    }

    /**
     * Checkout Form Shortcode
     *
     * Show the checkout form.
     *
     * @since 1.0
     * @return string
     */
    public static function fooditem_checkout()
    {
        return bnd_checkout_form();
    }

    /**
     * Receipt Shortcode
     *
     * Shows an order receipt.
     *
     * @since 1.0.0
     * @param array $atts
     *            Shortcode attributes
     * @param string $content
     * @return string
     */
    public static function bnd_receipt($atts = array(), $content = null)
    {
        global $bnd_receipt_args;

        $bnd_receipt_args = shortcode_atts(array(
            'error' => __('Sorry, trouble retrieving payment receipt.', ''),
            'price' => true,
            'discount' => true,
            'products' => true,
            'date' => true,
            'notes' => true,
            'payment_key' => false,
            'payment_method' => true,
            'payment_id' => true
        ), $atts, 'bnd_receipt');

        $session = buynowdepot_get_purchase_session();
        if (isset($_GET['payment_key'])) {
            $payment_key = urldecode($_GET['payment_key']);
        } else if ($session) {
            $payment_key = $session['purchase_key'];
        } elseif ($bnd_receipt_args['payment_key']) {
            $payment_key = $bnd_receipt_args['payment_key'];
        }

        // No key found
        if (! isset($payment_key)) {
            return '<p class="bnd-alert bnd-alert-error">' . $bnd_receipt_args['error'] . '</p>';
        }

        $payment_id = buynowdepot_get_purchase_id_by_key($payment_key);
        $user_can_view = bnd_can_view_receipt($payment_key);

        // Key was provided, but user is logged out. Offer them the ability to login and view the receipt
        if (! $user_can_view && ! empty($payment_key) && ! is_user_logged_in() && ! bnd_is_guest_payment($payment_id)) {
            global $bnd_login_redirect;
            $bnd_login_redirect = buynowdepot_get_current_page_url();

            ob_start();

            echo '<p class="bnd-alert bnd-alert-warn">' . __('You must be logged in to view this payment receipt.', '') . '</p>';
            buynowdepot_get_template_part('shortcode', 'login');

            $login_form = ob_get_clean();

            return $login_form;
        }

        $user_can_view = apply_filters('bnd_user_can_view_receipt', $user_can_view, $bnd_receipt_args);

        // If this was a guest checkout and the purchase session is empty, output a relevant error message
        if (empty($session) && ! is_user_logged_in() && ! $user_can_view) {
            return '<p class="bnd-alert bnd-alert-error">' . apply_filters('bnd_receipt_guest_error_message', __('Receipt could not be retrieved, your purchase session has expired.', '')) . '</p>';
        }

        /*
         * Check if the user has permission to view the receipt
         *
         * If user is logged in, user ID is compared to user ID of ID stored in payment meta
         *
         * Or if user is logged out and purchase was made as a guest, the purchase session is checked for
         *
         * Or if user is logged in and the user can view sensitive shop data
         *
         */
        if (! $user_can_view) {
            return '<p class="bnd-alert bnd-alert-error">' . $bnd_receipt_args['error'] . '</p>';
        }

        ob_start();

        buynowdepot_get_template_part('shortcode', 'receipt');

        $display = ob_get_clean();

        return $display;
    }

    /**
     * Login Shortcode
     *
     * Shows a login form allowing users to users to log in. This function simply
     * calls the bnd_login_form function to display the login form.
     *
     * @since 1.0
     * @param array $atts
     *            Shortcode attributes
     * @param string $content
     * @uses bnd_login_form()
     * @return string
     */
    public static function bnd_login($atts, $content = null)
    {
        $redirect = '';

        extract(shortcode_atts(array(
            'redirect' => $redirect
        ), $atts, 'bnd_login'));

        if (empty($redirect)) {
            $login_redirect_page = buynowdepot_get_option('login_redirect_page', '');

            if (! empty($login_redirect_page)) {
                $redirect = get_permalink($login_redirect_page);
            }
        }

        if (empty($redirect)) {
            $order_history = buynowdepot_get_option('bnd-menuitems', 0);
            error_log("Login is being called start :" . $order_history);
            if (! empty($order_history)) {
                $redirect = get_permalink($order_history);
            }
        }

        if (empty($redirect)) {
            $redirect = home_url();
        }

        error_log("Login is being called");
        return bnd_login_form($redirect);
    }

    /**
     * Register Shortcode
     *
     * Shows a registration form allowing users to register for the site
     *
     * @since 1.0.0
     * @param array $atts
     *            Shortcode attributes
     * @param string $content
     * @uses bnd_register_form()
     * @return string
     */
    public static function bnd_signup($atts, $content = null)
    {
        $redirect = home_url();
        $order_history = buynowdepot_get_option('bnd-menuitems', 0);

        if (! empty($order_history)) {
            $redirect = get_permalink($order_history);
        }

        extract(shortcode_atts(array(
            'redirect' => $redirect
        ), $atts, 'bnd_signup'));
        return bnd_register_form($redirect);
    }

    /**
     * Profile Editor Shortcode
     *
     * @since 1.0.0
     *       
     * @author 
     *        
     * @param $atts 
     *         
     * @param null $content
     * @return string Output generated from the profile editor
     */
    public static function bnd_profile($atts, $content = null)
    {
        ob_start();

        if (! bnd_user_pending_verification()) {
            buynowdepot_get_template_part("flexmenu/profile");
        } else {
            buynowdepot_get_template_part("flexmenu/verification");
        }
        $display = ob_get_clean();

        return $display;
    }
}