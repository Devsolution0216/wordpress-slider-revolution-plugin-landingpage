<?php
function perch_shortcodes_prefix(){
    return apply_filters( 'perch_modules/vc_template_prefix', 'perch_' );
}

if(!function_exists('perch_posts_template')):

  function perch_posts_template( $atts, $content = null, $type = "posts" ) {
    // Prepare error var
    $error = null;
    // Parse attributes
    $atts  = shortcode_atts( array(
       'template' => 'templates/default-loop.php',
      'id' => false,
      'posts_per_page' => get_option( 'posts_per_page' ),
      'post_type' => 'post',
      'taxonomy' => 'category',
      'tax_term' => false,
      'tax_operator' => 'IN',
      'author' => '',
      'tag' => '',
      'meta_key' => '',
      'offset' => 0,
      'excerpt_length' => 18,
      'order' => 'DESC',
      'orderby' => 'date',
      'post_parent' => false,
      'post_status' => 'publish',
      'ignore_sticky_posts' => 'no',
      'autoplay' => 'no',
      'control' => 'yes',
      'column' => 4 ,
      'active' => '',
      'readmore_link' =>'no',
      'info' => '',
      'pagination' => 'yes',
      'all_posts_link_text' => 'See All posts'
    ), $atts, $type );    

    $original_atts = $atts;    

    $author              = sanitize_text_field( $atts['author'] );
    $id                  = $atts['id']; // Sanitized later as an array of integers
    $ignore_sticky_posts = ( bool ) ( $atts['ignore_sticky_posts'] === 'yes' ) ? true : false;
    $meta_key            = sanitize_text_field( $atts['meta_key'] );
    $offset              = intval( $atts['offset'] );
    $order               = sanitize_key( $atts['order'] );
    $orderby             = sanitize_key( $atts['orderby'] );
    $post_parent         = $atts['post_parent'];
    $post_status         = $atts['post_status'];
    $post_type           = sanitize_text_field( $atts['post_type'] );
    $posts_per_page      = intval( $atts['posts_per_page'] );
    $tag                 = sanitize_text_field( $atts['tag'] );
    $tax_operator        = $atts['tax_operator'];
    $tax_term            = sanitize_text_field( $atts['tax_term'] );
    $taxonomy            = sanitize_key( $atts['taxonomy'] );
    // Set up initial query for post

    $args                = array(
       'category_name' => '',
      'order' => $order,
      'orderby' => $orderby,
      'post_type' => $post_type,
      'posts_per_page' => $posts_per_page,
      'tag' => $tag 
    );

    // Ignore Sticky Posts

    if ( $ignore_sticky_posts )
      $args['ignore_sticky_posts'] = true;
    // Meta key (for ordering)

    if ( !empty( $meta_key ) )
      $args['meta_key'] = $meta_key;

    // If Post IDs
    if ( $id ) {
      $posts_in         = array_map( 'intval', explode( ',', $id ) );
      $args['post__in'] = $posts_in;
    } //$id

    // Post Author
    if ( !empty( $author ) )
      $args['author'] = $author;

    // Offset
    if ( !empty( $offset ) )
      $args['offset'] = $offset;

    // Post Status
    $post_status = explode( ', ', $post_status );
    $validated   = array();
    $available   = array(
       'publish',
      'pending',
      'draft',
      'auto-draft',
      'future',
      'private',
      'inherit',
      'trash',
      'any' 
    );

    foreach ( $post_status as $unvalidated ) {
      if ( in_array( $unvalidated, $available ) )
        $validated[] = $unvalidated;
    } //$post_status as $unvalidated

    if ( !empty( $validated ) )
      $args['post_status'] = $validated;
    // If taxonomy attributes, create a taxonomy query
    if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
      // Term string to array
      $tax_term = explode( ',', $tax_term );
      // Validate operator

      if ( !in_array( $tax_operator, array(
         'IN',
        'NOT IN',
        'AND' 
      ) ) )

        $tax_operator = 'IN';

      $tax_args         = array(
         'tax_query' => array(
           array(
             'taxonomy' => $taxonomy,
            'field' => ( is_numeric( $tax_term[0] ) ) ? 'id' : 'slug',
            'terms' => $tax_term,
            'operator' => $tax_operator 
          ) 
        ) 
      );

      // Check for multiple taxonomy queries
      $count            = 2;
      $more_tax_queries = false;
      while ( isset( $original_atts['taxonomy_' . $count] ) && !empty( $original_atts['taxonomy_' . $count] ) && isset( $original_atts['tax_' . $count . '_term'] ) && !empty( $original_atts['tax_' . $count . '_term'] ) ) {

        // Sanitize values
        $more_tax_queries        = true;
        $taxonomy                = sanitize_key( $original_atts['taxonomy_' . $count] );
        $terms                   = explode( ', ', sanitize_text_field( $original_atts['tax_' . $count . '_term'] ) );
        $tax_operator            = isset( $original_atts['tax_' . $count . '_operator'] ) ? $original_atts['tax_' . $count . '_operator'] : 'IN';
        $tax_operator            = in_array( $tax_operator, array(
           'IN',
          'NOT IN',
          'AND' 
        ) ) ? $tax_operator : 'IN';
        $tax_args['tax_query'][] = array(
           'taxonomy' => $taxonomy,
          'field' => 'slug',
          'terms' => $terms,
          'operator' => $tax_operator 
        );
        $count++;
      } //isset( $original_atts['taxonomy_' . $count] ) && !empty( $original_atts['taxonomy_' . $count] ) && isset( $original_atts['tax_' . $count . '_term'] ) && !empty( $original_atts['tax_' . $count . '_term'] )

      if ( $more_tax_queries ):
        $tax_relation = 'AND';
        if ( isset( $original_atts['tax_relation'] ) && in_array( $original_atts['tax_relation'], array(
           'AND',
          'OR' 
        ) ) )
          $tax_relation = $original_atts['tax_relation'];
        $args['tax_query']['relation'] = $tax_relation;
      endif;

      $args = array_merge( $args, $tax_args );
    } //!empty( $taxonomy ) && !empty( $tax_term )

    

    // Fix for pagination
    if ( is_front_page() ) {
      $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
    } //is_front_page()
    else {
      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    }

    $args['paged'] = $paged;    

    // If post parent attribute, set up parent
    if ( $post_parent ) {
      if ( 'current' == $post_parent ) {
        global $post;
        $post_parent = $post->ID;
      } //'current' == $post_parent

      $args['post_parent'] = intval( $post_parent );

    } //$post_parent
    // Save original posts
    global $posts;
    $original_posts = $posts;

    // Query posts
    $posts = new WP_Query( $args ); 

    $posts->info = $atts['info'];   

    // Buffer output
    ob_start();
    // Search for template in stylesheet directory
    if ( file_exists( get_stylesheet_directory() . '/' . $atts['template'] ) )
      load_template( get_stylesheet_directory() . '/' . $atts['template'], false );
    // Search for template in theme directory

    elseif ( file_exists( get_template_directory() . '/' . $atts['template'] ) )
      load_template( get_template_directory() . '/' . $atts['template'], false );    

    // Template not found
    else
      echo __( 'template not found', 'perch' );
    $output = ob_get_contents();
    ob_end_clean();
    // Return original posts
    $posts = $original_posts;
    // Reset the query
    wp_reset_postdata();    

    return $output;
  }
endif;


add_shortcode( perch_shortcodes_prefix().'single_team_info', 'perch_single_team_info_callback' );
function perch_single_team_info_callback( $atts, $content = '' ){
	$atts = shortcode_atts( array(
	      'template' => 'team/header-info.php',
	  ), $atts );
	// Buffer output
  ob_start();

  // Search for template in stylesheet directory
  if ( file_exists( get_stylesheet_directory() . '/' . $atts['template'] ) )
    load_template( get_stylesheet_directory() . '/' . $atts['template'], false );
  // Search for template in theme directory

  elseif ( file_exists( get_template_directory() . '/' . $atts['template'] ) )
    load_template( get_template_directory() . '/' . $atts['template'], false );  

  // Template not found
  else

    echo __( 'template not found', 'perch' );
  $output = ob_get_contents();
  ob_end_clean();  

  return $output;
}



add_shortcode( perch_shortcodes_prefix().'single_portfolio_info', 'perch_single_portfolio_info_callback' );
function perch_single_portfolio_info_callback( $atts, $content = '' ){
  $atts = shortcode_atts( array(
        'template' => 'portfolio/gallery.php',
    ), $atts );


  // Buffer output
  ob_start();

  // Search for template in stylesheet directory

  if ( file_exists( get_stylesheet_directory() . '/' . $atts['template'] ) )
    load_template( get_stylesheet_directory() . '/' . $atts['template'], false );
  // Search for template in theme directory

  elseif ( file_exists( get_template_directory() . '/' . $atts['template'] ) )
    load_template( get_template_directory() . '/' . $atts['template'], false );
  

  // Template not found
  else

    echo __( 'template not found', 'perch' );
  $output = ob_get_contents();
  ob_end_clean();  

  return $output; 

}



add_shortcode( perch_shortcodes_prefix().'posts', 'perch_posts_callback' );
function perch_posts_callback( $atts, $content = '' ) {
    $atts = shortcode_atts( array(
    'template'            => 'templates/default-loop.php',
    'posts_per_page'      => 3,
    'img_size'            => 'perch-600x600-crop',
    'excerpt_length'      => 18,
    'id'                  => '',
    'post_type'           => 'post',
    'taxonomy'            => 'category',
    'tax_term'            => false,
    'tax_operator'        => 'IN',
    'order'               => 'DESC',
    'orderby'             => 'date',
    'ignore_sticky_posts' => 'yes',
    'column'              => 3,
    'read_more'           => 'Read more'
  ), $atts );


  $atts['info'] = $atts;

  return perch_posts_template( $atts );
}


// Realstate Shortcode
add_shortcode( perch_shortcodes_prefix().'team_template', 'perch_team_callback' );
function perch_team_callback( $atts = array(), $content = null ) {
    $atts = shortcode_atts( array(
        'template' => 'team/default-loop.php',
        'posts_per_page' => -1,
       'id' => '',
       'tax_term' => false,
       'tax_operator' => '',
       'order' => 'desc',
       'orderby' => 'id',
       'post_type' => 'team',
      'taxonomy' => '',
      'column' => 3,
      'read_more' => 'Know more',
      'dots' => 'no',
      'navs' => 'no',
      'autoplay' => 'no',
      ), $atts, 'team' );
    do_action( 'perch/shortcode/team', $atts );

    $atts['info'] = $atts;
    return perch_posts_template($atts, '', 'team');
}



// Realstate Shortcode
add_shortcode( perch_shortcodes_prefix().'services', 'perch_services_callback' );
function perch_services_callback( $atts = null, $content = null ) {
    $atts = shortcode_atts( array(
        'template' => 'service/grid-loop.php',
        'posts_per_page' => -1,
       'id' => '',
       'tax_term' => false,
       'tax_operator' => '',
       'order' => 'desc',
       'orderby' => 'id',
       'post_type' => 'service',
      'taxonomy' => '',
      'column' => 3,
      'read_more' => 'Know more',
      'dots' => 'no',
      'navs' => 'no',
      'autoplay' => 'no',
      ), $atts, 'services' );
    do_action( 'perch/shortcode/services', $atts );

    $atts['info'] = $atts;
    return perch_posts_template($atts, '', 'service');
}

// Realstate Shortcode
add_shortcode( perch_shortcodes_prefix().'portfolios', 'perch_portfolios_callback' );
function perch_portfolios_callback( $atts = null, $content = null ) {
    $atts = shortcode_atts( array(

      'title_display' => 'yes',
      'title' => 'My workfolio',
        'template' => 'portfolio/isotope1.php',
        'posts_per_page' => -1,
       'id' => '',
       'tax_term' => false,
       'tax_operator' => '',
       'order' => 'desc',
       'orderby' => 'id',
       'post_type' => 'portfolio',
      'taxonomy' => 'portfolio_category',
      'column' => 3,
      'read_more' => 'Load more',
      'dots' => 'no',
      'navs' => 'no',
      'autoplay' => 'no',
      'active' => 'all',
      'spacing' => 'yes',
      'filter' => 'yes',
      'link_type' => 'popup',
      'img_size'  => 'perch-400x--nocrop',
      ), $atts, 'portfolios' );

    do_action( 'perch/shortcode/portfolios', $atts );

    $atts['info'] = $atts;
    return perch_posts_template($atts, '', 'portfolio');
}



// Realstate Shortcode
add_shortcode( perch_shortcodes_prefix().'products', 'perch_products_callback' );
function perch_products_callback( $atts = null, $content = null ) {
    $atts = shortcode_atts( array( 
        'template' => 'woocommerce/carousel.php',
        'posts_per_page' => 3,
       'id' => '',
       'tax_term' => false,
       'tax_operator' => '',
       'order' => 'desc',
       'orderby' => 'id',
       'post_type' => 'product',
      'taxonomy' => 'product_cat',
      'column' => 3,
      'read_more' => 'Go to Shop',
      'dots' => 'no',
      'navs' => 'no',
      'autoplay' => 'no',
      ), $atts, 'products' );
    do_action( 'perch/shortcode/products', $atts );

    $atts['info'] = $atts;
    return perch_posts_template($atts, '', 'product');
}





add_shortcode( perch_shortcodes_prefix().'add_value', 'perch_add_value_callback' );
function perch_add_value_callback( $atts, $content = '' ) {
  $atts = shortcode_atts( array(
    'add_values' => ''
  ), $atts );  

  $output = '';
  $output = '';
  $paramGroup = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($atts['add_values']) : array();
  if(!empty($paramGroup)){
    $output .= '<div id="main" class="post-text">
                  <ul>';
    foreach ($paramGroup as $key => $value) {
        $output .= '<li>
                      <p class="h_small">'.esc_attr($value['title']).'</p>
                      <p class="smaller">'.esc_attr($value['desc']).'</p>
                      <div class="h30"></div>
                    </li>';
    }

    $output .= '</div></ul>';
  }  

  return $output;

}



add_shortcode( perch_shortcodes_prefix().'accordions', 'perch_accordions_callback' );
function perch_accordions_callback( $atts, $content = '' ) {
  return '<div class="perch-accordions">'.do_shortcode('[vc_tta_accordion shape="square" spacing="3" c_position="right" active_section="1" no_fill="true"]'.$content.'[/vc_tta_accordion]').'</div>';
}



add_shortcode( perch_shortcodes_prefix().'tabs', 'perch_tabs_callback' );
function perch_tabs_callback( $atts, $content = '' ) {
  return '<div class="perch-tabs">'.do_shortcode('[vc_tta_tabs shape="round" align="center" spacing="" active_section="1"]'.$content.'[/vc_tta_tabs]').'</div>';
}

/**
 * shortcodes
 */
class PerchShortcode{
  
  function __construct(){
    add_shortcode('perch_vc_newsletter', array( $this, 'vc_newsletter_html' ) );
    add_shortcode('perch_vc_newsletter_slide', array( $this, 'vc_newsletter_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'image', array( $this, 'perch_vc_image_html' ) );
    add_shortcode( perch_shortcodes_prefix().'image_slide', array( $this, 'perch_vc_image_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'hero_image', array( $this, 'hero_image_html' ) );
    add_shortcode( perch_shortcodes_prefix().'hero_image_slide', array( $this, 'hero_image_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'content_image', array( $this, 'content_image_html' ) );
    add_shortcode( perch_shortcodes_prefix().'content_image_slide', array( $this, 'content_image_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'download_image', array( $this, 'download_image_html' ) );
    add_shortcode( perch_shortcodes_prefix().'download_image_slide', array( $this, 'download_image_slide_html' ) );

     add_shortcode( perch_shortcodes_prefix().'list', array( $this, 'list_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'list_slide', array( $this, 'list_slide_html' ) );

     add_shortcode( perch_shortcodes_prefix().'feature_box', array( $this, 'feature_box_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'feature_box_slide', array( $this, 'feature_box_slide_html' ) );

     add_shortcode( perch_shortcodes_prefix().'counter_up', array( $this, 'vc_counter_up_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'counter_up_slide', array( $this, 'vc_counter_up_slide_html' ) ); 

     add_shortcode( perch_shortcodes_prefix().'content_box', array( $this, 'vc_content_box_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'content_box_slide', array( $this, 'vc_content_box_slide_html' ) );

     

      add_shortcode( perch_shortcodes_prefix().'button_group', array( $this, 'button_group_html' ) ); 
        add_shortcode( perch_shortcodes_prefix().'button_group_slide', array( $this, 'button_group_slide_html' ) ); 

     add_shortcode( perch_shortcodes_prefix().'button', array( $this, 'vc_button_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'button_slide', array( $this, 'vc_button_slide_html' ) );

     add_shortcode( perch_shortcodes_prefix().'app_devices', array( $this, 'app_devices_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'app_devices_slide', array( $this, 'app_devices_slide_html' ) );   

     add_shortcode( perch_shortcodes_prefix().'testimonial', array( $this, 'testimonial_html' ) ); 
        add_shortcode( perch_shortcodes_prefix().'testimonial_slide', array( $this, 'testimonial_slide_html' ) ); 

    add_shortcode( perch_shortcodes_prefix().'vc_title', array( $this, 'vc_title_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'vc_title_slide', array( $this, 'vc_title_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'section_title', array( $this, 'section_title_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'section_title_slide', array( $this, 'section_title_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'hero_text', array( $this, 'hero_text_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'hero_text_slide', array( $this, 'hero_text_slide_html' ) ); 

    add_shortcode( perch_shortcodes_prefix().'content_text', array( $this, 'content_text_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'content_text_slide', array( $this, 'content_text_slide_html' ) );  

    add_shortcode( perch_shortcodes_prefix().'download_text', array( $this, 'download_text_html' ) ); 
     add_shortcode( perch_shortcodes_prefix().'download_text_slide', array( $this, 'download_text_slide_html' ) ); 

    add_shortcode( perch_shortcodes_prefix().'watch_video', array( $this, 'vc_watch_video_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'watch_video_slide', array( $this, 'vc_watch_video_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'watch_video2', array( $this, 'vc_watch_video2_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'watch_video2_slide', array( $this, 'vc_watch_video2_slide_html' ) );   

    add_shortcode( perch_shortcodes_prefix().'pricing_table', array( $this, 'vc_pricing_table_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'pricing_table_slide', array( $this, 'vc_pricing_table_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'press_logo', array( $this, 'press_logo_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'press_logo_slide', array( $this, 'press_logo_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'brand_logo', array( $this, 'brand_logo_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'brand_logo_slide', array( $this, 'brand_logo_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'posts_template', array( $this, 'posts_template_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'posts_template_slide', array( $this, 'posts_template_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'newsletter_form', array( $this, 'newsletter_form_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'newsletter_form_slide', array( $this, 'newsletter_form_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'faqs', array( $this, 'faqs_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'faqs_slide', array( $this, 'faqs_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'cboxs', array( $this, 'cboxs_html' ) ); 
    add_shortcode( perch_shortcodes_prefix().'cboxs_slide', array( $this, 'cboxs_slide_html' ) );

    add_shortcode( perch_shortcodes_prefix().'carousel', array( $this, 'vc_carousel_html' ) );    
    add_shortcode( perch_shortcodes_prefix().'video_bg', array( $this, 'vc_video_bg_html' ) );    
  }

  public function vc_video_bg_html( $atts, $content = NULL ) {
    return apply_filters('perch_video_bg', $atts, $content);
  }

  public function vc_carousel_html( $atts, $content = NULL ) {
    return apply_filters('perch_vc_carousel', $atts, $content);
  }
  
  public function cboxs_html( $atts, $content = NULL ) {
    return apply_filters('perch_cboxs', $atts, $content);
  }
  public function cboxs_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_cboxs_slide', $atts, $content);
  }

  public function faqs_html( $atts, $content = NULL ) {
    return apply_filters('perch_faqs', $atts, $content);
  }
  public function faqs_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_faqs_slide', $atts, $content);
  }

  public function download_image_html( $atts, $content = NULL ) {
    return apply_filters('perch_download_image', $atts, $content);
  }
  public function download_image_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_download_image_slide', $atts, $content);
  }

  public function hero_image_html( $atts, $content = NULL ) {
    return apply_filters('perch_hero_image', $atts, $content);
  }
  public function hero_image_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_hero_image_slide', $atts, $content);
  }

  public function content_image_html( $atts, $content = NULL ) {
    return apply_filters('perch_content_image', $atts, $content);
  }
  public function content_image_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_content_image_slide', $atts, $content);
  }

  public function newsletter_form_html( $atts, $content = NULL ) {
    return apply_filters('perch_newsletter_form', $atts, $content);
  }
  public function newsletter_form_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_newsletter_form_slide', $atts, $content);
  }

  public function posts_template_html( $atts, $content = NULL ) {
    return apply_filters('perch_posts_template', $atts, $content);
  }
  public function posts_template_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_posts_template_slide', $atts, $content);
  }

  public function brand_logo_html( $atts, $content = NULL ) {
    return apply_filters('perch_brand_logo', $atts, $content);
  }
  public function brand_logo_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_brand_logo_slide', $atts, $content);
  }

  public function press_logo_html( $atts, $content = NULL ) {
    return apply_filters('perch_press_logo', $atts, $content);
  }
  public function press_logo_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_press_logo_slide', $atts, $content);
  }

  public function vc_pricing_table_html( $atts, $content = NULL ) {
    return apply_filters('perch_pricing_table', $atts, $content);
  }
  public function vc_pricing_table_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_pricing_table_slide', $atts, $content);
  }

  public function vc_watch_video_html( $atts, $content = NULL ) {
    return apply_filters('perch_watch_video', $atts, $content);
  }
  public function vc_watch_video_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_watch_video_slide', $atts, $content);
  }

  public function vc_watch_video2_html( $atts, $content = NULL ) {
    return apply_filters('perch_watch_video2', $atts, $content);
  }
  public function vc_watch_video2_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_watch_video2_slide', $atts, $content);
  }

  public function vc_title_html( $atts, $content = NULL ) {
    return apply_filters('perch_vc_title', $atts, $content);
  }
  public function vc_title_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_vc_title', $atts, $content);
  }

  public function section_title_html( $atts, $content = NULL ) {
    return apply_filters('perch_section_title', $atts, $content);
  }
  public function section_title_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_section_title_slide', $atts, $content);
  }

  public function hero_text_html( $atts, $content = NULL ) {
    return apply_filters('perch_hero_text', $atts, $content);
  }
  public function hero_text_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_hero_text_slide', $atts, $content);
  }

  public function content_text_html( $atts, $content = NULL ) {
    return apply_filters('perch_content_text', $atts, $content);
  }
  public function content_text_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_content_text_slide', $atts, $content);
  }

  public function download_text_html( $atts, $content = NULL ) {
    return apply_filters('perch_download_text', $atts, $content);
  }
  public function download_text_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_download_text_slide', $atts, $content);
  }

  public function testimonial_html( $atts, $content = NULL ) {
    return apply_filters('perch_testimonial', $atts, $content);
  }
  public function testimonial_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_testimonial_slide', $atts, $content);
  }

  public function app_devices_html( $atts, $content = NULL ) {
    return apply_filters('perch_app_devices', $atts, $content);
  }
  public function app_devices_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_app_devices_slide', $atts, $content);
  }

  public function vc_button_html( $atts, $content = NULL ) {
    return apply_filters('perch_button', $atts, $content);
  }
  public function vc_button_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_button_slide', $atts, $content);
  }

  public function button_group_html( $atts, $content = NULL ) {
    return apply_filters('perch_button_group', $atts, $content);
  }
  public function button_group_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_button_group_slide', $atts, $content);
  }

  public function vc_content_box_html( $atts, $content = NULL ) {
    return apply_filters('perch_content_box', $atts, $content);
  }
  public function vc_content_box_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_content_box_slide', $atts, $content);
  }

  public function vc_counter_up_html( $atts, $content = NULL ) {
    return apply_filters('perch_counter_up', $atts, $content);
  }
  public function vc_counter_up_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_counter_up', $atts, $content);
  }

  public function feature_box_html( $atts, $content = NULL ) {
    return apply_filters('perch_feature_box', $atts, $content);
  }
  public function feature_box_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_feature_box', $atts, $content);
  }

  public function list_html( $atts, $content = NULL ) {
    return apply_filters('perch_list', $atts, $content);
  }
  public function list_slide_html( $atts, $content = NULL ) {
    return apply_filters('perch_list_slide', $atts, $content);
  }


   public function vc_newsletter_html( $atts, $content = NULL ) {
      return apply_filters('perch_vc_newsletter', $atts, $content);
   }
   public function vc_newsletter_slide_html( $atts, $content = NULL ) {
      return apply_filters('perch_vc_newsletter_slide', $atts, $content);
   }

   public function perch_vc_image_html( $atts, $content = NULL ) {
      return apply_filters('perch_image', $atts, $content);
   }
   public function perch_vc_image_slide_html( $atts, $content = NULL ) {
      return apply_filters('perch_image_slide', $atts, $content);
   }
}
new PerchShortcode();

add_shortcode( 'perch_process', 'perch_process_html' ); 
function perch_process_html( $atts, $content = NULL ) {
    return apply_filters('perch_process', $atts, $content);
}

add_shortcode( 'perch_ibox', 'perch_ibox_html' ); 
function perch_ibox_html( $atts, $content = NULL ) {
    return apply_filters('perch_ibox', $atts, $content);
}

add_shortcode( 'perch_app_box', 'perch_app_box_html' ); 
function perch_app_box_html( $atts, $content = NULL ) {
    return apply_filters('perch_app_box', $atts, $content);
}

add_shortcode( 'perch_inner_page_title', 'perch_inner_page_title_html' ); 
function perch_inner_page_title_html( $atts, $content = NULL ) {
    return apply_filters('perch_inner_page_title', $atts, $content);
}

add_shortcode( 'perch_list_tab', 'perch_list_tab_html' ); 
function perch_list_tab_html( $atts, $content = NULL ) {
    return apply_filters('perch_list_tab', $atts, $content);
}
add_shortcode( 'perch_cbox', 'perch_cbox_html' ); 
function perch_cbox_html( $atts, $content = NULL ) {
    return apply_filters('perch_cbox', $atts, $content);
}