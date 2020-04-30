<?php 
if( !function_exists('vc_get_shared') ):
/**
 * @param string $asset
 *
 * @return array|string
 */
function vc_get_shared( $asset = '' ) {
    switch ( $asset ) {
        case 'colors':
            return VcSharedLibrary::getColors();
            break;

        case 'colors-dashed':
            return VcSharedLibrary::getColorsDashed();
            break;

        case 'icons':
            return VcSharedLibrary::getIcons();
            break;

        case 'sizes':
            return VcSharedLibrary::getSizes();
            break;

        case 'button styles':
        case 'alert styles':
            return VcSharedLibrary::getButtonStyles();
            break;
        case 'message_box_styles':
            return VcSharedLibrary::getMessageBoxStyles();
            break;
        case 'cta styles':
            return VcSharedLibrary::getCtaStyles();
            break;

        case 'text align':
            return VcSharedLibrary::getTextAlign();
            break;

        case 'cta widths':
        case 'separator widths':
            return VcSharedLibrary::getElementWidths();
            break;

        case 'separator styles':
            return VcSharedLibrary::getSeparatorStyles();
            break;

        case 'separator border widths':
            return VcSharedLibrary::getBorderWidths();
            break;

        case 'single image styles':
            return VcSharedLibrary::getBoxStyles();
            break;

        case 'single image external styles':
            return VcSharedLibrary::getBoxStyles( array( 'default', 'round' ) );
            break;

        case 'toggle styles':
            return VcSharedLibrary::getToggleStyles();
            break;

        case 'animation styles':
            return VcSharedLibrary::getAnimationStyles();
            break;

        default:
            # code...
            break;
    }

    return '';
}
endif; 

if( !function_exists('vc_extract_dimensions') ):
/**
 * Extract width/height from string
 *
 * @param string $dimensions WxH
 *
 * @return mixed array(width, height) or false
 * @since 4.7
 *
 */
function vc_extract_dimensions( $dimensions ) {
  $dimensions = str_replace( ' ', '', $dimensions );
  $matches = null;

  if ( preg_match( '/(\d+)x(\d+)/', $dimensions, $matches ) ) {
    return array(
      $matches[1],
      $matches[2],
    );
  }

  return false;
}
endif;
function perch_shortcodes_vc_class(){
    return apply_filters( 'perch_modules/vc_class', 'perch-vc' );
}

function perch_shortcodes_vc_category(){
    return apply_filters( 'perch_modules/vc_category', 'Perch' );
}

function perch_modules_icon_type_options(){
    $array = array(
            __( 'Fontawesome', 'pergo' ) => 'fontawesome',
            __( 'Image', 'pergo' ) => 'image',
        );
    return apply_filters( 'perch_modules/icon_type_options', $array );
}


function perch_modules_icon_type_std(){
    return apply_filters( 'perch_modules/icon_type_std', 'fontawesome' );
}

function perch_range_option( $start, $limit, $step = 1 ) {
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

function perch_vc_dropdown_options($start, $limit, $step = 1, $unit = 'px', $prefix = '' ){
   $range = perch_range_option( $start, $limit, $step );
	 $array = array();
    foreach( $range as $k => $v ) {
      $array[$prefix . $v] = $v . $unit;
    } 

   $array = array_flip($array);

   return apply_filters( 'perch_vc_dropdown_options', $array );
}

function perch_numeric_dropdown_options($prefix = 'px', $postfix = '', $label = '', $args = array() ){
    $args = shortcode_atts( array('start' => 1, 'limit' => 10, 'step' => 1), $args );
    extract($args);

    $range = perch_range_option( $start, $limit, $step );
    $array = array( 'Inherit' );
    foreach( $range as $k => $v ) {
      $array[$prefix . $v .$postfix] = $label.$v;
    } 

    $array = array_flip($array);

    return apply_filters( 'perch_vc_dropdown_options', $array );
}

vc_add_shortcode_param( 'number', 'perch_module_number_settings_field' );
vc_add_shortcode_param( 'perch_select', 'perch_module_perch_select_settings_field' );
vc_add_shortcode_param( 'image_upload', 'perch_module_vc_image_upload_settings_field' );

require_once PERCH_MODULES_DIR . '/vc/inc.php';
require_once PERCH_MODULES_DIR . '/vc/vc-typography-field.php';
require_once PERCH_MODULES_DIR . '/vc/vc-gradient.php';


/* global vc include files */
foreach ( glob( PERCH_MODULES_DIR . "/vc-extends/*.php" ) as $filename ) {
    include $filename;
} //glob( PERCH_MODULES_DIR . "/admin/vc-extends/*.php" ) as $filename

/**
* get param array

* @return array
*/
function perch_modules_get_param_array( $params, $param_name, $key = 'param_name' ){

     $arrKey = array_search($param_name, array_column($params, $key));
     if($params[$arrKey]['param_name'] == $param_name){         
         return $params[$arrKey];
     }else{
        return false;
     }
}
/**
* is param_name exists in params
* @param array, string, string(optional)

* @return array
*/
function perch_modules_is_field_id_exists($params, $param_name, $key = 'param_name'){
    $arrKey = array_search($param_name, array_column($params, $key));
     if( isset($params[$arrKey]['param_name']) && ($params[$arrKey]['param_name'] == $param_name)){         
         return true;
     }else{
        return false;
     }
}

function perch_modules_get_vc_param_html( $param_name = '', $atts = array(), $map_arr = array() ){    
	$_atts = $atts;
    $output = $all_classes = $inline_css = $wrapper_attributes = '';

    if( !isset($atts[$param_name]) || ($atts[$param_name] == '') ) return '';
    $output = $atts[$param_name];

    $output = apply_filters('perch_modules_text_filter', $output, $param_name, $_atts, $map_arr);


    $font_container = $param_name.'_font_container';
    if( isset($atts[$font_container])  && ($atts[$font_container] != '')){       
        $typo_settings =  perch_modules_get_vc_typography_value($atts[$font_container], $param_name, $_atts);
        extract($typo_settings);
        if( '' == $inner_tag ){
            $output = "<{$tag}{$all_classes}{$inline_css}{$wrapper_attributes}>{$output}</{$tag}>";
        }else{
            $output = "<{$tag}{$all_classes}{$inline_css}{$wrapper_attributes}><{$inner_tag}>{$output}</{$inner_tag}></{$tag}>";
        }
        
    }
    


    return $output;
}

function perch_modules_highlight_text($output, $param_name, $atts, $map_arr = array() ){
	
	$field_id = $param_name. '_highlight_text_enable';
  if( !isset($atts[$field_id]) ) return $output;
  
	if( ('' != $param_name) && ( 'yes' == $atts[$field_id] ) ){
		$key = $param_name. '_highlight_text';
		if( isset($atts[$key])  && ($atts[$key] != '')){
			$args = perch_modules_get_vc_typography_value($atts[$key], $param_name, $atts);
			$output = perch_modules_parse_text($output, $args);
		}
	}

	return $output;
}
add_filter( 'perch_modules_text_filter', 'perch_modules_highlight_text', 20, 4 );

function perch_modules_generate_default_html($output, $param_name, $atts, $map_arr = array() ){
    if( !empty($map_arr) ){
        if( perch_modules_is_field_id_exists($map_arr, $param_name) ){
            $param = perch_modules_get_param_array($map_arr, $param_name);           
            if( isset($param['perch_settings']) ){
                $settings = $param['perch_settings'];
                $output = perch_generate_html_by_settings($output, $param_name, $atts, $settings );
            }
        }
    }
   

    return $output;
}

add_filter( 'perch_modules_text_filter', 'perch_modules_generate_default_html', 10, 4 );
function perch_generate_html_by_settings( $output, $param_name, $atts, $settings ){    
    if( isset($settings['typo_settings']) && !$settings['typo_settings'] ){
        if(isset($settings['typo_std']) && ($settings['typo_std'] != '') ){
            $value = $settings['typo_std'];
            $output = perch_generate_input_field_html_by_settings($output, $value, $atts);         
        }
    }
    
    return $output; 
}


function perch_generate_input_field_html_by_settings( $output, $value, $atts ){    
  
    if( $value != '' ):
      $fields = array();
      $values = '';
      $font_container = new Perch_Modules_Vc_Font_Container();    
      $_args = $font_container->_vc_font_container_parse_attributes($fields, $values);
      $args = $_args['values'];

      $atts = vc_parse_multi_attribute($value, $args);
      $atts = shortcode_atts( $args , $atts);

      $classes = perch_generate_class_inline_css( $atts, 'classes' );
      if(!empty($classes)){
          $atts[ 'all_classes' ] = ' class="'.implode(' ', $classes).'"';
      }
      $css = perch_generate_class_inline_css( $atts, 'css' );   
      if(!empty($css)){
          $atts[ 'inline_css' ] = ' style="'.implode(' ', $css).'"';
      }
      extract($atts);
      $output = "<{$tag}{$all_classes}{$inline_css}>{$output}</{$tag}>";   
    endif;           
        
    
    return $output; 
}

add_filter( 'perch_modules/add_item_link', 'perch_add_link_in_vc_element', 10, 3 );
function perch_add_link_in_vc_element( $value, $field_id, $atts ){
    $args = array( 
        'title' => '', 
        'url' => '', 
        'target' => '',
        'class' => '',
        'link_title' => $value 
    );    
    $enable_id = $field_id.'_link';

    if( isset($atts[$enable_id]) && ($atts[$enable_id] == 'yes') ){
        $link_id = $field_id.'_url';
        $href = vc_build_link( $atts[$link_id] );        
        $link_atts = shortcode_atts($args , $href);
        $value = perch_build_safe_link($link_atts);
    }

    return $value;
}

/*
*
* @param array('title'=>'', 'url' => '', 'target' => '', 'link_title' => $link_title)
*
* @since 1.2.5
* @return mixed html
*/
function perch_build_safe_link( $args = array() ){
   
    if( empty($args) ) return false;
    extract($args);

    if($link_title == '') return false;
    if( $url == '' ) return $link_title;

    $atts = array();

    $atts[] = 'href="'.esc_url($url).'"';
    $atts[] = ($class != '')? 'class="'.esc_attr($class).'"' : '';    
    $atts[] = ( $title != '' )? 'title="'.esc_attr($title).'"' : '';   
    $atts[] = ( $target != '' )? 'target="'.esc_attr($target).'"' : '';
    $atts = array_filter($atts);

    $output = '<a '.implode(' ', $atts).'>'.$link_title.'</a>';

    return $output;

}

/*
*
* @param array
* @param string
*
* @since 1.0.1
* @return mixed html
*/
function perch_element_buttons_html($atts, $extra_class = ''){
    if( empty($atts) ) return false;
    if( !isset($atts['btn_url']) ) return false;

    $args = array( 
        'title' => '', 
        'url' => '', 
        'target' => '',
        'class' => 'btn',
    ); 

    $href = vc_build_link( $atts['btn_url'] ); 
    $link_atts = shortcode_atts($args , $href);
    $link_atts['class'] = ( isset($atts['button_style']) && ($atts['button_style'] != ''))? 'btn btn-arrow '.$atts['button_style'] : $args['class'];

    if( $extra_class != '' ){
        $output = '<div class="'.esc_attr($extra_class).'">'. perch_build_safe_btn_link($link_atts).'</div>';
    }else{
        $output = perch_build_safe_btn_link($link_atts);
    }
    

    return $output;
}

/*
*
* @param array
*
* @since 1.0.1
* @return mixed html
*/
function perch_build_safe_btn_link( $args = array() ){
   
    if( empty($args) ) return false;
    extract($args);  

    if( $title == '' ) return false;  

    $atts = array();

    $atts[] = 'href="'.esc_url($url).'"';
    $atts[] = ($class != '')? 'class="'.esc_attr($class).'"' : '';    
    $atts[] = ( $title != '' )? 'title="'.esc_attr($title).'"' : '';   
    $atts[] = ( $target != '' )? 'target="'.esc_attr($target).'"' : '';
    $atts = array_filter($atts);

    $output = '<a '.implode(' ', $atts).'><span>'.$title.' <i class="fas fa-angle-double-right"></i></span></a>';

    return $output;

}