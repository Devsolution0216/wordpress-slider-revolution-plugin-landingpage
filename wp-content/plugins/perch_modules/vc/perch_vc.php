<?php 
// Element Class 
class PerchVcMap{
     
  
    function __construct() {
        add_action( 'wp_ajax_perch_vc_admin_view', array( $this, 'vc_admin_view' ) );
        add_action( 'init', array( $this, 'vc_map_register_scripts' ) );

        add_filter( 'perch/vc_map_input_field_filter', array( $this, 'vc_map_input_field_filter' ), 5 );


        add_filter( 'perch_modules/wrapper_attributes', array( $this, 'wrapperAttributes' ), 10, 2 );
        add_filter( 'perch_modules/vc_typography_classes', array( $this, 'periodic_getCSSAnimation' ), 10, 3 );
        add_filter( 'perch_modules/vc_typography_wrapper_attributes', array( $this, 'periodic_wrapperAttributes' ), 10, 3 );

        
    }

    function vc_map_register_scripts() {
        wp_register_script( 'perch-scripts', get_template_directory_uri() . '/admin/assets/js/perch-scripts.js', array('jquery', 'owl-carousel', 'slick'), '1.0.0', 'all' );
      
    }

    public static function carousel_item_map(){
        $array = array('perch_feature_box_slide','perch_testimonial_slide', 'perch_title_slide', 'perch_image_slide', 'vc_row', 'perch_pricing_table_slide', 'perch_brand_logo_slide', 'perch_press_logo_slide');        

        return implode(', ', $array);
    }

    public static function get_vc_element_atts_array($map_arr, $map_content = null){ 
         
        $newarray = array();
        foreach ($map_arr as $key => $value) {
            $param_name = isset($value['param_name'])? $value['param_name'] : '';
            $std = '';
            if(isset($value['value']) ){
                if( is_array($value['value']) && ( $value['type'] == 'dropdown' ) ) {
                    $array = $value['value'];  $std = array_shift($array);
                }else {
                    $std = $value['value'];
                }
            }
            $std = isset($value['std'])? $value['std'] : $std;

            if( $param_name != '' ){
                $newarray[$param_name] = $std;
            }
        } 

        $newarray['content'] = $map_content;       
       
        if( !empty($newarray) ) $map_arr = $newarray;
       
        
        return $map_arr;

    }

    public static function typography_fields_settings_options(){
        $array = array(
                'tag' => 'p', 
                'size', 
                'text_color',              
                'text_underline',                
                'text_align',
                'text_bg',
                'extra_class',
                // inline css                
                'font_size',
                'font_style',
                'font_size',
                'text_transform',
                'text_decoration',            
                'font_variant',
                'font_weight',
                'letter_spacing',           
                'line_height',
            );
        return $array;
    }

    public static function highlight_fields_settings_options(){
        $array = array(               
                'text_underline',
                'text_color',
                'text_bg',
                'extra_class',
                // inline css            
                'font_size',
                'font_style',
                'font_size',
                'text_transform',
                'text_decoration',            
                'font_variant',
                'font_weight',
                'letter_spacing',           
                'line_height',
            );
        return $array;
    }

    public static function input_field_settings_options(){
        $array = array(
            'input_field' => true,
            'textarea' => false,  
            'google_font_settings' => true,          
            'typo_settings' => true,
            'highlight_settings' => true,
            'typo_std' => '',
            'highlight_std' => '',
            'typo_fields' => self::typography_fields_settings_options(),
            'highlight_fields' => self::highlight_fields_settings_options()
        );
        return $array;
    }

    public static function vc_map_input_field_filter($args){
        if(isset($args['perch_settings']) && is_array($args['perch_settings'])){            
            $textarea = isset($settings['textarea'])? $settings['textarea'] : false;
            $settings = $args['perch_settings'];
            $field_id = $args['param_name'];
            $heading = $args['heading'];
            $settings['input_field'] = false;

            $modified_args = array(
                shortcode_atts(self::_vc_param_input_field($field_id, $heading, $textarea, $settings ), $args)
            );
                      
            $new_params = self::_vc_map_input_field_group($field_id, $heading, $textarea, $settings );
            $args = array_merge($modified_args, $new_params);
        }else{
            $args = array($args);
        }
        return $args;
    }

    // input/textare filed element mapping
    public static function _vc_map_input_field_group($field_id, $heading='', $textarea=false, $args=array()){
       
        $default = self::input_field_settings_options();
        $args = shortcode_atts( $default, $args );        
        extract($args);
       
       // check for filter 
       if($input_field){
            $array = array( PerchVcMap::_vc_param_input_field( $field_id, $heading, $textarea, $args ) );
       }else{
            $array = array();
       }
        

        

        if($highlight_settings){
            $new_array = self::_vc_map_highlight_settings_group($field_id, $heading, $textarea, $args);
            $array = array_merge($array, $new_array);
        }

        if($typo_settings){
            $array[] = self::_vc_param_typography($field_id, $heading, $textarea, $args);            
        }

        if($google_font_settings){
            $new_array = self::_vc_map_google_fonts_settings_group($field_id, $heading, $textarea, $args);
            $array = array_merge($array, $new_array);
        }

        $array = array_filter($array);

        $array = apply_filters( 'perch/vc_map_input_field_group', $array );

        return $array;
    }



    public static function _vc_param_input_field($field_id, $heading='', $textarea = false, $args=array()){
        $_textarea = $textarea;
        extract(self::input_field_settings_options());           
        extract($args);

        $column = ($highlight_settings)? 'vc_col-sm-9' : 'vc_col-sm-12';
        $array = array(
                'heading' => $heading,
                'param_name' => $field_id,
                'value' => __( 'Default text', 'perch' ),
                'description' => __( 'Leave blank to avoid this field', 'perch' ),
                'admin_label' => true,
                'edit_field_class' => $column,
                'perch_settings' => $args                
            );
        $array['type'] = ($_textarea == true)? 'textarea' : 'textfield';
        /*if( in_array($field_id, array('subtitle', 'leadtext')) ){
             $array['type'] = 'textarea';
        }*/
        return $array; 
    }

    public static function _vc_map_highlight_settings_group($field_id, $heading, $textarea, $args){
        $array = array(
             PerchVcMap::_vc_param_enable_highlight_text($field_id, $heading, $textarea, $args), 
            PerchVcMap::_vc_highlight_text_typography($field_id, $heading, $textarea, $args), 
        );
        return $array;
    }

    public static function _vc_map_google_fonts_settings_group($field_id, $heading, $textarea, $args){
        $array = array(
            PerchVcMap::_vc_param_enable_google_fonts($field_id, $heading, $textarea, $args),
            PerchVcMap::_vc_param_custom_google_fonts($field_id, $heading, $textarea, $args),
        );
        return $array;
    }
    

    public static function _vc_param_typography($field_id, $heading='', $textarea=false, $args=array()){ 
        extract(self::input_field_settings_options());
        extract($args);  

       
        $group = __( 'Typography settings', 'perch' );
        $param_name = $field_id. '_font_container';
        $title = sprintf(__( '%s Typography settings', 'perch' ), $heading);

        $std = $typo_std;    
        $fields = $typo_fields;
     
        $array = array(
            'type' => 'perch_vc_typography',
            'title' => esc_attr($title),
            'param_name' => esc_attr($param_name),
            'column' => 4,
            'std' => $std,
            'group' => $group, 
            'settings' => array(
                'fields' => $fields,
            ),
        );         
        return $array;  
          
    }

    public static function _vc_param_enable_highlight_text($field_id, $heading='', $textarea=false, $args=array()){  
        $default = self::input_field_settings_options();
        $args = shortcode_atts( $default, $args );     
        extract($args);

        $group = __( 'Highlight text', 'perch' );
        $param_name = $field_id. '_highlight_text_enable';
        $title = sprintf(__( 'Enable highlight text for %s', 'perch' ), $heading);
        $desc = __( 'Checked to enable. Use {} to make text highlight.', 'perch' );      
        $array = array(
                'type' => 'checkbox',
                'heading' => __( 'Highlight text?', 'perch' ),
                'param_name' => esc_attr($param_name),                
                'value' => array( __('Enable', 'perch') => 'yes' ), 
                'description' => esc_attr($desc),
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-3'
            );         
        return $array;    
    }

    public static function _vc_highlight_text_typography($field_id, $heading='', $textarea=false, $args=array()){  
        $default = self::input_field_settings_options();
        $args = shortcode_atts( $default, $args );     
        extract($args);

        $group = __( 'Highlight text', 'perch' );
        $param_name = $field_id. '_highlight_text';
        $title = sprintf(__( '%s Highlight text settings', 'perch' ), $heading);
        $dependency = array( 'element' => $field_id. '_highlight_text_enable', 'value' => 'yes' );
        $std = 'inner_tag:span|text_underline:underline-yellow';    
        $fields = self::highlight_fields_settings_options();
       
        $array = array(
            'type' => 'perch_vc_typography',
            'title' => esc_attr($title),
            'param_name' => esc_attr($param_name),
            'column' => 4,
            'std' => $std,
            'group' => $group,
            'dependency' =>  $dependency,
            'settings' => array(
                'fields' => $fields,
            ),
        );         
        return $array;    
    }

    public static function _vc_param_enable_google_fonts($field_id, $heading='', $textarea=false, $args=array()){  
        $default = self::input_field_settings_options();
        $args = shortcode_atts( $default, $args );     
        extract($args);

        $group = __( 'Typography settings', 'perch' );
        $param_name = $field_id. '_custom_font';
        $title = sprintf(__( 'Custom font family for %s', 'perch' ), $heading);
        $desc = __( 'Checked to enable custom fonts', 'perch' );      
        $array = array(
                'type' => 'checkbox',
                'param_name' => esc_attr($param_name),                
                'value' => array( esc_attr($title) => 'yes' ), 
                'description' => esc_attr($desc),
                'admin_label' => true,
                'group' => $group, 
            );         
        return $array;    
    }

    public static function _vc_param_custom_google_fonts($field_id, $heading='', $textarea=false, $args=array()){  
        $default = self::input_field_settings_options();
        $args = shortcode_atts( $default, $args );     
        extract($args);

        $group = __( 'Typography settings', 'perch' );
        $param_name = $field_id. '_google_font';
        $font_dependency = array( 'element' => $field_id. '_custom_font', 'value' => 'yes' );     
        $array = array(
                'type' => 'google_fonts',
                'param_name' => esc_attr($param_name),
                'settings' => array(
                    'fields' => array(
                        'font_family_description' => __( 'Select Font Family.', 'perch' ),
                    ),
                ), 
                'group' => $group, 
                'dependency' => $font_dependency, 

            );
               
        return $array;    
    }

    public  static function div_size_class_options(){
        $array = array(
                'None' => '',
                'Section name' => 'section-id',
                'Os version' => 'os-version',
                'Pricing validity' => 'validity',
                'Price' => 'price',
                'Text uppercase' => 'text-uppercase',
                'Text lowercase' => 'text-lowercase',
                'Text capitalize' => 'text-capitalize',
                'Text lead' => 'text-lead',
                'Truncate the text with an ellipsis' => 'text-truncate',
                'Text Muted' => 'text-muted',
                'Text italic' => 'font-italic',
                'Text success' => 'text-success',                               
                'Text danger' => 'text-danger',                               
                'Text warning' => 'text-warning',                               
                'Text info' => 'text-info',                               
                'Text opacity 25%' => 'opacity-25',                               
                'Text opacity 50%' => 'opacity-50',                               
                'Text opacity 75%' => 'opacity-75',                               
                'Text monospace' => 'text-monospace',                               
                'Blockquote' => 'blockquote',
                'Blockquote footer' => 'blockquote-footer',
                'Figure caption' => 'figure-caption',
                'Attribute' => 'attribute',
                'Screen readers text' => 'sr-only',
                 'Clearfix' => 'clearfix',
               
            );       

        return $array; 
    } 

    public static function element_align_args(){
         $array = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Alignment', 'perch' ),
                'param_name' => 'align',
                'std' => '',
                'value' => array(
                    'Inherit' => '',
                    'Left' => 'text-left',
                    'Center' => 'text-center',
                    'Right' => 'text-right',
                    'Justify' => 'text-justify',                    
                ),
            ),
         );       

        return $array;   
    }    
    

    public static function element_common_args(){
         $array = array(
            array(
                'type' => 'animation_style',
                'heading' => __( 'Animation Style', 'perch' ),
                'param_name' => 'css_animation',
                'description' => __( 'Choose your animation style', 'perch' ),
                'std' => 'fadeInUp',
                'admin_label' => false,                
            ),            
            array(
                'type' => 'textfield',
                'heading' => __( 'Animation Delay', 'perch' ),
                'param_name' => 'animation_delay',
                'value' => '300', 
                'description' => __( 'Delay before the animation starts, in ms', 'perch' ),
                'edit_field_class' => 'vc_col-sm-4',
                'dependency' => array(
                    'element' => 'css_animation',
                    'value_not_equal_to' => 'none'
                )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Animation duration', 'perch' ),
                'param_name' => 'animation_duration',
                'value' => '1200', 
                'description' => __( 'Change the animation duration, in ms', 'perch' ),
                'edit_field_class' => 'vc_col-sm-4',
                'dependency' => array(
                    'element' => 'css_animation',
                    'value_not_equal_to' => 'none'
                )
            ), 
            array(
                'type' => 'checkbox',
                'heading' => __( 'Perodic animation', 'perch' ),
                'param_name' => 'periodic_animation',               
                'value' => array( __( 'Checked to make item elements animation Perodic', 'perch' ) => 'yes' ),
                'edit_field_class' => 'vc_col-sm-4',
                'std' => '',  
                'dependency' => array(
                    'element' => 'css_animation',
                    'value_not_equal_to' => 'none'
                )             
                
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Perodic animation interval', 'perch' ),
                'param_name' => 'animation_interval',
                'value' => '100',  
                'description' => 'Change the animation duration interval per element. Number only, in ms.',                
                'dependency' => array(
                    'element' => 'periodic_animation',
                    'value' => 'yes'
                )
            ),                      
            array(
                'type' => 'el_id',
                'heading' => __( 'Element ID', 'perch' ),
                'param_name' => 'el_id',
                'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'perch' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
                'edit_field_class' => 'vc_col-sm-6',                
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Extra class name', 'perch' ),
                'param_name' => 'el_class',
                'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'perch' ),
                'edit_field_class' => 'vc_col-sm-6',
            ),            
            perch_vc_spacing_options_param('margin', 'top'),
            perch_vc_spacing_options_param('margin', 'bottom'), 
            perch_vc_spacing_options_param('padding', 'left'),
            perch_vc_spacing_options_param('padding', 'right'),
            array(
                'type' => 'css_editor',
                'heading' => __( 'CSS box', 'perch' ),
                'param_name' => 'css',
                'group' => __( 'Design Options', 'perch' ),
                
            ),            
         );
        

        return $array;   
    } 


    //********************************//
    // GOOGLE FONTS PRIVATE FUNCTIONS // 
    //********************************//
     
    // Build the string of values in an Array
    public static function getFontsData( $fontsString ) {   
     
        // Font data Extraction
        $googleFontsParam = new Vc_Google_Fonts();      
        $fieldSettings = array();
        $fontsData = strlen( $fontsString ) > 0 ? $googleFontsParam->_vc_google_fonts_parse_attributes( $fieldSettings, $fontsString ) : '';
        return $fontsData;
         
    }
     
    // Build the inline style starting from the data
    public static function googleFontsStyles( $fontsData, $array = false ) {
         
        // Inline styles
        $fontFamily = explode( ':', $fontsData['values']['font_family'] );
        $styles[] = 'font-family:' . $fontFamily[0];
        $fontStyles = explode( ':', $fontsData['values']['font_style'] );
        $styles[] = 'font-weight:' . $fontStyles[1];
        $styles[] = 'font-style:' . $fontStyles[2];

        if( $array ){
            $_arr = array();
            $_fontFamily = explode( ':', $fontsData['values']['font_family'] );
            $_arr[] = 'font-family:' . $fontFamily[0];
            return $_arr;

        }else{
            $inline_style = '';     
            foreach( $styles as $attribute ){           
                $inline_style .= $attribute.'; ';       
            }   
             
            return $inline_style;
        }
         
        
         
    }
     
    // Enqueue right google font from Googleapis
    public static function enqueueGoogleFonts( $fontsData, $key="" ) {
         
        // Get extra subsets for settings (latin/cyrillic/etc)
        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        } else {
            $subsets = '';
        }

        if( $key == '' ){
            $key = 'vc_google_fonts_' . vc_build_safe_css_class( $fontsData['values']['font_family'] );
        }

        // We also need to enqueue font from googleapis
        if ( isset( $fontsData['values']['font_family'] ) ) {
            wp_enqueue_style( 
              $key  , 
                '//fonts.googleapis.com/css?family=' . $fontsData['values']['font_family'] . $subsets
            );
        }
         
    } 

    public static function admin_vc_view($atts, $admin_params){
        if( isset($atts['icon_type']) && ($atts['icon_type'] != '') ){
            echo PerchVcMap::icon_html($atts);
        }
        if( isset($atts['custom_src']) && ($atts['custom_src'] != '') ){
            echo '<img src="'.esc_url($atts['custom_src']).'" alt="vc-image" width="100">';
        }
        if( isset($atts['title']) && ($atts['title'] != '') ){
            $atts['title_tag'] = 'h5';
            echo perch_get_parse_text_html($atts['title'], $atts, 'title');
        }
        if( isset($atts['subtitle']) && ($atts['subtitle'] != '') ){
            $atts['subtitle_tag'] = 'p';
            echo perch_get_parse_text_html($atts['subtitle'], $atts, 'subtitle');
        }

        //params
        $paramsArr = array();
        if( isset($atts['params']) && ($atts['params'] != '') ){
            $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($atts['params']) : array();
            if( !empty($paramsArr) ):
                echo '<ul class="list-inline">';
                foreach ($paramsArr as $key => $value) {
                   extract($value);
                   echo '<li>';
                   if( $title && ($title != '') ){
                        echo '<strong>Title:</strong> '.esc_attr($title);
                   }
                   if( $subtitle && ($subtitle != '') ){
                        echo '<strong>Sub-Title:</strong> '.esc_attr($subtitle);
                   }
                   if( $image && ($image != '') ){
                        echo '<strong>Image:</strong> <img src="'.esc_url($image).'" alt="vc-image" width="50">';
                   }
                  
                   echo '</li>';
                }
                echo '</ul>';
            endif;
        }

        //params2
        $paramsArr = array();
        if( isset($atts['params2']) && ($atts['params2'] != '') ){
            $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($atts['params2']) : array();
            if( !empty($paramsArr) ):
                echo '<ul class="list-inline">';
                foreach ($paramsArr as $key => $value) {
                   extract($value);
                   echo '<li>';
                   if( $title && ($title != '') ){
                        echo '<strong>Title:</strong> '.esc_attr($title);
                   }
                   if( $subtitle && ($subtitle != '') ){
                        echo '<strong>Sub-Title:</strong> '.esc_attr($subtitle);
                   }
                   if( $image && ($image != '') ){
                        echo '<strong>Image:</strong> <img src="'.esc_url($image).'" alt="vc-image" width="50">';
                   }
                   echo '</li>';
                }
                echo '</ul>';
            endif;
        }
       
        //list_group
        $paramsArr = array();
        if( isset($atts['list_group']) && ($atts['list_group'] != '') ){
            $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($atts['list_group']) : array();
            if( !empty($paramsArr) ):
                echo '<ul class="list-inline">';
                foreach ($paramsArr as $key => $value) {
                   extract($value);
                   echo '<li>';
                   if( $title && ($title != '') ){
                        echo '<strong>Title:</strong> '.esc_attr($title);
                   }
                   if( $subtitle && ($subtitle != '') ){
                        echo '<strong>Sub-Title:</strong> '.esc_attr($subtitle);
                   }
                   if( $image && ($image != '') ){
                        echo '<strong>Image:</strong> <img src="'.esc_url($image).'" alt="vc-image" width="50">';
                   }
                   echo '</li>';
                }
                echo '</ul>';
            endif;
        }

        $haystack = array('title', 'subtitle', 'custom_src', 'image', 'params', 'params2', 'list_group' );

        $btn_group = PerchVcMap::button_args();
        $btn_group = array_merge($btn_group, PerchVcMap::icon_args());
        $btn_group = array_merge($btn_group, PerchVcMap::heighlights_text_args());
        foreach ($btn_group as $param) {
            $haystack[] = $param['param_name'];
        }

        $params = '';
        foreach ($atts as $key => $value) {
            $orginal_value = $value;
            if( !in_array($key, $haystack) && ( $value != '' ) ){
                $heading = (($admin_params[$key]['heading'] != '') && ($orginal_value != 'default') && ($orginal_value != ''))? '<strong>'.$admin_params[$key]['heading'].'</strong>: ' : '';
                if( $admin_params[$key]['type'] == 'dropdown' ){                    
                    $_value = $admin_params[$key]['value'];
                    $_value = array_flip($_value);
                    $value = $_value[$value];
                    $params .= ($heading != '')? $heading. $value . ', ' : '';
                }else{
                    $params .= ($heading != '')?  $heading. $value . ', ' : '';
                }
                
            }
        }

        if( $params != '' ){
            echo '<p class="p values">'.$params.'</p>';
        }
      

    } 

    public static function btn_style_options($vcoptions = false){
        $arr = array(                
                'btn-tra-white tra-hover' => 'Transparent white'                
            );       

        
        $arr = apply_filters( 'perch_modules/get_allowed_btn_class', $arr );
       

         if($vcoptions){
            return array_flip($arr);
         }else{
            return $arr;
         }
        
    }

    public static function vc_icon_set( $type, $name = 'icon_fontawesome', $value = '', $dependency = '' ){
        return apply_filters( 'perch_modules/vc_icon_set', array(), $type, $name, $value, $dependency );
    }

    public static function vc_color_options( $coloronly = false, $prefix = '', $postfix = '' ){
        return apply_filters( 'perch_modules/get_allowed_color_class', array('Inherit' => '') );
    }

    public static function default_dark_color_classes( $args ){
        return apply_filters( 'perch_modules/default_dark_color_classes', $args );
    }

    

    public static function vc_spacing_options( $type='padding', $pos = 'bottom' ){
        do_action( 'perch_modules/vc_spacing_options', $type, $pos );
    }

     public static function get_parse_text_html($text = '', $args = array(), $type = 'title'){
        do_action( 'perch_modules/get_parse_text_html', $text, $args, $type );
     }

    public static function vc_element_icon_size(){
        return array(
                        'Default' => 'icon',
                        'Extra small' => 'xs',                    
                        'Small' => 'sm',
                        'Medium' => 'md',
                        'Large' => 'lg',
                        '2X size' => '2x',
                        '3X size' => '3x',
                        '5X size' => '5x',
                        '7X size' => '7x',
                        '10X size' => '10x',
                    );
    }

    public static function element_group_typography( $field_id, $_heading = 'Title', $std = '', $dependency = array(), $args = array() ){  

        $group = __( 'Typography settings', 'perch' );
        $param_name = $field_id;
        $title = sprintf(__( '%s', 'perch' ), $_heading);
        

        $fields = array(
                'tag' => 'p', 
                'size', 
                'text_color',              
                'text_underline',                
                'text_align',
                'text_bg',
                'extra_class',
                // inline css
                'font_family',
                'font_size',
                'font_style',
                'font_size',
                'text_transform',
                'text_decoration',            
                'font_variant',
                'font_weight',
                'letter_spacing',           
                'line_height',
            );
        
        $array = array(
            'type' => 'perch_vc_typography',
            'title' => esc_attr($title),
            'param_name' => esc_attr($param_name),
            'column' => apply_filters('perch_vc_typography_fields_column', 4, $field_id),
            'std' => $std,
            'group' => $group, 
            'settings' => array(
                'fields' => $fields,
            ),
        ); 

        if( !empty($dependency) ) $array['dependency'] = $dependency;

        return $array;    
    }

    public function enable_logo_image(){
        $array = array(
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable image icon?', 'perch' ),
                'param_name' => 'enable_logo',
                'value' => array( __( 'Checked to enable.', 'perch' ) => 'yes' ),  
                'admin_label' => true,
            ),            
            array(
                'type' => 'image_upload',
                'heading' => __( 'Icon image', 'perch' ),
                'param_name' => 'custom_src',
                'value' => get_template_directory_uri(). '/images/hero-logo.png',
                'description' => __( 'Select external link.', 'perch' ),
                'dependency' => array( 'element' => 'enable_logo', 'value' => 'yes', ),
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-8', 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image size', 'perch' ),
                'param_name' => 'external_img_size',
                'value' => '125x125',
                'description' => __( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'perch' ),
                'dependency' => array( 'element' => 'enable_logo', 'value' => 'yes', ),
                'edit_field_class' => 'vc_col-sm-4', 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Logo image prefix text', 'perch' ),
                'param_name' => 'logo_prefix',
                'value' => '',
                'description' => __( 'Display before logo image. Leave blank to avoid field', 'perch' ),
                'dependency' => array( 'element' => 'enable_logo', 'value' => 'yes', ),
                'edit_field_class' => 'vc_col-sm-6', 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Logo image postfix text', 'perch' ),
                'param_name' => 'logo_postfix',
                'value' => '',
                'description' => __( 'Display after logo image. Leave blank to avoid field', 'perch' ),
                'dependency' => array( 'element' => 'enable_logo', 'value' => 'yes', ),
                'edit_field_class' => 'vc_col-sm-6', 
            ),
                                  
            
        );
        return $array;
    }

    public function logo_image_html($atts){
        $output = $enable_logo = $image_html = '';
        extract($atts);

        if( $enable_logo == 'yes' ){ 
            $classes = array('hero-app-logo');
            $classes = self::periodic_getCSSAnimation($classes, 'hero-app-logo', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'hero-app-logo', $atts );
            
            if( function_exists('vc_extract_dimensions') ):
	            $dimensions = vc_extract_dimensions( $external_img_size );
	            $hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';
	            $default_src = vc_asset_url( 'vc/no_image.png' );
	            $custom_src = $custom_src ? esc_attr( $custom_src ) : $default_src;        	
            	$alt = isset($atts['external_img_alt'])? esc_attr($atts['external_img_alt']) : 'External image link';
            	$image_html = '<img class="img-fluid" ' . $hwstring . ' src="' . $custom_src . '" alt="'.esc_attr($alt).'" />';
            endif;        
            
            $atts['css_animation'] = '';
            $output = '<div'.implode(' ', $wrapper_attributes).'>
                    '.$logo_prefix.$image_html.$logo_postfix.'                  
                </div>';
        } 
        return $output; 
    }

    public function enable_textfield(){
        $fields = array(
                'tag' => 'h5', 
                'size', 
                'text_color',              
                'extra_class',                
            );
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable New textfield field?', 'perch' ),
                    'param_name' => 'enable_textfield',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',                  
                    'param_name' => 'textfield_area', 
                    'dependency' => array( 'element' => 'enable_textfield', 'value' => 'yes', ), 
                    'edit_field_class' => 'vc_col-sm-7',                 
                ), 
                array(
                    'type' => 'perch_vc_typography',
                    'title' => '',
                    'param_name' => 'textfield_area_font_container',
                    'column' => '2',
                    'std' => 'tag:h5|size:md', 
                    'settings' => array(
                        'fields' => $fields,
                    ),
                    'edit_field_class' => 'vc_col-sm-5',
                    'dependency' => array( 'element' => 'enable_textfield', 'value' => 'yes', ), 
                ) 
            );  
        return $array;
    }

    public function textfield_html($atts ){
        $output = $enable_textfield = '';
        extract($atts);

        if( $enable_textfield == 'yes' ){ 
            $classes = array('');
            $classes = self::periodic_getCSSAnimation($classes, 'textfield_area', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'textfield_area', $atts );
                    
            
            $atts['css_animation'] = '';
            $output = '<div'.implode(' ', $wrapper_attributes).'>
                    '.perch_modules_get_vc_param_html( 'textfield_area', $atts ).'
                </div>';
        } 
        return $output; 
    }

    public function enable_content(){
        $fields = array(
                'tag' => 'p', 
                'size', 
                'text_color',              
                'extra_class',                
            );
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable New textarea field?', 'perch' ),
                    'param_name' => 'enable_textarea',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',                  
                    'param_name' => 'textarea_field',                            
                    'dependency' => array( 'element' => 'enable_textarea', 'value' => 'yes', ), 
                    'edit_field_class' => 'vc_col-sm-7',                 
                ), 
                array(
                    'type' => 'perch_vc_typography',
                    'title' => '',
                    'param_name' => 'textarea_field_font_container',
                    'column' => '2',
                    'std' => 'tag:p', 
                    'settings' => array(
                        'fields' => $fields,
                    ),
                    'edit_field_class' => 'vc_col-sm-5',
                    'dependency' => array( 'element' => 'enable_textarea', 'value' => 'yes', ), 
                ) 
            );  
        return $array;
    }

    public function content_html($atts, $content='' ){
        $output = $enable_textarea = '';
        extract($atts);

        if( $enable_textarea == 'yes' ){ 
            $classes = array('');
            $classes = self::periodic_getCSSAnimation($classes, 'textarea_field', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'textarea_field', $atts );
                    
            
            $atts['css_animation'] = '';
            $output = '<div'.implode(' ', $wrapper_attributes).'>
                    '.perch_modules_get_vc_param_html( 'textarea_field', $atts ).'
                </div>';
        } 
        return $output; 
    }

    public function enable_content_list(){
        $list_type_std = apply_filters( 'perch_modules/content_list/list_type/std','content-list' );
        $list_type_options = array('Content list' => 'content-list');
        $list_type_options = apply_filters( 'perch_modules/content_list/list_type/value', $list_type_options );
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable content list?', 'perch' ),
                    'param_name' => 'enable_content_list',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                    'edit_field_class' => 'vc_col-sm-9',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'List type', 'perch' ),
                    'param_name' => 'list_type',
                    'std' => $list_type_std,
                    'value' => $list_type_options,  
                    'admin_label' => true,
                    'dependency' => array( 'element' => 'enable_content_list', 'value' => 'yes', ),  
                    'edit_field_class' => 'vc_col-sm-3',
                ),
                array(
                    'type' => 'exploded_textarea',                  
                    'param_name' => 'content_list',     
                    'value' => 'Vitae auctor integer congue magna at pretium purus,Vitae auctor integer congue magna at pretium purus pretium ligula rutrum luctus risus enim ipsum blandit,Magna at pretium purus pretium ligula rutrum,and many more...',  
                    'edit_field_class' => 'vc_col-sm-12',                    
                    'dependency' => array( 'element' => 'enable_content_list', 'value' => 'yes', ),                 
                ),  
            );  
        return $array;
    }

    public function content_list_html($atts){
        $output = $enable_content_list = $list_type = '';        
        extract($atts);

        $list_type = ('' == $list_type )? 'content-list' : $list_type;
        $list_type = apply_filters('perch_modules/simple_content_list_type', $list_type);
        if( $enable_content_list == 'yes' ){
            $array = explode(',', $content_list);
            
            
            if( !empty($array) ):
                $interval = intval(get_query_var('perch_periodic_interval'));
                $new_interval = $interval + 1;
                set_query_var( 'perch_periodic_interval',  $new_interval );
                
                $classes = array();
                $classes = self::periodic_getCSSAnimation($classes, 'content-list', $atts);
                $content_list_class = apply_filters('perch_modules/content_list_class', 'content-list');
                $output = '<ul class="'.esc_attr($list_type).'">';
                foreach ($array as $key => $value) {
                    $wrapper_attributes = array();
                    $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
                    $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'content-list', $atts );
                    $value = apply_filters('perch_modules/simple_content_list/output', $value, $atts );
                    $output .= '<li'.implode(' ', $wrapper_attributes).'>'.$value.'</li>';
                }               
                $output .= '</ul>';
            endif;
        } 
        return $output; 
    }

    public  function enable_hero_button_group(){
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable Buttons?', 'perch' ),
                    'param_name' => 'enable_buttons',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                ), 
                array(                
                  'heading' => __( 'Button Group', 'perch' ),
                  'param_name' => 'params',   
                  'type' => 'param_group',
                  'save_always' => true, 
                  'dependency' => array( 'element' => 'enable_buttons', 'value' => 'yes', ),            
                  'value' => urlencode( json_encode( array(
                      array(
                          'button_type' => 'img_btn', 
                          'img_btn' => get_template_directory_uri(). '/images/appstore.png',
                          'img_btn_size' => '160',                          
                          'button_text' => 'Appstore',
                          'button_url' => '#',
                          'button_target' => '_blank',
                      ),
                      array(
                        'button_type' => 'img_btn', 
                        'img_btn' => get_template_directory_uri(). '/images/googleplay.png',
                        'img_btn_size' => '160',                          
                        'button_text' => 'Googleplay',
                        'button_url' => '#',
                        'button_target' => '_blank',
                      ),
                  ) ) ),
                  'params' => PerchVcMapButtons::button_groups_param()
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Buttons description', 'perch' ),
                    'param_name' => 'buttons_desc',
                    'value' => '* Requires iOS 7.0 or higher',                
                    'dependency' => array( 'element' => 'enable_buttons', 'value' => 'yes', ),                  
                ),  
            );  
        return $array;
    }    

   public function buttons_html($atts, $classes = array('stores-badge') ){
        $output = $enable_buttons = '';
        extract($atts);
        $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($params) : array();

        if( ($enable_buttons == 'yes') && !empty($paramsArr) ){
            
            $classes = self::periodic_getCSSAnimation($classes, 'stores_badge', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'stores_badge', $atts );
            
            $buttons = PerchVcMapButtons::button_groups_html($paramsArr, 'store', $atts);        
            $buttons_desc = ($buttons_desc != '')? '<span class="os-version">'.esc_attr($buttons_desc).'</span>' : '';
            $buttons_desc = apply_filters('perch_modules/buttons/buttons_desc', $buttons_desc, $atts );

            $atts['css_animation'] = '';
            $output = '<div'.implode(' ', $wrapper_attributes).'>
                '.$buttons.'
                '. $buttons_desc .'
            </div>';
        } 
        return $output; 
    }

    public function enable_counter_group(){
        $dependency = array( 'element' => 'enable_counter_group', 'value' => 'yes', );
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable counter group?', 'perch' ),
                    'param_name' => 'enable_counter_group',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                ),
                self::element_group_typography('counter_typo', 'Counter typography', 'tag:p|extra_class:statistic-number txt-700', $dependency ),
                array(                
                  'heading' => __( 'Counter Group', 'perch' ),
                  'param_name' => 'counter_group',   
                  'type' => 'param_group',
                  'save_always' => true, 
                  'dependency' => $dependency,            
                  'value' => urlencode( json_encode( array(
                      array(
                          'prefix' => '3,', 
                          'count' => '438',
                          'counter_color' => 'coral-color',
                          'title' => 'Downloads',
                      ),
                      array(
                          'prefix' => '1,', 
                          'count' => '263',
                          'counter_color' => 'coral-color',
                          'title' => 'Tickets Closed',
                      ),
                  ) ) ),
                  'params' => array(                            
                            array(
                                 'type' => 'textfield',
                                'value' => '3,',
                                'heading' => 'Count Prefix',
                                'param_name' => 'prefix',                
                                'edit_field_class' => 'vc_col-sm-4',
                                'admin_label' => true 
                            ),
                            array(
                                 'type' => 'textfield',
                                'value' => '438',
                                'heading' => 'Count',
                                'param_name' => 'count', 
                                'description' => 'Number only',               
                                'edit_field_class' => 'vc_col-sm-4',
                                'admin_label' => true 
                            ),
                            array(
                                 'type' => 'dropdown',
                                'heading' => __( 'Counter color', 'perch' ),
                                'param_name' => 'counter_color',
                                'value' => PerchVcMap::vc_color_options(true, '', ''),
                                'std' => '',
                                'edit_field_class' => 'vc_col-sm-4', 
                            ),
                            array(
                                 'type' => 'textfield',
                                'value' => '',
                                'heading' => 'Title',
                                'param_name' => 'title',
                                'admin_label' => true 
                            ),
                        )
                    
                ),                 
            );  
        return $array;
    }

    public function counter_group_html($atts){
        $output = $enable_counter_group = $title = '';
        extract($atts);
        $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($counter_group) : array();

        if( $enable_counter_group == 'yes'  && !empty($paramsArr) ){ 
            $classes = array('small-statistic', 'mt-40');
            $classes = self::periodic_getCSSAnimation($classes, 'small-statistic', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'small-statistic', $atts );
                    
            
            $atts['css_animation'] = '';
            $output = '<div '. implode( ' ', $wrapper_attributes ).'><div class="row">';
            foreach ($paramsArr as $key => $value) {
                    extract($value);
                     $counter_html = esc_attr($prefix).'<span class="count-element">'.intval($count).'</span>';
                     $counter_html = perch_generate_input_field_html_by_settings($counter_html, $counter_typo, $atts);
                     $output .= '<div class="col-sm-4 col-md-6 col-lg-5"><div class="statistic-block">'.$counter_html.'<p class="txt-500">'.$title.'</p></div></div>';
            }    
            $output .= '</div></div>'; 
        } 
        return $output;
    }

    public function enable_modal_popup(){
        $array = array(
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable video popup?', 'perch' ),
                'param_name' => 'enable_modal_popup',
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                'admin_label' => true,                
            ), 
            array(
                'type' => 'textfield',
                'heading' => __( 'Video URL', 'perch' ),
                'param_name' => 'modal_popup_url',
                'value' => 'https://www.youtube.com/watch?v=7e90gBu4pas',
                'dependency' => array( 'element' => 'enable_modal_popup', 'value' => 'yes', ),
            ),
            array(
                 'type' => 'textarea',
                'heading' => __( 'Video Title', 'perch' ),
                'param_name' => 'modal_popup_title',
                'value' => 'Watch Our Video {duration: 2:40 min}',
                'edit_field_class' => 'vc_col-sm-6',
                'admin_label' => true,                
                'dependency' => array( 'element' => 'enable_modal_popup', 'value' => 'yes', ),
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Video title color', 'perch' ),
                'param_name' => 'modal_popup_title_color',
                'value' => PerchVcMap::vc_color_options(true, '', ''),
                'std' => '',
                'edit_field_class' => 'vc_col-sm-3', 
                'dependency' => array( 'element' => 'enable_modal_popup', 'value' => 'yes', ),
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Video icon color', 'perch' ),
                'param_name' => 'modal_popup_icon_color',
                'value' => PerchVcMap::vc_color_options(true, '', ''),
                'std' => '',
                'edit_field_class' => 'vc_col-sm-3', 
                'dependency' => array( 'element' => 'enable_modal_popup', 'value' => 'yes', ),
            ),
        ); 

        return $array;

    }

    public function modal_popup_html($atts){
        $output = $enable_modal_popup = $modal_popup_title_color = $modal_popup_icon_color = '';
        extract($atts);

        if( $enable_modal_popup == 'yes' ){ 
            $classes = array('modal-video', 'mt-10', $modal_popup_title_color);
            $classes = self::periodic_getCSSAnimation($classes, 'hero-content', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'hero-content', $atts );
                    
            
            $atts['css_animation'] = '';
            $output = '<div '. implode( ' ', $wrapper_attributes ).'>
                <a class="video-popup2" href="'.esc_url($modal_popup_url).'">
                     <i class="fas fa-play-circle '.$modal_popup_icon_color.'"></i> 
                    '.perch_modules_parse_text($modal_popup_title ).'  
                </a>       
            </div>'; 

            $output = apply_filters( 'perch_modules/modal_popup_html', $output, $atts );
        } 
        return $output;
    }

    public function enable_app_devices(){
        $array = array(
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable App devices?', 'perch' ),
                'param_name' => 'enable_app_devices',
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                'admin_label' => true,                
            ), 
            array(
                'type' => 'textarea',
                'heading' => __( 'App devices description', 'perch' ),
                'param_name' => 'app_devices_title',
                'value' => 'Available on iPhone, iPad and all Android devices from 5.5',
                'dependency' => array( 'element' => 'enable_app_devices', 'value' => 'yes', ),
            ),
        ); 

        return $array;

    }

    public function app_devices_html($atts){
        $output = $enable_app_devices = '';
        extract($atts);

        if( $enable_app_devices == 'yes' ){ 
            $classes = array('app-devices', 'clearfix');
            $classes = self::periodic_getCSSAnimation($classes, 'app-devices', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'app-devices', $atts );
            
            $output = '<div '. implode( ' ', $wrapper_attributes ).'>                
                <i class="fas fa-tablet-alt f-tablet"></i>
                <i class="fas fa-mobile-alt f-phone"></i>
                <div class="app-devices-desc"><p class="p-small">'.perch_modules_parse_text($app_devices_title ).'</p></div>
            </div>'; 
        } 
        return $output;
    }

    public function enable_quote(){
        $array = array(
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Qutoe?', 'perch' ),
                'param_name' => 'enable_testimonial',
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                'admin_label' => true,                
            ), 
            array(
                'type' => 'textarea',
                'heading' => __( 'Qutoe description', 'perch' ),
                'param_name' => 'testimonial_desc',
                'value' => '"Lorem ipsum dolor sit amet, lectus laoreet impedit gestas. Aenean magna ligula eget dolor suscipit egestas viverra dolor iaculis luctus magna suscipit egestas "',
                'dependency' => array( 'element' => 'enable_testimonial', 'value' => 'yes', ),
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( 'Qutoe avatar', 'perch' ),
                'param_name' => 'testimonial_avatar',
                'description' => 'You can use image instead of Icon',
                'value' => '',
                'dependency' => array( 'element' => 'enable_testimonial', 'value' => 'yes', ), 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Author name', 'perch' ),
                'param_name' => 'testimonial_name',
                'value' => 'James Paterson',
                'dependency' => array( 'element' => 'enable_testimonial', 'value' => 'yes', ),
                'edit_field_class' => 'vc_col-sm-6', 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Author title', 'perch' ),
                'param_name' => 'testimonial_title',
                'value' => 'CEO of '.perch_modules_current_theme(),
                'dependency' => array( 'element' => 'enable_testimonial', 'value' => 'yes', ),
                'edit_field_class' => 'vc_col-sm-6', 
            ),
        ); 

        return $array;

    }

    public function quote_html($atts){
        $output = $enable_testimonial = '';
        extract($atts);

        if( $enable_testimonial == 'yes' ){ 
            $classes = array('quote', 'primary-theme', 'mt-20');
            $classes = self::periodic_getCSSAnimation($classes, 'quote', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'quote', $atts );
           
            $testimonial_desc = ($testimonial_desc != '')? '<p>'.force_balance_tags($testimonial_desc).'</p> ': '';
            $testimonial_avatar =  ($testimonial_avatar != '')?  '<div class="quote-avatar">
                                        <img src="'.esc_url($testimonial_avatar).'" alt="'.esc_attr($testimonial_name).'">
                                    </div>' : '';    
            $testimonial_author =   '<div class="quote-author">
                                        <h5 class="h5-xs">'.esc_attr($testimonial_name).'</h5>
                                        <span class="grey-color">'.esc_attr($testimonial_title).'</span>
                                    </div> ';                                
            
            $output = '<div '. implode( ' ', $wrapper_attributes ).'>                
                '.$testimonial_desc.'
                '.$testimonial_avatar.'
                '.$testimonial_author.'               
            </div>'; 
        } 
        return $output;
    }

    public function enable_technologies(){
        $array = array(
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Technologies?', 'perch' ),
                'param_name' => 'enable_technologies',
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                'admin_label' => true,                
            ), 
            array(
                'type' => 'textfield',
                'heading' => __( 'Technologies title', 'perch' ),
                'param_name' => 'technologies_title',
                'value' => 'Technologies We Use:',
                'dependency' => array( 'element' => 'enable_technologies', 'value' => 'yes', ),
            ),
            array(
                'type' => 'param_group',
                'save_always' => true,
                'heading' => __( 'Techs', 'perch' ),
                'param_name' => 'techs_group',
                'value' => urlencode( json_encode( array(
                    array(
                         'title' => 'HTML5',
                        'icon' => 'fa fa-html5',
                        'image' => ''
                    ),
                    array(
                         'title' => 'CSS3',
                        'icon' => 'fa fa-css3',
                        'image' => ''
                    ),
                    array(
                         'title' => 'jsfiddle',
                        'icon' => 'fa fa-jsfiddle',
                        'image' => ''
                    ),
                    array(
                         'title' => 'git',
                        'icon' => 'fa fa-git',
                        'image' => ''
                    ),
                    array(
                         'title' => 'WordPress',
                        'icon' => 'fa fa-wordpress',
                        'image' => ''
                    ),
                    array(
                         'title' => 'mixcloud',
                        'icon' => 'fa fa-mixcloud',
                        'image' => ''
                    ),
                ) ) ),
                'params' => array(
                     array(
                        'type' => 'textfield',
                        'heading' => __( 'Title', 'perch' ),
                        'param_name' => 'title',
                        'description' => '',
                        'value' => '',
                        'admin_label' => true 
                    ),
                    self::vc_icon_set( 'fontawesome','icon','fa fa-free-code-camp'), 
                     array(
                        'type' => 'image_upload',
                        'heading' => __( 'Icon Image', 'perch' ),
                        'param_name' => 'image',
                        'description' => 'You can use image instead of Icon',
                        'value' => '' 
                    ),
                ),
                'dependency' => array( 'element' => 'enable_technologies', 'value' => 'yes', ),
            )
        ); 

        return $array;

    }

    public function technologies_html($atts){
        $output = $enable_technologies = '';
        extract($atts);

        if( $enable_technologies == 'yes' ){ 
            $classes = array('tools-list', ' mt-15');
            $classes = self::periodic_getCSSAnimation($classes, 'technologies', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'app-devices', $atts );
            $technologies_title = ( $technologies_title != '' )? '<h5 class="h5-xs">'.esc_attr($technologies_title).'</h5>' : '';

            $techs_html = '';
            $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($techs_group) : array();
        
            if( !empty($paramsArr) ):
                 foreach ($paramsArr as $key => $value): 
                    if( isset($value['icon']) ):                           
                        $techs_html .= '<span class="html-ico">';
                            if( isset($value['image']) && ($value['image'] != '') ): 
                            $techs_html .= '<img class="img-fluid" src="'.esc_url($value['image']).'" alt="'.esc_attr($value['title']).'">';
                            else:
                            $icon_class = str_replace('fa ', 'fab ', $value['icon']);
                            $techs_html .= '<i class="'.esc_attr($icon_class).'"></i>';
                           endif;
                         $techs_html .= '</span>';
                    endif;
                endforeach;
            endif;

            $output = '<div '. implode( ' ', $wrapper_attributes ).'>                
                '.$technologies_title.'
                '.$techs_html.'
            </div>'; 
        } 
        return $output;
    }

    public function enable_newsletter_form(){
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable newsletter form?', 'perch' ),
                    'param_name' => 'enable_newsletter_form',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Email placeholder', 'perch' ),                  
                    'param_name' => 'email_placeholder', 
                    'value' => 'Your email address',                           
                    'dependency' => array( 'element' => 'enable_newsletter_form', 'value' => 'yes'),   
                    'edit_field_class' => 'vc_col-sm-8',               
                ),
                array(
                    'type' => 'textfield',  
                    'heading' => __( 'Button text', 'perch' ),                
                    'param_name' => 'button_text', 
                    'value' => 'Get Started',                           
                    'dependency' => array( 'element' => 'enable_newsletter_form', 'value' => 'yes'),   
                    'edit_field_class' => 'vc_col-sm-4',               
                ), 
                array(
                    'type' => 'textarea',  
                    'heading' => __( 'Newsletter footer text', 'perch' ),                
                    'param_name' => 'newsletter_form_footer', 
                    'value' => 'Try '.perch_modules_current_theme().' free for 14 days. No risk, and no credit card required.',                           
                    'dependency' => array( 'element' => 'enable_newsletter_form', 'value' => 'yes'),
                ), 
            );  
        return $array;
    }

    public function newsletter_form_html($atts){
        $output = $enable_newsletter_form = $newsletter_form_footer = '';
        extract($atts);

        if( $enable_newsletter_form == 'yes' ){ 
            $classes = array('hero-newsletter-form');
            $classes = self::periodic_getCSSAnimation($classes, 'hero-newsletter-form', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, '', $atts );

            $data = array();
            $data['form_class'] = 'mt-30';  
            $data['email_placeholder'] = $email_placeholder;
            $data['name_visible'] = false; 
            $data['fields_before'] = '<div class="input-group">';   
            $data['fields_after'] = '</div>';    
            $data['button_style'] = 'btn-theme';    
            $data['button_extra_class'] = 'black-hover';    

           
            $data['form_button_style'] = 'text_button';
            $data['button_field_before'] = '<span class="input-group-btn">';    
            $data['button_field_after'] = '</span>';
            ob_start();
            PerchNewsletter::render_form($data);
            $form_html = ob_get_clean();
           
            $form_desc = ($newsletter_form_footer != '')? '<div class="hero-links mt-25"><span>'.force_balance_tags($newsletter_form_footer).'</span></div>' : '';
            $form_desc = apply_filters('perch_modules/newsletter_form/form_desc', $form_desc, $atts );
                    
            
            $atts['css_animation'] = '';
            $output = '<div'.implode(' ', $wrapper_attributes).'>
                    '.$form_html.'
                    '.$form_desc.'                  
                </div>';
        } 
        return $output; 
    }


    public function enable_input_fields( $field_id, $title = '' , $textarea = false, $args = array() ){
        if($field_id == '') return false;

        $title = ( $title == '' )? 'Input field' : $title;
        $param_name = $field_id;  

        $enable_args = array(
                'type' => 'checkbox',
                'heading' => __( 'Enable '. $title .'?', 'perch' ),
                'param_name' => 'enable_'.$field_id,
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ), 
                'std' => '', 
                'admin_label' => true,                             
            );

         
        $param_args = array(
                'type' => ($textarea)? 'textarea' : 'textfield',
                'heading' => $title,
                'param_name' => $param_name,
                'value' => 'Input field text',
                'dependency' => array( 'element' => 'enable_'.$param_name, 'value' => 'yes', ),
                'perch_settings' => array(),
            );
        $array = array(
             $enable_args,
             $param_args,            
        ); 

        $array = array_merge($array, $args);

        return $array;

    }

    public function input_fields_html($param_name = '', $atts = array(), $ext_classes = '', $typo_settings = '', $tag = 'div'){
        $output =  ''; 

        $enable_param_name = $atts['enable_'.$param_name]; 
             

        if( $enable_param_name == 'yes' ){   
            $output =  $atts[$param_name]; 
            $classes = array($ext_classes);        
            $classes = self::periodic_getCSSAnimation($classes, $param_name, $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, $param_name, $atts );
            
            $output = '<'.esc_attr($tag).' '. implode( ' ', $wrapper_attributes ).'>                
               '.perch_generate_input_field_html_by_settings($output, $typo_settings, $atts ).'
            </'.esc_attr($tag).'>'; 
        } 
        return $output;
    }


    public static function periodic_animation_start( $atts ) {
        if( isset($atts['periodic_animation']) && ( $atts['periodic_animation'] == 'yes') ){
            set_query_var( 'perch_periodic_animation', true );
            set_query_var( 'perch_periodic_interval', 0 );
        }        
    }

    public static function periodic_animation_end() {
        set_query_var( 'perch_periodic_animation', false );    
        set_query_var( 'perch_periodic_interval', 0 );    
    }

    /**
     * @param $el_class
     *
     * @return string
     */
    public static function getExtraClass( $el_class, $atts = array() ) {
        $output = '';
        if ( '' !== $el_class ) {
            $output = ' ' . str_replace( '.', '', $el_class );
        }

        return $output;
    }

    /**
     * @param $css_animation
     *
     * @return string
     */
    public  static function getCSSAnimation( $css_animation, $atts = array() ) {
        if( !empty($atts) && isset($atts['periodic_animation']) ){
            $css_animation = ( $atts['periodic_animation'] == 'yes' )? 'none' : $css_animation;
        }
        $output = '';
        if ( '' !== $css_animation && 'none' !== $css_animation ) {
            wp_enqueue_script( 'wow' );
            wp_enqueue_style( 'animate' );
            $output = ' wow perch-' . $css_animation . ' ' . $css_animation;
        }

        return $output;
    }

    public static function periodic_getCSSAnimation( $classes, $param_name, $atts ){      
        if(!isset($atts['css_animation']) || ($atts['css_animation'] == '') || ($atts['css_animation'] == 'none')){
            return $classes;
        }

        if( !empty($atts) && isset($atts['periodic_animation']) && ( $atts['periodic_animation'] == 'yes' ) ){

            $atts['periodic_animation'] = '';
            if ( ('' !== $atts['css_animation']) && ('none' !== $atts['css_animation']) ) {
                $classes[] = self::getCSSAnimation($atts['css_animation'], $atts);
            }
        }

        return $classes;
    }

        

    public static function wrapperAttributes($wrapper_attributes, $atts){

        if(isset($atts['periodic_animation']) && ($atts['periodic_animation'] != '')){
            return $wrapper_attributes;
        }

        if(!isset($atts['css_animation']) || ($atts['css_animation'] == '') || ($atts['css_animation'] == 'none')){
            return $wrapper_attributes;
        }


        $wrapper_attributes[] = (isset($atts['animation_delay']) && ($atts['animation_delay'] != ''))? 'data-wow-delay="'.intval($atts['animation_delay']).'ms"' : '';
        $wrapper_attributes[] = (isset($atts['animation_duration']) && ($atts['animation_duration'] != ''))? 'data-wow-duration="'.intval($atts['animation_duration']).'ms"' : '';

       // $wrapper_attributes[] = 'data-wow-iteration="2"';

        $wrapper_attributes = array_filter($wrapper_attributes);

        return $wrapper_attributes;
    }


    public static function periodic_wrapperAttributes( $wrapper_attributes, $param_name, $atts ){ 

        if(!isset($atts['css_animation']) || ($atts['css_animation'] == '') || ($atts['css_animation'] == 'none')){
            return $wrapper_attributes;
        }



        if( get_query_var('perch_periodic_animation', false)){
            $atts['periodic_animation'] = ''; 
            $interval = get_query_var('perch_periodic_interval');
            if($interval){                
                $atts['animation_delay'] = intval($atts['animation_delay']) + ($interval * intval($atts['animation_interval']));
            }
            $new_interval = $interval + 1;
            set_query_var( 'perch_periodic_interval',  $new_interval );
            $wrapper_attributes = self::wrapperAttributes($wrapper_attributes, $atts);
            
            
            
        }      

        return $wrapper_attributes;
    }


    public static function perch_vc_get_params_array($map_arr){        
    
         
        $newarray = array();
        foreach ($map_arr as $key => $value) {
            $param_name = isset($value['param_name'])? $value['param_name'] : '';
            $std = '';
            if(isset($value['value']) ){
                if( is_array($value['value']) ) {
                    $array = $value['value']; reset($array); $std = key($array);
                }else {
                    $std = $value['value'];
                }
            }
            $std = isset($value['std'])? $value['std'] : $std;

            if( $param_name != '' ){
                $newarray[$param_name] = $std;
            }
        }          
       
        if( !empty($newarray) ) $map_arr = $newarray;
       
        
        return $map_arr;

    }
    
    
    public function vc_admin_view(){
         
        $paramsArr = $_POST['params'];    
        $paramsArr['title_animation'] = '';
        $paramsArr['subtitle_animation'] = '';
        $paramsArr['css_animation'] = '';
        //var_dump($paramsArr);
        $params = ' ';
        foreach ($paramsArr as $key => $value) {
            $params .= ' '.$key.'="'.$value.'"';
        }
       //echo $params;

        $admin_view_style = 'full';

        $base = $_POST['element'];
        if( $admin_view_style == 'simple' ){
            $admin_params = $_POST['admin_params'];
            if($admin_params != ''){
                echo PerchVcMap::admin_vc_view($paramsArr, $admin_params);
            }
        }else{
           echo do_shortcode('['.$base.$params.']'); 
        }
        

        wp_die();
    }

    public function carousel_output($html){

        if( is_user_logged_in() ){
             wp_enqueue_script( 'perch-scripts' );
            $html .=  '<div id="perch-vc-frontend-scripts"></div>';
        }
        return $html;
    }


     
} // End Element Class
 
 
// Element Class Init
new PerchVcMap();