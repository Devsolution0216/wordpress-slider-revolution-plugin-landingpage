<?php
add_filter( 'perch_modules/vc/perch_download_image', 'shiftkey_vc_download_image_default_args' );
function shiftkey_vc_download_image_default_args( $args ){
	$default = array(
		'source' => 'external_link',              
        'custom_src' => SHIFTKEY_URI. '/images/image-09.png', 
        'onclick' => 'custom_link', 
        'img_link_target' => '_blank',         
        'el_class' => 'download-img',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}