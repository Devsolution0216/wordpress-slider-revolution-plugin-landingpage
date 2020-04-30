<?php
/**
 * Plugin Name: Variation Swatcher for WooCommerce
 * Plugin URI: http://themealien.com/wordpress-plugin/woocommerce-variation-swatches
 * Description: An extension of WooCommerce to make variable products be more beauty and friendly to users.
 * Version: 1.0.6
 * Author: ThemeAlien
 * Author URI: http://themealien.com/
 * Requires at least: 4.5
 * Tested up to: 5.3
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wcvs
 * Domain Path: /languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 3.9.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define TAWC_DEALS_PLUGIN_FILE
if ( ! defined( 'TAWC_VS_PLUGIN_FILE' ) ) {
	define( 'TAWC_VS_PLUGIN_FILE', __FILE__ );
}

if ( ! function_exists( 'ta_wc_variation_swatches_wc_notice' ) ) {
	/**
	 * Display notice in case of WooCommerce plugin is not activated
	 */
	function ta_wc_variation_swatches_wc_notice() {
		?>

		<div class="error">
			<p><?php esc_html_e( 'Variation Swatcher for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'wcvs' ); ?></p>
		</div>

		<?php
	}
}

if ( ! function_exists( 'ta_wc_variation_swatches_pro_notice' ) ) {
	/**
	 * Display notice in case of WooCommerce plugin is not activated
	 */
	function ta_wc_variation_swatches_pro_notice() {
		?>

		<div class="error">
			<p><?php esc_html_e( 'No need to activate the free version of Variation Swatcher for WooCommerce plugin while the pro version is activated.', 'wcvs' ); ?></p>
		</div>

		<?php
	}
}

if ( ! function_exists( 'ta_wc_variation_swatches_constructor' ) ) {
	/**
	 * Construct plugin when plugins loaded in order to make sure WooCommerce API is fully loaded
	 * Check if WooCommerce is not activated then show an admin notice
	 * or create the main instance of plugin
	 */
	function ta_wc_variation_swatches_constructor() {
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'ta_wc_variation_swatches_wc_notice' );
		} elseif ( defined( 'TAWC_VS_PRO' ) ) {
			add_action( 'admin_notices', 'ta_wc_variation_swatches_pro_notice' );
			deactivate_plugins( plugin_basename( __FILE__ ) );
		} else {
			require_once plugin_dir_path( __FILE__ ) . '/includes/class-variation-swatches.php';
			TA_WCVS();
		}
	}
}

if ( ! function_exists( 'ta_wc_variation_swatches_deactivate' ) ) {
	/**
	 * Deactivation hook.
	 * Backup all unsupported types of attributes then reset them to "select".
	 *
	 * @param bool $network_deactivating Whether the plugin is deactivated for all sites in the network
	 *                                   or just the current site. Multisite only. Default is false.
	 */
	function ta_wc_variation_swatches_deactivate( $network_deactivating ) {
		global $wpdb;

		$blog_ids         = array( 1 );
		$original_blog_id = 1;
		$network          = false;

		if ( is_multisite() && $network_deactivating ) {
			$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
			$original_blog_id = get_current_blog_id();
			$network          = true;
		}

		foreach ( $blog_ids as $blog_id ) {
			if ( $network ) {
				switch_to_blog( $blog_id );
			}

			// Backup attribute types.
			$attributes         = wc_get_attribute_taxonomies();
			$default_types      = array( 'text', 'select' );
			$ta_wcvs_attributes = array();

			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attribute ) {
					if ( ! in_array( $attribute->attribute_type, $default_types ) ) {
						$ta_wcvs_attributes[ $attribute->attribute_id ] = $attribute;
					}
				}
			}

			if ( ! empty( $ta_wcvs_attributes ) ) {
				set_transient( 'tawcvs_attribute_taxonomies', $ta_wcvs_attributes );
				delete_transient( 'wc_attribute_taxonomies' );
				update_option( 'tawcvs_backup_attributes_time', time() );
			}

			// Reset attributes.
			if ( ! empty( $ta_wcvs_attributes ) ) {
				foreach ( $ta_wcvs_attributes as $id => $attribute ) {
					$wpdb->update(
						$wpdb->prefix . 'woocommerce_attribute_taxonomies',
						array( 'attribute_type' => 'select' ),
						array( 'attribute_id' => $id ),
						array( '%s' ),
						array( '%d' )
					);
				}
			}

			// Delete the option of restoring time.
			delete_option( 'tawcvs_restore_attributes_time' );
		}

		if ( $network ) {
			switch_to_blog( $original_blog_id );
		}
	}
}

add_action( 'plugins_loaded', 'ta_wc_variation_swatches_constructor', 20 );
register_deactivation_hook( __FILE__, 'ta_wc_variation_swatches_deactivate' );
