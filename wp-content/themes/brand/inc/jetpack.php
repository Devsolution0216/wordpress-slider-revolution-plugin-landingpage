<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.me/
 *
 * @package Brand
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.me/support/infinite-scroll/
 * See: https://jetpack.me/support/responsive-videos/
 */
function brand_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'primary-content',
		'render'    => 'brand_infinite_scroll_render',
		'footer'    => false,
	) );

	// Adds theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'brand_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function brand_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
		    get_template_part( 'template-parts/content', 'search' );
		else :
		    get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}
}

/**
 * Checks if a Jetpack module is active.
 *
 *
 * @since 1.1.0
 *
 * @param string $module Module to check.
 * @return boolean
 */
 function brand_is_jet_module_active( $module ) {
	 if( class_exists( 'Jetpack' ) && Jetpack::is_module_active( $module ) ) {
		 return true;
	 }
	 return false;
 }
