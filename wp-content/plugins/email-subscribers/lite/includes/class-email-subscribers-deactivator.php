<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      4.0
 *
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      4.0
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/includes
 * @author     Your Name <email@example.com>
 */
class Email_Subscribers_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 4.0
	 * @since 4.3.2 Clear all schedule crons
	 */
	public static function deactivate() {
		/**
		 * Cleanup all plugin related stuff in plugin deactivation
		 *
		 * @since 4.3.2
		 */
		do_action('ig_es_plugin_deactivate');
	}

}
