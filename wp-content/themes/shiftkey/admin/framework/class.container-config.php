<?php
defined( 'ABSPATH' ) || exit;
/**
* Shiftkey config
*/
class Shiftkey_Container_Config extends Shiftkey{

	function __construct(){	
		add_filter('shiftkey_container_id', array( $this, 'predefined_container_id' ) );
		add_filter('shiftkey_container_class', array( $this, 'predefined_container_class' ), 50, 2 );	
		add_filter('shiftkey_container_class', array( $this, 'custom_container_class' ), 10, 2 );	
		add_filter('shiftkey_sidebar_id', array( $this, 'predefined_sidebar_id' ) );		
		add_filter('shiftkey_sidebar_class', array( $this, 'custom_sidebar_class' ), 10, 2 );	
		add_action('shiftkey_sidebar_before', array( $this, 'sidebar_before' ));	
		add_action('shiftkey_sidebar_after', array( $this, 'sidebar_after' ));	
	}

	/*
	* get_template_type
	* @return default/landing
	*/
	public static function get_template_type(){
		$opt_name = 'shiftkey_template_type';
		$output = false;	
		if( function_exists('rwmb_meta') ){
			$output = rwmb_meta( $opt_name, array(), self::get_id() );
		}	

		return $output;
	}

	/*
	* predefined_template
	* @return Boolean
	*/
	public static function get_layout_type(){
		$opt_name = 'layout_type';
		$output = false;	
		if( shiftkey_is_meta_field_exists($opt_name) ){
			$output = rwmb_meta( $opt_name, array(), self::get_id() );
		}	

		return $output;
	}

	/*
	* predefined_template
	* @return Boolean
	*/
	public static function is_predefined_template(){
		$opt_name = 'predefined_template';
		$post_id = self::get_id();
		$default = true;

		if( shiftkey_is_meta_field_exists($opt_name) && $post_id ){
			$output = rwmb_meta( $opt_name, array(), $post_id );			
		}else{
			$output = $default;
		}	

		return $output;
	}

	/*
	* predefined_layout
	* @return string
	*/
	public static function predefined_container_id($output){	
		$opt_name = 'predefined_layout';
		$default = shiftkey_default_container_id();
		$post_id = get_the_ID();
			

		if( shiftkey_is_meta_field_exists($opt_name, $post_id) && self::is_predefined_template() ){
			$meta = rwmb_meta( $opt_name, array(), $post_id );						
			$output = shiftkey_get_predefined_template_attr($meta, 'id');
		}else{
			$output = $default;
		}		
		return $output;
	}

	/*
	* predefined_layout class
	* @return array
	*/
	public static function predefined_container_class($classes, $class){	
		$opt_name = 'predefined_layout';
		$default = '';
		$meta = (is_page())? 'page' : 'blog';	
		$meta = is_singular( 'post' )? 'blog_single' : $meta;	
		$post_id = self::get_id();

		if(self::is_predefined_template()):					
			if( shiftkey_is_meta_field_exists($opt_name, $post_id) ){
				$meta = rwmb_meta( $opt_name, array(), $post_id );
				$classes = array(shiftkey_get_predefined_template_attr($meta, 'class'));
			}else{
				$classes = array(shiftkey_get_predefined_template_attr($meta, 'class'));
			}		
		endif;	
		return $classes;
	}

	/*
	* predefined_layout class
	* @return array
	*/
	public static function custom_container_class($classes, $class){	
		$opt_name = 'container_spacing';
		$post_id = self::get_id();
		$default = 'wide-60';

		if(!self::is_predefined_template()):					
			if( shiftkey_is_meta_field_exists($opt_name, $post_id) ){
				$meta = rwmb_meta( $opt_name, array(), $post_id );
				$classes[] = shiftkey_wide_class_prefix().$meta;
			}else{
				$classes[] = $default;
			}
		else:	
			$classes[] = $default;
		endif;	
		return $classes;
	}

	public static function sidebar_before(){		
		
		if(shiftkey_get_layout() == 'full' ) return false;

		if(shiftkey_get_layout() == 'ls' ){
			$class = shiftkey_padding_right_class_prefix().'60';
		}else{
			$class = shiftkey_padding_left_class_prefix().'60';
		}
		echo '<div id="sidebar" class="'.esc_attr($class).'">';
	}

	public static function sidebar_after(){		
		$output = '';
		if(self::get_layout_type() == 'full' ) return false;	
		echo '</div>';
	}

	/*
	* predefined_sidebar_id
	* @return string
	*/
	public static function predefined_sidebar_id($output){	
		$opt_name = 'predefined_layout';		
		if( function_exists('rwmb_meta') ){
			$meta = rwmb_meta( $opt_name, array(), self::get_id() );	
			$arr = shiftkey_get_predefined_template_attr($meta, 'sidebar');
			$output = ( isset($arr['id']) )? $arr['id'] : 'sidebar-right';
		}else{
			$output = 'sidebar-right';
		}		
		return $output;
	}

	/*
	* predefined_layout class
	* @return array
	*/
	public static function predefined_sidebar_class($classes, $class){	
		if(self::is_predefined_template()):
			$opt_name = 'predefined_layout';		
			
		endif;	
		return $classes;
	}

	/*
	* predefined_layout class
	* @return array
	*/
	public static function custom_sidebar_class($classes, $class){	
		if(!self::is_predefined_template()):
			$opt_name = 'container_spacing';		
			
		endif;	
		return $classes;
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
	
	
}
new Shiftkey_Container_Config();

/*
* Container functions
*/
/* Layout option for shiftkey */
function shiftkey_layout_option_values( $options = array() ){
	

	if( is_page() ):		
		$layout = Shiftkey_Container_Config::get_layout_type();

		$sidebar = 	get_post_meta( get_the_ID(), 'sidebar', true );	
		$sidebar = ( $sidebar== '' )? 'sidebar-page' : $sidebar;

	elseif( is_singular('post') ):
		$layout = shiftkey_get_option('single_layout', 'rs');	
		$sidebar = 	shiftkey_get_option( 'single_layout_sidebar', 'sidebar-post' );			
	else:
		$layout = shiftkey_get_option('blog_layout', 'rs');
		$sidebar = 	shiftkey_get_option( 'blog_layout_sidebar', 'sidebar-post' );				
	endif;

	if( function_exists('is_woocommerce') ){
		if( is_product() ):
			$layout = shiftkey_get_option('product_layout', 'full');
			$sidebar = 	shiftkey_get_option( 'product_layout_sidebar', 'sidebar-product' );
		elseif( is_woocommerce() ):
			$layout = shiftkey_get_option('shop_layout', 'full');
			$sidebar = 	shiftkey_get_option( 'shop_layout_sidebar', 'sidebar-product' );
		endif;
	}

	

	if ( 'portfolio' == get_post_type() ){
		$archive_id = shiftkey_get_option('portfolio_archive');
		if(get_post_status($archive_id) == 'publish'){
			$page_id = $archive_id; 
			$layout = get_post_meta( $page_id, 'page_layout', true );
			$sidebar = 	get_post_meta( $page_id, 'sidebar', true );

		}else{
			$layout = shiftkey_get_option('portfolio_layout', 'full');
			$sidebar = 	shiftkey_get_option( 'portfolio_layout_sidebar', 'sidebar-portfolio' );			
		}

		if( is_singular('portfolio') ){
			$layout = shiftkey_get_option('portfolio_single_layout', 'full');
			$sidebar = 	shiftkey_get_option( 'portfolio_single_layout_sidebar', 'sidebar-portfolio' );
		}		
	}


	if ( 'team' == get_post_type() ){
		$archive_id = shiftkey_get_option('team_archive');
		if(get_post_status($archive_id) == 'publish'){
			$page_id = $archive_id; 
			$layout = get_post_meta( $page_id, 'page_layout', true );
			$sidebar = 	get_post_meta( $page_id, 'sidebar', true );

		}else{
			$layout = shiftkey_get_option('team_layout', 'full');
			$sidebar = 	shiftkey_get_option( 'team_layout_sidebar', 'sidebar-page' );			
		}

		if( is_singular('team') ){
			$layout = shiftkey_get_option('team_single_layout', 'full');
			$sidebar = 	shiftkey_get_option( 'team_single_layout_sidebar', 'sidebar-page' );
		}		
	}

	if(is_404()) $layout = 'full';



	if ( !is_active_sidebar( $sidebar ) ){
		$layout = 'full';
	}

	
	
	$layout = ( $layout == '' )? 'full' : $layout;	

	$options['layout'] = $layout;
	$options['sidebar'] = ( $layout != 'full' )? $sidebar : '';

	return apply_filters(  'shiftkey_layout_option_values', $options );
	
}


function shiftkey_get_layout(){
	global $wp_query;
	return $wp_query->shiftkey['layout'];
}

function shiftkey_get_sidebar(){
	global $wp_query;	
	return $wp_query->shiftkey['sidebar'];
}