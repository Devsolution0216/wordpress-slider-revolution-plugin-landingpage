<?php

/*
Element Description: Testimonial
*/
 
// Element Class 
class PerchTestimonial extends PerchVcMap {
     
    private $base = 'perch_testimonial';

    private $title = 'Testimonial';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_testimonial_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_testimonial_slide_output' ), 30, 2 ); 
    }

    private function testimonial_image(){
        $array = array(
            array(
                'type' => 'image_upload',
                'heading' => __( 'Reviewer Image', 'perch' ),
                'param_name' => 'image',
                'value' => get_template_directory_uri(). '/images/review-author-1.jpg', 
            ),
        );

        return $array;
    }

    private function testimonial_rating(){
        $array = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Choose Rating', 'perch' ),
                'param_name' => 'review',                
                'value' => array(                    
                    'None' => '',
                    '5 out of 5' => 'star:star:star:star:star',                                  
                    '4.5 out of 5' => 'star:star:star:star:star-half',                                  
                    '4 out of 5' => 'star:star:star:star',                                  
                    '3.5 out of 5' => 'star:star:star:star-half',                                  
                    '3 out of 5' => 'star:star:star',                                  
                    '2.5 out of 5' => 'star:star:star-half',                                  
                    '2 out of 5' => 'star:star',                                  
                    '1.5 out of 5' => 'star:star-half',                                  
                    '1 out of 5' => 'star',                                  
                ),
            )
        );

        return $array;
    }

   

    // Title element mapping
    private function vc_map_args(){        

        $array = self::element_align_args();
        $array[] = array(
                'type' => 'dropdown',
                'heading' => __( 'Display as', 'perch' ),
                'param_name' => 'display_as',
                'value' => array(                    
                    'Review style 1' => 'review-1',
                    'Review style 2' => 'review-2',
                    'Review style 3' => 'review-3',                
                ),
            ); 
        $array = array_merge($array, self::testimonial_image());
        $array = array_merge($array, self::testimonial_rating());
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('title', 'Title'));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('name', 'Reviewer Name'));
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('info', 'Reviewer info'));        
        $array = array_merge($array, PerchVcMap::_vc_map_input_field_group('subtitle', 'Review description',true, array('textarea' => true)));
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_testimonial_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);

        extract($atts);
        $rating_html = '';
        $info =  perch_modules_get_vc_param_html('info', $atts, $map_arr );
        $title_html =  perch_modules_get_vc_param_html('name', $atts, $map_arr );
        $title2_html =  perch_modules_get_vc_param_html('title', $atts, $map_arr );
        $subtitle_html =  perch_modules_get_vc_param_html('subtitle', $atts, $map_arr );
        $image_html = '<div class="testimonial-avatar"><img src="'.esc_attr($image).'" alt="'.esc_attr($title).'"></div><!-- Author Avatar -->';
        $image_html2 = '<img src="'.esc_attr($image).'" alt="'.esc_attr($title).'"><!-- Author Avatar -->';
        if( $review != ''){
            $reviewArr = explode(':', $review);
           $rating_html = '<div class="app-rating rating">
                                <i class="fa fa-'.implode('"></i><i class="fas fa-', $reviewArr).'"></i>
                            </div><!-- App Rating -->'; 
        }

        // Fill $html var with data
        if( $display_as == 'review-3' ){
            $html = '<div '. implode( ' ', $wrapper_attributes ).'>
               '.$subtitle_html.'           
                <div class="author-data clearfix d-flex align-items-center">
                    '.$image_html.'                
                    <div class="review-author">
                        '.$title_html.' 
                        '.$info.' 
                        '.$rating_html.'                  
                    </div><!-- Author Data -->
                </div>  <!-- End Testimonial Author -->         
            </div>  <!-- END review style 3-->'."\n";
        }elseif( $display_as == 'review-2' ){
            $html = '
            <div '. implode( ' ', $wrapper_attributes ).'>
                <div class="review-txt">
                    <div class="author-data clearfix">
                        <div class="testimonial-avatar">
                            '.$image_html.'
                        </div>
                        <div class="review-author">
                            '.$title_html.'
                            '.$info.'
                            '.$rating_html.' 
                        </div>

                    </div>  <!-- End Testimonial Author --> 
                                            
                    <!-- Testimonial Text -->
                    '.$subtitle_html.'                                    
                                            
                </div>                      
            </div><!-- END review style 2-->'."\n"; 
        }elseif( $display_as == 'review-1' ){
            $html = '
            <div '. implode( ' ', $wrapper_attributes ).'>
                <div class="review-txt">
                    <div class="author-data clearfix">
                        <div class="testimonial-avatar">
                            '.$image_html.'
                        </div>
                        <!-- Author Data -->
                        <div class="review-author">
                            '.$title_html.'
                            '.$info.'
                            '.$rating_html.' 
                        </div>

                    </div>  <!-- End Testimonial Author -->
                    <!-- Testimonial Text -->
                    '.$subtitle_html.'                                    
                                            
                </div>                      
            </div><!-- END review style 1-->'."\n"; 
        }else{

            $html = '<div '. implode( ' ', $wrapper_attributes ).'>
                <div class="review-txt">
                    '.$title2_html.'
                    '.$subtitle_html.'
                </div>  <!-- Testimonial Text -->
                
                <div class="review-author clearfix">
                    '.$image_html2.'
                    '.$title_html.'
                    '.$info.'
                </div><!-- Testimonial Author -->
                '.$rating_html.'
            </div>  <!-- END review style 1-->'."\n"; 

        }  

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'title_html' => $title_html,
            'rating_html' => $rating_html,
            'info' => $info,
            'subtitle_html' => $subtitle_html,
            'single_image' => $image_html2,
            'image_html' => $image_html,
            'review_title' => $title2_html,
        ); 

        $html = apply_filters('perch_modules/testimonial/output', $html, $html_args, $atts);

        PerchVcMap::periodic_animation_end();     
         
        return force_balance_tags($html);
         
    }

    public function perch_testimonial_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_testimonial_output($atts);
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
new PerchTestimonial();