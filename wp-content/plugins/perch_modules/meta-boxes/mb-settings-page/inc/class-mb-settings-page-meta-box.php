<?php
/**
 * The main class of the plugin which handles show, edit, save meta boxes for settings page.
 *
 * @package    Meta Box
 * @subpackage MB Settings Page
 * @author     Tran Ngoc Tuan Anh <rilwis@gmail.com>
 */

/**
 * Class for handling meta boxes for settings page.
 */
class MB_Settings_Page_Meta_Box extends RW_Meta_Box {
	/**
	 * Current settings page ID. It will be set when page loads.
	 *
	 * @var string
	 */
	public $page_args;

	/**
	 * Object type.
	 *
	 * @var string
	 */
	protected $object_type = 'setting';

	/**
	 * Constructor.
	 * Call parent constructor and add specific hooks.
	 *
	 * @param array $meta_box Meta box configuration.
	 */
	public function __construct( $meta_box ) {
		parent::__construct( $meta_box );
		$this->meta_box['settings_pages'] = (array) $this->meta_box['settings_pages'];
	}

	/**
	 * Specific hooks for meta box object. Default is 'post'.
	 * This should be extended in sub-classes to support meta fields for terms, user, settings pages, etc.
	 */
	protected function object_hooks() {
		add_action( 'mb_settings_page_init', array( $this, 'init_page_args' ) );
		add_action( 'mb_settings_page_load', array( $this, 'load' ) );

		// Register fields must run after init page args.
		add_action( 'init', array( $this, 'register_fields' ), 30 );
	}

	/**
	 * Assigns settings page args to $page_args property.
	 *
	 * @param  array $args Settings page args.
	 */
	public function init_page_args( $args ) {
		if ( ! in_array( $args['id'], $this->settings_pages, true ) ) {
			return;
		}

		$this->page_args = $args;
	}

	/**
	 * Add hooks for current settings page.
	 *
	 * @param array $page_args Settings page arguments.
	 */
	public function load( $page_args ) {
		static $message_shown = false;

		// Add meta boxes.
		if ( ! in_array( $page_args['id'], $this->meta_box['settings_pages'] ) ) {
			return;
		}

		// Reset page args and object ID for the current settings page.
		$this->page_args = $page_args;
		$this->set_object_id( $page_args['option_name'] );

		// Add meta boxes.
		add_meta_box(
			$this->id,
			$this->title,
			array( $this, 'show' ),
			null, // Current page.
			$this->context,
			$this->priority
		);

		// Backward compatible with Meta Box Group extension < 1.2.7 when Meta Box 1.12 switch to use storage.
		$old_group = false;
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'meta-box-group/meta-box-group.php' ) ) {
			$group = get_plugin_data( WP_PLUGIN_DIR . '/meta-box-group/meta-box-group.php' );

			if ( version_compare( $group['Version'], '1.2.7' ) < 0 ) {
				$old_group = true;
			}
		}

		if ( $old_group ) {
			// Filter field meta (saved value).
			add_filter( 'rwmb_field_meta', array( $this, 'field_meta' ), 10, 2 );
		}

		// Save options.
		// @codingStandardsIgnoreLine
		if ( ! empty( $_POST['submit'] ) ) {
			$this->save_post( $page_args['option_name'] );

			// Compatible with old hook.
			$data = get_option( $page_args['option_name'], array() );
			$data = apply_filters( 'mb_settings_pages_data', $data, $page_args['option_name'] );
			update_option( $page_args['option_name'], $data );

			// Prevent duplicate messages.
			if ( ! $message_shown ) {
				add_settings_error( $page_args['id'], 'saved', $page_args['message'], 'updated' );
				$message_shown = true;
			}
		}
	}

	/**
	 * Get field meta value
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return mixed
	 */
	public function field_meta( $meta, $field ) {
		if ( ! $this->is_edit_screen() ) {
			return $meta;
		}

		$option_name = sanitize_text_field( $this->page_args['option_name'] );
		$storage     = $this->get_storage();

		$meta = $storage->get( $option_name, $field['id'], array(
			'std' => $field['std'],
		) );

		// Escape attributes.
		$meta = RWMB_Field::call( $field, 'esc_meta', $meta );

		// Make sure meta value is an array for clonable and multiple fields.
		if ( $field['clone'] || $field['multiple'] ) {
			if ( empty( $meta ) || ! is_array( $meta ) ) {
				/**
				 * Note: if field is clonable, $meta must be an array with values
				 * so that the foreach loop in self::show() runs properly.
				 *
				 * @see self::show()
				 */
				$meta = $field['clone'] ? array( '' ) : array();
			}
		}

		return $meta;
	}

	/**
	 * Check if we're on the right edit screen.
	 *
	 * @param WP_Screen $screen Screen object. Optional. Use current screen object by default.
	 *
	 * @return bool
	 */
	public function is_edit_screen( $screen = null ) {
		return in_array( $this->page_args['id'], $this->settings_pages, true );
	}

	/**
	 * Get current object id.
	 *
	 * @return int|string
	 */
	public function get_current_object_id() {
		return $this->page_args['option_name'];
	}

	/**
	 * Add fields to field registry.
	 */
	public function register_fields() {
		if ( ! $this->page_args ) {
			return;
		}

		$field_registry = rwmb_get_registry( 'field' );

		foreach ( $this->fields as $field ) {
			$field_registry->add( $field, $this->page_args['option_name'], 'setting' );
		}
	}
}
