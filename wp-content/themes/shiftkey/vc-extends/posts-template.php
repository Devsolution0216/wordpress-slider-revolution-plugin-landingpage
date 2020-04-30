<?php
add_filter( 'perch_modules/vc/perch_posts_template', 'shiftkey_vc_posts_template_default_args' );
function shiftkey_vc_posts_template_default_args( $args ){
	$default = array(
        'excerpt_length' => 0,
        'column' => 3,
        'img_size' => 'shiftkey-400x300-crop',
        'el_class' => 'primary-theme',
        'dots' => 'yes',
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}
