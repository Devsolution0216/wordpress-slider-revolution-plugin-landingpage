<?php
function shiftkey_post_options( $options = array( ) ) {
    $options = array(
         array(
             'id' => 'single_post_header_style',
            'label' => esc_attr__( 'Post header style', 'shiftkey' ),
            'desc' => '',
            'std' => 'banner',
            'type' => 'select',
            'section' => 'post_options',
            'condition' => '',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'label' => 'Slider Style 1',
                    'value' => 'style1' 
                ),
                array(
                     'label' => 'Slider Style 2',
                    'value' => 'style2' 
                ),
                array(
                     'label' => 'Custom Shortcode',
                    'value' => 'shortcode' 
                ),
                array(
                     'label' => 'Leave blank',
                    'value' => 'empty' 
                ) 
            ) 
        ),
        array(
             'id' => 'single_post_shortcode',
            'label' => esc_attr__( 'Post Banner Shortcode', 'shiftkey' ),
            'desc' => esc_attr__( 'Use custom shortcode, you can create shortcode using revoulation slider.', 'shiftkey' ),
            'std' => '',
            'type' => 'text',
            'section' => 'post_options',
            'condition' => 'single_post_header_style:is(shortcode)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_layout',
            'label' => esc_attr__( 'Single post layout', 'shiftkey' ),
            'desc' => '',
            'std' => 'rs',
            'type' => 'radio-image',
            'section' => 'post_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => '',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'value' => 'full',
                    'label' => esc_attr__( 'Full width', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/full-width.png' 
                ),
                array(
                     'value' => 'ls',
                    'label' => esc_attr__( 'Left sidebar', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/left-sidebar.png' 
                ),
                array(
                     'value' => 'rs',
                    'label' => esc_attr__( 'Right sidebar', 'shiftkey' ),
                    'src' => OT_URL . '/assets/images/layout/right-sidebar.png' 
                ) 
            ) 
        ),
        array(
             'id' => 'single_layout_sidebar',
            'label' => esc_attr__( 'Single post Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-1',
            'type' => 'sidebar-select',
            'section' => 'post_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'single_layout:not(full)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_post_sharing',
            'label' => esc_attr__( 'Sharing Icon in single post', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'post_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_related_posts',
            'label' => esc_attr__( 'Show Related posts', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'post_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_related_posts_title',
            'label' => esc_attr__( 'Related posts title', 'shiftkey' ),
            'desc' => '',
            'std' => esc_attr__( 'Related Posts', 'shiftkey' ),
            'type' => 'text',
            'section' => 'post_options',
            'condition' => 'single_related_posts:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_total_related_posts',
            'label' => esc_attr__( 'Related posts display maximum', 'shiftkey' ),
            'desc' => '',
            'std' => '3',
            'type' => 'numeric-slider',
            'section' => 'post_options',
            'min_max_step' => '1,12,1',
            'condition' => 'single_related_posts:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_related_posts_column',
            'label' => esc_attr__( 'Related posts column', 'shiftkey' ),
            'desc' => '',
            'std' => '3',
            'type' => 'numeric-slider',
            'section' => 'post_options',
            'min_max_step' => '1,4,1',
            'condition' => 'single_related_posts:is(on)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'single_post_options_css',
            'label' => esc_attr__( 'CSS', 'shiftkey' ),
            'class' => 'hide-field',
            'desc' => '',
            'std' => '

.blog-header{

   {{single_post_header_background}}

} 



',
            'type' => 'css',
            'section' => 'post_options',
            'rows' => '20',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'condition' => '',
            'operator' => 'and' 
        ) 
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'post_options' );
}
?>