<?php
add_filter( 'perch_modules/vc/perch_faqs', 'shiftkey_vc_faqs_default_args' );
function shiftkey_vc_faqs_default_args( $args ){
	$default = array(
		'title_font_container' => 'tag:h5|size:md', 
        'subtitle_font_container' => 'tag:p',   
        'list_group' => urlencode( json_encode( array(
                    array(
                         'title' => 'What is Shiftkey?',
                         'subtitle' => 'Aliqum mullam blandit tempor sapien gravida donec ipsum, at porta justo. Velna vitae auctor eros congue magna nihil impedit ligula risus. Mauris donec ociis et magnis sapien etiam sapien sem sagittis congue tempor gravida donec enim ipsum porta justo integer at odio velna congue integer vitae auctor eros dolor luctus odio placerat massa magna ',
                    ),
                    array(
                         'title' => 'Is this available to my country?',
                         'subtitle' => 'Aliqum mullam blandit tempor sapien gravida donec ipsum, at porta justo. Velna vitae auctor eros congue magna nihil impedit ligula risus. Mauris donec ociis et magnis sapien etiam sapien sem sagittis congue tempor gravida donec enim ipsum porta justo integer at odio velna congue integer vitae auctor eros dolor luctus odio placerat massa magna ',
                    ),
                ) ) ),
        'el_id' => 'accordion',      
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}


