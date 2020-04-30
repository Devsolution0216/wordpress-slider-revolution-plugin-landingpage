<?php
// Register Style
function perch_modules_register_scripts( ) {
    wp_register_style( 'animate', PERCH_MODULES_URI . '/assets/css/animate.min.css', false, '3.7.0', 'all' ); 
    wp_register_script( 'wow', PERCH_MODULES_URI . '/assets/js/wow.min.js', array('jquery'), '1.3.0', false );  
    wp_register_script( 'vidbg', PERCH_MODULES_URI . '/assets/js/vidbg.min.js', array('jquery'), '1.3.0', false );  
}
// Hook into the 'admin_enqueue_scripts' action
add_action( 'init', 'perch_modules_register_scripts' );


// Register Style
function perch_admin_styles( ) {
    wp_enqueue_style( 'perch-admin-style', PERCH_MODULES_URI . '/assets/css/style.css', false, '1.0.0', 'all' );
    wp_enqueue_style( 'perch-vc-admin', PERCH_MODULES_URI . '/assets/css/vc-admin.css', false, '1.0.0', 'all' );
    wp_enqueue_style( 'tonicons' );
    wp_enqueue_style( 'flaticon' );
	wp_enqueue_style( 'fontawesome' );
}
// Hook into the 'admin_enqueue_scripts' action
add_action( 'admin_enqueue_scripts', 'perch_admin_styles' );

// Register Script
function perch_admin_scripts() {
   
    wp_register_script( 'perch-admin-scripts', PERCH_MODULES_URI . '/assets/js/perch-ui.js', array(
         'jquery' 
    ), '1.0.0', false ); 
    wp_enqueue_script( 'perch-admin-scripts' );   

    $arr = array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'PERCH_MODULES_URI' => PERCH_MODULES_URI,
        'PERCH_MODULES_DIR' => PERCH_MODULES_DIR,
        'vc_preview' => apply_filters('perch_modules/vc_admin_view', 'full'),
        );
    wp_localize_script( 'perch-admin-scripts', 'PERCH_MODULES', $arr );
}
// Hook into the 'admin_enqueue_scripts' action
add_action( 'admin_enqueue_scripts', 'perch_admin_scripts' );

add_action('login_enqueue_scripts', 'perch_modules_admin_logo');
function perch_modules_admin_logo(){   
    echo apply_filters('perch_modules/admin_logo', '');
   
}

