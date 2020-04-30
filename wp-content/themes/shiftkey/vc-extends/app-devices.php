<?php
add_filter( 'perch_modules/vc/perch_app_devices', 'shiftkey_vc_app_devices_default_args' );
function shiftkey_vc_app_devices_default_args( $args ){
	$default = array(       
        'subtitle' => 'Available on iPhone, iPad and all Android devices from 5.5',
        'subtitle_font_container' => 'tag:p|extra_class:p-small',         
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}