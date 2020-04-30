<?php
if ( ! function_exists( 'shiftkey_get_typography_font_options' ) ) :
// template default fonts	
function shiftkey_get_typography_font_options(){
	
	$fonts = array(
		'Roboto:300,400,500,700,900',
		'Noto Sans TC:300,400,500,700,900',
	);

	return $fonts;
}
endif;

if ( ! function_exists( 'shiftkey_fonts_url' ) ) :
/**
 * Register Google fonts for shiftkey.
 *
 * @return string Google fonts URL for the theme.
 */
function shiftkey_fonts_url() {
	$fonts_url = '';
	$fonts     = array();

	/*
	 * Translators: If there are characters in your language that are not supported
	 */
	$fonts = shiftkey_get_typography_font_options();
	

	$subsets   = 'latin,latin-ext';
	$subset = 'no-subset';

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 */
function shiftkey_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'shiftkey_javascript_detection', 0 );

// Register Style
function shiftkey_styles() {

	wp_register_style( 'shiftkey-google-fonts', shiftkey_fonts_url(), array(), null );
	wp_enqueue_style( 'shiftkey-google-fonts' );
	

	
	if( is_rtl() ){
		wp_register_style( 'bootstrap-rtl', SHIFTKEY_URI. '/rtl/bootstrap-rtl.min.css', false, '1.0' );
		wp_enqueue_style( 'bootstrap-rtl' );
	}else{
		wp_enqueue_style( 'bootstrap', SHIFTKEY_URI. '/css/bootstrap.min.css', false, '4.0.0' );
	}
	wp_enqueue_style( 'fa-svg-with-js', SHIFTKEY_URI. '/css/fa-svg-with-js.css', false, '1.0.0' );
	wp_enqueue_style( 'flaticon' , SHIFTKEY_URI. '/css/flaticon.css', false, '1.0.0' );
	wp_enqueue_style( 'fontawesome' );
	wp_enqueue_style( 'magnific-popup', SHIFTKEY_URI. '/css/magnific-popup.css', false, '1.0.0' );		
	wp_enqueue_style( 'slick', SHIFTKEY_URI. '/css/slick.css', false, '1.0.0' );		
	wp_enqueue_style( 'slick-theme', SHIFTKEY_URI. '/css/slick-theme.css', false, '1.0.0' );		
	wp_enqueue_style( 'shiftkey-flexslider', SHIFTKEY_URI. '/css/flexslider.css', false, '1.0.0' );
	wp_enqueue_style( 'owl-carousel', SHIFTKEY_URI. '/css/owl.carousel.min.css', false, '1.0.0' );
	wp_enqueue_style( 'owl-theme-default', SHIFTKEY_URI. '/css/owl.theme.default.min.css', false, '1.0.0' );
	wp_deregister_style( 'animate' );	
	wp_register_style( 'animate', SHIFTKEY_URI. '/css/animate.css', false, '1.0.0' );	
	wp_enqueue_style( 'selectize-bootstrap4', SHIFTKEY_URI. '/css/selectize.bootstrap4.css', false, '1.0.0' );
	wp_enqueue_style( 'shiftkey-spinner', SHIFTKEY_URI. '/css/spinner.css', false, '1.0.0' );
	
	if( function_exists('is_woocommerce') ){				
    	wp_enqueue_style( 'shiftkey-woocommerce', get_theme_file_uri( '/css/woocommerce.css' ), array('woocommerce-general'), SHIFTKEY_VERSION );
	}

	
	wp_enqueue_style( 'shiftkey-default-style', SHIFTKEY_URI. '/css/style.css', false, SHIFTKEY_VERSION );
	wp_enqueue_style( 'shiftkey-style', SHIFTKEY_URI. '/style.css', false, SHIFTKEY_VERSION );
	if( is_rtl() ){
		wp_enqueue_style( 'shiftkey-styles-rtl', SHIFTKEY_URI. '/rtl/style-rtl.css', array('shiftkey-style'), SHIFTKEY_VERSION );		
	}
	
	wp_enqueue_style( 'shiftkey-responsive', SHIFTKEY_URI. '/css/responsive.css', array('shiftkey-style'), SHIFTKEY_VERSION );

	

	$shiftkey_layout_style = shiftkey_get_option( 'shiftkey_layout_style', 'semirounded' );
	if( $shiftkey_layout_style != 'semirounded' ){
		wp_enqueue_style( 'shiftkey-layout-'.$shiftkey_layout_style, SHIFTKEY_URI. '/css/shiftkey-'.$shiftkey_layout_style.'.css', array('shiftkey-style'), SHIFTKEY_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'shiftkey_styles' );


/**
 * Output an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the Customizer
 * preview.
 *
 */
function shiftkey_inline_css_style() {	
	wp_add_inline_style( 'shiftkey-style', shiftkey_get_dynamic_header_css() );	
  	wp_add_inline_style( 'shiftkey-default-style', shiftkey_dynamic_general_style_css() );
  	wp_add_inline_style( 'bootstrap', shiftkey_bootstrap_style_css() );  
  	if(function_exists('is_woocommerce')){
    	wp_add_inline_style( 'shiftkey-woocommerce', shiftkey_woocommerce_general_style_css() );
  	}
}
add_action( 'wp_enqueue_scripts', 'shiftkey_inline_css_style' );


// Register Script
function shiftkey_scripts() {
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// google map scripts
	wp_register_script( 'shiftkey-map', get_theme_file_uri( '/js/shiftkey-map.js' ), array( 'jquery' ), '1.0.0', true );	
	$key = shiftkey_get_option( 'google_map_api', '' );
	$key = ($key != '')? '&key='.esc_attr($key) : '';
	wp_register_script( 'shiftkey-googleapis', '//maps.googleapis.com/maps/api/js?callback=shiftkeyinitMap'.$key, array( 'jquery', 'shiftkey-map' ), '1.0.0', true );	
	
	// Jquery library
	
	wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/js/bootstrap.min.js' ), array( 'jquery' ), '1.0.0', true ); 	
	
	wp_enqueue_script( 'html5shiv', get_theme_file_uri( '/js/html5shiv.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'jquery-vide', get_theme_file_uri( '/js/jquery.vide.min.js' ), array('jquery'),'1.0.0',true );
	
	wp_enqueue_script( 'fa-v4-shims', get_theme_file_uri( '/js/v4-shims.js' ), array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'modernizr-custom', get_theme_file_uri( '/js/modernizr.custom.js' ), array('jquery'),'1.0.0',true );	
	wp_enqueue_script( 'jquery-easing', get_theme_file_uri( '/js/jquery.easing.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'jquery-stellar', get_theme_file_uri( '/js/jquery.stellar.min.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'jquery-scrollto', get_theme_file_uri( '/js/jquery.scrollto.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'jquery-appear', get_theme_file_uri( '/js/jquery.appear.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'jquery-superslides', get_theme_file_uri( '/js/jquery.superslides.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'vidbg', get_theme_file_uri( '/js/vidbg.min.js' ), array('jquery'),'0.5.1',true );
	wp_enqueue_script( 'isotope-pkgd', get_theme_file_uri( '/js/isotope.pkgd.min.js' ), array('jquery'),'1.0.0',true );
	
	wp_enqueue_script( 'jquery-flexslider', get_theme_file_uri( '/js/jquery.flexslider.js' ), array('jquery'),'1.0.0',true );	
	wp_enqueue_script( 'owl-carousel', get_theme_file_uri( '/js/owl.carousel.min.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'slick', get_theme_file_uri( '/js/slick.min.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'selectize', get_theme_file_uri( '/js/selectize.min.js' ), array('bootstrap'),'1.0.0',true );
	wp_enqueue_script( 'respond', get_theme_file_uri( '/js/respond.min.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'wow', get_theme_file_uri( '/js/wow.js' ), array('jquery'),'1.0.0',true );
	wp_enqueue_script( 'jquery-magnific-popup', get_theme_file_uri( '/js/jquery.magnific-popup.min.js' ), array('jquery'),'1.0.0',true );
	
	wp_register_script( 'front_enqueue_js', get_theme_file_uri( '/js/front_enqueue_js.js' ), array('jquery'),'1.0.0',true );
	wp_register_script( 'jquery-countdown', get_theme_file_uri( '/js/jquery.countdown.min.js' ), array( 'shiftkey-custom' ), '1.0.0', true);	

	// Shiftkey custom scripts
	wp_enqueue_script( 'shiftkey-custom', get_theme_file_uri(  '/js/custom.js' ), array( 'jquery', 'jquery-masonry', 'masonry', 'imagesloaded' ), SHIFTKEY_VERSION, true );
	 
	// Shiftkey licalize
	$arr = array( 
		'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
		'SHIFTKEY_URI' => esc_url(SHIFTKEY_URI),
		'SHIFTKEY_DIR' => SHIFTKEY_DIR,
		'animation' => shiftkey_get_option( 'shiftkey_animation', 'on' )
		);
	wp_localize_script( 'shiftkey-custom', 'SHIFTKEY', $arr );

}
add_action( 'wp_enqueue_scripts', 'shiftkey_scripts' );


if( function_exists('register_block_type') ): // checked for lower version of WP
// Shiftkey gutenberg button block compability
function shiftkey_gutenberg_block_styles() {
	wp_register_style(
        'shiftkey-fonts',
        shiftkey_fonts_url(),
        array(),
        false
    );

    wp_register_style(
        'button',
        get_theme_file_uri( 'css/buttons/style.css' ),
        array(),
        filemtime( SHIFTKEY_DIR . '/css/buttons/style.css' )
    );

    register_block_type( 'core/button', array(
        'style' => 'button',
    ));

    if( is_admin() ):
	    wp_register_style(
	        'paragraph',
	        get_theme_file_uri( 'css/typography/style.css' ),
	        array( 'shiftkey-fonts', 'wp-editor' ),
	        filemtime( SHIFTKEY_DIR . '/css/typography/style.css' )
	    );

	    register_block_type( 'core/paragraph', array(
	        'style' => 'paragraph',
	    ));
	endif;
}
add_action( 'init', 'shiftkey_gutenberg_block_styles' );
endif;