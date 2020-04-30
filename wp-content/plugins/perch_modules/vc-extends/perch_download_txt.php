<?php

/*
Element Description: VC Title area
*/
 
// Element Class 
class PerchDownloadText extends PerchVcMap {
     
    private $base = 'perch_download_text';

    private $title = 'Download text';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_download_text_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_download_text_slide_output' ), 30, 2 ); 
    }

    // Title element mapping
    private function vc_map_args(){        

    	$array = self::element_align_args();        
        $array = array_merge($array, self::enable_logo_image() );       
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title'));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Sub-Title',true, array('textarea' => true))); 
       
        $array = array_merge($array, self::enable_content_list() );       
        $array = array_merge($array, self::enable_hero_button_group() );
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_download_text_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);

        $image_html = self::logo_image_html($atts);
        $title_html = perch_modules_get_vc_param_html('title', $atts, $map_arr );
        $subtitle_html = perch_modules_get_vc_param_html('subtitle', $atts, $map_arr );
        $content_list_html = self::content_list_html($atts);
        $buttons_html = self::buttons_html($atts);
      
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'> 
        	'.$image_html.'            
            '.$title_html.'
            '.$subtitle_html.'    
            '.$content_list_html.' 
            '.$buttons_html.'      
        </div>'; 

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'image_html' => $image_html,        
            'title_html' => $title_html,         
            'subtitle_html' => $subtitle_html,
            'content_list_html' => $content_list_html,
            'buttons_html' => $buttons_html,
        ); 

        $html = apply_filters('perch_modules/download_text/output', $html, $html_args, $atts);

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_download_text_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_download_text_output($atts);
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
                'description' => __('Display name, title, subtitle & content', 'perch'),                 
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
new PerchDownloadText();