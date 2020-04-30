<?php
wp_link_pages( array(					
	'nextpagelink'     => esc_attr__( 'Next', 'shiftkey'),
	'previouspagelink' => esc_attr__( 'Previous', 'shiftkey' ),
	'pagelink'         => '%',
	'echo'             => 1
) );
?>