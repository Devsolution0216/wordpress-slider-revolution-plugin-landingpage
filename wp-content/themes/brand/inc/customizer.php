<?php
/**
 * Brand Theme Customizer.
 *
 * @package Brand
 */

/**
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */


function brand_customize_register( $wp_customize ) {

	// Get default options
	$defaults = brand_get_defaults();

	// Get brand options.
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	require_once get_template_directory() . '/inc/sanitize.php';
	require_once get_template_directory() . '/inc/controls.php';
	require_once get_template_directory() . '/inc/brand-section-pro.php';
	require_once get_template_directory() . '/inc/customizer-callbacks.php';

	// Set sections position for default section
	$wp_customize->get_section( 'static_front_page' )->priority = '10';

	// Add postMessage support for site title and description for the Theme Customizer.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	// Add Selective refresh support for site title and description.
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
      'selector' => '.site-title > a',
      'container_inclusive' => false,
      'render_callback' => function() {
        bloginfo( 'name' );
      },
  ) );

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
      'selector' => '.site-description',
      'container_inclusive' => false,
      'render_callback' => function() {
        bloginfo( 'description' );
      },
  ) );

	// Register custom section types.
	$wp_customize->register_section_type( 'Brand_Customize_Section_Pro' );

	if ( ! brand_addons_installed() ) {
		// Add section.
		$wp_customize->add_section(
			new Brand_Customize_Section_Pro(
				$wp_customize,
				'go-pro',
					array(
						'title'    => esc_html__( 'Ready for more?', 'brand' ),
						'pro_text' => esc_html__( 'Get Brand Bundle', 'brand' ),
						'pro_url'  => 'https://www.wp-brandtheme.com/downloads/brand-bundle/',
						'pro_classes'  => 'pro-features',
						'priority' => 240,
					)
			)
		);
	}

	$wp_customize->add_section(
		new Brand_Customize_Section_Pro(
			$wp_customize,
			'go-doc',
				array(
					'title'    => esc_html__( 'Read the Documentation', 'brand' ),
					'pro_text' => esc_html__( 'Go', 'brand' ),
					'pro_url'  => 'https://www.wp-brandtheme.com/documentation/',
					'priority' => 210,
				)
		)
	);

	$wp_customize->remove_control( 'display_header_text' );
	$wp_customize->remove_control( 'header_textcolor' );

	$wp_customize->add_setting( 'brand_settings[show_site_title]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['show_site_title'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'brand_settings[show_site_title]', array(
  		'type' => 'checkbox',
  		'section' => 'title_tagline',
  		'description' => __( 'Display Site Title and Tagline ', 'brand' ),
			'priority' => 10,
	) );

	// Add logo settings and control to site identity section
	$wp_customize->add_setting( 'brand_settings[logo_url]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['logo_url'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'brand_settings[logo_url]', array(
  		'label' => __( 'Logo', 'brand' ),
  		'section' => 'title_tagline',
  		'mime_type' => 'image',
		'priority'  => '100'
	) ) );

	$wp_customize->add_setting( 'brand_settings[logo_mobile_url]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['logo_mobile_url'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'brand_settings[logo_mobile_url]', array(
  		'label' => __( 'Mobile logo', 'brand' ),
  		'section' => 'title_tagline',
  		'mime_type' => 'image',
		'priority'  => '115'
	) ) );

	// Adds layout panel
	$wp_customize->add_panel( 'brand_layout', array(
    	'priority'       => 25,
    	'capability'     => 'edit_theme_options',
    	'title'          => __( 'Layout', 'brand' ),
	) );

	// Adds general section
	$wp_customize->add_section( 'brand_general', array(
  		'title' => __( 'General', 'brand' ),
  		'priority' => 10,
  		'capability' => 'edit_theme_options',
			'panel' => 'brand_layout',
	) );

	$wp_customize->add_setting( 'brand_settings[container_width]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['container_width'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control(
		new Brand_Customize_Width_Slider_Control(
			$wp_customize,
			'brand_settings[container_width]',
			array(
				'label' => __('Container width','brand'),
				'section' => 'brand_general',
				'settings' => 'brand_settings[container_width]',
				'priority' => 0
			)
		)
	);

	$wp_customize->add_setting( 'brand_settings[container_type]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['container_type'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_width'
	) );

	$wp_customize->add_control( 'brand_settings[container_type]', array(
  		'type' => 'select',
  		'section' => 'brand_general',
  		'label' => __( 'Container type', 'brand' ),
		'priority' => 5,
  		'choices' => array(
			'fullwidth' => 'Full width',
    		'boxed'     => 'Boxed'
  		),
	) );

	$wp_customize->add_setting( 'brand_settings[footer_nav_fullwidth]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['footer_nav_fullwidth'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'brand_settings[footer_nav_fullwidth]', array(
  		'type' => 'checkbox',
  		'section' => 'brand_general',
  		'description' => __( 'Let navigation and footer content take the entire container width. ', 'brand' ),
			'priority' => 10,
	) );

	$wp_customize->add_setting( 'brand_settings[sidebar_layout]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'theme_supports' => '', // Rarely needed.
  		'default' => $defaults['sidebar_layout'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_sidebar_array',
	) );

	$wp_customize->add_control( 'brand_settings[sidebar_layout]', array(
  		'type' => 'select',
  		'section' => 'brand_general',
  		'label' => __( 'Show sidebar', 'brand' ),
		'priority' => 10,
  		'choices' => array(
				'no' 		=> __( 'No sidebar', 'brand' ),
    		'left'  => __( 'Left sidebar', 'brand' ),
    		'right' => __( 'Right sidebar', 'brand' ),
  		),
	) );

	$wp_customize->add_setting( 'brand_settings[featured_position]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'theme_supports' => '', // Rarely needed.
  		'default' => $defaults['featured_position'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_featured_position',
	) );

	$wp_customize->add_control( 'brand_settings[featured_position]', array(
  		'type'        => 'select',
  		'section'     => 'brand_general',
  		'label'       => __( 'Featured image position', 'brand' ),
			'description' => __( 'Choose the position where featured image will be displayed in single post or page.', 'brand' ),
		  'priority'    => 15,
  		'choices'     => array(
				'inside'     => __( 'Inside content', 'brand' ),
    		'before'     => __( 'Before content', 'brand' ),
				'inside_header'     => __( 'Inside header', 'brand' ),
  		),
	) );

	// Add Navigation section in the Layout panel
	$wp_customize->add_section( 'brand_navigation_layout', array(
  		'title' => __( 'Navigation', 'brand' ),
  		'priority' => 20,
  		'capability' => 'edit_theme_options',
			'panel' => 'brand_layout',
	) );

	$wp_customize->add_setting( 'brand_settings[nav_layout]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['nav_layout'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_nav_layout',
	) );

	$wp_customize->add_control( 'brand_settings[nav_layout]', array(
  		'type' => 'select',
  		'section' => 'brand_navigation_layout',
  		'label' => __( 'Navigation layout', 'brand' ),
			'description' => __( 'Choose inline to show logo and navigation on the same line. Choose centered to center them.', 'brand' ),
			'priority' => 20,
  		'choices' => array(
				'inline'   => 'Inline',
				'centered' => 'Centered',
  		)
	) );

	$wp_customize->add_setting( 'brand_settings[compact_menu]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['compact_menu'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_boolopt',
	) );

	$wp_customize->add_control( 'brand_settings[compact_menu]', array(
  		'type' => 'select',
  		'section' => 'brand_navigation_layout',
  		'label' => __( 'Use compact menu on desktop', 'brand' ),
		'priority' => 30,
  		'choices' => array(
			'yes'   => 'Yes',
			'no' => 'No',
  		)
	) );

	$wp_customize->add_setting( 'brand_settings[sticky_menu]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['sticky_menu'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_sticky_menu'
	) );

	$wp_customize->add_control( 'brand_settings[sticky_menu]', array(
  		'type' => 'select',
  		'section' => 'brand_navigation_layout',
  		'label' => __( 'Sticky menu', 'brand' ),
		'priority' => 35,
  		'choices' => array(
			'yes'    => 'Yes',
    	'no' => 'No',
  		),
	) );

	$wp_customize->add_setting( 'brand_settings[nav_orientation]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['nav_orientation'],
  		'transport' => 'postMessage',
  		'sanitize_callback' => 'brand_sanitize_nav_orientation',
	) );

	$wp_customize->add_control( 'brand_settings[nav_orientation]', array(
  		'type' => 'select',
  		'section' => 'brand_navigation_layout',
  		'label' => __( 'Navigation orientation', 'brand' ),
		'priority' => 40,
  		'choices' => array(
			'horizontal'   => 'Horizontal',
			'vertical' => 'Vertical',
  		)
	) );

	$wp_customize->add_setting( 'brand_settings[nav_search]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['nav_search'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_nav_search'
	) );

	$wp_customize->add_control( 'brand_settings[nav_search]', array(
  		'type' => 'select',
  		'section' => 'brand_navigation_layout',
  		'label' => __( 'Search icon', 'brand' ),
			'priority' => 50,
  		'choices' => array(
			'enabled'  => 'Enabled',
    		'disabled' => 'Disabled',
  		),
	) );

	// Add Header section in the Layout panel
	$wp_customize->add_section( 'brand_header_layout', array(
  		'title' => __( 'Header', 'brand' ),
  		'priority' => 30,
  		'capability' => 'edit_theme_options',
			'panel' => 'brand_layout',
	) );

	$wp_customize->add_setting( 'brand_settings[header_alignment]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['header_alignment'],
  		'transport' => 'postMessage',
  		'sanitize_callback' => 'brand_sanitize_alignment_array'
	) );

	$wp_customize->add_control( 'brand_settings[header_alignment]', array(
  		'type' => 'select',
  		'section' => 'brand_header_layout',
  		'label' => __( 'Header alignment', 'brand' ),
			'priority' => 15,
  		'choices' => array(
				'left'  => 'Left',
    		'center' => 'Center',
				'right' => 'Right',
  		),
	) );

	// Add Page Title section in the Layout panel
	$wp_customize->add_section( 'brand_page_title_layout', array(
  		'title' => __( 'Page Title', 'brand' ),
  		'priority' => 40,
  		'capability' => 'edit_theme_options',
			'panel' => 'brand_layout',
	) );

	$wp_customize->add_setting( 'brand_settings[page_title_alignment]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['page_title_alignment'],
  		'transport' => 'postMessage',
  		'sanitize_callback' => 'brand_sanitize_alignment_array'
	) );

	$wp_customize->add_control( 'brand_settings[page_title_alignment]', array(
  		'type' => 'select',
  		'section' => 'brand_page_title_layout',
  		'label' => __( 'Alignment', 'brand' ),
			'priority' => 15,
  		'choices' => array(
				'left'  => 'Left',
    		'center' => 'Center',
				'right' => 'Right',
  		),
	) );

	// Add Sidebar section in the Layout panel
	$wp_customize->add_section( 'brand_sidebar_layout', array(
  		'title' => __( 'Sidebar', 'brand' ),
  		'priority' => 60,
  		'capability' => 'edit_theme_options',
		  'panel' => 'brand_layout',
	) );

	// Add Footer section in the Layout panel
	$wp_customize->add_section( 'brand_footer_layout', array(
  		'title' => __( 'Footer', 'brand' ),
  		'priority' => 80,
  		'capability' => 'edit_theme_options',
		  'panel' => 'brand_layout',
	) );

	$wp_customize->add_setting( 'brand_settings[footer_widgets]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'theme_supports' => '', // Rarely needed.
  		'default' => $defaults['footer_widgets'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'brand_settings[footer_widgets]', array(
  		'type' => 'select',
  		'section' => 'brand_footer_layout',
  		'label' => __( 'Footer widgets', 'brand' ),
		  'priority' => 15,
  		'choices' => array(
				'0' => 'None',
    		'1'   => 'One',
    		'2' => 'Two',
        '3' => 'Three',
        '4' => 'Four',
  		),
	) );


	// Remove default color section
	$wp_customize->remove_section( 'colors' );

	// Add custom color panel
	$wp_customize->add_panel( 'brand_colors', array(
    	'priority'       => 30,
    	'capability'     => 'edit_theme_options',
    	'title'          => __( 'Colors', 'brand' ),
	) );

	// Add Base colors section in the Colors panel
	$wp_customize->add_section( 'brand_base_colors', array(
  		'title' => __( 'Base colors', 'brand' ),
  		'priority' => 10,
  		'capability' => 'edit_theme_options',
		'panel'      => 'brand_colors'
	) );

	// Put core background color feature in Base Colors section
	$wp_customize->get_control( 'background_color' )->section = 'brand_base_colors';
	$wp_customize->get_setting( 'background_color' )->default = $defaults['body_bg_color'];

	// Add base colors options
	$base_colors = array();
	$base_colors[] = array(
		'id'=>'body_text_color',
		'default' => $defaults['body_text_color'],
		'label' => __('Text color', 'brand'),
		'transport' => 'postMessage'
	);

	$base_colors[] = array(
		'id'=>'link_color',
		'default' => $defaults['link_color'],
		'label' => __('Link color', 'brand'),
		'transport' => 'postMessage'
	);

	$base_colors[] = array(
		'id'=>'link_hover_color',
		'default' => $defaults['link_hover_color'],
		'label' => __('Link color on hover', 'brand'),
		'transport' => 'postMessage'
	);

	foreach( $base_colors as $color ) {
		$wp_customize->add_setting(
			'brand_settings[' . $color['id'] . ']', array(
				'default' => $color['default'],
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => $color['transport']
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['id'],
				array(
					'label' => $color['label'],
					'section' => 'brand_base_colors',
					'settings' => 'brand_settings[' . $color['id'] . ']'
				)
			)
		);
	}

	if ( ! brand_addons_installed() ) {

		$wp_customize->add_control(
			new Brand_Customize_Misc_Control(
				$wp_customize,
				'colors_get_addon_desc',
				array(
					'section'     => 'brand_base_colors',
					'type'        => 'addon',
					'label'			  => __( 'More Settings','brand' ),
					'url'         => 'https://www.wp-brandtheme.com/downloads/brand-premium/',
					'description' => sprintf(
						/* translators: link to premium add-ons */
						__( 'Need more color settings?<br /> %s.', 'brand' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( 'https://www.wp-brandtheme.com/downloads/brand-premium/' ),
							__( 'Take a look at our premium plugin', 'brand' )
						)
					),
					'priority'    => 50,
				)
			)
		);
	}

	$wp_customize->remove_section( 'background_image' );
	$wp_customize->get_control( 'background_image' )->section = 'brand_body_image';
	$wp_customize->get_control( 'background_image' )->priority = 10;

	// Add Backgrounds panel
	$wp_customize->add_panel( 'brand_backgrounds', array(
			'priority'       => 35,
			'capability'     => 'edit_theme_options',
			'title'          => __( 'Backgrounds', 'brand' ),
	) );

	// Add Blog panel
	$wp_customize->add_panel( 'brand_blog', array(
			'priority'       => 40,
			'capability'     => 'edit_theme_options',
			'title'          => __( 'Blog', 'brand' ),
	) );

	// Add General section
	$wp_customize->add_section( 'brand_blog_general', array(
  		'title' => __( 'General', 'brand' ),
  		'priority' => 10,
  		'capability' => 'edit_theme_options',
			'panel' => 'brand_blog',
	) );

	$wp_customize->add_setting( 'brand_settings[show_excerpt]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['show_excerpt'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_show_excerpt_array',
	) );

	$wp_customize->add_control( 'brand_settings[show_excerpt]', array(
  		'type' => 'select',
  		'section' => 'brand_blog_general',
  		'label' => __( 'Show excerpt', 'brand' ),
		  'priority' => 10,
  		'choices' => array(
    		'yes'   => 'Yes',
    		'no' => 'No',
				'only_title' => 'Only title',
  		),
	) );

	$wp_customize->add_setting( 'brand_settings[excerpt_length]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['excerpt_length'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'brand_settings[excerpt_length]', array(
  		'type' => 'input',
  		'section' => 'brand_blog_general',
  		'label' => __( 'Excerpt length', 'brand' ),
			'description' => __( 'words number', 'brand' ),
		  'priority' => 15,
	) );

	if ( ! brand_addons_installed() ) {

		$wp_customize->add_control(
			new Brand_Customize_Misc_Control(
				$wp_customize,
				'blog_get_addon_desc',
				array(
					'section'     => 'brand_blog_general',
					'type'        => 'addon',
					'label'			=> __( 'More Settings','brand' ),
					'url' => 'https://www.wp-brandtheme.com/downloads/brand-premium/',
					'description' => sprintf(
						/* translators: link to premium add-ons */
						__( 'Need more blog settings?<br /> %s.', 'brand' ),
						sprintf(
							'<a href="%1$s" target="_blank">%2$s</a>',
							esc_url( 'https://www.wp-brandtheme.com/downloads/brand-premium/' ),
							__( 'Take a look at our premium plugin', 'brand' )
						)
					),
					'priority'    => 50
				)
			)
		);
	}

	// Add Header area panel
	$wp_customize->add_panel( 'brand_header_area', array(
			'priority'       => 20,
			'capability'     => 'edit_theme_options',
			'title'          => __( 'Header Area', 'brand' ),
	) );

	$wp_customize->add_section( 'brand_header_general', array(
			'title' => __( 'General', 'brand' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'brand_header_area',
	) );

	$wp_customize->add_setting( 'brand_settings[header_type_front]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['header_type_front'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_header_type',
	) );

	$wp_customize->add_control( 'brand_settings[header_type_front]', array(
  		'type' => 'select',
  		'section' => 'brand_header_general',
  		'label' => __( 'Header type on front page', 'brand' ),
			'description' => __( 'Set a header type for the header shows on front page. Then set the slider in the Slider panel and the background image or the video in the Header Media section.', 'brand' ),
		  'priority' => 10,
  		'choices' => array(
				'slider'   => __( 'Slider', 'brand' ),
				'image' => __( 'Static image or video', 'brand' ),
				'no-header' => __( 'No header', 'brand' ),
  		),
	) );

	$wp_customize->add_setting( 'brand_settings[header_type]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['header_type'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_header_type',
	) );

	$wp_customize->add_control( 'brand_settings[header_type]', array(
  		'type' => 'select',
  		'section' => 'brand_header_general',
  		'label' => __( 'Header type', 'brand' ),
			'description' => __( 'Set a header type for the header shows on all pages execpt the front page, pages and posts. Then set the background image in the Header Media section. You can hide the header on single posts and pages using the hide elements metabox in the screen editor.', 'brand' ),
		  'priority' => 20,
  		'choices' => array(
				'image' => __( 'Static image', 'brand' ),
				'no-header' => __( 'No header', 'brand' ),
  		),
	) );

	$wp_customize->add_setting( 'brand_settings[header_front_page_height]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['header_front_page_height'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'height_units_sanitization',
	) );

	$wp_customize->add_control( 'brand_settings[header_front_page_height]', array(
  		'type' => 'text',
  		'section' => 'brand_header_general',
			'label' => __( 'Header height on front page', 'brand' ),
			'description' => __( 'Choose a height for the header shows on front page. You can use pixels or vh (1vh= 1% of the window height) units. If you use auto the header height will be determined by its content.', 'brand' ),
			'priority' => 30,
	) );

	$wp_customize->add_setting( 'brand_settings[header_height]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['header_height'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'height_units_sanitization',
	) );

	$wp_customize->add_control( 'brand_settings[header_height]', array(
  		'type' => 'text',
  		'section' => 'brand_header_general',
			'label' => __( 'Header height on other pages', 'brand' ),
			'description' => __( 'Choose a height for the header shows on all the pages except the front page.', 'brand' ),
			'priority' => 40,
	) );

	$wp_customize->get_section( 'header_image' )->panel = 'brand_header_area';
	$wp_customize->get_section( 'header_image' )->priority = 15;
	$wp_customize->get_control( 'header_image' )->priority = 10;

	// Adds slider panel
	$wp_customize->add_panel( 'brand_slider', array(
    	'priority'       => 25,
    	'capability'     => 'edit_theme_options',
    	'title'          => __( 'Slider', 'brand' ),
	) );

	// Adds slider settings section
	$wp_customize->add_section( 'brand_slider_settings', array(
			'title' => __( 'Settings', 'brand' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'brand_slider',
	) );

	$wp_customize->add_setting( 'brand_settings[autoplay_slider]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['autoplay_slider'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'brand_settings[autoplay_slider]', array(
  		'type' => 'checkbox',
  		'section' => 'brand_slider_settings',
  		'description' => __( 'Enable autoplay', 'brand' ),
			'priority' => 10,
	) );

	$wp_customize->add_setting( 'brand_settings[lazy_loading_slider]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['lazy_loading_slider'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'brand_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'brand_settings[lazy_loading_slider]', array(
  		'type' => 'checkbox',
  		'section' => 'brand_slider_settings',
  		'description' => __( 'Enable lazy loading (it loads only the current, previous and next slide images to speed up page loading.)', 'brand' ),
			'priority' => 20,
	) );

	$wp_customize->add_setting( 'brand_settings[delay_slider]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['delay_slider'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'brand_settings[delay_slider]', array(
  		'type' => 'text',
  		'section' => 'brand_slider_settings',
			'label' => __( 'Delay', 'brand' ),
  		'description' => __( 'Delay between slides transitions (in milliseconds).', 'brand' ),
			'priority' => 30,
	) );

	$wp_customize->add_setting( 'brand_settings[speed_slider]', array(
  		'type' => 'option',
  		'capability' => 'edit_theme_options',
  		'default' => $defaults['speed_slider'],
  		'transport' => 'refresh',
  		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'brand_settings[speed_slider]', array(
  		'type' => 'text',
  		'section' => 'brand_slider_settings',
			'label' => __( 'Speed', 'brand' ),
  		'description' => __( 'Duration of transition between slides (in milliseconds).', 'brand' ),
			'priority' => 40,
	) );

	$slides_number = $brand_settings['slides_number'];

	for( $i = 1; $i <= $slides_number; $i++ ) {

		// Adds slide sections
		$wp_customize->add_section( 'brand_slide' . $i, array(
				'title' => __( 'Slide ', 'brand' ) . $i,
				'priority' => 10,
				'capability' => 'edit_theme_options',
				'panel' => 'brand_slider',
		) );

		// Add slides settings and controls.
		$wp_customize->add_setting( 'brand_settings[brand_slide_image' . $i . ']', array(
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'default' => $defaults['brand_slide_image' . $i],
				'transport' => 'refresh',
				'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'brand_settings[brand_slide_image' . $i . ']', array(
				'label' => __( 'Background image', 'brand' ),
				'section' => 'brand_slide' . $i,
				'type' => 'image',
				'priority'  => 10
		) ) );

		$wp_customize->add_setting( 'brand_settings[brand_slide_title' . $i . ']', array(
	  		'type' => 'option',
	  		'capability' => 'edit_theme_options',
	  		'default' => $defaults['brand_slide_title' . $i],
	  		'transport' => 'postMessage',
	  		'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'brand_settings[brand_slide_title' . $i . ']', array(
	  		'type' => 'text',
	  		'section' => 'brand_slide' . $i,
				'label' => __( 'Slide title', 'brand' ),
				'priority' => 20,
		) );

		$wp_customize->add_setting( 'brand_settings[brand_slide_subtitle' . $i . ']', array(
	  		'type' => 'option',
	  		'capability' => 'edit_theme_options',
	  		'default' => $defaults['brand_slide_subtitle' . $i],
	  		'transport' => 'postMessage',
	  		'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'brand_settings[brand_slide_subtitle' . $i . ']', array(
	  		'type' => 'text',
	  		'section' => 'brand_slide' . $i,
				'label' => __( 'Slide subtitle', 'brand' ),
				'priority' => 30,
		) );

		$wp_customize->add_setting( 'brand_settings[brand_slide_button_text' . $i . ']', array(
	  		'type' => 'option',
	  		'capability' => 'edit_theme_options',
	  		'default' => $defaults['brand_slide_button_text' . $i],
	  		'transport' => 'postMessage',
	  		'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'brand_settings[brand_slide_button_text' . $i . ']', array(
	  		'type' => 'text',
	  		'section' => 'brand_slide' . $i,
				'label' => __( 'Button', 'brand' ),
				'description' => __( 'Button text', 'brand' ),
				'priority' => 40,
		) );

		$wp_customize->add_setting( 'brand_settings[brand_slide_button_url' . $i . ']', array(
	  		'type' => 'option',
	  		'capability' => 'edit_theme_options',
	  		'default' => $defaults['brand_slide_button_url' . $i],
	  		'transport' => 'refresh',
	  		'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( 'brand_settings[brand_slide_button_url' . $i . ']', array(
	  		'type' => 'text',
	  		'section' => 'brand_slide' . $i,
				'description' => __( 'Button url', 'brand' ),
				'priority' => 50,
		) );

		// Add Selective refresh support for slider title.
		$wp_customize->selective_refresh->add_partial( 'brand_settings[brand_slide_title' . $i . ']', array(
			'selector' => '#brand-slide-' . $i . ' h1',
				'container_inclusive' => false,
				'render_callback' => function() use ($i) {
					$brand_settings = wp_parse_args(
						get_option( 'brand_settings', array() ),
						brand_get_defaults()
					);
					return esc_html( $brand_settings['brand_slide_title' . $i] );
					},
		) );

		// Add Selective refresh support for slider subtitle.
		$wp_customize->selective_refresh->add_partial( 'brand_settings[brand_slide_subtitle' . $i . ']', array(
			'selector' => '#brand-slide-' . $i . ' h2',
				'container_inclusive' => false,
				'render_callback' => function() use ($i) {
					$brand_settings = wp_parse_args(
						get_option( 'brand_settings', array() ),
						brand_get_defaults()
					);
					return esc_html( $brand_settings['brand_slide_subtitle' . $i] );
					},
		) );

		// Add Selective refresh support for slider button text.
		$wp_customize->selective_refresh->add_partial( 'brand_settings[brand_slide_button_text' . $i . ']', array(
			'selector' => '#brand-slide-' . $i . ' a',
				'container_inclusive' => false,
				'render_callback' => function() use ($i) {
					$brand_settings = wp_parse_args(
						get_option( 'brand_settings', array() ),
						brand_get_defaults()
					);
					return esc_html( $brand_settings['brand_slide_button_text' . $i] );
					},
		) );

		$wp_customize->add_setting(
			'brand_settings[brand_slide_text_color' . $i . ']', array(
				'default' => $defaults['brand_slide_text_color' . $i],
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'brand_settings[brand_slide_text_color' . $i . ']',
				array(
					'label' => __( 'Text color', 'brand' ),
					'section' => 'brand_slide' . $i,
					'settings' => 'brand_settings[brand_slide_text_color' . $i . ']'
				)
			)
		);

	}

}

add_action( 'customize_register', 'brand_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function brand_customize_preview_js() {
	wp_enqueue_script( 'brand_customizer', get_template_directory_uri() . '/assets/javascripts/admin/customizer.js', array( 'customize-preview' ), BRAND_VER, true );
}
add_action( 'customize_preview_init', 'brand_customize_preview_js' );

function brand_enqueue_control_scripts() {
	wp_enqueue_style( 'brand-customize-controls', get_template_directory_uri() . '/assets/css/admin/brand-customize-controls.css', BRAND_VER );
	wp_enqueue_script( 'brand-pro-section', get_template_directory_uri() . '/assets/javascripts/admin/brand-pro-section.js', array( 'customize-controls' ), BRAND_VER, true );
}
add_action( 'customize_controls_enqueue_scripts', 'brand_enqueue_control_scripts' );

add_action('customize_controls_print_styles', 'brand_customize_preview_css');
function brand_customize_preview_css() {
	?>
	<style>
		#accordion-section-secondary_bg_images_section li.customize-section-description-container {
			float: none;
			width: 100%;
		}

		#customize-control-brand_settings-show_site_title .description,
		#customize-control-brand_settings-footer_nav_fullwidth .description,
		#customize-control-brand_settings-autoplay_slider .description,
		#customize-control-brand_settings-lazy_loading_slider .description {
			display: inline;
		}

		.customize-control-title.addon {
			display:inline;
		}

		.get-addon a {
			background: #D54E21;
			color:#FFF;
			text-transform: uppercase;
			font-size:11px;
			padding: 3px 5px;
			font-weight:bold;
		}

		.customize-control-addon {
			margin-top: 10px;
		}

		.slider-input {
			width: 40px !important;
			font-size: 12px;
			padding: 2px;
			text-align: center;
		}

		span.value {
			display: inline-block;
			float: right;
			width: 30%;
			text-align: right;
		}

		span.typography-size-label {
			display: inline-block;
			width: 70%;
		}

		div.slider {
			margin-top: 8px;
		}

		span.px {
			background: #FAFAFA;
			line-height: 18px;
			display: inline-block;
			padding: 2px 5px;
			font-style: normal;
			font-weight: bold;
			border-right: 1px solid #DDD;
			border-top: 1px solid #DDD;
			border-bottom: 1px solid #DDD;
			font-size: 12px;
		}

	</style>
	<?php
}

/**
 * Custom styles to output in the <head> tag.
 */
 function brand_custom_styles() {

	// Get brand options.
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	$footer_nav_width = false !== $brand_settings['footer_nav_fullwidth'] ? '100%' : intval( $brand_settings['container_width'] ) . 'px';

	if( is_singular() ) {
		global $post;
		if( has_post_thumbnail( $post->ID ) && 'inside_header' === $brand_settings['featured_position'] ) {
			$header_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		} else {
			$header_image_url = get_header_image();
		}
	} else {
		$header_image_url = get_header_image();
	}?>

	<style>
	#header-wrapper:before {
		background-image: <?php echo 'url(' . esc_url( $header_image_url ) . ')' ?>;
		background-attachment: scroll;
		background-size: cover;
		background-position: center;
	}

	body {
		color: <?php echo esc_attr($brand_settings['body_text_color']); ?>;
	}

	.has-front-page-custom-header #header-wrapper,
	.brand-header-content,
	.front-page-slider .inner-slide {
		min-height: <?php echo esc_attr( $brand_settings['header_front_page_height'] ); ?>;
	}

	.brand-has-header-image:not(.has-front-page-custom-header) #header-wrapper {
		min-height: <?php echo esc_attr( $brand_settings['header_height'] ); ?>;
	}

	a, .woocommerce ul.products li.product .button,
	.woocommerce div.product .woocommerce-tabs ul.tabs li a {
		color: <?php echo esc_attr($brand_settings['link_color']); ?>;
	}

	a:hover, a:focus, a:active,
	.woocommerce ul.products li.product .price,
	.woocommerce ul.products li.product .button:hover,
	.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,
	.woocommerce div.product .woocommerce-tabs ul.tabs li.active a {
		color: <?php echo esc_attr($brand_settings['link_hover_color']); ?>;
	}

	.container, #content > .page-header-featured {
		width:100%;
		max-width: <?php echo intval($brand_settings['container_width']); ?>px;
	}

	#main-nav-wrapper .container,
	#footer .container,
	.site-info .container {
		max-width: <?php echo $footer_nav_width; // WPCS: XSS ok. ?>;
	}

	<?php
	// Slider
	for( $i = 1; $i <= $brand_settings['slides_number']; $i++ ) {
		if( 0 !== $brand_settings['brand_slide_image' . $i] ) {
			if( false === $brand_settings['lazy_loading_slider'] ) {
				echo '#brand-slide-' . $i . ':before' ?> {
					background-image: url( <?php echo esc_url( $brand_settings['brand_slide_image' . $i] ); ?> );
				}  <?php
			}
			echo '#brand-slide-' . $i . ' h1, #brand-slide-' . $i . ' h2';  ?> {
				color: <?php echo esc_attr( $brand_settings['brand_slide_text_color' . $i] ); ?>;
			} <?php
		}

	} ?>

	/* a:hover, a.light:hover, .button-link a:hover, button:hover, html input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover {
		color: <?php echo esc_attr($brand_settings['link_hover_color']); ?>;
	}

	.button-link a:hover, button:hover, html input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, input:focus, textarea:focus, .form-control {
		border-color: <?php echo esc_attr($brand_settings['link_hover_color']); ?>;
	} */

	#header-wrapper, #header-portfolio-wrapper {
		text-align: <?php echo esc_attr($brand_settings['header_alignment']); ?>;
	}

	.main-title {
		text-align: <?php echo esc_attr( $brand_settings['page_title_alignment'] ); ?>;
	}

	/* Woocommerce */
	<?php $product_cols = intval( $brand_settings['woocommerce_products_cols'] );
	$col_width = ( ( 100 - ( $product_cols - 1 ) ) / $product_cols );
	$col_width = intval( $col_width * 10 ) / 10;
	$tablet = apply_filters( 'brand_tablet_breakpoint', '1024px' );
	$mobile = apply_filters( 'brand_mobile_breakpoint', '640px' );
	$tablet_breakpoint = intval( $tablet );
	$breakpoint_unit = str_replace( $tablet_breakpoint, '', $tablet );
	$desktop_breakpoint = $tablet_breakpoint + 1 . $breakpoint_unit; ?>

	.woocommerce-page ul.products.columns- <?php echo $product_cols // WPCS: XSS ok. ?> > li.product {
		margin:0 1% 30px 0;
		width: <?php echo $col_width; // WPCS: XSS ok. ?>%;
	}

	@media( min-width: <?php echo esc_attr( $desktop_breakpoint ) ?> ) {
		.woocommerce-page ul.products > li.product:nth-child(<?php echo $product_cols // WPCS: XSS ok. ?>n) {
			margin-right: 0;
		}
	}

	@media( max-width: <?php echo esc_attr( $tablet ) ?> ) {
		.woocommerce-page ul.products > li.product,
		.woocommerce ul.products > li.product.type-product {
	    width:49.4%;
			margin:0 1% 30px 0;
	  }

		.woocommerce-page ul.products > li.product:nth-child(2n),
		.woocommerce ul.products > li.product.type-product:nth-child(2n) {
	    margin-right: 0;
	  }
	}

	@media( max-width: <?php echo esc_attr( $mobile ) ?> ) {
		.woocommerce-page ul.products > li.product,
		.woocommerce ul.products > li.product.type-product {
	    width:100%;
			margin:0 0 30px 0;
	  }
	}

</style>
<?php
 }

add_action( 'wp_head', 'brand_custom_styles');
