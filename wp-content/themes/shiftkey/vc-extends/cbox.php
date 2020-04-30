<?php
if(class_exists('PerchVcMap')):
/*
Element Description: VC Title area
*/

//default values setup
add_filter( 'perch_modules/vc/perch_cbox', 'shiftkey_vc_cbox_default_args' );
function shiftkey_vc_cbox_default_args( $args ) {
    $default = array(
        'icon_type' => 'fontawesome',
        'icon_fontawesome' => 'far fa-calendar-check',
        'title' => 'Scheduling and Planning',
        'title_font_container' => 'tag:h5|size:sm',
        'subtitle' => 'An enim nullam tempor sapien gravida donec blandit ipsum porta justo integer odio velna vitae auctor integer congue magna ',
        'subtitle_font_container' => 'tag:p|text_color:grey-color',
        'el_class' =>'pc-30',
    );
   
    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args; 
}

// Element Class 
class ShiftkeyCbox extends PerchVcMap {
     
    private $base = 'perch_cbox';

    private $title = 'Cbox';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_feature_box_output' ), 20, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_feature_box_slide_output' ), 20, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        
        $array = self::element_align_args();
        $array[] = array(
                'type' => 'dropdown',
                'heading' => esc_attr__( 'Display as', 'shiftkey' ),
                'param_name' => 'display_as',
                'value' => array(
                    'Cbox style 2' => 'cbox-2',
                    'Cbox style 3' => 'cbox-3',
                               
                ),
            );  

        $icon_args = PerchVcMapIcons::icon_args();
        $array = array_merge($array, $icon_args);
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title'));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Sub-Title',true, array('textarea' => true)));
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_feature_box_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);

        $icon_html = PerchVcMapIcons::icon_html($atts);

        extract($atts);
        $feature_common_class = apply_filters( 'perch_modules/'.$this->base.'/common_class', '' );       
        $icon_html .= ($display_as ==  $feature_common_class.'-2')? '<div class="box-line"></div>' : '';
        $title_html = perch_modules_get_vc_param_html('title', $atts, $map_arr );
        $subtitle_html = perch_modules_get_vc_param_html('subtitle', $atts, $map_arr );

      
        // Fill $html var with data        
       if( $display_as == 'cbox-2' ){
        $html = '<div '. implode( ' ', $wrapper_attributes ).'>

                    <div class="'.$feature_common_class.' '.$icon_color.'-cbox-2 icon-xs">
                        <div class="cbox-2-title clearfix">                      
                           <!-- Icon & Title -->
                            '.$icon_html.'
                            '.$title_html.'
                        </div>
                            <!-- Text -->
                            '.$subtitle_html.'
                    </div>
                </div>';
       }elseif ($display_as == 'cbox-3') {
           $html = '<div '. implode( ' ', $wrapper_attributes ).'>

                  <div class="'.$feature_common_class.' '.$icon_color.'-cbox-3 icon-xs mb-10">                         
                       <!-- Icon & Title -->
                        '.$icon_html.'
                       <div class="cbox-3-txt">
                           '.$title_html.'
                           <!-- Text -->
                           '.$subtitle_html.'
                       </div>
                   </div>
           </div>';
       }
      
        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_feature_box_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_feature_box_output($atts);
        $html .='</div>';

        return $html;
    }


    // Element Mapping
    public function vc_mapping( $return = false ) {
        $params = $this->vc_map_args();
        if($return) {
            return $params;  
        }        
       $name = apply_filters( 'perch_modules/'.$this->base.'/name', $this->title );
       $vc_map = array(
                'class' => perch_shortcodes_vc_class(),
                'category' => perch_shortcodes_vc_category(),
                'base' => $this->base,
                'name' => $name,                
                'description' => esc_attr__('Display title & subtitle', 'shiftkey'),                 
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
                'name' => $name,                
                'description' => esc_attr__('Display title & subtitle', 'shiftkey'),                 
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
new ShiftkeyCbox();
endif;