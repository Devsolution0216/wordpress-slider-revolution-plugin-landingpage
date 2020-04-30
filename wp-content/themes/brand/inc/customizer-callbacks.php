<?php
/**
 * Custom callbacks to show or hide controls.
 *
 *
 * @package Brand
 */

 function is_custom_posts_colors() {
	 // Get brand blog options.
	 $brand_settings = wp_parse_args(
		 get_option( 'brand_settings', array() ),
		 brand_get_default_blog()
	 );
	 if( 'masonry' === $brand_settings['posts_listing_style'] ) {
		 return true;
	 }
	 return false;

 }
