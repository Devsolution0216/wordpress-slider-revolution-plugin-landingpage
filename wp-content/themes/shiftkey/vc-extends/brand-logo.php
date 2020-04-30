<?php
add_filter( 'perch_modules/vc/perch_brand_logo', 'shiftkey_vc_brand_logo_default_args' );
function shiftkey_vc_brand_logo_default_args( $args ){
	$default = array(
		'source' => 'external_link',              
        'custom_src' => SHIFTKEY_URI. '/images/brand-1.png', 
        'onclick' => 'custom_link', 
        'img_link_target' => '_blank',         
        'el_class' => 'brand-logo',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}