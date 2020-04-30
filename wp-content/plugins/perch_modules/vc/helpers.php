<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
define( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG', 'vc_shortcodes_css_class' );
}

/**
 * @param $content
 * @param bool $autop
 *
 * @since 4.2
 * @return string
 */
function perch_js_remove_wpautop( $content, $autop = false ) {

	if ( $autop ) { // Possible to use !preg_match('('.WPBMap::getTagsRegexp().')', $content)
		$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
	}

	return do_shortcode( shortcode_unautop( $content ) );
}

if ( ! function_exists( 'perch_vc_parse_multi_attribute' ) ) {
/**
 * Parse string like "title:Hello world|weekday:Monday" to array('title' => 'Hello World', 'weekday' => 'Monday')
 *
 * @param $value
 * @param array $default
 *
 * @since 4.2
 * @return array
 */
function perch_vc_parse_multi_attribute( $value, $default = array() ) {
	$result = $default;
	$params_pairs = explode( '|', $value );
	if ( ! empty( $params_pairs ) ) {
		foreach ( $params_pairs as $pair ) {
			$param = preg_split( '/\:/', $pair );
			if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
				$result[ $param[0] ] = rawurldecode( $param[1] );
			}
		}
	}

	return $result;
}
}

if ( ! function_exists( 'perch_vc_param_options_parse_values' ) ) {
/**
 * @param $v
 *
 * @since 4.2
 * @return string
 */
function perch_vc_param_options_parse_values( $v ) {
	return rawurldecode( $v );
}
}

if ( ! function_exists( 'perch_vc_param_options_get_settings' ) ) {
/**
 * @param $name
 * @param $settings
 *
 * @since 4.2
 * @return bool
 */
function perch_vc_param_options_get_settings( $name, $settings ) {
	if ( is_array( $settings ) ) {
		foreach ( $settings as $params ) {
			if ( isset( $params['name'] ) && $params['name'] === $name && isset( $params['type'] ) ) {
				return $params;
			}
		}
	}

	return false;
}
}

if ( ! function_exists( 'perch_vc_convert_atts_to_string' ) ) {
/**
 * @param $atts
 *
 * @since 4.2
 * @return string
 */
function perch_vc_convert_atts_to_string( $atts ) {
	$output = '';
	foreach ( $atts as $key => $value ) {
		$output .= ' ' . $key . '="' . $value . '"';
	}

	return $output;
}
}

if ( ! function_exists( 'perch_vc_parse_options_string' ) ) {
/**
 * @param $string
 * @param $tag
 * @param $param
 *
 * @since 4.2
 * @return array
 */
function perch_vc_parse_options_string( $string, $tag, $param ) {
	$options = $option_settings_list = array();
	$settings = WPBMap::getParam( $tag, $param );

	foreach ( preg_split( '/\|/', $string ) as $value ) {
		if ( preg_match( '/\:/', $value ) ) {
			$split = preg_split( '/\:/', $value );
			$option_name = $split[0];
			$option_settings = $option_settings_list[ $option_name ] = vc_param_options_get_settings( $option_name, $settings['options'] );
			if ( isset( $option_settings['type'] ) && 'checkbox' === $option_settings['type'] ) {
				$option_value = array_map( 'vc_param_options_parse_values', preg_split( '/\,/', $split[1] ) );
			} else {
				$option_value = rawurldecode( $split[1] );
			}
			$options[ $option_name ] = $option_value;
		}
	}
	if ( isset( $settings['options'] ) ) {
		foreach ( $settings['options'] as $setting_option ) {
			if ( 'separator' !== $setting_option['type'] && isset( $setting_option['value'] ) && empty( $options[ $setting_option['name'] ] ) ) {
				$options[ $setting_option['name'] ] = 'checkbox' === $setting_option['type'] ? preg_split( '/\,/', $setting_option['value'] ) : $setting_option['value'];
			}
			if ( isset( $setting_option['name'] ) && isset( $options[ $setting_option['name'] ] ) && isset( $setting_option['value_type'] ) ) {
				if ( 'integer' === $setting_option['value_type'] ) {
					$options[ $setting_option['name'] ] = (int) $options[ $setting_option['name'] ];
				} elseif ( 'float' === $setting_option['value_type'] ) {
					$options[ $setting_option['name'] ] = (float) $options[ $setting_option['name'] ];
				} elseif ( 'boolean' === $setting_option['value_type'] ) {
					$options[ $setting_option['name'] ] = (boolean) $options[ $setting_option['name'] ];
				}
			}
		}
	}

	return $options;
}
}

if ( ! function_exists( 'perch_vc_build_safe_css_class' ) ) {
/**
 * Convert string to a valid css class name.
 *
 * @since 4.3
 *
 * @param string $class
 *
 * @return string
 */
function perch_vc_build_safe_css_class( $class ) {
	return preg_replace( '/\W+/', '', strtolower( str_replace( ' ', '_', strip_tags( $class ) ) ) );
}
}

if ( ! function_exists( 'perch_vc_camel_case' ) ) {
/**
 * VC Convert a value to camel case.
 *
 * @since 4.3
 *
 * @param  string $value
 *
 * @return string
 */
function perch_vc_camel_case( $value ) {
	return lcfirst( vc_studly( $value ) );
}
}

if ( ! function_exists( 'perch_vc_icon_element_fonts_enqueue' ) ) {
/**
 * Enqueue icon element font
 * @todo move to separate folder
 * @since 4.4
 *
 * @param $font
 */
function perch_vc_icon_element_fonts_enqueue( $font ) {
	switch ( $font ) {
		case 'fontawesome':
			wp_enqueue_style( 'font-awesome' );
			break;
		case 'openiconic':
			wp_enqueue_style( 'vc_openiconic' );
			break;
		case 'typicons':
			wp_enqueue_style( 'vc_typicons' );
			break;
		case 'entypo':
			wp_enqueue_style( 'vc_entypo' );
			break;
		case 'linecons':
			wp_enqueue_style( 'vc_linecons' );
			break;
		case 'monosocial':
			wp_enqueue_style( 'vc_monosocialiconsfont' );
			break;
		case 'material':
			wp_enqueue_style( 'vc_material' );
			break;
		default:
			do_action( 'vc_enqueue_font_icon_element', $font ); // hook to custom do enqueue style
	}
}
}

if ( ! function_exists( 'perch_vc_shortcode_attribute_parse' ) ) {
/**
 * Function merges defaults attributes in attributes by keeping it values
 *
 * Example
 *      array defaults     |   array attributes     |    result array
 *      'color'=>'black',         -                   'color'=>'black',
 *      'target'=>'_self',      'target'=>'_blank',   'target'=>'_blank',
 *             -                'link'=>'google.com'  'link'=>'google.com'
 *
 * @since 4.4
 *
 * @param array $defaults
 * @param array $attributes
 *
 * @return array - merged attributes
 *
 * @see vc_map_get_attributes
 */
function perch_vc_shortcode_attribute_parse( $defaults = array(), $attributes = array() ) {
	$atts = $attributes + shortcode_atts( $defaults, $attributes );

	return $atts;
}
}

if ( ! function_exists( 'perch_vc_get_shortcode_regex' ) ) {
function perch_vc_get_shortcode_regex( $tagregexp = '' ) {
	if ( 0 === strlen( $tagregexp ) ) {
		return get_shortcode_regex();
	}

	return '\\['                              // Opening bracket
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "($tagregexp)"                     // 2: Shortcode name
		. '(?![\\w-])'                       // Not followed by word character or hyphen
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
		. '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		. '(?:' . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		. '[^\\]\\/]*'               // Not a closing bracket or forward slash
		. ')*?' . ')' . '(?:' . '(\\/)'                        // 4: Self closing tag ...
		. '\\]'                          // ... and closing bracket
		. '|' . '\\]'                          // Closing bracket
		. '(?:' . '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		. '[^\\[]*+'             // Not an opening bracket
		. '(?:' . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		. '[^\\[]*+'         // Not an opening bracket
		. ')*+' . ')' . '\\[\\/\\2\\]'             // Closing shortcode tag
		. ')?' . ')' . '(\\]?)';
}
}

if ( ! function_exists( 'perch_vc_message_warning' ) ) {
/**
 * Used to send warning message
 *
 * @since 4.5
 *
 * @param $message
 *
 * @return string
 */
function perch_vc_message_warning( $message ) {
	return '<div class="wpb_element_wrapper"><div class="vc_message_box vc_message_box-standard vc_message_box-rounded vc_color-warning">
	<div class="vc_message_box-icon"><i class="fa fa-exclamation-triangle"></i>
	</div><p class="messagebox_text">' . $message . '</p>
</div></div>';
}
}


if ( ! function_exists( 'perch_vc_has_class' ) ) {
/**
 * Check if element has specific class
 *
 * E.g. f('foo', 'foo bar baz') -> true
 *
 * @param string $class Class to check for
 * @param string $classes Classes separated by space(s)
 *
 * @return bool
 */
function perch_vc_has_class( $class, $classes ) {
	return in_array( $class, explode( ' ', strtolower( $classes ) ) );
}
}

if ( ! function_exists( 'perch_vc_remove_class' ) ) {
/**
 * Remove specific class from classes string
 *
 * E.g. f('foo', 'foo bar baz') -> 'bar baz'
 *
 * @param string $class Class to remove
 * @param string $classes Classes separated by space(s)
 *
 * @return string
 */
function perch_vc_remove_class( $class, $classes ) {
	$list_classes = explode( ' ', strtolower( $classes ) );

	$key = array_search( $class, $list_classes, true );

	if ( false === $key ) {
		return $classes;
	}

	unset( $list_classes[ $key ] );

	return implode( ' ', $list_classes );
}
}


if ( ! function_exists( 'perch_vc_stringify_attributes' ) ) {
/**
 * Convert array of named params to string version
 * All values will be escaped
 *
 * E.g. f(array('name' => 'foo', 'id' => 'bar')) -> 'name="foo" id="bar"'
 *
 * @param $attributes
 *
 * @return string
 */
function perch_vc_stringify_attributes( $attributes ) {
	$atts = array();
	foreach ( $attributes as $name => $value ) {
		$atts[] = $name . '="' . esc_attr( $value ) . '"';
	}

	return implode( ' ', $atts );
}
}


if ( ! function_exists( 'perch_vc_do_shortcode' ) ) {
/**
 * Do shortcode single render point
 *
 * @param $atts
 * @param null $content
 * @param null $tag
 *
 * @return string
 */
function perch_vc_do_shortcode( $atts, $content = null, $tag = null ) {
	return Vc_Shortcodes_Manager::getInstance()->getElementClass( $tag )->output( $atts, $content );
}
}


if ( ! function_exists( 'perch_vc_random_string' ) ) {
/**
 * Return random string
 *
 * @param int $length
 *
 * @return string
 */
function perch_vc_random_string( $length = 10 ) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$len = strlen( $characters );
	$str = '';
	for ( $i = 0; $i < $length; $i ++ ) {
		$str .= $characters[ rand( 0, $len - 1 ) ];
	}

	return $str;
}
}

if ( ! function_exists( 'perch_vc_slugify' ) ) {
function perch_vc_slugify( $str ) {
	$str = strtolower( $str );
	$str = html_entity_decode( $str );
	$str = preg_replace( '/[^\w ]+/', '', $str );
	$str = preg_replace( '/ +/', '-', $str );

	return $str;
}
}

if ( ! function_exists( 'perch_vc_vc_link_form_field' ) ) {
/**
 * @param $settings
 * @param $value
 *
 * @since 4.2
 * @return string
 */
function perch_vc_vc_link_form_field( $settings, $value ) {
	$link = vc_build_link( $value );

	return '<div class="vc_link">'
	       . '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . htmlentities( $value, ENT_QUOTES, 'utf-8' ) . '" data-json="' . htmlentities( json_encode( $link ), ENT_QUOTES, 'utf-8' ) . '" />'
	       . '<a href="#" class="button vc_link-build ' . $settings['param_name'] . '_button">' . __( 'Select URL', 'js_composer' ) . '</a> <span class="vc_link_label_title vc_link_label">' . __( 'Title', 'js_composer' ) . ':</span> <span class="title-label">' . $link['title'] . '</span> <span class="vc_link_label">' . __( 'URL', 'js_composer' ) . ':</span> <span class="url-label">' . $link['url'] . ' ' . $link['target'] . '</span>'
	       . '</div>';
}
}

if ( ! function_exists( 'perch_vc_build_link' ) ) {
	/**
	 * @param $value
	 *
	 * @since 4.2
	 * @return array
	 */
	function perch_vc_build_link( $value ) {
		return perch_vc_parse_multi_attribute( $value, array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' ) );
	}
}

/**
 * @param $param
 * @param $value
 *
 * @since 4.2
 * @return mixed|string
 */
function perch_get_dropdown_option( $param, $value ) {
	if ( '' === $value && is_array( $param['value'] ) ) {
		$value = array_shift( $param['value'] );
	}
	if ( is_array( $value ) ) {
		reset( $value );
		$value = isset( $value['value'] ) ? $value['value'] : current( $value );
	}
	$value = preg_replace( '/\s/', '_', $value );

	return ( '' !== $value ? $value : '' );
}

/**
 * @param $prefix
 * @param $color
 *
 * @since 4.2
 * @return string
 */
function perch_get_css_color( $prefix, $color ) {
	$rgb_color = preg_match( '/rgba/', $color ) ? preg_replace( array(
		'/\s+/',
		'/^rgba\((\d+)\,(\d+)\,(\d+)\,([\d\.]+)\)$/',
	), array(
		'',
		'rgb($1,$2,$3)',
	), $color ) : $color;
	$string = $prefix . ':' . $rgb_color . ';';
	if ( $rgb_color !== $color ) {
		$string .= $prefix . ':' . $color . ';';
	}

	return $string;
}

/**
 * @param $param_value
 * @param string $prefix
 *
 * @since 4.2
 * @return string
 */
function perch_shortcode_custom_css_class( $param_value, $prefix = '' ) {
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';

	return $css_class;
}

/**
 * @param $subject
 * @param $property
 * @param bool|false $strict
 *
 * @since 4.9
 * @return bool
 */
function perch_shortcode_custom_css_has_property( $subject, $property, $strict = false ) {
	$styles = array();
	$pattern = '/\{([^\}]*?)\}/i';
	preg_match( $pattern, $subject, $styles );
	if ( array_key_exists( 1, $styles ) ) {
		$styles = explode( ';', $styles[1] );
	}
	$new_styles = array();
	foreach ( $styles as $val ) {
		$val = explode( ':', $val );
		if ( is_array( $property ) ) {
			foreach ( $property as $prop ) {
				$pos = strpos( $val[0], $prop );
				$full = ( $strict ) ? ( 0 === $pos && strlen( $val[0] ) === strlen( $prop ) ) : true;
				if ( false !== $pos && $full ) {
					$new_styles[] = $val;
				}
			}
		} else {
			$pos = strpos( $val[0], $property );
			$full = ( $strict ) ? ( 0 === $pos && strlen( $val[0] ) === strlen( $property ) ) : true;
			if ( false !== $pos && $full ) {
				$new_styles[] = $val;
			}
		}
	}

	return ! empty( $new_styles );
}

add_action( 'vc_after_init_base', 'perch_add_more_custom_layouts' );
function perch_add_more_custom_layouts() {
  global $vc_row_layouts;
  $new_layouts = array(
      'cells' => '512_112_12',
      'mask' => '424',
      'title' => 'Custom 5/12 + 1/12 + 6/12',
      'icon_class' => '512_112_12' 
    );
  array_push( $vc_row_layouts,  $new_layouts );

  $new_layouts = array(
      'cells' => '12_112_512',
      'mask' => '424',
      'title' => 'Custom 6/12 + 1/12 + 5/12',
      'icon_class' => '12_112_512' 
    );
  array_push( $vc_row_layouts,  $new_layouts );

  $new_layouts = array(
      'cells' => '112_56_112',
      'mask' => '424',
      'title' => 'Custom 1/12 + 10/12 + 1/12',
      'icon_class' => '112_56_112' 
    );
  array_push( $vc_row_layouts,  $new_layouts );

  
}