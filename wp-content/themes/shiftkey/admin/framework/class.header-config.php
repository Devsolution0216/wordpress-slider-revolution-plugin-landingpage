<?php
defined( 'ABSPATH' ) || exit;
/**
* Shiftkey config
*/
class Shiftkey_Header_Config extends Shiftkey{

	function __construct(){	
		add_filter('shiftkey_wrapper_class', array( $this, 'predefined_wrapper_class' ), 10, 2 );	
		add_filter( 'shiftkey_header_parallax_bg', array( $this, 'header_parallax_bg_image_url' ) );	
	}

	/*
	* predefined_layout class
	* @return array
	*/
	public static function predefined_wrapper_class($classes, $class){	
		if(ShiftkeyHeader::header_banner_is_on()){
			$classes[] = 'header-banner-on';
		}
		
		return $classes;
	}

	/*
	* Get logo type
	* @return    string
	*/
	public static function logo_type(){
		$opt_name = 'logo_type';
		$default = 'image';

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/logo_type', $output );

		return $output;
	}	

	/*
	* logo text
	* @param     string
	* @return    html
	*/
	private static function logo_text( $text = '' ){
		if( $text == '' ) return '';

		return $text;
	}

	/*
	* logo
	* @param     boolean
	* @return    url
	*/
	public static function logo( $dark ){
		$logo_type = self::logo_type();		
		$opt_name = ( $dark )? 'logo' : 'logo_white';
		$default = ( $dark )? SHIFTKEY_URI.'/images/logo.png' : SHIFTKEY_URI.'/images/logo-white.png';
		
		// image logo
		if( $logo_type == 'image' ):
			$logo = shiftkey_get_option( $opt_name );			
			$output = isset($logo[ 'url' ])? $logo[ 'url' ] : $default;		
		endif;

		// text logo
		$opt_name = 'logo_text';
		if( $logo_type == 'text' ):
			$logo = shiftkey_get_option( $opt_name, get_bloginfo( 'name' ));

			//Generate logo html
			//$logo = self::logo_text($logo);
			$output = $logo;		
		endif;

		$output = apply_filters( 'shiftkey/get_logo', $output, $logo_type, $dark );

		return $output;
	}	

	/*
	* logo type
	* @return string
	*/
	public static function get_logo_type(){
		return self::logo_type();
	}

	/*
	* logo type
	* @param boolean
	* @return Url
	*/
	public static function get_logo($dark = true){
		return self::logo($dark);
	}

	/*
	* navbar_style
	* @return    string
	*/
	private static function navbar_style(){
		$opt_name = 'navbar_style';
		$default = 'style1';

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/navbar_style', $output );
		return $output;
	}

	/*
	* Navbar style
	* @return string
	*/
	public static function get_navbar_style(){
		return self::navbar_style();
	}

	/*
	* Social Icons display
	* @return Boolean
	*/
	public static function header_search_icon_is_on(){
		$opt_name = 'header_search_display';
		$default = false;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/header_search_display', $output );
		return $output;
	}

	/*
	* Social Icons display
	* @return Boolean
	*/
	public static function header_social_icons_is_on(){
		$opt_name = 'header_social_icons_display';
		$default = false;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/header_social_icons_display', $output );
		return $output;
	}

	/*
	* Social Icons
	* @return array
	*/
	public static function header_social_icons(){
		$opt_name = 'header_social_icons';
		$default = array('facebook','twitter');

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/header_social_icons', $output );
		return $output;
	}

	/*
	* Social Icons
	* @return array()
	*/
	public static function _header_social_icons(){
		if(self::header_social_icons_is_on()){
			return self::header_social_icons();
		}else{
			return array();
		}		
	}

	/*
	* Social Icons html
	* @return array()
	*/
	public static function get_header_social_icons($args = array(), $output = ''){		
		$iconsArr = self::_header_social_icons();		
		if(empty($iconsArr)) return $output;
		
		$icon_list = shiftkey_default_social_links_callback();	
		$options = get_option('shiftkey_settings', array());
		if( !empty($options) ):			
		$icon_list = $options['social_links_group'];		
		endif;

		$iconsArr = array_filter($iconsArr);
		$array = array();
		if( !empty($iconsArr) ):			
			foreach ($iconsArr as $key => $value) {
				$array[] = isset($icon_list[$value])? $icon_list[$value] : array();
			}
		endif;
		$array = array_filter($array);

		$output = self::get_social_icons_html($array, $args);

		return $output;
	}

	/*
	* Buttons display
	* @return Boolean
	*/
	public static function navbar_bg_is_on(){		
		$opt_name = 'enable_navbar_bg';
		$default = 'on';

		$output = shiftkey_get_option( $opt_name, $default );
		$output = Shiftkey::maybe_boolean($output);
		
		$output = apply_filters( 'shiftkey/enable_navbar_bg', $output );		
		
		

		return $output;
	}

	/*
	* Buttons display
	* @return Boolean
	*/
	public static function breadcrumbs_display_is_on(){		
		$opt_name = 'breadcrumbs_display';
		$default = true;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = Shiftkey::maybe_boolean($output);

		$output = apply_filters( 'shiftkey/breadcrumbs_display', $output );		
		
		

		return $output;
	}

	/*
	* Buttons display
	* @return Boolean
	*/
	public static function header_buttons_is_on(){
		$opt_name = 'header_button_display';
		$default = false;

		$output = shiftkey_get_option( $opt_name, $default );

		$output = apply_filters( 'shiftkey/header_button_display', $output );

		return $output;
	}

	/*
	* Buttons
	* @return array
	*/
	public static function header_buttons(){
		$opt_name = 'header_buttons';
		$default = array('contact_us');

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/header_buttons', $output );		
		return $output;
	}

	/*
	* Buttons
	* @return array()
	*/
	public static function _header_buttons(){
		if(self::header_buttons_is_on()){
			return self::header_buttons();
		}else{
			return array();
		}		
	}

	/*
	* Buttons html
	* @return array()
	*/
	public static function get_header_buttons($args = array(), $output = ''){		
		
		$iconsArr = self::_header_buttons();
		if(empty($iconsArr)) return $output;

		$_array = shiftkey_default_buttons_set_callback();	
		$options = get_option('shiftkey_settings', array());
		if( !empty($options) ):			
		$_array = $options['buttons_group'];		
		endif;

		$iconsArr = array_filter($iconsArr);

		
		$array = array();
		foreach ($iconsArr as $key => $value) {
			$array[] = isset($_array[$value])? $_array[$value] : array();
		}
		$array = array_filter($array);
		$output = '<span>'.self::get_buttons_html($array).'</span>';

		return $output;
	}

	/*
	* Banner display
	* @return Boolean
	*/
	public static function header_banner_is_on(){
		$opt_name = 'title_display';
		$default = true;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/header_banner_display', $output );

		return $output;
	}

	public static function header_parallax_bg_image_url($arr){
		$post_id = self::get_id();
		$default = 'options';
		$opt_name = 'banner_bg_image_source';

		$image_source = get_post_meta( $post_id, $opt_name, true );
		$image_source = ($image_source != '')? $image_source : $default;
		if( $image_source == 'featured' ){
			if( has_post_thumbnail($post_id) ){
				$url = get_the_post_thumbnail_url( $post_id, 'full');
				if( $url ){
					$arr['background-image']  = esc_url($url);
				}
				
			}
		}

		if( $image_source == 'custom' ){
			$attachment_id = get_post_meta( $post_id, 'custom_banner_bg_image', true );
			if( $attachment_id ){
				$url = wp_get_attachment_url( $attachment_id);
				if( $url ){
					$arr['background-image']  = esc_url($url);
				}
			}
		}

		return $arr;
	}
	
	
}
new Shiftkey_Header_Config();