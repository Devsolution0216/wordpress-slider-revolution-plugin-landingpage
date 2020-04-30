<?php

/*
Element Description: List
*/
 
// Element Class 
class PerchCboxs extends PerchVcMap {
     
    private $base = 'perch_cboxs';

    private $title = 'Content box group';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_cbox_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_cbox_slide_output' ), 30, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        
        $array = array();        
        
        $array = array_merge($array, self::cbox_group_args());
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_cbox_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );               

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);
      
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'>                      
            '.self::cbox_group_html($atts).'     
        </div>'; 

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'group_html' => self::cbox_group_html($atts)         
        ); 

        $html = apply_filters('perch_modules/cboxs/output', $html, $html_args, $atts);

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_cbox_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_cbox_output($atts);
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

    public static function cbox_args(){
        $icon_args = PerchVcMapIcons::icon_args();
        $array = array_merge($icon_args, array(                
                array(
                    'type' => 'textarea',
                    'heading' => __( 'List title', 'perch' ),
                    'param_name' => 'title',
                    'description' => '',
                    'value' => 'The easy element for creativity & design',
                    'admin_label' => true 
                ),
                 array(
                    'type' => 'textarea',
                    'heading' => __( 'Description', 'perch' ),
                    'param_name' => 'subtitle',
                    'description' => '',
                    'value' => '',
                ),
            ));

        

        $array = apply_filters( 'perch/accordian_group_args', $array );

        return $array;
    }

    public static function cbox_group_args(){
        $array = array(
            array(
                'type' => 'dropdown',
                'heading' => 'Bottom spacing',
                'param_name' => 'li_spacing_bottom',
                'std' => '',
                'value' => array_merge(array('Inherit' => ''), perch_vc_dropdown_options(0, 150, 5, 'px', 'mb-')),                
                'description' => 'List item spacing in bottom'
            ),
            array(
                'type' => 'param_group',
                'save_always' => true,
                'heading' => __( 'List Group', 'perch' ),
                'param_name' => 'list_group',                
                'value' => urlencode( json_encode( array(
                    array(
                        'icon_type' => 'flaticon',
                        'icon_color' => 'preset',
                        'icon_flaticon' => 'flaticon-056-settings-1',
                        'icon_size' => 'sm',
                         'title' => 'Real Time Customization',
                         'subtitle' => 'Real time customization available in frontend & backend. You can edit element tag, class, color etc. You can also enable google fonts for each element.',
                    ),
                ) ) ),
                'params' => self::cbox_args()
            ),
            PerchVcMapListGroup::list_group_typography('title', 'List title', 'tag:h5|size:sm'),
            PerchVcMapListGroup::list_group_typography('subtitle', 'List Sub-Title', 'tag:p'),
        );

        

        $array = apply_filters( 'perch/accordian_group_args', $array );

        return $array;
    }

    public static function cbox_html($value){
        $map_arr = self::cbox_group_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr);      
        
        $value = shortcode_atts($value, $args);
        $_atts = $typo_atts = $value;
        $typo_atts['periodic_animation'] = '';
        $typo_atts['css_animation'] = '';
        extract($value);
      

        $output = '';

        $new_title = isset($value['title'])? $value['title'] : '';
        $new_subtitle = isset($value['subtitle'])? $value['subtitle'] : '';
        $typo_atts['title'] =  $new_title;                   
        $typo_atts['subtitle'] =  $new_subtitle; 
        $tag = 'div';
       

        if( ( $new_title != '') || ( $new_subtitle != '') ):                                      
            $li_attr = array();
            $classes = array('cbox', 'cbox-2', $li_spacing_bottom); 
            
            $classes = self::periodic_getCSSAnimation($classes, 'list_li', $_atts);               
            
           
            $classes = array_filter($classes);
            $li_attr[] = (!empty($classes))? 'class="'.implode(' ', $classes).'"' : ''; 
            $li_attr = self::periodic_wrapperAttributes($li_attr, 'list_li', $_atts );                   
            $li_attributes = implode( ' ', $li_attr );
            
            $icon_html = PerchVcMapIcons::icon_html($typo_atts);
            $title_html = perch_modules_get_vc_param_html( 'title', $typo_atts );                    
            $subtitle_html = perch_modules_get_vc_param_html('subtitle', $typo_atts);  
            
                         

            $output = "<{$tag} {$li_attributes}>
                {$icon_html}
                <div class='cbox-2-txt'>{$title_html}
                {$subtitle_html}
                </div>
            </{$tag}>";           
        endif;

        return $output;        
    }

    public static function cbox_group_html($atts){
        $_atts = $atts;       
        // typo attr
        $typo_atts = $atts;       

        extract($atts);      

        $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($list_group) : array();

       
        $classes = array('c8-boxes');          
        $classes = array_filter($classes);
        $wrapper_attributes[] = (!empty($classes))?'class="'.implode(' ', $classes).'"' : '';        
        $wrapper_attributes = array_filter($wrapper_attributes);  
        $wrapper_attributes = apply_filters( 'perch_modules/wrapper_attributes', $wrapper_attributes, $atts);
  
        

        $output = '<div '.implode( ' ', $wrapper_attributes ).'>';
        if(!empty($paramsArr)):
            $delay = 200;
            $paramsArr = array_filter($paramsArr);
            $count = 1;            
            foreach ($paramsArr as $key => $value) {
                $output .= self::cbox_html(array_merge($atts, $value));
            }
        endif;
        $output .= '</div>';

        return $output;

    }
    
    
     
} // End Element Class 
 
// Element Class Init
new PerchCboxs();