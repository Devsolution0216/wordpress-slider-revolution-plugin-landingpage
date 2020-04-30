<?php
function shiftkey_footer_options( $metabox = false, $options = array() ) {
    $old_options = array(
        /*array(
            'id' => 'footer_bg_style',
            'label' => __('Footer background style', 'shiftkey'),
            'desc' => '',
            'std' => 'bg-tra',
            'type' => 'select',
            'choices' => shiftkey_bg_color_options(),
            'section' => 'footer_options'
        ),*/
        /*
        array(
        'id'          => 'footer_background',
        'label'       => esc_attr__( 'Footer background', 'shiftkey' ),
        'desc'        => '',
        'std'         => '',
        'type'        => 'background',
        'section'     => 'footer_options',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_widget_area:is(on)',
        'operator'    => 'and',
        'action'   => array()
      ), */       
         
    );
    

    $options = array_merge($options, array(
        array(
             'id' => 'newsletter_area',
            'title' => esc_attr__( 'Newsletter area Display', 'shiftkey' ),
            'desc' => 'Display before footer area',
            'default' => false,
            'type' => 'switch',          
        ),
        array(
             'id' => 'cta_area_display',
            'title' => esc_attr__( 'Call to action area Display', 'shiftkey' ),
            'desc' => 'Display before footer area',
            'default' => false,
            'type' => 'switch',          
        ),
        array(
             'id' => 'footer_widget_area',
            'title' => esc_attr__( 'Footer widget area Display','shiftkey' ),
            'desc' => '',
            'default' => true,
            'type' => 'switch',          
        ), 
        array(
             'id' => 'footer_copyright_bar',
            'title' =>  __( 'Footer copyright', 'shiftkey' ),
            'desc' => '',
            'default' => true,
            'type' => 'switch',          
        ),
        array(
             'id' => 'quickform_area',
            'title' =>  __( 'Quick contact form Display', 'shiftkey' ),
            'desc' => esc_attr__( 'Display in bottom of page','shiftkey' ),
            'default' => true,
            'type' => 'switch',          
        ),  
        
    ));

    $bg_color = array(
        array(
            'id' => 'footer_bg_class',
            'title' => esc_attr__( 'Footer background style', 'shiftkey' ),
            'desc' => '',
            'default' => 'bg-tra',
            'type' => 'select',            
            'prefix' => 'footer_bg',
            'selector' => 'footer.footer',            
            'options' => shiftkey_redux_options(shiftkey_bg_color_options()),                     
        )
    );
    $bg_color = apply_filters( 'shiftkey/bg-color', $bg_color );
    $options = array_merge( $options, $bg_color);
    // Filter for option tree to redux options
    //$modyfied_option = apply_filters( 'shiftkey_theme_options', $old_options, 'footer_options' );
    //$options = array_merge( $options, $modyfied_option );


    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}

foreach ( glob( SHIFTKEY_DIR . "/admin/options/footer/*-settings.php" ) as $filename ) {
    if( file_exists($filename) ){
        load_template($filename);
    }    
}