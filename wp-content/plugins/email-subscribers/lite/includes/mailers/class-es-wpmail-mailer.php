<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'ES_Wpmail_Mailer' ) ) {

	class ES_Wpmail_Mailer extends ES_Base_Mailer {
		/**
		 * ES_Wpmail_Mailer constructor.
		 *
		 * @since 4.3.2
		 */
		function __construct() {
			parent::__construct();
		}

		/**
		 * @param ES_Message $message
		 *
		 * @return boolean|WP_Error
		 *
		 * @since 4.3.2
		 */
		function send( ES_Message $message ) {

			ES()->logger->info( 'Start Sending Email Using WP Mail', $this->logger_context );

			$send_mail = wp_mail( $message->to, $message->subject, $message->body, $message->headers );

			if ( ! $send_mail ) {
				global $phpmailer;

				$message = wp_strip_all_tags( $phpmailer->ErrorInfo );

				return $this->do_response( 'error', $message );
			}

			ES()->logger->info( 'Email Sent Successfully Using WP Mail', $this->logger_context );

			return $this->do_response( 'success' );
		}

	}

}
