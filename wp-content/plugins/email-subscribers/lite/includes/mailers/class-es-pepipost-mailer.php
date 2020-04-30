<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'ES_Pepipost_Mailer' ) ) {
	/**
	 * Class ES_Pepipost_Mailer
	 *
	 * @since 4.2.1
	 * @since 4.3.2 Modified response
	 */
	class ES_Pepipost_Mailer extends ES_Base_Mailer {
		/**
		 * @since 4.3.2
		 * @var string
		 *
		 */
		var $api_url = 'https://api.pepipost.com/v2/sendEmail';
		/**
		 * @since 4.3.2
		 * @var string
		 *
		 */
		var $api_key = '';

		/**
		 * ES_Pepipost_Mailer constructor.
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
		 * @since 4.2.1
		 * @since 4.3.2 Modified Response
		 */
		function send( ES_Message $message ) {

			ES()->logger->info( 'Start Sending Email Using Pepipost', $this->logger_context );

			$ig_es_mailer_settings = get_option( 'ig_es_mailer_settings', array() );

			$this->api_key = ! empty( $ig_es_mailer_settings['pepipost']['api_key'] ) ? $ig_es_mailer_settings['pepipost']['api_key'] : '';

			if ( empty( $this->api_key ) ) {
				return $this->do_response( 'error', 'API Key is empty' );
			}

			$params = array();

			$params['personalizations'][]['recipient'] = $message->to;
			$params['from']['fromEmail']               = $message->from;
			$params['from']['fromName']                = $message->from_name;
			$params['subject']                         = $message->subject;
			$params['content']                         = $message->body;

			$headers = array(
				'user-agent'   => 'APIMATIC 2.0',
				'Accept'       => 'application/json',
				'content-type' => 'application/json; charset=utf-8',
				'api_key'      => $this->api_key
			);

			$headers = ! empty( $message->headers ) ? array_merge( $headers, $message->headers ) : $headers;
			$method  = 'POST';
			$qs      = json_encode( $params );

			$options = array(
				'timeout' => 15,
				'method'  => $method,
				'headers' => $headers
			);

			if ( $method == 'POST' ) {
				$options['body'] = $qs;
			}

			$response = wp_remote_request( $this->api_url, $options );
			if ( ! is_wp_error( $response ) ) {
				$body = ! empty( $response['body'] ) ? json_decode( $response['body'], true ) : '';

				if ( ! empty( $body ) ) {
					if ( 'Success' === $body['message'] ) {
						return $this->do_response( 'success' );
					} elseif ( ! empty( $body['error_info'] ) ) {
						return $this->do_response( 'error', $body['error_info']['error_message'] );
					}
				} else {
					$this->do_response( 'error', wp_remote_retrieve_response_message( $response ) );
				}
			}

			ES()->logger->info( 'Email Sent Successfully Using Pepipost', $this->logger_context );

			return $this->do_response( 'success' );
		}

	}

}
