<?php
add_filter( 'perch_modules/vc/perch_watch_video2', 'shiftkey_vc_watch_video2_default_args' );
function shiftkey_vc_watch_video2_default_args( $args ){
	$default = array(  
		'align' => 'text-center',     
        'title' => 'Crafted with detail for a great promotion!',
        'title_font_container' => 'tag:h2|size:sm',               
        'subtitle' => 'Massive Elements & Unique Design',
        'subtitle_font_container' => 'tag:p',  
        'periodic_animation' => 'yes',             
        'el_class' => 'white-color'
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}