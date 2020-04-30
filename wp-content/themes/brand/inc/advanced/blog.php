<?php
if( !function_exists('brand_get_default_blog' )) {
	function brand_get_default_blog() {
		$brand_default_blog = array(
			'show_date'           => 'yes',
			'show_author'         => 'yes',
			'show_categories'     => 'yes',
			'show_tags'           => 'yes',
			'posts_listing_style' => 'classic',
			'posts_bg_color'      => '#f6f6f6',
			'posts_txt_color'     => '#777777',
			'posts_link_color'    => '#222222',
			'posts_meta_color'    => '#222222',
			'col_numb'            => '1',
			'col_numb_md'         => '1',
			'col_numb_sm'         => '1',
			'post_spacing'        => '1.5'
		);

		return apply_filters('brand_default_blog_options', $brand_default_blog);
	}
}

function brand_blog_styles() {

	// Get brand blog options.
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_default_blog()
	);

	$total_gutter = $brand_settings['post_spacing'] * ( $brand_settings['col_numb'] - 1 );
	$total_gutter_md = $brand_settings['post_spacing'] * ( $brand_settings['col_numb_md'] - 1 );
	$total_gutter_sm = $brand_settings['post_spacing'] * ( $brand_settings['col_numb_sm'] - 1 );
	$grid_sizer =  ( 100 - $total_gutter ) / $brand_settings['col_numb'];
	$grid_sizer_md =  ( 100 - $total_gutter_md ) / $brand_settings['col_numb_md'];
	$grid_sizer_sm =  ( 100 - $total_gutter_sm ) / $brand_settings['col_numb_sm'];

	$brand_blog = array(
		'.brand-grid-masonry .brand-grid-sizer,
		.brand-grid-masonry article.post,
		.brand-grid-flex article.post' => array(
			'width'     => $grid_sizer . '%',
			'float'     => 'left',
		),

		'.brand-grid-masonry .brand-gutter-sizer' => array(
			'width'     => $brand_settings['post_spacing'] . '%',
		),

		'.brand-grid-masonry article.post' => array(
			'margin-bottom'     => $brand_settings['post_spacing'] . '%',
		),

		'.brand-grid-flex article.post' => array(
			'margin-right'     => $brand_settings['post_spacing'] . '%',
		),

		'.custom-blog-colors article' => array(
			'background-color' => $brand_settings['posts_bg_color'],
			'color' => $brand_settings['posts_txt_color'],
		),

		'.custom-blog-colors .entry-title a, .custom-blog-colors .entry-title a:visited, .entry-title a:focus,
		.custom-blog-colors article a.more-link, .custom-blog-colors article a.more-link:visited,
		.custom-blog-colors article a.more-link:focus' => array(
			'color' => $brand_settings['posts_link_color'],
		),

		'.custom-blog-colors .entry-meta,
		.custom-blog-colors .entry-meta a,
		.custom-blog-colors .entry-meta a:visited,
		.custom-blog-colors .entry-meta a:focus' => array(
			'color' => $brand_settings['posts_meta_color']
		),

	);

	// Output the above CSS
	$output = '';
	foreach($brand_blog as $k => $properties) {
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

	$tablet = apply_filters( 'brand_tablet_breakpoint', '1024px' );
	$mobile = apply_filters( 'brand_mobile_breakpoint', '640px' );
	$tablet_breakpoint = intval( $tablet );
	$breakpoint_unit = str_replace( $tablet_breakpoint, '', $tablet );
	$desktop_breakpoint = $tablet_breakpoint + 1 . $breakpoint_unit;

	$output .= '@media (min-width:' . $desktop_breakpoint . ') {
		.brand-grid-flex article.post:nth-child(' . $brand_settings['col_numb'] . 'n) {
			margin-right: 0%;
		}
	}';

	$output .= '@media (max-width:' . $tablet . ') {
		.brand-grid-masonry .brand-grid-sizer,
		.brand-grid-masonry article.post,
		.brand-grid-flex article.post {
			width: ' . $grid_sizer_md . '%;
		}

		.brand-grid-flex article.post:nth-child(' . $brand_settings['col_numb_md'] . 'n) {
			margin-right: 0%;
		}
	}';

	$output .= '@media (max-width:' . $mobile . ') {
		.brand-grid-masonry .brand-grid-sizer,
		.brand-grid-masonry article.post,
		.brand-grid-flex article.post {
			width: ' . $grid_sizer_sm . '%;
		}

		.brand-grid-flex article.post:nth-child(' . $brand_settings['col_numb_sm'] . 'n) {
			margin-right: 0%;
		}
	}';

	$output = str_replace(array("\r", "\n", "\t"), '', $output);
	return $output;
}

/**
 * Enqueue blog styles
 */
add_action( 'wp_enqueue_scripts', 'brand_blog_scripts', 50 );
function brand_blog_scripts() {

	wp_add_inline_style( 'brand', brand_blog_styles() );

}
