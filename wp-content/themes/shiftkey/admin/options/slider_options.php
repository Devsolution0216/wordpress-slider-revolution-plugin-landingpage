<?php
function shiftkey_slider_options( $options = array( ) ) {
    $options = array(
         array(
             'id' => 'posts_option',
            'label' => 'Posts display option',
            'desc' => '',
            'std' => 'recent',
            'type' => 'select',
            'section' => 'front_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'choices' => array(
                 array(
                     'value' => 'recent',
                    'label' => 'Recent posts',
                    'src' => '' 
                ),
                array(
                     'value' => 'ids',
                    'label' => 'ID,s only',
                    'src' => '' 
                ),
                array(
                     'value' => 'category',
                    'label' => 'Category posts',
                    'src' => '' 
                ) 
            ) 
        ),
        array(
             'id' => 'slider_posts',
            'label' => 'Display post in slider',
            'desc' => '',
            'std' => 5,
            'type' => 'numeric-slider',
            'section' => 'front_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '1,20,1',
            'class' => '',
            'condition' => 'posts_option:not(ids)' 
        ),
        array(
             'id' => 'exclude_cat',
            'label' => 'Exclude Category',
            'desc' => 'Exclude category from recent posts.',
            'std' => '',
            'type' => 'category-checkbox',
            'section' => 'front_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'posts_option:is(recent)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'ids',
            'label' => 'Post ID,s',
            'desc' => 'You can select post,event ID.',
            'std' => '',
            'type' => 'post-checkbox',
            'section' => 'front_options',
            'rows' => '',
            'post_type' => 'post,event',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'posts_option:is(ids)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'include_cat',
            'label' => 'Include Category',
            'desc' => 'Default show recent posts from all category.',
            'std' => '',
            'type' => 'category-checkbox',
            'section' => 'front_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => 'posts_option:is(category)',
            'operator' => 'and' 
        ),
        array(
             'id' => 'category_display',
            'label' => 'Category Display',
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'front_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'class' => '',
            'operator' => 'and' 
        ) 
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'slider_options' );
}
function shiftkey_get_slider_args( $args = array( ) ) {
    $slider_posts                  = shiftkey_get_option( 'slider_posts' );
    $excerpt_display               = shiftkey_get_option( 'excerpt_display' );
    $excerpt_length                = shiftkey_get_option( 'excerpt_length' );
    $exclude_cat                   = shiftkey_get_option( 'include_cat', array( ) );
    $posts_option                  = shiftkey_get_option( 'posts_option', 'recent' );
    $args[ 'ignore_sticky_posts' ] = true;
    if ( $posts_option == 'ids' ) {
        $id = shiftkey_get_option( 'ids', array( ) );
        if ( $id ) {
            $posts_in           = array_map( 'intval', shiftkey_get_option( 'ids', array( ) ) );
            $args[ 'post__in' ] = $posts_in;
        } //$id
    } //$posts_option == 'ids'
    else {
        $args[ 'posts_per_page' ] = $slider_posts;
    }
    if ( $posts_option == 'recent' ) {
        $exclude_cat                = shiftkey_get_option( 'exclude_cat', array( ) );
        $args[ 'category__not_in' ] = $exclude_cat;
    } //$posts_option == 'recent'
    if ( $posts_option == 'category' ) {
        $include_cat            = shiftkey_get_option( 'include_cat', array( ) );
        $args[ 'category__in' ] = $include_cat;
    } //$posts_option == 'category'
    return apply_filters( 'shiftkey_get_slider_args', $args );
}