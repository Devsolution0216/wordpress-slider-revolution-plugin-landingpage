<?php
function shiftkey_header_navbar_options( $metabox = false, $args = array( ) ) {
	$options = array();
    $old_options = array( 
        array(
             'id' => 'navbar_style',
            'label' => esc_attr__( 'Navbar style', 'shiftkey' ),
            'desc' => '',
            'std' => 'style1',
            'type' => 'select',
            'section' => 'header_options',
            'choices' => array(
                array( 'label' => 'Navbar style #1',  'value' => 'style1' ),
                array( 'label' => 'Navbar style #2',  'value' => 'style2' ),
            )
        ),
    );
    // option tree options to redux options
    $modyfied_option = apply_filters( 'shiftkey_theme_options', $old_options, 'header_options' );
    $options = array_merge( $options, $modyfied_option );


    $bg_color = array(
        array(
            'id' => 'nav_bg_class',
            'title' => esc_attr__( 'Navbar background color', 'shiftkey' ),
            'desc' => '',
            'default' => 'bg-tra-dark',
            'type' => 'select',            
            'prefix' => 'nav_bg',                    
            'options' => shiftkey_redux_options(shiftkey_bg_color_options()),                      
        )
    );
    // filter for custom color/gradient settings
    $bg_color = apply_filters( 'shiftkey/bg-color', $bg_color );
    $options = array_merge( $options, $bg_color);

    $new_options =  array(
        array(
             'id' => 'header_sticky_nav',
            'title' => esc_attr__( 'Sticky navbar', 'shiftkey' ),
            'desc' => '',
            'default' => true,
            'type' => 'switch',
        ), 
        array(
             'id' => 'nav_style_scroll',
            'title' => esc_attr__( 'Navbar background color while scrolling', 'shiftkey' ),
            'desc' => '',
            'default' => 'white-scroll',
            'type' => 'select',
            'options' => shiftkey_redux_options(shiftkey_navscrool_bg_color_options()),  
            'required' => array('header_sticky_nav', '=', true),       
        ),
                       
    );	
    $options = array_merge( $options, $new_options );

    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}