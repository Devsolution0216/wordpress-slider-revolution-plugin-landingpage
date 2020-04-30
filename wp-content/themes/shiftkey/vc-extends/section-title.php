<?php
add_filter( 'perch_modules/vc/perch_section_title', 'shiftkey_vc_section_title_default_args' );
function shiftkey_vc_section_title_default_args( $args ){
	$default = array(
		'align' => 'text-center',              
        'title' => 'Packed with tons of features', 
        'title_font_container' => 'tag:h3|size:md',
        'subtitle' => 'Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero tempus, tempor posuere ligula varius',
        'subtitle_font_container' => 'tag:p|size:md',       
        'el_class' => 'section-title',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}