<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function vc_add_ie9_degradation() {
	wp_enqueue_style( 'vc_lte_ie9' );
}

add_action( 'vc_backend_editor_footer_render', 'vc_add_ie9_degradation' );
