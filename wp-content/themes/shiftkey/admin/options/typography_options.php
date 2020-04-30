<?php
function shiftkey_recognized_font_families( $families ) {
    $families[ 'roboto' ] = 'Roboto';
    $families[ 'montserrat' ] = 'Montserrat';
    return $families;
}
add_filter( 'ot_recognized_font_families', 'shiftkey_recognized_font_families' );
function shiftkey_filter_typography_fields( $array, $field_id ) {
    if ( $field_id == "primary_font" ) {
        $array = array(
             'font-family' 
        );
    } //$field_id == "primary_font"
    if ( $field_id == "secondary_font" ) {
        $array = array(
             'font-family' 
        );
    } //$field_id == "secondary_font"
    return $array;
}

function shiftkey_typography_options( $args = array() ) {    

    $options  = array(
        array(
            'id'       => 'body',
            'type'     => 'typography',
            'title'    => esc_attr__( 'Body Font - Primary font', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Specify the global body font properties.', 'shiftkey' ),
            'font_family_clear' => false,
            'google'   => true,
            'font-backup' => true,
            'letter-spacing'=> false,
            'font-size'     => true,
            'line-height'   => false,
            'text-align'   => false,
            'units'       => 'px',
            'default'  => array(
                'color'       => '#333',
                'font-weight'  => '300',
                'font-family' => 'Roboto',                 
                'font-size'     => '16px',               
            ),
        ),
        array(
            'id'       => 'heading',
            'type'     => 'typography',
            'title'    => esc_attr__( 'Heading Font - secondary font', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Specify the heading font properties.', 'shiftkey' ),
            'google'   => true,
            'font-backup' => true,
            'letter-spacing'=> true,
            'font-size'     => false,
            'line-height'   => false,
            'text-align'   => false,
            'color' => false,
            'units'       => 'rem',
        ), 
        array(
            'id'       => 'logo_text_typo',
            'type'     => 'typography',
            'title'    => esc_attr__( 'Text Logo typography settings', 'shiftkey' ),
            'subtitle' => esc_attr__( 'Specify the Logo font properties.', 'shiftkey' ),
            'google'   => true,
            'letter-spacing'=> true,
            'font-size'     => true,
            'font-style'     => true,
            'text-transform' => true,
            'line-height'   => true,
            'text-align'   => true,
            'units'       => 'rem',
            'default'  => array(                       
                'font-size'     => '3rem',               
            ),
            'preview' => array(
                'text' => get_bloginfo( 'name' ),
                'font-size' => '3rem',
                'always_display' => true
            ),
        ),
    );
    return $options;
}

foreach ( glob( SHIFTKEY_DIR . "/admin/options/typography/*-settings.php" ) as $filename ) {
    include $filename;
}