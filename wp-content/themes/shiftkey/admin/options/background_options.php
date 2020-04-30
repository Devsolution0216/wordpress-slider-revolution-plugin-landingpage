<?php
function shiftkey_background_options( $options = array( ) ) {
    $options = array(
        array(
             'id' => 'container_width',
            'label' => esc_attr__( 'Container width', 'shiftkey' ),
            'desc' => '',
            'std' => array( '1140',  'px' ),
            'type' => 'measurement',
            'section' => 'background_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '320,2000,1',
            'class' => '',
            'condition' => '',
            'operator' => 'and',
            'action' => array( ) 
        ),
        array(
             'id' => 'body_background',
            'label' => esc_attr__( 'Body background', 'shiftkey' ),
            'desc' => '',
            'std' => '',
            'type' => 'background',
            'section' => 'background_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'operator' => 'and',
            'action' => array( ) 
        ),
        array(
             'id' => 'title_display',
            'label' => esc_attr__( 'Banner display', 'shiftkey' ),
            'desc' => esc_attr__('You can edit banner option from each page.', 'shiftkey'),
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'background_options',
        ),
        array(
             'id' => 'header_bg_img',
            'label' => esc_attr__( 'Header Default background image', 'shiftkey' ),
            'desc' => '',
            'std' => ShiftkeyHeader::get_default_header_image(),
            'type' => 'upload',
            'section' => 'background_options',
            'condition' => '',
            'operator' => 'and' 
        ), 
        array(
            'id'          => 'breadcrumbs_overlay_type',
            'label'       => esc_attr__( 'Breadcrumbs overlay type', 'shiftkey' ),
            'std'         => apply_filters( 'shiftkey_breadcrumbs_overlay_type', 'light'),
            'type'        => 'radio',
            'section'     => 'background_options',
            'operator'    => 'and',
            'choices'     => array(                 
              array(
                'value'       => 'light',
                'label'       => esc_attr__( 'Light', 'shiftkey' ),
              ),
              array(
                'value'       => 'dark',
                'label'       => esc_attr__( 'Dark', 'shiftkey' ),
              ),
              array(
                'value'       => 'theme',
                'label'       => esc_attr__( 'Preset color', 'shiftkey' ),
              ),
            )
        ), 
        array(
             'id' => 'overlay_opacity',
            'label' => esc_attr__( 'Breadcrumbs overlay opacity', 'shiftkey' ),
            'desc' => '',
            'std' => '0',
            'type' => 'numeric-slider',
            'section' => 'background_options',
            'min_max_step' => '0,100,1',
            'condition' => '',
            'operator' => 'and' 
        ),
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'background_options' );
}
?>