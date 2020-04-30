<?php
function shiftkey_general_options( $options = array() ) {
    $_options = array(
        array(
            'id'       => 'shiftkey_layout_style',
            'type'     => 'button_set',
            'title'    => esc_attr__( 'Global layout design', 'shiftkey' ), 
            'desc'    => esc_attr__('Globally effect on theme buttons, form elements etc', 'shiftkey'),          
            'options'  => array(
                'rounded' => 'Rounded',
                'semirounded' => 'Semi-rounded',
                'flat' => 'Flat'
            ),
            'default'  => 'semirounded'
        ),
        array(
            'id'       => 'preloader_display',
            'type'     => 'button_set',
            'title'    => esc_attr__( 'Preloader display', 'shiftkey' ),           
            'options'  => array(
                'none' => 'None',
                'default' => 'Default preloader',
                'custom' => 'Custom preloader'
            ),
            'default'  => 'default'
        ),
        
    );
    $options = array(                   
        array(
             'id' => 'custom_preloader',
            'label' => esc_attr__( 'Custom preloader image', 'shiftkey' ),
            'desc' => '',
            'std' => SHIFTKEY_URI . '/images/preloader.png',
            'type' => 'upload',
            'section' => 'general_options',
            'condition' => 'preloader_display:is(custom)',
            'operator' => 'and' 
        ),                      
        array(
             'id' => 'google_map_api',
            'label' => esc_attr__( 'Google map API', 'shiftkey' ),
            'desc' => 'Authentication for the standard API - API keys. <br><a class="button" href="//console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank"><strong>Get an API key</strong></a>',
            'std' => '',
            'type' => 'text',
            'section' => 'general_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
            'id'       => 'social_links',
            'type'     => 'select',
            'multi'    => true,
            'title'    => esc_attr__('Social Links', 'shiftkey'),            
            'desc'     => sprintf(__('You can set up your social settings <a target="_blank" href="%s">here</a>', 'shiftkey'), admin_url( 'admin.php?page=shiftkey-settings#tab-social_settings' )),           
            'options'  => shiftkey_supported_social_links_callback(),
            'default'  => shiftkey_general_options_social_link(),            
        ),
        array(
             'id' => 'search_placeholder',
            'label' => esc_attr__( 'Search Placeholder Text', 'shiftkey' ),
            'desc' => '',
            'std' => 'Search...',
            'type' => 'text',
            'section' => 'general_options',
            'condition' => '',
            'operator' => 'and' 
        ),       
        
    );
    $options =  apply_filters( 'shiftkey_theme_options', $options, 'general_options' );

    return array_merge($_options, $options);
}

function shiftkey_general_advanced_options(){

    $options = array( 
        array(
             'id' => 'admin_logo',
            'label' => esc_attr__( 'Admin logo', 'shiftkey' ),
            'desc' => '',
            'std' => SHIFTKEY_URI . '/images/logo.png',
            'type' => 'upload',
            'section' => 'general_options',
            'condition' => '',
            'operator' => 'and' 
        ), 
        array(
            'id'          => 'vc_admin_view',
            'label'       => esc_attr__( 'Visual composer element preview', 'shiftkey' ),
            'desc'        => esc_attr__('Only worked in VC admin page edit', 'shiftkey'),
            'std'         => 'full',
            'type'        => 'select',
            'section'     => 'general_options',
            'class'       => '',
            'condition'   => '',
            'operator'    => 'and',
            'choices'   => array(
                array(
                    'label' => 'Full preview',
                    'value' => 'full' 
                    ),
                array(
                    'label' => 'Simple preview',
                    'value' => 'simple' 
                    ),
                )
        ),
        
    );

    return  apply_filters( 'shiftkey_theme_options', $options, 'general_options' );

}