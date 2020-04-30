<?php

/*
Element Description: Testimonial
*/
 
// Element Class 
class PerchDownloadImage extends PerchVcMap {
     
    private $base = 'perch_download_image';

    private $title = 'Download image';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_image_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_image_slide_output' ), 30, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        
        $array = self::element_align_args();         
        $array = array_merge($array, self::display_as());
        $array = array_merge($array, PerchVcMapImage::image_args_simple());
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_image_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base ); 

        $atts['css_animation'] = 'none';
        $atts['el_class'] = '';
        $html = '
        <div '. implode( ' ', $wrapper_attributes ).'> 
        '.PerchVcMapImage::image_html($atts).'
        </div>';      

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'image_html' => PerchVcMapImage::image_html($atts),
        );     

        $html = apply_filters('perch_modules/download_image/output', $html, $html_args, $atts);
          
         
        return $html;
         
    }

    public function perch_image_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_image_output($atts);
        $html .='</div>';

        return $html;
    }


    // Element Mapping
    public function vc_mapping( $return = false ) {
        $params = $this->vc_map_args();
        if($return) {
            return $params;  
        }        
       
       $vc_map = array(
                'class' => perch_shortcodes_vc_class(),
                'category' => perch_shortcodes_vc_category(),
                'base' => $this->base,
                'name' => $this->title,                
                'description' => __('Display download image', 'perch'),                 
                'params' => $params,
                'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',  
                'admin_enqueue_js' =>   array( PERCH_MODULES_URI. '/assets/js/vc-admin-scripts.js'),       
            ); 
        // slide element
        $vc_map_slide = array(
                'class' => perch_shortcodes_vc_class(),
                'category' => perch_shortcodes_vc_category(),
                'base' => $this->base.'_slide',
                'name' => $this->title,                
                'description' => __('Display download image', 'perch'),                 
                'as_child'  => array('only' => 'perch_vc_carousel'),           
                'params' => $params,                    
            );
        
        vc_map( $vc_map );
        vc_map( $vc_map_slide );
        
    }

    private function display_as(){
        return array(
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Display as', 'perch' ),
                'param_name' => 'display_as',
                'value' => perch_numeric_dropdown_options( 'download-', '-img', 'Download image style ' ),
                'std' => ''
            )
        );
    }
    
    
     
} // End Element Class 
 
// Element Class Init
new PerchDownloadImage();