<?php
class PerchVcMapIcons extends PerchVcMap{
	function __construct() {        
    }

    public static function icon_args(){

        $group = __( 'Icon', 'perch' );
        $dependency = array( 'element' => 'custom_title_option', 'value' => 'yes' );
        $font_dependency = array( 'element' => 'custom_title_font', 'value' => 'yes' );

        $array = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Icon type', 'perch' ),
                'param_name' => 'icon_type',
                'std' => perch_modules_icon_type_std(),
                'value' => perch_modules_icon_type_options(),               
                'admin_label' => true 
            ), 
            self::vc_icon_set( 'fontawesome','icon_fontawesome','fa fa-free-code-camp','icon_type'),
        );            
            
        $new_array = apply_filters( 'perch_modules/vc_icon_type_element', array() );
        if( !empty($new_array) ){
            $array = array_merge($array, $new_array);   
        }
            
        $new_array = array(             
            array(
                'type' => 'image_upload',
                'heading' => __( 'Icon Image', 'perch' ),
                'param_name' => 'icon_image',
                'value' => get_template_directory_uri(). '/images/hero-logo.png',
                'dependency' => array(
                     'element' => 'icon_type',
                    'value' => 'image' 
                ), 
                'edit_field_class' => 'vc_col-sm-8',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Icon image size', 'perch' ),
                'param_name' => 'img_icon_size',
                'value' => '60',
                'dependency' => array(
                     'element' => 'icon_type',
                    'value' => 'image'
                ),
                'edit_field_class' => 'vc_col-sm-4', 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Icon size', 'perch' ),
                'param_name' => 'icon_size',
                'value' => self::vc_element_icon_size(),             
                'std' => 'md',
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => array('tonicons', 'fontawesome', 'flaticon'), 
                ),   
                'edit_field_class' => 'vc_col-sm-8',          
            ),            
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Icon color', 'perch' ),
                'param_name' => 'icon_color',
                'value' => self::vc_color_options(true, '', ''),
                'std' => 'grey-color',             
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => array('tonicons', 'text', 'fontawesome', 'flaticon'), 
                ),
                'edit_field_class' => 'vc_col-sm-4', 
            ),            
        );
        $array = array_merge($array, $new_array); 

        $array = apply_filters( 'perch/icon_args', $array );

        return $array;   
    }

    public static function icon_html($atts){
        $icon_html = $icon_type = $content = '';       

        $map_arr = self::icon_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content);
        $atts = array_merge( $args, $atts );

        extract($atts);
       
         
         $title = isset($atts['title'])? $atts['title'] : 'icon';

         $icon_wrap_classes = array();         
         $icon_wrap_classes[] = ( $icon_color != '' )? $icon_color.'-icon' : '';         
         $icon_wrap_classes[] = ( $icon_type == 'image')? 'b-img' : 'b-icon';
         if( !in_array($icon_type, array('image'))){
             $icon_wrap_classes[] = ( $icon_type != 'fontawesome' )? 'box-icon-'.$icon_size : 'fa-'.$icon_size;
         }

        $icon_wrap_classes = self::periodic_getCSSAnimation( $icon_wrap_classes, 'icon', $atts );
        

        $wrapper_attributes = array();
        $wrapper_attributes[] = (!empty($icon_wrap_classes))?' class="'.trim(implode(' ', $icon_wrap_classes)).'"' : '';
        $wrapper_attributes = array_filter($wrapper_attributes);
        $wrapper_attributes = self::periodic_wrapperAttributes( $wrapper_attributes, 'icon', $atts);

        if( ( $icon_type != 'image' ) && ($icon_type != 'fontawesome') && isset($atts['icon_'.$icon_type]) ){
            $icon_class = $atts['icon_'.$icon_type];
            $icon_class .= ' '.$icon_color;
            $icon_html = '<div'.implode(' ', $wrapper_attributes).'><span class="'.esc_attr($icon_class).'"></span></div>';
        }
        if( ( $icon_type == 'fontawesome' ) && ($icon_fontawesome != '') ){
            $icon_fontawesome .= ( $icon_size != 'icon' )? ' fa-'.$icon_size : '';
            $icon_fontawesome .= ' '.$icon_color;
            $icon_html = '<div'.implode(' ', $wrapper_attributes).'><span><i class="'.esc_attr($icon_fontawesome).'"></i></span></div>';
        }



        if( $icon_type == 'text' ){
            $icon_html = '<h2 class="tra-digit '.esc_attr($icon_color) .'-color">'.esc_attr($tra_digit).'</h2>';
        }
        if( $icon_type == 'image' ){
            $icon_html = '<div'.implode(' ', $wrapper_attributes).'>
            <img src="'.esc_url($icon_image).'" alt="'.esc_attr($title).'" width="'.intval($img_icon_size).'" class="img-fluid">
        </div>';
        }

        return $icon_html;
    }
}
new PerchVcMapIcons();