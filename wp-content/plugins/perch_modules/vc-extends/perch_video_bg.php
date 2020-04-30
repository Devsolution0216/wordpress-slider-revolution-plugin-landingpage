<?php

/*
Element Description: Watch video
*/
 
// Element Class 
class PerchVideoBG extends PerchVcMap {
     
    private $base = 'perch_video_bg';

    private $title = 'Video background';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_video_bg_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_video_bg_slide_output' ), 30, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        

        $array = array(
            array(
                'type' => 'image_upload',
                'heading' => __( 'Poster Image', 'perch' ),
                'param_name' => 'image',
                'description' => '',
                'value' => '',
                'admin_label' => true,
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( '.mp4 format video', 'perch' ),
                'param_name' => 'mp4',
                'description' => '',
                'value' => '',
                'admin_label' => true,
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( '.webm format video', 'perch' ),
                'param_name' => 'webm',
                'description' => '',
                'value' => '',
                'admin_label' => true,
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( '.ogv format video', 'perch' ),
                'param_name' => 'ogv',
                'description' => '',
                'value' => '',
                'admin_label' => true, 
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'loop',  
                'heading' => __( 'Loop', 'perch' ),              
                'value' => array( __( 'Checked to enable video loop', 'landpick' ) => 'yes' ), 
                'std' => 'yes',
                'group' => 'Video player Settings',
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'overlay', 
                'heading' => __( 'Overlay', 'perch' ),               
                'value' => array( __( 'Checked to enable video overlay', 'landpick' ) => 'yes' ), 
                'std' => 'yes',
                'group' => 'Video player Settings',
                'edit_field_class' => 'vc_col-sm-6',
            ), 
            array(
                'type' => 'dropdown',
                'param_name' => 'overlay_color', 
                'heading' => __( 'Overlay color', 'perch' ),               
                'value' => perch_vc_global_color_options(),
                'std' => 'dark',
                'group' => 'Video player Settings',
                'edit_field_class' => 'vc_col-sm-6',
                'dependency' => array(
                    'element' => 'overlay',
                    'value' => 'yes'
                )
            ),
            array(
                'type' => 'textfield',
                'param_name' => 'overlay_alpha',    
                'heading' => __( 'Overlay opacity', 'perch' ),             
                'value' => '0.7',
                'description' => 'Min value 0, Max value 1',
                'group' => 'Video player Settings',
                'edit_field_class' => 'vc_col-sm-6',
                'dependency' => array(
                    'element' => 'overlay',
                    'value' => 'yes'
                )
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'resizing',  
                'heading' => __( 'Resizing', 'perch' ),              
                'value' => array( __( 'Video resizing', 'landpick' ) => 'yes' ), 
                'std' => 'yes',
                'group' => 'Video player Settings',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Volume', 'perch' ),
                'param_name' => 'volume',                
                'value' => array( __( 'Checked to enable video volume', 'landpick' ) => 'yes' ), 
                'std' => '',
                'group' => 'Video player Settings',
                'edit_field_class' => 'vc_col-sm-6',
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'muted',  
                'heading' => __( 'Muted', 'perch' ),              
                'value' => array( __( 'Checked to enable video muted', 'landpick' ) => 'yes' ), 
                'std' => '',
                'group' => 'Video player Settings',
                'edit_field_class' => 'vc_col-sm-6',
            ),            
                        
        );
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_video_bg_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

             

        // Used for periodic animation
        
        extract($atts);


        $muted = ($muted == 'yes')? '1' : '0';
        $volume = ($volume == 'yes')? '1' : '0';
        $loop = ($loop == 'yes')? '1' : '0';
        $overlay = ($overlay == 'yes')? '1' : '0';
        $colors = perch_default_color_classes();
        $overlay_color = isset($colors[$overlay_color])? $colors[$overlay_color]['value'] : '#000';
        // Fill $html var with data
        $html ='<div class="perch-video-play" 
                data-section_id="hero-9" 
                data-class="hero-section bg-video" 
                data-poster="'.esc_url($image).'" 
                data-webm="'.esc_url($webm).'" 
                data-ogv="'.esc_url($ogv).'" 
                data-mp4="'.esc_url($mp4).'"
                data-volume="'.esc_attr($volume).'"
                data-muted="'.esc_attr($muted).'"
                data-loop="'.esc_attr($loop).'"
                data-overlay="'.esc_attr($overlay).'"
                data-overlay_color="'.esc_attr($overlay_color).'"
                data-overlay_alpha="'.esc_attr($overlay_alpha).'">
            </div>  <!-- END Video background -->';


        wp_enqueue_script( 'vidbg' );  
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_video_bg_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_video_bg_output($atts);
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
                'description' => __('Video will play on dekstops and will resort to the fallback image on mobile devices', 'perch'),                 
                'params' => $params,                       
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
            );
        
        vc_map( $vc_map );
        vc_map( $vc_map_slide );
        
    }
    
    
     
} // End Element Class 
 
// Element Class Init
new PerchVideoBG();