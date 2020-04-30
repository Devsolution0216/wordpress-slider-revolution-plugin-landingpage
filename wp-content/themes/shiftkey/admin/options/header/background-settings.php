<?php
function shiftkey_header_background_options( $args = array( ) ) {   

    $options = array(                      
        array(
            'id'       => 'header_bg_style',
            'type'     => 'button_set',
            'title'    => esc_attr__( 'Header background type', 'shiftkey' ),
            'subtitle'    => esc_attr__('Choose your site header background type', 'shiftkey'),          
            'options'  => array(
                '' => 'Inherit',
                'solid' => 'Solid/Gradient color',
            ),
            'default'  => 'solid'
        ),       
    );

    $bg_color = array(
        array(
            'id' => 'header_bg_class',
            'title' => esc_attr__( 'Header background color', 'shiftkey' ),
            'desc' => '',
            'default' => 'bg-dark',
            'type' => 'select',            
            'prefix' => 'header_bg',
            'selector' => '#blogs-page.page-hero-section',            
            'options' => shiftkey_redux_options(shiftkey_bg_color_options()),
            'required' => array( 
                array('header_bg_style','!=',''),
            )           
        )
    );
    $bg_color = apply_filters( 'shiftkey/bg-color', $bg_color );

    $options = array_merge( $options, $bg_color);
    $options = array_merge( $options, array( 
        array(
            'id'       => 'header_parallax_switch',
            'type'     => 'switch', 
            'title'    => esc_attr__('Header background parallax', 'shiftkey'),            
            'default'  => true,
        ),
        array(
            'id'       => 'header_parallax_bg',
            'type'     => 'background',
            'output'   => false,
            'title'    => esc_attr__( 'Header parallax background', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Header background with image, color, etc.', 'shiftkey' ),
            'preview' => true,
            'preview_media' => false,
            'background-clip' => true,
            'background-origin' => true,
            'background-color' => false,
            'preview_height' => '200px',
            'default'  => array(
                'background-size' => 'cover',
            ),
            'required' => array('header_parallax_switch','equals',true)
        ),
        array(
            'id'            => 'header_parallax_opacity',
            'type'          => 'slider',
            'title'         => esc_attr__( 'Header parallax opacity', 'shiftkey' ),
            'desc'          => esc_attr__( 'Min: 0, max: 1, step: .1, default value: 1', 'shiftkey' ),
            'default'       => 1,
            'min'           => 0,
            'step'          => .1,
            'max'           => 1,
            'resolution'    => 0.1,
            'display_value' => 'text',
            'required' => array('header_parallax_switch','equals',true)
        ),
               
    ));
    return $options;
}