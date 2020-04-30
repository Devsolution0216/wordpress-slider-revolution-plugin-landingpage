<?php
class PerchVcMapButtons extends PerchVcMap{
	function __construct() {        
    }

    public static function text_button_param($args = array()) {
        return array(
            array(
                'type' => 'textfield',
                'value' => 'Get Started Now',
                'heading' => 'Button title',
                'param_name' => 'button_text',
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-9'
            ),            
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Button style', 'perch' ),
                'param_name' => 'button_style',
                'std' => 'btn-preset',
                'value' => array_flip(self::btn_style_options(true)),
                'edit_field_class' => 'vc_col-sm-3'                  
            ),  
            self::vc_icon_set( 'fontawesome','icon',apply_filters('perch_modules/button/default_icon', 'fa fa-angle-double-right')),          
        );
    }

    public static function button_groups_param($args = array()) {
        return array(
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Button type', 'perch' ),
                'param_name' => 'button_type',
                'value' => array(
                     'Text button' => 'text_btn',
                    'Image button' => 'img_btn', 
                    'Video Link' => 'video_btn', 
                ),
                'save_always' => true, 
                'admin_label' => true
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( 'Button Image', 'perch' ),
                'param_name' => 'img_btn',
                'value' => get_template_directory_uri(). '/images/googleplay.png',
                'dependency' => array(
                     'element' => 'button_type',
                    'value' => 'img_btn' 
                ),
                'edit_field_class' => 'vc_col-sm-8'
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Button size', 'perch' ),
                'param_name' => 'img_btn_size',
                'value' => '160',
                'dependency' => array(
                     'element' => 'button_type',
                    'value' => 'img_btn' 
                ),
                'edit_field_class' => 'vc_col-sm-4'
            ),            
            array(
                'type' => 'textfield',
                'value' => 'Learn More About It',
                'heading' => 'Button title',
                'param_name' => 'button_text',
                'admin_label' => true,
            ),
            array(
                'type' => 'textfield',
                'value' => '#',
                'heading' => 'Button URL',
                'param_name' => 'button_url',
            ),  
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Button style', 'perch' ),
                'param_name' => 'button_style',
                'std' => 'btn-preset',
                'value' => array_flip(self::btn_style_options(true)),
                'dependency' => array(
                        'element' => 'button_type',
                        'value' => array('text_btn', 'video_btn' )
                    ), 
                'edit_field_class' => 'vc_col-sm-6' 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Button hover style', 'perch' ),
                'param_name' => 'button_hover_style',
                'std' => '',
                'value' => perch_vc_color_options(true, ''),
                'dependency' => array(
                        'element' => 'button_type',
                        'value' => 'text_btn' 
                    ), 
                'edit_field_class' => 'vc_col-sm-6' 
            ),         
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Button target', 'perch' ),
                'param_name' => 'button_target',
                'value' => array(
                     'Open link in a self tab' => '_self',
                    'Open link in a new tab' => '_blank' 
                ) ,
                'dependency' => array(
                        'element' => 'button_type',
                        'value' => array('text_btn', 'img_btn' )
                    ), 
                'edit_field_class' => 'vc_col-sm-6',
            ),            
            array(
                 'type' => 'dropdown',
                'std' => 'btn-normal',
                'value' => array( 
                    'Default' => '',
                    'Medium' => 'btn-md',                
                    'Large' => 'btn-lg',
                    'Small' => 'btn-sm',               
                    
                ),
                'heading' => 'Button size',
                'param_name' => 'button_size',
                'dependency' => array(
                        'element' => 'button_type',
                        'value' => 'text_btn' 
                    ), 
                'edit_field_class' => 'vc_col-sm-6'      
            ) 
        );
    }

    public static function single_button_html( $atts = array(), $extra_class = '',  $tag = 'a', $buttonAttr = array() ) {
        if ( empty( $atts ) )
            return;

        $output = '';

        $darkcolorArr = PerchVcMap::default_dark_color_classes(array('prefix' => 'btn-')); 
        $del_val = 'btn-tra-dark';
        if (($key = array_search($del_val, $darkcolorArr)) !== false) {
            unset($darkcolorArr[$key]);
        }        
        $darkcolortraArr = PerchVcMap::default_dark_color_classes(array('prefix' => 'btn-tra-')); 

        extract( shortcode_atts( array(            
            'button_type' => 'text_btn', 
            'img_btn' => get_template_directory_uri(). '/images/googleplay.png',
            'img_btn_size' => '160',
            'icon_position' => 'icon_position-right',
            'button_text' => 'Get Started Now',
            'button_url' => '#',
            'button_target' => '_self',
            'button_style' => 'btn-preset',
            'button_hover_style' => '',
            'button_size' => '',
            'button_extra_class' => '',
            'icon' => apply_filters('perch_modules/button/default_icon', 'fa fa-angle-double-right'),
        ), $atts ) );

        $iconClass              = array();
        $iconClass[]           = $icon;
        $iconClass              = array_filter( $iconClass );

        if( $button_type == 'text_btn' ){
             $buttonClass = array('btn','btn-arrow'); 
             $buttonClass = apply_filters('perch_modules/buttons/common_class', $buttonClass); 
             $btntxt = perch_parse_text( $button_text, array( 'tag' => 'strong') );
             $buttonClass[]         = $button_style;
            $buttonClass[]         = $button_size;
            $buttonClass[]  = (($icon != '') && ($button_text != ''))? $icon_position : '';          
        }
        if( $button_type == 'img_btn' ){
            $icon_position = '';
            $buttonClass =  array('img-btn');
            $btntxt = '<img src="'.esc_url($img_btn).'" alt="'.esc_attr($button_text).'" class="store-btn" width="'.intval($img_btn_size).'">';
        }       


        $buttonClass[] = ($button_hover_style != '')? $button_hover_style.'-hover' : '';
        $buttonClass[] = $extra_class;
        $buttonClass[] = $button_extra_class;

        $buttonClass = self::periodic_getCSSAnimation( $buttonClass, 'button', $atts );
        
        if($button_style != ''){
            $buttonClass[] = (in_array( $button_style, $darkcolorArr))? 'btn-type-dark' : 'btn-type-light';
            if(in_array( $button_style, $darkcolortraArr)){
                $buttonClass[] = 'btn-hover-type-dark';
            }
        }  
       
        if( $button_type == 'video_btn' ){
            $icon_position = '';
            $buttonClass =  array('video-popup2', 'btn');
            $buttonClass[]         = $button_style;
            $btntxt = esc_attr($button_text) . ' <i class="far fa-play-circle"></i>';
        }
 
        
        $buttonClass            = array_filter( $buttonClass );   
        if( empty($buttonAttr) ){
            $buttonAttr[ 'target' ] = $button_target;
        }     
        
        if(!array_key_exists("type", $buttonAttr)){
            $buttonAttr[ 'href' ]   = esc_url( $button_url );
        }
        
        $buttonAttr[ 'title' ]  = esc_attr( $button_text );
        $buttonAttr[ 'class' ]  = implode( ' ', $buttonClass );            
       
        $wrapper_attributes = array();
        foreach ( $buttonAttr as $key => $value ) {
            $wrapper_attributes[] = ' ' . $key . '="' . $value . '"';
        } //$buttonAttr as $key => $value
        $wrapper_attributes = array_filter($wrapper_attributes);
        $wrapper_attributes = self::periodic_wrapperAttributes( $wrapper_attributes, 'button', $atts);
       
        if ( $icon != '' ) {
            $icon = ' <i class="' . implode( ' ', $iconClass ) . '"></i>';
        } //$icon_perch != ''
        $output .= '<'. $tag . implode(' ', $wrapper_attributes) . '><span>';
        $output .= $btntxt;
        $output .= ( $icon_position == 'icon_position-right' ) ? $icon : '';
        $output .= '</span></'. $tag.'>';
        
        return $output;
    }

    public static function button_groups_html( $buttons = array(), $extra_class = '', $atts = array() ) {
        if ( empty( $buttons ) )
            return;

        $darkcolorArr = PerchVcMap::default_dark_color_classes(array('prefix' => 'btn-')); 
        $del_val = 'btn-tra-dark';
        if (($key = array_search($del_val, $darkcolorArr)) !== false) {
            unset($darkcolorArr[$key]);
        }     
        $darkcolortraArr = PerchVcMap::default_dark_color_classes(array('prefix' => 'btn-tra-')); 

        $output = '';
        foreach ( $buttons as $key => $value ):            
            $output .= self::single_button_html($value, $extra_class);
        endforeach;
        return $output;
    }

    public static function button_args(){

            $group = __( 'Button', 'perch' );
            $dependency = '';

            $icons_params = vc_map_integrate_shortcode( vc_icon_element_params(), 'i_', '', array(
                'include_only_regex' => '/^(type|icon_\w*)/',
                // we need only type, icon_fontawesome, icon_blabla..., NOT color and etc
            ), array(
                'element' => 'add_icon',
                'value' => 'true',            
            )   
            );

            $array = array_merge( array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Button Text', 'perch' ),
                    'param_name' => 'btn_title',
                    // fully compatible to btn1 and btn2
                    'value' => __( 'Text on the button', 'perch' ),
                    'edit_field_class' => 'vc_col-sm-8',
                ),            
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button type', 'perch' ),
                    'param_name' => 'color',
                    'description' => __( 'Select button color.', 'perch' ),
                    // compatible with btn2, need to be converted from btn1
                    'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                    'edit_field_class' => 'vc_col-sm-4',
                    'value' => self::btn_style_options(true) + 
                    array(
                        // Btn1 Colors                
                        __( 'Classic Grey', 'perch' ) => 'default',
                        __( 'Classic Blue', 'perch' ) => 'primary',
                        __( 'Classic Turquoise', 'perch' ) => 'info',
                        __( 'Classic Green', 'perch' ) => 'success',
                        __( 'Classic Orange', 'perch' ) => 'warning',
                        __( 'Classic Red', 'perch' ) => 'danger',
                        __( 'Classic Black', 'perch' ) => 'inverse' 
                        // + Btn2 Colors (default color set)
                    ) + vc_get_shared( 'colors-dashed' ) ,
                    'std' => 'btn-preset',
                    // must have default color grey
                    'dependency' => array(
                         'element' => 'style',
                        'value_not_equal_to' => array(
                             'custom',
                            'outline-custom',
                            'gradient',
                            'gradient-custom' 
                        ) 
                    ) 
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Add button image?', 'perch' ),
                    'param_name' => 'add_image', 
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),                 
                ),
                array(
                    'type' => 'image_upload',
                    'heading' => __( 'Button image', 'perch' ),
                    'param_name' => 'button_image', 
                    'value' => get_template_directory_uri().'/images/appstore.png',
                    'edit_field_class' => 'vc_col-sm-8',
                    'dependency' => array(
                         'element' => 'add_image',
                        'value' => 'yes' 
                    ) 
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Button image width', 'perch' ),
                    'param_name' => 'btn_image_width',
                    // fully compatible to btn1 and btn2
                    'value' => __( '160', 'perch' ),
                    'dependency' => array(
                         'element' => 'add_image',
                        'value' => 'yes' 
                    ), 
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'URL (Link)', 'perch' ),
                    'param_name' => 'link',
                    'description' => __( 'Add link to button.', 'perch' ),
                    // compatible with btn2 and converted from href{btn1}

                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Alignment', 'perch' ),
                    'param_name' => 'align',
                    'description' => __( 'Select button alignment.', 'perch' ),
                    // compatible with btn2, default left to be compatible with btn1
                    'std' => 'inline',
                    'value' => array(
                        __( 'Inline', 'perch' ) => 'inline',
                        // default as well
                        __( 'Left', 'perch' ) => 'left',
                        // default as well
                        __( 'Right', 'perch' ) => 'right',
                        __( 'Center', 'perch' ) => 'center',

                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Set full width button?', 'perch' ),
                    'param_name' => 'button_block',
                    'dependency' => array(
                        'element' => 'align',
                        'value_not_equal_to' => 'inline',
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                ),  
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Add icon?', 'perch' ),
                    'param_name' => 'add_icon', 
                    'std' => false,
                    'edit_field_class' => 'vc_col-sm-4',
                ),          
                array(
                 'type' => 'dropdown',
                    'heading' => __( 'Style', 'perch' ),
                    'description' => __( 'Select button display style.', 'perch' ),
                    'param_name' => 'style',
                    'std' => 'perch',
                    // partly compatible with btn2, need to be converted shape+style from btn2 and btn1
                    'value' => array(
                        __( 'Landpick', 'perch' ) => 'perch',
                        __( 'Modern', 'perch' ) => 'modern',
                        __( 'Classic', 'perch' ) => 'classic',
                        __( 'Flat', 'perch' ) => 'flat',
                        __( 'Outline', 'perch' ) => 'outline',
                        __( '3d', 'perch' ) => '3d',
                        __( 'Custom', 'perch' ) => 'custom',
                        __( 'Outline custom', 'perch' ) => 'outline-custom',
                        __( 'Gradient', 'perch' ) => 'gradient',
                        __( 'Gradient Custom', 'perch' ) => 'gradient-custom' 
                    ),
                    'group' => $group,
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Display arrow on button hover?', 'perch' ),
                    'param_name' => 'btnarrow',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),
                    'std' => 'yes',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => 'perch',
                    ),
                     'group' => $group,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Gradient Color 1', 'perch' ),
                    'param_name' => 'gradient_color_1',
                    'description' => __( 'Select first color for gradient.', 'perch' ),
                    'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                    'value' => vc_get_shared( 'colors-dashed' ),
                    'std' => 'turquoise',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'gradient' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-6',
                    'group' => $group,
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Gradient Color 2', 'perch' ),
                    'param_name' => 'gradient_color_2',
                    'description' => __( 'Select second color for gradient.', 'perch' ),
                    'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                    'value' => vc_get_shared( 'colors-dashed' ),
                    'std' => 'blue',
                    // must have default color grey
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'gradient' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-6',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Gradient Color 1', 'perch' ),
                    'param_name' => 'gradient_custom_color_1',
                    'description' => __( 'Select first color for gradient.', 'perch' ),
                    'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                    'value' => '#dd3333',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'gradient-custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Gradient Color 2', 'perch' ),
                    'param_name' => 'gradient_custom_color_2',
                    'description' => __( 'Select second color for gradient.', 'perch' ),
                    'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                    'value' => '#eeee22',
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'gradient-custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Button Text Color', 'perch' ),
                    'param_name' => 'gradient_text_color',
                    'description' => __( 'Select button text color.', 'perch' ),
                    'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                    'value' => '#ffffff',
                    // must have default color grey
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'gradient-custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Background', 'perch' ),
                    'param_name' => 'custom_background',
                    'description' => __( 'Select custom background color for your element.', 'perch' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-6',
                    'std' => '#ededed',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Text', 'perch' ),
                    'param_name' => 'custom_text',
                    'description' => __( 'Select custom text color for your element.', 'perch' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-6',
                    'std' => '#666',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Outline and Text', 'perch' ),
                    'param_name' => 'outline_custom_color',
                    'description' => __( 'Select outline and text color for your element.', 'perch' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'outline-custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                    'std' => '#666',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Hover background', 'perch' ),
                    'param_name' => 'outline_custom_hover_background',
                    'description' => __( 'Select hover background color for your element.', 'perch' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'outline-custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                    'std' => '#666',
                    'group' => $group,
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Hover text', 'perch' ),
                    'param_name' => 'outline_custom_hover_text',
                    'description' => __( 'Select hover text color for your element.', 'perch' ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => array( 'outline-custom' ),
                    ),
                    'edit_field_class' => 'vc_col-sm-4',
                    'std' => '#fff',
                    'group' => $group,
                ),
                array(
                     'type' => 'dropdown',
                    'heading' => __( 'Shape', 'perch' ),
                    'description' => __( 'Select button shape.', 'perch' ),
                    'param_name' => 'shape',
                    // need to be converted
                    'value' => array(
                         __( 'Square', 'perch' ) => 'square',
                        __( 'Rounded', 'perch' ) => 'rounded',
                        __( 'Round', 'perch' ) => 'round' 
                    ),
                    'dependency' => array(
                         'element' => 'style',
                        'value_not_equal_to' => array(
                             'perch'
                        ) 
                    ),
                    'group' => $group,
                ),            
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Size', 'perch' ),
                    'param_name' => 'size',
                    'description' => __( 'Select button display size.', 'perch' ),
                    // compatible with btn2, default md, but need to be converted from btn1 to btn2
                    'std' => 'md',
                    'value' => vc_get_shared( 'sizes' ),
                    'group' => $group,
                ),            
                
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Icon Alignment', 'perch' ),
                    'description' => __( 'Select icon alignment.', 'perch' ),
                    'param_name' => 'i_align',
                    'value' => array(
                        __( 'Left', 'perch' ) => 'left',
                        // default as well
                        __( 'Right', 'perch' ) => 'right',
                    ),
                    'dependency' => array(
                        'element' => 'add_icon',
                        'value' => 'true',
                    ),
                    'group' => $group,
                ),
            ), $icons_params
        );

        $array = apply_filters( 'perch/button_args', $array );

        return $array;   
    }

    public static function button_html($atts){
        $css_class = $add_image = $btn_image_width = '';
        $custom_onclick = $custom_onclick_code = '';
        $a_href = $a_title = $a_target = $a_rel = $button_block = $add_icon = '';
        $styles = array();
        $icon_wrapper = false;
        $icon_html = false;
        $attributes = array();
        $wrapper_classes = array();

        if( !isset($atts['style']) || ($atts['style'] == '') ) $atts['style'] = 'perch';
        if( !isset($atts['btnarrow']) || ($atts['btnarrow'] == '') ) $atts['btnarrow'] = 'yes';
        if( !isset($atts['size']) || ($atts['size'] == '') ) $atts['size'] = 'md';


        extract( $atts );

       
        //parse link
        $link = trim( $link );
        $link = ( '||' === $link ) ? '' : $link;
        $link = perch_vc_build_link( $link );
        $use_link = true;       
        if ( strlen( $link['url'] ) > 0 ) {
            $use_link = true;
            $a_href = $link['url'];
            $a_href = apply_filters( 'vc_btn_a_href', $a_href );
            $a_title = $link['title'];
            $a_title = apply_filters( 'vc_btn_a_title', $a_title );
            $a_target = $link['target'];
            $a_rel = $link['rel'];
        }

        if($style != 'perch'){ 
            $wrapper_classes = array(
                'vc_btn3-container',
                'vc_btn3-' . $align,
            );

            $button_classes = array(
                'vc_general',
                'vc_btn3',
                'vc_btn3-size-' . $size,
                'vc_btn3-shape-' . $shape,
                'vc_btn3-style-' . $style,
            );
        }


        if($style == 'perch'){            
            $size = ( $size == 'md' )? '' : 'btn-' . $size;
            $button_classes = array(
                'btn',      
                $size,
            );

            $darkcolorArr = PerchVcMap::default_dark_color_classes(array('prefix' => 'btn-'));   
            $darkcolortraArr = PerchVcMap::default_dark_color_classes(array('prefix' => 'btn-tra-'));    
           
            if(in_array( $color, $darkcolorArr)){
                $button_classes[] = 'btn-type-dark';
            }
            if(in_array( $color, $darkcolortraArr)){
                $button_classes[] = 'btn-hover-type-dark';
            }
            $button_classes[] = ( $btnarrow == 'yes' )? 'btn-arrow': '';
            
        }



        if( $style == 'perch' ){
            $button_html = ( $btnarrow == 'yes' )? '<span>'. $btn_title .' <i class="fa fa-angle-double-right"></i></span>': $btn_title;    
        }else{
            $button_html = $btn_title;
        }

        if ( '' === trim( $btn_title ) ) {
            $button_classes[] = 'vc_btn3-o-empty';
            $button_html = '<span class="vc_btn3-placeholder">&nbsp;</span>';
        }
        if ( 'true' === $button_block && 'inline' !== $align ) {
            $button_classes[] = ( $style == 'perch' )? 'btn-block' : 'vc_btn3-block';
        }
        if ( 'true' === $add_icon ) {
            $button_classes[] = 'vc_btn3-icon-' . $i_align;
            vc_icon_element_fonts_enqueue( $i_type );

            if ( isset( ${'i_icon_' . $i_type} ) ) {
                if ( 'pixelicons' === $i_type ) {
                    $icon_wrapper = true;
                }
                $icon_class = ${'i_icon_' . $i_type};
            } else {
                $icon_class = 'fa fa-adjust';
            }

            if ( $icon_wrapper ) {
                $icon_html = '<i class="vc_btn3-icon"><span class="vc_btn3-icon-inner ' . esc_attr( $icon_class ) . '"></span></i>';
            } else {
                $icon_html = '<i class="vc_btn3-icon ' . esc_attr( $icon_class ) . '"></i>';
            }

            if ( 'left' === $i_align ) {
                $button_html = $icon_html . ' ' . $button_html;
            } else {
                $button_html .= ' ' . $icon_html;
            }
        }

        if ( 'custom' === $style ) {
            if ( $custom_background ) {
                $styles[] = vc_get_css_color( 'background-color', $custom_background );
            }

            if ( $custom_text ) {
                $styles[] = vc_get_css_color( 'color', $custom_text );
            }

            if ( ! $custom_background && ! $custom_text ) {
                $button_classes[] = 'vc_btn3-color-grey';
            }
        } elseif ( 'outline-custom' === $style ) {
            if ( $outline_custom_color ) {
                $styles[] = vc_get_css_color( 'border-color', $outline_custom_color );
                $styles[] = vc_get_css_color( 'color', $outline_custom_color );
                $attributes[] = 'onmouseleave="this.style.borderColor=\'' . $outline_custom_color . '\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'' . $outline_custom_color . '\'"';
            } else {
                $attributes[] = 'onmouseleave="this.style.borderColor=\'\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'\'"';
            }

            $onmouseenter = array();
            if ( $outline_custom_hover_background ) {
                $onmouseenter[] = 'this.style.borderColor=\'' . $outline_custom_hover_background . '\';';
                $onmouseenter[] = 'this.style.backgroundColor=\'' . $outline_custom_hover_background . '\';';
            }
            if ( $outline_custom_hover_text ) {
                $onmouseenter[] = 'this.style.color=\'' . $outline_custom_hover_text . '\';';
            }
            if ( $onmouseenter ) {
                $attributes[] = 'onmouseenter="' . implode( ' ', $onmouseenter ) . '"';
            }

            if ( ! $outline_custom_color && ! $outline_custom_hover_background && ! $outline_custom_hover_text ) {
                $button_classes[] = 'vc_btn3-color-inverse';

                foreach ( $button_classes as $k => $v ) {
                    if ( 'vc_btn3-style-outline-custom' === $v ) {
                        unset( $button_classes[ $k ] );
                        break;
                    }
                }
                $button_classes[] = 'vc_btn3-style-outline';
            }
        } elseif ( 'gradient' === $style || 'gradient-custom' === $style ) {

            $gradient_color_1 = vc_convert_vc_color( $gradient_color_1 );
            $gradient_color_2 = vc_convert_vc_color( $gradient_color_2 );

            $button_text_color = '#fff';
            if ( 'gradient-custom' === $style ) {
                $gradient_color_1 = $gradient_custom_color_1;
                $gradient_color_2 = $gradient_custom_color_2;
                $button_text_color = $gradient_text_color;
            }

            $gradient_css = array();
            $gradient_css[] = 'color: ' . $button_text_color;
            $gradient_css[] = 'border: none';
            $gradient_css[] = 'background-color: ' . $gradient_color_1;
            $gradient_css[] = 'background-image: -webkit-linear-gradient(left, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
            $gradient_css[] = 'background-image: linear-gradient(to right, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
            $gradient_css[] = '-webkit-transition: all .2s ease-in-out';
            $gradient_css[] = 'transition: all .2s ease-in-out';
            $gradient_css[] = 'background-size: 200% 100%';

            // hover css
            $gradient_css_hover = array();
            $gradient_css_hover[] = 'color: ' . $button_text_color;
            $gradient_css_hover[] = 'background-color: ' . $gradient_color_2;
            $gradient_css_hover[] = 'border: none';
            $gradient_css_hover[] = 'background-position: 100% 0';

            $uid = uniqid();
            echo '<style type="text/css">.vc_btn3-style-' . $style . '.vc_btn-gradient-btn-' . $uid . ':hover{' . implode( ';', $gradient_css_hover ) . ';' . '}</style>';
            echo '<style type="text/css">.vc_btn3-style-' . $style . '.vc_btn-gradient-btn-' . $uid . '{' . implode( ';', $gradient_css ) . ';' . '}</style>';
            $button_classes[] = 'vc_btn-gradient-btn-' . $uid;
            $attributes[] = 'data-vc-gradient-1="' . $gradient_color_1 . '"';
            $attributes[] = 'data-vc-gradient-2="' . $gradient_color_2 . '"';
        } else {
            $button_classes[] = ( $style == 'perch' )? $color: 'vc_btn3-color-' . $color;
        }

        if ( $styles ) {
            $attributes[] = 'style="' . implode( ' ', $styles ) . '"';
        }

        $class_to_filter = implode( ' ', array_filter( $wrapper_classes ) );
        //$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, 'perch_vc_button', $atts );
       
        if ( !empty($button_classes) ) {
            if($add_image == 'yes'){
                $button_html = '<img src="'.esc_url($button_image).'" alt="'.esc_attr($btn_title).'" class="img-fluid" width="'.intval($btn_image_width).'">';
                $use_link = true;
            }else{                
                $button_classes = esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $button_classes ) ), 'perch_vc_button', $atts ) );
                $attributes[] = 'class="' . trim( $button_classes ) . '"';
            }
        }

        
      

        if ( $use_link ) {
            $attributes[] = 'href="' . trim( $a_href ) . '"';
            $attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
            if ( ! empty( $a_target ) ) {
                $attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
            }
            if ( ! empty( $a_rel ) ) {
                $attributes[] = 'rel="' . esc_attr( trim( $a_rel ) ) . '"';
            }
        }

        if ( ! empty( $custom_onclick ) && $custom_onclick_code ) {
            $attributes[] = 'onclick="' . esc_attr( $custom_onclick_code ) . '"';
        }

        $attributes = implode( ' ', $attributes );
        $wrapper_attributes = array();
        if ( ! empty( $btn_el_id ) ) {
            $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
        }



        $output = '<div class="'.trim( esc_attr( $css_class ) ).'" '.implode( ' ', $wrapper_attributes ).'>';
         
            if ( $use_link ) {
                $output .= '<a ' . $attributes . '>' . $button_html . '</a>';
            } else {
                $output .=  '<button ' . $attributes . '>' . $button_html . '</button>';
            }
        $output .=  '</div>';


        return perch_js_remove_wpautop($output);
    }

}
new PerchVcMapButtons();