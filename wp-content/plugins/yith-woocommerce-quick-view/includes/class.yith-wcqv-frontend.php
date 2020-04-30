<?php
/**
 * Frontend class
 *
 * @author YITH
 * @package YITH WooCommerce Quick View
 * @version 1.1.1
 */

if ( ! defined( 'YITH_WCQV' ) ) {
	exit;
} // Exit if accessed directly

if( ! class_exists( 'YITH_WCQV_Frontend' ) ) {
	/**
	 * Admin class.
	 * The class manage all the Frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WCQV_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCQV_Frontend
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
		 * Returns single instance of the class
		 *
		 * @return \YITH_WCQV_Frontend
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

			// custom styles and javascripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

			// enqueue gift card script
            if( defined('YITH_YWGC_FILE') ){
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_gift_card_script' ) );
            }

			// quick view ajax
		    add_action( 'wp_ajax_yith_load_product_quick_view', array( $this, 'yith_load_product_quick_view_ajax' ) );
		    add_action( 'wp_ajax_nopriv_yith_load_product_quick_view', array( $this, 'yith_load_product_quick_view_ajax' ) );

			// add button
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'yith_add_quick_view_button' ), 15 );
			add_action( 'yith_wcwl_table_after_product_name', array( $this, 'yith_add_quick_view_button' ), 15, 0 );

			// load modal template
			add_action( 'wp_footer', array( $this, 'yith_quick_view' ) );

			// load action for product template
			$this->yith_quick_view_action_template();

			add_shortcode( 'yith_quick_view', array( $this, 'quick_view_shortcode' ) );
            add_filter( 'woocommerce_add_to_cart_form_action', array( $this, 'avoid_redirect_to_single_page'), 10, 1 );
		}

		/**
		 * Enqueue styles and scripts
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_styles_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script( 'yith-wcqv-frontend', YITH_WCQV_ASSETS_URL . '/js/frontend'.$suffix.'.js', array('jquery'), $this->version, true);
			wp_enqueue_script( 'yith-wcqv-frontend' );
			wp_enqueue_style( 'yith-quick-view', YITH_WCQV_ASSETS_URL . '/css/yith-quick-view.css' );

			$background_modal = get_option( 'yith-wcqv-background-modal' );
			$close_color = get_option( 'yith-wcqv-close-color' );
			$close_color_hover = get_option( 'yith-wcqv-close-color-hover' );

			$inline_style = "
				#yith-quick-view-modal .yith-wcqv-main{background:{$background_modal};}
				#yith-quick-view-close{color:{$close_color};}
				#yith-quick-view-close:hover{color:{$close_color_hover};}";

			wp_add_inline_style( 'yith-quick-view', $inline_style );
		}


        /**
         * Enqueue scripts for YITH WooCommerce Gift Cards
         *
         * @access public
         * @return void
         * @since 1.0.0
         * @author Francesco Licandro <francesco.licandro@yithemes.com>
         */
        public function enqueue_gift_card_script(){

            if( !wp_script_is('ywgc-frontend') && !is_checkout() ){
                wp_register_script( "ywgc-frontend",
                    YITH_YWGC_URL . 'assets/js/' . yit_load_js_file( 'ywgc-frontend.js' ),
                    array(
                        'jquery',
                        'woocommerce',
                    ),
                    YITH_YWGC_VERSION,
                    true );

                wp_enqueue_script( "ywgc-frontend" );
            }
        }

		/**
		 * Add quick view button in wc product loop
		 *
		 * @access public
		 * @param integer|string $product_id The product id
		 * @param string $label The button label
		 * @param boolean $return
		 * @return string|void
		 * @since  1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_add_quick_view_button( $product_id = 0, $label = '', $return = false ) {

			global $product;

            if( ! $product_id ){
                $product instanceof WC_Product && $product_id = yit_get_prop( $product, 'id', true );
            }

            $show_quick_view_button = apply_filters( 'yith_wcqv_show_quick_view_button', true, $product_id );

            if( !$show_quick_view_button ) return;

            $button = '';
            if( $product_id ) {
                // get label
                ! $label && $label = $this->get_button_label();

                $button = '<a href="#" class="button yith-wcqv-button" data-product_id="' . $product_id . '">' . $label . '</a>';
                $button = apply_filters('yith_add_quick_view_button_html', $button, $label, $product);
            }

			if( $return ) {
				return $button;
			}

			echo $button;
		}

		/**
		 * Enqueue scripts and pass variable to js used in quick view
		 *
		 * @access public
		 * @return bool
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_woocommerce_quick_view() {

			wp_enqueue_script( 'wc-add-to-cart-variation' );
			if( version_compare( WC()->version, '3.0.0', '>=' ) ) {
                if( current_theme_supports('wc-product-gallery-zoom') ) {
                    wp_enqueue_script('zoom');
                }
                if( current_theme_supports('wc-product-gallery-lightbox') ) {
                    wp_enqueue_script('photoswipe-ui-default');
                    wp_enqueue_style('photoswipe-default-skin');
                    if( has_action('wp_footer', 'woocommerce_photoswipe') === FALSE ) {
                        add_action('wp_footer', 'woocommerce_photoswipe', 15);
                    }
                }
                wp_enqueue_script('wc-single-product');
            }

			// enqueue wc color e label variation style
			wp_enqueue_script( 'yith_wccl_frontend' );
			wp_enqueue_style( 'yith_wccl_frontend' );

			$lightbox_en = get_option( 'yith-wcqv-enable-lightbox' ) == 'yes' ? true : false;

			// if enabled load prettyPhoto css
			if( $lightbox_en ) {

				$assets_path = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';

				wp_enqueue_script( 'prettyPhoto', $assets_path . 'js/prettyPhoto/jquery.prettyPhoto.min.js', array( 'jquery' ), '3.1.5', true );
				wp_enqueue_style( 'woocommerce_prettyPhoto_css', $assets_path . 'css/prettyPhoto.css' );
			}

			$version = version_compare( preg_replace( '/-beta-([0-9]+)/', '', WC()->version ), '2.3.0', '<' );

            // loader gif
            $loader = apply_filters( 'yith_quick_view_loader_gif', YITH_WCQV_ASSETS_URL . '/image/qv-loader.gif' );

            // Allow user to load custom style and scripts
            do_action( 'yith_quick_view_custom_style_scripts' );

			wp_localize_script( 'yith-wcqv-frontend', 'yith_qv', array (
					'ajaxurl'           => admin_url( 'admin-ajax.php', 'relative' ),
					'loader'            => $loader,
					'is2_2'             => $version,
                    'lang'              => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : ''
				)
			);

			return true;
		}

		/**
		 * Ajax action to load product in quick view
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_load_product_quick_view_ajax() {

			if ( ! isset( $_REQUEST['product_id'] ) ) {
				die();
			}

			global $sitepress;

			$product_id = intval( $_REQUEST['product_id'] );

            /**
             * WPML Suppot:  Localize Ajax Call
             */
            $lang = isset( $_REQUEST['lang'] ) ? $_REQUEST['lang'] : '';
            if( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
                $sitepress->switch_lang( $lang, true );
            }

			// set the main wp query for the product
			wp( 'p=' . $product_id . '&post_type=product' );

			// remove product thumbnails gallery
			remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

			// change template for variable products
			if ( isset( $GLOBALS['yith_wccl'] ) ) {
				$GLOBALS['yith_wccl']->obj = new YITH_WCCL_Frontend( YITH_WCCL_VERSION );
				$GLOBALS['yith_wccl']->obj->override();
			}

			ob_start();

			// load content template
			wc_get_template( 'yith-quick-view-content.php', array(), '', YITH_WCQV_DIR . 'templates/' );

			echo ob_get_clean();

			die();
		}

		/**
		 * Load quick view template
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_quick_view() {
			$this->yith_woocommerce_quick_view();
			wc_get_template( 'yith-quick-view.php', array(), '', YITH_WCQV_DIR . 'templates/' );
		}

		/**
		 * Load wc action for quick view product template
		 *
		 * @access public
		 * @return void
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function yith_quick_view_action_template() {

			// Image
			add_action( 'yith_wcqv_product_image', 'woocommerce_show_product_sale_flash', 10 );
			add_action( 'yith_wcqv_product_image', 'woocommerce_show_product_images', 20 );

			// Summary
			add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_title', 5 );
			add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_rating', 10 );
			add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_price', 15 );
			add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_excerpt', 20 );
			add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
			add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );
		}

		/**
		 * Get Quick View button label
		 *
		 * @author Francesco Licandro
		 * @since 1.2.0
		 * @return string
		 */
		public function get_button_label(){
			$label = get_option( 'yith-wcqv-button-label' );
			$label = call_user_func( '__' , $label, 'yith-woocommerce-quick-view' );

			return apply_filters( 'yith_wcqv_button_label', esc_html( $label ) );
		}

		/**
		 * Quick View shortcode button
		 *
		 * @access public
		 * @since 1.0.7
		 * @param array $atts
		 * @return string
		 * @author Francesco Licandro
		 */
		public function quick_view_shortcode( $atts ) {

			$atts = shortcode_atts(array(
				'product_id' => 0,
				'label'		 => ''
			), $atts );

			extract( $atts );

			return $this->yith_add_quick_view_button( intval( $product_id ), $label, true );
		}

        /**
         * Check if is quick view
         *
         * @access public
         * @return bool
         * @since 1.3.1
         * @author Francesco Licandro <francesco.licandro@yithemes.com>
         */
        public function yith_is_quick_view() {
            return ( defined('DOING_AJAX') && DOING_AJAX && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'yith_load_product_quick_view' ) ? true : false;
        }

        /**
         * Avoid redirect to single product page on add to cart action in quick view
         *
         * @since 1.3.1
         * @author Francesco Licandro
         * @param string $value
         * @return string
         */
        public function avoid_redirect_to_single_page( $value ){
            if( $this->yith_is_quick_view() )  {
                return '';
            }
            return $value;
        }
	}
}
/**
 * Unique access to instance of YITH_WCQV_Frontend class
 *
 * @return \YITH_WCQV_Frontend
 * @since 1.0.0
 */
function YITH_WCQV_Frontend(){
	return YITH_WCQV_Frontend::get_instance();
}
