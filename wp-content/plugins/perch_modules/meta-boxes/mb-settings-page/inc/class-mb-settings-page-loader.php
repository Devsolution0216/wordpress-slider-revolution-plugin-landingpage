<?php
/**
 * Loader for settings page
 *
 * @package    Meta Box
 * @subpackage MB Settings Page
 * @author     Tran Ngoc Tuan Anh <rilwis@gmail.com>
 */

/**
 * Loader class
 */
class MB_Settings_Page_Loader {
	/**
	 * Settings pages.
	 *
	 * @var array
	 */
	protected $settings_pages = array();

	/**
	 * Run hooks to get meta boxes for terms and initialize them.
	 */
	public function init() {
		$this->register_settings_pages();

		add_filter( 'rwmb_meta_box_class_name', array( $this, 'meta_box_class_name' ), 10, 2 );

		add_filter( 'rwmb_meta_type', array( $this, 'filter_meta_type' ), 10, 3 );
	}

	/**
	 * Filter meta box class name.
	 *
	 * @param  string $class_name Meta box class name.
	 * @param  array  $meta_box   Meta box data.
	 * @return string
	 */
	public function meta_box_class_name( $class_name, $meta_box ) {
		if ( isset( $meta_box['settings_pages'] ) ) {
			$class_name = 'MB_Settings_Page_Meta_Box';
		}

		return $class_name;
	}

	/**
	 * Register settings pages.
	 */
	public function register_settings_pages() {
		// Get settings page.
		$this->settings_pages = apply_filters( 'mb_settings_pages', array() );

		if ( empty( $this->settings_pages ) || ! is_array( $this->settings_pages ) ) {
			return;
		}

		foreach ( $this->settings_pages as $settings_page ) {
			new MB_Settings_Page( $settings_page );
		}
	}

	/**
	 * Filter meta type from object type and object id.
	 *
	 * @param string     $type        Meta type get from object type and object id.
	 *                                Assert 'setting' if object id is a string.
	 * @param string     $object_type Object type.
	 * @param string|int $object_id   Object id. Should be the option name.
	 *
	 * @return string
	 */
	public function filter_meta_type( $type, $object_type, $object_id ) {
		return 'setting' === $object_type ? $object_id : $type;
	}

	/**
	 * Get option name from settings page id.
	 *
	 * @param  string $page_id Settings page id.
	 * @return string
	 */
	public function get_option_name_from_settings_page_id( $page_id ) {
		$option_name = '';

		foreach ( $this->settings_pages as $settings_page ) {
			if ( $page_id === $settings_page['id'] ) {
				$option_name = $settings_page['option_name'];
				break;
			}
		}

		return $option_name;
	}
}
