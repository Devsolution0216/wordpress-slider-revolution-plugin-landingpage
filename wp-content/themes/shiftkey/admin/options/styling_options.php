<?php
function shiftkey_styling_options( $options = array( ) ) {
    $options = array(
         array(
             'id' => 'primary_color',
            'label' => esc_attr__( 'Primary color', 'shiftkey' ),
            'desc' => '',
            'std' => shiftkey_primary_color(),
            'type' => 'colorpicker',
            'section' => 'styling_options',
            'operator' => 'and' 
        ),
        array(
             'id' => 'secondary_color',
            'label' => esc_attr__( 'Secondary color', 'shiftkey' ),
            'desc' => '',
            'std' => shiftkey_secondary_color(),
            'type' => 'colorpicker',
            'section' => 'styling_options',
            'operator' => 'and' 
        ),
        array(
             'id' => 'dark_color',
            'label' => esc_attr__( 'Dark color', 'shiftkey' ),
            'desc' => '',
            'std' => shiftkey_dark_color(),
            'type' => 'colorpicker',
            'section' => 'styling_options',
            'operator' => 'and' 
        ),
        array(
             'id' => 'grey_color',
            'label' => esc_attr__( 'Grey color', 'shiftkey' ),
            'desc' => '',
            'std' => shiftkey_grey_color(),
            'type' => 'colorpicker',
            'section' => 'styling_options',
            'operator' => 'and' 
        ),
        array(
             'id' => 'lightgrey_color',
            'label' => esc_attr__( 'Light-grey color', 'shiftkey' ),
            'desc' => '',
            'std' => shiftkey_light_grey_color(),
            'type' => 'colorpicker',
            'section' => 'styling_options',
            'operator' => 'and' 
        ) 
    );
    return apply_filters( 'shiftkey_theme_options', $options, 'styling_options' );
}
?>