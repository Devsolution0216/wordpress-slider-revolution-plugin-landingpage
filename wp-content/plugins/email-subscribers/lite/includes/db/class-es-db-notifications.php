<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ES_DB_Notifications {

	public $table_name;

	public $version;

	public $primary_key;

	public function __construct() {

	}

	/**
	 * Migrate Post Notification Email Template Type
	 *
	 * @return bool|int
	 *
	 * @since 4.0.0
	 */
	public static function migrate_post_notification_es_template_type() {
		global $wpdb;

		$sql    = "UPDATE {$wpdb->prefix}postmeta SET meta_value = %s WHERE meta_key = %s AND meta_value = %s";
		$query  = $wpdb->prepare( $sql, array( 'post_notification', 'es_template_type', 'Post Notification' ) );
		$update = $wpdb->query( $query );

		return $update;
	}

	/**
	 * Migrate Newsletter Email template type
	 *
	 * @return bool|int
	 *
	 * @since 4.0.0
	 */
	public static function migrate_newsletter_es_template_type() {
		global $wpdb;

		$sql    = "UPDATE {$wpdb->prefix}postmeta SET meta_value = %s WHERE meta_key = %s AND meta_value = %s";
		$query  = $wpdb->prepare( $sql, array( 'newsletter', 'es_template_type', 'Newsletter' ) );
		$update = $wpdb->query( $query );

		return $update;
	}

}
