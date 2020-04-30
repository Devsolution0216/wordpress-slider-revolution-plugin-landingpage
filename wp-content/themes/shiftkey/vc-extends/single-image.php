<?php
add_action( 'vc_after_init', 'shiftkey_hero_image_shortcode_vc' );
function shiftkey_hero_image_shortcode_vc() {

	$args = array(
		'name' => esc_attr__( 'Single Image', 'shiftkey' ),
		'base' => 'vc_single_image',
		'icon' => 'shiftkey-icon',
		'category' => esc_attr__( 'Shiftkey', 'shiftkey' ),
		'description' => esc_attr__( 'Simple image with CSS animation', 'shiftkey' ),
		'params' => array(			
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Image source', 'shiftkey' ),
				'param_name' => 'source',
				'std' => 'external_link',
				'value' => array(
					__( 'Media library', 'shiftkey' ) => 'media_library',
					__( 'External link', 'shiftkey' ) => 'external_link',
					__( 'Featured Image', 'shiftkey' ) => 'featured_image',
				),
				'description' => esc_attr__( 'Select image source.', 'shiftkey' ),
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_attr__( 'Image', 'shiftkey' ),
				'param_name' => 'image',
				'value' => '',
				'description' => esc_attr__( 'Select image from media library.', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => 'media_library',
				),
				'admin_label' => true,
			),
			array(
				'type' => 'image_upload',
				'heading' => esc_attr__( 'External link', 'shiftkey' ),
				'param_name' => 'custom_src',
				'value' => SHIFTKEY_URI. '/images/image-01.png',
				'description' => esc_attr__( 'Select external link.', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => 'external_link',
				),
				'admin_label' => true,
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Image size', 'shiftkey' ),
				'param_name' => 'img_size',
				'std' => 'full',
				'value' => array_flip( shiftkey_get_image_sizes_Arr() ),
				'description' => esc_attr__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => array(
						'media_library',
						'featured_image',
					),
				),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_attr__( 'Image size', 'shiftkey' ),
				'param_name' => 'external_img_size',
				'value' => '',
				'description' => esc_attr__( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => 'external_link',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_attr__( 'Caption', 'shiftkey' ),
				'param_name' => 'caption',
				'description' => esc_attr__( 'Enter text for image caption.', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => 'external_link',
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_attr__( 'Add caption?', 'shiftkey' ),
				'param_name' => 'add_caption',
				'description' => esc_attr__( 'Add image caption.', 'shiftkey' ),
				'value' => array( __( 'Yes', 'shiftkey' ) => 'yes' ),
				'dependency' => array(
					'element' => 'source',
					'value' => array(
						'media_library',
						'featured_image',
					),
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Image alignment', 'shiftkey' ),
				'param_name' => 'alignment',
				'value' => array(
					__( 'Left', 'shiftkey' ) => 'left',
					__( 'Right', 'shiftkey' ) => 'right',
					__( 'Center', 'shiftkey' ) => 'center',
				),
				'description' => esc_attr__( 'Select image alignment.', 'shiftkey' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Image style', 'shiftkey' ),
				'param_name' => 'style',
				'value' => vc_get_shared( 'single image styles' ),
				'description' => esc_attr__( 'Select image display style.', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => array(
						'media_library',
						'featured_image',
					),
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Image style', 'shiftkey' ),
				'param_name' => 'external_style',
				'value' => vc_get_shared( 'single image external styles' ),
				'description' => esc_attr__( 'Select image display style.', 'shiftkey' ),
				'dependency' => array(
					'element' => 'source',
					'value' => 'external_link',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Border color', 'shiftkey' ),
				'param_name' => 'border_color',
				'value' => vc_get_shared( 'colors' ),
				'std' => 'grey',
				'dependency' => array(
					'element' => 'style',
					'value' => array(
						'vc_box_border',
						'vc_box_border_circle',
						'vc_box_outline',
						'vc_box_outline_circle',
						'vc_box_border_circle_2',
						'vc_box_outline_circle_2',
					),
				),
				'description' => esc_attr__( 'Border color.', 'shiftkey' ),
				'param_holder_class' => 'vc_colored-dropdown',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Border color', 'shiftkey' ),
				'param_name' => 'external_border_color',
				'value' => vc_get_shared( 'colors' ),
				'std' => 'grey',
				'dependency' => array(
					'element' => 'external_style',
					'value' => array(
						'vc_box_border',
						'vc_box_border_circle',
						'vc_box_outline',
						'vc_box_outline_circle',
					),
				),
				'description' => esc_attr__( 'Border color.', 'shiftkey' ),
				'param_holder_class' => 'vc_colored-dropdown',
			),
			array(
                'type' => 'checkbox',
                'heading' => esc_attr__( 'Force image to overflow container?', 'shiftkey' ),
                'param_name' => 'max_width',
                'description' => esc_attr__( 'Checked to force image to overflow container.', 'shiftkey' ),
                'value' => array( __( 'Yes', 'shiftkey' ) => 'yes' ),  
                'admin_label' => true,
            ),
            array(
                'type' => 'checkbox',
                'heading' => esc_attr__( 'Display image as background image', 'shiftkey' ),
                'param_name' => 'image_as_bg',
                'description' => esc_attr__( 'Checked to force image to overflow container.', 'shiftkey' ),
                'value' => array( __( 'Yes', 'shiftkey' ) => 'yes' ),  
                'admin_label' => true,
            ),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'On click action', 'shiftkey' ),
				'param_name' => 'onclick',
				'value' => array(
					__( 'None', 'shiftkey' ) => '',
					__( 'Link to large image', 'shiftkey' ) => 'img_link_large',
					__( 'Open prettyPhoto', 'shiftkey' ) => 'link_image',
					__( 'Open custom link', 'shiftkey' ) => 'custom_link',
					__( 'Zoom', 'shiftkey' ) => 'zoom',
					__( 'Video', 'shiftkey' ) => 'video',
				),
				'description' => esc_attr__( 'Select action for click action.', 'shiftkey' ),
				'std' => '',
				'group' => esc_attr__('On click action', 'shiftkey'),
			),
			array(
				'type' => 'href',
				'heading' => esc_attr__( 'Video link', 'shiftkey' ),
				'param_name' => 'video_link',
				'value' => 'https://www.youtube.com/embed/SZEflIVnhH8',
				'description' => esc_attr__( 'Enter URL if you want this image to have a popup video link', 'shiftkey' ),
				'dependency' => array(
					'element' => 'onclick',
					'value' => 'video',
				),
				'group' => esc_attr__('On click action', 'shiftkey'),
			),
			array(
	             'type' => 'dropdown',
	            'heading' => esc_attr__( 'Video icon color', 'shiftkey' ),
	            'param_name' => 'icon_class',	            
	            'value' => shiftkey_vc_global_color_options(),
	            'std' => 'theme',
	            'description' => '',
	            'dependency' => array(
					'element' => 'onclick',
					'value' => 'video',
				), 
				'group' => esc_attr__('On click action', 'shiftkey'),
	        ),
			array(
				'type' => 'href',
				'heading' => esc_attr__( 'Image link', 'shiftkey' ),
				'param_name' => 'link',
				'description' => esc_attr__( 'Enter URL if you want this image to have a link (Note: parameters like "mailto:" are also accepted).', 'shiftkey' ),
				'dependency' => array(
					'element' => 'onclick',
					'value' => 'custom_link',
				),
				'group' => esc_attr__('On click action', 'shiftkey'),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_attr__( 'Link Target', 'shiftkey' ),
				'param_name' => 'img_link_target',
				'value' => shiftkey_target_param_list(),
				'dependency' => array(
					'element' => 'onclick',
					'value' => array(
						'custom_link',
						'img_link_large',
					),
				),
				'group' => esc_attr__('On click action', 'shiftkey'),
			),
			shiftkey_vc_animation_type(),
			array(
				'type' => 'el_id',
				'heading' => esc_attr__( 'Element ID', 'shiftkey' ),
				'param_name' => 'el_id',
				'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'shiftkey' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_attr__( 'Extra class name', 'shiftkey' ),
				'param_name' => 'el_class',
				'value' => '',
				'description' => esc_attr__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'shiftkey' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => esc_attr__( 'CSS box', 'shiftkey' ),
				'param_name' => 'css',
				'group' => esc_attr__( 'Design Options', 'shiftkey' ),
			),
			// backward compatibility. since 4.6
			array(
				'type' => 'hidden',
				'param_name' => 'img_link_large',
			),
		),
	);
	
	$newParamData = $args['params'];

 	foreach ( $newParamData as $key => $value ) {
        vc_update_shortcode_param( 'vc_single_image', $value );
    } //$newParamData as $key => $value

    
}