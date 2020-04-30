<?php

/**
 * The main plugin class
 */
final class TA_WC_Variation_Swatches {
	/**
	 * The single instance of the class
	 *
	 * @var TA_WC_Variation_Swatches
	 */
	protected static $instance = null;

	/**
	 * Extra attribute types
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * Main instance
	 *
	 * @return TA_WC_Variation_Swatches
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->types = array(
			'color' => esc_html__( 'Color', 'wcvs' ),
			'image' => esc_html__( 'Image', 'wcvs' ),
			'label' => esc_html__( 'Label', 'wcvs' ),
		);

		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once dirname( __FILE__ ) . '/class-frontend.php';

		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/class-admin.php';
		}
	}

	/**
	 * Initialize hooks
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );

		if ( is_admin() ) {
			add_action( 'init', array( 'TA_WC_Variation_Swatches_Admin', 'instance' ) );
		}

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			add_action( 'init', array( 'TA_WC_Variation_Swatches_Frontend', 'instance' ) );
		}
	}

	/**
	 * Load plugin text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wcvs', false, dirname( plugin_basename( TAWC_VS_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Add extra attribute types
	 * Add color, image and label type
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	public function add_attribute_types( $types ) {
		$types = array_merge( $types, $this->types );

		return $types;
	}

	/**
	 * Get attribute's properties
	 *
	 * @param string $taxonomy
	 *
	 * @return object
	 */
	public function get_tax_attribute( $taxonomy ) {
		global $wpdb;

		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", $attr ) );

		return $attr;
	}

	/**
	 * Instance of admin
	 *
	 * @return object
	 */
	public function admin() {
		return TA_WC_Variation_Swatches_Admin::instance();
	}

	/**
	 * Instance of frontend
	 *
	 * @return object
	 */
	public function frontend() {
		return TA_WC_Variation_Swatches_Frontend::instance();
	}
}

if ( ! function_exists( 'TA_WCVS' ) ) {
	/**
	 * Main instance of plugin
	 *
	 * @return TA_WC_Variation_Swatches
	 */
	function TA_WCVS() {
		return TA_WC_Variation_Swatches::instance();
	}
}