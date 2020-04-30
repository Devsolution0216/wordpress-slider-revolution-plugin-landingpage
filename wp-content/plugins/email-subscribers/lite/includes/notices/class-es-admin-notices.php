<?php

defined( 'ABSPATH' ) || exit;

/**
 * ES_Admin_Notices Class.
 */
class ES_Admin_Notices {

	/**
	 * Stores notices.
	 *
	 * @var array
	 */
	private static $notices = array();

	/**
	 * Array of notices - name => callback.
	 *
	 * @var array
	 */
	private static $core_notices = array(
		'update' => 'update_notice'
	);

	/**
	 * Constructor.
	 */
	public static function init() {
		self::$notices = get_option( 'ig_es_admin_notices', array() );

		add_action( 'wp_loaded', array( __CLASS__, 'es_dismiss_admin_notice' ) );
		add_action( 'shutdown', array( __CLASS__, 'store_notices' ) );

		add_action( 'admin_print_styles', array( __CLASS__, 'add_notices' ) );
	}

	/**
	 * Store notices to DB
	 */
	public static function store_notices() {
		update_option( 'ig_admin_notices', self::get_notices() );
	}

	/**
	 * Get notices
	 *
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}

	/**
	 * Remove all notices.
	 */
	public static function remove_all_notices() {
		self::$notices = array();
	}

	/**
	 * Show a notice.
	 *
	 * @param string $name Notice name.
	 */
	public static function add_notice( $name ) {
		self::$notices = array_unique( array_merge( self::get_notices(), array( $name ) ) );
	}

	/**
	 * Remove a notice from being displayed.
	 *
	 * @param string $name Notice name.
	 */
	public static function remove_notice( $name ) {
		self::$notices = array_diff( self::get_notices(), array( $name ) );
		delete_option( 'ig_es_admin_notice_' . $name );
	}

	/**
	 * See if a notice is being shown.
	 *
	 * @param string $name Notice name.
	 *
	 * @return boolean
	 */
	public static function has_notice( $name ) {
		return in_array( $name, self::get_notices(), true );
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		$hide_notice             = ig_es_get_request_data( 'ig-es-hide-notice' );
		$ig_es_hide_notice_nonce = ig_es_get_request_data( '_ig_es_notice_nonce' );
		if ( isset( $_GET['ig-es-hide-notice'] ) && isset( $_GET['_ig_es_notice_nonce'] ) ) { // WPCS: input var ok, CSRF ok.
			if ( ! wp_verify_nonce( sanitize_key( $ig_es_hide_notice_nonce ), 'ig_es_hide_notices_nonce' ) ) { // WPCS: input var ok, CSRF ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'email-subscribers' ) );
			}

			self::remove_notice( $hide_notice );

			update_user_meta( get_current_user_id(), 'dismissed_' . $hide_notice . '_notice', true );

			do_action( 'ig_es_hide_' . $hide_notice . '_notice' );
		}
	}

	public static function add_notices() {
		$notices = self::get_notices();

		if ( empty( $notices ) ) {
			return;
		}

		if ( ! ES()->is_es_admin_screen() ) {
			return;
		}

		foreach ( $notices as $notice ) {
			if ( ! empty( self::$core_notices[ $notice ] ) ) {
				add_action( 'admin_notices', array( __CLASS__, self::$core_notices[ $notice ] ) );
			} else {
				add_action( 'admin_notices', array( __CLASS__, 'output_custom_notices' ) );
			}
		}
	}

	/**
	 * Add a custom notice.
	 *
	 * @param string $name Notice name.
	 * @param string $notice_html Notice HTML.
	 */
	public static function add_custom_notice( $name, $args ) {
		self::add_notice( $name );
		update_option( 'ig_es_custom_admin_notice_' . $name, $args );
	}

	/**
	 * Output any stored custom notices.
	 */
	public static function output_custom_notices() {
		$notices = self::get_notices();

		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				if ( empty( self::$core_notices[ $notice ] ) ) {
					$notice_args     = get_option( 'ig_es_custom_admin_notice_' . $notice );
					$timezone_format = _x( 'Y-m-d', 'timezone date format' );
					$ig_current_date = strtotime( date_i18n( $timezone_format ) );
					if ( $notice_args['include'] ) {
						include_once( $notice_args['include'] );
					}
					if ( ! empty( $notice_args['html'] ) ) {
						echo $notice_args['html'];
					}
					// if ( $notice_html ) {
					// 	include dirname( __FILE__ ) . '/views/html-notice-custom.php';
					// }
				}
			}
		}
	}

	/**
	 * If we need to update, include a message with the update button.
	 */
	public static function update_notice() {

		$latest_version_to_update = ES_Install::get_latest_db_version_to_update();

		if ( version_compare( get_ig_es_db_version(), $latest_version_to_update, '<' ) ) {
			// Database is updating now.
			include dirname( __FILE__ ) . '/views/html-notice-updating.php';

			// Show button to to "Run the updater"
			//include dirname( __FILE__ ) . '/views/html-notice-update.php';

		} else {
			include dirname( __FILE__ ) . '/views/html-notice-updated.php';
		}
	}

	/**
	 * If we need to update, include a message with the update button.
	 */
	public static function es_dismiss_admin_notice() {
		$es_dismiss_admin_notice = ig_es_get_request_data( 'es_dismiss_admin_notice' );
		$option_name             = ig_es_get_request_data( 'option_name' );
		if ( $es_dismiss_admin_notice == '1' && ! empty( $option_name ) ) {
			update_option( 'ig_es_' . $option_name, 'yes', false );
			if ( in_array( $option_name, array( 'redirect_upsale_notice', 'dismiss_upsale_notice', 'dismiss_star_notice', 'star_notice_done' ) ) ) {
				update_option( 'ig_es_' . $option_name . '_date', ig_get_current_date_time(), false );
			}
			if ( $option_name === 'star_notice_done' ) {
				header( "Location: https://wordpress.org/support/plugin/email-subscribers/reviews/" );
				exit();
			}
			if ( $option_name === 'redirect_upsale_notice' ) {
				header( "Location: https://www.icegram.com/email-subscribers-starter-plan-pricing/?utm_source=es&utm_medium=es_upsale_banner&utm_campaign=es_upsale" );
				exit();
			}
			if ( $option_name === 'offer_bfcm_done_2019' || $option_name === 'offer_last_day_bfcm_done_2019' ) {
				$url = "https://www.icegram.com/?utm_source=in_app&utm_medium=es_banner&utm_campaign=" . $option_name;
				header( "Location: {$url}" );
				exit();
			} else {
				$referer = wp_get_referer();
				wp_safe_redirect( $referer );
			}
			exit();
		}

	}


}

ES_Admin_Notices::init();
