<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ES_DB_Lists_Contacts extends ES_DB {
	/**
	 * @since 4.3.5
	 *
	 * @var $table_name
	 */
	public $table_name;

	/**
	 * @since 4.3.5
	 *
	 * @var $version
	 */
	public $version;

	/**
	 * @since 4.3.5
	 *
	 * @var $primary_key
	 */
	public $primary_key;

	/**
	 * ES_DB_Lists_Contacts constructor.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		global $wpdb;

		parent::__construct();

		$this->table_name = $wpdb->prefix . 'ig_lists_contacts';

		$this->primary_key = 'id';

		$this->version = '1.0';
	}

	/**
	 * Get columns
	 *
	 * @return array
	 *
	 * @since 4.3.5
	 */
	public function get_columns() {
		return array(
			'id'              => '%d',
			'list_id'         => '%d',
			'contact_id'      => '%d',
			'status'          => '%s',
			'optin_type'      => '%d',
			'subscribed_at'   => '%s',
			'subscribed_ip'   => '%s',
			'unsubscribed_at' => '%s',
			'unsubscribed_ip' => '%s',
		);
	}

	/**
	 * Get default column values
	 *
	 * @since 4.3.5
	 */
	public function get_column_defaults() {
		return array(
			'list_id'         => 0,
			'contact_id'      => 0,
			'status'          => 0,
			'optin_type'      => 1,
			'subscribed_at'   => null,
			'subscribed_ip'   => null,
			'unsubscribed_at' => null,
			'unsubscribed_ip' => null,
		);
	}

	/**
	 * Add single contacts to multiple lists
	 *
	 * @param array $contact_data
	 * @param array $list_ids
	 *
	 * @return bool
	 *
	 * @since 4.3.5
	 */
	public function add_contact_to_lists( $contact_data = array(), $list_ids = array() ) {

		if ( ! is_array( $list_ids ) ) {
			$list_ids = array( $list_ids );
		}

		if ( is_array( $list_ids ) && count( $list_ids ) > 0 ) {

			// Remove entry if it's already there in a list
			$contact_id = ! empty( $contact_data['contact_id'] ) ? $contact_data['contact_id'] : 0;
			$this->remove_contacts_from_lists( $contact_id, $list_ids );

			foreach ( $list_ids as $list_id ) {
				$contact_data['list_id'] = $list_id;
				if ( ! $this->insert( $contact_data, 'list_contact' ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Prepare contact_data
	 *
	 * @param array $contact_ids
	 * @param $list_id
	 *
	 * @return array|bool
	 *
	 * @since 4.3.5
	 */
	public function prepare_contact_data( $contact_ids = array(), $list_id ) {

		if ( empty( $contact_ids ) || empty( $list_id ) ) {
			return array();
		}

		$list_id = esc_sql( absint( $list_id ) );

		$contact_data = array();
		if ( 0 != $list_id ) {

			$optin_type_option = get_option( 'ig_es_optin_type', true );

			$optin_type = 1;
			if ( in_array( $optin_type_option, array( 'double_opt_in', 'double_optin' ) ) ) {
				$optin_type = 2;
			}

			$data = array(
				'list_id'       => $list_id,
				'status'        => 'subscribed',
				'optin_type'    => $optin_type,
				'subscribed_at' => ig_get_current_date_time()
			);

			foreach ( $contact_ids as $contact_id ) {
				$data['contact_id'] = $contact_id;
				$contact_data[]     = $data;
			}

		}

		return $contact_data;
	}

	/**
	 * Add contacts to list
	 *
	 * @param array $contact_ids
	 * @param int $list_id
	 *
	 * @return bool
	 *
	 * @since 4.3.5
	 */
	public function add_contacts_to_list( $contact_ids = array(), $list_id = 0 ) {

		if ( empty( $list_id ) ) {
			return false;
		}

		if ( ! is_array( $contact_ids ) ) {
			$contact_ids = array( absint( $contact_ids ) );
		}

		$list_id = esc_sql( absint( $list_id ) );

		if ( 0 != $list_id ) {
			$this->remove_contacts_from_lists( $contact_ids, $list_id );

			$contact_data = $this->prepare_contact_data( $contact_ids, $list_id );

			return $this->bulk_insert( $contact_data );
		}

		return false;
	}

	/**
	 * Move multiple contacts to specific list
	 *
	 * @param array $contact_ids
	 * @param int $list_id
	 *
	 * @return bool
	 *
	 * @since 4.3.5
	 */
	public function move_contacts_to_list( $contact_ids = array(), $list_id = 0 ) {

		if ( empty( $contact_ids ) ) {
			return false;
		}

		$list_id = esc_sql( absint( $list_id ) );

		if ( is_array( $contact_ids ) && count( $contact_ids ) > 0 ) {

			$this->remove_contacts_from_lists( $contact_ids );

			$contact_data = $this->prepare_contact_data( $contact_ids, $list_id );

			return $this->bulk_insert( $contact_data );
		}

		return false;
	}

	/**
	 * @param $id
	 * @param string $status
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 * @since 4.3.5 Removed Static Method
	 */
	public function get_list_ids_by_contact( $contact_id = 0, $status = '' ) {
		global $wpdb;

		if ( empty( $contact_id ) ) {
			return array();
		}

		$where  = "contact_id = %d";
		$args[] = esc_sql( $contact_id );

		if ( ! empty( $status ) ) {
			$where  .= " AND status = %s";
			$args[] = esc_sql( $status );
		}

		$where = $wpdb->prepare( $where, $args );

		return $this->get_column_by_condition( 'list_id', $where );
	}

	/**
	 * @param int $contact_id
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 * @since 4.3.5 Removed Static method. Call get_columns_map method
	 */
	public function get_list_contact_status_map( $contact_id = 0 ) {

		global $wpdb;

		if ( 0 == $contact_id ) {
			return array();
		}

		$where = "contact_id = %d";
		$where = $wpdb->prepare( $where, $contact_id );

		return $this->get_columns_map( 'list_id', 'status', $where );
	}

	/**
	 * Update lists of contact
	 *
	 * @param int $contact_id
	 * @param array $list_ids
	 *
	 * @return bool
	 *
	 * @since 4.3.5
	 * @since 4.3.6 Modified lists saving
	 */
	public function update_contact_lists( $contact_id = 0, $lists = array() ) {

		if ( empty( $contact_id ) || empty( $lists ) ) {
			return false;
		}

		$contact_id = esc_sql( $contact_id );
		$lists      = esc_sql( $lists );

		if ( ! empty( $lists ) ) {

			$optin_type_option = get_option( 'ig_es_optin_type', true );

			$optin_type = 1;
			if ( in_array( $optin_type_option, array( 'double_opt_in', 'double_optin' ) ) ) {
				$optin_type = 2;
			}

			// Remove from all lists
			$this->remove_contacts_from_lists( $contact_id );

			$data = array();
			$key  = 0;
			foreach ( $lists as $list_id => $status ) {
				if ( ! empty( $status ) ) {
					$data[ $key ]['list_id']       = $list_id;
					$data[ $key ]['contact_id']    = $contact_id;
					$data[ $key ]['status']        = $status;
					$data[ $key ]['optin_type']    = $optin_type;
					$data[ $key ]['subscribed_at'] = ig_get_current_date_time();

					$key ++;
				}
			}

			return ES()->lists_contacts_db->bulk_insert( $data );
		}

		return false;

	}

	/**
	 * Remove Contacts from lists
	 *
	 * @param array $contact_id
	 * @param array $list_ids
	 *
	 * @return bool
	 *
	 * @since 4.3.5
	 */
	public function remove_contacts_from_lists( $contact_ids = array(), $list_ids = array() ) {

		if ( ! is_array( $contact_ids ) ) {
			$contact_ids = array( absint( $contact_ids ) );
		}

		if ( ! is_array( $list_ids ) ) {
			$list_ids = array( absint( $list_ids ) );
		}

		$where = '';
		if ( is_array( $contact_ids ) && count( $contact_ids ) > 0 ) {

			$contact_ids_str = $this->prepare_for_in_query( $contact_ids );

			$where = "contact_id IN ($contact_ids_str)";
		}

		if ( is_array( $list_ids ) && count( $list_ids ) > 0 ) {

			$list_ids_str = $this->prepare_for_in_query( $list_ids );

			if ( ! empty( $where ) ) {
				$where .= " AND ";
			}

			$where .= "list_id IN ($list_ids_str)";
		}

		return $this->delete_by_condition( $where );
	}

	/**
	 * Remove all contacts from specific list
	 *
	 * @param int $list_id
	 * @param array $contact_ids
	 *
	 * @return bool
	 *
	 * @since 4.3.5
	 */
	public function remove_all_contacts_from_list( $list_id = 0 ) {
		return $this->remove_contacts_from_lists( array(), $list_id );
	}

	/**
	 * Import contacts into lists
	 *
	 * @param int $list_id
	 * @param array $contacts
	 *
	 * @return bool
	 *
	 * @since 4.0.0
	 * @since 4.3.5
	 */
	public function import_contacts_into_lists( $list_id = 0, $contacts = array() ) {

		if ( count( $contacts ) > 0 ) {

			$emails = array();
			foreach ( $contacts as $contact ) {
				$emails[] = $contact['email'];
			}

			$contacts_str = $this->prepare_for_in_query( $emails );

			$where = "email IN ($contacts_str)";

			$email_id_map = $this->get_columns_map( 'email', 'id', $where );

			foreach ( $contacts as $key => $contact ) {
				$status     = 'subscribed';
				$optin_type = IG_SINGLE_OPTIN;
				if ( $contact['status'] === 'Single Opt In' ) {
					$optin_type = IG_SINGLE_OPTIN;
				} elseif ( $contact['status'] === 'Confirmed' ) {
					$optin_type = IG_DOUBLE_OPTIN;
				} elseif ( $contact['status'] === 'Unconfirmed' ) {
					$optin_type = IG_DOUBLE_OPTIN;
					$status     = 'Unconfirmed';
				} elseif ( $contact['status'] === 'Unsubscribed' ) {
					$optin_type = IG_DOUBLE_OPTIN;
					$status     = 'unsubscribed';
				}

				$contact[ $key ]['list_id']       = $list_id;
				$contact[ $key ]['contact_id']    = $email_id_map[ $contact['email'] ];
				$contact[ $key ]['status']        = $status;
				$contact[ $key ]['optin_type']    = $optin_type;
				$contact[ $key ]['subscribed_at'] = $contact['subscribed_at'];
			}

			return $this->bulk_insert( $contacts );
		}

		return true;
	}

	/**
	 * Add contacts into lists_contacts table
	 *
	 * @param int $list_id
	 * @param array $contacts
	 * @param string $status
	 * @param int $optin_type
	 * @param null $subscribed_at
	 * @param null $subscribed_ip
	 * @param null $unsubscribed_at
	 * @param null $unsubscribed_ip
	 *
	 * @return bool
	 *
	 * @since 4.0.0
	 * @since 4.3.5 Used bulk_insert method
	 */
	public function do_import_contacts_into_list( $list_id = 0, $contacts = array(), $status = 'subscribed', $optin_type = 1, $subscribed_at = null, $subscribed_ip = null, $unsubscribed_at = null, $unsubscribed_ip = null ) {
		if ( count( $contacts ) > 0 ) {
			$values = array();

			$key = 0;
			foreach ( $contacts as $contact_id ) {

				$values[ $key ]['contact_id']      = $contact_id;
				$values[ $key ]['list_id']         = $list_id;
				$values[ $key ]['status']          = $status;
				$values[ $key ]['optin_type']      = $optin_type;
				$values[ $key ]['subscribed_at']   = $subscribed_at;
				$values[ $key ]['subscribed_ip']   = $subscribed_ip;
				$values[ $key ]['unsubscribed_at'] = $unsubscribed_at;
				$values[ $key ]['unsubscribed_ip'] = $unsubscribed_ip;

				$key ++;
			}

			return $this->bulk_insert( $values );
		}

		return false;
	}

	/**
	 * Get total contacts based on list & status
	 *
	 * @param int $list_id
	 * @param string $status
	 *
	 * @return string|null
	 *
	 * @since 4.0.0
	 * @since 4.3.5 Removed static call
	 */
	public function get_total_count_by_list( $list_id = 0, $status = 'subscribed' ) {
		$list_id = absint( $list_id );

		if ( empty( $list_id ) ) {
			return 0;
		}

		return $this->get_contacts( $status, $list_id, 0, true, true );
	}

	/**
	 * Get total distinct contacts by condition
	 *
	 * @param string $where
	 *
	 * @return string|null
	 *
	 * @since 4.3.5
	 * @since 4.3.6 Added $distinct
	 */
	public function get_total_contacts( $where = '', $distinct = true ) {
		global $wpdb;

		if ( $distinct ) {
			$query = "SELECT count(DISTINCT(contact_id)) FROM $this->table_name";
		} else {
			$query = "SELECT count(contact_id) FROM $this->table_name";
		}

		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}

		return $wpdb->get_var( $query );
	}

	/**
	 * Get List => Status map by contact_ids
	 *
	 * @param array $contact_ids
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 * @since 4.3.5 Used prepare_for_in_query & get_columns_by_condition method
	 */
	public function get_list_status_by_contact_ids( $contact_ids = array() ) {

		if ( empty( $contact_ids ) ) {
			return array();
		}

		if ( ! is_array( $contact_ids ) ) {
			$contact_ids = array( $contact_ids );
		}

		$contact_ids_str = $this->prepare_for_in_query( $contact_ids );

		$where = "contact_id IN ($contact_ids_str)";

		$columns = array( 'contact_id', 'list_id', 'status' );

		$results = $this->get_columns_by_condition( $columns, $where );

		$map = array();
		if ( count( $results ) > 0 ) {

			foreach ( $results as $result ) {
				$map[ $result['contact_id'] ][ $result['list_id'] ] = $result['status'];
			}
		}

		return $map;
	}

	/**
	 * Update list wise subscription status based on contact ids
	 *
	 * @param $ids array
	 * @param $status string
	 *
	 * @return bool|int
	 *
	 * @since 4.1.14
	 * @since 4.3.5 Removed static call
	 */
	public function edit_subscriber_status( $ids = array(), $status = '' ) {
		global $wpdb;

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$ids    = array_map( 'absint', $ids );
		$status = esc_sql( $status );

		$ids = $this->prepare_for_in_query( $ids );

		$current_date = ig_get_current_date_time();

		$query = '';
		if ( 'subscribed' === $status ) {
			$sql   = "UPDATE $this->table_name SET status = %s, subscribed_at = %s WHERE contact_id IN ($ids)";
			$query = $wpdb->prepare( $sql, array( $status, $current_date ) );
		} elseif ( 'unsubscribed' === $status ) {
			$sql   = "UPDATE $this->table_name SET status = %s, unsubscribed_at = %s WHERE contact_id IN ($ids)";
			$query = $wpdb->prepare( $sql, array( $status, $current_date ) );
		} elseif ( 'unconfirmed' === $status ) {
			$sql   = "UPDATE $this->table_name SET status = %s, optin_type = %d, subscribed_at = NULL, unsubscribed_at = NULL WHERE contact_id IN ($ids)";
			$query = $wpdb->prepare( $sql, array( $status, IG_DOUBLE_OPTIN ) );
		}

		if ( $query ) {
			return $wpdb->query( $query );
		}

		return false;

	}

	/**
	 * Check whether status update required
	 *
	 * @param $contact_ids
	 * @param $status
	 *
	 * @return bool
	 *
	 * @since 4.1.14
	 * @since 4.3.5 Removed static call
	 */
	public function is_status_update_required( $ids = array(), $status = '' ) {
		global $wpdb;

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$ids = array_map( 'absint', $ids );

		$ids = $this->prepare_for_in_query( $ids );

		$where = $wpdb->prepare( "contact_id IN ($ids) && status != %s", $status );

		if ( $this->count( $where ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get Total Subscribers count based on timeline
	 *
	 * @param int $days
	 *
	 * @return string|null
	 *
	 * @since 4.3.2
	 * @since 4.3.5 Removed static call
	 * @since 4.3.6 Modified method name from get_total_subscribed_contacts to get_subscribed_contacts_count
	 */
	public function get_subscribed_contacts_count( $days = 0, $distinct = true ) {
		return $this->get_contacts( 'subscribed', 0, $days, true, $distinct );
	}

	/**
	 * Get Total Subscribers count based on timeline
	 *
	 * @param int $days
	 *
	 * @return string|null
	 *
	 * @since 4.3.2
	 * @since 4.3.5 Removed static call
	 * @since 4.3.6 Changed Method name from get_total_unsubscribed_contacts to get_unsubscribed_contacts_count
	 */
	public function get_unsubscribed_contacts_count( $days = 0, $distinct = true ) {
		return $this->get_contacts( 'unsubscribed', 0, $days, true, $distinct );
	}

	/**
	 * Get all confirmed contacts
	 *
	 * @param int $days
	 * @param bool $distinct
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_confirmed_contacts_count( $days = 0, $distinct = true ) {
		return $this->get_contacts( 'confirmed', 0, $days, true, $distinct );
	}

	/**
	 * Get all unconfirmed contacts
	 *
	 * @param int $days
	 * @param bool $distinct
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_unconfirmed_contacts_count( $days = 0, $distinct = true ) {
		return $this->get_contacts( 'unconfirmed', 0, $days, true, $distinct );
	}

	/**
	 * Get all contacts count
	 *
	 * @param int $days
	 * @param bool $distinct
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_contacts_count( $days = 0, $distinct = true ) {
		return $this->get_contacts( 'all', 0, $days, true, $distinct );
	}

	/**
	 * Get all contacts based on status, list, days, count
	 *
	 * @param string $status
	 * @param int $list_id
	 * @param int $days
	 * @param bool $count_only
	 * @param bool $distinct
	 *
	 * @return array|object|string|null
	 *
	 * @since 4.3.6
	 */
	public function get_contacts( $status = 'all', $list_id = 0, $days = 0, $count_only = false, $distinct = true ) {
		global $wpdb;

		$expected_statuses = array( 'subscribed', 'unsubscribed', 'unconfirmed', 'confirmed', 'all' );

		if ( ! in_array( $status, $expected_statuses ) ) {
			return array();
		}

		$status  = esc_sql( $status );
		$list_id = esc_sql( $list_id );

		$where[] = "1 = %d";
		$args[]  = 1;
		if ( ! empty( $status ) ) {
			switch ( $status ) {
				case 'subscribed':
				case 'unsubscribed':
				case 'unconfirmed':
					$where[] = "status = %s";
					$args[]  = $status;
					break;
				case 'confirmed':
					$where[] = "status = %s AND optin_type = %d";
					$args[]  = $status;
					$args[]  = IG_DOUBLE_OPTIN;
					break;
				default:
					$where[] = "1 = 1";
					break;
			}
		}

		$list_id = absint( $list_id );
		if ( ! empty( $list_id ) ) {
			$list_id = esc_sql( $list_id );
			$where[] = "list_id = %d";
			$args[]  = $list_id;
		}

		$days = absint( $days );
		if ( $days > 0 ) {
			$days = esc_sql( $days );

			if ( 'unsubscribed' === $status ) {
				$where[] = "unsubscribed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)";
				$args[]  = $days;
			} elseif ( 'subscribed' === $status ) {
				$where[] = "subscribed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)";
				$args[]  = $days;
			}
		}

		if ( count( $where ) > 0 ) {
			$where = implode( " AND ", $where );
			$where = $wpdb->prepare( $where, $args );
		}

		if ( $count_only ) {
			return $this->get_total_contacts( $where, $distinct );
		} else {
			return $this->get_by_conditions( $where );
		}
	}

	/**
	 * Get contacts from list based on status
	 *
	 * @param string $status
	 * @param int $list_id
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_contacts_from_list( $status = 'all', $list_id = 0 ) {
		$list_id = absint( $list_id );

		if ( empty( $list_id ) ) {
			return array();
		}

		return $this->get_contacts( $status, $list_id );
	}

	/**
	 * Get Subscribed contacts from list
	 *
	 * @param int $list_id
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_subscribed_contacts_from_list( $list_id = 0 ) {
		return $this->get_contacts_from_list( 'subscribed', $list_id );
	}

	/**
	 * Get Unsubscribed contacts from list
	 *
	 * @param int $list_id
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_unsubscribed_contacts_from_list( $list_id = 0 ) {
		return $this->get_contacts_from_list( 'unsubscribed', $list_id );
	}

	/**
	 * Get Confirmed contacts from list
	 *
	 * @param int $list_id
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_confirmed_contacts_from_list( $list_id = 0 ) {
		return $this->get_contacts_from_list( 'confirmed', $list_id );
	}

	/**
	 * Get Unconfirmed contacts from list
	 *
	 * @param int $list_id
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_unconfirmed_contacts_from_list( $list_id = 0 ) {
		return $this->get_contacts_from_list( 'unconfirmed', $list_id );
	}

	/**
	 * Get All Contacts from list
	 *
	 * @param int $list_id
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_contacts_from_list( $list_id = 0 ) {
		return $this->get_contacts_from_list( 'all', $list_id );
	}

	/**
	 * Get All Subscribed contacts
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_subscribed_contacts() {
		return $this->get_contacts( 'subscribed' );
	}

	/**
	 * Get All Unsubscribed contacts
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_unsubscribed_contacts() {
		return $this->get_contacts( 'unsubscribed' );
	}

	/**
	 * Get All Confirmed contacts from list
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_confirmed_contacts() {
		return $this->get_contacts( 'confirmed' );
	}

	/**
	 * Get All Unconfirmed contacts
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_unconfirmed_contacts() {
		return $this->get_contacts( 'unconfirmed' );
	}

	/**
	 * Get All Contacts
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.6
	 */
	public function get_all_contacts() {
		return $this->get_contacts( 'all' );
	}

	/**
	 * Get total subscribed contacts by date
	 *
	 * @param string $status
	 * @param int $days
	 *
	 * @return array
	 *
	 * @since 4.4.0
	 */
	public function get_total_subscribed_contacts_by_date( $days = 60 ) {
		global $wpdb;

		$columns = array( 'DATE(subscribed_at) as date', 'count(DISTINCT(contact_id)) as total' );
		$where   = "status = %s";
		$args[] = 'subscribed';

		if ( 0 != $days ) {
			$days   = esc_sql( $days );
			$where  .= " AND subscribed_at >= DATE_SUB(NOW(), INTERVAL %d DAY)";
			$args[] = $days;
		}

		$group_by = ' GROUP BY DATE(subscribed_at)';

		$where .= $group_by;

		$where = $wpdb->prepare( $where, $args );

		$results = $this->get_columns_by_condition( $columns, $where );

		$contacts = array();
		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$contacts[ $result['date'] ] = $result['total'];
			}
		}

		return $contacts;
	}

}