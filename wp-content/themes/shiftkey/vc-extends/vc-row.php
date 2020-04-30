<?php
function shiftkey_vc_row_type_options(){
    $array = array(
        '' => 'None',
        'hero' => 'Hero row',
        'content' => 'Content row', 
        'reviews' => 'Reviews row',
    );
    $new_arr = array();
    foreach ($array as $key => $value) {
        $new_arr["{$value}"] = $key;
    }
    return $new_arr;
}

function shiftkey_vc_row_class_options(){
    $array = array(
        '' => 'None',
        'content-boxes' => 'Content box',
        'content-txt' => 'Content Text', 
        'hero-txt' => 'Hero Text',
        'hero-img' => 'Hero Image',
        'bg-inner' => 'Inner bg',
        'hero-content' => 'Hero content',
        'section-content' => 'Section content',
    );
    $new_arr = array();
    foreach ($array as $key => $value) {
        $new_arr["{$value}"] = $key;
    }
    return $new_arr;
}

add_action( 'vc_after_init', 'shiftkey_vc_row_settings' );
function shiftkey_vc_row_settings($return = 0) {
    $newParamData = array(
        array(
            'type' => 'el_id',
            'heading' => esc_attr__( 'Row ID', 'shiftkey' ),
            'param_name' => 'el_id',
            'description' => sprintf( __( 'Enter section ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'shiftkey' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            'group' => 'Shiftkey Settings',
            'edit_field_class' => 'vc_col-sm-8',
            'weight' => 123
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_attr__( 'Enable outer row container?', 'shiftkey' ),
            'param_name' => 'enable_outer_container',          
            'value' => array( __( 'Checked to enable', 'shiftkey' ) => 'yes' ), 
            'group' => 'Shiftkey Settings',
            'edit_field_class' => 'vc_col-sm-4',
            'weight' => 122
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Row stretch', 'shiftkey' ),
            'param_name' => 'full_width',
            'group' => 'Shiftkey Settings',
            'value' => array(
                __( 'Default', 'shiftkey' ) => '',
                __( 'Stretch row', 'shiftkey' ) => 'stretch_row',
                __( 'Stretch row and content', 'shiftkey' ) => 'stretch_row_content',
                __( 'Stretch row and content (no paddings)', 'shiftkey' ) => 'stretch_row_content_no_spaces',
            ),
            'description' => esc_attr__( 'Select stretching options for row and content.', 'shiftkey' ),
          
        ),  
        array(
            'type' => 'checkbox',
            'heading' => esc_attr__( 'Enable inner row container?', 'shiftkey' ),
            'param_name' => 'enable_inner',
            'description' => esc_attr__( 'Checked to setup section inner bg. You can change image in Design options', 'shiftkey' ),
            'value' => array( __( 'Yes', 'shiftkey' ) => 'yes' ), 
            'group' => 'Shiftkey Settings',
            'dependency' => array(
                'element' => 'full_width',
                'value' => array('stretch_row', 'stretch_row_content')
            )
        ),
        array(
            'group' => 'Shiftkey Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Column style', 'shiftkey' ),
            'param_name' => 'column_style',
            'std' => '',
            'edit_field_class' => 'vc_col-sm-6',
            'value' => array(
                 'Default' => '',
                'Border separated' => 'border-separated-column',
                'No spacing in column' => 'no-spacing-column',
            ),
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Predefined row class', 'shiftkey' ),
            'param_name' => 'row_class',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_row_class_options(),
            'std' => '',
            'description' => '',
            'edit_field_class' => 'vc_col-sm-6',
        ), 
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Background', 'shiftkey' ),
            'param_name' => 'inner_bg_class',
            'group' => 'Row inner settings',
            'value' => shiftkey_vc_background_options(),
            'std' => 'bg-tra',
            'description' => '',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Section inner wide', 'shiftkey' ),
            'param_name' => 'inner_padding_class',
            'group' => 'Row inner settings',
            'value' => shiftkey_vc_padding_options(),
            'description' => esc_attr__( 'Section top & bottom padding', 'shiftkey' ),  
            'edit_field_class' => 'vc_col-sm-6', 
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )       
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding top', 'shiftkey' ),
            'param_name' => 'inner_padding_top',
            'group' => 'Row inner settings',
            'value' => shiftkey_vc_spacing_options('padding', 'top'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding bottom', 'shiftkey' ),
            'param_name' => 'inner_padding_bottom',
            'group' => 'Row inner settings',
            'value' => shiftkey_vc_spacing_options('padding', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),        
        
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin top', 'shiftkey' ),
            'param_name' => 'inner_margin_top',
            'group' => 'Row inner settings',
            'value' => shiftkey_vc_spacing_options('margin', 'top'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin bottom', 'shiftkey' ),
            'param_name' => 'inner_margin_bottom',
            'group' => 'Row inner settings',
            'value' => shiftkey_vc_spacing_options('margin', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ), 
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Row Background color', 'shiftkey' ),
            'param_name' => 'bg_class',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_background_options(),
            'std' => 'bg-tra',
            'description' => '',
            'edit_field_class' => 'vc_col-sm-6',
        ),           
        array(
            'type' => 'dropdown',
            'group' => 'Shiftkey Settings',
            'heading' => esc_attr__( 'Background attachment', 'shiftkey' ),
            'param_name' => 'parallax_image_attachment',
            'std' => 'cover',
            'value' => array(
                 'Default' => 'inherit',
                'Fixed' => 'fixed',
                'Scroll' => 'scroll',
                'Local' => 'local',
                'Unset' => 'inset' 
            ),
            'edit_field_class' => 'vc_col-sm-6',
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding top', 'shiftkey' ),
            'param_name' => 'padding_top',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('padding', 'top'),
            'edit_field_class' => 'vc_col-sm-6',  
            'dependency' => array(
                'element' => 'padding_class',
                'value' => array('')
            )          
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding bottom', 'shiftkey' ),
            'param_name' => 'padding_bottom',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('padding', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'padding_class',
                'value' => array('')
            )            
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin top', 'shiftkey' ),
            'param_name' => 'margin_top',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('margin', 'top'),
            'edit_field_class' => 'vc_col-sm-6',
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin bottom', 'shiftkey' ),
            'param_name' => 'margin_bottom',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('margin', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Content position', 'shiftkey' ),
            'param_name' => 'content_placement',
            'value' => array(
                __( 'Default', 'shiftkey' ) => '',
                __( 'Top', 'shiftkey' ) => 'top',
                __( 'Middle', 'shiftkey' ) => 'middle',
                __( 'Bottom', 'shiftkey' ) => 'bottom',
            ),
            'std' => 'middle',
            'description' => esc_attr__( 'Select content position within columns.', 'shiftkey' ),
            'weight' => 100
        ),
        

         
    );

    if( $return ) return $newParamData;

    foreach ( $newParamData as $key => $value ) {
        vc_update_shortcode_param( 'vc_row', $value );
        vc_update_shortcode_param( 'vc_row_inner', $value );
    } 
    

    $settings = array (
    'show_settings_on_create' => true,
    'category' => esc_attr__( 'Shiftkey', 'shiftkey' )
    );
    vc_map_update( 'vc_row_inner', $settings );
}

