<?php
function shiftkey_footer_newsletter_options( $metabox = false ){
    $options = array(
        array(
             'id' => 'newsletter_title',
            'title' => esc_attr__( 'Newsletter title', 'shiftkey' ),
            'desc' => 'Use {} to highlight text',
            'default' => esc_attr__( 'Stay Updated With Our News', 'shiftkey' ),
            'type' => 'text',
            
        ),
        array(
             'id' => 'newsletter_subtitle',
            'title' => esc_attr__( 'Newsletter subtitle', 'shiftkey' ),
            'desc' => 'Use {} to highlight text',
            'default' => 'Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero at tempus, blandit posuere ligula varius congue porta feugiat',
            'type' => 'textarea',
            'args' => array('media_buttons' => false, 'wpautop' => false),
            
        ),
        array(
             'id' => 'newsletter_placeholder',
            'title' => esc_attr__( 'Newsletter email placeholder', 'shiftkey' ),
            'desc' => '',
            'default' =>  __( 'Your email address', 'shiftkey' ),
            'type' => 'text',
            
        ),
        array(
             'id' => 'newsletter_footer',
            'title' => esc_attr__( 'Newsletter footer text', 'shiftkey' ),
            'desc' => '',
            'default' => '',
            'type' => 'editor',
            'args' => array('media_buttons' => false),            
        )
    );

    $bg_color = array(
        array(
            'id' => 'newsletter_bg_class',
            'title' => esc_attr__( 'Newsletter background color', 'shiftkey' ),
            'desc' => '',
            'default' => '',
            'type' => 'select',            
            'prefix' => 'newsletter_bg',
            'selector' => '.newsletter-section',            
            'options' => shiftkey_redux_options(shiftkey_bg_color_options()),                   
        )
    );
    $bg_color = apply_filters( 'shiftkey/bg-color', $bg_color );
    $options = array_merge( $options, $bg_color);

    $options = array_merge( $options, array(
        array(
            'id'       => 'newsletter_parallax_switch',
            'type'     => 'switch', 
            'title'    => esc_attr__('Newsletter background parallax', 'shiftkey'),            
            'default'  => false,            
        ),        
        array(
            'id'       => 'newsletter_bg',
            'type'     => 'background',
            'output'   => array( '.newsletter-section .parallax-inner' ),
            'title'    => esc_attr__( 'Newsletter parallax background', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Newsletter background with image, color, etc.', 'shiftkey' ),
            'preview' => true,
            'preview_media' => false,
            'background-clip' => true,
            'background-origin' => true,
            'background-color' => false,
            'preview_height' => '200px',
            'default'  => array( 'background-size' => 'cover',),
            'required' => array('newsletter_parallax_switch', '=', true)
        ),
        array(
            'id'            => 'newsletter_parallax_opacity',
            'type'          => 'slider',
            'title'         => esc_attr__( 'Newsletter parallax opacity', 'shiftkey' ),
            'desc'          => esc_attr__( 'Min: 0, max: 1, step: .1, default value: 1', 'shiftkey' ),
            'default'       => 1,
            'min'           => 0,
            'step'          => .1,
            'max'           => 1,
            'resolution'    => 0.1,
            'display_value' => 'text',
            'required' => array('newsletter_parallax_switch','equals',true)
        ),        
        array(
             'id' => 'cta_title',
            'title' => esc_attr__( 'Call to action title', 'shiftkey' ),
            'desc' => 'Use {} to highlight text',
            'default' => esc_attr__( 'Have a project in mind? Let\'s discuss', 'shiftkey' ),
            'type' => 'text',
        ),
        array(
             'id' => 'cta_subtitle',
            'title' => esc_attr__( 'Call to action subtitle', 'shiftkey' ),
            'desc' => 'Use {} to highlight text',
            'default' => 'Donec vel sapien augue integer urna vel turpis cursus porta, mauris sed augue luctus dolor velna auctor congue tempus magna integer',
            'type' => 'textarea',
            'args' => array('media_buttons' => false, 'wpautop' => false),
        ),
        array(
             'id' => 'cta_button_text',
            'title' => esc_attr__( 'Call to action button text', 'shiftkey' ),
            'default' => esc_attr__( 'Let\'s Started', 'shiftkey' ),
            'type' => 'text',
        ),
        array(
             'id' => 'cta_button_link',
            'title' => esc_attr__( 'Call to action button link', 'shiftkey' ),
            'default' => '#',
            'type' => 'text',
        ),
    ));


    $bg_color = array(
       array(
            'id'       => 'cta_parallax_switch',
            'type'     => 'switch', 
            'title'    => esc_attr__('CTA background parallax', 'shiftkey'),            
            'default'  => false,            
        ),        
        array(
            'id'       => 'cta_bg',
            'type'     => 'background',
            'output'   => array( '.cta-section .parallax-inner' ),
            'title'    => esc_attr__( 'CTA parallax background', 'shiftkey' ),
            'subtitle' => esc_attr__( 'CTA background with image, color, etc.', 'shiftkey' ),
            'preview' => true,
            'preview_media' => false,
            'background-clip' => true,
            'background-origin' => true,
            'background-color' => false,
            'preview_height' => '200px',
            'default'  => array( 'background-size' => 'cover',),
            'required' => array('cta_parallax_switch', '=', true)
        ),
        array(
            'id'            => 'cta_parallax_opacity',
            'type'          => 'slider',
            'title'         => esc_attr__( 'CTA parallax opacity', 'shiftkey' ),
            'desc'          => esc_attr__( 'Min: 0, max: 1, step: .1, default value: 1', 'shiftkey' ),
            'default'       => 1,
            'min'           => 0,
            'step'          => .1,
            'max'           => 1,
            'resolution'    => 0.1,
            'display_value' => 'text',
            'required' => array('cta_parallax_switch','equals',true)
        ), 
    );
   
    $options = array_merge( $options, $bg_color);

	

    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}