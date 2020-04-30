<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * You can add an optional custom header image to header.php like so ...
 *
 *	<?php if ( get_header_image() ) : ?>
 *	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
 *		<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
 *  </a>
 *	<?php endif; // End header image check. ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Brand
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses brand_header_style()
 */
function brand_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'brand_custom_header_args', array(
		'default-image'          => get_parent_theme_file_uri( '/assets/images/header.jpg' ),
		'default-text-color'     => '000000',
		'width'                  => '1500',
		'height'                 => '400',
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => '',
		'video'                  => true,
	) ) );
}
add_action( 'after_setup_theme', 'brand_custom_header_setup' );

// Enqueue styles for header image and video
function brand_custom_header_styles() {
	wp_enqueue_style( 'brand-custom-header-styles', get_template_directory_uri() . '/assets/css/brand-custom-header.css' );
}
add_action( 'wp_enqueue_scripts', 'brand_custom_header_styles' );
