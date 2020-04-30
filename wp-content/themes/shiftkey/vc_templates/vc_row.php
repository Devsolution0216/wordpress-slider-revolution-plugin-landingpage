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
 * @var $equal_height
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
$el_class = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = $css_animation = $row_type = $row_class = $row_bg_class = $enable_row_inner = $row_inner_bg_class = $enable_outer_container = '';
$disable_element = '';
$output = $after_output = '';

$row_params = shiftkey_vc_row_settings(true);
foreach ($row_params as $key => $value) {
	${$value['param_name']} = '';
}

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$extra_class = '';
$dark_color_class = shiftkey_default_dark_color_classes();
if( in_array($row_bg_class, $dark_color_class) ){
	$extra_class = 'white-color';
}

$css_classes = array(
	'vc_row',
	'wpb_row',
	//deprecated
	'vc_row-fluid',
	$column_style,
	$el_class,
);
$css_classes[] = $margin_top;
$css_classes[] = $margin_bottom;
$css_classes[] = $padding_top;
$css_classes[] = $padding_bottom;
$css_classes[] = $bg_class;
$css_classes[] = $row_class;


/* inner class*/

$__classes = array();
if($enable_inner == 'yes'){
	$__classes = array('row-inner-wrap', 'd-flex');
	$__classes[] = $inner_margin_top;
	$__classes[] = $inner_margin_bottom;
	$__classes[] = $inner_padding_top;
	$__classes[] = $inner_padding_bottom;
	$__classes[] = $inner_padding_class;
	$__classes[] = $inner_bg_class;
	$__classes[] = vc_shortcode_custom_css_class( $css );
	$__classes[] = in_array($inner_bg_class,$dark_color_class)? 'white-color' : '';	
	$__classes[] = 'bg-'.$parallax_image_attachment;
	$css_classes[] = 'enable-bg-inner';
}else{
	$css_classes[] = vc_shortcode_custom_css_class( $css );
	$css_classes[] = 'bg-'.$parallax_image_attachment;
}




$inner_before = $inner_after = '';
if( ($full_width != '') && ($enable_inner == 'yes') ){
	$__classes = array_filter($__classes); 
	$__attr = array();   
	$__attr[] = (!empty($__classes))?'class="'.implode(' ', $__classes).'"' : '';
	$__attributes = implode( ' ', $__attr );

	$inner_before = '<div class="container"><div '.$__attributes.'>';
	$inner_after = '</div></div>';
}

if($full_width != 'stretch_row'){
	$css_classes[] = vc_shortcode_custom_css_class( $css );	
	$css_classes[] = vc_shortcode_custom_css_class( $css );
}


if($row_type != '' ){	
	$css_classes[] = $row_type.'-'.$atts[$row_type.'_type'];
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
}





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
	$css_classes[] = 'vc_row-has-fill';
}

if ( ! empty( $atts['gap'] ) ) {
	$css_classes[] = 'vc_column-gap-' . $atts['gap'];
}

if ( ! empty( $atts['rtl_reverse'] ) ) {
	$css_classes[] = 'vc_rtl-columns-reverse';
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $full_width ) ) {
	
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-full-width="true"';
		$wrapper_attributes[] = 'data-vc-full-width-init="false"';
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-full-width="true"';
		$wrapper_attributes[] = 'data-vc-full-width-init="false"';
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width vc_clearfix"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
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
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

/* custom*/
$__classes = array_filter($__classes); 
$__attr = array();   
$__attr[] = (!empty($__classes))?'class="'.implode(' ', $__classes).'"' : '';
$__attributes = implode( ' ', $__attr );


$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= $inner_before;
$output .= wpb_js_remove_wpautop( $content );
$output .= $inner_after;
$output .= '</div>';

$output .= $after_output;

if( $enable_outer_container == 'yes' ){
	echo '<div class="container">';
}
echo wpb_js_remove_wpautop($output);
if( $enable_outer_container == 'yes' ){
	echo '</div>';
}
