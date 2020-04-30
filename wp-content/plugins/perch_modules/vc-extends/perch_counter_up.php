<?php
/*
Element Description: VC Title area
*/
 
// Element Class 
class PerchCounterUp extends PerchVcMap {
     
    private $base = 'perch_counter_up';

    private $title = 'Statistic block';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_counter_up_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_counter_up_slide_output' ), 30, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        

        $array = self::element_align_args();
        $array = array_merge($array, PerchVcMapIcons::icon_args());
        $args = array(
            array(
                 'type' => 'textfield',
                'value' => '3,',
                'heading' => 'Count Prefix',
                'param_name' => 'prefix',                
                'edit_field_class' => 'vc_col-sm-3',
                'admin_label' => true 
            ),
            array(
                 'type' => 'textfield',
                'value' => '438',
                'heading' => 'Count',
                'param_name' => 'count', 
                'description' => 'Number only',               
                'edit_field_class' => 'vc_col-sm-6',
                'admin_label' => true 
            ),
            array(
                 'type' => 'textfield',
                'value' => '',
                'heading' => 'Count Postfix',
                'param_name' => 'postfix',                
                'edit_field_class' => 'vc_col-sm-3',
                'admin_label' => true 
            ),
            self::element_group_typography('counter_typo', 'Counter typography', 'tag:p|extra_class:statistic-number txt-700' ),
        );
        $array = array_merge($array, $args);
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title'));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Sub-Title',true, array('textarea' => true)));
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_counter_up_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );       

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);

        

        extract($atts);
        
        $icon_html = PerchVcMapIcons::icon_html($atts);
        $counter_html = esc_attr($atts['prefix']).'<span class="count-element">'.intval($atts['count']).'</span>'.esc_attr($atts['postfix']);

        $counter_html = perch_generate_input_field_html_by_settings($counter_html, $counter_typo, $atts);
        $title_html = perch_modules_get_vc_param_html('title', $atts, $map_arr );
        $subtitle_html = perch_modules_get_vc_param_html('subtitle', $atts, $map_arr );
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'> 
                '.$icon_html.'          
                '.$counter_html.'                
                '.$title_html.'
                '.$subtitle_html.'                
        </div>'; 

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'icon_html' => $icon_html,         
            'counter_html' => $counter_html,         
            'title_html' => $title_html,         
            'subtitle_html' => $subtitle_html,
        ); 

        $html = apply_filters('perch_modules/counter_up/output', $html, $html_args, $atts);

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_counter_up_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_counter_up_output($atts);
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
                'description' => __('Display counter up with title & subtitle', 'perch'),                 
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
new PerchCounterUp();