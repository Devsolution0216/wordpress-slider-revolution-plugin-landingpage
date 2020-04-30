<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'ES_Phpmail_Mailer' ) ) {
	/**
	 * Class ES_Phpmail_Mailer
	 *
	 * @since 4.3.2
	 */
	class ES_Phpmail_Mailer extends ES_Base_Mailer {
		/**
		 * ES_Phpmail_Mailer constructor.
		 *
		 * @since 4.3.2
		 */
		function __construct() {
			parent::__construct();
		}

		/**
		 * Send Email
		 *
		 * @param ES_Message $message
		 *
		 * @return bool|WP_Error
		 *
		 * @since 4.3.2
		 */
		function send( ES_Message $message ) {

			ES()->logger->info( 'Start Sending Email Using PHP Mail', $this->logger_context );

			$message->headers[] = "MIME-Version: 1.0";
			$message->headers[] = "X-Mailer: PHP" . phpversion();
            
            $headers = implode("\n", $message->headers);
			$send = mail( $message->to, $message->subject, $message->body, $headers );

			if ( ! $send ) {
				return $this->do_response( 'error', 'Error in Email Sending' );
			}

			ES()->logger->info( 'Email Sent Successfully Using PHP Mail', $this->logger_context );

			return $this->do_response( 'success' );
		}

	}

}
