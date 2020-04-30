<?php
defined( 'ABSPATH' ) || exit;
/**
* Shiftkey config
*/
class Shiftkey_Footer_Config extends Shiftkey{

	function __construct(){				
	}	

	/*
	* Newsletter area display
	* @return Boolean
	*/
	public static function newsletter_area_is_on(){
		$opt_name = 'newsletter_area';
		$default = false;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/newsletter_area', $output );
		return $output;
	}

	/*
	* CTA display
	* @return Boolean
	*/
	public static function cta_area_is_on(){
		$opt_name = 'cta_area_display';
		$default = false;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/cta_area_display', $output );
		return $output;
	}

	/*
	* Widget area display
	* @return Boolean
	*/
	public static function widget_area_is_on(){
		$opt_name = 'footer_widget_area';
		$default = true;

		$output = shiftkey_get_option( $opt_name, $default );	
		$output = apply_filters( 'shiftkey/footer_widget_area', $output );	
		return $output;
	}

	public static function footer_widget_area_is_empty(){		
		$active = array();

		$column = self::footer_widget_area_column();		
		for ( $i = 1; $i <= $column; $i++ ) {
			$widget_area = 'footer-'.$i;
			if ( is_active_sidebar( $widget_area ) ){
				$active[] = $widget_area;				
			}
		}

		$output = empty($active)? true : false;

		return $output;
	}

	/*
	* Widget area Column No
	* @return Boolean
	*/
	public static function footer_widget_area_column(){
		$opt_name = 'footer_widget_area_column';
		$default = 4;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/footer_widget_area', $output );
		return $output;
	}

	/*
	* Widget area Column No
	* @return Boolean
	*/
	public static function footer_widget_area_column_sizes(){		
		$opt_name = 'footer_widget_area_column_sizes';
		$default = 'col-md-3 col-sm-6,col-md-3 col-sm-6,col-md-3 col-sm-6,col-md-3 col-sm-6';

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/footer_widget_area_column_sizes', $output );
		return $output;
	}

	/*
	* Widget area Column No
	* @return Boolean
	*/
	public static function footer_widget_area_get_column_sizes(){
		$column_sizes = self::footer_widget_area_column_sizes();
		
		$output = explode(',', $column_sizes);	
		$output = array_filter($output);	
		return $output;
	}

	/*
	* Widget area display
	* @return Boolean
	*/
	public static function copyright_bar_is_on(){
		$opt_name = 'footer_copyright_bar';
		$default = true;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/footer_copyright_bar', $output );
		return $output;
	}

	/*
	* Sticky bottom form display
	* @return Boolean
	*/
	public static function quickform_area_is_on(){
		$opt_name = 'quickform_area';
		$default = false;

		$output = shiftkey_get_option( $opt_name, $default );
		$output = apply_filters( 'shiftkey/quickform_area', $output );
		return $output;
	}
	
	
}
new Shiftkey_Footer_Config();