<?php

/*
Element Description: VC Title area
*/
 
// Element Class 
class PerchVcTitleMap extends PerchVcMap {
     
    private $base = 'perch_vc_title';

    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_title_mapping' ) );

        add_filter( 'perch_vc_title', array( $this, 'vc_title_html' ), 30, 2 ); 
        add_filter( 'perch_vc_title_slide', array( $this, 'vc_title_slide_html' ), 30, 2 ); 
    }

    // Element Mapping
    public function vc_title_mapping( $return = false ) {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        $params = $this->title_map_args();  
        $params = apply_filters( 'perch/title_map_args', $params ); 
        if($return) {
            return $params;  
        }
       
       $vc_map_array = array(
                'class' => apply_filters( 'perch_modules/vc_class', 'perch-vc' ),
                'name' => __('Title area', 'perch'),
                'base' => apply_filters( 'perch_modules/vc_template_prefix', 'perch_' ).'vc_title',
                'description' => __('Display title & subtitle', 'perch'), 
                'category' => apply_filters( 'perch_modules/vc_category', 'Perch' ),   
                'icon' => PERCH_MODULES_DIR.'/images/app-logo-2.png',            
                'params' => $params,
                'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',  
                'admin_enqueue_js' =>   array( PERCH_MODULES_URI. '/assets/js/vc-admin-scripts.js'),       
            );

        $vc_map_array = apply_filters( 'perch/vc_map_array', $vc_map_array );
        // Map the block with vc_map()
        vc_map( $vc_map_array );    

        $vc_map_array = array(
                'class' => apply_filters( 'perch_modules/vc_class', 'perch-vc' ),
                'name' => __('Title area', 'perch'),
                'base' => apply_filters( 'perch_modules/vc_template_prefix', 'perch_' ).'vc_title_slide',
                'description' => __('Display title & subtitle', 'perch'), 
                'category' => apply_filters( 'perch_modules/vc_category', 'Perch' ), 
                'as_child'  => array('only' => 'perch_vc_carousel'),   
                'icon' => PERCH_MODULES_DIR.'/images/app-logo-2.png',            
                'params' => $params,
                'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',        
            );
        vc_map( $vc_map_array );    


        
    }

    public function title_default_settings(){
        $array = array(
            'google_font_settings' => true,
            'typo_std' => 'tag:h2|size:sm|extra_class:os-version',
                      
        );
        return $array;
    }

    public function subtitle_default_settings(){
        $array = array(
            'typo_settings' => true,
            'textarea' => true,
            'typo_std' => 'tag:p|size:md|extra_class:os-version',
        );
        return $array;
    }

    // Title element mapping
    public function title_map_args(){
        $title = self::title_default_settings();
        $subtitle = self::subtitle_default_settings();

        $array = PerchVcMap::element_align_args();  
        /*$new_args = array(
                 'type' => 'textfield',
                'heading' => __( 'Name', 'perch' ),
                'param_name' => 'name',
                'value' => 'Take control of the details',
                'perch_settings' => array(
                    'textarea' => false,
                    'typo_settings' => true,
                    'highlight_settings' => true,
                    'google_font_settings' => true,
                    'typo_std' => 'tag:span|extra_class:section-id',
                    
                )
            ); 
        $new_args = apply_filters('perch/vc_map_input_field_filter', $new_args);
        $array = array_merge($array, $new_args);*/ 
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title', false, $title));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Sub-Title', true, $subtitle));
        $array = array_merge($array, PerchVcMap::element_common_args());

        

        return $array;
    } 
     
     
    // Element HTML
    public function vc_title_html( $atts, $content = NULL ) {
        $map_arr = self::vc_title_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 


        // Params extraction
        $atts = shortcode_atts( $args, $atts );
        extract( $atts );    

       $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, perch_shortcode_custom_css_class( $css, ' ' ), $this->base , $atts );


        $classes = array(             
                $css_class, 
                $mtop, 
                $mbottom,
                $pleft,  
                $pright,
                PerchVcMap::getExtraClass( $el_class ), 
                PerchVcMap::getCSSAnimation( $css_animation, $atts ),
                // custom class
                $align,
                //$display_as,
            );       
        $classes = array_filter($classes);
        $classes = array_unique($classes);


      
        $wrapper_attributes = array();
        if ( ! empty( $el_id ) ) {
            $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
        }
        $wrapper_attributes[] = (!empty($classes))?'class="'.implode(' ', $classes).'"' : '';
        $wrapper_attributes = array_filter($wrapper_attributes);

        $wrapper_attributes = apply_filters( 'perch_modules/wrapper_attributes', $wrapper_attributes, $atts);

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);
      
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'> 
        '.perch_modules_get_vc_param_html('name', $atts, $map_arr ).'           
        '.perch_modules_get_vc_param_html('heading1', $atts, $map_arr ).'           
            '.perch_modules_get_vc_param_html('title', $atts, $map_arr ).'
            '.perch_modules_get_vc_param_html('subtitle', $atts, $map_arr ).'         
        </div>';

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'name' => perch_modules_get_vc_param_html('name', $atts, $map_arr ),
            'heading1' => perch_modules_get_vc_param_html('heading1', $atts, $map_arr ),
            'title' => perch_modules_get_vc_param_html('title', $atts, $map_arr ),
            'subtitle' => perch_modules_get_vc_param_html('subtitle', $atts, $map_arr ),
        );     

        $html = apply_filters('perch_modules/vc_title/output', $html, $html_args, $atts); 

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function vc_title_slide_html( $atts, $content = NULL ) {
        $map_arr = self::title_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::vc_title_html($atts);
        $html .='</div>';

        return $html;
    }
    
    
     
} // End Element Class
 
 
// Element Class Init
new PerchVcTitleMap();