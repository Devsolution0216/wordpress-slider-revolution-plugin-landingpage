<?php
/**
 * Plugin Name: Email Subscribers & Newsletters
 * Plugin URI: https://www.icegram.com/
 * Description: Add subscription forms on website, send HTML newsletters & automatically notify subscribers about new blog posts once it is published.
 * Version: 4.4.0
 * Author: Icegram
 * Author URI: https://www.icegram.com/
 * Requires at least: 3.9
 * Tested up to: 5.3.2
 * Text Domain: email-subscribers
 * Domain Path: /lite/languages/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Copyright (c) 2016-2019 Icegram
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Earlier we were using IG_ES_FEEDBACK_VERSION constant
 * We have made some changes into 1.0.11 which we want to use.
 * So, avoid conflicts, we have define new constant
 * Now onward, we will use this constant
 *
 * @since 4.3.0
 */
if ( ! defined( 'IG_ES_FEEDBACK_TRACKER_VERSION' ) ) {
	define( 'IG_ES_FEEDBACK_TRACKER_VERSION', '1.2.0' );
}


global $ig_es_tracker;
/* ***************************** Initial Compatibility Work (Start) ******************* */

/* =========== Do not edit this code unless you know what you are doing ========= */

/*
 * Note: We are not using ES_PLUGIN_DIR constant at this moment because there are chances
 * It might be defined from older version of ES
 */
require plugin_dir_path( __FILE__ ) . 'lite/includes/feedback/class-ig-tracker.php';
$ig_es_tracker = 'IG_Tracker_V_' . str_replace( '.', '_', IG_ES_FEEDBACK_TRACKER_VERSION );

if ( ! function_exists( 'ig_es_show_upgrade_pro_notice' ) ) {
	/**
	 * Show ES Premium Upgrade Notice
	 *
	 * @since 4.3.0
	 */
	function ig_es_show_upgrade_pro_notice() {
		$url = admin_url( "plugins.php?plugin_status=upgrade" );
		?>
        <div class="notice notice-error">
            <p><?php echo sprintf( __( 'You are using older version of <strong>Email Subscribers Premium</strong> plugin. It won\'t work because it needs plugin to be updated. Please update %s plugin.', 'email-subscribers' ),
					'<a href="' . $url . '" target="_blank">' . __( 'Email Subscribers Premium', 'email-subscribers' ) . '</a>' ); ?></p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'deactivate_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$is_premium = false;
if ( 'email-subscribers-premium.php' === basename( __FILE__ ) ) {
	$is_premium = true;
}

if ( $is_premium ) {
	// We don't need ES Lite version As we are already running ES Premium 4.3.0+
	// which includes ES Lite code.
	// So, deactivate it
	deactivate_plugins( 'email-subscribers/email-subscribers.php', true );
} else {
	/**
	 * Steps:
	 * - Check Whether ES Premium Installed
	 * - If It's installed & It's < 4.3.0 => Show Upgrade Notice
	 * - If It's installed & It's >= 4.3.0 => return
	 */

	//- If It's installed & It's < 4.3.0 => Show Upgrade Notice
	$all_plugins = $ig_es_tracker::get_plugins( 'all', true );

	$es_pro_plugin         = 'email-subscribers-premium/email-subscribers-premium.php';
	$es_pro_plugin_version = ! empty( $all_plugins[ $es_pro_plugin ] ) ? $all_plugins[ $es_pro_plugin ]['version'] : '';

	if ( ! empty( $es_pro_plugin_version ) ) {

		// Is Pro active?
		$is_pro_active = $all_plugins[ $es_pro_plugin ]['is_active'];

		// Free >= 4.3.0 && Pro < 4.3.0
		if ( version_compare( $es_pro_plugin_version, 4.3, '<' ) ) {

			// Show Upgrade Notice if It's Admin Screen.
			if ( is_admin() ) {
				add_action( 'admin_head', 'ig_es_show_upgrade_pro_notice', PHP_INT_MAX );
			}

		} elseif ( $is_pro_active && version_compare( $es_pro_plugin_version, 4.3, '>=' ) ) {
			return;
		}
	}
}
/* ***************************** Initial Compatibility Work (End) ******************* */

if ( ! defined( 'ES_PLUGIN_VERSION' ) ) {
	define( 'ES_PLUGIN_VERSION', '4.4.0' );
}

// Plugin Folder Path.
if ( ! defined( 'ES_PLUGIN_DIR' ) ) {
	define( 'ES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ES_PLUGIN_BASE_NAME' ) ) {
	define( 'ES_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ES_PLUGIN_FILE' ) ) {
	define( 'ES_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'ES_PLUGIN_URL' ) ) {
	define( 'ES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! function_exists( 'activate_email_subscribers' ) ) {
	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-email-subscribers-activator.php
	 */
	function activate_email_subscribers() {
		require_once ES_PLUGIN_DIR . 'lite/includes/class-email-subscribers-activator.php';
		Email_Subscribers_Activator::activate();
		add_option( 'email_subscribers_do_activation_redirect', true );
	}
}

if ( ! function_exists( 'deactivate_email_subscribers' ) ) {
	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-email-subscribers-deactivator.php
	 */
	function deactivate_email_subscribers() {
		require_once ES_PLUGIN_DIR . 'lite/includes/class-email-subscribers-deactivator.php';
		Email_Subscribers_Deactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activate_email_subscribers' );
register_deactivation_hook( __FILE__, 'deactivate_email_subscribers' );

add_action( 'admin_init', 'email_subscribers_redirect' );

if ( ! function_exists( 'email_subscribers_redirect' ) ) {
	function email_subscribers_redirect() {
		if ( get_option( 'email_subscribers_do_activation_redirect', false ) ) {
			delete_option( 'email_subscribers_do_activation_redirect' );
			wp_redirect( 'admin.php?page=es_dashboard' );
		}
	}
}

if ( ! function_exists( 'es_subbox' ) ) {
	/**
	 * Show subscription form
	 *
	 * @param null $namefield
	 * @param null $desc
	 * @param null $group
	 */
	function es_subbox( $namefield = null, $desc = null, $group = null ) {

		$atts = array(
			'namefield' => $namefield,
			'desc'      => $desc,
			'group'     => $group
		);

		echo ES_Shortcode::render_es_subscription_shortcode( $atts );
	}
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ES_PLUGIN_DIR . 'lite/includes/class-email-subscribers.php';

if ( ! function_exists( 'ES' ) ) {
	/**
	 * @return Email_Subscribers
	 *
	 * @since 4.2.1
	 */
	function ES() {
		return Email_Subscribers::instance();
	}
}

// Start ES
ES()->run();