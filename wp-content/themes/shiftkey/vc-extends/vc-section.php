<?php
function shiftkey_vc_section_type_options(){
    $array = array(
        '' => 'None',
        'hero' => 'Hero section', 
        'services' => 'Services section', 
        'content' => 'Content section', 
        'video' => 'Video section', 
        'reviews' => 'Reviews section', 
        'brands' => 'Brands section', 
        'pricing' => 'Pricing section', 
        'download' => 'Download section', 
        'faqs' => 'Faqs section', 
        'contacts' => 'Contacts section',
        'cta' => 'Cta section',
        'services' => 'Services section',
        'footer' => 'Footer section', 
    );
    $new_arr = array();
    foreach ($array as $key => $value) {
        $new_arr["{$value}"] = $key;
    }
    return $new_arr;
}

function shiftkey_vc_section_class_options(){
    $array = array(
        'None' => '',
        'Hero content' => 'hero-content',
    );

    return $array;
}

add_action( 'vc_after_init', 'shiftkey_vc_section_settings' );
function shiftkey_vc_section_settings($return = 0) {

    
    $newParamData = array( 
        array(
            'type' => 'el_id',
            'heading' => esc_attr__( 'Section ID', 'shiftkey' ),
            'param_name' => 'el_id',
            'description' => sprintf( __( 'Enter section ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'shiftkey' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            'group' => 'Shiftkey Settings',
            'edit_field_class' => 'vc_col-sm-8',
            'weight' => 130
        ),  
        array(
            'type' => 'textfield',
            'heading' => esc_attr__( 'Extra class name', 'shiftkey' ),
            'param_name' => 'el_class',
            'description' => esc_attr__( 'Use this field to add a class name, refer to it in your css file. E.g: white-color', 'shiftkey' ),
            'group' => 'Shiftkey Settings',
            'weight' => 125,
            'edit_field_class' => 'vc_col-sm-4',
        ),    
        array(
            'group' => 'Shiftkey Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Section stretch', 'shiftkey' ),
            'param_name' => 'full_width',
            'weight' => 120,
            'value' => array(
                 __( 'Default', 'shiftkey' ) => 'container',
                __( 'Stretch section', 'shiftkey' ) => 'container-wide' 
            ),
            'description' => esc_attr__( 'Select stretching options for section and content (Note: stretched may not work properly if parent container has "overflow: hidden" CSS property).', 'shiftkey' ),
            'edit_field_class' => 'vc_col-sm-8',             
        ), 
        array(
            'type' => 'checkbox',
            'param_name' => 'enable_inner',
            'description' => esc_attr__( 'Checked to setup section inner bg. You can change image in Design options', 'shiftkey' ),
            'value' => array( __( 'Enable section inner', 'shiftkey' ) => 'yes' ), 
            'group' => 'Shiftkey Settings',
            'edit_field_class' => 'vc_col-sm-4',
            'weight' => 119,
        ),       
        array(
            'group' => 'Shiftkey Settings',
            'type' => 'dropdown',
            'weight' => 118,
            'heading' => esc_attr__( 'Pre-defined Section type', 'shiftkey' ),
            'param_name' => 'section_type',
            'value' => shiftkey_vc_section_type_options(),
            'std' => '',
            'description' => esc_attr__( 'Predefined section setup section spacing, background image etc.', 'shiftkey' ),
            'edit_field_class' => 'vc_col-sm-8', 
        ),
        shiftkey_vc_section_type_params('hero_type', 'Hero style', 18, 'hero'),        
        shiftkey_vc_section_type_params('services_type', 'Services style', 8, 'services'),        
        shiftkey_vc_section_type_params('content_type', 'Content style', 10, 'content'),        
        shiftkey_vc_section_type_params('video_type', 'Video style', 3, 'video'),        
        shiftkey_vc_section_type_params('reviews_type', 'Reviews style', 3, 'reviews'),        
        shiftkey_vc_section_type_params('brands_type', 'Brands style', 3, 'brands'),        
        shiftkey_vc_section_type_params('pricing_type', 'Pricing style', 2, 'pricing'),        
        shiftkey_vc_section_type_params('download_type', 'Download style', 3, 'download'),        
        shiftkey_vc_section_type_params('faqs_type', 'Faqs style', 3, 'faqs'),        
        shiftkey_vc_section_type_params('contacts_type', 'Contacts style', 6, 'contacts'),
        shiftkey_vc_section_type_params('cta_type', 'Cta style', 4, 'cta'),
        shiftkey_vc_section_type_params('services_type', 'Services style', 8, 'services'),
        shiftkey_vc_section_type_params('footer_type', 'Footer style', 4, 'footer'),
        array(
            'type' => 'el_id',
            'heading' => esc_attr__( 'Section inner ID', 'shiftkey' ),
            'param_name' => 'inner_el_id',
            'description' => sprintf( __( 'Enter section ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'shiftkey' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            'group' => 'Section inner settings',
            'edit_field_class' => 'vc_col-sm-6', 
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )           
        ),  
        array(
            'type' => 'textfield',
            'heading' => esc_attr__( 'Extra class for section inner', 'shiftkey' ),
            'param_name' => 'inner_el_class',
            'description' => esc_attr__( 'Use this field to add a class name, refer to it in your css file. E.g: white-color', 'shiftkey' ),
            'group' => 'Section inner settings',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ), 
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Background', 'shiftkey' ),
            'param_name' => 'inner_bg_class',
            'group' => 'Section inner settings',
            'value' => shiftkey_vc_background_options(),
            'std' => 'bg-tra',
            'description' => '',
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Section inner wide', 'shiftkey' ),
            'param_name' => 'inner_padding_class',
            'group' => 'Section inner settings',
            'value' => shiftkey_vc_padding_options(),
            'description' => esc_attr__( 'Section top & bottom padding', 'shiftkey' ),  
            'edit_field_class' => 'vc_col-sm-6', 
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )       
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding top', 'shiftkey' ),
            'param_name' => 'inner_padding_top',
            'group' => 'Section inner settings',
            'value' => shiftkey_vc_spacing_options('padding', 'top'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding bottom', 'shiftkey' ),
            'param_name' => 'inner_padding_bottom',
            'group' => 'Section inner settings',
            'value' => shiftkey_vc_spacing_options('padding', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),        
        
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin top', 'shiftkey' ),
            'param_name' => 'inner_margin_top',
            'group' => 'Section inner settings',
            'value' => shiftkey_vc_spacing_options('margin', 'top'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin bottom', 'shiftkey' ),
            'param_name' => 'inner_margin_bottom',
            'group' => 'Section inner settings',
            'value' => shiftkey_vc_spacing_options('margin', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'enable_inner',
                'value' => 'yes'
            )
        ), 
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Section Background', 'shiftkey' ),
            'param_name' => 'bg_class',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_background_options(),
            'std' => 'bg-white',
            'description' => '',
            'edit_field_class' => 'vc_col-sm-6',
        ),           
        array(
            'type' => 'dropdown',
            'group' => 'Shiftkey Settings',
            'heading' => esc_attr__( 'Background attachment', 'shiftkey' ),
            'param_name' => 'parallax_image_attachment',
            'std' => 'cover',
            'value' => array(
                 'Default' => 'inherit',
                'Fixed' => 'fixed',
                'Scroll' => 'scroll',
                'Local' => 'local',
                'Unset' => 'inset' 
            ),
            'edit_field_class' => 'vc_col-sm-6',
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding top', 'shiftkey' ),
            'param_name' => 'padding_top',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('padding', 'top'),
            'edit_field_class' => 'vc_col-sm-6',  
            'dependency' => array(
                'element' => 'padding_class',
                'value' => array('')
            )          
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Padding bottom', 'shiftkey' ),
            'param_name' => 'padding_bottom',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('padding', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
            'dependency' => array(
                'element' => 'padding_class',
                'value' => array('')
            )            
        ),        
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Section wide', 'shiftkey' ),
            'param_name' => 'padding_class',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_padding_options(),
            'std'  => 'wide-60',
            'description' => esc_attr__( 'Section top & bottom padding', 'shiftkey' ),          
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin top', 'shiftkey' ),
            'param_name' => 'margin_top',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('margin', 'top'),
            'edit_field_class' => 'vc_col-sm-6',
        ),
        array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Margin bottom', 'shiftkey' ),
            'param_name' => 'margin_bottom',
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_spacing_options('margin', 'bottom'),
            'edit_field_class' => 'vc_col-sm-6',
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Parallax', 'shiftkey' ),
            'param_name' => 'parallax',
            'std' => '',
            'weight' => 1,
            'value' => array(
                __( 'None', 'shiftkey' ) => '',
                __( 'Simple', 'shiftkey' ) => 'content-moving',
                __( 'With fade', 'shiftkey' ) => 'content-moving-fade',
            ),
            'description' => esc_attr__( 'Add parallax type background for section (Note: If no image is specified, parallax will use background image from Design Options).', 'shiftkey' ),
            'dependency' => array(
                'element' => 'video_bg',
                'is_empty' => true,
            ),
        ),       
        array(
             'type' => 'image_upload',
            'heading' => esc_attr__( 'Image', 'shiftkey' ),
            'param_name' => 'parallax_image',
            'weight' => 119,
            'value' => SHIFTKEY_URI . '/images/banner-1.jpg',
            'description' => esc_attr__( 'Select image from media library.', 'shiftkey' ),
            'dependency' => array(
                 'element' => 'parallax',
                'not_empty' => true 
            ) 
        ),
        array(
            'group' => 'Parallax Settings',
            'type' => 'textfield',
            'heading' => esc_attr__( 'Parallax background image opacity', 'shiftkey' ),
            'param_name' => 'parallax_image_opacity',
            'value' => '1',      
            'description' => esc_attr__( 'Maximum value 1', 'shiftkey' ),
            'dependency' => array(
                 'element' => 'parallax',
                'not_empty' => true 
            ),
            'edit_field_class' => 'vc_col-sm-6',  
        ),
        array(
             'group' => 'Parallax Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Parallax width', 'shiftkey' ),
            'param_name' => 'parallax_width',
            'std' => '100%',
            'value' => array(
                 '100%' => '100%',
                '75%' => '75%',
                '50%' => '50%',
                '25%' => '25%' 
            ),
            'dependency' => array(
                 'element' => 'parallax',
                'not_empty' => true 
            ),
            'edit_field_class' => 'vc_col-sm-6',  
        ),
        array(
             'group' => 'Parallax Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Parallax background image size', 'shiftkey' ),
            'param_name' => 'parallax_image_size',
            'std' => 'cover',
            'value' => array(
                 'Cover' => 'cover',
                'Contain' => 'contain',
                'Auto' => 'auto',
                '25% auto' => '25% auto',
                '50% auto' => '50% auto',
                'auto 50%' => 'auto 50%',
                'auto 25%' => 'auto 25%' 
            ),
            'dependency' => array(
                 'element' => 'parallax',
                'not_empty' => true 
            ) ,
            'edit_field_class' => 'vc_col-sm-6', 
        ),
        array(
             'group' => 'Parallax Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Parallax background image repeat', 'shiftkey' ),
            'param_name' => 'parallax_image_repeat',
            'std' => 'cover',
            'value' => array(
                 'Default' => '',
                'No Repeat' => 'no-repeat',
                'Repeat' => 'repeat' 
            ),
            'dependency' => array(
                 'element' => 'parallax',
                'not_empty' => true 
            ) ,
            'edit_field_class' => 'vc_col-sm-6', 
        ),
        array(
             'group' => 'Parallax Settings',
            'type' => 'dropdown',
            'heading' => esc_attr__( 'Parallax background image position', 'shiftkey' ),
            'param_name' => 'parallax_image_position',
            'std' => 'cover',
            'value' => array(
                 'Default' => '50% 0',
                'Center' => 'center',
                'Top center' => 'top center',
                'Bottom center' => 'bottom center',
                'Top left' => 'top left',
                'Bottom left' => 'bottom left',
                'Top right' => 'top right',
                'Bottom right' => 'bottom right' 
            ),
            'dependency' => array(
                 'element' => 'parallax',
                'not_empty' => true 
            ),
            'edit_field_class' => 'vc_col-sm-6',  
        ),
         
    );

    if( $return ) return $newParamData;

    foreach ( $newParamData as $key => $value ) {
        vc_update_shortcode_param( 'vc_section', $value );
    } //$newParamData as $key => $value 

   

    $settings = array (
        'show_settings_on_create' => true,
      'category' => esc_attr__( 'Shiftkey', 'shiftkey' )
    );
    vc_map_update( 'vc_section', $settings ); 


}

