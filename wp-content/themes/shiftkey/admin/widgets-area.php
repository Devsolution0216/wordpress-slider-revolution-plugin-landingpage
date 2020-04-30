<?php
/*Shiftkey widget are - Main widget area + Page widget area + Portfolio widget area*/
add_action( 'widgets_init', 'shiftkey_sidebars' );
if ( !function_exists( 'shiftkey_sidebars' ) ) {
    // Register Sidebars
    function shiftkey_sidebars() {
        $sidebar_class = shiftkey_sidebar_common_class();
        $args = array(
             'id' => 'sidebar-post',
            'name' => esc_attr__( 'Main Widget Area', 'shiftkey' ),
            'before_title' => '<h5 class="h5-sm widget-title">',
            'after_title' => '</h5>',
            'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
            'after_widget' => '</div>' 
        );
        register_sidebar( $args );
        if( class_exists( 'ReduxFramework' ) ):
        $args = array(
             'id' => 'sidebar-page',
            'name' => esc_attr__( 'Page Widget Area', 'shiftkey' ),
            'before_title' => '<h5 class="h5-sm widget-title">',
            'after_title' => '</h5>',
            'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
            'after_widget' => '</div>' 
        );
        register_sidebar( $args );
        $args = array(
             'id' => 'sidebar-portfolio',
            'name' => esc_attr__( 'Portfolio Widget Area', 'shiftkey' ),
            'before_title' => '<h5 class="h5-sm widget-title">',
            'after_title' => '</h5>',
            'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
            'after_widget' => '</div>' 
        );
        register_sidebar( $args );
        endif;
        if ( function_exists( 'is_woocommerce' ) ):
            $args = array(
                 'id' => 'sidebar-product',
                'name' => esc_attr__( 'Shop Widget Area', 'shiftkey' ),
                'before_title' => '<h5 class="h5-sm widget-title">',
                'after_title' => '</h5>',
                'before_widget' => '<div id="%1$s" class="'.esc_attr(implode( ' ', $sidebar_class )).' %2$s">',
                'after_widget' => '</div>' 
            );
            register_sidebar( $args );
        endif;
        
       
            $column = shiftkey_get_option( 'footer_widget_area_column', '4' );
            for ( $i = 1; $i <= $column; $i++ ) {
                $args = array(
                     'id' => 'footer-' . $i,
                    'name' => esc_attr__( 'Footer Widget Area ', 'shiftkey' ) . intval( $i ),
                    'before_title' => '<h5 class="h5-sm">',
                    'after_title' => '</h5>',
                    'before_widget' => '<div id="%1$s" class="footer-links footer-widget mb-40 %2$s">',
                    'after_widget' => '</div>'  
                );
                register_sidebar( $args );
            } //$i = 1; $i <= $column; $i++
       
        if ( function_exists( 'shiftkey_get_option' ) ):
            $sidebarArr = shiftkey_get_option( 'create_sidebar', array( ) );
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
        endif; //if( function_exists( 'shiftkey_get_option' ) ):	
    }
} //!function_exists( 'shiftkey_sidebars' )

function shiftkey_context_args($context){
    return $context;
}