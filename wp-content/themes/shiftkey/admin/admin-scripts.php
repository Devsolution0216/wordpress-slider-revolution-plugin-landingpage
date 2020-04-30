<?php
// Register Style
function shiftkey_iconpicker_styles() {
    wp_register_style( 'fontawesome', SHIFTKEY_URI. '/css/fontawesome.css', false, '5.0.0', 'all' );    
    wp_register_style( 'flaticon', SHIFTKEY_URI. '/css/flaticon.css', false, '1.1' );
    wp_register_style( 'tonicons', SHIFTKEY_URI. '/css/tonicons.css', false, '1.0' );
}

// Hook into the 'admin_enqueue_scripts' action
add_action( 'init', 'shiftkey_iconpicker_styles' );


// Register Style
function shiftkey_admin_styles( ) {
    wp_enqueue_style( 'shiftkey-admin-style', SHIFTKEY_URI . '/admin/assets/css/style.css', false, '1.0.0', 'all' );
    wp_enqueue_style( 'shiftkey-vc-admin', SHIFTKEY_URI . '/admin/assets/css/vc-admin.css', false, '1.0.0', 'all' );   
    wp_enqueue_style( 'tonicons' );
    wp_enqueue_style( 'flaticon' );
	wp_enqueue_style( 'fontawesome' );
    wp_add_inline_style( 'shiftkey-admin-style', shiftkey_dynamic_general_style_css() ); 

}
// Hook into the 'admin_enqueue_scripts' action
add_action( 'admin_enqueue_scripts', 'shiftkey_admin_styles' );
// Register Script
function shiftkey_admin_scripts( ) {
    wp_enqueue_media();
    wp_enqueue_script( 'v4-shims', SHIFTKEY_URI .'/js/fa-v4-shims.min.js', array( 'jquery' ), '1.0.0', true );
   
    wp_register_script( 'shiftkey-scripts', SHIFTKEY_URI . '/admin/assets/js/scripts.js', array(
         'jquery' 
    ), '1.0.0.8', false );
    wp_enqueue_script( 'shiftkey-scripts' );

    $arr = array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'SHIFTKEY_URI' => SHIFTKEY_URI,
        'SHIFTKEY_DIR' => SHIFTKEY_DIR,
        'animation' => shiftkey_get_option( 'shiftkey_animation', 'on' ),
        'vc_preview' => shiftkey_get_option('vc_admin_view', 'full')
        );
    wp_localize_script( 'shiftkey-scripts', 'SHIFTKEY', $arr );
}
// Hook into the 'admin_enqueue_scripts' action
add_action( 'admin_enqueue_scripts', 'shiftkey_admin_scripts' );

function shiftkey_admin_editor_dynamic_css() {    
    wp_add_inline_style( 'shiftkey-admin-bootstrap', shiftkey_dynamic_general_style_css() );  
}
add_action( 'admin_enqueue_scripts', 'shiftkey_admin_editor_dynamic_css' );