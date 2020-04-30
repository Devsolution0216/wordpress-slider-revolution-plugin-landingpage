<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Admin Settings
 *
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/admin
 * @author     Your Name <email@example.com>
 */
class ES_Tools {
	// class instance
	static $instance;

	public function __construct() {
		// Allow only to send test email to user who have Settings & Campaigns permission
		$accessible_sub_menus = ES_Common::ig_es_get_accessible_sub_menus();
		if ( defined( 'DOING_AJAX' ) && ( in_array( 'settings', $accessible_sub_menus ) || in_array( 'campaigns', $accessible_sub_menus ) ) ) {
			add_action( 'wp_ajax_es_send_test_email', array( $this, 'send_test_email' ) );
		}
	}

	/**
	 * Send Test Email
	 *
	 * @since 4.0.0
	 * @since 4.3.2 Call ES()->mailer->send_test_email() method to send test email
	 */
	public function send_test_email() {

		$response = array();
		
		$email = sanitize_email( ig_es_get_request_data( 'es_test_email' ) );

		$subject = ig_es_get_post_data( 'subject', '' );
		$content = wp_kses_post(ig_es_get_request_data( 'content', '', false ));

		if ( ! empty( $email ) ) {

			if ( ! empty( $content ) ) {
				$content = str_replace( "{{EMAIL}}", "User Email", $content );
				$content = str_replace( "{{NAME}}", "Username", $content );
			}

			$response = ES()->mailer->send_test_email( $email, $subject, $content );

			if ( $response && $response['status'] === 'SUCCESS' ) {
				$response['message'] = __( 'Email has been sent. Please check your inbox', 'email-subscribers' );
			}

		}

		echo json_encode( $response );
		exit;
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

?>