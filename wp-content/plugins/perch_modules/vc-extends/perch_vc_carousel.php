<?php

/*
Element Description: VC Title area
*/
 
// Element Class 
class PerchVcCarouselMap extends PerchVcMap {

    private $base = 'perch_vc_carousel';

    private $title = 'Carousel container';
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_carousel_mapping' ) );
        add_filter( 'perch_vc_carousel', array( $this, 'vc_carousel_html' ), 10, 2 ); 
    }

    // Element Mapping
    public function vc_carousel_mapping() {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        $params = $this->carousel_map_args();     
       
       $vc_map_array = array(
                'class' => apply_filters( 'perch_modules/vc_class', 'perch-vc' ),
                'name' => __('Carousel', 'perch'),
                'front_enqueue_js' => PERCH_MODULES_DIR. '/assets/js/perch_vc_frontend.js',
                'base' => apply_filters( 'perch_modules/carousel_prefix', 'perch_' ).'vc_carousel',
                'description' => __('Display single column or multi-column item', 'perch'), 
                'category' => apply_filters( 'perch_modules/vc_category', 'Perch' ),   
                'icon' => PERCH_MODULES_DIR.'/images/app-logo-2.png',   
                'as_parent'  => array('only' => PerchVcMap::carousel_item_map()), 
                'content_element' => true,
                'show_settings_on_create' => true,
                'is_container' => true,
                'js_view' => 'VcColumnView',         
                'params' => $params,
                //'js_view' => 'PerchVcElementPreview',      
            );

       
         
        // Map the block with vc_map()
        vc_map( $vc_map_array );  

                                  
        
    }

    // Title element mapping
    public function carousel_map_args(){

        $carousel_map = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Carousel type', 'perch' ),
                'param_name' => 'cacrousel_type',
                'std' => 'owl',
                'value' => array(
                    'Owl Carousel' => 'owl',
                    'Slick carousel' => 'slick',
                    'Custom format' => 'custom',
                ), 
                'edit_field_class' => 'vc_col-sm-8',               
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Carousel style', 'perch' ),
                'param_name' => 'cacrousel_style',
                'std' => 'owl',
                'value' => array(
                    'Default' => '',
                    'Screen carousel' => 'screens-carousel',
                ), 
                'edit_field_class' => 'vc_col-sm-4',               
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Large screen column', 'perch' ),
                'param_name' => 'column_lg',
                'std' => '3',
                'value' => array(
                    'Single item' => '1',
                    '2 column' => '2',
                    '3 column' => '3',
                    '4 column' => '4',
                    '5 column' => '5',
                    '6 column' => '6',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Medium screen column', 'perch' ),
                'param_name' => 'column_md',
                'std' => '3',
                'value' => array(
                    'Single item' => '1',
                    '2 column' => '2',
                    '3 column' => '3',
                    '4 column' => '4',
                    '5 column' => '5',
                    '6 column' => '6',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Small screen column', 'perch' ),
                'param_name' => 'column_sm',
                'std' => '2',
                'value' => array(
                    'Single item' => '1',
                    '2 column' => '2',
                    '3 column' => '3',
                    '4 column' => '4',
                    '5 column' => '5',
                    '6 column' => '6',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Mobile screen column', 'perch' ),
                'param_name' => 'column_xs',
                'std' => '1',
                'value' => array(
                    'Single item' => '1',
                    '2 column' => '2',
                    '3 column' => '3',
                    '4 column' => '4',
                    '5 column' => '5',
                    '6 column' => '6',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Autoplay', 'perch' ),
                'param_name' => 'autoplay',
                'std' => '1',
                'value' => array(
                    'Yes' => '1',
                    'No' => '0',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Loop', 'perch' ),
                'param_name' => 'loop',
                'std' => '1',
                'value' => array(
                    'Yes' => '1',
                    'No' => '0',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Center', 'perch' ),
                'param_name' => 'center',
                'std' => '0',
                'value' => array(
                    'Yes' => '1',
                    'No' => '0',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Show dots navigation.', 'perch' ),
                'param_name' => 'dots',
                'std' => '1',
                'value' => array(
                    'Yes' => '1',
                    'No' => '0',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),            
            array(
                'type' => 'dropdown',
                'heading' => __( 'Show next/prev buttons.', 'perch' ),
                'param_name' => 'navs',
                'std' => '0',
                'value' => array(
                    'Yes' => '1',
                    'No' => '0',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Nav color', 'perch' ),
                'param_name' => 'dots_color',
                'std' => 'grey',
                'value' => perch_vc_color_options(true),
                'edit_field_class' => 'vc_col-sm-6',

            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Item inner spacing', 'perch' ),
                'param_name' => 'margin',
                'std' => '10',
                'value' => array(
                    'None' => '0',
                    '5px' => '5',
                    '10px' => '10',
                    '15px' => '15',
                    '20px' => '20',
                    '25px' => '25',
                    '30px' => '30',
                    '35px' => '35',
                    '40px' => '40',
                    '45px' => '45',
                    '50px' => '50',
                    '55px' => '55',
                    '60px' => '60',
                ),
                'edit_field_class' => 'vc_col-sm-6',
            ),
        );
        
        $array = PerchVcMap::element_align_args();        
        $array = array_merge($array, $carousel_map);
        $array = array_merge($array, PerchVcMap::element_common_args());

        $array = apply_filters( 'perch/carousel_map_args', $array );

        return $array;
    } 
     
     
    // Element HTML
    public function vc_carousel_html( $atts, $shortcode_content ) {
        $content = $shortcode_content;
        $map_arr = $this->carousel_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );
        extract( $atts );
        

       $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, perch_shortcode_custom_css_class( $css, ' ' ), $this->base, $atts );

        $classes = array(    
                $cacrousel_type. '-carousel',
                $cacrousel_type. '-theme',
                'perch-vc-carousel',
                'perch-vc-carousel-'.$cacrousel_type,
                'perch-vc-carousel-column-'.$column_lg,
                $dots_color.'-nav'
            );
        $classes = array_filter($classes);
        $classes = array_unique($classes);

        $car_classes = array(             
                $css_class, 
                $mtop, 
                $mbottom,
                $pleft,  
                $pright,
                PerchVcMap::getExtraClass( $el_class ), 
                PerchVcMap::getCSSAnimation( $css_animation, $atts ),
                $align,
                $cacrousel_style,                
            );       
        $car_classes = array_filter($car_classes);
        $car_classes = array_unique($car_classes);

         


      
        $wrapper_attributes = array();
        if ( ! empty( $el_id ) ) {
            $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
        }
        $wrapper_attributes[] = (!empty($classes))?'class="'.implode(' ', $classes).'"' : '';
        $wrapper_attributes = array_filter($wrapper_attributes);

        $data_args = array(
            'carousel_type' => $cacrousel_type,
            'column_lg' => $column_lg,
            'column_md' => $column_md,
            'column_sm' => $column_sm,
            'column_xs' => $column_xs,
            'autoplay' => $autoplay,
            'center' => $center,
            'dots' => $dots,
            'navs' => $navs,
            'margin' => $margin,
            'loop' => $loop
        );
        foreach ($data_args as $key => $value) {
            $wrapper_attributes[] = 'data-'.$key.'="' . esc_attr($value) . '"';
        }

        
        
      
      
        // Fill $html var with data
        $html ='<div class="'. implode( ' ', $car_classes ).'">        
            <div '. implode( ' ', $wrapper_attributes ).'>        
           '.do_shortcode($shortcode_content).'   
           </div> 
        </div>';   

        wp_enqueue_script( 'perch-scripts' );

        $html = apply_filters('perch/carousel_output', $html);   

        //used for vc frontend editor
        //do_action( 'perch/carousel_output' );
         
        return $html;
         
    }    

     
} // End Element Class
 
 
// Element Class Init
new PerchVcCarouselMap();

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){    
    class WPBakeryShortCode_Perch_vc_carousel extends WPBakeryShortCodesContainer {
    }
}   