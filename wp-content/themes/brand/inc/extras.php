<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Brand
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function brand_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_front_page() ) {
		if ( ( function_exists('has_custom_header') && has_custom_header() ) || has_header_image() ) {
			$classes[] = 'has-front-page-custom-header';
		}
	}

	return $classes;
}

add_filter( 'body_class', 'brand_body_classes' );

/**
 * Adds custom classes to the navigation wrapper.
 * @since 1.0
 */
add_filter( 'brand_nav_wrapper_class', 'brand_nav_wrapper_classes');
function brand_nav_wrapper_classes( $classes='' )
{

	// Get theme options
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
	$nav_width = $brand_settings['container_type'];

	if ( $nav_width === 'boxed' ) :
		$classes[] = 'container';
	endif;

	return $classes;
}

/**
 * Adds custom classes to the header wrapper.
 * @since 1.0
 */
add_filter( 'brand_header_wrapper_class', 'brand_header_wrapper_classes');
function brand_header_wrapper_classes( $classes='' )
{

	// Get theme options
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	$header_width = $brand_settings['container_type'];

	if ( $header_width === 'boxed' ) :
		$classes[] = 'container';
	endif;

	return $classes;
}

/**
 * Adds custom classes to #content.
 * @since 1.8.1
 */
add_filter( 'brand_content_class', 'brand_content_classes');
function brand_content_classes( $classes='' )
{

	// Get theme options
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	$content_width = $brand_settings['container_type'];

	if ( $content_width === 'boxed' ) :
		$classes[] = 'container';
	endif;

	return $classes;
}

/**
 * Adds custom classes to the footer container.
 * @since 1.0
 */
add_filter( 'brand_footer_class', 'brand_footer_classes');
function brand_footer_classes( $classes='' )
{

	// Get theme options
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	$footer_width = $brand_settings['container_type'];

	if ( $footer_width === 'boxed' ) :
		$classes[] = 'container';
	endif;

	return $classes;
}
