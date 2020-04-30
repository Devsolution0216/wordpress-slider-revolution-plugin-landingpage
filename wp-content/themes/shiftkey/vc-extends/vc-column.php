<?php
add_action( 'vc_after_init', 'shiftkey_vc_column_settings' );
function shiftkey_vc_column_settings() {
    $newParamData = array( 
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Predefined Column class', 'shiftkey' ),
            'param_name' => 'column_inner_style',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_column_class_options(),
            'std' => '',
            'description' => '',            
        ), 
        shiftkey_vc_column_type_params('hero_type', 'Hero style', 12, 'hero-img'), 
        shiftkey_vc_column_type_params('hero_txt_type', 'Hero style', 12, 'hero-txt'), 
        shiftkey_vc_column_type_params('content_img_type', 'Content style', 12, 'content-img'), 
        shiftkey_vc_column_type_params('content_txt_type', 'Content style', 12, 'content-txt'), 
        shiftkey_vc_column_type_params('download_img_type', 'Download style', 12, 'download-img'), 
        array(
             'group' => 'Shiftkey Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Border color', 'shiftkey' ),
            'param_name' => 'rounded_color',
            'std' => 'theme',
            'value' => shiftkey_vc_color_options(true),
            'dependency' => array(
                'element' => 'column_inner_style',
                'value' => 'box-rounded',
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Column Background', 'shiftkey' ),
            'param_name' => 'column_bg_class',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_background_options(),
            'std' => 'bg-tra',
            'description' => '' 
        )
         
    );
    foreach ( $newParamData as $key => $value ) {
        vc_update_shortcode_param( 'vc_column', $value );
        vc_update_shortcode_param( 'vc_column_inner', $value );
    }     

    
}

