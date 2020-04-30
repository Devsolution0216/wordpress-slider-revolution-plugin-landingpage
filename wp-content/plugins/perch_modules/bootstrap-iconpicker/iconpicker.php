<?php
define( 'PERCH_ICONPICKER_DIR', PERCH_MODULES_DIR . '/bootstrap-iconpicker' );
define( 'PERCH_ICONPICKER_URI', PERCH_MODULES_URI. '/bootstrap-iconpicker' );
add_action( 'init', 'perch_modules_load_iconpicker_type' ); 
function perch_modules_load_iconpicker_type() {
    require_once PERCH_ICONPICKER_DIR . '/field-iconpicker.php';
}

add_action( 'init', 'perch_modules_load_color_gradient_type' ); 
function perch_modules_load_color_gradient_type() {
    require_once PERCH_ICONPICKER_DIR . '/field-color-gradient.php';
}


// Register Style
function perch_iconpicker_iconpicker_styles() {	
	wp_register_style( 'perch-iconpicker-admin-bootstrap', PERCH_ICONPICKER_URI. '/assets/css/bootstrap.min.css', false, '4.0.0', 'all' );	
	wp_register_style( 'perch-iconpicker-iconpicker', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css', array('perch-iconpicker-admin-bootstrap'), '5.3.1', 'all' );	
}
// Hook into the 'admin_enqueue_scripts' action
add_action( 'init', 'perch_iconpicker_iconpicker_styles' );


// Register Script
function perch_iconpicker_iconpicker_scripts() {	
	wp_register_script( 'bootstrap-bundle', PERCH_ICONPICKER_URI. '/assets/js/bootstrap.bundle.min.js', array( 'jquery' ), false, false );
	
	wp_register_script( 'perch-iconpicker-iconset-all', PERCH_ICONPICKER_URI. '/assets/js/bootstrap-iconpicker-iconset-all.min.js', array( 'bootstrap-bundle' ), false, false );
	wp_register_script( 'perch-iconpicker-iconpicker', PERCH_ICONPICKER_URI. '/assets/js/bootstrap-iconpicker.js', array( 'perch-iconpicker-iconset-all' ), false, false );

}
add_action( 'init', 'perch_iconpicker_iconpicker_scripts' );