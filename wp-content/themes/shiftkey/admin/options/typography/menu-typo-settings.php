<?php
function shiftkey_menu_typography_options( $args = array() ){
    $options  = array(
        array(
            'id'       => 'menu_a',
            'type'     => 'typography',
            'title'    => esc_attr__( 'First lavel menu', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Specify the First lavel menu item font properties.', 'shiftkey' ),
            'font_family_clear' => true,
            'google'   => true,
            'font-backup' => false,
            'non-google' => 'Arial',
            'letter-spacing'=> true,
            'font-size'     => true,
            'line-height'   => true,
            'text-transform' => true,
            'text-align'   => false,
            'units'       => 'rem',
            'default'  => array(
                'font-weight'  => '',
                'font-family' => '',                
                'font-size'     => '',               
            ),
        ),
        array(
            'id'       => 'submenu_a',
            'type'     => 'typography',
            'title'    => esc_attr__( 'Second lavel menu', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Specify the Second lavel menu item font properties.', 'shiftkey' ),
            'font_family_clear' => true,
            'google'   => true,
            'letter-spacing'=> true,
            'font-size'     => true,
            'line-height'   => true,
            'text-transform' => true,
            'text-align'   => false,
            'units'       => 'rem',
            'default'  => array(
                'font-weight' => '',                  
                'font-size'     => '',               
            ),
        ),
        array(
            'id'       => 'submenu_ul_a',
            'type'     => 'typography',
            'title'    => esc_attr__( 'Third lavel menu', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Specify the Third lavel menu item font properties.', 'shiftkey' ),
            'font_family_clear' => true,
            'google'   => true,
            'letter-spacing'=> true,
            'font-size'     => true,
            'line-height'   => true,
            'text-transform' => true,
            'text-align'   => false,
            'units'       => 'rem',
            'default'  => array(
                'font-weight' => '',                  
                'font-size'     => '',               
            ),
        ),
    );

    return apply_filters( 'shiftkey/menu_typography_options', $options, $args );
}