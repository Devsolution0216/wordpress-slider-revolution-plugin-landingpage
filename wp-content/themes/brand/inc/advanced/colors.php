<?php

if( !function_exists('brand_get_default_colors' )) {
	function brand_get_default_colors() {
		$brand_default_colors = array(
			'header_bg_color'                    => '#ffffff',
			'header_title_color'                 => '#ffffff',
			'header_text_color'                 => '#ffffff',
			'navigation_bg_color'                => '#222222',
			'navigation_bg_opacity'              => '0.1',
			'navigation_text_color'              => '#eeeeee',
			'navigation_text_hover_color'        => '#ffffff',
			'navigation_text_current_color'      => '#ffffff',
			'subnavigation_bg_color'             => '#444444',
			'subnavigation_text_color'           => '#eeeeee',
			'subnavigation_text_hover_color'     => '#ffffff',
			'subnavigation_bg_hover_color'       => '#666666',
			'subnavigation_text_current_color'   => '#ffffff',
			'subnavigation_bg_current_color'     => '#666666',
			'mob_navigation_bg_color'            => '#222222',
			'mob_navigation_text_color'          => '#eeeeee',
			'mob_navigation_text_hover_color'    => '#ffffff',
			'mob_navigation_text_current_color'  => '#ffffff',
			'mob_widget_text_color'              => '#eeeeee',
			'page_title_color'                   => '',
			'content_bg_color'                   => '#ffffff',
			'content_text_color'                 => '',
			'content_link_color'                 => '',
			'content_link_hover_color'           => '',
			'entry_meta_text_color'              => '#222222',
			'entry_meta_link_hover_color'        => '#1ebaf3',
			'comments_bg_color'                  => '#f6f6f6',
			'comments_text_color'                => '',
			'comments_link_color'                => '',
			'comments_link_hover_color'          => '',
			'headings_color'                     => '',
			'sidebar_widget_background_color'    => '#ffffff',
			'sidebar_widget_text_color'          => '',
			'sidebar_widget_link_color'          => '',
			'sidebar_widget_link_hover_color'    => '',
			'sidebar_widget_title_color'         => '#777777',
			'footer_widget_background_color'     => '#ffffff',
			'footer_widget_text_color'           => '',
			'footer_widget_link_color'           => '#1e73be',
			'footer_widget_link_hover_color'     => '#000000',
			'footer_widget_title_color'          => '#000000',
			'footer_background_color'            => '#000000',
			'footer_bg_opacity'                  => '1',
			'footer_text_color'                  => '#ffffff',
			'footer_link_color'                  => '#ffffff',
			'footer_link_hover_color'            => '#1ebaf3',
			'form_background_color'              => '#fafafa',
			'form_text_color'                    => '#444444',
			'form_background_color_focus'        => '#ffffff',
			'form_text_color_focus'              => '#444444',
			'form_border_color'                  => '#e2e2e2',
			'form_border_color_focus'            => '#1ebaf3',
			'form_button_background_color'       => '#1ebaf3',
			'form_button_background_color_hover' => '#1e73be',
			'form_button_text_color'             => '#ffffff',
			'form_button_text_color_hover'       => '#ffffff',
			'form_button_border_color'           => '#e2e2e2',
			'form_button_border_color_hover'     => '#e2e2e2',
		);

		return apply_filters('brand_default_color_options', $brand_default_colors);
	}
}

function brand_colors_styles() {
	// Get brand colors options.
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_default_colors()
	);

    if( is_singular() && !is_attachment() ) {

 		global $post;
 		$brand_header_meta = get_post_meta( $post->ID, '_brand_header_meta', true );
 		$brand_navigation_meta = get_post_meta( $post->ID, '_brand_navigation_meta', true );
	  $brand_header_title_color = isset($brand_header_meta['header_title_color']) && $brand_header_meta['header_title_color'] !== ''  ? $brand_header_meta['header_title_color'] : $brand_settings['header_title_color'];
 		$brand_navigation_bg_color = isset($brand_navigation_meta['navigation_bg_color']) && $brand_navigation_meta['navigation_bg_color']  !== ''  ? $brand_navigation_meta['navigation_bg_color'] : $brand_settings['navigation_bg_color'];
 		$brand_navigation_bg_opacity = isset($brand_navigation_meta['navigation_bg_opacity']) && $brand_navigation_meta['navigation_bg_opacity']  !== ''  ? $brand_navigation_meta['navigation_bg_opacity'] : $brand_settings['navigation_bg_opacity'];
  	} else {
		$brand_header_title_color = $brand_settings['header_title_color'];
		$brand_navigation_bg_color =  $brand_settings['navigation_bg_color'];
		$brand_navigation_bg_opacity =  $brand_settings['navigation_bg_opacity'];
  	}

	$nav_bg_rgb = brand_hex2rgb( $brand_navigation_bg_color );
	$footer_bg_rgb = brand_hex2rgb( $brand_settings['footer_background_color'] );

	$brand_colors = array(

		// Header
		'#header-wrapper, #header-portfolio-wrapper' => array(
			'background-color' => $brand_settings['header_bg_color'],
			'color' => $brand_settings['header_text_color'],
		),

		'#header-wrapper a, #header-portfolio-wrapper a' => array(
			'color' => $brand_settings['header_text_color'],
		),

		'.site-title, .site-title a,
		.site-description,
		.site-title a:focus,
		.site-title a:link,
		.site-title a:visited' => array(
			'color' => $brand_header_title_color,
		),

		//Navigation
		'.brand-has-header-image #main-nav-wrapper:before,
		.brand-has-header-image .mobile-nav-bar:before' => array(
			'background-color' => 'rgba(' . $nav_bg_rgb['red'] . ',' . $nav_bg_rgb['green'] . ',' . $nav_bg_rgb['blue'] . ',' . $brand_navigation_bg_opacity . ')',
		),

		'#main-nav-wrapper:before,
		.mobile-nav-bar:before,
		#main-nav-wrapper.scroll:before,
		.mobile-nav-bar.scroll:before,
		#main-nav-wrapper:hover:before' => array(
			'background-color' => 'rgba(' . $nav_bg_rgb['red'] . ',' . $nav_bg_rgb['green'] . ',' . $nav_bg_rgb['blue'] . ',1)',
		),

		'nav.main-nav a,
		.mobile-nav-bar a,
		.compact-menu-icons a,
		.mobile-nav-bar a:visited' => array(
			'color' => $brand_settings['navigation_text_color'],
		),

		'.c-hamburger > span,
		.c-hamburger > span:after,
		.c-hamburger > span:before' => array(
			'background-color' => $brand_settings['navigation_text_color'],
		),

		'.mobile-nav-bar a:hover,
		.compact-menu-icons a:hover' => array(
			'color' => $brand_settings['navigation_text_hover_color'],
		),

		'.c-hamburger:hover > span,
		.c-hamburger:hover > span:after,
		.c-hamburger:hover > span:before' => array(
			'background-color' => $brand_settings['navigation_text_hover_color'],
		),

		'nav.main-nav ul li:hover a' => array(
			'color' => $brand_settings['navigation_text_hover_color'],
		),

		'nav.main-nav ul li.current-menu-item a,
		nav.main-nav ul li.current-menu-parent a,
		nav.main-nav ul li.current-menu-ancestor a' => array(
			'color' => $brand_settings['navigation_text_current_color'],
		),

		'#main-nav-wrapper nav.main-nav li ul li a,
		#sticky-nav-wrapper nav.main-nav li ul li a' => array(
			'color' => $brand_settings['subnavigation_text_color'],
			'background-color' => $brand_settings['subnavigation_bg_color'],
		),

		'#main-nav-wrapper nav.main-nav li ul li > a:hover,
		#sticky-nav-wrapper nav.main-nav li ul li > a:hover' => array(
			'color' => $brand_settings['subnavigation_text_hover_color'],
			'background-color' => $brand_settings['subnavigation_bg_hover_color'],
		),

		'nav.main-nav li ul li.current-menu-item > a,
		 nav.main-nav li ul li.current-menu-parent > a,
		 nav.main-nav li ul li.current-menu-ancestor > a' => array(
			'color' => $brand_settings['subnavigation_text_current_color'],
			'background-color' => $brand_settings['subnavigation_bg_current_color'],
		),

		'.main-title h1,
		h1.product_title' => array(
			'color' => $brand_settings['page_title_color'],
		),

		// Mobile navigation
		'#mobile-menu-wrapper' => array(
			'background-color' => $brand_settings['mob_navigation_bg_color'],
			'color' => $brand_settings['mob_widget_text_color'],
		),

		'#mobile-menu-wrapper a' => array(
			'color' => $brand_settings['mob_navigation_text_color'],
		),

		'#mobile-menu-wrapper ul li:hover a' => array(
			'color' => $brand_settings['mob_navigation_text_hover_color'],
		),

		'#mobile-menu-wrapper ul li.current-menu-item a,
		#mobile-menu-wrapper ul li.current-menu-parent a,
		#mobile-menu-wrapper ul li.current-menu-ancestor a' => array(
			'color' => $brand_settings['mob_navigation_text_current_color'],
		),

		// Content
		'#content,
		#pagination,
		.posts-navigation,
		#numbered-pagination' => array(
			'background-color' => $brand_settings['content_bg_color'],
		),

		'body:not(.custom-blog-colors) .entry-content,
		.entry-summary,
		.page-content' => array(
			'color' => $brand_settings['content_text_color'],
		),

		'body:not(.custom-blog-colors) article a, body:not(.custom-blog-colors) article a:visited, body:not(.custom-blog-colors) article a:focus,
		.page-header a, .page-header a:visited, .page-header a:focus,
		.page-content a, .page-content a:visited, .page-content a:focus' => array(
			'color' => $brand_settings['content_link_color'],
		),

		'article a:hover, article a:active,
		.page-header a:hover, .page-header a:active,
		.page-content a:hover, .page-content a:active' => array(
			'color' => $brand_settings['content_link_hover_color'],
		),

		'body:not(.custom-blog-colors) .entry-header .entry-title a,
		body:not(.custom-blog-colors) .entry-header .entry-title a:visited,
		body:not(.custom-blog-colors) .entry-header .entry-title a:focus' => array(
			'color' => $brand_settings['content_link_color'],
		),

		'body:not(.custom-blog-colors) .entry-header .entry-title a:hover,
		body:not(.custom-blog-colors) .entry-header .entry-title a:active' => array(
			'color' => $brand_settings['content_link_hover_color'],
		),

		'.entry-meta,
		.entry-meta a,
		.entry-meta a:visited,
		.entry-meta a:focus' => array(
			'color' => $brand_settings['entry_meta_text_color']
		),

		'.entry-meta a:hover,
		.entry-meta a:active' => array(
			'color' => $brand_settings['entry_meta_link_hover_color']
		),

		'body:not(.use-sections) .entry-content h1,
		body:not(.use-sections) .entry-content h2,
		body:not(.use-sections) .entry-content h3,
		body:not(.use-sections) .entry-content h4,
		body:not(.use-sections) .entry-content h5,
		body:not(.use-sections) .entry-content h6' => array(
			'color' => $brand_settings['headings_color']
		),

		// Comments area
		'#comments-wrapper' => array(
			'background-color' => $brand_settings['comments_bg_color'],
		),

		'#comments-wrapper .comments-area,
		article.comment-body' => array(
			'color' => $brand_settings['comments_text_color'],
		),

		'.comments-area a, .comments-area a:visited, .comments-area a:focus' => array(
			'color' => $brand_settings['comments_link_color'],
		),

		'.comments-area a:hover, .comments-area a:active' => array(
			'color' => $brand_settings['comments_link_hover_color'],
		),


		// Sidebar widget
		'#secondary-content .widget-area .widget' => array(
			'background-color' => $brand_settings['sidebar_widget_background_color'],
			'color' => $brand_settings['sidebar_widget_text_color']
		),

		'#secondary-content .widget-area .widget a,
		#secondary-content .widget-area .widget a:visited,
		#secondary-content .widget-area .widget a:focus' => array(
			'color' => $brand_settings['sidebar_widget_link_color'],
		),

		'#secondary-content .widget-area .widget a:hover,
		#secondary-content .widget-area .widget a:active' => array(
			'color' => $brand_settings['sidebar_widget_link_hover_color'],
		),

		'#secondary-content .widget-area .widget-title' => array(
			'color' => $brand_settings['sidebar_widget_title_color'],
		),

		// Footer widget
		'#footer' => array(
			'background-color' => $brand_settings['footer_widget_background_color'],
			'color' => $brand_settings['footer_widget_text_color']
		),

		'#footer .widget-area .widget a,
		#footer .widget-area .widget a:visited,
		#footer .widget-area .widget a:focus' => array(
			'color' => $brand_settings['footer_widget_link_color'],
		),

		'#footer .widget-area .widget a:hover,
		#footer .widget-area .widget a:active' => array(
			'color' => $brand_settings['footer_widget_link_hover_color'],
		),

		'#footer .widget-area .widget-title' => array(
			'color' => $brand_settings['footer_widget_title_color'],
		),

		// Site info
		'.site-info' => array(
			'background-color' => 'rgba(' . $footer_bg_rgb['red'] . ',' . $footer_bg_rgb['green'] . ',' . $footer_bg_rgb['blue'] . ',' . $brand_settings['footer_bg_opacity'] . ')',
			'color' => $brand_settings['footer_text_color']
		),

		'.site-info a,
		.site-info a:visited,
		.site-info a:focus' => array(
			'color' => $brand_settings['footer_link_color'],
		),

		'.site-info a:hover,
		.site-info a:active' => array(
			'color' => $brand_settings['footer_link_hover_color'],
		),

		// Form
		'input[type="text"],
		input[type="email"],
		input[type="url"],
		input[type="password"],
		input[type="tel"],
		input[type="search"],
		input[type="phone"],
		input[type="number"],
		select,
		textarea' => array(
			'background-color' => $brand_settings['form_background_color'],
			'border-color' => $brand_settings['form_border_color'],
			'color' => $brand_settings['form_text_color']
		),

		'input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="tel"]:focus,
		input[type="search"]:focus,
		input[placeholder]:focus,
		input[type="number"]:focus,
		select:focus,
		textarea:focus' => array(
			'background-color' => $brand_settings['form_background_color_focus'],
			'color' => $brand_settings['form_text_color_focus'],
			'border-color' => $brand_settings['form_border_color_focus']
		),

		'button,
		html input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.button,
		.button:visited,
		body:not(.custom-blog-colors) article a.button, body:not(.custom-blog-colors) article a.button:visited, body:not(.custom-blog-colors) article a.button:focus,
		.page-header a.button, .page-header a.button:visited, .page-header a.button:focus,
		.page-content a.button, .page-content a.button:visited, .page-content a.button:focus,
		.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
		.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
		.woocommerce #respond input#submit.alt,
		.woocommerce a.button.alt,
		.woocommerce button.button.alt,
		.woocommerce input.button.alt,
		.woocommerce #respond input#submit,
		.woocommerce a.button,
		.woocommerce button.button,
		.woocommerce input.button' => array(
			'background-color' => $brand_settings['form_button_background_color'],
			'color' => $brand_settings['form_button_text_color'],
			'border-color' => $brand_settings['form_button_border_color'],
		),

		'.woocommerce button.disabled,
		.woocommerce button:disabled,
		input[submit].disabled,
		input[submit]:disabled' => array(
			'background-color' => $brand_settings['form_button_background_color'] . '!important',
			'color' => $brand_settings['form_button_text_color'] . '!important'
		),

		'.woocommerce .sorting,
		.woocommerce span.onsale' => array(
			'background-color' => $brand_settings['form_button_background_color'],
			'color' => $brand_settings['form_button_text_color']
		),

		'.woocommerce .sorting .selected-order,
		.orderby-list > ul' => array(
			'border-color' => $brand_settings['form_button_border_color']
		),

		// Form button hover
		'button:hover,
		html input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		.button:hover,
		button:focus,
		.button:focus,
		body:not(.custom-blog-colors) article a.button:hover,
		.page-header a.button:hover,
		.page-content a.button:hover,
		html input[type="button"]:focus,
		input[type="reset"]:focus,
		input[type="submit"]:focus,
		.woocommerce #respond input#submit.alt:hover,
		.woocommerce #respond input#submit.alt:focus,
		.woocommerce a.button.alt:hover,
		.woocommerce a.button.alt:focus,
		.woocommerce button.button.alt:hover,
		.woocommerce button.button.alt:focus,
		.woocommerce input.button.alt:hover,
		.woocommerce input.button.alt:focus,
		.woocommerce #respond input#submit:hover,
		.woocommerce #respond input#submit:focus,
		.woocommerce a.button:hover,
		.woocommerce a.button:focus,
		.woocommerce button.button:hover,
		.woocommerce button.button:focus,
		.woocommerce input.button:hover,
		.woocommerce input.button:focus' => array(
			'background-color' => $brand_settings['form_button_background_color_hover'],
			'color' => $brand_settings['form_button_text_color_hover'],
			'border-color' => $brand_settings['form_button_border_color_hover'],
		)

	);

		// Output the above CSS
		$output = '';
		foreach($brand_colors as $k => $properties) {
			if(!count($properties))
				continue;

			$temporary_output = $k . ' {';
			$elements_added = 0;

			foreach($properties as $p => $v) {
				if(empty($v))
					continue;

				$elements_added++;
				$temporary_output .= $p . ': ' . $v . '; ';
			}

			$temporary_output .= "}";

			if($elements_added > 0)
				$output .= $temporary_output;
		}

		$output = str_replace(array("\r", "\n", "\t"), '', $output);
		return $output;

}

	/**
	 * Enqueue colors styles
	 */
	add_action( 'wp_enqueue_scripts', 'brand_color_scripts', 50 );
	function brand_color_scripts() {

		wp_add_inline_style( 'brand', brand_colors_styles() );

	}
