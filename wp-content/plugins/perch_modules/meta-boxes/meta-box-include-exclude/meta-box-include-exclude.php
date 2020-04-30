<?php
/**
 * Plugin Name: Meta Box Include Exclude
 * Plugin URI: https://metabox.io/plugins/meta-box-include-exclude/
 * Description: Easily show/hide meta boxes by ID, page template, taxonomy or custom defined function.
 * Version: 1.0.10
 * Author: MetaBox.io
 * Author URI: https://metabox.io
 * License: GPL2+
 *
 * @package Meta Box
 * @subpage Meta Box Include Exclude
 */

if ( defined( 'ABSPATH' ) && ! class_exists( 'MB_Include_Exclude' ) ) {
	require plugin_dir_path( __FILE__ ) . 'class-mb-include-exclude.php';
	add_filter( 'rwmb_show', array( 'MB_Include_Exclude', 'check' ), 10, 2 );
}
