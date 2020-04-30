<?php
/**
 * Uninstall plugin
 */

// If uninstall not called from WordPress exit
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

//change to standard select type custom attributes
$table = $wpdb->prefix . 'woocommerce_attribute_taxonomies';
$update = "UPDATE `$table` SET `attribute_type` = 'select' WHERE `attribute_type` != 'text'";
$wpdb->query( $update );