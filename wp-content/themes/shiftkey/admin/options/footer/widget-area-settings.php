<?php
function shiftkey_widget_area_options( $metabox = false, $options = array() ){
	$options = array(        
        array(
            'id'            => 'footer_widget_area_column',
            'type'          => 'slider',
            'title'         => esc_attr__( 'Footer widget area column', 'shiftkey' ),
            'desc'          => esc_attr__( 'Min: 1, max: 4, step: 1, default value: 4', 'shiftkey' ),
            'default'       => 4,
            'min'           => 1,
            'step'          => 1,
            'max'           => 4,
            'resolution'    => 1,
            'display_value' => 'text',           
        ),  
        array(
            'id'       => 'footer_widget_area_column_sizes',
            'type'     => 'select',
            'title'    => __('Footer widget area column sizes', 'shiftkey'), 
            'options'  => shiftkey_footer_column_style(),
            'default'  => 'col-md-3 col-sm-6,col-md-3 col-sm-6,col-md-3 col-sm-6,col-md-3 col-sm-6',
            'required' => array('footer_widget_area_column','equals', 4),
            'width' => '100%'
        ),        
        
	);
	

    if($metabox){
        return apply_filters( 'shiftkey/redux_to_metaboxes', $options);
    }else{
        return $options;
    }
}