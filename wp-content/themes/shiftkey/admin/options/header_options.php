<?php
function shiftkey_woo_cart_icon_option( ) {
    if ( function_exists( 'is_woocommerce' ) ) {
        return array(
             'id' => 'header_cart_icon',
            'label' => esc_attr__( 'Header cart icon display', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'header_options',
            'condition' => '',
            'operator' => 'or' 
        );
    } //function_exists( 'is_woocommerce' )
}
function shiftkey_langs_dropdown_option( ) {
    return array(
         'id' => 'header_language_dropdown',
        'label' => esc_attr__( 'Header topbar Language dropdown display', 'shiftkey' ),
        'desc' => 'This option only applicable when <strong>WPML</strong>, <strong>Polylang</strong> or <strong>Multilanguage by BestWebSoft</strong> plugins are installed',
        'std' => 'on',
        'type' => 'on-off',
        'section' => 'header_options',
        'condition' => '',
        'operator' => 'or' 
    );
}
function shiftkey_header_default_options(){
    $options = array( 
        array(
            'id'       => 'enable_navbar_bg',
            'type'     => 'on-off', 
            'title'    => esc_attr__('Header navbar Background', 'shiftkey'),            
            'std'  => 'on',
        ),
        array(
            'id'       => 'title_display',
            'type'     => 'on-off', 
            'title'    => esc_attr__('Header Title banner display', 'shiftkey'),            
            'std'  => 'off',
        ), 
        array(
            'id'       => 'breadcrumbs_display',
            'type'     => 'on-off', 
            'title'    => esc_attr__('Header breadcrumbs display', 'shiftkey'),            
            'std'  => 'on',
        ), 
    );

    return apply_filters( 'shiftkey_theme_options', $options, 'header_options' );

}
function shiftkey_header_options( $metabox = false ) {
    $options = array(        
        array(
            'id'       => 'logo_type',
            'type'     => 'button_set',
            'title'    => esc_attr__( 'Logo type', 'shiftkey' ), 
            'subtitle'    => esc_attr__('Choose your site logo', 'shiftkey'),          
            'options'  => array(
                'image' => 'Image',
                'text' => 'Text',
            ),
            'default'  => 'image'
        ),
        array(
            'id'       => 'logo',
            'type'     => 'media',
            'url'      => true,
            'title'    => esc_attr__( 'Logo', 'shiftkey' ),
            'compiler' => 'true',
            //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
            'desc'     => '',
            'subtitle' => esc_attr__( 'Upload any media using the WordPress native uploader', 'shiftkey' ),
            'default'  => array( 'url' => apply_filters( 'shiftkey_header_logo_default', SHIFTKEY_URI . '/images/logo.png') ),
            'hint'      => array(
                'content'   => esc_attr__('Display on light color navbar background', 'shiftkey'),
            ),
            'required' => array('logo_type','equals','image')
        ), 
        array(
            'id'       => 'logo_white',
            'type'     => 'media',
            'url'      => true,
            'title'    => esc_attr__( 'Logo white', 'shiftkey' ),
            'compiler' => 'true',
            //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
            'desc'     => '',
            'subtitle' => esc_attr__( 'Upload any media using the WordPress native uploader', 'shiftkey' ),
            'default'  => array( 'url' => apply_filters( 'shiftkey_header_logo_default', SHIFTKEY_URI . '/images/logo-white.png') ),
            'hint'      => array(
                'content'   => esc_attr__('Display on dark type navbar background', 'shiftkey'),
            ),
            'required' => array('logo_type','equals','image')
        ), 
        array(
            'id'             => 'logo_dimensions',
            'type'           => 'dimensions',
            'units'          => array( 'px' ),    // You can specify a unit value. Possible: px, em, %
            'units_extended' => false,  // Allow users to select any type of unit
            'title'          => esc_attr__( 'Logo Dimensions (Width/Height)', 'shiftkey' ),
            'subtitle'       => esc_attr__( 'Choose width, height', 'shiftkey' ),
            'default'        => array(
                'width'  => 125,
                'height' => 30,
            ),
            'required' => array('logo_type','equals','image')
        ),
        array(
            'id'    => 'logo_text',
            'type'  => 'text',
            'title' => 'Logo',
            'default' => get_bloginfo( 'name' ),
            'required' => array('logo_type','equals','text')
        ),   
        /*array(        
        'id' => 'header_menu_breakpoint',        
        'label' => esc_attr__( 'Header menu breakpoint', 'shiftkey' ),        
        'desc' => 'in pixel',        
        'std' => '800',        
        'type' => 'text',
        'section' => 'header_options'         
        ),*/
        //shiftkey_langs_dropdown_option(),
        //shiftkey_woo_cart_icon_option(),         
    );
    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
    
}


foreach ( glob( SHIFTKEY_DIR . "/admin/options/header/*-settings.php" ) as $filename ) {
    if( file_exists($filename) ){
        load_template($filename);
    }    
}