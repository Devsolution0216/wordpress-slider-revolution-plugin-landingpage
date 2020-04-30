<?php
function shiftkey_quick_contact_form_options( $metabox = false, $options = array() ){
	$old_options = array(        
        array(
             'id' => 'quickform_title',
            'label' => esc_attr__( 'Contact form title', 'shiftkey' ),
            'std' => esc_attr__( 'Send a Message', 'shiftkey' ),
            'type' => 'text',
            'section' => 'footer_options',
            'operator' => 'and' 
        ),
        array(
             'id' => 'title_avatar',
            'label' => esc_attr__( 'Title avatar', 'shiftkey' ),
            'std' => SHIFTKEY_URI.'/images/assistant-avatar.jpg',
            'type' => 'upload',
            'section' => 'footer_options',
            'operator' => 'and' 
        ),
        array(
             'id' => 'quickform_shortcode',
            'label' => esc_attr__( 'Contact form Shortcode', 'shiftkey' ),
            'std' => '',
            'desc' => esc_attr__('Choose shortcode from Contact form 7.', 'shiftkey').' <a href="'.admin_url('admin.php?page=wpcf7').'" target="_blank">'.esc_attr__('Click here', 'shiftkey').'</a>',
            'type' => 'text',
            'section' => 'footer_options',
            'operator' => 'and' 
        ),
	);

	// Filter for option tree to redux options
    $modyfied_option = apply_filters( 'shiftkey_theme_options', $old_options, 'footer_options' );
    $options = array_merge( $options, $modyfied_option );

    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}