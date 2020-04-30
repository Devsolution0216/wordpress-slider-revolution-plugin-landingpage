<?php
function shiftkey_blog_options( $options = array( ) ) {
    $options = array(        
        array(
             'id' => 'blog_layout',
            'label' => esc_attr__( 'Blog page layout', 'shiftkey' ),
            'desc' => 'Optional. Only work, When Posts page is not selected in Settings > Reading.',
            'std' => 'rs',
            'type' => 'radio-image',
            'section' => 'blog_options',
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
             'id' => 'blog_layout_sidebar',
            'label' => esc_attr__( 'Blog Sidebar', 'shiftkey' ),
            'desc' => '',
            'std' => 'sidebar-post',
            'type' => 'sidebar-select',
            'section' => 'blog_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'blog_layout:not(full)',
            'operator' => 'and' 
        ),
         array(
             'id' => 'sticky_post_text',
            'label' => esc_attr__( 'Sticky post text', 'shiftkey' ),
            'desc' => '',
            'std' => 'Sticky',
            'type' => 'text',
            'section' => 'blog_options',
            'condition' => '',
            'operator' => 'and' 
        ),
         array(
             'id' => 'latest_article_title_display',
            'label' => esc_attr__( 'Latest Articles title display', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'blog_options' 
        ),
         array(
             'id' => 'latest_article_title',
            'label' => esc_attr__( 'Latest Articles title', 'shiftkey' ),
            'desc' => '',
            'std' => 'Latest Articles',
            'type' => 'text',
            'section' => 'blog_options',
            'condition'   => 'latest_article_title_display:is(on)', 
            'operator' => 'and' 
        ),
         array(
             'id' => 'popular_post_display',
            'label' => esc_attr__( 'Most Read Articles display', 'shiftkey' ),
            'desc' => '',
            'std' => 'off',
            'type' => 'on-off',
            'section' => 'blog_options' 
        ),
         array(
             'id' => 'popular_title',
            'label' => esc_attr__( 'Most Read Articles', 'shiftkey' ),
            'desc' => '',
            'std' => 'Most Read Articles',
            'type' => 'text',
            'section' => 'blog_options',            
            'operator' => 'and',
            'condition'   => 'popular_post_display:is(on)', 
        ),
        /* array(
             'id' => 'post_meta_display',
            'label' => esc_attr__( 'Post meta display', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'blog_options' 
        ),*/
        /*array(
            'id'          => 'post_meta',
            'label'       => esc_attr__( 'Post meta options', 'shiftkey' ),
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
        array(
             'id' => 'excerpt_length',
            'label' => esc_attr__( 'Excerpt Length', 'shiftkey' ),
            'desc' => '',
            'std' => '40',
            'type' => 'text',
            'section' => 'blog_options',
            'min_max_step' => '1,150,1',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'excerpt_length_sm',
            'label' => esc_attr__( 'Excerpt Length for small box', 'shiftkey' ),
            'desc' => '',
            'std' => '30',
            'type' => 'text',
            'section' => 'blog_options',
            'min_max_step' => '1,150,1',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'read_more_text',
            'label' => esc_attr__( 'Read more text', 'shiftkey' ),
            'desc' => '',
            'std' => 'More Details',
            'type' => 'text',
            'section' => 'blog_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => 'next_post_text',
            'label' => esc_attr__( 'Next post text', 'shiftkey' ),
            'desc' => '',
            'std' => 'Next',
            'type' => 'text',
            'section' => 'blog_options' 
        ),
        array(
             'id' => 'prev_post_text',
            'label' => esc_attr__( 'Previous post text', 'shiftkey' ),
            'desc' => '',
            'std' => 'Previous',
            'type' => 'text',
            'section' => 'blog_options' 
        ),
        
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'blog_options' );
}

foreach ( glob( SHIFTKEY_DIR . "/admin/options/blog/*-settings.php" ) as $filename ) {
    if( file_exists($filename) ){
        load_template($filename);
    }    
}