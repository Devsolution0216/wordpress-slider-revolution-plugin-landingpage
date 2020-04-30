<?php
/**
 * Main class
 *
 * @author YITH
 * @package YITH WooCommerce Quick View
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WCQV' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCQV' ) ) {
	/**
	 * YITH WooCommerce Quick View
	 *
	 * @since 1.0.0
	 */
	class YITH_WCQV {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCQV
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WCQV_VERSION;

		/**
		 * Plugin object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $obj = null;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCQV
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return mixed| YITH_WCQV_Admin | YITH_WCQV_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			// Load Plugin Framework
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );
			
			$action = array(
				'woocommerce_get_refreshed_fragments',
				'woocommerce_apply_coupon',
				'woocommerce_remove_coupon',
				'woocommerce_update_shipping_method',
				'woocommerce_update_order_review',
				'woocommerce_add_to_cart',
				'woocommerce_checkout'
			);

			// Exit if is woocommerce ajax
			if( defined( 'DOING_AJAX') && DOING_AJAX && isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $action ) ) {
				return;
			}

			if ( $this->is_admin() ) {
				// load admin classes
				require_once( 'class.yith-wcqv-admin.php' );
				YITH_WCQV_Admin();
			}

			if( $this->load_frontend() ) {
				// load frontend classes
				require_once( 'class.yith-wcqv-frontend.php' );
				YITH_WCQV_Frontend();
			}
		}

		/**
		 * Check if context is admin
		 * 
		 * @author Francesco Licandro
		 * @since 1.2.0 	
		 * @return boolean
		 */
		public function is_admin(){
			$is_ajax = ( defined( 'DOING_AJAX') && DOING_AJAX && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend' );
			return apply_filters( 'yith_wcqv_is_admin', is_admin() && ! $is_ajax );
		}

		/**
		 * Check if load or not frontend
		 * 
		 * @author Francesco Licandro
		 * @since 1.2.0
		 * @return boolean
		 */
		public function load_frontend(){
			// Class frontend
			$enable             = get_option( 'yith-wcqv-enable', 'yes' ) == 'yes';
			$enable_on_mobile   = get_option( 'yith-wcqv-enable-mobile', 'yes' ) ==  'yes';
			$is_mobile          = wp_is_mobile();
						
			return apply_filters( 'yith_wcqv_load_frontend', ( ! $is_mobile && $enable ) || ( $is_mobile && $enable_on_mobile ) );
		}


		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
        public function plugin_fw_loader() {
            if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
                global $plugin_fw_data;
                if( ! empty( $plugin_fw_data ) ){
                    $plugin_fw_file = array_shift( $plugin_fw_data );
                    require_once( $plugin_fw_file );
                }
            }
        }
	}
}

/**
 * Unique access to instance of YITH_WCQV class
 *
 * @return \YITH_WCQV
 * @since 1.0.0
 */
function YITH_WCQV(){
	return YITH_WCQV::get_instance();
}