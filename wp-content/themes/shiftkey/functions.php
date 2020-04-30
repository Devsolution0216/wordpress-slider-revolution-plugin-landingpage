<?php
define( 'SHIFTKEY_VERSION', '1.0.6' );
define( 'SHIFTKEY_URI', get_template_directory_uri() );
define( 'SHIFTKEY_DIR', get_template_directory() );

// Set content width value based on the theme's design
if ( ! isset( $content_width ) )
	$content_width = 1170;


if ( ! function_exists('shiftkey_theme_features') ) :
// Register Theme Features
function shiftkey_theme_features()  {

	// Add theme support for Automatic Feed Links
	add_theme_support( 'automatic-feed-links' );	

	// Add theme support for Featured Images
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'shiftkey-800x400-crop', 800, 400, true );
	add_image_size( 'shiftkey-800x600-crop', 800, 600, true );
	add_image_size( 'shiftkey-700x700-crop', 700, 700, true );
	add_image_size( 'shiftkey-600x600-crop', 600, 600, true );
	add_image_size( 'shiftkey-600x--nocrop', 600, '', false );
	add_image_size( 'shiftkey-500x--nocrop', 500, '', false );	
	add_image_size( 'shiftkey-400x400-crop', 400, 400, true );	
	add_image_size( 'shiftkey-400x500-crop', 400, 500, true );	
	add_image_size( 'shiftkey-400x300-crop', 400, 300, true );	
	add_image_size( 'shiftkey-350x270-crop', 350, 270, true );	
	add_image_size( 'shiftkey-400x--nocrop', 400, '', false );		
	add_image_size( 'shiftkey-150x150-crop', 150, 150, true );

	 // Set custom thumbnail dimensions
	set_post_thumbnail_size( 830, 540, true );

	// add theme support for woocommerce
	add_theme_support( 'woocommerce' );

	// Add theme support for Custom Background

	$background_args = array(
		'default-color'          => '#fff',
		'default-image'          => '',
		'default-repeat'         => '',
		'default-position-x'     => ''
	);

	//add_theme_support( 'custom-background', $background_args );


	// Add theme support for HTML5 Semantic Markup
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );


	// Add theme support for document Title tag
	add_theme_support( 'title-tag' );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add theme support for custom CSS in the TinyMCE visual editor
	add_editor_style( 'css/editor-style.css', shiftkey_fonts_url(), 'css/flaticon.css' );

	// Add theme support for Translation
	load_theme_textdomain( 'shiftkey', get_template_directory() . '/languages' );	

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

}

add_action( 'after_setup_theme', 'shiftkey_theme_features' );
endif;


// Register Shiftkey Navigation Menus
if ( !function_exists( 'shiftkey_navigation_menus' ) ):
function shiftkey_navigation_menus() {
	$locations = array(
		'primary' => esc_attr__( 'Header Menu', 'shiftkey' )
	);
	register_nav_menus( $locations );
}

add_action( 'init', 'shiftkey_navigation_menus' );
endif; 

// Shiftkey default preset color
if ( !function_exists( 'shiftkey_default_color' ) ):
function shiftkey_default_color(){
	return 'theme';
}
endif; 

// Theme name for plugin compability
if ( !function_exists( 'shiftkey_current_theme_name' ) ):
function shiftkey_current_theme_name( $theme_name ){
	$my_theme = wp_get_theme( 'shiftkey' );
	if ( $my_theme->exists() ){
		$theme_name = $my_theme->get( 'Name' );
	}
	return 	$theme_name;
}
endif;
add_filter( 'perch_modules/current_theme', 'shiftkey_current_theme_name' );


// plugin extension for Shiftkey
if ( !function_exists( 'shiftkey_meta_boxes_extension' ) ):
function shiftkey_meta_boxes_extension( $args ){
	$new_args = array(
		'mb-admin-columns',
		'mb-relationships',
		'mb-settings-page',
		'meta-box-conditional-logic', 
		'meta-box-group',
		'meta-box-include-exclude', 
		'meta-box-show-hide', 
		'meta-box-tooltip',
		'meta-box-tabs', 
		'meta-box-yoast-seo'
	);

	$args = array_merge( $args, $new_args );

	return $args;
}
endif;
add_filter( 'perch_modules/meta_boxes_extension', 'shiftkey_meta_boxes_extension' );


//required plugins
require_once( SHIFTKEY_DIR . '/tgmpa/shiftkey-plugins.php' );

//admin functions 
require_once( SHIFTKEY_DIR. '/admin/admin-functions.php');

//redux framework config
require_once( SHIFTKEY_DIR . '/admin/framework/redux/ReduxFramework.config.php' );

//frontend functions
require_once( SHIFTKEY_DIR. '/includes/template-functions.php');