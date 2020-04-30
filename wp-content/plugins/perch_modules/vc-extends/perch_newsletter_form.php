<?php

/*
Element Description: Newsletter form
*/
 
// Element Class 
class PerchNewsletterForm extends PerchVcMap {
     
    private $base = 'perch_newsletter_form';

    private $title = 'Newsletter form';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_newsletter_form_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_newsletter_form_slide_output' ), 30, 2 ); 
    }

    // Title element mapping
    private function vc_map_args(){        

        $array = self::element_align_args(); 
        $array = array_merge( $array, self::enable_input_fields( 'name', 'Name field' ) ); 
        

        $array = array_merge( $array, self::email_placeholder() );
        $array = array_merge( $array, PerchVcMapButtons::text_button_param() );
        $array = array_merge( $array, self::enable_form_requirement() );
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Footer text',true, array('textarea' => true)));
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_newsletter_form_output( $atts, $content = NULL ) {
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
            '.self::form_shortcode_html($atts).'  
            '.self::form_requirement_html($atts).'
            '.perch_modules_get_vc_param_html('subtitle', $atts, $map_arr ).'
           
        </div>';

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'form_shortcode' => self::form_shortcode_html($atts),
            'form_requirement' => self::form_requirement_html($atts),
            'subtitle' => perch_modules_get_vc_param_html('subtitle', $atts, $map_arr ),
        );     

        $html = apply_filters('perch_modules/newsletter_form/output', $html, $html_args, $atts); 

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_newsletter_form_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_newsletter_form_output($atts);
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
                'icon' => perch_shortcodes_vc_class(),
                'category' => perch_shortcodes_vc_category(),
                'base' => $this->base,
                'name' => $this->title,                
                'description' => __('Display newsletter form', 'perch'),                 
                'params' => $params,
                'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',  
                'admin_enqueue_js' =>   array( PERCH_MODULES_URI. '/assets/js/vc-admin-scripts.js'),       
            ); 
        // slide element
        $vc_map_slide = array(
                'class' => perch_shortcodes_vc_class(),
                'icon' => perch_shortcodes_vc_class(),
                'category' => perch_shortcodes_vc_category(),
                'base' => $this->base.'_slide',
                'name' => $this->title,                
                'description' => __('Display newsletter form', 'perch'),                 
                'as_child'  => array('only' => 'perch_vc_carousel'),           
                'params' => $params,
                'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',        
            );
        
        vc_map( $vc_map );
        vc_map( $vc_map_slide );
        
    }


    public function email_placeholder(){
        $array = array(                
                array(
                     'type' => 'textfield',
                    'value' => 'Your email address*',
                    'heading' => 'Email placeholder',
                    'param_name' => 'email',               
                    'admin_label' => true,                
                ), 
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable inline form', 'perch' ),
                    'param_name' => 'inline_form',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                    'edit_field_class' => 'vc_col-sm-9',
                ), 
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Checked to enable text button', 'perch' ),
                    'param_name' => 'form_button_style',
                    'std' => '',
                    'value' => array( __( 'Yes', 'perch' ) => 'text_button' ),  
                    'dependency' => array( 'element' => 'enable_name', 'value_not_equal_to' => 'yes', ),  
                    'edit_field_class' => 'vc_col-sm-3',                  
                ),
            );  
        return $array;
    }

    public function enable_form_requirement(){
        $array = array(
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable form requirement?', 'perch' ),
                    'param_name' => 'enable_form_requirement',
                    'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                    'admin_label' => true,
                ),
                array(
                    'type' => 'exploded_textarea',                  
                    'param_name' => 'form_requirement',     
                    'value' => 'No credit card required,* <a href="#">See FAQ</a> for more details,<a href="#">Privacy Policy</a>',                      
                    'dependency' => array( 'element' => 'enable_form_requirement', 'value' => 'yes', ),                 
                ),  
            );  
        return $array;
    }

    public function form_requirement_html($atts){
        $output = $enable_form_requirement = '';
        extract($atts);

        if( $enable_form_requirement == 'yes' ){
            $classes = array('hero-links');
            $classes = self::periodic_getCSSAnimation($classes, 'form_requirement', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = self::periodic_wrapperAttributes($wrapper_attributes, 'form_requirement', $atts );

            $requirement_html = ($form_requirement != '')?'<span>'.str_replace( ',', '</span><span>', $form_requirement ).'</span>' : '';

            $output = '<div '. implode( ' ', $wrapper_attributes ).'>                
               '.$requirement_html.'
            </div>';
            
            
        } 
        return $output; 
    }

    public function form_shortcode_html($atts){
        $inline_form = '';
        if ( ! is_array( $atts ) ) {
            return '';
        }

        $atts['es_desc'] = '';
        $atts['es_group'] = 'Public';



       
        $atts['es_desc']  = trim($atts['es_desc']);
        $atts['es_group'] = trim($atts['es_group']);
        $atts['es_pre'] = 'shortcode';
        if(defined('ES_PLUGIN_VERSION')): 
            extract($atts);
            if( ($inline_form == 'yes') && ($enable_name == 'yes') ){
                return PerchNewsletter::es_get_form_horizontal($atts);
            }if( $inline_form == 'yes' ){
                $atts['form_button_style'] = 'text_button';
                $atts['form_wrapper_class'] = 'mb-30';   
                $atts['button_field_before'] =   '<span class="input-group-btn">';           
                $atts['button_field_after'] =   '</span>';           
                return PerchNewsletter::es_get_form_simple($atts);
            }else{              
                return PerchNewsletter::es_get_form($atts);
            }
        else:
            return 'Email Subscribers plugin is not activated.';
        endif;
               
    }
    
    
     
} // End Element Class 
 
// Element Class Init
new PerchNewsletterForm();