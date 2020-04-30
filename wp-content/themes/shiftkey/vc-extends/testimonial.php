<?php
add_filter( 'perch_modules/vc/perch_testimonial', 'shiftkey_vc_testimonial_default_args' );
function shiftkey_vc_testimonial_default_args( $args ){
	$default = array(
		'align' => '',              
        'title' => 'Super Support!', 
        'title_font_container' => 'tag:h5|size:md',
        'name' => 'Robert Peterson', 
        'name_font_container' => 'tag:h5|size:xs',
        'info' => 'SEO Manager', 
        'info_font_container' => 'tag:span',
        'subtitle' => 'An orci nullam tempor sapien, eget orci gravida donec enim ipsum porta justo integer at odio velna auctor. Magna undo ipsum vitae purus ipsum primis in laoreet augue lectus',
        'subtitle_font_container' => 'tag:p',
        'review' => 'star:star:star:star:star-half',       
        'el_class' => 'review',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}