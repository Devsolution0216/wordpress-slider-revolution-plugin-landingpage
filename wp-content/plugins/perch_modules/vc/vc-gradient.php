<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Class Perch_Modules_Vc_Gradient_Container
 * @since 4.3
 * vc_map examples:
 *  array(
 *        'type' => 'gradient_container',
 *        'param_name' => 'gradient_container',
 *        'value'=>'',
 *        'settings'=>array(
 *            'fields'=>array(
 *                'tag'=>'h2',
 *                'text_align',
 *                'font_size',
 *                'line_height',
 *                'color',
 *
 *                'tag_description' => __('Select element tag.','js_composer'),
 *                'text_align_description' => __('Select text alignment.','js_composer'),
 *                'font_size_description' => __('Enter font size.','js_composer'),
 *                'line_height_description' => __('Enter line height.','js_composer'),
 *                'color_description' => __('Select color for your element.','js_composer'),
 *            ),
 *        ),
 *    ),
 *  Ordering of fields, font_family, tag, text_align and etc. will be Same as ordering in array!
 *  To provide default value to field use 'key' => 'value'
 */
class Perch_Modules_Vc_Gradient_Container {

	/**
	 * @param $settings
	 * @param $value
	 *
	 * @return string
	 */
	public function render( $settings, $value ) {
		$fields = array();
		$values = array();
		extract( $this->_vc_gradient_container_parse_attributes( $settings['settings']['fields'], $value ) );

		$column = isset($settings['column'])? 'vc_col-sm-'.(12/intval($settings['column'])) : 'vc_col-sm-4';

		$data = array();
		$output = '<div class="perch-gradient-setting m-top-30"><div class="vc_row-fluid">';
		$output .= isset($settings['title'])? '<div class="wpb_element_label">'.esc_attr($settings['title']).'</div>' : '';
		if ( ! empty( $fields ) ) {			

			if ( isset( $fields['direction'] ) ) {
				$_output = '';
				$_name = 'direction';
               	$_value = $values['direction'];
               	$_options = $this->vc_gradient_direction_options();
               	$_placeholder = 'Direction';
               
                $data['direction'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			}

			if ( isset( $fields['color1'] ) ) {
				$_output = '';
				$_name = 'color1';
               	$_value = $values['color1'];
               	$_options = $this->vc_bg_class_options();
               	$_placeholder = 'Color 1';
               
                $data['color1'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			}

			if ( isset( $fields['color2'] ) ) {
				$_output = '';
				$_name = 'color2';
               	$_value = $values['color2'];
               	$_options = $this->vc_bg_class_options();
               	$_placeholder = 'Color 2';
               
                $data['color2'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			}
			

			$data = apply_filters( 'vc_gradient_container_output_data', $data, $fields, $values, $settings );
			// combine all in output, make sure you follow ordering
			
			foreach ( $fields as $key => $field ) {
				if ( isset( $data[ $key ] ) ) {
					$output .= $data[ $key ];					
				}
			}
			
		}

		$output .= '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . $value . '" />';
		$output .= '</div></div>';

		return $output;
	}
	/**
	 * Tag size class array
	 * @return array
	 */
	public function vc_gradient_direction_options(){
	    $array = array(
	        __('To Left', 'perch') => 'left',
	        __('To Right', 'perch') => 'right',
	        __('To top', 'perch') => 'top',
	        __('To bottom', 'perch') => 'bottom',	      
	    );

	    return $array;
	}

	/**
	 * Background class array
	 * @return array
	 */
	public function vc_bg_class_options(){
	    $allowed_color = array();

	    if( function_exists('perch_vc_background_options') ){
	    	$allowed_color = array_merge($allowed_color, perch_vc_color_options(true));
	    }

	    return apply_filters( 'vc_font_container_get_allowed_bg_class', $allowed_color );
	}

	
	/**
	 * Compact select field
	 * @return select field html
	 */
	public function _perch_ui_compact_select($name = '', $value = '', $options = array(), $placeholder = ''){   
		$output = '';
		if( empty($options) ) return $output;
		$_options = array_flip($options);	
		$first_key_value = reset($_options);		
		if($value != '') {			
			$placeholder = $_options[$value];
		}

		$placeholder = ( $placeholder != '' )? '<span>'.$placeholder.'</span>' : '<span>'.$first_key_value.'</span>';
		$output .= '<div class="vc_gradient_container_form_field-'.$name.'-container">
		<div class="select-wrapper">
			'.$placeholder.'
			<select class="perch-ui-select vc_gradient_container_form_field-'.$name.'-select perch-gradient-field" data-name="'.$name.'">';
			foreach ( $options as $key => $_value ) {
				$output .= '<option value="' . $_value . '" class="' . $_value . '" ' . ( $value == $_value ? 'selected' : '' ) . '>' . $key . '</option>';
			}
		$output .= '
			</select>			
		</div></div>';

		return $output;
	}

	/**
	 * @param $attr
	 * @param $value
	 *
	 * @return array
	 */
	public function _vc_gradient_container_parse_attributes( $attr, $value ) {

		$fields = array();
		if ( isset( $attr ) ) {
			foreach ( $attr as $key => $val ) {
				if ( is_numeric( $key ) ) {
					$fields[ $val ] = '';
				} else {
					$fields[ $key ] = $val;
				}
			}
		}

		$values = vc_parse_multi_attribute( $value, array(				
				'type' => isset( $fields['type'] ) ? $fields['type'] : 'linear',
				'direction' => isset( $fields['direction'] ) ? $fields['direction'] : 'right',
				'color1' => isset( $fields['color1'] ) ? $fields['color1'] : '',
				'color2' => isset( $fields['color2'] ) ? $fields['color2'] : '',
			)
		);

		return array( 'fields' => $fields, 'values' => $values );
	}
	
}

/**
 * @param $settings
 * @param $value
 *
 * @return mixed|void
 */
function perch_vc_gradient_container_form_field( $settings, $value ) {
	$gradient_container = new Perch_Modules_Vc_Gradient_Container();

	return apply_filters( 'vc_gradient_container_render_filter', $gradient_container->render( $settings, $value ) );
}

vc_add_shortcode_param( 'perch_vc_gradient', 'perch_vc_gradient_container_form_field' );


