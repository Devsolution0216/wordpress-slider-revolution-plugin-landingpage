<?php
/**
 * Admin class
 *
 * @author YITH
 * @package YITH WooCommerce Quick View
 * @version 1.1.1
 */

if ( ! defined( 'YITH_WCQV' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCQV_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCQV_Admin {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCQV_Admin
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @var array
		 * @access public
		 * @since 1.0.0
		 */
		public $options = array();

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WCQV_VERSION;

		/**
		 * @var $_panel Panel Object
		 */
		protected $_panel;

		/**
		 * @var $_premium string Premium tab template file name
		 */
		protected $_premium = 'premium.php';

		/**
		 * @var string Premium version landing link
		 */
		protected $_premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-quick-view/';

		/**
		 * @var string Quick View panel page
		 */
		protected $_panel_page = 'yith_wcqv_panel';

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCQV_Admin
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
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			// Add panel options
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5) ;

			//Add action links
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_WCQV_DIR . '/' . basename( YITH_WCQV_FILE ) ), array( $this, 'action_links') );
            add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

            add_action( 'yith_quick_view_premium', array( $this, 'premium_tab' ) );

			// YITH WCQV Loaded
			do_action( 'yith_wcqv_loaded' );

		}


		/**
		 * Action Links
		 *
		 * add the action links to plugin admin page
		 *
		 * @param $links | links plugin array
		 *
		 * @return   mixed Array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return mixed
		 * @use plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->_panel_page, false );
			return $links;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array(
				'settings' => __( 'Settings', 'yith-woocommerce-quick-view' ),
				'premium'  => __( 'Premium Version', 'yith-woocommerce-quick-view' ),
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
                'page_title'       => _x( 'YITH WooCommerce Quick View', 'options page title', 'yith-woocommerce-quick-view' ),
                'menu_title'       => _x( 'Quick View', 'plugin menu title', 'yith-woocommerce-quick-view' ),
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yith_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YITH_WCQV_DIR . '/plugin-options',
                'class'            => yith_set_wrapper_class(),
                'plugin_slug'      => YITH_WCQV_SLUG
			);


			/* === Fixed: not updated theme  === */
			if( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once( 'plugin-fw/lib/yit-plugin-panel-wc.php' );
			}

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return void
		 */
		public function premium_tab() {
			$premium_tab_template = YITH_WCQV_TEMPLATE_PATH . '/admin/' . $this->_premium;
			if( file_exists( $premium_tab_template ) ) {
				include_once($premium_tab_template);
			}

		}

        /**
         * plugin_row_meta
         *
         * add the action links to plugin admin page
         *
         * @param $plugin_meta
         * @param $plugin_file
         * @param $plugin_data
         * @param $status
         *
         * @return   Array
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @use plugin_row_meta
         */
        public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status ) {
            if ( defined( 'YITH_WCQV_INIT' ) && YITH_WCQV_INIT == $plugin_file ) {
                $new_row_meta_args['slug']      = YITH_WCQV_SLUG;

                if( defined( 'YITH_WCQV_PREMIUM' ) ){
                    $new_row_meta_args['is_premium'] = true;
                }
            }
            return $new_row_meta_args;
        }

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri(){
			return $this->_premium_landing;
		}

	}
}
/**
 * Unique access to instance of YITH_WCQV_Admin class
 *
 * @return \YITH_WCQV_Admin
 * @since 1.0.0
 */
function YITH_WCQV_Admin(){
	return YITH_WCQV_Admin::get_instance();
}