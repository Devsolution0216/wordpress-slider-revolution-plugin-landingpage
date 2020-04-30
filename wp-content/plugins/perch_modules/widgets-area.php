<?php
/*Appset widget are - Main widget area + Page widget area + Portfolio widget area*/
add_action( 'widgets_init', 'perch_sidebars' );
if ( !function_exists( 'perch_sidebars' ) ) {
    // Register Sidebars
    function perch_sidebars() {
        $sidebar_class = perch_sidebar_common_class();
        $args = array(
             'id' => 'sidebar-post',
            'name' => __( 'Main Widget Area', 'perch' ),
            'before_title' => '<h5 class="h5-sm widget-title">',
            'after_title' => '</h5>',
            'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
            'after_widget' => '</div>' 
        );
        register_sidebar( $args );
        $args = array(
             'id' => 'sidebar-page',
            'name' => __( 'Page Widget Area', 'perch' ),
            'before_title' => '<h5 class="h5-sm widget-title">',
            'after_title' => '</h5>',
            'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
            'after_widget' => '</div>' 
        );
        register_sidebar( $args );
        $args = array(
             'id' => 'sidebar-portfolio',
            'name' => __( 'Portfolio Widget Area', 'perch' ),
            'before_title' => '<h5 class="h5-sm widget-title">',
            'after_title' => '</h5>',
            'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
            'after_widget' => '</div>' 
        );
        register_sidebar( $args );
        if ( function_exists( 'is_woocommerce' ) ):
            $args = array(
                 'id' => 'sidebar-product',
                'name' => __( 'Shop Widget Area', 'perch' ),
                'before_title' => '<h5 class="h5-sm widget-title">',
                'after_title' => '</h5>',
                'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
                'after_widget' => '</div>' 
            );
            register_sidebar( $args );
        endif;
        
        if ( Appset_Footer_Config::widget_area_is_on() ):
            $column = perch_get_option( 'footer_widget_area_column', '4' );
            for ( $i = 1; $i <= $column; $i++ ) {
                $args = array(
                     'id' => 'footer-' . $i,
                    'name' => __( 'Footer Widget Area ', 'perch' ) . intval( $i ),
                    'before_title' => '<h5 class="h5-sm">',
                    'after_title' => '</h5>',
                    'before_widget' => '<div id="%1$s" class="footer-widget mb-40 %2$s">',
                    'after_widget' => '</div>'  
                );
                register_sidebar( $args );
            } //$i = 1; $i <= $column; $i++
        endif;
        if ( function_exists( 'perch_get_option' ) ):
            $sidebarArr = perch_get_option( 'create_sidebar', array( ) );
            if ( !empty( $sidebarArr ) ) {
                $i = 2;
                foreach ( $sidebarArr as $sidebar ) {
                    register_sidebar( array(
                         'name' => esc_attr( $sidebar[ 'title' ] ),
                        'id' => 'sidebar-' . $i,
                        'description' => esc_attr( $sidebar[ 'desc' ] ),
                        'before_widget' => '<aside id="%1$s" class="single-widget %2$s">',
                        'after_widget' => '</aside>',
                        'before_title' => '<h3 class="widget-title">',
                        'after_title' => '</h3>' 
                    ) );
                    $i++;
                } //$sidebarArr as $sidebar
            } //!empty( $sidebarArr )
        endif; //if( function_exists( 'perch_get_option' ) ):	
    }
} //!function_exists( 'perch_sidebars' )

function perch_context_args($context){
    return $context;
}

include PERCH_MODULES_DIR . '/widgets/widgets.php';