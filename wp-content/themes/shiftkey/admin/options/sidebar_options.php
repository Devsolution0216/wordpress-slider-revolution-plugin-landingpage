<?php
function shiftkey_sidebar_options( $options = array( ) ) {
    $options = array(
         array(
             'id' => 'create_sidebar',
            'label' => esc_attr__( 'Create Sidebar', 'shiftkey' ),
            'desc' => 'You must save after create sidebar, otherwise creared sidebar is not populated in Sidebar selection dropdown list.',
            'std' => '',
            'type' => 'list-item',
            'section' => 'sidebar_option',
            'rows' => '',
            'post_type' => '',
            'taxonomy' => '',
            'min_max_step' => '',
            'class' => '',
            'condition' => '',
            'operator' => 'and',
            'settings' => array(
                 array(
                     'id' => 'desc',
                    'label' => esc_attr__( 'Description', 'shiftkey' ),
                    'desc' => esc_attr__( '(optional)', 'shiftkey' ),
                    'std' => '',
                    'type' => 'text',
                    'rows' => '',
                    'post_type' => '',
                    'taxonomy' => '',
                    'min_max_step' => '',
                    'class' => '',
                    'condition' => '',
                    'operator' => 'and' 
                ) 
            ) 
        ),
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'sidebar_options' );
}
?>