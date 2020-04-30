<?php
function shiftkey_get_iconpicker_inputgroup( $name, $atts, $iconset='fontawesome', $iconprefix=""){
	if($name == '') return;

	extract(shortcode_atts( array('icon' => 'icon-home', 'input' => ''), $atts ));
	$iconsetArr = explode('|', $iconset);
	foreach ($iconsetArr as $key => $value) {
		wp_enqueue_style( 'shiftkey-'.esc_attr($value));
	}	
	wp_enqueue_style( 'shiftkey-iconpicker');
	wp_enqueue_script( 'iconset-fontawesome');
	wp_enqueue_script( 'shiftkey-iconset-all');
	wp_enqueue_script('shiftkey-iconpicker');

	return '<div class="input-group">
		    <span class="input-group-btn">
		        <button class="btn btn-primary iconpicker" name="'.esc_attr($name).'[icon]" data-iconset="'.esc_attr($iconset).'" data-icon="'.esc_attr($icon).'" data-cols="9" data-selected-class="btn-primary" role="iconpicker">
		        '.(($icon != '')?'<i class="'.$iconprefix.esc_attr($icon).'"></i>' : '<i class="fa fa-adjust"></i>').'
		        <input type="hidden" name="'.esc_attr($name).'[icon]" value="'.esc_attr($icon).'">
		        </button>
		    </span>
		    <input type="text" name="'.esc_attr($name).'[input]" class="form-control" value="'.esc_attr($input).'">
		</div>';

}


add_filter( 'ot_option_types_array', 'shiftkey_ot_option_types_array', 10, 1 );
function shiftkey_ot_option_types_array($args){
	$args['iconpicker_input'] = 'Icon picker input group';
	return $args;
}

/**
 * Text option type
 *
 * See @ot_display_by_type to see the full list of available arshiftkeyents.
 *
 * @param     array     An array of arshiftkeyents.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_iconpicker_input' ) ) {  
  function ot_type_iconpicker_input( $args = array() ) {  

    /* turns arshiftkeyents array into variables */ 
    extract( $args ); 

    /* verify a description */
    $has_desc = $field_desc ? true : false;    

    /* format setting outer wrapper */
    echo '<div class="format-setting type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';      

      /* description */
      if($has_desc) echo '<div class="description">' . wpb_js_remove_wpautop( $field_desc ) . '</div>';      

      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';
     	$field_value = !empty($field_value)? $field_value : $field_std;
     	$name = 'option_tree['.$field_id.']';
        echo shiftkey_get_iconpicker_inputgroup( $field_name,  $field_value );    
      echo '</div>';   
    echo '</div>';   

  } 

}

function shiftkey_ot_iconpicker($field_id = ''){
  if( $field_id == '' ) return;
  $field_value = shiftkey_get_option($field_id);
  $iconclass = '';
  if( $field_value != '' ) { $v= explode('|',$field_value); $iconclass = $v[0].' '.$v[1]; }
  return ( $iconclass != '' )? "<i class='{$iconclass}'></i>" : "";
}

function shiftkey_ot_get_icon($field_value = ''){
  if( $field_value == '' ) return;
  $iconclass = '';
  if( $field_value != '' ) { $v= explode('|',$field_value); $iconclass = $v[0].' '.$v[1]; }
  return ( $iconclass != '' )? "<i class='{$iconclass}'></i>" : "";
}