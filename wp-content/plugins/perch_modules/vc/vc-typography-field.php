<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Class Perch_Modules_Vc_Font_Container
 * @since 4.3
 * vc_map examples:
 *  array(
 *        'type' => 'font_container',
 *        'param_name' => 'font_container',
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
class Perch_Modules_Vc_Font_Container {

	/**
	 * @param $settings
	 * @param $value
	 *
	 * @return string
	 */
	public function render( $settings, $value ) {
		$fields = array();
		$values = array();
		extract( $this->_vc_font_container_parse_attributes( $settings['settings']['fields'], $value ) );

		$column = isset($settings['column'])? 'vc_col-sm-'.(12/intval($settings['column'])) : 'vc_col-sm-3';

		$data = array();
		$output = '<div class="perch-typography-setting m-top-30"><div class="vc_row-fluid">';
		$output .= (isset($settings['title'])  && ($settings['title'] != ''))? '<div class="wpb_element_label">'.esc_attr($settings['title']).'</div>' : '';
		if ( ! empty( $fields ) ) {

			if ( isset( $fields['tag'] ) ) {
				$_name = 'tag';
               	$_value = $values['tag'];
               	$_options = $this->_vc_font_container_get_allowed_tags();
               	$_placeholder = '';

               	$data['tag'] = '<div class="'.esc_attr($column).'">';
                $data['tag'] .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                $data['tag'] .= '<span class="vc_description clear">Select tag</span>';
				$data['tag'] .= '</div>';
			}

			if ( isset( $fields['inner_tag'] ) ) {

				$_output = '';
				$_name = 'inner_tag';
               	$_value = $values['inner_tag'];
               	$_options = $this->_vc_font_container_get_allowed_inner_tags();
               	$_placeholder = '';


               	$_output = '<div class="'.esc_attr($column).'">';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                $_output .= '<span class="vc_description clear">Select inner tag</span>';
				$_output .= '</div>';
				$data['inner_tag'] = $_output;
			}

			if ( isset( $fields['size'] ) ) {
				$_output = '';
				$_name = 'size';
               	$_value = $values['size'];
               	$_options = $this->_vc_size_class_options();
               	$_placeholder = 'Font size';


               	$_output = '<div class="'.esc_attr($column).'">';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                $_output .= '<span class="vc_description clear">Text size</span>';
				$_output .= '</div>';
				$data['size'] = $_output;				
			}

			if ( isset( $fields['text_color'] ) ) {
				$_output = '';
				$_name = 'text_color';
               	$_value = $values['text_color'];
               	$_options = $this->_vc_color_class_options();
               	$_placeholder = 'Text color';


               	$_output = '<div class="'.esc_attr($column).'">';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                 $_output .= '<span class="vc_description clear">Text color</span>';
				$_output .= '</div>';
				
				$data['text_color'] = $_output;
				
			}

			if ( isset( $fields['text_align'] ) ) {
				$_output = '';
				$_name = 'text_align';
               	$_value = $values['text_align'];
               	$_options = $this->_vc_align_class_options();
               	$_placeholder = 'Text align';


               	$_output = '<div class="'.esc_attr($column).'">';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                $_output .= '<span class="vc_description clear">Text alignment</span>';
				$_output .= '</div>';
				$data['text_align'] = $_output;
				
			}

			if ( isset( $fields['extra_class'] ) ) {
				$_output = '';
				$_name = 'extra_class';
               	$_value = $values['extra_class'];
               	$_placeholder = 'Additional class';


               	$_output = '<div class="'.esc_attr($column).'">';
                $_output .= '<input type="text" value="'.esc_attr($_value).'" class="vc_font_container_form_field-'.$_name.'-input perch-typography-field" data-name="'.$_name.'" placeholder="'.esc_attr($_placeholder).'">';
				
				$_output .= '</div>';
				$data['extra_class'] = $_output;
				
			}

			
			if ( isset( $fields['text_underline'] ) ) {
				$_name = 'text_underline';
               	$_value = $values['text_underline'];
               	$_options = $this->_vc_uncerline_class_options();
               	$_placeholder = 'Text underline';


               	$data['text_underline'] = '<div class="'.esc_attr($column).'">';
                $data['text_underline'] .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                $data['text_underline'] .= '<span class="vc_description clear">Text underline color</span>';
				$data['text_underline'] .= '</div>';			
			}
			if ( isset( $fields['text_bg'] ) ) {
				$_name = 'text_bg';
               	$_value = $values['text_bg'];
               	$_options = $this->_vc_bg_class_options();
               	$_placeholder = 'background color';


               	$data['text_bg'] = '<div class="'.esc_attr($column).'">';
                $data['text_bg'] .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
                $data['text_bg'] .= '<span class="vc_description clear">Choose background color</span>';
				$data['text_bg'] .= '</div>';
			}

			

			if ( isset( $fields['letter_spacing'] ) ) {
				$_name = 'letter_spacing';
               	$_value = $values['letter_spacing'];
               	$_options = $this->_vc_letter_spacing_options();
               	$_placeholder = 'Letter spacing';
               	
                $data['letter_spacing'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);				
			}

			if ( isset( $fields['text_transform'] ) ) {
				$_output = '';
				$_name = 'text_transform';
               	$_value = $values['text_transform'];
               	$_options = $this->_vc_text_transformations_options();
               	$_placeholder = 'text-transform';


               	$_output = '';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			
				$data['text_transform'] = $_output;
			}

			if ( isset( $fields['text_decoration'] ) ) {
				$_output = '';
				$_name = 'text_decoration';
               	$_value = $values['text_decoration'];
               	$_options = $this->_vc_text_decorations_options();
               	$_placeholder = 'text-decoration';


               	$_output = '';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			
				$data['text_decoration'] = $_output;
			}

			if ( isset( $fields['font_variant'] ) ) {
				$_output = '';
				$_name = 'font_variant';
               	$_value = $values['font_variant'];
               	$_options = $this->_vc_font_variants_options();
               	$_placeholder = 'font-variant';


               	$_output = '';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
				
				$data['font_variant'] = $_output;
			}

			if ( isset( $fields['font_weight'] ) ) {
				$_output = '';
				$_name = 'font_weight';
               	$_value = $values['font_weight'];
               	$_options = $this->_vc_font_weights_options();
               	$_placeholder = 'font-weight';


               	$_output = '';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
				
				$data['font_weight'] = $_output;
			}

			if ( isset( $fields['font_family'] ) ) {	

				$_output = '';
				$_name = 'font_family';
               	$_value = $values['font_family'];
               	$_options = $this->_vc_font_container_get_web_safe_fonts();
               	$_placeholder = 'Font family';


               	$_output = '';
                $_output .= $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
				
				$data['font_family'] = $_output;				
			}
			if ( isset( $fields['line_height'] ) ) {
				$_output = '';
				$_name = 'line_height';
               	$_value = $values['line_height'];
               	$_options = $this->_vc_line_height_options();
               	$_placeholder = 'line-height';
               
                $data['line_height'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			}
			if ( isset( $fields['font_size'] ) ) {
				$_output = '';
				$_name = 'font_size';
               	$_value = $values['font_size'];
               	$_options = $this->_vc_font_sizes_options();
               	$_placeholder = 'font-size';
               
                $data['font_size'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
			}
			if ( isset( $fields['font_style'] ) ) {
					$_name = 'font_style';
	               	$_value = $values['font_style'];
	               	$_options = $this->_vc_font_styles_options();
	               	$_placeholder = 'font-style';
	              
	                $data['font_style'] = $this->_perch_ui_compact_select($_name, $_value, $_options, $_placeholder);
				
			}

			$data = apply_filters( 'vc_font_container_output_data', $data, $fields, $values, $settings );
			// combine all in output, make sure you follow ordering
			$_output = '';
			foreach ( $fields as $key => $field ) {
				if ( isset( $data[ $key ] ) ) {
					if( in_array($key, $this->_vc_font_container_inline_options()) ){
						$_output .= $data[ $key ];
					}else{
						$output .= $data[ $key ];
					}
					
				}
			}
			$output .= ( $_output != '' )?'<div class="vc_col-sm-12 clear"><label>Inline CSS<label>'.$_output.'</div>' : '';
		}

		$output .= '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . $value . '" />';
		$output .= '</div></div>';

		return $output;
	}
	/**
	 * If field 'font_family' is used this is list of fonts available
	 * To modify this list, you should use add_filter('vc_font_container_get_fonts_filter','your_custom_function');
	 * vc_filter: vc_font_container_get_fonts_filter - to modify list of fonts
	 * @return array list of fonts
	 */
	public function _vc_font_container_get_web_safe_fonts() {
		// this is "Web Safe FONTS" from w3c: http://www.w3schools.com/cssref/css_websafe_fonts.asp
		$web_fonts = array(
			__('Theme default', 'perch') => '',
			'Georgia' => 'Georgia, serif',
			'Palatino Linotype' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'Book Antiqua' => '"Book Antiqua", Palatino, serif',
			'Palatino' => 'Palatino, serif',
			'Times New Roman' => '"Times New Roman", Times, serif',
			'Arial' => 'Arial, Helvetica, sans-serif',
			'Arial Black' => '"Arial Black", Gadget, sans-serif',
			'Helvetica' => 'Helvetica, sans-serif',
			'Comic Sans MS' => '"Comic Sans MS", cursive, sans-serif',
			'Impact' => 'Impact, Charcoal, sans-serif',
			'Charcoal' => 'Charcoal, sans-serif',
			'Lucida Sans Unicode' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'Lucida Grande' => '"Lucida Grande", sans-serif',
			'Tahoma' => 'Tahoma, Geneva, sans-serif',
			'Geneva' => 'Geneva, sans-serif',
			'Trebuchet MS' => '"Trebuchet MS", Helvetica, sans-serif',
			'Verdana' => '"Trebuchet MS", Helvetica, sans-serif',
			'Courier New' => '"Courier New", Courier, monospace',
			'Lucida Console' => '"Lucida Console", Monaco, monospace',
			'Monaco' => 'Monaco, monospace',
		);

		
    	if( function_exists('ot_recognized_font_families') ):
		    $web_fonts = array_merge(array( ''=>'font-family'),ot_recognized_font_families('font_family'));
		     $web_fonts = array_flip( $web_fonts);
		endif;

		return apply_filters( 'vc_font_container_get_fonts_filter', $web_fonts );
	}

	public function _vc_get_font_family_name($value){
		$google_fonts  = get_theme_mod( 'ot_google_fonts', array() );		
		$family = isset($google_fonts[$value])? $google_fonts[$value]['family'] : '';

		$default = array(
            'roboto'     => 'Roboto',
            'montserrat'     => 'Montserrat',
            'arial'     => 'Arial',
            'georgia'   => 'Georgia',
            'helvetica' => 'Helvetica',
            'palatino'  => 'Palatino',
            'tahoma'    => 'Tahoma',
            'times'     => 'Times New Roman',
            'trebuchet' => 'Trebuchet',
            'verdana'   => 'Verdana'
          );
		if( ('' == $family) && isset($default[$value]) ){
			$family = $default[$value];
		}


		return $family;
	}

	/**
	 * If 'tag' field used this is list of allowed tags
	 * To modify this list, you should use add_filter('vc_font_container_get_allowed_tags','your_custom_function');
	 * vc_filter: vc_font_container_get_allowed_tags - to modify list of allowed tags by default
	 * @return array list of allowed tags
	 */
	public function _vc_font_container_get_allowed_tags() {
		$allowed_tags = array(
        __('Heading 1', 'perch') => 'h1',
        __('Heading 2', 'perch') => 'h2',
        __('Heading 3', 'perch') => 'h3',
        __('Heading 4', 'perch') => 'h4',
        __('Heading 5', 'perch') => 'h5',
        __('Heading 6', 'perch') => 'h6',
        __('Paragraph', 'perch') => 'p',
        __('Span', 'perch') => 'span',
        __('Div', 'perch') => 'div',
    );

		return apply_filters( 'vc_font_container_get_allowed_tags', $allowed_tags );

	}

	public function _vc_font_container_get_allowed_inner_tags(){
		$allowed_tags = array(
		__('Choose Inner tag', 'perch') => '',
		__('Span', 'perch') => 'span',
        __('Paragraph', 'perch') => 'p', 
        __('Small', 'perch') => 'small',       
        __('Strong', 'perch') => 'strong', 
         __('Underline', 'perch') => 'u',      
        __('Blockquote', 'perch') => 'blockquote',               
        __('Address', 'perch') => 'address',       
        __('em', 'perch') => 'em',       
        __('Del', 'perch') => 'del',       
        __('Mark', 'perch') => 'mark',       
        __('S', 'perch') => 's',       
        __('Ins', 'perch') => 'ins',       
        __('Code', 'perch') => 'code',       
        __('Pre', 'perch') => 'pre',       
        __('Var', 'perch') => 'var',       
        __('kbd', 'perch') => 'kbd',       
        __('samp', 'perch') => 'samp',   
         __('Footer', 'perch') => 'footer',     
             
    );

		return apply_filters( '_vc_font_container_get_allowed_inner_tags', $allowed_tags );

	}

	/**
	 * Tag size class array
	 * @return array
	 */
	public function _vc_size_class_options(){
	    $allowed_size = array(
	        __('Default', 'perch') => '',
	        __('Huge', 'perch') => 'huge',
	        __('Extra large', 'perch') => 'xl',
	        __('Large', 'perch') => 'lg',
	        __('Medium', 'perch') => 'md',
	        __('Small', 'perch') => 'sm',
	        __('Extra small', 'perch') => 'xs',
	    );

	    return apply_filters( 'vc_font_container_get_allowed_size_class', $allowed_size );
	}

	/**
	 * Recognized font weights
	 * @return array
	 */
	public function _vc_font_weights_options(){
	   $array = array(
	      ''    => __('font-weight', 'perch'),
	      'normal'    => 'Normal',
	      'bold'      => 'Bold',
	      'bolder'    => 'Bolder',
	      'lighter'   => 'Lighter',
	      '100'       => '100',
	      '200'       => '200',
	      '300'       => '300',
	      '400'       => '400',
	      '500'       => '500',
	      '600'       => '600',
	      '700'       => '700',
	      '800'       => '800',
	      '900'       => '900',
	      'inherit'   => 'Inherit'
	    );
	   $array = array_flip($array);

	    return apply_filters( 'vc_font_container_font_weights_options', $array );
	}

	/**
	 * Recognized letter spacing
	 * @return array
	 */
	public function _vc_letter_spacing_options(){
	   $range = $this->_perch_range( 
	      apply_filters( '_vc_letter_spacing_low_range', -0.1 ), 
	      apply_filters( '_vc_letter_spacing_high_range', 0.1 ), 
	      apply_filters( '_vc_letter_spacing_range_interval', 0.01 )
	    );
    	$unit = apply_filters( '_vc_letter_spacing_unit_type', 'em' );   

    	$array = array( '' => 'Letter spacing' );
	    foreach( $range as $k => $v ) {
	      $array[$v . $unit] = $v . $unit;
	    } 

	   $array = array_flip($array);

	    return apply_filters( 'vc_font_container_font_weights_options', $array );
	}

	/**
	 * Recognized Font sizes
	 * @return array
	 */
	public function _vc_font_sizes_options(){
	   $range = $this->_perch_range( 
	      apply_filters( '_vc_font_size_low_range', 0 ), 
	      apply_filters( '_vc_font_size_high_range', 15 ), 
	      apply_filters( '_vc_font_size_range_interval', .1 )
	    );
    	$unit = apply_filters( '_vc_font_size_unit_type', 'rem' );   

    	$array = array( '' => 'Font size' );
	    foreach( $range as $k => $v ) {
	      $array[$v . $unit] = $v . $unit;
	    } 

	   $array = array_flip($array);

	    return apply_filters( 'vc_font_container_font_sizes_options', $array );
	}

	/**
	 * Recognized text_decorations
	 * @return array
	 */
	public function _vc_text_decorations_options(){   
		$array = array( 
			'' => 'Text decorations',
			'blink'         => 'Blink',
			'inherit'       => 'Inherit',
			'line-through'  => 'Line Through',
			'none'          => 'None',
			'overline'      => 'Overline',
			'underline'     => 'Underline'
		 );	   
		$array = array_flip($array);
		return apply_filters( 'vc_font_container_text_decorations_options', $array );
	}

	/**
	 * Recognized text_transformations
	 * @return array
	 */
	public function _vc_text_transformations_options(){   
		$array = array( 
			'' => 'Text transform',
			'capitalize'  => 'Capitalize',
			'inherit'     => 'Inherit',
			'lowercase'   => 'Lowercase',
			'none'        => 'None',
			'uppercase'   => 'Uppercase'
		 );	   
		$array = array_flip($array);
		return apply_filters( 'vc_font_container_text_transformations_options', $array );
	}

	/**
	 * Recognized font_variants
	 * @return array
	 */
	public function _vc_font_variants_options(){   
		$array = array( 
			'' => 'font-variant',
			'normal'      => 'Normal',
      		'small-caps'  => 'Small Caps',
      		'inherit'     => 'Inherit'
		 );	   
		$array = array_flip($array);
		return apply_filters( 'vc_font_container_font_variants_options', $array );
	}

	/**
	 * Recognized font_styles
	 * @return array
	 */
	public function _vc_font_styles_options(){   
		$array = array( 			
			''  => 'font-style',
			'normal'  => 'Normal',
			'italic'  => 'Italic',
			'oblique' => 'Oblique',
			'inherit' => 'Inherit'
		 );	   
		$array = array_flip($array);
		return apply_filters( 'vc_font_container_font_styles_options', $array );
	}

	/**
	 * Recognized line height
	 * @return array
	 */
	public function _vc_line_height_options(){
	   $range = $this->_perch_range( 
	      apply_filters( '_vc_line_height_low_range', 0 ), 
	      apply_filters( '_vc_line_height_high_range', 15 ), 
	      apply_filters( '_vc_line_height_range_interval', .1 )
	    );
    	$unit = apply_filters( '_vc_line_height_unit_type', 'rem' );   

    	$array = array( '' => 'line-height' );
	    foreach( $range as $k => $v ) {
	      $array[$v . $unit] = $v . $unit;
	    } 

	   $array = array_flip($array);

	    return apply_filters( 'vc_font_container_line_height_options', $array );
	}
	

	/**
	 * Color class array
	 * @return array
	 */
	public function _vc_color_class_options(){
	    $allowed_color = array(
	        '' => __('Default', 'perch')
	    );

	    if( function_exists('perch_vc_color_options') ){
	    	$allowed_color = array_merge($allowed_color, perch_vc_color_options(false));
	    }

	    return apply_filters( 'vc_font_container_get_allowed_color_class', $allowed_color );
	}

	/**
	 * Underline class array
	 * @return array
	 */
	public function _vc_uncerline_class_options(){
	    $allowed_color = array(
	        __('None', 'perch') => ''
	    );

	    if( function_exists('perch_vc_underline_color_options') ){
	    	$allowed_color = array_merge($allowed_color, perch_vc_underline_color_options());
	    }

	    return apply_filters( 'vc_font_container_get_allowed_underline_class', $allowed_color );
	}

	/**
	 * Background class array
	 * @return array
	 */
	public function _vc_bg_class_options(){
	    $allowed_color = array(
	        __('None', 'perch') => ''
	    );

	    if( function_exists('perch_vc_background_options') ){
	    	$allowed_color = array_merge($allowed_color, perch_vc_background_options(true));
	    }

	    return apply_filters( 'vc_font_container_get_allowed_bg_class', $allowed_color );
	}

	/**
	 * Text align class array
	 * @return array
	 */
	public function _vc_align_class_options(){
	    $allowed_size = array(
	    			'Inherit' => '',
                    'Left' => 'text-left',
                    'Center' => 'text-center',
                    'Right' => 'text-right',
                    'Justify' => 'text-justify',                    
                );

	    return apply_filters( 'vc_font_container_get_allowed_align_class', $allowed_size );
	}

	public function _perch_range( $start, $limit, $step = 1 ) {
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
		$output .= '<div class="vc_font_container_form_field-'.$name.'-container">
		<div class="select-wrapper">
			'.$placeholder.'
			<select class="perch-ui-select vc_font_container_form_field-'.$name.'-select perch-typography-field" data-name="'.$name.'">';
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
	public function _vc_font_container_parse_attributes( $attr, $value ) {

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
				'tag' => isset( $fields['tag'] ) ? $fields['tag'] : 'h2',
				'inner_tag' => isset( $fields['inner_tag'] ) ? $fields['inner_tag'] : '',
				'text_color' => isset( $fields['text_color'] ) ? $fields['text_color'] : '',
				'font_family' => isset( $fields['font_family'] ) ? $fields['font_family'] : '',				
				'font_size' => isset( $fields['font_size'] ) ? $fields['font_size'] : '',
				'size' => isset( $fields['size'] ) ? $fields['size'] : '',
				'font_style' => isset( $fields['font_style'] ) ? $fields['font_style'] : '',
				'font_variant' => isset( $fields['font_variant'] ) ? $fields['font_variant'] : '',
				'font_weight' => isset( $fields['font_weight'] ) ? $fields['font_weight'] : '',
				'letter_spacing' => isset( $fields['letter_spacing'] ) ? $fields['letter_spacing'] : '',
				'text_decoration' => isset( $fields['text_decoration'] ) ? $fields['text_decoration'] : '',
				'text_transform' => isset( $fields['text_transform'] ) ? $fields['text_transform'] : '',
				'line_height' => isset( $fields['line_height'] ) ? $fields['line_height'] : '',				
				'text_align' => isset( $fields['text_align'] ) ? $fields['text_align'] : '',
				'text_bg' => isset( $fields['text_bg'] ) ? $fields['text_bg'] : '',
				'text_underline' => isset( $fields['text_underline'] ) ? $fields['text_underline'] : '',
				'extra_class' => isset( $fields['extra_class'] ) ? $fields['extra_class'] : '',				
				'css_class' => isset( $fields['css_class'] ) ? $fields['css_class'] : '',
				'all_classes' => '',
				'inline_css' => '',				
				'wrapper_attributes' => '',				
			)
		);

		return array( 'fields' => $fields, 'values' => $values );
	}

	public function _vc_font_container_inline_options() {
		return array('font_family', 'line_height', 'font_size', 'font_variant', 'font_weight', 'text_decoration', 'text_transform', 'letter_spacing', 'font_style');
	}

	public function _vc_font_container_class_options() {
		return array('text_color', 'size', 'text_align', 'text_bg', 'text_underline', 'extra_class', 'css_class');
	}
}

/**
 * @param $settings
 * @param $value
 *
 * @return mixed|void
 */
function perch_vc_font_container_form_field( $settings, $value ) {
	$font_container = new Perch_Modules_Vc_Font_Container();

	return apply_filters( 'vc_font_container_render_filter', $font_container->render( $settings, $value ) );
}

vc_add_shortcode_param( 'perch_vc_typography', 'perch_vc_font_container_form_field' );



/**
 * @param $value
 *
 * @return array ('classes' => '', 'css' => '') 
 */
function perch_modules_get_vc_typography_value( $value, $param_name = '', $shortcode_atts = array() ){
	$fields = array();
	$values = '';
	$font_container = new Perch_Modules_Vc_Font_Container();	
	$_args = $font_container->_vc_font_container_parse_attributes($fields, $values);
	$args = $_args['values'];
	
	$atts = vc_parse_multi_attribute($value, $args);
	$atts = shortcode_atts( $args , $atts);
	
	$classes = perch_generate_class_inline_css( $atts, 'classes' );
	$classes = apply_filters( 'perch_modules/vc_typography_classes', $classes, $param_name, $shortcode_atts );
	if(!empty($classes)){
		$atts[ 'all_classes' ] = ' class="'.implode(' ', $classes).'"';
	}

	$css = perch_generate_class_inline_css( $atts, 'css' );
	$css = apply_filters( 'perch_modules/vc_typography_inline_css', $css, $param_name, $shortcode_atts );

	if(!empty($css)){
		$atts[ 'inline_css' ] = ' style="'.implode(' ', $css).'"';
	}

	$wrapper_attributes = array();
	$wrapper_attributes = apply_filters( 'perch_modules/vc_typography_wrapper_attributes', $wrapper_attributes, $param_name, $shortcode_atts );	

	if(!empty($wrapper_attributes)){
		$atts[ 'wrapper_attributes' ] = ' '.implode(' ', $wrapper_attributes);
	}

	

	return $atts;
}



function perch_generate_class_inline_css($atts, $return = 'classes' ){
	$font_container = new Perch_Modules_Vc_Font_Container();
	$inline_elements = $font_container->_vc_font_container_inline_options();
	$class_elements = $font_container->_vc_font_container_class_options();
	$tag = $atts['tag'];	
	$tagArr = array('h1', 'h2','h3','h4','h5','h6','p', 'i');

	$classes = array();
	$css = array();
	foreach ($atts as $key => $value) {		
		if( in_array($key, $class_elements) && ( $value != '' ) ){
			if( ($key == 'size') && in_array( $tag, $tagArr )  ){
				$classes[] = $tag .'-'. $value;
			}else{
				$classes[] = $value;
			}

		}

		if( in_array($key, $inline_elements) && ( $value != '' ) ){
			$property = str_replace("_","-",$key);
			if( $key == 'font_family'  ){
				$value = $font_container->_vc_get_font_family_name($value);
				$css[] = ($value != '')? "{$property}: '{$value}';" : "";
			}else{
				$css[] = "{$property}: {$value};";
			}
			
		}

	}



	if( $return == 'classes' ){
		return array_filter($classes);
	}else{
		return array_filter($css);
	}
}

function perch_modules_vc_typography_google_fonts($css, $param_name, $shortcode_atts){	

	$field_id = $param_name. '_custom_font';
	if( isset($shortcode_atts[$field_id]) && ('' != $param_name) && ( 'yes' == $shortcode_atts[$field_id] ) ){	
		$key = $param_name. '_google_font';	
		$value = $shortcode_atts[$key];
		$text_font_data = PerchVcMap::getFontsData( $value );
		$font_data = PerchVcMap::googleFontsStyles( $text_font_data, true );	
		$css = array_merge($css, $font_data);



		PerchVcMap::enqueueGoogleFonts( $text_font_data, uniqid($key.'_') );
		
	}

	return $css;
}
add_filter( 'perch_modules/vc_typography_inline_css', 'perch_modules_vc_typography_google_fonts', 10, 3 );
add_filter( 'perch_vc_typography_inline_css', 'perch_modules_vc_typography_google_fonts', 10, 3 );