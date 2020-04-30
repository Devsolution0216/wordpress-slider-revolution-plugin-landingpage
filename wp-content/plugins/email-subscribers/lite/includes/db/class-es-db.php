<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ES_DB base class
 *
 * @since 4.0
 */
abstract class ES_DB {
	/**
	 * @since 4.0.0
	 * @var $table_name
	 *
	 */
	public $table_name;

	/**
	 * @since 4.0.0
	 * @var $version
	 *
	 */
	public $version;

	/**
	 * @since 4.0.0
	 * @var $primary_key
	 *
	 */
	public $primary_key;

	/**
	 * ES_DB constructor.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
	}

	/**
	 * Get default columns
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 */
	public function get_columns() {
		return array();
	}

	/**
	 * @return array
	 *
	 * @since 4.0.0
	 */
	public function get_column_defaults() {
		return array();
	}

	/**
	 * Retrieve a row by the primary key
	 *
	 * @param $row_id
	 *
	 * @return array|object|void|null
	 *
	 * @since 4.0.0
	 */
	public function get( $row_id, $output = ARRAY_A ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ), $output );
	}

	/**
	 * Retrieve a row by a specific column / value
	 *
	 * @param $column
	 * @param $row_id
	 *
	 * @return array|object|void|null
	 *
	 * @since 4.0.0
	 */
	public function get_by( $column, $row_id, $output = ARRAY_A ) {
		global $wpdb;
		$column = esc_sql( $column );

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $row_id ), $output );
	}

	/**
	 * Get rows by conditions
	 *
	 * @param string $where
	 *
	 * @since 4.2.1
	 */
	public function get_by_conditions( $where = '', $output = ARRAY_A ) {
		global $wpdb;

		$query = "SELECT * FROM $this->table_name";

		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}

		return $wpdb->get_results( $query, $output );
	}

	/**
	 * Get all data from table without any condition
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.1
	 */
	public function get_all() {
		return $this->get_by_conditions();
	}

	/**
	 * Retrieve a specific column's value by the primary key
	 *
	 * @param $column
	 * @param $row_id
	 *
	 * @return null|string|array
	 *
	 * @since 4.0.0
	 */
	public function get_column( $column, $row_id = 0 ) {
		global $wpdb;

		$column = esc_sql( $column );

		if ( $row_id ) {
			return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
		} else {
			return $wpdb->get_col( "SELECT $column FROM $this->table_name" );
		}
	}

	/**
	 * Retrieve a specific column's value by the the specified column / value
	 *
	 * @param $column
	 * @param $column_where
	 * @param $column_value
	 * @param bool $only_one
	 *
	 * @return array|string|null
	 *
	 * @since 4.0.0
	 * @since 4.3.4 Added support to retrieve whole column
	 */
	public function get_column_by( $column, $column_where, $column_value, $only_one = true ) {
		global $wpdb;

		$column_where = esc_sql( $column_where );
		$column       = esc_sql( $column );

		if ( $only_one ) {
			return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
		} else {
			return $wpdb->get_col( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $column_where = %s;", $column_value ) );
		}
	}

	/**
	 * Get column based on where condition
	 *
	 * @param $column
	 * @param string $where
	 *
	 * @return array
	 *
	 * @since 4.3.5
	 */
	public function get_column_by_condition( $column, $where = '' ) {
		global $wpdb;

		$column = esc_sql( $column );

		if ( ! empty( $where ) ) {
			return $wpdb->get_col( "SELECT $column FROM $this->table_name WHERE $where" );
		} else {
			return $wpdb->get_col( "SELECT $column FROM $this->table_name" );
		}
	}

	/**
	 * Select few columns based on condition
	 *
	 * @param array $columns
	 * @param string $where
	 *
	 * @return array|object|null
	 *
	 * @since 4.3.5
	 */
	public function get_columns_by_condition( $columns = array(), $where = '', $output = ARRAY_A ) {
		global $wpdb;

		if ( ! is_array( $columns ) ) {
			return array();
		}

		$columns = esc_sql( $columns );

		$columns = implode( ', ', $columns );

		if ( ! empty( $where ) ) {
			return $wpdb->get_results( "SELECT $columns FROM $this->table_name WHERE $where", $output );
		} else {
			return $wpdb->get_results( "SELECT $columns FROM $this->table_name", $output );
		}
	}

	/**
	 * Insert a new row
	 *
	 * @param $data
	 * @param string $type
	 *
	 * @return int
	 *
	 * @since 4.0.0
	 */
	public function insert( $data, $type = '' ) {
		global $wpdb;

		// Set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		do_action( 'ig_es_pre_insert_' . $type, $data );

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$wpdb->insert( $this->table_name, $data, $column_formats );
		$wpdb_insert_id = $wpdb->insert_id;

		do_action( 'ig_es_post_insert_' . $type, $wpdb_insert_id, $data );

		return $wpdb_insert_id;
	}

	/**
	 * Update a specific row
	 *
	 * @param $row_id
	 * @param array $data
	 * @param string $where
	 *
	 * @return bool
	 *
	 * @since 4.0.0
	 */
	public function update( $row_id, $data = array(), $where = '' ) {

		global $wpdb;

		// Row ID must be positive integer
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		if ( empty( $where ) ) {
			$where = $this->primary_key;
		}

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		if ( false === $wpdb->update( $this->table_name, $data, array( $where => $row_id ), $column_formats ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Delete a row by primary key
	 *
	 * @param int $row_id
	 *
	 * @return bool
	 *
	 * @since 4.0.0
	 */
	public function delete( $row_id = 0 ) {

		global $wpdb;

		// Row ID must be positive integer
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		$where = $wpdb->prepare( "$this->primary_key = %d", $row_id );

		if ( false === $this->delete_by_condition( $where ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Delete rows by primary key
	 *
	 * @param array $row_ids
	 *
	 * @return bool
	 *
	 * @since 4.3.4
	 */
	public function bulk_delete( $row_ids = array() ) {

		if ( ! is_array( $row_ids ) && empty( $row_ids ) ) {
			return false;
		}

		$row_ids_str = $this->prepare_for_in_query( $row_ids );

		$where = "$this->primary_key IN( $row_ids_str )";

		if ( false === $this->delete_by_condition( $where ) ) {
			return false;
		}

		return true;
	}


	/**
	 * Delete records based on $where
	 *
	 * @param string $where
	 *
	 * @return bool
	 *
	 * @since 4.2.4
	 */
	public function delete_by_condition( $where = '' ) {
		global $wpdb;

		if ( empty( $where ) ) {
			return false;
		}

		if ( false === $wpdb->query( "DELETE FROM $this->table_name WHERE $where" ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check whether table exists or not
	 *
	 * @param $table
	 *
	 * @return bool
	 *
	 * @since 4.0.0
	 */
	public function table_exists( $table ) {
		global $wpdb;
		$table = sanitize_text_field( $table );

		return $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table ) ) === $table;
	}

	/**
	 * Check whether table installed
	 *
	 * @return bool
	 *
	 * @since 4.0.0
	 */
	public function installed() {
		return $this->table_exists( $this->table_name );
	}

	/**
	 * Get total count
	 *
	 * @return string|null
	 *
	 * @since 4.2.1
	 */
	public function count( $where = '' ) {
		global $wpdb;

		$query = "SELECT count(*) FROM $this->table_name";

		if ( ! empty( $where ) ) {
			$query .= " WHERE $where";
		}

		return $wpdb->get_var( $query );
	}

	/**
	 * Insert data into bulk
	 *
	 * @param $values
	 * @param int $length
	 * @param string $type
	 *
	 * @since 4.2.1
	 * @since 4.3.5 Fixed issues and started using it.
	 */
	public function bulk_insert( $values, $length = 100 ) {
		global $wpdb;

		if ( ! is_array( $values ) ) {
			return false;
		}

		// Get the first value from an array to check data structure
		$first_value = array_slice( $values, 0, 1 );

		$data = array_shift( $first_value );

		// Set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Remove primary key as we don't require while inserting data
		unset( $column_formats[ $this->primary_key ] );

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		$data_keys = array_keys( $data );

		$fields = array_keys( array_merge( array_flip( $data_keys ), $column_formats ) );

		// Convert Batches into smaller chunk
		$batches = array_chunk( $values, $length );

		foreach ( $batches as $key => $batch ) {

			$place_holders = $final_values = array();

			foreach ( $batch as $value ) {

				$formats = array();
				foreach ( $column_formats as $column => $format ) {
					$final_values[] = isset( $value[ $column ] ) ? $value[ $column ] : $data[ $column ]; // set default if we don't have
					$formats[]      = $format;
				}

				$place_holders[] = "( " . implode( ', ', $formats ) . " )";
				$fields_str      = "`" . implode( "`, `", $fields ) . "`";
			}

			$query = "INSERT INTO $this->table_name ({$fields_str}) VALUES ";
			$query .= implode( ', ', $place_holders );
			$sql   = $wpdb->prepare( $query, $final_values );

			if ( ! $wpdb->query( $sql ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $table_name
	 * @param $fields
	 * @param $place_holders
	 * @param $values
	 *
	 * @return bool
	 *
	 *
	 */
	public static function do_insert( $table_name, $fields, $place_holders, $values ) {
		global $wpdb;

		$fields_str = "`" . implode( "`, `", $fields ) . "`";

		$query = "INSERT INTO $table_name ({$fields_str}) VALUES ";
		$query .= implode( ', ', $place_holders );
		$sql   = $wpdb->prepare( $query, $values );

		if ( $wpdb->query( $sql ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get ID, Name Map
	 *
	 * @param string $where
	 *
	 * @return array
	 *
	 * @since 4.2.2
	 * @since 4.3.5 Used get_columns_map method
	 */
	public function get_id_name_map( $where = '' ) {
		return $this->get_columns_map( $this->primary_key, 'name', $where );
	}

	/**
	 * Get map of two columns
	 *
	 * e.g array($column_1 => $column_2)
	 *
	 * @param string $column_1
	 * @param string $column_2
	 * @param string $where
	 *
	 * @return array
	 *
	 * @since 4.3.5
	 */
	public function get_columns_map( $column_1 = '', $column_2 = '', $where = '' ) {
		if ( empty( $column_1 ) || empty( $column_2 ) ) {
			return array();
		}

		$columns = array( $column_1, $column_2 );

		$results = $this->get_columns_by_condition( $columns, $where );

		$map = array();
		if ( count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$map[ $result[ $column_1 ] ] = $result[ $column_2 ];
			}
		}

		return $map;
	}

	public static function prepare_data( $data, $column_formats, $column_defaults, $insert = true ) {

		// Set default values
		if ( $insert ) {
			$data = wp_parse_args( $data, $column_defaults );
		}

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		return array(
			'data'           => $data,
			'column_formats' => $column_formats
		);

	}

	/**
	 * Prepare string for SQL IN query
	 *
	 * @param array $array
	 *
	 * @return string
	 *
	 * @since 4.3.4
	 */
	public function prepare_for_in_query( $array = array() ) {

		$array = esc_sql( $array );

		if ( is_array( $array ) && count( $array ) > 0 ) {
			return "'" . implode( "', '", $array ) . "'";
		}

		return '';
	}

}
