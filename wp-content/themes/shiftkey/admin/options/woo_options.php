<?php
function shiftkey_woo_options( $options = array( ) ) {
    $options = array(
        array(
             'id' => 'shop_layout',
            'label' => esc_attr__( 'Shop layout', 'shiftkey' ),
            'desc' => '',
            'std' => 'full',
            'type' => 'radio-image',
            'section' => 'woo_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => '',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'value' => 'full',
                    'label' => esc_attr__( 'Full width', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/full-width.png' 
                ),
                array(
                     'value' => 'ls',
                    'label' => esc_attr__( 'Left sidebar', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/left-sidebar.png' 
                ),
                array(
                     'value' => 'rs',
                    'label' => esc_attr__( 'Right sidebar', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/right-sidebar.png' 
                ) 
            ) 
        ),
        array(
             'id' => 'shop_layout_sidebar',
            'label' => esc_attr__( 'Select shop Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-product',
            'type' => 'sidebar-select',
            'section' => 'woo_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'shop_layout:not(full)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'loop_columns',
            'label' => esc_attr__( 'Products column', 'shiftkey' ),
            'desc' => '',
            'std' => '3',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'min_max_step' => '1,4,1',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'shop_per_page',
            'label' => esc_attr__( 'Products per page', 'shiftkey' ),
            'desc' => '',
            'std' => '9',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'min_max_step' => '-1,15,1',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'catalog_image_width',
            'label' => esc_attr__( 'Catalog Images Width', 'shiftkey' ),
            'desc' => esc_attr__( 'The size used in product listing.', 'shiftkey' ),
            'std' => '400',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'min_max_step' => '350,1200,1',
            'class' => '',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'catalog_image_height',
            'label' => esc_attr__( 'Catalog Images height', 'shiftkey' ),
            'desc' => esc_attr__( 'The size used in product listing.', 'shiftkey' ),
            'std' => '500',
            'type' => 'numeric-slider',
            'section' => 'woo_options',
            'min_max_step' => '350,1000,1',
            'class' => '',
            'condition' => '',
            'operator' => 'and' 
        ),

        
    );
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        return apply_filters( 'shiftkey_theme_options', $options, 'woo_options' );
    } //in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
    else {
        $options = array(
             array(
                 'id' => 'woo_info',
                'label' => 'Woocommerce',
                'desc' => esc_attr__( 'Woocommerce plugin is Required. Installed & activated woocommerce plugin to get Woo options', 'shiftkey' ),
                'std' => '3',
                'type' => 'textblock',
                'section' => 'woo_options' 
            ) 
        );
        return apply_filters( 'shiftkey_theme_options', $options, 'woo_options' );
    }
}

foreach ( glob( SHIFTKEY_DIR . "/admin/options/shop/*-settings.php" ) as $filename ) {
    if( file_exists($filename) ){
        load_template($filename);
    }    
}