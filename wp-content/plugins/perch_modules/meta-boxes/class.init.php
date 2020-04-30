<?php
class Perch_Modules_Meta_Settings{

	function __construct(){
		add_filter( 'rwmb_meta_boxes', array( __CLASS__, 'perch_modules_register_settings' ) );
		add_filter( 'perch_modules/array_key_move_to_value', array( __CLASS__, 'array_key_move_to_value' ) );
		add_filter( 'perch_modules/supported_social_links_meta', array( __CLASS__, 'supported_social_links_meta' ), 9, 2 );
		add_filter( 'perch_modules/supported_buttons_meta', array( __CLASS__, 'supported_buttons_meta' ), 9, 2 );				
	}

	public static function array_key_move_to_value( $array ){
		$array = array_filter($array);
		if( !empty($array) ){
			$newarray = array();
			foreach ($array as $key => $value) {
				$value['key'] = $key;
				$newarray[] = $value;
			}
			$array = $newarray;
		}

		return $array;
	}

	public static function supported_buttons_meta( $array,  $meta ){
		if( !empty($meta) ):
		foreach ($meta as $value) {
			$key = $value['key'];
			if(isset($array[$key])){
				$array[$key] = shortcode_atts($array[$key], $value);
			}
		}
		endif;
		return $array;
	}

	public static function supported_social_links_meta( $array,  $meta ){
		if( !empty($meta) ):
		foreach ($meta as $value) {
			$key = $value['key'];
			if(isset($array[$key])){
				$array[$key] = shortcode_atts($array[$key], $value);
			}
		}
		endif;
		return $array;
	}

	public static function supported_social_links( $index = true ){
		$array = array();
		$array = apply_filters( 'perch_modules/supported_social_links', $array );
		
		if( function_exists('rwmb_meta') ):
		$meta = rwmb_meta( 'social_links_group', array( 'object_type' => 'setting' ), perch_modules_settings_name() );		
		$array = apply_filters( 'perch_modules/supported_social_links_meta', $array, $meta );
		endif;	
	
		$array = array_filter($array);

		if( $index ) return $array;
		else return apply_filters( 'perch_modules/array_key_move_to_value', $array );
		
	}

	

	public static function supported_buttons( $index = true ){
		$array = array();
		$array = apply_filters( 'perch_modules/supported_buttons', $array );
		
		if( function_exists('rwmb_meta') ):
		$meta = rwmb_meta( 'buttons_group', array( 'object_type' => 'setting' ), perch_modules_settings_name() );		
		$array = apply_filters( 'perch_modules/supported_buttons_meta', $array, $meta );
		endif;
		
		$array = array_filter($array);	

		if( $index ) return $array;
		else return apply_filters( 'perch_modules/array_key_move_to_value', $array );
	}	
	

	public static function perch_modules_register_settings( $meta_boxes ) {
		$meta_boxes[] = array (
			'id'             => 'social_settings',
        	'title'          => 'Social settings',
        	'settings_pages' => perch_modules_settings_page(),
        	'tab'            => 'social_settings',
			'fields' => array(
				array (
                    'id' => 'social_links_group',
                    'type' => 'group',
                    'std' => self::supported_social_links(false),
                    'fields' => array(
                    	array (
							'id' => 'key',
							'type' => 'hidden',
							'name' => 'Hidden',
							'std' => '',
						),
                    	array(
                            'name' => 'Icon',
                            'id'   => 'icon',
                            'type' => 'iconpicker',
                            'std' => '',
                            'columns' => 2,
                        ),
                        array (
                            'id' => 'title',
                            'type' => 'text',
                            'name' => 'Title',
                            'required' => 1,
                            'std' => '',
                            'columns' => 4,
                        ),
                        array (
                            'id' => 'url',
                            'type' => 'text',
                            'name' => 'URL',
                            'required' => 1,
                            'std' => '',
                            'columns' => 6,
                            'size' => 50,
                        ),                        
                    ),
                    'clone' => true,
                    'default_state' => 'expanded',
                    'collapsible' => true,
                    'max_clone' => 100,
                    'clone_as_multiple' => false,
                    'sort_clone' => true,
                    'add_button' => 'Add new social link',
                    'group_title' => array( 'field' => 'title',  ),
                    'save_field' => true,   
                    'save_state' => true, 
                ),
            ),			
		);			
			
		/*$meta_boxes[] = array(
	        'id'             => 'info',
	        'title'          => 'About social settings',
	        'context'        => 'side',
	        'settings_pages' => array(sanitize_title(perch_modules_current_theme().'-social-settings')),
	        'collapsible' => false,
	        'style'       => 'no-boxes',
	        'fields'         => array(
	            array(
	                'type' => 'custom_html',
	                'std'  => 'A responsive theme for businesses and agencies.',
	            )
	        ),
	    );*/

		$meta_boxes[] = array (
			'id'             => 'button_settings',
        	'title'          => 'Button settings',
        	'settings_pages' => perch_modules_settings_page(),
        	'tab'            => 'button_settings',
			'fields' => array(
				
				array (
					'id' => 'buttons_group',
					'type' => 'group',	
					'std' => self::supported_buttons(false),				
					'fields' => array(	
						array (
							'id' => 'key',
							'type' => 'hidden',
							'name' => 'Hidden',
							'std' => '',
						),
						array (
							'id' => 'name',
							'type' => 'text',
							'name' => 'Name',
							'std' => 'Button name',
						),					
						array(
	                        'name'    => 'Button type',
	                        'id'      => 'type',
	                        'std'     => 'text',
	                        'type'    => 'select',
	                        'options' => array(
	                            'text'  => 'Text',
	                            'image' => 'Image',
	                        ),
	                    ),
	                    array(
				            'type' => 'file_input',
				            'name' => __( 'Button Image', 'perch' ),
				            'id' => 'image',
				            'value' => apply_filters( 'perch_modules/perch_buttons/image/std', '' ),
				            'visible' => array( 'type', '=',  'image'),
				        ),
				        array(
						    'name' => 'Width',
						    'id'   => 'image_size',
						    'type' => 'text',
						    'prefix' => 'max: ',
						    'suffix' => ' px',
						    'js_options' => array(
						        'min'   => 100,
						        'max'   => 400,
						        'step'  => 5,
						    ),
						    'std' => apply_filters( 'perch_modules/perch_buttons/image_width/std', 160 ),
						    'visible' => array( 'type', '=',  'image'),
						),
				        array (
							'id' => 'title',
							'type' => 'text',
							'name' => 'Button Text',
							'std' => apply_filters( 'perch_modules/perch_buttons/title/std', 'Button text' ),						
						),				        
						array (
							'id' => 'url',
							'type' => 'text',
							'name' => 'URL',
						),
						array(
	                        'name'    => 'Button target',
	                        'id'      => 'target',
	                        'std'     => '_self',
	                        'type'    => 'select',
	                        'options' => array(
	                            '_self'  => 'Open link in a self tab',
	                            '_blank' => 'Open link in a new tab',
	                        ),
	                    ),
	                    array(
	                        'name'    => 'Button color',
	                        'id'      => 'style',
	                        'type'    => 'select',
	                        'options' => apply_filters( 'perch_modules/perch_buttons/color/options', array( 'Default') ),
	                        'visible' => array( 'type', '=',  'text'),
	                    ),
	                    array(
	                        'name'    => 'Button size',
	                        'id'      => 'size',
	                        'type'    => 'select',
	                        'options' => apply_filters( 'perch_modules/perch_buttons/size/options', array( 'Default') ),
	                        'visible' => array( 'type', '=',  'text'),
	                    ),	                   
	                    
					),
					'clone' => 1,
					'default_state' => 'collapsed',
					'collapsible' => true,
					'max_clone' => 100,
					'clone_as_multiple' => false,
					'sort_clone' => false,
					'add_button' => 'Add New Button',
					'group_title' => array( 'field' => 'name' ),
					'save_state' => false,					
				),
			),				
			
		);
		return $meta_boxes;
	}
}
new Perch_Modules_Meta_Settings();