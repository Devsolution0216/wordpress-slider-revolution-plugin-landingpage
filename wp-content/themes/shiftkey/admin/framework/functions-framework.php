<?php
if( !function_exists('shiftkey_breakCSS') ){
	function shiftkey_breakCSS($css){

	    $results = array();

	    preg_match_all('/(.+?)\s?\{\s?(.+?)\s?\}/', $css, $matches);
	    foreach($matches[0] AS $i=>$original)
	        foreach(explode(';', $matches[2][$i]) AS $attr)
	            if (strlen(trim($attr)) > 0) // for missing semicolon on last element, which is legal
	            {
	                list($name, $value) = explode(':', $attr);
	                $results[$matches[1][$i]][trim($name)] = trim($value);
	            }
	    return $results;
	}
}

if( !function_exists('shiftkey_breakCSS_iconArr') ){
	function shiftkey_breakCSS_iconArr($css){
		$css = shiftkey_breakCSS($css);
		$css = array_filter($css);

	    $results = array();

	    foreach ($css as $key => $value) {
	    	$key = str_replace(".","", $key );
	    	$key = str_replace(":before","", $key );

	    	$value = str_replace("-"," ", $key );

	    	$results[] = array ( $key => $value);
	    	
	    }
	    
	    return $results;
	}
}

function shiftkey_sidebar_common_class(){	
	
	$array = array( 'sidebar-div', 'single-widget', 'b-bottom', 'pb-50', 'mb-50' );

	return $array;
}

add_filter( 'perch_modules/vc_category', 'shiftkey_vc_category' );
if( !function_exists('shiftkey_vc_category') ){
	function shiftkey_vc_category(){
		return 'Shiftkey';
	}
}

add_filter( 'perch_modules/vc_class', 'shiftkey_vc_class' );
if( !function_exists('shiftkey_vc_class') ){
	function shiftkey_vc_class(){
		return 'shiftkey-vc';
	}
}

function shiftkey_input_field_settings_options(){
    $array = array(
        'input_field' => true,
        'textarea' => false,  
        'google_font_settings' => false,          
        'typo_settings' => true,
        'highlight_settings' => true,
        'typo_std' => '',
        'highlight_std' => '',       
    );

    if( class_exists('PerchVcMap') ){
    	$array['typo_fields'] = PerchVcMap::typography_fields_settings_options();
        $array['highlight_fields'] = PerchVcMap::highlight_fields_settings_options();
    }
    return $array;
}


if (!function_exists('shiftkey_get_option')) {
    function shiftkey_get_option($option_id, $default = ''){
        global $shiftkey_options;
       
        /* look for the saved value */
        if (isset($shiftkey_options[$option_id])) {
            return $shiftkey_options[$option_id];
        }
        return $default;
    }
}

function shiftkey_range_option( $start, $limit, $step = 1 ) {
  if ( $step < 0 )
  $step = 1;
  $range = range( $start, $limit, $step );	
  foreach( $range as $k => $v ) {
    if ( strpos( $v, 'E' ) ) {
      $range[$k] = 0;
    }
  }

  return $range;
}
function shiftkey_wrapper_id(){
	$id = 'page';
	$id = apply_filters( 'shiftkey_wrapper_id', $id );
	echo sanitize_title($id);
}

function shiftkey_wrapper_class( $class = '' ) {
	// Separates classes with a single space, collates classes for wrapper element
	echo 'class="' . join( ' ', shiftkey_get_wrapper_class( $class ) ) . '"';
}

function shiftkey_get_wrapper_class( $class = '' ) {
	global $wp_query;

	$classes = array('page');	
	if( Shiftkey_Header_Config::breadcrumbs_display_is_on() && !is_front_page() && function_exists('bcn_display_list')){
		$classes[] = '';
	}else{
		$classes[] = 'no-breadcrumbs-page';
	}

	if( is_404() ){
		$classes[] = 'bg-primary';
		$classes[] = 'white-color';
	}

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'shiftkey_wrapper_class', $classes, $class );

	return array_unique( $classes );
}


function shiftkey_header_id(){
	$id = 'header';
	$id = apply_filters( 'shiftkey_header_id', $id );
	echo sanitize_title($id);
}

function shiftkey_header_class( $class = '' ) {
	// Separates classes with a single space, collates classes for wrapper element
	echo 'class="' . join( ' ', shiftkey_get_header_class( $class ) ) . '"';
}

function shiftkey_get_header_class( $class = '' ) {
	global $wp_query;

	$classes = array('header');	

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'shiftkey_header_class', $classes, $class );

	return array_unique( $classes );
}

function shiftkey_navbar_class( $class = '' ) {
	// Separates classes with a single space, collates classes for body element
	echo 'class="' . join( ' ', shiftkey_get_navbar_class( $class ) ) . '"';
}

function shiftkey_get_navbar_class( $class = '' ) {
	global $wp_query;

	$classes = array('navbar', 'navbar-expand-md', 'hover-menu');		

		$nav_style = 'bg-light';
	
	$nav_style = shiftkey_get_option( 'nav_bg_class', 'bg-tra-dark' );	
	$nav_style = apply_filters( 'shiftkey/nav_bg_class', $nav_style );	

	$custom_nav_style = '';	
	if( strpos($nav_style, 'bg-custom') !==  false ){
		$custom_nav_style = shiftkey_get_option( 'nav_bg_type' );	
		$custom_nav_style = apply_filters( 'shiftkey/nav_bg_type', $custom_nav_style );		
	}

	$sticky_navbar = shiftkey_get_option( 'header_sticky_nav', true );
	$sticky_navbar = apply_filters( 'shiftkey/header_sticky_nav', $sticky_navbar );	
	//print_r($sticky_navbar); die;
	$classes[] = $nav_style;
	if( $sticky_navbar ){
		$nav_style_scroll = shiftkey_get_option( 'nav_style_scroll', 'white-scroll' );	
		$nav_style_scroll = apply_filters( 'shiftkey/nav_style_scroll', $nav_style_scroll );
		$classes[] = 'fixed-top';
		$classes[] = $nav_style_scroll;

		$args = array('prefix' => '', 'postfix' => '-scroll');
		$dark_classes = shiftkey_default_dark_color_classes($args);
		if( $custom_nav_style == 'white-color' ){
			$dark_classes[] =  $nav_style_scroll;
		}
		$classes[] = in_array($nav_style_scroll, $dark_classes)? 'scrollbg-dark' : 'scrollbg-light';

	}

	$dark_classes = shiftkey_default_dark_color_classes();
	if( $custom_nav_style == 'white-color' ){
		$dark_classes[] =  $nav_style;
	}

	
	if( is_singular('post') && !ShiftkeyHeader::header_banner_is_on() ){
		$nav_style = 'bg-light';
		$classes[] = 'bg-light';
	}
	
	

	if($nav_style == 'bg-tra-dark'){
		$classes[] = 'bg-tra navbar-dark';
	}else{
		$classes[] = in_array($nav_style, $dark_classes)? 'navbar-dark' : 'navbar-light';
	}
	   

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );


	$classes = apply_filters( 'shiftkey_navbar_class', $classes, $class );

	return array_unique( $classes );
}

function shiftkey_breadcrumbs_id(){
	$id = 'blogs-page';
	$id = apply_filters( 'shiftkey_breadcrumbs_id', $id );
	echo sanitize_title($id);
}

function shiftkey_breadcrumbs_class( $class = '' ) {
	// Separates classes with a single space, collates classes for wrapper element
	echo 'class="' . join( ' ', shiftkey_get_breadcrumbs_class( $class ) ) . '"';
}

function shiftkey_get_breadcrumbs_class( $class = '' ) {
	global $wp_query;

	$classes = array('breadcrumbs-area', 'page-hero-section', 'division', 'parallax');
	$bg_class = shiftkey_get_option( 'header_bg_class', 'bg-dark');	
	$classes[] = $bg_class;
	$dark_class = shiftkey_default_dark_color_classes();	
	$classes[] = in_array( $bg_class, $dark_class)? 'white-color' : '';
	if( strpos($bg_class, 'bg-custom') !==  false){
  		$classes[] = shiftkey_get_option( 'header_bg_type', '');
  	}

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'shiftkey_breadcrumbs_class', $classes, $class );

	return array_unique( $classes );
}

/*
* Container
*/
function shiftkey_container_id(){
	$id = shiftkey_default_container_id();	
	$id = apply_filters( 'shiftkey_container_id', $id );
	echo sanitize_title($id);
}

function shiftkey_container_class( $class = '' ) {
	// Separates classes with a single space, collates classes for wrapper element
	echo 'class="' . join( ' ', shiftkey_get_container_class( $class ) ) . '"';
}

function shiftkey_get_container_class( $class = '' ) {
	global $wp_query;

	$classes = array('division');	
	$classes[] = 'shiftkey-'.get_post_type().'-content';

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'shiftkey_container_class', $classes, $class );

	return array_unique( $classes );
}

/*
* Sidebar
*/
function shiftkey_sidebar_id(){
	$id = 'shiftkey-'.get_post_type().'-sidebar';
	
	$id = apply_filters( 'shiftkey_sidebar_id', $id );
	echo sanitize_title($id);
}

function shiftkey_sidebar_class( $class = '' ) {
	// Separates classes with a single space, collates classes for wrapper element
	echo 'class="' . join( ' ', shiftkey_get_sidebar_class( $class ) ) . '"';
}

function shiftkey_get_sidebar_class( $class = '' ) {
	global $wp_query;

	$classes = array('col-md-4');	
	$classes[] = 'shiftkey-'.get_post_type().'-sidebar';

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'shiftkey_sidebar_class', $classes, $class );

	return array_unique( $classes );
}

/*
* Footer
*/
function shiftkey_footer_id(){
	$id = 'footer';
	$id = apply_filters( 'shiftkey_footer_id', $id );
	echo sanitize_title($id);
}

function shiftkey_footer_class( $class = '' ) {
	// Separates classes with a single space, collates classes for body element
	echo 'class="' . join( ' ', shiftkey_get_footer_class( $class ) ) . '"';
}

function shiftkey_get_footer_class( $class = '' ) {
	global $wp_query;

	$classes = array('footer', 'division');
	$bg_class = shiftkey_get_option( 'footer_bg_class');	
	$bg_class = apply_filters( 'shiftkey/footer_bg_class', $bg_class );
	$classes[] = $bg_class;

	$custom_bg_style = '';	
	if( strpos($bg_class, 'bg-custom') !==  false){
		$custom_style = shiftkey_get_option( 'footer_bg_type' );	
		$custom_style = apply_filters( 'shiftkey/custom_footer_bg_type', $custom_style );		
		$classes[] = $custom_style;
	}

	$dark_class = shiftkey_default_dark_color_classes();
  	$classes[] = in_array( $bg_class, $dark_class)? 'white-color' : '';

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );


	$classes = apply_filters( 'shiftkey_footer_class', $classes, $class );

	return array_unique( $classes );
}

/*
* Newsletter
*/
function shiftkey_newsletter_id(){
	$id = 'newsletter-1';
	$id = apply_filters( 'shiftkey_footer_id', $id );
	echo sanitize_title($id);
}

function shiftkey_newsletter_class( $class = '' ) {
	// Separates classes with a single space, collates classes for body element
	echo 'class="' . join( ' ', shiftkey_get_newsletter_class( $class ) ) . '"';
}

function shiftkey_get_newsletter_class( $class = '' ) {
	global $wp_query;

	$classes = array('newsletter-section', 'wide-80', 'division', 'parallax', 'bg-image');
	$bg_class = shiftkey_get_option( 'newsletter_bg_class');	
	$bg_class = apply_filters( 'shiftkey/newsletter_bg_class', $bg_class);	
	$classes[] = $bg_class;

	$custom_bg_style = '';	
	if( strpos($bg_class, 'bg-custom') !==  false){
		$custom_style = shiftkey_get_option( 'newsletter_bg_type' );	
		$custom_style = apply_filters( 'shiftkey/custom_newsletter_bg_type', $custom_style );		
		$classes[] = $custom_style;
	}

	$dark_class = shiftkey_default_dark_color_classes();
  	$classes[] = in_array( $bg_class, $dark_class)? 'white-color' : '';

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );


	$classes = apply_filters( 'shiftkey_newsletter_class', $classes, $class );

	return array_unique( $classes );
}

add_filter( 'perch_modules/supported_social_links', 'shiftkey_default_social_links_callback' );
function shiftkey_default_social_links_callback($array = array()){
	$new_array = array(
		'500px' 		=> array( 'title' => '500px', 'url' => '#', 'icon' => 'fab fa-500px', ),
		'amazon' 		=> array( 'title' => 'Amazon', 'url' => '#', 'icon' => 'fab fa-amazon', ),
		'adn' 			=> array( 'title' => 'Adn', 'url' => '#', 'icon' => 'fab fa-adn', ),
		'android' 		=> array( 'title' => 'Android', 'url' => '#', 'icon' => 'fab fa-android', ),
		'angellist' 	=> array( 'title' => 'Angel list', 'url' => '#', 'icon' => 'fab fa-angellist', ),
		'bandcamp' 		=> array( 'title' => 'Bandcamp', 'url' => '#', 'icon' => 'fab fa-bandcamp', ),
		'behance' 		=> array( 'title' => 'Behance', 'url' => '#', 'icon' => 'fab fa-behance', ),
		'bitbucket' 	=> array( 'title' => 'Bitbucket', 'url' => '#', 'icon' => 'fab fa-bitbucket', ),
		'bitcoin' 		=> array( 'title' => 'Bitcoin', 'url' => '#', 'icon' => 'fab fa-bitcoin', ),
		'codepen' 		=> array( 'title' => 'Codepen', 'url' => '#', 'icon' => 'fab fa-codepen', ),
		'delicious' 	=> array( 'title' => 'Delicious', 'url' => '#', 'icon' => 'fab fa-delicious', ),
		'digg' 			=> array( 'title' => 'Digg', 'url' => '#', 'icon' => 'fab fa-digg', ),
		'dribbble' 		=> array( 'title' => 'Dribbble', 'url' => '#', 'icon' => 'fab fa-dribbble', ),
		'dropbox' 		=> array( 'title' => 'Dropbox', 'url' => '#', 'icon' => 'fab fa-dropbox', ),
		'facebook' 		=> array( 'title' => 'Facebook', 'url' => '#', 'icon' => 'fab fa-facebook-f', ),
		'flickr' 		=> array( 'title' => 'Flickr', 'url' => '#', 'icon' => 'fab fa-flickr', ),
		'git' 			=> array( 'title' => 'Git', 'url' => '#', 'icon' => 'fab fa-git', ),
		'github' 		=> array( 'title' => 'Github', 'url' => '#', 'icon' => 'fab fa-github', ),	
		'gitlab' 		=> array( 'title' => 'Gitlab', 'url' => '#', 'icon' => 'fab fa-gitlab', ),
		'google-plus' 	=> array( 'title' => 'Google-plus', 'url' => '#', 'icon' => 'fab fa-google-plus', ),
		'instagram' 	=> array( 'title' => 'Instagram', 'url' => '#', 'icon' => 'fab fa-instagram', ),
		'jsfiddle' 		=> array( 'title' => 'jsfiddle', 'url' => '#', 'icon' => 'fab fa-jsfiddle', ),
		'linkedin' 		=> array( 'title' => 'Linkedin', 'url' => '#', 'icon' => 'fab fa-linkedin', ),
		'linux' 		=> array( 'title' => 'Linux', 'url' => '#', 'icon' => 'fab fa-linux', ),
		'linode' 		=> array( 'title' => 'Linode', 'url' => '#', 'icon' => 'fab fa-linode', ),
		'medium' 		=> array( 'title' => 'Medium', 'url' => '#', 'icon' => 'fab fa-medium', ),
		'meetup' 		=> array( 'title' => 'Meetup', 'url' => '#', 'icon' => 'fab fa-meetup', ),
		'odnoklassniki' => array( 'title' => 'Odnoklassniki', 'url' => '#', 'icon' => 'fab fa-odnoklassniki', ),
		'paypal' 		=> array( 'title' => 'Paypal', 'url' => '#', 'icon' => 'fab fa-paypal', ),
		'pinterest' 	=> array( 'title' => 'Pinterest', 'url' => '#', 'icon' => 'fab fa-pinterest', ),
		'reddit' 		=> array( 'title' => 'Reddit', 'url' => '#', 'icon' => 'fab fa-reddit', ),
		'scribd' 		=> array( 'title' => 'Scribd', 'url' => '#', 'icon' => 'fab fa-scribd', ),
		'share' 		=> array( 'title' => 'Share-alt', 'url' => '#', 'icon' => 'fab fa-share-alt', ),
		'skype' 		=> array( 'title' => 'Skype', 'url' => '#', 'icon' => 'fab fa-skype', ),
		'slack' 		=> array( 'title' => 'Slack', 'url' => '#', 'icon' => 'fab fa-slack', ),
		'soundcloud' 	=> array( 'title' => 'Soundcloud', 'url' => '#', 'icon' => 'fab fa-soundcloud', ),
		'stack-exchange' => array( 'title' => 'Stack-exchange', 'url' => '#', 'icon' => 'fab fa-stack-exchange', ),
		'stack-overflow' => array( 'title' => 'Stack-overflow', 'url' => '#', 'icon' => 'fab fa-stack-overflow', ),
		'stumbleupon' 	=> array( 'title' => 'Stumbleupon', 'url' => '#', 'icon' => 'fab fa-stumbleupon', ),
		'trello' 		=> array( 'title' => 'Trello', 'url' => '#', 'icon' => 'fab fa-trello', ),
		'tumblr' 		=> array( 'title' => 'Tumblr', 'url' => '#', 'icon' => 'fab fa-tumblr', ),
		'twitter' 		=> array( 'title' => 'Twitter', 'url' => '#', 'icon' => 'fab fa-twitter', ),
		'vimeo' 		=> array( 'title' => 'Vimeo', 'url' => '#', 'icon' => 'fab fa-vimeo', ),
		'vk' 			=> array( 'title' => 'VK', 'url' => '#', 'icon' => 'fab fa-vk', ),
		'whatsapp' 		=> array( 'title' => 'Whatsapp', 'url' => '#', 'icon' => 'fab fa-whatsapp', ),
		'wikipedia' 	=> array( 'title' => 'Wikipedia', 'url' => '#', 'icon' => 'fab fa-wikipedia-w', ),
		'wordpress' 	=> array( 'title' => 'WordPress', 'url' => '#', 'icon' => 'fab fa-wordpress', ),
		'xing' 			=> array( 'title' => 'Xing', 'url' => '#', 'icon' => 'fab fa-xing', ),
		'yahoo' 		=> array( 'title' => 'Yahoo', 'url' => '#', 'icon' => 'fab fa-yahoo', ),
		'yelp' 			=> array( 'title' => 'Yelp', 'url' => '#', 'icon' => 'fab fa-yelp', ),
		'youtube' 		=> array( 'title' => 'Youtube', 'url' => '#', 'icon' => 'fab fa-youtube', ),

	);
	$array = array_merge($array, $new_array );
	return $array;
}

add_filter( 'perch_modules/supported_buttons', 'shiftkey_default_buttons_set_callback' );
function shiftkey_default_buttons_set_callback($array = array()){
	$new_array = array(
		'btn1' => array(
			'name' => 'Amazon - image', 
			'title' => 'Buy on amazon', 
			'type' => 'image',
			'url' => '#', 
			'target'=> '_blank',
			'image'=>SHIFTKEY_URI.'/images/amazon.png',
			'style' => ''
		),
		'btn2' => array(
			'name' => 'App store - image', 
			'title' => 'App store', 
			'type' => 'image',
			'url' => '#', 
			'target'=> '_blank',
			'image'=>SHIFTKEY_URI.'/images/appstore.png',
			'style' => ''
		),
		'btn3' => array(
			'name' => 'Google play - image', 
			'title' => 'Google play', 
			'type' => 'image',
			'url'=>'#',
			'target'=> '_blank',
			'image'=>SHIFTKEY_URI.'/images/googleplay.png',
			'style' => ''
		),
		'btn4' => array(
			'name' => 'Get started - text button', 
			'title' => 'Get started', 
			'type' => 'text',
			'url'=>'#',
			'target'=> '_self',
			'style' => ''
		),
		'btn5' => array(
			'name' => 'Contact us - text button', 
			'title' => 'Contact us', 
			'type' => 'text',
			'url'=>'#',
			'target'=> '_self',
			'style' => ''
		),
	);
	$array = array_merge($array, $new_array );

	return $array;
}

function shiftkey_supported_social_links(){
	$array = shiftkey_default_social_links_callback();

	$options = get_option('shiftkey_settings', array());
	if( !empty($options) ):			
	$array = $options['social_links_group'];
	//$array = apply_filters( 'perch_modules/supported_social_links_meta', $array, $meta );
	endif;
	
	return $array;
}

function shiftkey_supported_buttons(){
	$array = shiftkey_default_buttons_set_callback();	

	$options = get_option('shiftkey_settings', array());
	if( !empty($options) ):			
	$array = $options['buttons_group'];
	//$array = apply_filters( 'perch_modules/supported_buttons_meta', $array, $meta );
	endif;	
	return $array;
}

function shiftkey_supported_social_links_callback($array = array()){	
	$supported = shiftkey_supported_social_links();
	foreach ($supported as $key => $value) {
		$array[$key] = $value['title'];
	}
	return $array;
}


function shiftkey_supported_buttons_callback($array = array()){	
	$supported = shiftkey_supported_buttons();
	foreach ($supported as $key => $value) {		
		$array[$key] = isset($value['name'])? $value['name'] : '';
	}
	return $array;
}

function shiftkey_predefined_page_templates( $array = array() ){
	$_array = array(
		'blog' => array(
			'title' => esc_attr__('Blog page', 'shiftkey'),
			'id' => 'blog-page',
			'class' => 'wide-100 blog-page-section division',
			'sidebar' => array(
				'id' => 'sidebar-right',
				'class' => '',
			),
		),
		'blog_single' => array(
			'title' => get_the_title(),
			'id' => 'single-post',
			'class' => 'wide-100 blog-page-section division',
			'sidebar' => array(
				'id' => 'sidebar-right',
				'class' => '',
			),
		),
		'faqs' => array(
			'title' => esc_attr__('Faq page', 'shiftkey'),
			'id' => 'faqs-page',
			'class' => 'bg-fixed download-section division',
		),
		'terms' => array(
			'title' => esc_attr__('Terms page', 'shiftkey'),
			'id' => 'terms-page',
			'class' => 'terms-section division',
		),
		'download' => array(
			'title' => esc_attr__('Download page', 'shiftkey'),
			'id' => 'download-page',
			'class' => 'bg-fixed download-section division',
		),
		'page' => array(
			'title' => esc_attr__('Default page', 'shiftkey'),
			'id' => 'page',
			'class' => 'page-section wide-100 division',
		),
		
	);
	return array_merge($array, $_array);
}
add_filter( 'perch_modules/predefined_page_templates', 'shiftkey_predefined_page_templates' );

function shiftkey_predefined_page_templates_options(){
	$array = shiftkey_predefined_page_templates();
	$output = array();
	if( !empty($array) ):
		foreach ($array as $key => $value) {
			$output[$key] = $value['title'];
		}
	endif;

	return $output;
}

function shiftkey_get_predefined_template_attr( $id = NULL, $attr = 'class'  ){
	$array = shiftkey_predefined_page_templates();
	if( $id == NULL ) return false;
	if( !isset($array[$id]) ) return false;
	$template = $array[$id];
	if( !isset($template[$attr]) ) return false;
	$output = $template[$attr];
	return $output;
}

function shiftkey_set_default_vc_values($default, $args){
	
	foreach ($default as $key => $value) {
        $arrKey = array_search($key, array_column($args, 'param_name'));
        if( !is_array($value) ){
        	if( isset($args[$arrKey]['value']) && is_array($args[$arrKey]['value']) ){
            	$args[$arrKey]['std'] = $value;
	        }elseif( isset($args[$arrKey]['settings']) && is_array($args[$arrKey]['settings']) ){
	        	$args[$arrKey]['std'] = $value;
	        }else{
	            $args[$arrKey]['value'] = $value;
	        }
        }else{
        	$args[$arrKey] = array_merge($args[$arrKey], $value );        	
        }       
    }

    return $args;
}

function shiftkey_range_css_option($prefix, $property, $args = array()){
	$default = array('start' => 0, 'limit' => 10, 'step' => 1, 'unit' => 'px');
	extract(shortcode_atts($default , $args));

   	$range = shiftkey_range_option( $start, $limit, $step );
	$array = array();
    foreach( $range as $k => $v ) {
      $array[] = $prefix.$v. ' { '.$property.': '. $v . $unit . '; }';
    } 

   return apply_filters( 'perch_vc_dropdown_options', $array );
}

function shiftkey_get_social_links_by_options($iconsArr = array(), $args = array()){

	$icon_list = shiftkey_supported_social_links();	
	$social_links = array();
	if( !empty($iconsArr) ):			
		foreach ($iconsArr as $key => $value) {
			$array = shiftkey_array_search_by_key_value($icon_list, 'key', $value);		
			$social_links[] = isset($array[0])? $array[0] : '';
		}
	endif;
	$social_links = array_filter($social_links);
	return Shiftkey::get_social_icons_html($social_links, $args);
}

function shiftkey_spacing_css_style(){

  	$css = '';
  	// Wide
  	$wide = '.'.shiftkey_wide_class_prefix();
	$arr = shiftkey_range_css_option( $wide, 'padding-bottom', array('limit' => 200, 'step' => 10));
	$css .= implode(' ', $arr);
	$arr = shiftkey_range_css_option( $wide, 'padding-top', array('start' => 100, 'limit' => 200, 'step' => 10));
	$css .= implode(' ', $arr);

	// Ind
	$ind = '.'.shiftkey_ind_class_prefix();
	$arr = shiftkey_range_css_option($ind, 'padding-left', array('limit' => 150, 'step' => 5));
	$css .= implode(' ', $arr);
	$arr = shiftkey_range_css_option($ind, 'padding-right', array('limit' => 150, 'step' => 5));
	$css .= implode(' ', $arr);

	// Margin
	$args = array('limit' => 200, 'step' => 5);
	$mtop  		= '.'.shiftkey_margin_top_class_prefix();
	$arr = shiftkey_range_css_option($mtop, 'margin-top', $args );
	$css .= implode(' ', $arr);

	$mright  		= '.'.shiftkey_margin_right_class_prefix(); 
	$arr = shiftkey_range_css_option($mright, 'margin-right', $args );
	$css .= implode(' ', $arr);

	$mbottom  	= '.'.shiftkey_margin_bottom_class_prefix(); 
	$arr = shiftkey_range_css_option($mbottom, 'margin-bottom', $args );
	$css .= implode(' ', $arr);

	$mleft  	= '.'.shiftkey_margin_left_class_prefix();
	$arr = shiftkey_range_css_option($mleft, 'margin-left', $args );
	$css .= implode(' ', $arr);

	// Padding
	$args = array('limit' => 200, 'step' => 5);
	$ptop = '.'.shiftkey_padding_top_class_prefix();
	$arr = shiftkey_range_css_option($ptop, 'padding-top', $args );
	$css .= implode(' ', $arr);
	$pbottom = '.'.shiftkey_padding_bottom_class_prefix();
	$arr = shiftkey_range_css_option($pbottom, 'padding-bottom', $args );
	$css .= implode(' ', $arr);
	$pleft = '.'.shiftkey_padding_left_class_prefix();
	$arr = shiftkey_range_css_option($pleft, 'padding-left', $args );
	$css .= implode(' ', $arr);
	$pright = '.'.shiftkey_padding_right_class_prefix();
	$arr = shiftkey_range_css_option($pright, 'padding-right', $args );
	$css .= implode(' ', $arr);

  	return $css;
}