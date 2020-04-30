<?php
add_filter( 'rwmb_meta_boxes', 'shiftkey_register_template_settings_meta_boxes' );
function shiftkey_register_template_settings_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array (
		'title' => 'Template settings',
		'id' => 'template-settings',
		'post_types' => array( 'page',),
		'context' => 'side',
		'priority' => 'high',
		'default_hidden' => false,
		'fields' => array(
			array (
				'id' => 'shiftkey_template_type',
				'name' => 'Choose template type',
				'type' => 'button_group',
				'options' => array(
					'default' => 'Default',
					'landing' => 'Front page',
				),
				'std' => 'default',
			),
			array (
				'id' => 'layout_type',
				'type' => 'image_select',
				'std' => array( 'full' ),
				'options' => array(
					'full' => get_template_directory_uri(). '/admin/assets/images/layout/full-width.png',
					'ls' => get_template_directory_uri(). '/admin/assets/images/layout/left-sidebar.png',
					'rs' => get_template_directory_uri(). '/admin/assets/images/layout/right-sidebar.png',
				),
				'required' => 1,
				'visible' => array(
					'when' => array(						
						array ( 'shiftkey_template_type', '!=', 'landing' )
					),
					'relation' => 'and',
				),
			),
			array (
                'id' => 'one_page_wp_nav',
                'type' => 'select',
                'column' => 'column-1',
                'name' => esc_attr__('Landing page Nav menu', 'shiftkey'),
                'placeholder' => 'Select nav menu',
                'options' => shiftkey_get_terms_choices('nav_menu'),
                'tab' => 'General_settings',
                'visible' => array(
                    'when' => array(
                        array ('shiftkey_template_type','=','landing'),
                    ),
                    'relation' => 'and',
                ),
            ),
			array(
			    'name'        => 'Sidebar',
			    'id'          => 'sidebar',
			    'type'        => 'sidebar',
			    // Field type.
			    'field_type'  => 'select',
			    // Placeholder.
			    'placeholder' => 'Select a sidebar',
			    'std' => 'sidebar-page',
			    'visible' => array(
					'when' => array(						
						array ( 'layout_type', '!=', 'full' ),
						array ( 'shiftkey_template_type', '!=', 'landing' )
					),
					'relation' => 'and',
				),
			),

			array(
			    'id'        => 'predefined_template',
			    'name'      => 'Enable Predefined template?',
			    'type'      => 'switch',			    
			    // Style: rounded (default) or square
			    'style'     => 'square',
			    // On label: can be any HTML
			    'on_label'  => 'Yes',
			    // Off label
			    'off_label' => 'No',
			    'visible' => array(
					'when' => array(						
						array (
							'shiftkey_template_type',
							'!=',
							'landing',
						),
					),
					'relation' => 'and',
				),
			),
			array (
				'id' => 'predefined_layout',
				'type' => 'select',
				'std' => array( 'blog' ),
				'options' => shiftkey_predefined_page_templates_options(),
				'required' => 1,
				'visible' => array(
					'when' => array(						
						array (
							'predefined_template',
							'=',
							true,
						),
					),
					'relation' => 'and',
				),
			),
			array (
				'id' => 'container_spacing',
				'type' => 'slider',
				'name' => 'Container wide',
				'prefix' => 'Wide ',
    			'suffix' => '',
    			'std' => '60',
				'js_options' => array(
			        'min'   => 0,
			        'max'   => 200,
			        'step'  => 10,
			    ),
			    'visible' => array(
					'when' => array( array( 'predefined_template', '=', false ) ),
					'relation' => 'and',
				),
			),
			
		),
	);
	return $meta_boxes;
}