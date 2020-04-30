<?php

/*
Element Description: VC Title area
*/
 
// Element Class 
class PerchPostsTemplate extends PerchVcMap {
     
    private $base = 'perch_posts_template';

    private $title = 'Posts template';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_posts_template_output' ), 30, 2 ); 
        add_filter( $this->base.'_slide', array( $this, 'perch_posts_template_slide_output' ), 30, 2 ); 
    }

    public function posts_template_args(){
        $array = array(
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Display type', 'perch' ),
                'param_name' => 'template',
                'value' => array(
                     'Default' => 'templates/default-loop.php',
                     'Carousel' => 'templates/carousel-loop.php',

                ),
                'std' => 'templates/default-loop.php',
                'admin_label' => true 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Image size', 'perch' ),
                'param_name' => 'img_size',
                'value' => array_flip( perch_get_image_sizes_Arr() ),
                'description' => __( 'Choose image size', 'perch' ),
                'std' => 'perch-600x600-crop' 
            ),
            array(
                 'type' => 'number',
                'heading' => __( 'Excerpt length', 'perch' ),
                'param_name' => 'excerpt_length',
                'value' => '18',
                'min' => '0',
                'max' => '100',
                'step' => '1',
                'description' => 'Specify number of posts excerpt length that you want to show. Enter 0 to hide excerpt',
                'admin_label' => true 
            ),
            array(
                 'type' => 'number',
                'heading' => __( 'Posts per page', 'perch' ),
                'param_name' => 'posts_per_page',
                'value' => '3',
                'min' => '-1',
                'max' => '100',
                'step' => '1',
                'description' => 'Specify number of posts that you want to show. Enter -1 to get all posts',
                'admin_label' => true 
            ),
            array(
                'type' => 'number',
                'heading' => __( 'Column', 'perch' ),
                'param_name' => 'column',
                'value' => '3',
                'min' => '2',
                'max' => '4',
                'step' => '1',                
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Autoplay', 'perch' ),
                'param_name' => 'autoplay',
                'std' => 'no',
                'value' => array(
                     'Yes' => 'yes',
                    'No' => 'no' 
                ),
                'dependency' => array(
                    'element'   => 'template',
                    'value'     => array( 'templates/carousel-loop.php' ) 
                ) 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Dots', 'perch' ),
                'param_name' => 'dots',
                'std' => 'no',
                'value' => array(
                     'Yes' => 'yes',
                    'No' => 'no' 
                ),
                'dependency' => array(
                    'element'   => 'template',
                    'value'     => array( 'templates/carousel-loop.php' ) 
                ) 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Navs', 'perch' ),
                'param_name' => 'navs',
                'std' => 'no',
                'value' => array(
                     'Yes' => 'yes',
                    'No' => 'no' 
                ),
                'dependency' => array(
                    'element'   => 'template',
                    'value'     => array( 'templates/carousel-loop.php' ) 
                ) 
            ),            
        );

        return $array;
    }

    public function posts_query_args(){
        $array = array(
            array(
                 'type' => 'textfield',
                'heading' => __( 'Post ID\'s', 'perch' ),
                'param_name' => 'id',
                'value' => '',
                'description' => 'Enter comma separated ID\'s of the posts that you want to show',
                'group' => 'Posts Settings' 
            ),
            array(
                 'type' => 'perch_select',
                'multiple' => 'multiple',
                'heading' => __( 'Select category', 'perch' ),
                'param_name' => 'tax_term',
                'value' => perch_get_terms(),
                'group' => 'Posts Settings' 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Taxonomy term operator', 'perch' ),
                'param_name' => 'tax_operator',
                'description' => 'IN - posts that have any of selected categories terms<br/>NOT IN - posts that is does not have any of selected terms<br/>AND - posts that have all selected terms',
                'value' => array(
                     'IN' => 'IN',
                    'NOT IN' => 'NOT IN',
                    'AND' => 'AND' 
                ),
                'group' => 'Posts Settings' 
            ),
            array(
                 'type' => 'textfield',
                'heading' => __( 'Authors', 'perch' ),
                'param_name' => 'author',
                'description' => 'Enter here comma-separated list of author\'s IDs. Example: 1,7,18',
                'group' => 'Posts Settings' 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Order', 'perch' ),
                'param_name' => 'order',
                'description' => 'Posts order',
                'value' => array_flip( array(
                     'desc' => __( 'Descending', 'perch' ),
                    'asc' => __( 'Ascending', 'perch' ) 
                ) ),
                'std' => 'desc',
                'group' => 'Posts Settings' 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Order by', 'perch' ),
                'param_name' => 'orderby',
                'description' => 'Order posts by',
                'std' => 'date',
                'value' => array_flip( array(
                     'none' => __( 'None', 'perch' ),
                    'id' => __( 'Post ID', 'perch' ),
                    'author' => __( 'Post author', 'perch' ),
                    'title' => __( 'Post title', 'perch' ),
                    'name' => __( 'Post slug', 'perch' ),
                    'date' => __( 'Date', 'perch' ),
                    'modified' => __( 'Last modified date', 'perch' ),
                    'parent' => __( 'Post parent', 'perch' ),
                    'rand' => __( 'Random', 'perch' ),
                    'comment_count' => __( 'Comments number', 'perch' ),
                    'menu_order' => __( 'Menu order', 'perch' ),
                    'meta_value' => __( 'Meta key values', 'perch' ) 
                ) ),
                'group' => 'Posts Settings' 
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Ignore sticky', 'perch' ),
                'param_name' => 'ignore_sticky_posts',
                'description' => 'Select Yes to ignore posts that is sticked',
                'value' => array_flip( array(
                     'no' => __( 'No', 'perch' ),
                    'yes' => __( 'Yes', 'perch' ) 
                ) ),
                'group' => 'Posts Settings' 
            )
        );

        return $array;
    }

    // Title element mapping
    private function vc_map_args(){        

        $array = self::element_align_args();        
               
               
        $array = array_merge($array, self::posts_template_args() );        
        $array = array_merge($array, self::enable_hero_button_group() );
        $array = array_merge($array, self::posts_query_args() );
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_posts_template_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );        

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);

        $atts['info'] = $atts;
      
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'>
            '.perch_posts_template($atts).'
            '.self::buttons_html($atts).'      
        </div>';

        $html_args = array(
            'wrapper_attributes' => $wrapper_attributes,
            'posts_template' => perch_posts_template($atts),         
            'buttons_html' => self::buttons_html($atts),         
        ); 

        $html = apply_filters('perch_modules/posts_template/output', $html, $html_args, $atts); 

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_posts_template_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_posts_template_output($atts);
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
                /*'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',  
                'admin_enqueue_js' =>   array( PERCH_MODULES_URI. '/assets/js/vc-admin-scripts.js'),    */   
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
                /*'js_view' => 'PerchVcElementPreview',
                'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>', */       
            );
        
        vc_map( $vc_map );
        vc_map( $vc_map_slide );
        
    }
    
    
     
} // End Element Class 
 
// Element Class Init
new PerchPostsTemplate();