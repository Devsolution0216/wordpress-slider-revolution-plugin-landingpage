<?php
/**
 * Brand functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Brand
 */

 // Brand version
 define( 'BRAND_VER','1.9' );

 // Bootstrap version
 define( 'BOOTSTRAP_VER','3.3.6' );

 // Font Awesome version
 define( 'FONTAWESOME_VER','4.7.0' );

 // Swiper version
 define( 'SWIPER_VER','4.1.6' );

if ( ! function_exists( 'brand_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function brand_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Brand, use a find and replace
	 * to change 'brand' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'brand', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Add various custom image sizes.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	 add_image_size( 'post-1', 2000, 1125, true );
	 add_image_size( 'post-2', 1000, 563, true );
	 add_image_size( 'post-3', 670, 377, true );
	 add_image_size( 'post-4', 640, 360, true );
	 add_image_size( 'post-landscape', 800, 600, true );
	 add_image_size( 'post-medium', 540, 304, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'brand' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'audio',
		'gallery',
		'video',
		'quote',
		'link',
	) );

	// Adds support for custom background feature.
	add_theme_support( 'custom-background' );

  // Adds support for selective refresh in the Customizer.
  add_theme_support( 'customize-selective-refresh-widgets' );

}
endif;
add_action( 'after_setup_theme', 'brand_setup' );

/**
 * Set default options
 */
function brand_get_defaults()
{
	$brand_defaults = array(
		'container_width'                  => '1180',
		'container_type'                   => 'fullwidth',
		'footer_nav_fullwidth'             => false,
		'sticky_menu'                      => 'no',
		'mobile_menu_style'                => 'slide',
    'show_site_title'                  => '1',
		'logo_url'                         => '',
    'logo_mobile_url'                  => '',
		'nav_layout'                       => 'inline',
		'nav_search'                       => 'enabled',
    'nav_orientation'                  => 'horizontal',
		'logo_alignment'                   => 'left',
		'header_front_page_height'         => '100vh',
		'header_height'                    => '50vh',
		'header_type_front'                => 'slider',
		'header_type'                      => 'image',
		'slides_number'                    => intval( apply_filters( 'brand_slides_number', 3 ) ),
		'autoplay_slider'                  => true,
		'lazy_loading_slider'              => true,
		'delay_slider'                     => 10000,
		'speed_slider'                     => 1000,
    'header_alignment'                 => 'center',
    'page_title_alignment'             => 'left',
		'body_bg_color'                    => '#eeeeee',
		'body_text_color'                  => '#777777',
		'link_color'                       => '#222222',
		'link_hover_color'                 => '#1ebaf3',
		'sidebar_layout'                   => 'no',
    'featured_position'                => 'inside',
    'footer_widgets'                   => '0',
    'show_excerpt'                     => 'yes',
    'excerpt_length'                   => '20',
    'woocommerce_products_cols'        => 3,
		'compact_menu'                     => 'yes',
	);

	// Slides defaults
	$slides_defaults = array();
	for ($i=1; $i <= $brand_defaults['slides_number']; $i++) {
		$slides_defaults['brand_slide_image' . $i] = get_template_directory_uri() . '/assets/images/slider/img' . $i . '.jpg';
		$slides_defaults['brand_slide_title' . $i] = 'Your Slide Title Here';
		$slides_defaults['brand_slide_subtitle' . $i] = 'Your Subtitle Here';
		$slides_defaults['brand_slide_button_text' . $i] = 'Button text';
		$slides_defaults['brand_slide_button_url' . $i] = '#content';
		$slides_defaults['brand_slide_text_color' . $i] = '#ffffff';
	}
	$brand_defaults = array_merge( $brand_defaults, $slides_defaults );

	return apply_filters( 'brand_option_defaults', $brand_defaults );
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function brand_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'brand_content_width', 1180 );
}
add_action( 'after_setup_theme', 'brand_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function brand_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'brand' ),
		'id'            => 'sidebar-1',
		'description'   => 'Standard sidebar',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Sidebar Column 1', 'brand' ),
		'id'            => 'sidebar-footer-1',
		'description'   => esc_html__( 'Footer sidebar column 1', 'brand' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Sidebar Column 2', 'brand' ),
		'id'            => 'sidebar-footer-2',
		'description'   => esc_html__( 'Footer sidebar column 2', 'brand' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Sidebar Column 3', 'brand' ),
		'id'            => 'sidebar-footer-3',
		'description'   => esc_html__( 'Footer sidebar column 3', 'brand' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Sidebar Column 4', 'brand' ),
		'id'            => 'sidebar-footer-4',
		'description'   => esc_html__( 'Footer sidebar column 4', 'brand' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Mobile Sidebar', 'brand' ),
		'id'            => 'sidebar-mobile',
		'description'   => esc_html__( 'Mobile Sidebar', 'brand' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'brand_widgets_init' );

/**
 * Add Woocommerce support and fit it to Brand Theme.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Build navigation.
 */
require get_template_directory() . '/inc/navigation.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load add-ons options.
 */
require get_template_directory() . '/inc/advanced/add-ons.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom classes.
 */
require get_template_directory() . '/inc/brand-classes.php';

/**
 * Options page.
 */
require get_template_directory() . '/inc/options/brand-options.php';

/**
 * Enqueue scripts and styles.
 */
function brand_scripts() {
	wp_enqueue_style( 'brand-style', get_stylesheet_uri(), BRAND_VER );

	// Loads FontAwesome stylesheet
	wp_enqueue_style('fontawesome-style', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', FONTAWESOME_VER);

	// Loads Google Fonts
	wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Raleway:400,700');

	if( !wp_style_is( 'brand-bootstrap' )) {
		wp_register_style(
			'brand-bootstrap',
			get_template_directory_uri() . '/assets/css/bootstrap.min.css',
			array(),
			BOOTSTRAP_VER,
			'all'
		);
		wp_enqueue_style('brand-bootstrap');
	}

	wp_enqueue_style('brand', get_template_directory_uri() . '/assets/css/brand.min.css', array('brand-bootstrap'), BRAND_VER );

	if( !wp_script_is( 'brand-bootstrap' )) {
		wp_register_script(
			'brand-bootstrap',
			get_template_directory_uri() . '/assets/javascripts/bootstrap.min.js',
			array('jquery'),
			BOOTSTRAP_VER,
			true
		);
		wp_enqueue_script( 'brand-bootstrap' );
	}

	wp_enqueue_script( 'brand', get_template_directory_uri() . '/assets/javascripts/brand.min.js', array(), BRAND_VER, true );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

  wp_register_script( 'brand-video', get_template_directory_uri() . '/assets/javascripts/brand-video.js', array( 'jquery' ), BRAND_VER, true );
  global $post;
  if( ( isset( $post->ID ) && has_post_format( 'video', $post->ID ) ) || is_home() ) {
    wp_enqueue_script( 'brand-video' );
  }

	// Adds Swiper script and styles if Header type is Slider.
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
	if( ( is_front_page() && $brand_settings['header_type_front'] === 'slider' ) || ( ! is_front_page() && $brand_settings['header_type'] === 'slider' ) ) {
		wp_enqueue_script( 'swiper-script', get_template_directory_uri() . '/assets/javascripts/swiper.min.js', array( 'jquery' ), SWIPER_VER, true );
		wp_enqueue_script( 'brand-swiper-script', get_template_directory_uri() . '/assets/javascripts/brand-swiper.js', array( 'swiper-script' ), BRAND_VER, true );
		wp_enqueue_style('brand-swiper-style', get_template_directory_uri() . '/assets/css/swiper.min.css', array(), SWIPER_VER );
		wp_localize_script( 'brand-swiper-script', 'swiper_settings', array(
				'lazy_loading' => $brand_settings['lazy_loading_slider'],
				'autoplay'     => $brand_settings['autoplay_slider'],
				'delay'        => $brand_settings['delay_slider'],
				'speed'        => $brand_settings['speed_slider'],
		));
	}
}
add_action( 'wp_enqueue_scripts', 'brand_scripts' );

// Enqueque script and style for admin
function brand_admin_scripts($hook) {
    if( 'post.php' == $hook || 'post-new.php' == $hook ) {

		//Enqueque script for upload custom image
    	wp_enqueue_script( 'brand-img-uploader',
        	get_template_directory_uri() . '/assets/javascripts/admin/img-uploader.js',
        	array('jquery')
    	);
    	wp_localize_script( 'brand-img-uploader', 'brand_img_uploader_obj', array(
        	'ajax_url' => admin_url( 'admin-ajax.php' ),
          'media_frame_title' => __('Select or Upload Image', 'brand'),
          'media_frame_btn'   => __('Use this image', 'brand'),

    	));

		//Includes WordPress color picker in post and page
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'maxsdesign-color-picker', get_template_directory_uri() . '/assets/javascripts/admin/maxsdesign-color-picker.js', array('wp-color-picker'), BRAND_VER, true );

		// Enqueque styles for post and page metaboxes
		wp_enqueue_style('maxsdesign-metaboxes-style', get_template_directory_uri() . '/assets/css/admin/maxsdesign-metaboxes.css', array(), BRAND_VER);
	}

  // Enqueue options page styles and scripts
  if( 'appearance_page_brand_setting_page' == $hook || 'toplevel_page_brand_setting_page' == $hook ) {
    wp_enqueue_style('brand-options-styles', get_template_directory_uri() . '/assets/css/admin/brand-option-admin.css', array(), BRAND_VER);
		wp_enqueue_script( 'brand-subscribe', get_template_directory_uri() . '/assets/javascripts/admin/brand-subscribe.js', array('jquery'), BRAND_VER, true );
		$brand_subscribe_nonce = wp_create_nonce( 'brand_subscribe_nonce' );
		wp_localize_script( 'brand-subscribe', 'brand_subscribe', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'    => $brand_subscribe_nonce,
		));
  }
	// Enqueue style for all admin areas
	wp_enqueue_style('brand-admin', get_template_directory_uri() . '/assets/css/admin/brand-admin.css', array(), BRAND_VER);

	// Enqueue script to manage reminder
	wp_enqueue_script( 'brand-rate-reminder', get_template_directory_uri() . '/assets/javascripts/admin/brand-rate-reminder.js' , array(), BRAND_VER );
	$brand_rate_reminder_nonce = wp_create_nonce( 'brand_rate_reminder_nonce' );
	wp_localize_script( 'brand-rate-reminder', 'brand_rate_reminder', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => $brand_rate_reminder_nonce,
			'notice'    => 'brand-reminder',
	));

}
add_action('admin_enqueue_scripts', 'brand_admin_scripts');

/**
 * Adds metaboxes.
 */
require  get_template_directory() . '/inc/metaboxes/metaboxes.php';

/**
 * Loads Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Filter the excerpt length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function brand_custom_excerpt_length( $length ) {
  $brand_settings = wp_parse_args(
    get_option( 'brand_settings', array() ),
    brand_get_defaults()
  );
  return $brand_settings['excerpt_length'];
}
add_filter( 'excerpt_length', 'brand_custom_excerpt_length', 999 );

if (!function_exists('wp_get_attachment_image_url')) {
	/**
	 * Add wp_get_attachment_image_url function in WordPress version 4.3
	 *
	 * @param int          $attachment_id Image attachment ID.
	 * @param string|array $size          Optional. Image size to retrieve. Accepts any valid image size, or an array
	 *                                    of width and height values in pixels (in that order). Default 'thumbnail'.
	 * @param bool         $icon          Optional. Whether the image should be treated as an icon. Default false.
	 * @return string|false Attachment URL or false if no image is available.
	 */
	function wp_get_attachment_image_url( $attachment_id, $size = 'thumbnail', $icon = false ) {
    	$image = wp_get_attachment_image_src( $attachment_id, $size, $icon );
    	return isset( $image['0'] ) ? $image['0'] : false;
	}
}

/**
 * Converts a HEX value to RGB.
 *
 * @since Brand 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function brand_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

if ( !function_exists('brand_sidebar' ) ) {
	/**
	 * Function that adds classes on body element for show sidebar.
	 * @param $classes array of current body classes
	 * @return array array of changed body classes
	 */
	function brand_sidebar($classes) {

		// Get theme options
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);

		global $post;
		if( is_singular() && !is_attachment() ) {
		 	$brand_show_sidebar = get_post_meta( $post->ID, '_brand_show_sidebar', true );
			$classes[] = isset($brand_show_sidebar) && $brand_show_sidebar != '' && $brand_show_sidebar !== 'default' ? $brand_show_sidebar . '-sidebar' : $brand_settings['sidebar_layout'] . '-sidebar';
		} else {
			$classes[] = 	$brand_settings['sidebar_layout'] . '-sidebar';
		}
		return $classes;
	}
	add_filter('body_class','brand_sidebar');
}

/**
 * Adds centered-nav class to the body if navigation layout is set to centered.
 * @since 1.8
 */
add_filter( 'body_class', 'brand_nav_layout');
function brand_nav_layout( $classes='' )
{

	// Get theme options
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	if ( 'centered' === $brand_settings['nav_layout'] ) :
		$classes[] = 'centered-nav ';
	endif;

	return $classes;
}

/**
 * Adds custom classes to the body if display_header_text return true.
 * @since 1.2.0
 */
add_filter( 'body_class', 'brand_show_site_title_class');
function brand_show_site_title_class( $classes='' ) {
  $brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
  if ( $brand_settings['show_site_title'] === '1' ) :
		$classes[] = 'show-site-title';
	endif;

	return $classes;
}

/**
 * Adds custom classes to the body according to navigation orientation setting.
 * @since 1.2.0
 */
add_filter( 'body_class', 'brand_nav_orientation_class');
function brand_nav_orientation_class( $classes='' ) {

  // Get theme options
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
  if ( $brand_settings['nav_orientation'] === 'horizontal' ) :
		$classes[] = 'horizontal-main-nav';
  else:
    $classes[] = 'vertical-main-nav';
	endif;

	return $classes;
}

/**
 * Adds custom classes to the body if is set a header image.
 * @since 1.6.0
 */
add_filter( 'body_class', 'brand_has_custom_header_img_class');
function brand_has_custom_header_img_class( $classes='' ) {
	if( ! brand_no_header() ) {
		$classes[] = 'brand-has-header-image';
	}
  return $classes;
}

/**
 * Add footer site info.
 * @since 1.6
 */
 if ( ! brand_addons_installed() ) {
  function brand_print_site_info() {
    $output = '<a href="https://wordpress.org/" class="customize-unpreviewable">Proudly powered by WordPress</a>';
    $output .= '<span class="sep"> | </span>';
		$output .= 'Theme: brand by <a href="https://www.wp-brandtheme.com" rel="designer" class="customize-unpreviewable">Massimo Sanfelice - Maxsdesign</a>';
		echo apply_filters( 'brand_footer_text', $output );
  }
  add_action( 'brand_site_info', 'brand_print_site_info' );
}

/**
 * Adds sticky-nav class to the body if sticky_menu option is set to yes.
 *
 * @since 1.8.0
 */
add_filter( 'body_class', 'brand_sticky_nav_class');
function brand_sticky_nav_class( $classes='' ) {
  $brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
  if ( $brand_settings['sticky_menu'] === 'yes' ) :
		$classes[] = 'sticky-nav';
	endif;

	return $classes;
}

/**
 * Adds front-page-slider class to the body if header type is set to slider.
 *
 * @since 1.8.0
 */
add_filter( 'body_class', 'brand_frontpage_slider_class');
function brand_frontpage_slider_class( $classes='' ) {
  $brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
  if ( is_front_page() && $brand_settings['header_type_front'] === 'slider' ) :
		$classes[] = 'front-page-slider';
	endif;

	return $classes;
}

/**
 * Adds brand-grid-masonry class or brand-grid-flex to the body according
 * to posts listing style setting.
 *
 * @since 1.8.1
 */
add_filter('body_class','brand_post_listing_stile_class');
function brand_post_listing_stile_class( $classes='' ) {
	global $post;
  if( ( is_home() || is_archive() ) && get_post_type( $post->ID ) === 'post' ) {

    // Get brand blog options.
    $brand_settings = wp_parse_args(
      get_option( 'brand_settings', array() ),
      brand_get_default_blog()
    );
    if( $brand_settings['posts_listing_style'] === 'masonry' ) {
      $classes[] = 'brand-grid-masonry custom-blog-colors';
    } else {
      $classes[] = 'brand-grid-flex';
    }
  }
  return $classes;
}

/**
 * Adds .fs-menu class to the body according to
 * brand settings.
 *
 * @since 1.8.4
 */
add_filter( 'body_class', 'add_full_nav_class');
function add_full_nav_class( $classes='') {
	// Get brand options.
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	if( 'fullscreen' === $brand_settings['mobile_menu_style'] ) {
		$classes[] = 'fs-menu';
	}
	return $classes;
}


/**
 * Saves logo urls in the new options if old options is set.
 *
 * @since 1.8.6
 */
function brand_set_logos_url( $upgrader_object, $options ) {
	if ($options['action'] == 'update' && $options['type'] == 'theme' ) {

		// Get brand background options.
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);

		if( isset( $brand_settings['logo'] ) && intval( $brand_settings['logo'] ) !== 0 ) {
			$brand_settings['logo_url'] = wp_get_attachment_url( $brand_settings['logo'] );
			unset( $brand_settings['logo'] );
		}

		if( isset( $brand_settings['logo_mobile'] ) && intval( $brand_settings['logo_mobile'] ) !== 0 ) {
			$brand_settings['logo_mobile_url'] = wp_get_attachment_url( $brand_settings['logo_mobile'] );
			unset( $brand_settings['logo_mobile'] );
		}

		update_option( 'brand_settings', $brand_settings );
	}
}
add_action( 'upgrader_process_complete', 'brand_set_logos_url', 10, 2 );

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/inc/TGMPA/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'brand_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * @since 1.8.8
 */
function brand_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'      => 'Brand Extra',
			'slug'      => 'brand-extra',
			'required'  => false,
		),

	);

	$config = array(
		'is_automatic' => true,
	);


	tgmpa( $plugins, $config );
}

/**
 * Freemius integration.
 *
 * @since 1.8.9
 */
 // Create a helper function for easy SDK access.
 function bra_fs() {
     global $bra_fs;

     if ( ! isset( $bra_fs ) ) {
         // Include Freemius SDK.
         require_once dirname(__FILE__) . '/freemius/start.php';

         $bra_fs = fs_dynamic_init( array(
             'id'                  => '1761',
             'slug'                => 'brand',
             'type'                => 'theme',
             'public_key'          => 'pk_fdebe910721cd73f229f14cf52928',
             'is_premium'          => false,
             'has_addons'          => false,
             'has_paid_plans'      => false,
         ) );
     }

     return $bra_fs;
 }

 // Init Freemius.
 bra_fs();
 // Signal that SDK was initiated.
 do_action( 'bra_fs_loaded' );

 /**
  * Set transients on theme activation.
  *
  * @since 1.8.9
  */
	function brand_set_rate_reminder() {
		if( ! get_transient( 'brand_rate_reminder_deleted' ) && ! get_transient( 'brand_rate_reminder' ) ) {
			$date = new DateTime();
			set_transient( 'brand_rate_reminder', $date->format( 'Y-m-d' ) );
		}
	}
	add_action( 'after_switch_theme', 'brand_set_rate_reminder' );

	/**
   * Set reminder transients on theme update.
   *
   * @since 1.9
   */
 	function brand_set_update_rate_reminder( $upgrader_object, $options ) {
		if ( $options['action'] == 'update' && $options['type'] == 'theme' ) {
			if( ! get_transient( 'brand_rate_reminder_deleted' ) ) {
	 			$date = new DateTime('2018-03-10');
	 			set_transient( 'brand_rate_reminder', $date->format( 'Y-m-d' ) );
	 		}
		}
 	}
	add_action( 'upgrader_process_complete', 'brand_set_update_rate_reminder', 10, 2 );

	/**
   * Show reminders.
   *
   * @since 1.8.9
   */
 	function brand_show_rate_reminder() {
 		if( get_transient( 'brand_rate_reminder' ) ) {
			$start_date = new DateTime( get_transient( 'brand_rate_reminder' ) );
			$start_date->add( new DateInterval( 'P7D' ) );
			$actual_date = new DateTime();
			if( $actual_date >= $start_date ) {
				$img_msg = sprintf( esc_html( '%1$s' ), '<img src="https://secure.gravatar.com/avatar/2467ad9a4cd4baeb814aed1fe1e9c235?s=150&d=retro&r=g" alt="Brand Theme Author" />' );
				$message = sprintf( esc_html__( '%1$s Hey, I noticed you are using our theme %2$s that%3$ss awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation. %4$s - %5$ sMassimo Sanfelice %6$s %7$s', 'brand' ), '<b>', '&ndash;', '&apos;', '</br>', '<em>', '</em>', '</b>' );
				$message .= sprintf( esc_html__( '%1$s %2$s YES, YOU DESERVE IT %3$s %4$s REMIND ME LATER %3$s %5$s I ALREADY DID  %3$s %6$s', 'brand'  ), '<span>', '<a class="button button-primary clear-rate-reminder" href="https://wordpress.org/support/theme/brand/reviews/?filter=5" target="_blank">', '</a>', '<a class="button ask-later" href="#">', '<a class="button delete-rate-reminder" href="#">', '</span>' );
				printf( '<div class="notice brand-reminder"><div class="brand-author-avatar">%1$s</div><div class="brand-message">%2$s</div></div>', wp_kses_post( $img_msg ), wp_kses_post( $message ) );
			}
		}
 	}
 	add_action( 'admin_notices', 'brand_show_rate_reminder' );

	/**
	 * Delete an admin notice.
	 */
	function brand_update_rate_reminder() {
		check_ajax_referer( 'brand_rate_reminder_nonce' );
		if( isset( $_POST['notice'] ) && isset( $_POST['update'] ) ) {
			$notice = sanitize_text_field( $_POST['notice'] );
			if( $_POST['update'] === 'brand_delete_rate_reminder' ) {
				delete_transient( 'brand_rate_reminder' );
				if( ! get_transient( 'brand_rate_reminder' ) && set_transient( 'brand_rate_reminder_deleted', 'No reminder to show' ) ) {
					$response = array(
						'error' => false,
					);
				} else {
					$response = array(
						'error' => true,
					);
				}
			}
			if( $_POST['update'] === 'brand_ask_later' ) {
				$date = new DateTime();
				$date->add( new DateInterval( 'P7D' ) );
				$date_format = $date->format( 'Y-m-d' );
				delete_transient( 'brand_rate_reminder' );
				if( set_transient( 'brand_rate_reminder', $date_format ) ) {
					$response = array(
						'error' => false,
					);
				} else {
					$response = array(
						'error' => true,
						'error_type' => set_transient( 'brand_rate_reminder', $date_format ),
					);
				}
			}

			wp_send_json( $response );
		}

	}
	add_action( 'wp_ajax_brand_update_rate_reminder', 'brand_update_rate_reminder' );
