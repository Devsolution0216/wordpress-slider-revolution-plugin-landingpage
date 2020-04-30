<?php
if( !function_exists( 'shiftkey_primary_color' ) ):
function shiftkey_primary_color(){
	return shiftkey_get_option('primary_color', '#5496ff');
}
endif;

if( !function_exists( 'shiftkey_secondary_color' ) ):
function shiftkey_secondary_color(){
	return shiftkey_get_option('secondary_color', '#5496ff');
}
endif;

if( !function_exists( 'shiftkey_dark_color' ) ):
function shiftkey_dark_color(){
	return shiftkey_get_option('dark_color', '#333');
}
endif;

if( !function_exists( 'shiftkey_grey_color' ) ):
function shiftkey_grey_color(){
	return shiftkey_get_option('grey_color', '#666');
}
endif;

if( !function_exists( 'shiftkey_light_grey_color' ) ):
function shiftkey_light_grey_color(){
	return shiftkey_get_option('lightgrey_color', '#f8f9fb');
}
endif;

add_filter( 'perch_modules/wide_class_prefix', 'shiftkey_wide_class_prefix' );
function shiftkey_wide_class_prefix(){
	return 'wide-';
}

add_filter( 'perch_modules/ind_class_prefix', 'shiftkey_ind_class_prefix' );
function shiftkey_ind_class_prefix(){
	return 'pc-';
}

add_filter( 'perch_modules/margin_top_class_prefix', 'shiftkey_margin_top_class_prefix' );
function shiftkey_margin_top_class_prefix(){
	return 'm-top-';
}

add_filter( 'perch_modules/margin_right_class_prefix', 'shiftkey_margin_right_class_prefix' );
function shiftkey_margin_right_class_prefix(){
	return 'm-right-';
}

add_filter( 'perch_modules/margin_bottom_class_prefix', 'shiftkey_margin_bottom_class_prefix' );
function shiftkey_margin_bottom_class_prefix(){
	return 'm-bottom-';
}

add_filter( 'perch_modules/margin_left_class_prefix', 'shiftkey_margin_left_class_prefix' );
function shiftkey_margin_left_class_prefix(){
	return 'm-left-';
}

add_filter( 'perch_modules/padding_top_class_prefix', 'shiftkey_padding_top_class_prefix' );
function shiftkey_padding_top_class_prefix(){
	return 'p-top-';
}

add_filter( 'perch_modules/padding_right_class_prefix', 'shiftkey_padding_right_class_prefix' );
function shiftkey_padding_right_class_prefix(){
	return 'p-right-';
}

add_filter( 'perch_modules/padding_bottom_class_prefix', 'shiftkey_padding_bottom_class_prefix' );
function shiftkey_padding_bottom_class_prefix(){
	return 'p-bottom-';
}

add_filter( 'perch_modules/padding_left_class_prefix', 'shiftkey_padding_left_class_prefix' );
function shiftkey_padding_left_class_prefix(){
	return 'p-left-';
}

if( !function_exists( 'shiftkey_default_container_id' ) ):
function shiftkey_default_container_id(){
	$output = 'blog-page';
	
	if( is_page() ) $output = 'page';

	if('post' == get_post_type()){
		$output = ( is_singular() )? 'single-post' : $output;
	}	

	return trim($output);	
}
endif;

if( !function_exists( 'shiftkey_blog_classes' ) ):
function shiftkey_blog_classes(){
	return 'blog-post';
	
}
endif;

if( !function_exists( 'shiftkey_blog_single_classes' ) ):
function shiftkey_blog_single_classes(){
	return 'single-blog-post';
}
endif;

if( !function_exists( 'shiftkey_post_header_class' ) ):
function shiftkey_post_header_class(){
	$output = ( is_singular() )? 'sblog-post-txt' : 'blog-post-txt';

	echo trim($output);
}
endif;

function shiftkey_post_format_class( $classes = '' ){
	$classes .= ( is_singular() )? ' mb-40' : ' mb-25';

	echo trim($classes);
}

function shiftkey_default_pagination_classes(){
	$output = ( is_singular() )? ' mb-0 mt-40' : ' mb-40';

	return $output;
}


if( !function_exists( 'shiftkey_footer_column_style' ) ):
function shiftkey_footer_column_style(){
	$array = array(
	'col-md-3 col-sm-6,col-md-3 col-sm-6,col-md-3 col-sm-6,col-md-3 col-sm-6' => '4/12 + 4/12 + 4/12 + 4/12',
	'col-md-10 col-lg-4,col-md-3 col-lg-2 offset-lg-1,col-md-3 col-lg-2,col-md-6 col-lg-3' => '4/12 + 2/12(-1/12) + 2/12 + 3/12',
	'col-md-10 col-lg-4,col-md-3 col-lg-2,col-md-3 col-lg-2 offset-lg-1,col-md-6 col-lg-3' => '4/12 + 2/12 + 2/12(-1/12) + 3/12',
	'col-md-3 col-lg-4,col-md-3 col-lg-2,col-md-3 col-lg-2,col-md-3 col-lg-4' => '4/12 + 2/12 + 2/12 + 4/12',
	);

	$array = array_filter($array);
	return $array;
}
endif;

function shiftkey_general_options_social_link(){
	return array('facebook','twitter', 'linkedin', 'tumblr');
}

function shiftkey_get_post_content_style( $count ){
	global $wp_query;
	$output = 'content';
	$count = $wp_query->shiftkey['counter'];	
	if( $count == 1 ) $output = 'content-right';
	if( $count > 3 ) $output = 'content-right';

	return $output;
}

add_filter( 'shiftkey/newsletter_bg_class', 'shiftkey_newsletter_bg_class_default' );
function shiftkey_newsletter_bg_class_default($class){
	if( $class == '' ){
		if( get_post_type() == 'post' && is_singular() ){
			$class = 'bg-lightgrey1';
		}else{
			$class = 'bg-lightgrey';
		}
	}

	return $class;
}