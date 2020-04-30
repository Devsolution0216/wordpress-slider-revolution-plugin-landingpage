<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ES_DB_Actions extends ES_DB {
	/**
	 * @since 4.2.1
	 * @var $table_name
	 *
	 */
	public $table_name;
	/**
	 * @since 4.2.1
	 * @var $version
	 *
	 */
	public $version;
	/**
	 * @since 4.2.1
	 * @var $primary_key
	 *
	 */
	public $primary_key;

	/**
	 * ES_DB_Lists constructor.
	 *
	 * @since 4.2.1
	 */
	public function __construct() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'ig_actions';

		$this->version = '1.0';

	}

	/**
	 * Get table columns
	 *
	 * @return array
	 *
	 * @since 4.2.1
	 */
	public function get_columns() {
		return array(
			'contact_id'  => '%d',
			'message_id'  => '%d',
			'campaign_id' => '%d',
			'type'        => '%d',
			'count'       => '%d',
			'link_id'     => '%d',
			'list_id'     => '%d',
			'created_at'  => '%d',
			'updated_at'  => '%d'
		);
	}

	/**
	 * Get default column values
	 *
	 * @since  4.2.1
	 */
	public function get_column_defaults() {
		return array(
			'contact_id'  => null,
			'message_id'  => null,
			'campaign_id' => null,
			'type'        => 0,
			'count'       => 0,
			'link_id'     => 0,
			'list_id'     => 0,
			'created_at'  => ig_es_get_current_gmt_timestamp(),
			'updated_at'  => ig_es_get_current_gmt_timestamp()
		);
	}

	/**
	 * Track action
	 *
	 * @param $args
	 * @param bool $explicit
	 *
	 * @return bool
	 *
	 * @since 4.2.4
	 */
	public function add( $args, $explicit = true ) {

		global $wpdb;

		$ig_actions_table = IG_ACTIONS_TABLE;

		$args_keys     = array_keys( $args );
		$args_keys_str = implode( ", ", $args_keys );

		$sql = "INSERT INTO $ig_actions_table ($args_keys_str)";

		$args_values = array_values( $args );

		$args_values_str = $this->prepare_for_in_query( $args_values );

		$sql .= " VALUES ($args_values_str) ON DUPLICATE KEY UPDATE";

		$sql .= ( $explicit ) ? $wpdb->prepare( " created_at = created_at, count = count+1, updated_at = %d", ig_es_get_current_gmt_timestamp() ) : ' count = values(count)';

		$result = $wpdb->query( $sql );

		if ( false !== $result ) {
			return true;
		}

		return false;
	}

	/**
	 * Get total contacts who have clicked links in last $days
	 *
	 * @param int $days
	 *
	 * @return string|null
	 *
	 * @since 4.3.2
	 */
	public function get_total_contacts_clicks_links( $days = 0, $distinct = true ) {
		global $wpdb;

		$ig_actions_table = IG_ACTIONS_TABLE;

		if ( $distinct ) {
			$query = "SELECT COUNT(DISTINCT(`contact_id`)) FROM $ig_actions_table WHERE `type` = %d";
		} else {
			$query = "SELECT COUNT(`contact_id`) FROM $ig_actions_table WHERE `type` = %d";
		}

		$args[] = IG_LINK_CLICK;

		if ( 0 != $days ) {
			$days   = esc_sql( $days );
			$where  = " AND created_at >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL %d DAY))";
			$query  .= $where;
			$args[] = $days;
		}

		return $wpdb->get_var( $wpdb->prepare( $query, $args ) );
	}

	/**
	 * Get total contacts who have unsubscribed in last $days
	 *
	 * @param int $days
	 *
	 * @return string|null
	 *
	 * 
	 */
	public function get_total_contact_lost( $days = 0, $distinct = true ) {
		global $wpdb;

		$ig_actions_table = IG_ACTIONS_TABLE;

		if ( $distinct ) {
			$query = "SELECT COUNT(DISTINCT(`contact_id`)) FROM $ig_actions_table WHERE `type` = %d";
		} else {
			$query = "SELECT COUNT(`contact_id`) FROM $ig_actions_table WHERE `type` = %d";
		}

		$args[] = IG_CONTACT_UNSUBSCRIBE;

		if ( 0 != $days ) {
			$days   = esc_sql( $days );
			$where  = " AND created_at >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL %d DAY))";
			$query  .= $where;
			$args[] = $days;
		}

		return $wpdb->get_var( $wpdb->prepare( $query, $args ) );
	}



	/**
	 * Get total contacts who have opened message in last $days
	 *
	 * @param int $days
	 *
	 * @return string|null
	 *
	 * @since 4.4.0
	 */
	public function get_total_contacts_opened_message( $days = 0, $distinct = true ) {
		global $wpdb;

		$ig_actions_table = IG_ACTIONS_TABLE;

		if ( $distinct ) {
			$query = "SELECT COUNT(DISTINCT(`contact_id`)) FROM $ig_actions_table WHERE `type` = %d";
		} else {
			$query = "SELECT COUNT(`contact_id`) FROM $ig_actions_table WHERE `type` = %d";
		}

		$args[] = IG_MESSAGE_OPEN;

		if ( 0 != $days ) {
			$days   = esc_sql( $days );
			$where  = " AND created_at >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL %d DAY))";
			$query  .= $where;
			$args[] = $days;
		}

		return $wpdb->get_var( $wpdb->prepare( $query, $args ) );
	}

	/**
	 * Get total emails sent in last $days
	 *
	 * @param int $days
	 *
	 * @return string|null
	 *
	 * @since 4.4.0
	 */
	public function get_total_emails_sent( $days = 0, $distinct = true ) {
		global $wpdb;

		$ig_actions_table = IG_ACTIONS_TABLE;

		if ( $distinct ) {
			$query = "SELECT COUNT(DISTINCT(`contact_id`)) FROM $ig_actions_table WHERE `type` = %d";
		} else {
			$query = "SELECT COUNT(`contact_id`) FROM $ig_actions_table WHERE `type` = %d";
		}

		$args[] = IG_MESSAGE_SENT;

		if ( 0 != $days ) {
			$days   = esc_sql( $days );
			$where  = " AND created_at >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL %d DAY))";
			$query  .= $where;
			$args[] = $days;
		}

		return $wpdb->get_var( $wpdb->prepare( $query, $args ) );
	}
}
