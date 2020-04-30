<?php
function shiftkey_404_options( $options = array( ) ) {
    $options = array(
         array(
             'id' => '404_title',
            'label' => esc_attr__( 'Large Title', 'shiftkey' ),
            'desc' => '',
            'std' => '404',
            'type' => 'text',
            'section' => '404_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => '404_subtitle',
            'label' => esc_attr__( 'Subtitle', 'shiftkey' ),
            'desc' => '<strong>{}</strong> use this symbol to highlight text',
            'std' => '{Sorry}, The page was not found',
            'type' => 'text',
            'section' => '404_options',
            'condition' => '',
            'operator' => 'and' 
        ),
        array(
             'id' => '404_content',
            'label' => esc_attr__( '404 Content', 'shiftkey' ),
            'desc' => '',
            'std' => '',
            'type' => 'textarea',
            'section' => '404_options',
            'condition' => '',
            'operator' => 'and' 
        ) 
    );
    return apply_filters( 'shiftkey_theme_options', $options, '404_options' );
}
?>