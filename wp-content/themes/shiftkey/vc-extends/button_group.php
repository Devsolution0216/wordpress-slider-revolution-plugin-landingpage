<?php
add_filter( 'perch_modules/vc/perch_button_group', 'shiftkey_vc_button_group_default_args' );
function shiftkey_vc_button_group_default_args( $args ){
	$default = array(       
        'subtitle' => '* Requires iOS 7.0 or higher',
        'subtitle_font_container' => 'tag:p|extra_class:os-version',         
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}