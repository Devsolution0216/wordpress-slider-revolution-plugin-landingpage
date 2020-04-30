<?php
add_filter( 'shiftkey/nav_style_scroll', 'shiftkey_nav_style_scroll', 10, 1 );
add_filter( 'shiftkey/header_sticky_nav', 'shiftkey_header_sticky_nav', 10, 1 );
add_filter( 'shiftkey/nav_bg_class', 'shiftkey_nav_bg_class', 10, 1 );
add_filter( 'shiftkey/nav_bg_gradient', 'shiftkey_nav_bg_gradient', 10, 1 );
add_filter( 'shiftkey/nav_bg_gradient_type', 'shiftkey_nav_bg_gradient_type', 10, 1 );
add_filter( 'shiftkey/nav_bg_color', 'shiftkey_nav_bg_custom_color', 10, 1 );
add_filter( 'shiftkey/nav_bg_type', 'shiftkey_custom_nav_bg_type', 10, 1 );
add_filter( 'shiftkey/navbar_style', 'shiftkey_navbar_style', 10, 1 );
add_filter( 'shiftkey/logo_type', 'shiftkey_page_logo_type', 10, 1 );	
add_filter( 'shiftkey/get_logo', 'shiftkey_get_logo', 10, 3 );	
add_filter( 'shiftkey/header_search_display', 'shiftkey_header_search_display', 10, 1 );
add_filter( 'shiftkey/nav_search_placeholder', 'shiftkey_nav_search_placeholder', 10, 1 );	
add_filter( 'shiftkey/header_social_icons_display', 'shiftkey_header_social_icons_display', 10, 1 );	
add_filter( 'shiftkey/header_social_icons', 'shiftkey_header_social_icons', 10, 1 );	
add_filter( 'shiftkey/header_button_display', 'shiftkey_header_button_display', 10, 1 );	
add_filter( 'shiftkey/header_buttons', 'shiftkey_header_buttons', 10, 1 );	
add_filter( 'perch_modules/perch_buttons/color/options', 'shiftkey_button_scolor_options', 10, 1 );

add_filter( 'shiftkey/enable_navbar_bg', 'shiftkey_enable_navbar_bg', 10, 1 );
add_filter( 'shiftkey/breadcrumbs_display', 'shiftkey_breadcrumbs_display', 10, 1 );
		
/*footer*/	
add_filter( 'shiftkey/footer_bg_class', 'shiftkey_footer_bg_class', 10, 1 );
add_filter( 'shiftkey/footer_bg_gradient', 'shiftkey_footer_bg_gradient', 10, 1 );
add_filter( 'shiftkey/footer_bg_gradient_type', 'shiftkey_footer_bg_gradient_type', 10, 1 );
add_filter( 'shiftkey/footer_bg_color', 'shiftkey_footer_bg_custom_color', 10, 1 );
add_filter( 'shiftkey/custom_footer_bg_type', 'shiftkey_custom_footer_bg_type', 10, 1 );

add_filter( 'shiftkey/newsletter_area', 'shiftkey_footer_newsletter_area_display', 10, 1 );
add_filter( 'shiftkey/cta_area_display', 'shiftkey_footer_cta_area_display', 10, 1 );
add_filter( 'shiftkey/footer_widget_area', 'shiftkey_footer_widget_area_display', 10, 1 );
add_filter( 'shiftkey/footer_copyright_bar', 'shiftkey_footer_copyright_bar_display', 10, 1 );
add_filter( 'shiftkey/quickform_area', 'shiftkey_quickform_area_display', 10, 1 );

function shiftkey_button_scolor_options($array){
	$new_array = shiftkey_redux_options(shiftkey_btn_style_options(false));
	return array_merge($array, $new_array);
}
function shiftkey_get_post_group_meta( $output, $group_id = '', $opt_name = '', $default = '' ){
	$output = $default;
	if($group_id == '') return $output;
	if($opt_name == '') return $output;

	$meta = array();
	if( function_exists('rwmb_meta')  ) {
		$meta = rwmb_meta( $group_id );		
		$output = !isset($meta[$opt_name])? false : $output;	
	}
		
	
	if( isset($meta[$opt_name]) && ($meta[$opt_name] != '') ){
		return $meta[$opt_name];
	}else{			
		return $output;
	}
}

function shiftkey_footer_newsletter_area_display($output){
	$opt_name = 'newsletter_area';
	$group_id = 'footer_settings_group';
	
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name, false );
	}else{
		if( is_page() ){
			$output = false;
		}
	}			
	return $output;
}

function shiftkey_footer_cta_area_display($output){
	$opt_name = 'cta_area_display';
	$group_id = 'footer_settings_group';
	
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name, false );
	}else{
		if( is_page() ){
			$output = false;
		}
	}			
	return $output;
}

function shiftkey_quickform_area_display($output){
	$opt_name = 'quickform_area';
	$group_id = 'footer_settings_group';
	
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name, false );
	}else{
		if( is_page() ){
			$output = false;
		}
	}			
	return $output;
}


function shiftkey_footer_copyright_bar_display($output){
	$opt_name = 'footer_copyright_bar';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_footer_widget_area_display($output){
	$opt_name = 'footer_widget_area';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_footer_bg_custom_color($output){
	$opt_name = 'footer_bg_color';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_custom_footer_bg_type($output){
	$opt_name = 'footer_bg_type';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_footer_bg_gradient_type($output){
	$opt_name = 'footer_bg_gradient_type';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_footer_bg_gradient($output){
	$opt_name = 'footer_bg_gradient';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_footer_bg_class($output){

	$opt_name = 'footer_bg_class';
	$group_id = 'footer_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_footer_settings')){		
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );		
	}			
	return $output;
}

function shiftkey_header_social_icons($output){
	$opt_name = 'header_social_icons';
	$group_id = 'navbar_icon_settings';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_icon_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );		
	}			
	return $output;
}

function shiftkey_header_social_icons_display($output){
	$opt_name = 'header_social_icons_display';
	$group_id = 'navbar_icon_settings';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_icon_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_header_button_display($output){
	$opt_name = 'header_button_display';
	$group_id = 'navbar_icon_settings';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_icon_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );		
	}			
	return $output;
}

function shiftkey_header_buttons($output){
	$opt_name = 'header_buttons';
	$group_id = 'navbar_icon_settings';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_icon_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );			
	}			
	return $output;
}

function shiftkey_header_search_display($output){
	$opt_name = 'header_search_display';
	$group_id = 'navbar_icon_settings';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_icon_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_nav_search_placeholder($output){
	$opt_name = 'nav_search_placeholder';
	$group_id = 'navbar_icon_settings';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_icon_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_nav_style_scroll($output){
	$opt_name = 'nav_style_scroll';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_header_sticky_nav($output){
	$opt_name = 'header_sticky_nav';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = false;
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}

	return $output;
}

function shiftkey_nav_bg_class($output){

	$opt_name = 'nav_bg_class';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){		
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_nav_bg_gradient($output){
	$opt_name = 'nav_bg_gradient';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_nav_bg_gradient_type($output){
	$opt_name = 'nav_bg_gradient_type';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_nav_bg_custom_color($output){
	$opt_name = 'nav_bg_color';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_custom_nav_bg_type($output){
	$opt_name = 'nav_bg_type';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_navbar_style($output){
	$opt_name = 'navbar_style';
	$group_id = 'navbar_settings_group';
	if(function_exists('rwmb_meta') && rwmb_meta('custom_nav_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_page_logo_type($output){
	$opt_name = 'logo_type';
	$group_id = 'logo_settings_group';

	if(function_exists('rwmb_meta') && rwmb_meta('custom_logo_settings')){
		$output = shiftkey_get_post_group_meta($output, $group_id, $opt_name );
	}			
	return $output;
}

function shiftkey_get_logo($output, $logo_type, $dark){
	$group_id = 'logo_settings_group';
	
	if(function_exists('rwmb_meta') && rwmb_meta('custom_logo_settings')):	

		$opt_name = ( $dark )? 'logo_white' : 'logo';
		if( $logo_type == 'image' ){
			$image_url = shiftkey_get_post_group_meta($output, $group_id, $opt_name);
			//$image = RWMB_Image_Field::file_info( $image_id, array( 'size' => 'full' ) );		

			$output = $image_url;
		}

		// text logo
		$opt_name = 'logo_text';
		if( $logo_type == 'text' ){
			$logo = shiftkey_get_post_group_meta($output, $group_id, $opt_name);
			$output = $logo;		
		}

	endif;

	return $output;
}

function shiftkey_breadcrumbs_display($output){
	$opt_name = 'breadcrumbs_display';
	

	if(function_exists('rwmb_meta') && is_page()){
		$output = rwmb_meta($opt_name, true );		
	}		
	return $output;
}

function shiftkey_enable_navbar_bg($output){
	$opt_name = 'enable_navbar_bg';
	

	if(is_page()){
		$output = function_exists('rwmb_meta')? rwmb_meta($opt_name, false ) : false;
	}		
	return $output;
}


add_filter( 'perch_modules/vc_spacing_options_param', 'shiftkey_spacing_options_param', 10, 4 );
function shiftkey_spacing_options_param($arr, $type, $pos, $name ){
	$new_array = shiftkey_vc_spacing_options($type, $pos);
	$arr = array_merge($arr, $new_array);
	return $arr;
}