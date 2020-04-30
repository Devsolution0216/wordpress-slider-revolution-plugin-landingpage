<?php
/**
* The VC Functions
*/
add_action( 'vc_before_init', 'shiftkey_technologie_shortcode_vc' );
function shiftkey_technologie_shortcode_vc( $return = false ) {
    $args = array(
         'icon' => 'shiftkey-icon',
        'name' => esc_attr__( 'Technologies', 'shiftkey' ),
        'base' => 'shiftkey_technologie',
        'class' => 'shiftkey-vc',
        'category' => esc_attr__( 'Shiftkey', 'shiftkey' ),
        'description' => esc_attr__( 'Display image, title, description & counter ', 'shiftkey' ),
        'params' => array(
            array(
                'type' => 'checkbox',
                'heading' => esc_attr__( 'Image in left position?', 'shiftkey' ),
                'param_name' => 'position',
                'description' => esc_attr__( 'Default image in right', 'shiftkey' ),
                'value' => array( __( 'Yes', 'shiftkey' ) => 'yes' ),                                 
            ),
            array(
                'type' => 'image_upload',
                'heading' => esc_attr__( 'Image', 'shiftkey' ),
                'param_name' => 'image',
                'description' => '',
                'value' => SHIFTKEY_URI . '/images/image-13.png',                
            ),
            array(
                 'type' => 'dropdown',
                'heading' => esc_attr__( 'Display', 'shiftkey' ),
                'param_name' => 'display',
                'value' => shiftkey_vc_element_display_option(),
                'std' => 'none',
                'admin_label' => true,
                'group' => esc_attr__( 'Content bottom', 'shiftkey' ), 
            ),
            array(
                 'type' => 'textfield',
                'heading' => esc_attr__( 'Tech Title', 'shiftkey' ),
                'param_name' => 'tech_title',
                'value' => 'Technologies we use:',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display',
                    'value' => 'techs'
                ) , 
                'group' => esc_attr__( 'Content bottom', 'shiftkey' ),               
            ),
            array(
                'type' => 'dropdown',
                'param_name' => 'style',
                'value' => shiftkey_vc_color_options(true),
                'std' => 'rose',
                'heading' => esc_attr__( 'Counter color', 'shiftkey' ),
                'dependency' => array(
                    'element' => 'display',
                    'value' => 'counter'
                ) , 
                'group' => esc_attr__( 'Content bottom', 'shiftkey' ),                
            ),
            array(
                 'type' => 'textfield',
                'heading' => esc_attr__( 'Video link Title', 'shiftkey' ),
                'param_name' => 'video_link_title',
                'value' => 'Watch Our Video {duration: 2:40 min}',
                'dependency' => array(
                    'element' => 'display',
                    'value' => 'video'
                ) , 
                'group' => esc_attr__( 'Content bottom', 'shiftkey' ),               
            ),
            array(
                 'type' => 'textfield',
                'heading' => esc_attr__( 'Video link', 'shiftkey' ),
                'param_name' => 'video_link',
                'value' => 'https://www.youtube.com/embed/SZEflIVnhH8',
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'display',
                    'value' => 'video'
                ) , 
                'group' => esc_attr__( 'Content bottom', 'shiftkey' ),               
            ),
            // params group
            shiftkey_vc_techs_group(),
            shiftkey_vc_counter_group(),            
        ) 
    );

    $args = apply_filters( 'shiftkey_vc_map_filter', $args, $args['base'] );
    $args = apply_filters( 'shiftkey_vc_responsive_options', $args, $args['base'] );
    if( $return ) {
        return shiftkey_vc_get_params_value($args);
    }else{
        vc_map( $args );
    }
}
class WPBakeryShortCode_Shiftkey_technologie extends WPBakeryShortCode {
}