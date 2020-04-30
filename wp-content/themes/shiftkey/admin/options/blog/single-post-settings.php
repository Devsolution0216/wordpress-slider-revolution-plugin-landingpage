<?php
function shiftkey_single_post_options( $metabox = false, $args = array( ) ) {
	$options = array();

	$options = array(
        array(
             'id' => 'single_post_header',
            'label' => esc_attr__( 'Single post header', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'blog_options' 
        ),
        array(
             'id' => 'single_layout',
            'label' => esc_attr__( 'Single post layout', 'shiftkey' ),
            'desc' => '',
            'std' => 'full',
            'type' => 'radio-image',
            'section' => 'blog_options',
            'operator' => 'and',
            'choices' => array(
                 array(
                     'value' => 'full',
                    'label' => esc_attr__( 'Full width', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/full-width.png' 
                ),
                array(
                     'value' => 'ls',
                    'label' => esc_attr__( 'Left sidebar', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/left-sidebar.png' 
                ),
                array(
                     'value' => 'rs',
                    'label' => esc_attr__( 'Right sidebar', 'shiftkey' ),
                    'src' => SHIFTKEY_URI . '/admin/assets/images/layout/right-sidebar.png' 
                ) 
            ) 
        ),
        array(
             'id' => 'single_layout_sidebar',
            'label' => esc_attr__( 'Single post Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-post',
            'type' => 'sidebar-select',
            'section' => 'blog_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'single_layout:not(full)',
            'operator' => 'and' 
        ),
        /*array(
            'id'          => 'single_post_meta',
            'label'       => esc_attr__( 'Single post meta options', 'shiftkey' ),
            'std'         => array('date', 'category', 'comment'),
            'type'        => 'checkbox',
            'section'     => 'blog_options',
            'condition'   => 'post_meta_display:is(on)',
            'operator'    => 'and',
            'choices'     => array(                 
              array(
                'value'       => 'date',
                'label'       => esc_attr__( 'Post date', 'shiftkey' ),
              ),
              array(
                'value'       => 'category',
                'label'       => esc_attr__( 'Post category', 'shiftkey' ),
              ),
              array(
                'value'       => 'author',
                'label'       => esc_attr__( 'Post author', 'shiftkey' ),
              ),
              array(
                'value'       => 'comment',
                'label'       => esc_attr__( 'Post comments', 'shiftkey' ),
              )
            )
        ),*/
        array(
             'id' => 'single_post_share',
            'label' => esc_attr__( 'Single post share', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'blog_options' 
        ),
        array(
             'id' => 'realted_post_display',
            'label' => esc_attr__( 'Display related posts', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'blog_options' 
        ),
        array(
             'id' => 'related_title',
            'label' => esc_attr__( 'Related Posts title', 'shiftkey' ),
            'desc' => '',
            'std' => 'Related Posts',
            'type' => 'text',
            'section' => 'blog_options',
            'condition' => 'realted_post_display:is(on)' 
        ),
        array(
             'id' => 'realted_post_base',
            'label' => esc_attr__( 'Related posts based on', 'shiftkey' ),
            'desc' => '',
            'std' => 'tag',
            'type' => 'select',
            'section' => 'blog_options',
            'condition' => 'realted_post_display:is(on)', 
            'choices' => array(
                array( 'label' => 'Tags',  'value' => 'tag' ),
                array( 'label' => 'Category',  'value' => 'category' ),
            )
        ),
        /*array(
            'id'          => 'related_post_meta',
            'label'       => esc_attr__( 'Related post meta options', 'shiftkey' ),
            'std'         => array('date', 'category'),
            'type'        => 'checkbox',
            'section'     => 'blog_options',
            'condition'   => 'post_meta_display:is(on)',
            'operator'    => 'and',
            'choices'     => array(                 
              array(
                'value'       => 'date',
                'label'       => esc_attr__( 'Post date', 'shiftkey' ),
              ),
              array(
                'value'       => 'category',
                'label'       => esc_attr__( 'Post category', 'shiftkey' ),
              ),
              array(
                'value'       => 'author',
                'label'       => esc_attr__( 'Post author', 'shiftkey' ),
              ),
              array(
                'value'       => 'comment',
                'label'       => esc_attr__( 'Post comments', 'shiftkey' ),
              )
            )
        ),*/
        
    );
    $options = apply_filters( 'shiftkey_theme_options', $options, 'blog_options' );

	if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}