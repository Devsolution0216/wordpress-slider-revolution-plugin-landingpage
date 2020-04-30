<?php
function shiftkey_copyright_bar_options( $metabox = false, $options = array() ){
    $options = array(        
        array(
             'id' => 'copyright_text',
            'title' => esc_attr__( 'Copyright Text', 'shiftkey' ),
            'desc' => '',
            'default' => '&copy; ' . date( 'Y' ).' <span>Shiftkey.</span> All Rights Reserved',
            'type' => 'editor',
            'args' => array('media_buttons' => false, 'teeny' => true, 'textarea_rows' => 2, 'wpautop' => false),
            
        ),         
        
    );

    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}