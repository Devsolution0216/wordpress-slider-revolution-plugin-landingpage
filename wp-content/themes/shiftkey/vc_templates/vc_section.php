<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $parallax_speed_bg
 * @var $parallax_speed_video
 * @var $content - shortcode content
 * @var $css_animation
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */

$el_class = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = $css_animation = $section_type = $enable_inner = $inner_bg_class = $inner_class = $inner_padding = '';
$section_params = shiftkey_vc_section_settings(true);
foreach ($section_params as $key => $value) {
	${$value['param_name']} = '';
}

$disable_element = '';
$output = $after_output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );


$extra_class = '';

$dark_color_class = shiftkey_default_dark_color_classes();


$type = 'section';
$css_classes = array(	
	$el_class
);

$custom_css_classes = array(
	$bg_class,
	$padding_class,	
);

if( in_array($bg_class, $dark_color_class) ){
	$css_classes[] = 'white-color';	
}

$custom_css_id = '';
if($section_type != '' ){	
	$custom_css_id = isset($atts[$section_type.'_type'])? $section_type.'-'.$atts[$section_type.'_type'] : '';
	$custom_css_classes[] = $section_type .'-section';
}
$custom_css_id = ($custom_css_id != '')? ' id="' . esc_attr( $custom_css_id ) . '"' : '';

if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( vc_shortcode_custom_css_has_property( $css, array(
		'border',
		'background',
	) ) || $video_bg || $parallax
) {
	$css_classes[] = 'vc_section-has-fill';
}

$custom_css_classes[] = $margin_top;
$custom_css_classes[] = $margin_bottom;
$custom_css_classes[] = $padding_top;
$custom_css_classes[] = $padding_bottom;

/* inner class*/

$__classes = array();
if($enable_inner == 'yes'){
	$__classes = array('section-inner-wrap', 'bg-inner', 'division');
	$__classes[] = $inner_margin_top;
	$__classes[] = $inner_margin_bottom;
	$__classes[] = $inner_padding_top;
	$__classes[] = $inner_padding_bottom;
	$__classes[] = $inner_padding_class;
	$__classes[] = $inner_bg_class;
	$__classes[] = $inner_el_class;
	$__classes[] = vc_shortcode_custom_css_class( $css );
	$__classes[] = in_array($inner_bg_class,$dark_color_class)? 'white-color' : '';	
	$__classes[] = ($parallax_image_attachment != 'inherit')? 'bg-'.$parallax_image_attachment : '';
	$css_classes[] = 'enable-bg-inner';
}else{
	$custom_css_classes[] = vc_shortcode_custom_css_class( $css );
	$custom_css_classes[] = ($parallax_image_attachment != 'inherit')? 'bg-'.$parallax_image_attachment : '';
}




$inner_before = $inner_after = '';

$__classes = array_filter($__classes); 
$__attr = array();   
$__attr[] = (!empty($__classes))?'class="'.implode(' ', $__classes).'"' : '';
if($enable_inner == 'yes'){
	$inner_el_id = ( ('' == $inner_el_id) && isset($atts[$section_type.'_type']) )? $section_type.'-'.$atts[$section_type.'_type'].'-txt' : $inner_el_id;
	$__attr[] = 'id="'.sanitize_title(esc_attr($inner_el_id)).'"';
	$__attributes = implode( ' ', $__attr );

	$inner_before = '<div '.$__attributes.'>';
	$inner_after = '</div>';
}



/* end inner*/


$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="false"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="false"';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_section-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_section-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_speed = $parallax_speed_video;
	$parallax_image = $video_bg_url;
	$css_classes[] = 'vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_src = $parallax_image;	
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
	$wrapper_attributes[] = 'data-opacity="' . esc_attr( $parallax_image_opacity ) . '"';
	$wrapper_attributes[] = 'data-size="' . esc_attr( $parallax_image_size ) . '"';
	$wrapper_attributes[] = 'data-width="' . esc_attr( $parallax_width ) . '"';

	if($parallax_image_position){
		$wrapper_attributes[] = 'data-position="' . esc_attr( $parallax_image_position ) . '"';
	}

	if($parallax_image_repeat){
		$wrapper_attributes[] = 'data-repeat="' . esc_attr( $parallax_image_repeat ) . '"';
	}

	if($parallax_image_attachment){
		$wrapper_attributes[] = 'data-attachment="' . esc_attr( $parallax_image_attachment ) . '"';
	}
}

if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = (trim( $css_class ) != '')?'class="' . esc_attr( trim( $css_class ) ) . '"' : '';



$output .= '<section ' . implode( ' ', $wrapper_attributes ) . '><div'.$custom_css_id.' class="'.implode(' ', $custom_css_classes).'">'.$inner_before.'<div class="'.$full_width.'">';
$output .= wpb_js_remove_wpautop( $content );
$output .= '</div>'.$inner_after.'</div></section>';
$output .= $after_output;

echo wpb_js_remove_wpautop($output);