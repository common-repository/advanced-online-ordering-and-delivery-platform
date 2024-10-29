<?php
/**
 * This class take care of sending emails during various activitied of this plugin
 *
 * * 
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 * 
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes/client
 * @author BuyNowDepot <admin@buynowdepot.com>
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Bnd_Flex_Order_Delivery_Email_Client {

	/**
	 * Holds the from address
	 *
	 * @since  1.0.0
	 */
	private $from_address;

	/**
	 * Holds the from name
	 *
	 * @since  1.0.0
	 */
	private $from_name;

	/**
	 * Holds the email content type
	 *
	 * @since  1.0.0
	 */
	private $content_type = "text/html";

	/**
	 * Holds the email headers
	 *
	 * @since  1.0.0
	 */
	private $headers;

	/**
	 * Whether to send email in HTML
	 *
	 * @since  1.0.0
	 */
	private $html = true;

	/**
	 * The email template to use
	 *
	 * @since  1.0.0
	 */
	private $template;

	/**
	 * The header text for the email
	 *
	 * @since  2.1
	 */
	private $heading = '';

	/**
	 * Get things going
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		if ( 'none' === $this->get_template() ) {
			$this->html = false;
		}
		$this->from_name = get_bloginfo( 'name' );
		$this->from_email =  get_option( 'admin_email' );
	}

	/**
	 * Set a property
	 *
	 * @since  1.0.0
	 */
	public function __set( $key, $value ) {
		$this->$key = $value;
	}

	/**
	 * Get a property
	 *
	 * @since 1.0.0.9
	 */
	public function __get( $key ) {
		return $this->$key;
	}

	/**
	 * Get the email content type
	 *
	 * @since  1.0.0
	 */
	public function get_content_type() {
		if ( ! $this->content_type && $this->html ) {
			$this->content_type = apply_filters( 'bnd_email_default_content_type', 'text/html', $this );
		} else if ( ! $this->html ) {
			$this->content_type = 'text/plain';
		}

		return apply_filters( 'bnd_email_content_type', $this->content_type, $this );
	}

	/**
	 * Get the email headers
	 *
	 * @since  1.0.0
	 */
	public function get_headers() {
		if ( ! $this->headers ) {
			$this->headers  = "From: {$this->from_name} <{$this->from_address}>\r\n";
			$this->headers .= "Reply-To: {$this->from_address}\r\n";
			$this->headers .= "Content-Type: {$this->get_content_type()}; charset=utf-8\r\n";
		}

		return apply_filters( 'rpress_email_headers', $this->headers, $this );
	}

	/**
	 * Retrieve email templates
	 *
	 * @since  1.0.0
	 */
	public function get_templates() {
		$templates = array(
			'default' => __( 'Default Template', 'BuyNowDepot' ),
			'none'    => __( 'No template, plain text only', 'BuyNowDepot' )
		);
	}

	/**
	 * Get the enabled email template
	 *
	 * @since  1.0.0
	 *
	 * @return string|null
	 */
	public function get_template() {
		if ( ! $this->template ) {
			$this->template = buynowdepot_get_option( 'email_template', 'default' );
		}
	}

	/**
	 * Get the header text for the email
	 *
	 * @since  1.0.0
	 */
	public function get_heading() {
		return $this->heading;
	}

	/**
	 * Parse email template tags
	 *
	 * @since  1.0.0
	 * @param string $content
	 */
	public function parse_tags( $content ) {
		return $content;
	}

	/**
	 * Build the final email
	 *
	 * @since  1.0.0
	 * @param string $message
	 *
	 * @return string
	 */
	public function build_email( $message ) {

		if ( false === $this->html ) {
			 wp_strip_all_tags( $message );
		}
		$message = $this->text_to_html( $message );
		ob_start();
		buynowdepot_get_template_part( 'email/layout', $this->get_template(), true );
    	$body    = ob_get_clean();
		$message = str_replace( '{email}', $message, $body );
	}

	/**
	 * Send the email
	 * @param  string  $to               The To address to send to.
	 * @param  string  $subject          The subject line of the email to send.
	 * @param  string  $message          The body of the email to send.
	 * @param  string|array $attachments Attachments to the email in a format supported by wp_mail()
	 * @since  1.0.0
	 */
	public function send( $to, $subject, $message, $attachments = '' ) {

		$subject = $this->parse_tags( $subject );
		$message = $this->parse_tags( $message );

		$message = $this->build_email( $message );

		$sent       = wp_mail( $to, $subject, $message, $this->get_headers(), $attachments );
		if(! $sent ) {
			if ( is_array( $to ) ) {
				$to = implode( ',', $to );
			}

			$log_message = sprintf(
				__( "Email from BuyNowDepot failed to send.\nSend time: %s\nTo: %s\nSubject: %s\n\n", 'BuyNowDepot' ),
				date_i18n( 'F j Y H:i:s', current_time( 'timestamp' ) ),
				$to,
				$subject
			);
			error_log( $log_message );
		}
		return $sent;

	}

	/**
	 * Converts text to formatted HTML. This is primarily for turning line breaks into <p> and <br/> tags.
	 * @since  1.0.0
	 */
	public function text_to_html( $message ) {

		if ( 'text/html' == $this->content_type || true === $this->html ) {
			$message = wpautop( $message );
			$message = make_clickable( $message );
			$message = str_replace( '&#038;', '&amp;', $message );
		}
		return $message;
	}

}
