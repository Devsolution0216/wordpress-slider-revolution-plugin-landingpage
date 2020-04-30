<?php
function brand_sanitize_opacity($data) {
	return floatval($data);
}

function brand_sanitize_skin_array($data) {
	if(in_array($data, array('dark','light')) === true) {
		return $data;
	} else{
		return 'dark';
	}
}

function brand_sanitize_alignment_array($data) {
	if(in_array($data, array('left','center', 'right')) === true) {
		return $data;
	} else{
		return 'left';
	}
}

function brand_sanitize_checkbox($data) {
	if( $data ) {
		return '1';
	} else {
		return false;
	}
}

function brand_sanitize_vh_array($data) {
	if(in_array($data, array(25, 50, 75, 100)) === true) {
		return $data;
	} else{
		return '';
	}
}

function brand_sanitize_sidebar_array($data) {
	if(in_array($data, array('no', 'left', 'right')) === true) {
		return $data;
	} else{
		return '';
	}
}

function brand_sanitize_content_layout_array($data) {
	if(in_array($data, array('one_block', 'separated_blocks')) === true) {
		return $data;
	} else{
		return '';
	}
}

function brand_sanitize_featured_position($data) {
	if( in_array( $data, array( 'inside', 'before', 'inside_header' ) ) === true ) {
		return $data;
	} else{
		return 'inside';
	}
}

function brand_sanitize_width($data) {
	if(in_array($data, array('fullwidth','boxed')) === true) {
		return $data;
	} else{
		return 'fullwidth';
	}
}

function brand_sanitize_sticky_menu($data) {
	if(in_array($data, array('yes','no')) === true) {
		return $data;
	} else{
		return 'yes';
	}
}

function brand_sanitize_logo_position($data) {
	if(in_array($data, array('above_nav','inline', 'inside_header', 'no_logo')) === true) {
		return $data;
	} else{
		return 'inside_header';
	}
}

function brand_sanitize_nav_layout( $data ) {
	if( in_array( $data, array( 'inline','centered' ) ) === true ) {
		return $data;
	} else{
		return 'inline';
	}
}

function brand_sanitize_nav_search($data) {
	if(in_array($data, array('enabled','disabled')) === true) {
		return $data;
	} else{
		return 'enabled';
	}
}

function brand_sanitize_show_excerpt_array($data) {
	if(in_array($data, array('yes','no', 'only_title')) === true) {
		return $data;
	} else{
		return 'yes';
	}
}

function brand_sanitize_show_thumb_index($data) {
	if(in_array($data, array('yes','no')) === true) {
		return $data;
	} else{
		return 'yes';
	}
}

function brand_sanitize_show_thumb_single($data) {
	if(in_array($data, array('yes','no')) === true) {
		return $data;
	} else{
		return 'no';
	}
}

/**
 * Sanitize typography dropdown
 * @since 1.0.0
 */
function brand_sanitize_typography( $input )
{

	// Grab all of our fonts
	$fonts = ( get_transient('brand_all_google_fonts') ? get_transient('brand_all_google_fonts') : array() );

	// Loop through all of them and grab their names
	$font_names = array();
	foreach ( $fonts as $k => $fam ) {
		$font_names[] = $fam['name'];
	}

	// Get all non-Google font names
	$not_google = array(
		'inherit',
		'Arial, Helvetica, sans-serif',
		'Century Gothic',
		'Comic Sans MS',
		'Courier New',
		'Georgia, Times New Roman, Times, serif',
		'Helvetica',
		'Impact',
		'Lucida Console',
		'Lucida Sans Unicode',
		'Palatino Linotype',
		'Tahoma, Geneva, sans-serif',
		'Trebuchet MS, Helvetica, sans-serif',
		'Verdana, Geneva, sans-serif'
	);

	// Merge them both into one array
	$valid = array_merge( $font_names, $not_google );

	// Sanitize
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return 'Open Sans';
    }
}

/**
 * Sanitize font weight
 * @since 1.0.0
 */
function brand_sanitize_font_weight( $input ) {

    $valid = array(
        'normal',
		'bold',
		'100',
		'200',
		'300',
		'400',
		'500',
		'600',
		'700',
		'800',
		'900'
    );

    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return 'normal';
    }
}

/**
 * Sanitize text transform
 * @since 1.0.0
 */
function brand_sanitize_text_transform( $input ) {

    $valid = array(
        'none',
		'capitalize',
		'uppercase',
		'lowercase'
    );

    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return 'none';
    }
}

function brand_sanitize_bg_repeat($data) {
	if(in_array($data, array('repeat', 'no-repeat', 'repeat-x', 'repeat-y') ) === true) {
		return $data;
	} else{
		return 'no-repeat';
	}
}

function brand_sanitize_bg_attachment($data) {
	if(in_array($data, array('scroll', 'fixed', 'local') ) === true) {
		return $data;
	} else{
		return 'fixed';
	}
}

function brand_sanitize_bg_size($data) {
	if(in_array($data, array('auto', '100%', 'cover', 'contain') ) === true) {
		return $data;
	} else{
		return 'cover';
	}
}

function brand_sanitize_nav_orientation($data) {
	if(in_array( $data, array( 'horizontal', 'vertical' ) ) === true ) {
		return $data;
	} else{
		return 'horizontal';
	}
}

function units_sanitization($data) {
	$pattern = '/^\s*[0-9]+(\.[0-9])?(?:px|%)\s*$/';
	if ( preg_match( $pattern, $data ) === 1 ) {
		return $data;
	}
	return '0';
}

function height_units_sanitization($data) {
	$pattern = '/^\s*[0-9]+(\.[0-9])?(?:px|%|vh)\s*$/';
	if ( preg_match( $pattern, $data ) === 1 ) {
		return $data;
	}
	return 'auto';
}

function brand_sanitize_posts_listing_styles($data) {
	if( in_array( $data, array( 'masonry', 'classic' ) ) === true ) {
		return $data;
	} else{
		return 'classic';
	}
}

function brand_sanitize_col_numb( $data ) {
	if( $data >= 1 && $data <= 6 ) {
		return $data;
	} else{
		return 1;
	}
}

function brand_sanitize_boolopt( $data ) {
	if( in_array($data, array('yes','no')) === true ) {
		return $data;
	} else{
		return 'no';
	}
}

function brand_sanitize_header_type( $data ) {
	if( in_array( $data, array( 'slider','image', 'no-header' ) ) === true ) {
		return $data;
	} else{
		return 'image';
	}
}
