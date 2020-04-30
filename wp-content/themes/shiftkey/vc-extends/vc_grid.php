<?php
if(class_exists('PerchVcMap')):
/*
Element Description: VC Title area
*/
 
// Element Class 
class PerchVcGridMap extends PerchVcMap {

    private $base = 'perch_vc_grid';

    private $title = 'Grid container';
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_grid_mapping' ) );
        add_filter( 'perch_vc_grid', array( $this, 'vc_grid_html' ), 10, 2 ); 
    }

    // Element Mapping
    public function vc_grid_mapping() {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        $params = $this->grid_map_args();     
       
       $vc_map_array = array(
                'class' => apply_filters( 'perch_modules/vc_class', 'perch-vc' ),
                'name' => esc_attr__('Grid', 'shiftkey'),
                'front_enqueue_js' => PERCH_MODULES_DIR. '/assets/js/perch_vc_frontend.js',
                'base' => apply_filters( 'perch_modules/grid_prefix', 'perch_' ).'vc_grid',
                'description' => esc_attr__('Display single column or multi-column item', 'shiftkey'), 
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
    public function grid_map_args(){

        $grid_map = array(
            array(
                'type' => 'dropdown',
                'heading' => esc_attr__( 'Grid type', 'shiftkey' ),
                'param_name' => 'cacrousel_type',
                'std' => 'masonry',
                'value' => array(
                    'Masonry Grid' => 'masonry'
                ),             
            ),                       
        );
        
        $array = PerchVcMap::element_align_args();        
        $array = array_merge($array, $grid_map);
        $array = array_merge($array, PerchVcMap::element_common_args());

        $array = apply_filters( 'perch/grid_map_args', $array );

        return $array;
    } 
     
     
    // Element HTML
    public function vc_grid_html( $atts, $shortcode_content ) {
        $content = $shortcode_content;
        $map_arr = $this->grid_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );
        extract( $atts );
        

       $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, perch_shortcode_custom_css_class( $css, ' ' ), $this->base, $atts );

        $classes = array(    
                'masonry-wrap',
                'grid-loaded',
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
            'grid_type' => $cacrousel_type
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

        $html = apply_filters('perch/grid_output', $html);   

        //used for vc frontend editor
        //do_action( 'perch/grid_output' );
         
        return $html;
         
    }    

     
} // End Element Class
 
 
// Element Class Init
new PerchVcGridMap();

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){    
    class WPBakeryShortCode_Perch_vc_grid extends WPBakeryShortCodesContainer {
    }
}
endif;   