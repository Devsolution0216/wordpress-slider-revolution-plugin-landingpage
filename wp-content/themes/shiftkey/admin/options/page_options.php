<?php
function shiftkey_page_options( $options = array( ) ) {
    $options = array(
         array(
             'id' => 'show_breadcrumbs',
            'label' => esc_attr__( 'Show Breadcrumbs', 'shiftkey' ),
            'desc' => '',
            'std' => 'on',
            'type' => 'on-off',
            'section' => 'page_options',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => '',
            'operator' => 'and' 
        ) 
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'page_options' );
}
?>