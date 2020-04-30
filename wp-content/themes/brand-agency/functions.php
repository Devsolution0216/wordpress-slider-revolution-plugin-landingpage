<?php
add_action( 'wp_enqueue_scripts', 'brand_agency_theme_enqueue_styles' );
function brand_agency_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

function brand_agency_change_slides_number() {
	return 2;
}
add_filter( 'brand_slides_number', 'brand_agency_change_slides_number' );

if ( !function_exists( 'brand_agency_creative_defaults' ) ) :
add_filter( 'brand_option_defaults','brand_agency_creative_defaults' );
function brand_agency_creative_defaults( $creative_defaults )
{
	$creative_defaults[ 'mobile_menu_style' ] = 'fullscreen';
	$creative_defaults[ 'nav_search' ] = 'disabled';
	$creative_defaults[ 'link_color' ] = '#dd7a7a';
	$creative_defaults[ 'link_hover_color' ] = '#dd4b4b';
	$creative_defaults[ 'featured_position' ] = 'inside_header';

	// Slider
	$slides_defaults['brand_slide_image1'] = get_stylesheet_directory_uri() . '/assets/img/slider/img1.jpg';
	$slides_defaults['brand_slide_title1'] = __( 'A New Fabulous Theme', 'brand-agency' );
	$slides_defaults['brand_slide_subtitle1'] = __( 'Take a Look', 'brand-agency' );
	$slides_defaults['brand_slide_button_text1'] = __( 'START NOW', 'brand-agency' );
	$slides_defaults['brand_slide_button_url1'] = '#content';
	$slides_defaults['brand_slide_text_color1'] = '#ffffff';
	$slides_defaults['brand_slide_image2'] = get_stylesheet_directory_uri() . '/assets/img/slider/img2.jpg';
	$slides_defaults['brand_slide_title2'] = __( 'We Create Beautiful Sites', 'brand-agency' );
	$slides_defaults['brand_slide_subtitle2'] = '';
	$slides_defaults['brand_slide_button_text2'] = __( 'GET IN TOUCH', 'brand-agency' );
	$slides_defaults['brand_slide_button_url2'] = '#content';
	$slides_defaults['brand_slide_text_color2'] = '#666666';

	$creative_defaults = array_merge( $creative_defaults, $slides_defaults );

	return $creative_defaults;
}
endif;

if ( !function_exists( 'brand_agency_creative_color_defaults' ) ) :
add_filter( 'brand_default_color_options','brand_agency_creative_color_defaults' );
function brand_agency_creative_color_defaults( $creative_color_defaults )
{
	$creative_color_defaults[ 'header_title_color' ] = '#666666';
	$creative_color_defaults[ 'navigation_bg_color' ] = '#eeeeee';
	$creative_color_defaults[ 'navigation_bg_opacity' ] = '0.8';
	$creative_color_defaults[ 'navigation_text_color' ] = '#666666';
	$creative_color_defaults[ 'navigation_text_hover_color' ] = '#c1c1c1';
	$creative_color_defaults[ 'navigation_text_current_color' ] = '#dd7a7a';
	$creative_color_defaults[ 'mob_navigation_bg_color' ] = '#ededed';
	$creative_color_defaults[ 'mob_navigation_text_color' ] = '#666666';
	$creative_color_defaults[ 'mob_navigation_text_hover_color' ] = '#c1c1c1';
	$creative_color_defaults[ 'mob_navigation_text_current_color' ] = '#dd7a7a';
	$creative_color_defaults[ 'mob_widget_text_color' ] = '#666666';
	$creative_color_defaults[ 'form_border_color_focus' ] = '#dd7a7a';
	$creative_color_defaults[ 'form_button_background_color' ] = '#dd7a7a';
	$creative_color_defaults[ 'form_button_background_color_hover' ] = '#dd4b4b';
	$creative_color_defaults[ 'entry_meta_link_hover_color' ] = '#dd4b4b';
	$creative_color_defaults[ 'footer_background_color' ] = '#eeeeee';
	$creative_color_defaults[ 'footer_text_color' ] = '#666666';
	$creative_color_defaults[ 'footer_link_color' ] = '#dd7a7a';
	$creative_color_defaults[ 'footer_link_hover_color' ] = '#dd4b4b';
	$creative_color_defaults[ 'footer_widget_link_color' ] = '#dd7a7a';
	$creative_color_defaults[ 'footer_widget_link_hover_color' ] = '#dd4b4b';

	return $creative_color_defaults;
}
endif;

if ( !function_exists( 'brand_agency_creative_font_defaults' ) ) :
add_filter( 'brand_font_option_defaults','brand_agency_creative_font_defaults' );
function brand_agency_creative_font_defaults( $creative_font_defaults )
{
	$creative_font_defaults[ 'font_body' ] = 'Oswald';
	$creative_font_defaults[ 'mob_navigation_font_transform' ] = 'uppercase';
	$creative_font_defaults[ 'mobile_mob_navigation_font_size' ] = '28';

	return $creative_font_defaults;
}
endif;
