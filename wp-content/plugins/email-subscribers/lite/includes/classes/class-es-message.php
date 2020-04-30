<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Message' ) ) {
	/**
	 * Class ES_Message
	 *
	 * @since 4.3.2
	 */
	class ES_Message {
		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $to = '';

		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $to_name = '';

		/**
		 * @var array
		 *
		 * @since 4.3.2
		 */
		var $headers = array();

		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $error = '';

		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $subject = '';

		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $body = '';

		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $body_text = '';

		/**
		 * @var
		 *
		 * @sinc 4.3.2
		 */
		var $from;

		/**
		 * @var string
		 *
		 * @since 4.3.2
		 */
		var $from_name = '';


		public function __construct() {

		}

	}
}


