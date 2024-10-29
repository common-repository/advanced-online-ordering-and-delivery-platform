<?php
/**
 * Login / Register Functions
 * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
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
 * Login Form
 *
 * @since 1.0
 * @global $post
 * @param string $redirect Redirect page URL
 * @return string Login form
*/
function bnd_login_form( $redirect = '' ) {
	global $bnd_login_redirect;

	if ( empty( $redirect ) ) {
		$redirect = buynowdepot_get_current_page_url();
	}
	$bnd_login_redirect = $redirect;
	ob_start();
	buynowdepot_get_template_part( 'flexmenu/login' );
	return apply_filters( 'bnd_login_form', ob_get_clean() );
}

/**
 * Registration Form
 *
 * @since  1.0.0
 * @global $post
 * @param string $redirect Redirect page URL
 * @return string Register form
*/
function bnd_register_form( $redirect = '' ) {
    error_log("Coming till here");
	global $bnd_register_redirect;

	if ( empty( $redirect ) ) {
		$redirect = buynowdepot_get_current_page_url();
	}

	$bnd_register_redirect = $redirect;

	ob_start();

	buynowdepot_get_template_part( "flexmenu/signup" );

	return apply_filters( 'bnd_register_form', ob_get_clean() );
}

/**
 * Process Login Form
 *
 * @since 1.0
 * @param array $data Data sent from the login form
 * @return void
*/
function bnd_process_login_form( $data ) {
    error_log("This action is being called:");
	if ( wp_verify_nonce( $data['bnd_login_nonce'], 'bnd-login-nonce' ) ) {
		$user_data = get_user_by( 'login', $data['bnd_user_login'] );
		if ( ! $user_data ) {
			$user_data = get_user_by( 'email', $data['bnd_user_login'] );
		}
		if ( $user_data ) {
			$user_ID = $user_data->ID;
			$user_email = $user_data->user_email;

			if ( wp_check_password( $data['bnd_user_pass'], $user_data->user_pass, $user_data->ID ) ) {

				if ( isset( $data['rememberme'] ) ) {
					$data['rememberme'] = true;
				} else {
					$data['rememberme'] = false;
				}

				bnd_log_user_in( $user_data->ID, $data['bnd_user_login'], $data['bnd_user_pass'], $data['rememberme'] );
			} else {
				buynowdepot_set_error( 'password_incorrect', __( 'The password you entered is incorrect', 'bnd-flex-order-delivery' ) );
			}
		} else {
			buynowdepot_set_error( 'username_incorrect', __( 'The username you entered does not exist', 'bnd-flex-order-delivery' ) );
		}
		// Check for errors and redirect if none present
		$errors = buynowdepot_get_errors();
		if ( ! $errors ) {
			$redirect = apply_filters( 'bnd_login_redirect', $data['bnd_redirect'], $user_ID );
			wp_redirect( $redirect );
			exit;
		}
	}
}
add_action( 'bnd_user_login', 'bnd_process_login_form' );

/**
 * Log User In
 *
 * @since 1.0
 * @param int $user_id User ID
 * @param string $user_login Username
 * @param string $user_pass Password
 * @param boolean $remember Remember me
 * @return void
*/
function bnd_log_user_in( $user_id, $user_login, $user_pass, $remember = false ) {
	if ( $user_id < 1 )
		return;

	wp_set_auth_cookie( $user_id, $remember );
	wp_set_current_user( $user_id, $user_login );
	do_action( 'wp_login', $user_login, get_userdata( $user_id ) );
	//do_action( 'bnd_log_user_in', $user_id, $user_login, $user_pass );
}


/**
 * Process Register Form
 *
 * @since  1.0.0
 * @param array $data Data sent from the register form
 * @return void
*/
function bnd_process_register_form( $data ) {

	if( is_user_logged_in() ) {
		return;
	}

	if( empty( $_POST['bnd_register_submit'] ) ) {
		return;
	}

	do_action( 'bnd_pre_process_register_form' );

	if( email_exists( $data['bnd_user_email'] ) ) {
		buynowdepot_set_error( 'email_unavailable', __( 'Email address already taken', 'bnd-flex-order-delivery' ) );
	}

	if( empty( $data['bnd_user_email'] ) || ! is_email( $data['bnd_user_email'] ) ) {
		buynowdepot_set_error( 'email_invalid', __( 'Invalid email', 'bnd-flex-order-delivery' ) );
	}

	if( ! empty( $data['bnd_payment_email'] ) && $data['bnd_payment_email'] != $data['bnd_user_email'] && ! is_email( $data['bnd_payment_email'] ) ) {
		buynowdepot_set_error( 'payment_email_invalid', __( 'Invalid payment email', 'bnd-flex-order-delivery' ) );
	}

	if( empty( $_POST['bnd_user_pass'] ) ) {
		buynowdepot_set_error( 'empty_password', __( 'Please enter a password', 'bnd-flex-order-delivery' ) );
	}

	if( ( ! empty( $_POST['bnd_user_pass'] ) && empty( $_POST['bnd_user_pass2'] ) ) || ( $_POST['bnd_user_pass'] !== $_POST['bnd_user_pass2'] ) ) {
		buynowdepot_set_error( 'password_mismatch', __( 'Passwords do not match', 'bnd-flex-order-delivery' ) );
	}
	// Check for errors and redirect if none present
	$errors = buynowdepot_get_errors();

	if ( empty( $errors ) ) {

		$redirect = apply_filters( 'bnd_register_redirect', $data['bnd_redirect'] );

		bnd_register_and_login_new_user( array(
			'user_login'      => $data['bnd_user_email'],
			'user_pass'       => $data['bnd_user_pass'],
			'user_email'      => $data['bnd_user_email'],
			'user_registered' => date( 'Y-m-d H:i:s' ),
			'role'            => get_option( 'default_role' )
		) );

		wp_redirect( $redirect );
		exit;
	}
}
add_action( 'bnd_user_register', 'bnd_process_register_form' );

/**
 * Register And Login New User
 *
 * @param array   $user_data
 *
 */
function bnd_register_and_login_new_user( $user_data = array() ) {
    // Verify the array
    if ( empty( $user_data ) )
        return -1;
        
        if ( buynowdepot_get_errors() )
            return -1;
            
            $user_args = apply_filters( 'bnd_insert_user_args', array(
                'user_login'      => isset( $user_data['user_login'] ) ? $user_data['user_login'] : '',
                'user_pass'       => isset( $user_data['user_pass'] )  ? $user_data['user_pass']  : '',
                'user_email'      => isset( $user_data['user_email'] ) ? $user_data['user_email'] : '',
                'first_name'      => isset( $user_data['user_first'] ) ? $user_data['user_first'] : '',
                'last_name'       => isset( $user_data['user_last'] )  ? $user_data['user_last']  : '',
                'user_registered' => date( 'Y-m-d H:i:s' ),
                'role'            => 'unverified_user',
                'show_admin_bar'            => false,
            ), $user_data );
            
            // Insert new user
            $user_id = wp_insert_user( $user_args );
            
            // Validate inserted user
            if ( is_wp_error( $user_id ) ) {
                return -1;
            }
            else { 
                //register user profile in the customer database
                $customer = array("email"=>$user_data['user_email'], "status"=>1);
                $model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
                $model->addCustomer($customer);
            }
            // Allow themes and plugins to filter the user data
            $user_data = apply_filters( 'bnd_insert_user_data', $user_data, $user_args );
            
            // Allow themes and plugins to hook
            do_action( 'bnd_insert_user', $user_id, $user_data );
            
            // Login new user
            bnd_log_user_in( $user_id, $user_data['user_login'], $user_data['user_pass'] );
            
            // Return user id
            return $user_id;
}

/**
 * Output a message on the login form when a user is already logged in.
 *
 * This remains mainly for backwards compatibility.
 *
 * @since 1.0.0
 */
function bnd_login_form_logged_in() {
    echo '<p class="bnd-logged-in">' . esc_html__( 'You are already logged in', 'bnd-flex-order-delivery' ) . '</p>';
}
add_action( 'bnd_login_form_logged_in', 'bnd_login_form_logged_in' );
