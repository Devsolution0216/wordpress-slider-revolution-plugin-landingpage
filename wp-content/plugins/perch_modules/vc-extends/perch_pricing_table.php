<?php
/*
Element Description: Pricing table
*/
 
// Element Class 
class PerchPricingTable extends PerchVcMap {
     
    private $base = 'perch_pricing_table';

    private $title = 'Pricing table';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_pricing_table_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_pricing_table_slide_output' ), 30, 2 ); 
    }

    private function pricing_args(){
        $array = array(
            array(
                 'type' => 'textfield',
                'value' => '$',
                'heading' => 'Price unit',
                'param_name' => 'unit',               
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-3' 
            ),  
            array(
                 'type' => 'textfield',
                'value' => '13',
                'heading' => 'Price',
                'param_name' => 'price',               
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-5' 
            ), 
            array(
                 'type' => 'textfield',
                'value' => '99',
                'heading' => 'Price coins',
                'param_name' => 'coins',               
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-4' 
            ),  
        );

        return $array;
    }


    private function pricing_feature_list(){
        $array = array(
            array(
                 'type' => 'exploded_textarea',
                'value' => '50 Users Tasks,500 GB in Cloud Storage,25 mySQL Database,12/7 Premium Support',
                'heading' => 'Content list',
                'param_name' => 'content',
                'admin_label' => true 
            )
        );

        return $array;
    }

    private function pricing_button(){
        $array = array(
            array(
                 'type' => 'textfield',
                'value' => 'Get Started Now',
                'heading' => 'Button title',
                'param_name' => 'button_text',               
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-4' 
            ), 
            array(
                'type' => 'textfield',
                'value' => '#',
                'heading' => 'Button URL',
                'param_name' => 'button_url',
                'edit_field_class' => 'vc_col-sm-8' 
            ),           
            array(
                'type' => 'dropdown',
                'heading' => __( 'Button style', 'perch' ),
                'param_name' => 'button_style',
                'description' => __( 'Select button color.', 'perch' ),
                // compatible with btn2, need to be converted from btn1
                'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                
                'value' => array_flip(PerchVcMap::btn_style_options(true)),
                'std' => 'btn-coral',                
            )
        );

        return $array;
    }
   

    // Title element mapping
    private function vc_map_args(){ 
               

        $array = self::element_align_args(); 
        $array = array_merge($array, self::enable_logo_image() );         
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title'));                
        $array = array_merge($array, $this->pricing_args());
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('validity', 'Validity'));
        $array = array_merge( $array, self::enable_input_fields( 'vat', 'Pricing VAT' ) );    
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('leadtext', 'Description',true, array('textarea' => true)));
        $array = array_merge($array, $this->pricing_feature_list());        
        $array = array_merge($array, $this->pricing_button());        
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_pricing_table_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );       

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);

        

        extract($atts);
        $image_html = self::logo_image_html($atts);
        $price_html = '<sup>'.esc_attr($unit).'</sup><span class="price">'.esc_attr($price).'</span><sup class="pricing-coins">'.esc_attr($coins).'</sup>';
        $feture_list_html = ($content != '')?'<ul class="features steelblue-color"><li><i class="fas fa-stop-circle"></i>'.str_replace( ',', '</li><li><i class="fas fa-stop-circle"></i>', $content ).'</li></ul>' : '';

        $pricing_title = perch_modules_get_vc_param_html('title', $atts, $map_arr );
        $button_html = PerchVcMapButtons::single_button_html($atts);
        $leadtext_html = perch_modules_get_vc_param_html('leadtext', $atts, $map_arr );
        $validity_html = perch_modules_get_vc_param_html('validity', $atts, $map_arr );
        $vat_html = self::input_fields_html( 'vat', $atts, 'price-vat', 'tag:p', 'span' );

        $image_html = ($image_html != '')? '<div class="pricing-coins">'.$image_html.'</div>' : '';
        $leadtext_html = ( $leadtext_html != '' )? '<div class="pricing-text">'.$leadtext_html.'</div>' : '';
      
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'>
            <div class="pricing-table">
                '.$image_html.'
                <div class="pricing-plan steelblue-color">
                    '.$pricing_title.'
                    '.$price_html.'   
                    '.$validity_html.'
                    '.$vat_html.' 
                </div>    
                '.$leadtext_html.' 
                        
                '.$feture_list_html.'                 
                '.$button_html.'           
            </div>       
        </div>';

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'image_html' => $image_html,
            'pricing_title' => $pricing_title,
            'leadtext_html' => $leadtext_html,
            'price_html' => $price_html,
            'validity_html' => $validity_html,
            'vat_html' => $vat_html,
            'feture_list_html' => $feture_list_html,
            'button_html' => $button_html,
        ); 

        $html = apply_filters('perch_modules/pricing_table/output', $html, $html_args, $atts);

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_pricing_table_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_pricing_table_output($atts);
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
new PerchPricingTable();