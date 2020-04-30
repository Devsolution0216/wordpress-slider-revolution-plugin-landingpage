<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Compatibility' ) ) {
	/**
	 * Make ES Compatible with other plugins
	 *
	 * Class ES_Compatibility
	 *
	 * @since 4.3.9
	 */
	class ES_Compatibility {
		/**
		 * ES_Compatibility constructor.
		 *
		 * @since 4.3.9
		 */
		public function __construct() {
			add_filter( 'wp_mail_smtp_providers_mailer_get_body', array( $this, 'wp_mail_smtp_modify_header' ), 10, 2 );
		}

		/**
		 * Outlook require X-Return-Path instead of Return-Path as a header
		 * So,we can handle it using 'wp_mail_smtp_providers_mailer_get_body'
		 * filter of WP Mail SMTP plugin.
		 *
		 * @param $body
		 * @param $mailer
		 *
		 * @return mixed
		 *
		 * @since 4.3.9
		 */
		public function wp_mail_smtp_modify_header( $body, $mailer ) {

			if ( 'outlook' === $mailer ) {
				$headers = $body['message']['internetMessageHeaders'];
				foreach ( $headers as $key => $header ) {
					if ( 'Return-Path' === $header['name'] ) {
						$body['message']['internetMessageHeaders'][ $key ]['name'] = 'X-Return-Path';
					}
				}
			}

			return $body;
		}
	}
}