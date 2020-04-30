<?php
add_filter( 'rwmb_meta_boxes', 'shiftkey_register_post_format_meta_boxes' );
function shiftkey_register_post_format_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array (
		'title' => 'Post format',
		'id' => 'shiftkey_post_format',
		'post_types' => array('post'),
		'context' => 'normal',
		'priority' => 'high',
		'style' => 'seamless',
		'fields' => array(
			array (
				'id' => 'enable_lead_text',
				'type' => 'switch',
				'name' => 'Enable Lead text',
				'label_description' => esc_attr__('Display top of the post (Optional)', 'shiftkey'),				
				'size' => 80,				
			),
			array (
				'id' => 'post_lead_text',
				'type' => 'wysiwyg',
				'name' => 'Lead text',
				'label_description' => esc_attr__('Display in single post page. (Optional)', 'shiftkey'),
				'visible' => array(
					'when' => array(						
						array ('enable_lead_text','=', 1 ),
					),
					'relation' => 'and',
				),
			),
			array (
				'id' => 'enable_featured_image',
				'type' => 'switch',
				'name' => 'Display featured image',
				'label_description' => esc_attr__('Default featured image is hide in single post', 'shiftkey'),				
				'size' => 80,				
			),			
			array (
				'id' => 'enable_video_popup',
				'type' => 'switch',
				'name' => 'Enable video popup',
				'label_description' => esc_attr__('Display top of the post thumbnail (Optional)', 'shiftkey'),				
				'size' => 80,	
				'visible' => array(
					'when' => array(						
						array ('enable_featured_image','=', 1 ),
					),
					'relation' => 'and',
				),			
			),			
			array (
				'id' => 'video_link',
				'type' => 'text',
				'name' => 'Video link',
				'std' => array('https://www.youtube.com/embed/SZEflIVnhH8'),
				'label_description' => esc_attr__('Display popup video link on featured image', 'shiftkey'),				
				'size' => 80,	
				'desc' => 'Example: https://www.youtube.com/embed/SZEflIVnhH8',
				'visible' => array(
					'when' => array(						
						array ('enable_video_popup','=', 1 ),
					),
					'relation' => 'and',
				),			
			),

		),		
	);
	return $meta_boxes;
}

add_filter( 'rwmb_oembed_not_available_string',  'shiftkey_oembed_not_available_string');
function shiftkey_oembed_not_available_string( $message ) {
    $message = __('Sorry, what you are looking here is not available.', 'shiftkey');
    return $message;
}
