<?php
/*
Element Description: Watch video
*/
 
// Element Class 
class PerchWatchVideo2 extends PerchVcMap {
     
    private $base = 'perch_watch_video2';

    private $title = 'Watch video';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_watch_video2_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_watch_video2_slide_output' ), 30, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        

       $array = self::element_align_args(); 
        $array[] = array(
                'type' => 'textfield',
                'heading' => __( 'Video URL', 'perch' ),
                'param_name' => 'url',
                'value' => 'https://www.youtube.com/watch?v=7e90gBu4pas',   
                'edit_field_class' => 'vc_col-sm-8',      
            ); 
        $array[] = array(
                 'type' => 'dropdown',
                'heading' => __( 'Video icon color', 'perch' ),
                'param_name' => 'icon_class',               
                'value' => perch_vc_global_color_options(),
                'std' => 'blue',
                'description' => '',               
                'edit_field_class' => 'vc_col-sm-4',
            );
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title'));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Sub-Title',true, array('textarea' => true)));
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_watch_video2_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
     
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);
        extract($atts);

      
        // Fill $html var with data
        
        $html ='
            <div '. implode( ' ', $wrapper_attributes ).'>
            <div class="video-preview">        
                <a class="video-popup1" href="'.esc_url($url).'">
                    <div class="video-btn play-icon-'.$icon_class.'">  
                        <div class="video-block-wrapper">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>                  
                </a>
                '.perch_modules_get_vc_param_html('title', $atts, $map_arr ).'
                '.perch_modules_get_vc_param_html('subtitle', $atts, $map_arr ).'
            </div>
            </div>';

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'title' => perch_modules_get_vc_param_html('title', $atts, $map_arr ),
            'subtitle' => perch_modules_get_vc_param_html('subtitle', $atts, $map_arr ),
        );     

        $html = apply_filters('perch_modules/watch_video2/output', $html, $html_args, $atts); 

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_watch_video2_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_watch_video2_output($atts);
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
                'description' => __('Display title & subtitle', 'perch'),                 
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
                'description' => __('Display title & subtitle', 'perch'),                 
                'as_child'  => array('only' => 'perch_vc_carousel'),           
                'params' => $params,
                'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',        
            );
        
        vc_map( $vc_map );
        vc_map( $vc_map_slide );
        
    }
    
    
     
} // End Element Class 
 
// Element Class Init
new PerchWatchVideo2();