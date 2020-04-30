<?php
function shiftkey_header_navbar_icons_options( $metabox = false, $args = array( ) ) {
	  

    $options =  array( 
        array(
             'id' => 'header_search_display',
            'title' => esc_attr__( 'Navbar Search icon display', 'shiftkey' ),
            'desc' => '',
            'default' => false,
            'type' => 'switch',          
        ),
        array(
             'id' => 'nav_search_placeholder',
            'title' => esc_attr__( 'Navbar Search placeholder text', 'shiftkey' ),
            'desc' => '',
            'default' => 'What are you looking for?',
            'type' => 'text',
            'required' => array('header_search_display', '=', true),
        ),
        array(
             'id' => 'header_social_icons_display',
            'title' => esc_attr__( 'Social Icons display', 'shiftkey' ),
            'desc' => '',
            'default' => false,
            'type' => 'switch',          
        ),
        array(
            'id'       => 'header_social_icons',
            'type'     => 'select',
            'multi'    => true,
            'title'    => __('Social Icons', 'shiftkey'),            
            'desc'     => sprintf(__('You can set up your social settings <a target="_blank" href="%s">here</a>', 'shiftkey'), admin_url( 'admin.php?page=shiftkey-settings#tab-social_settings' )),           
            'options'  => shiftkey_supported_social_links_callback(),
            'default'  => array('facebook','twitter'),
            'required' => array('header_social_icons_display', '=', true),
        ),
        array(
             'id' => 'header_button_display',
            'title' => esc_attr__( 'Header button display', 'shiftkey' ),
            'desc' => '',
            'default' => false,
            'type' => 'switch',          
        ),
        array(
            'id'       => 'header_buttons',
            'type'     => 'select',
            'multi'    => true,
            'title'    => __('Header buttons', 'shiftkey'),            
            'desc'     => sprintf(__('You can set up your button settings <a target="_blank" href="%s">here</a>', 'shiftkey'), admin_url( 'admin.php?page=shiftkey-settings#tab-button_settings' )),           
            'options'  => shiftkey_supported_buttons_callback(),
            'default'  => array('contact_us'),
            'required' => array('header_button_display', '=', true),
        ),
    );    


    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}