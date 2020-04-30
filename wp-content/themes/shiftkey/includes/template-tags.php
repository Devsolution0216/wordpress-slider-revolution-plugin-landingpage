<?php
/**
* Shiftkey header
*/
class ShiftkeyHeader{

	function __construct(){
		add_filter( 'shiftkey_header_image_url', array( __CLASS__, 'header_image_url' ) );	
		add_filter( 'shiftkey_navbar_logo', array( __CLASS__, 'header_logo_default' ) );	
		add_filter( 'shiftkey_navbar_logo_white', array( __CLASS__, 'header_logo_white_default' ) );	
		add_filter( 'shiftkey_sticky_navbar', array( __CLASS__, 'header_sticky_navbar' ) );	
		add_filter( 'shiftkey_navbg_style', array( __CLASS__, 'header_navbg_style' ) );
		add_filter( 'shiftkey_navbar_style_args', array( __CLASS__, 'navbar_style_args' ) );
	}

	public static function get_navbar_icon_elements(){
		$button = Shiftkey_Header_Config::get_header_buttons();
		$icons = Shiftkey_Header_Config::get_header_social_icons();
		$searchicon = ShiftkeyHeader::get_searchform();
		$cart = '';
		$elements = array(
			'searchicon' => $searchicon,
			'icons' => $icons,
			'button' => $button,
		);

		$elements = apply_filters( 'shiftkey/get_navbar_icon_elements', $elements );
		return implode('', $elements);
	}

	static function navbar_style_args($args){
		global $wpdb;
		$icons = self::get_navbar_icon_elements();
		$nav_style = Shiftkey_Header_Config::get_navbar_style();
		if($nav_style == 'style2'){
			$args[ 'menu_class' ] = 'navbar-nav mr-auto';			
		}else{	
			$searchicon = ShiftkeyHeader::get_searchform();	
			$button = Shiftkey_Header_Config::get_header_buttons();	
			$icons = Shiftkey_Header_Config::get_header_social_icons();
			$args[ 'menu_class' ] = 'navbar-nav ml-auto';
			$args[ 'items_wrap' ] = '<ul id="%1$s" class="%2$s">%3$s'.$searchicon.'</ul>'.$icons.$button;
		}

		if(shiftkey_get_page_template_type() == 'landing'){
			if ( function_exists( 'rwmb_meta' ) ) {
				$one_page_wp_nav = rwmb_meta('one_page_wp_nav');
				if($one_page_wp_nav != ''){					
					$args['menu'] = $one_page_wp_nav;
					unset($args['theme_location']);
				}
			}
		}



		return $args;
	}

	static function header_navbg_style($output){
		$newval = '';
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'nav_style', true );
		}

		if(!self::header_banner_is_on() && (self::get_shortcode() == false)){
			$newval = 'bg-light navbar-light';
		}

		if( is_404() ){
			$newval = 'bg-tra navbar-light';
		}

		$output = ( $newval != '' )? $newval : $output;

		return $output;
	}

	static function header_sticky_navbar($output){
		$newval = '';
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'header_sticky_nav', true );
		}

		$output = ( $newval != '' )? $newval : $output;

		return $output;
	}

	static function header_logo_default($output){
		$newval = '';
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'logo', true );
		}

		$output = ( $newval != '' )? $newval : $output;

		return $output;
	}

	static function header_logo_white_default($output){
		$newval = '';
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'logo_white', true );
		}

		$output = ( $newval != '' )? $newval : $output;

		return $output;
	}

	

	static function get_default_nav_buttons(){
		return array(
                    array(
                        'title' => 'Get Started',
                        'link' => '#',
                        'target' => '_self',
                        'style' => 'btn-primary'
                    ),
                );
	}

	static function get_default_portfolio_buttons(){
		return array(
                    array(
                        'title' => 'Open Website',
                        'button_url' => '#',
                        'button_target' => '_blank',
                        'button_style' => 'btn-primary'
                    ),
                );
	}

	static function get_default_header_buttons(){
		return array(
                    array(
                        'title' => 'View Collection',
                        'link' => '#',
                        'target' => '_self',
                        'style' => 'btn-primary'
                    ),
                );
	}

	static function get_default_header_image(){		
		
		$image = SHIFTKEY_URI.'/images/blogs-page.jpg';
		
		return $image;
	}

	static function get_id(){
		if( is_home() || (get_post_type() == 'post') ){
			$post_id = get_option( 'page_for_posts' ); 
		}elseif( is_page() ){
			$post_id = get_the_ID();
		}else{
			$post_id = NULL;
		}

		if( get_post_type() == 'portfolio' ){
			$post_id = shiftkey_get_option('portfolio_archive', NULL);
		}
		if( get_post_type() == 'team' ){
			$post_id = shiftkey_get_option('team_archive', NULL);
		}

		if( function_exists('is_woocommerce') ){
			if( (get_post_type() == 'product') ):
				$post_id = get_option( 'woocommerce_shop_page_id' );	
			endif;
		}

		return $post_id;
	}

	public static function get_shortcode(){
		if(shiftkey_get_page_template_type() == 'landing'){
			return true;
		}

		$post_id = self::get_id();
		$shortcode = get_post_meta( $post_id, 'shortcode', true );
		if( $shortcode != '' ){
			echo '<div class="slider-area">'.do_shortcode($shortcode).'</div>';
		}else{
			if(is_page_template('templates/vc-template.php')){
				return true;
			}else{
				return false;
			}
		}

		
	}

	public static function get_container_spacing(){
		$post_id = self::get_id();
		$output = get_post_meta( $post_id, 'container_spacing', true );
		$output = ($output == '')? 'wide-100' : $output;

		if( is_404() ) $output = '';
		
		return $output;		
	}
	
	public static function topbar_class(){
		$classArr = array();
		$topbar_background = shiftkey_get_option('topbar_background', 'dark-bg');
		$classArr[] = $topbar_background;
		if( in_array($topbar_background, array('color-bg', 'dark-bg')) ){
			$classArr[] = 'has-darkbg';
		}
		$classArr = array_filter($classArr);
		$classes = implode(' ', $classArr);

		echo apply_filters( 'shiftkey_topbar_class', $classes );
	}

	public static function header_class(){
		$classArr = array();
		$post_id = self::get_id();
		$transparent_header = get_post_meta( $post_id, 'force_transparent_header', true );
		$transparent_header = ( $transparent_header != '' )? $transparent_header : false;

		$classArr[] = ($transparent_header == 'on')? 'fixed-header' : 'default-header';

		$classArr = array_filter($classArr);
		$classes = implode(' ', $classArr);

		echo apply_filters( 'shiftkey_navbar_class', $classes );
	}

	public static function topbar_contact_info(){
		$contact_info = shiftkey_get_option( 'topbar_contact_info', shiftkey_header_default_contact_info() );

		$html = '';
		if( !empty($contact_info) ){
			
			foreach ($contact_info as $key => $value) {
				extract($value);
				$html .= '<li><a title="'.esc_attr($title).'" href="'.esc_url($link).'" ><i class="theme-text '.esc_attr($icon_link['icon']).'"></i> '.esc_attr($icon_link['input']).'</a></li>';
			}
			
		}
	     echo '<ul class="list-inline topbar-contact">';   
	    echo apply_filters( 'shiftkey_topbar_contact_info', $html ); 
	    shiftkey_wpml_lang_select_option();
	    echo '</ul>';
	}

	public static function header_social_icons(){

		$header_social_icons_display = shiftkey_get_option( 'header_social_icons_display', false );
		$newval = '';
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'header_social_icons_display', true );
		}
		$header_social_icons_display = ( $newval != '' )? $newval : $header_social_icons_display;

		if( $header_social_icons_display != 'on') return '';


		$social_icons = shiftkey_get_option( 'header_social_icons', shiftkey_header_default_social_icons() );
		if(shiftkey_get_page_template_type() == 'landing'){
			$social_icons = get_post_meta( get_the_ID(), 'header_social_icons', true );
		}
		
		$html = shiftkey_get_social_icons( $social_icons, array('wrap' => 'li', 'wrapclass' => 'header-socials clearfix', 'linkwrap' => 'span') );
	        
	    return apply_filters( 'shiftkey_topbar_social_icons', $html );    
	}
	
	public static function get_nav_button(){
		$html = '';

		

		$menu_button_display = shiftkey_get_option('header_button_display', false);
		$newval = '';
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'header_button_display', true );
		}
		$menu_button_display = ( $newval != '' )? $newval : $menu_button_display;

		if( $menu_button_display == false ) return $html;

		//$navbar_button = shiftkey_get_option('header_button', self::get_default_nav_buttons());
		$navbar_button = shiftkey_get_option('header_button', self::get_default_nav_buttons());
		$newval = array();
		if(shiftkey_get_page_template_type() == 'landing'){
			$newval = get_post_meta( get_the_ID(), 'header_button', true );
		}
		$navbar_button = ( !empty($newval) )? $newval : $navbar_button;

		if( !empty($navbar_button) ){
			$i=1;

			$darkcolorArr = shiftkey_default_dark_color_classes(array('prefix' => 'btn-'));   
    		$darkcolortraArr = shiftkey_default_dark_color_classes(array('prefix' => 'btn-tra-'));
			
		}
		return apply_filters( 'shiftkey_get_nav_button', $html );

	}

	public static function get_header_buttons(){
		$html = '';
		$post_id = self::get_id();

		//if( is_singular('product') ) return false;

		$button_display = get_post_meta( $post_id, 'button_display', true );
		$button_display = ($button_display)? $button_display : false;
		if( $button_display == false ) return $html;

		$buttons = get_post_meta( $post_id, 'buttons', true );
		$buttons = ($buttons)? $buttons : self::get_default_header_buttons();
		if( !empty($buttons) ){
			$i=1;
			$html .= '<div class="btns-wraper download-btn">';
			foreach ($buttons as $key => $value) {
				extract($value);
				$title = sprintf( _x('%s', 'Navbar button title #'.$key, 'shiftkey'), $title );
				$html .= '<a href="'.esc_url($link).'" class="button active-btn sabbi-button hupup '.esc_attr($style).'" target="'.esc_attr($target).'">'.esc_attr($title).'</a>';
				$i++;
			}
			$html .= '</div>';
		}
		return apply_filters( 'shiftkey_get_header_button', $html );

	}

	public static function get_searchform(){

		$header_search_display = Shiftkey_Header_Config::header_search_icon_is_on();

		if( $header_search_display == false ) return false;

		$placeholder = shiftkey_get_option( 'nav_search_placeholder', 'What are you looking for?' );
		$placeholder = apply_filters( 'shiftkey/nav_search_placeholder', $placeholder );
		$placeholder = sprintf( _x('%s', 'Navbar Search placeholder text', 'shiftkey'), $placeholder );

		return '<li class="cart-icon search-menu-item menu-item menu-item-has-children shiftkey-megamenu megamenu-navbarwidth dropdown">
			<a class="nav-link nav-icon dropdown-toggle"  data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false"><span class="fa fa-search"></span></a>
			<div class="dropdown-menu collapse" id="headersearch" role="menu">
				<form class="header-search-form" action="'.esc_url( home_url( '/' ) ).'">
					<div class="input-group">
		                <input class="form-control" placeholder="'.esc_attr($placeholder).'" type="text" name="s">
		                <div class="input-group-append">
					    	<button class="btn btn-primary white-color" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
					 	</div>
		            </div>
		        </form>
	        </div>
        </li>';
	}

	public static function header_breadcrumb_is_on(){

		$post_id = self::get_id();

		$display = get_post_meta( $post_id, 'title_display', true );
		$display = ( $display != '' )? $display : true;		

		return apply_filters( 'shiftkey_header_breadcrumb_is_on', $display );
	}

	public static function header_banner_is_on(){		
		$post_id = self::get_id();
		$default = true;

		$banner = shiftkey_get_option('title_display', true);

		if( 'post' == get_post_type() ):
			if( is_singular() ){
				$single_post_header = shiftkey_get_option( 'single_post_header', 'off' );
				$single_post_header = ( $single_post_header == 'on' )? true : false;
				$banner = ( is_singular('post') )? $single_post_header : $banner;
			}else{				
				$banner = get_post_meta( $post_id, 'title_display', true );
				$banner = ($banner != '' )? $banner : true;				
			}	
			return $banner;								
		endif;

		if( 'page' == get_post_type() ):		
			$banner = get_post_meta( $post_id, 'title_display', true );				
			$banner = ($banner != '' )? $banner : $default;
			return $banner;
		endif;	

		if( 'product' == get_post_type() ):
			if( is_singular() ){
				$single_header = shiftkey_get_option( 'single_product_header', 'on' );
				$banner = ( $single_header == 'on' )? true : false;				
			}else{				
				$banner = get_post_meta( $post_id, 'title_display', true );
				$banner = ($banner != '' )? $banner : true;				
			}	
			return $banner;
								
		endif;


		$banner = ( is_singular('portfolio') )? shiftkey_get_option( 'single_portfolio_header', false ) : $banner;
		$banner = ( is_singular('team') )? shiftkey_get_option( 'single_team_header', false ) : $banner;
		
		$banner = ( is_404() )? false : $banner;

		if( !is_bool($banner) ){
			$banner = ( $banner == 'on' )? true : false;
		}
		

		return $banner;
	}

	public static function get_header_banner_image(){
		$post_id = self::get_id();
	}


	public static function shortcode(){

		$post_id = self::get_id();

		$shortcode = get_post_meta( $post_id, 'shortcode', true );
		if( $shortcode == '' )  return false;

		$shortcode = '<div class="slider-wrapper">'.$shortcode.'</div>';

		return $shortcode;
	}
	
	public static function header_image_url($header_image){
		
		$post_id = self::get_id();


		

		if ( 'portfolio' == get_post_type() ){
			$id = shiftkey_get_post_type_archive_page_id('portfolio');
			if($id) $post_id = $id; 
		}

		
		if( function_exists('is_woocommerce') ){
			if( (get_post_type() == 'product') ):
				$post_id = get_option( 'woocommerce_shop_page_id' );
			endif;
		}

		$new_header_image = get_post_meta( $post_id, 'header_bg', true ); 

		
		
		$header_image = ( $new_header_image != '' )? $new_header_image : $header_image;

		return $header_image;
	}


	public static function get_title(){
		$title = get_the_title();
		$post_id = self::get_id();

		if(is_page()){
			$custom_title = get_post_meta( $post_id, 'custom_title', true );
			$newtitle = get_post_meta( $post_id, 'title', true );
			$title = ( $custom_title && ($newtitle != '') )? $newtitle : $title;
		}elseif ((get_post_type() == 'post')) {
			$post_page_id = get_option( 'page_for_posts' );
			if( (is_home() || is_single()) && ($post_page_id) ){
				$title = get_the_title( $post_id );
				$newtitle = get_post_meta( $post_id, 'title', true );
				$title = ( $newtitle != '' )? $newtitle : $title;
			}elseif( is_category() ){
				$prefix = '';
				$title = single_cat_title( $prefix, false );
			}elseif( is_tag() ){
				$prefix = '';
				$title = single_tag_title( $prefix, false );
			}elseif( is_archive() ){				
				$title = get_the_archive_title();
			}else{
				$title = esc_attr__( 'Blog', 'shiftkey' );
			}

			
		}

		$post_type_Arr = array('portfolio', 'team', 'service');
		foreach ($post_type_Arr as $key => $value) {
			if ( $value == get_post_type() ){
				$id = shiftkey_get_post_type_archive_page_id($value);
				$newtitle = get_post_meta( $id, 'title', true );
				$title = ( $newtitle != '' )? $newtitle : get_the_title($id);

				if(is_singular()){
					$title = get_the_title();
				}
			}
		}

		if( function_exists('is_woocommerce') ){
			if( (get_post_type() == 'product') ):
				$newtitle = get_post_meta( $post_id, 'title', true );
				$title = ( $newtitle != '' )? $newtitle : get_the_title($post_id);				
			endif;
		}

		
		
		if(is_404()){
			$title = shiftkey_get_option('404_title', '404');
		}

		if( is_search() ){
			$title = get_search_query();
		}

		return apply_filters( 'shiftkey_header_title', $title );
	}

	public static function get_subtitle(){
		$title = '';
		$post_id = self::get_id();

		$custom = get_post_meta( $post_id, 'custom_title', true );
		$newtitle = get_post_meta( $post_id, 'subtitle', true );
		$title = ( $custom && ($newtitle != '') )? $newtitle : $title;

		if($title != ''){
			$title = esc_attr($title);
		}
		$title = shiftkey_parse_text($title, array('tagclass' => 'theme-color'));

		return apply_filters( 'shiftkey_header_subtitle', $title );
	}
}

new ShiftkeyHeader();