<?php
function shiftkey_footer_settings_meta_boxes() {
	$meta_boxes = array(                              
            array (
                'id' => 'custom_footer_settings',
                'type' => 'switch',
                'name' => 'Custom footer settings',
                'desc' => 'By default all options comes from Theme options.',
                'style' => 'square',
                'on_label' => 'ON',
                'off_label' => 'OFF',
                'tab' => 'footer_settings',
            ),                
            array (
                'id' => 'footer_settings_group',
                'type' => 'group',
                'fields' => shiftkey_footer_options(true),
                'std' => shiftkey_get_metabox_group_std(shiftkey_footer_options(true)),
                'default_state' => 'expanded',
                'collapsible' => true,
                'save_state' => true,
                'group_title' => 'Custom Footer settings',
                'tab' => 'footer_settings',
                'visible' => array(
                    'when' => array(
                        array ( 'custom_footer_settings', '=', 1),
                    ),
                    'relation' => 'and',
                ),
            ),
            array (
                'id' => 'divider_10',
                'type' => 'divider',
                'name' => 'Divider',
                'tab' => 'footer_settings',
            ),

            
        );
	

	return $meta_boxes;
}