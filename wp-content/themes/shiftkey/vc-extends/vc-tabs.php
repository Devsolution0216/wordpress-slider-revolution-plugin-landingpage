<?php
add_action( 'vc_after_init', 'shiftkey_vc_tta_tabs_settings' );
function shiftkey_vc_tta_tabs_settings( ) {
	$value = array(
			'type' => 'dropdown',
			'param_name' => 'style',
			'value' => array(
				'Shiftkey style1' => 'shiftkey',
				'Shiftkey style2' => 'shiftkey-style2',
				__( 'Classic', 'shiftkey' ) => 'classic',
				__( 'Modern', 'shiftkey' ) => 'modern',
				__( 'Flat', 'shiftkey' ) => 'flat',
				__( 'Outline', 'shiftkey' ) => 'outline',
			),
			'heading' => esc_attr__( 'Style', 'shiftkey' ),
			'description' => esc_attr__( 'Select tabs display style.', 'shiftkey' ),
		);
	vc_update_shortcode_param( 'vc_tta_tabs', $value );
}