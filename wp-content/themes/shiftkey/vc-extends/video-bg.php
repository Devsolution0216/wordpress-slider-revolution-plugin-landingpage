<?php
add_filter( 'perch_modules/vc/perch_video_bg', 'shiftkey_vc_video_bg_default_args' );
function shiftkey_vc_video_bg_default_args( $args ){
	$default = array(
		'image' => SHIFTKEY_URI.'/images/video/video.jpg',              
        'mp4' => SHIFTKEY_URI.'/images/video/video.mp4', 
        'webm' => SHIFTKEY_URI.'/images/video/video.webm',
        'ogv' => SHIFTKEY_URI.'/images/video/video.webm',
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}