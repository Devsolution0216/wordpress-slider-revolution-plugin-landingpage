<?php
/**
 * Plugin Name: MB Admin Columns
 * Plugin URI: https://metabox.io/plugins/mb-admin-columns/
 * Description: Show custom fields in the post list table.
 * Version: 1.4.0
 * Author: MetaBox.io
 * Author URI: https://metabox.io
 * License: GPL2+
 * Text Domain: mb-admin-columns
 * Domain Path: /languages/
 *
 * @package    Meta Box
 * @subpackage MB Admin Columns
 */

// Prevent loading this file directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MB_Admin_Columns' ) ) {
	/**
	 * Plugin main class.
	 */
	class MB_Admin_Columns {
		/**
		 * Constructor.
		 * Add hooks.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'init' ) );
		}

		/**
		 * Initialization.
		 * Load plugin files and bootstrap for posts and taxonomies.
		 */
		public function init() {
			if ( ! defined( 'RWMB_VER' ) || class_exists( 'MB_Admin_Columns_Post' ) ) {
				return;
			}

			require_once dirname( __FILE__ ) . '/inc/class-mb-admin-columns-base.php';
			require_once dirname( __FILE__ ) . '/inc/class-mb-admin-columns-post.php';
			require_once dirname( __FILE__ ) . '/inc/class-mb-admin-columns-taxonomy.php';
			require_once dirname( __FILE__ ) . '/inc/class-mb-admin-columns-user.php';

			$this->posts();
			$this->taxonomies();
			$this->users();
		}

		/**
		 * Add admin columns for posts.
		 */
		protected function posts() {
			$meta_boxes = rwmb_get_registry( 'meta_box' )->get_by( array(
				'object_type' => 'post',
			) );
			foreach ( $meta_boxes as $meta_box ) {
				$fields = array_filter( $meta_box->fields, array( $this, 'has_admin_columns' ) );
				if ( empty( $fields ) ) {
					continue;
				}

				$table = isset( $meta_box->meta_box['table'] ) ? $meta_box->meta_box['table'] : '';

				foreach ( $meta_box->post_types as $post_type ) {
                    new MB_Admin_Columns_Post( $post_type, $fields, $table );
				}
			}
		}

		/**
		 * Add admin columns for terms.
		 */
		protected function taxonomies() {
			$meta_boxes = rwmb_get_registry( 'meta_box' )->get_by( array(
				'object_type' => 'term',
			) );
			foreach ( $meta_boxes as $meta_box ) {
				$fields = array_filter( $meta_box->fields, array( $this, 'has_admin_columns' ) );
				if ( empty( $fields ) ) {
					continue;
				}

				foreach ( $meta_box->taxonomies as $taxonomy ) {
					new MB_Admin_Columns_Taxonomy( $taxonomy, $fields );
				}
			}
		}

		/**
		 * Add admin columns for users.
		 */
		protected function users() {
			$meta_boxes = rwmb_get_registry( 'meta_box' )->get_by( array(
				'object_type' => 'user',
			) );
			foreach ( $meta_boxes as $meta_box ) {
				$fields = array_filter( $meta_box->fields, array( $this, 'has_admin_columns' ) );
				if ( empty( $fields ) ) {
					continue;
				}

				new MB_Admin_Columns_User( 'user', $fields );
			}
		}

		/**
		 * Check if field has admin columns.
		 *
		 * @param array $field Field configuration.
		 *
		 * @return bool
		 */
		protected function has_admin_columns( $field ) {
			return ! empty( $field['admin_columns'] );
		}
	}

	new MB_Admin_Columns;
} // End if().
