<?php
add_filter( 'perch_modules/vc/perch_content_text', 'shiftkey_vc_content_text_default_args' );
function shiftkey_vc_content_text_default_args( $args ){
	$default = array(
        'name' => 'Welcome to Shiftkey', 
        'name_font_container' => 'tag:span|text_color:theme-color|extra_class:section-id',        
        'title' => 'We are making design better for everyone', 
        'title_font_container' => 'tag:h3|size:md',
        'subtitle' => 'An enim nullam tempor sapien gravida donec pretium ipsum porta justo integer at odio velna vitae auctor integer congue magna purus pretium ligula rutrum luctus ultrice aliquam a augue suscipit',
        'textfield_area' => 'Features Never Stop',
        'textarea_field' => 'Semper lacus cursus porta, feugiat primis in ultrice ligula tempus auctor ipsum and mauris lectus enim ipsum enim gravida purus pretium ligula',
        'subtitle_font_container' => 'tag:p',
        'counter_typo' => 'tag:h3|size:sm',
        'enable_content' => 'yes',
        'periodic_animation' => '',
        'animation_interval' => '100',
        'el_class' => 'content-txt txt-block pc-30',  
        'testimonial_avatar' => SHIFTKEY_URI. '/images/quote-avatar.jpg',     
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}
