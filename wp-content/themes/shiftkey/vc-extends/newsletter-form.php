<?php
add_filter( 'perch_modules/vc/perch_newsletter_form', 'shiftkey_vc_newsletter_form_default_args' );
function shiftkey_vc_newsletter_form_default_args( $args ){
	$default = array(
		'align' => 'text-center',
        'name' => 'Your name',        
        'subtitle' => 'We don\'t share your personal information with anyone. Check out our <a href="#">Privacy Policy</a> for more information',
        'subtitle_font_container' => 'tag:p|size:sm',  
        'button_text' => 'Start Your Free Trial',  
        'el_class' => '',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}