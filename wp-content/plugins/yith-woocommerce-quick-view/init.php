<?php
/**
* Plugin Name: YITH WooCommerce Quick View
* Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-quick-view
* Description: The <code><strong>YITH WooCommerce Quick View</strong></code> plugin allows your customers to have a quick look about products. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
* Version: 1.3.18
* Author: YITH
* Author URI: https://yithemes.com/
* Text Domain: yith-woocommerce-quick-view
* Domain Path: /languages/
* WC requires at least: 3.7
* WC tested up to: 3.9
*
* @author YITH
* @package YITH WooCommerce Quick View
* @version 1.3.18
*/
/*  Copyright 2015-2019 - YITH (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_wcqv_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Quick View is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-quick-view' ); ?></p>
	</div>
	<?php
}


function yith_wcqv_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Quick View while you are using the premium one.', 'yith-woocommerce-quick-view' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( ! defined( 'YITH_WCQV_VERSION' ) ){
	define( 'YITH_WCQV_VERSION', '1.3.18' );
}

if ( ! defined( 'YITH_WCQV_FREE_INIT' ) ) {
	define( 'YITH_WCQV_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WCQV_INIT' ) ) {
    define( 'YITH_WCQV_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WCQV' ) ) {
	define( 'YITH_WCQV', true );
}

if ( ! defined( 'YITH_WCQV_FILE' ) ) {
	define( 'YITH_WCQV_FILE', __FILE__ );
}

if ( ! defined( 'YITH_WCQV_URL' ) ) {
	define( 'YITH_WCQV_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_WCQV_DIR' ) ) {
	define( 'YITH_WCQV_DIR', plugin_dir_path( __FILE__ )  );
}

if ( ! defined( 'YITH_WCQV_TEMPLATE_PATH' ) ) {
	define( 'YITH_WCQV_TEMPLATE_PATH', YITH_WCQV_DIR . 'templates' );
}

if ( ! defined( 'YITH_WCQV_ASSETS_URL' ) ) {
	define( 'YITH_WCQV_ASSETS_URL', YITH_WCQV_URL . 'assets' );
}

if ( ! defined( 'YITH_WCQV_SLUG' ) ) {
    define( 'YITH_WCQV_SLUG', 'yith-woocommerce-quick-view' );
}

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WCQV_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YITH_WCQV_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_WCQV_DIR  );

function yith_wcqv_init() {

	load_plugin_textdomain( 'yith-woocommerce-quick-view', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

	// Load required classes and functions
	require_once('includes/class.yith-wcqv.php');

	// Let's start the game!
	YITH_WCQV();
}
add_action( 'yith_wcqv_init', 'yith_wcqv_init' );


function yith_wcqv_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_wcqv_install_woocommerce_admin_notice' );
	}
	elseif ( defined( 'YITH_WCQV_PREMIUM' ) ) {
		add_action( 'admin_notices', 'yith_wcqv_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	else {
		do_action( 'yith_wcqv_init' );
	}
}
add_action( 'plugins_loaded', 'yith_wcqv_install', 11 );