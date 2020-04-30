<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ES_DB_Forms extends ES_DB {
	/**
	 * @since 4.2.2
	 * @var string
	 *
	 */
	public $table_name;

	/**
	 * @since 4.2.2
	 * @var string
	 *
	 */
	public $version;

	/**
	 * @since 4.2.2
	 * @var string
	 *
	 */
	public $primary_key;

	/**
	 * ES_DB_Forms constructor.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		global $wpdb;

		parent::__construct();

		$this->table_name = $wpdb->prefix . 'ig_forms';

		$this->primary_key = 'id';

		$this->version = '1.0';
	}

	/**
	 * Get table columns
	 *
	 * @return array
	 *
	 * @since 4.2.2
	 */
	public function get_columns() {
		return array(
			'id'         => '%d',
			'name'       => '%s',
			'body'       => '%s',
			'settings'   => '%s',
			'styles'     => '%s',
			'created_at' => '%s',
			'updated_at' => '%s',
			'deleted_at' => '%s',
			'af_id'      => '%d'
		);
	}

	/**
	 * Get default column values
	 *
	 * @since  4.2.2
	 */
	public function get_column_defaults() {
		return array(
			'name'       => null,
			'body'       => null,
			'settings'   => null,
			'styles'     => null,
			'created_at' => ig_get_current_date_time(),
			'updated_at' => null,
			'deleted_at' => null,
			'af_id'      => 0
		);
	}

	/**
	 * Get ID Name Map of Forms
	 *
	 * Note: We are using this static function in Icegram.
	 * Think about compatibility before any modification
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 *
	 * @modify 4.2.2
	 */
	public static function get_forms_id_name_map() {

		global $wpdb;

		$forms_table = IG_FORMS_TABLE;

		$query = "SELECT id, name FROM $forms_table";

		$results = $wpdb->get_results( $query, ARRAY_A );

		$id_name_map = array();
		if ( count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$id_name_map[ $result['id'] ] = $result['name'];
			}
		}

		return $id_name_map;
	}

	/**
	 * Add Form
	 *
	 * @param $data
	 *
	 * @return int
	 *
	 * @since 4.2.2
	 */
	public function add_form( $data ) {
		return $this->insert( $data );
	}

	/**
	 * Get Form By ID
	 *
	 * @param $id
	 *
	 * @return array|mixed
	 *
	 * @since 4.0.0
	 */
	public function get_form_by_id( $id ) {

		if ( empty( $id ) ) {
			return array();
		}

		return $this->get( $id );
	}

	/**
	 * Get form based on advance form id
	 *
	 * @param $af_id
	 *
	 * @return array|mixed
	 *
	 * @since 4.0.0
	 *
	 * @modify 4.2.0
	 */
	public function get_form_by_af_id( $af_id ) {
		global $wpdb;

		$where = $wpdb->prepare( "af_id = %d", $af_id );

		$forms = $this->get_by_conditions( $where );

		$form = array();
		if ( ! empty( $forms ) ) {
			$form = array_shift( $forms );
		}

		return $form;

	}

	/**
	 * Migrate advanced forms data
	 *
	 * @since 4.0.0
	 *
	 * @modify 4.2.2
	 */
	public function migrate_advanced_forms() {
		global $wpdb;

		$table           = sanitize_text_field( EMAIL_SUBSCRIBERS_ADVANCED_FORM );
		$is_table_exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table ) ) === $table;

		$lists_name_id_map = ES()->lists_db->get_list_id_name_map( '', true );
		if ( $is_table_exists ) {
			$query = "SELECT * FROM " . EMAIL_SUBSCRIBERS_ADVANCED_FORM;
			$forms = $wpdb->get_results( $query, ARRAY_A );

			if ( count( $forms ) > 0 ) {

				$data = array();
				foreach ( $forms as $key => $form ) {

					$es_af_id         = $form['es_af_id'];
					$es_af_title      = $form['es_af_title'];
					$es_af_desc       = $form['es_af_desc'];
					$es_af_name       = $form['es_af_name'];
					$es_af_name_mand  = $form['es_af_name_mand'];
					$es_af_email      = $form['es_af_email'];
					$es_af_email_mand = $form['es_af_email_mand'];
					$es_af_group      = $form['es_af_group'];
					$es_af_group_mand = $form['es_af_group_mand'];
					$es_af_group_list = $form['es_af_group_list'];

					$es_af_group_lists = explode( ',', $es_af_group_list );
					$list_ids          = array();
					if ( count( $es_af_group_lists ) > 0 ) {
						foreach ( $es_af_group_lists as $list ) {

							if ( ! isset( $lists_name_id_map[ $list ] ) ) {
								$list_id                    = ES()->lists_db->add_list( $list );
								$lists_name_id_map[ $list ] = $list_id;
							}

							$list_ids[] = $lists_name_id_map[ $list ];
						}
					}

					$body = array(
						array(
							'type'   => 'text',
							'name'   => 'Name',
							'id'     => 'name',
							'params' => array(
								'label'    => 'Name',
								'show'     => ( $es_af_name === 'YES' ) ? true : false,
								'required' => ( $es_af_name_mand === 'YES' ) ? true : false
							),

							'position' => 1
						),

						array(
							'type'   => 'text',
							'name'   => 'Email',
							'id'     => 'email',
							'params' => array(
								'label'    => 'Email',
								'show'     => ( $es_af_email === 'YES' ) ? true : false,
								'required' => ( $es_af_email_mand === 'YES' ) ? true : false
							),

							'position' => 2
						),

						array(
							'type'   => 'checkbox',
							'name'   => 'Lists',
							'id'     => 'lists',
							'params' => array(
								'label'    => 'Lists',
								'show'     => ( $es_af_group === 'YES' ) ? true : false,
								'required' => ( $es_af_group_mand === 'YES' ) ? true : false,
								'values'   => $list_ids
							),

							'position' => 3
						),

						array(
							'type'   => 'submit',
							'name'   => 'submit',
							'id'     => 'submit',
							'params' => array(
								'label' => 'Subscribe',
								'show'  => true
							),

							'position' => 4
						),

					);

					$settings = array(
						'lists' => $list_ids,
						'desc'  => $es_af_desc
					);

					$data[ $key ]['name']       = $es_af_title;
					$data[ $key ]['body']       = maybe_serialize( $body );
					$data[ $key ]['settings']   = maybe_serialize( $settings );
					$data[ $key ]['styles']     = null;
					$data[ $key ]['created_at'] = ig_get_current_date_time();
					$data[ $key ]['updated_at'] = null;
					$data[ $key ]['deleted_at'] = null;
					$data[ $key ]['af_id']      = $es_af_id;
				}

				$this->bulk_insert( $data );
			}
		}
	}

	/**
	 * Get total forms count
	 *
	 * @return string|null
	 *
	 * @since 4.0.0
	 *
	 * @modify 4.2.2
	 */
	public function count_forms() {
		return $this->count();
	}

	/**
	 * Delete Forms
	 *
	 * @param $ids
	 *
	 * @since 4.3.1
	 */
	public function delete_forms( $ids ) {

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		if ( is_array( $ids ) && count( $ids ) > 0 ) {

			foreach ( $ids as $id ) {
				$this->delete( absint( $id ) );

				/**
				 * Take necessary cleanup steps using this hook
				 *
				 * @since 4.3.1
				 */
				do_action( 'ig_es_form_deleted', $id );
			}
		}

	}


}