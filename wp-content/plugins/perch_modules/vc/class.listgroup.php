<?php
class PerchVcMapListGroup extends PerchVcMap{
	function __construct() {        
    }

    public static function list_group_typography( $field_id, $_heading = 'Title', $std = '' ){  

        $group = __( 'Typography settings', 'perch' );
        $param_name = $field_id. '_font_container';
        $title = sprintf(__( '%s Typography settings', 'perch' ), $_heading);
           
        $fields = array(
                'tag' => 'p', 
                'size', 
                'text_color',              
                'text_underline',                
                'text_align',
                'text_bg',
                'extra_class',
                // inline css
                'font_family',
                'font_size',
                'font_style',
                'font_size',
                'text_transform',
                'text_decoration',            
                'font_variant',
                'font_weight',
                'letter_spacing',           
                'line_height',
            );
        
        $array = array(
            'type' => 'perch_vc_typography',
            'title' => esc_attr($title),
            'param_name' => esc_attr($param_name),
            'column' => apply_filters('perch_vc_typography_fields_column', 4, $field_id),
            'std' => $std,
            'group' => $group, 
            'settings' => array(
                'fields' => $fields,
            ),
        );         
        return $array;    
    }

    public static function list_group_args(){
        $list_type_std = apply_filters( 'perch_modules/content_list/list_type/std','content-list' );
        $list_type_options = array('Content list' => 'content-list');
        $list_type_options = apply_filters( 'perch_modules/content_list/list_type/value', $list_type_options );
        $array = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'List type', 'perch' ),
                'param_name' => 'list_type',
                'std' => 'ul',
                'value' => array(
                    'Unordered list' => 'ul',
                    'Ordered list' => 'ol',
                    'Description list' => 'dl',  
                    'Custom list' => 'div',                  
                ),
                'edit_field_class' => 'vc_col-sm-3',
            ),            
            array(
                'type' => 'dropdown',
                'heading' => __( 'List icon', 'perch' ),
                'param_name' => 'icon_type',
                'std' => '',
                'value' => array(
                    'None' => '',
                    'Fontawesome icon' => 'fontawesome',
                    'Image icon' => 'image',
                ),
                'edit_field_class' => 'vc_col-sm-3', 
                'admin_label' => true 
            ),
            array(
                'type' => 'dropdown',
                'heading' => 'Bottom spacing',
                'param_name' => 'li_spacing_bottom',
                'std' => '',
                'value' => array_merge(array('Inherit' => ''), perch_vc_dropdown_options(0, 150, 5, 'px', 'mb-')),
                'edit_field_class' => 'vc_col-sm-3', 
                'description' => 'List item spacing in bottom'
            ), 
            array(
                'type' => 'dropdown',
                'heading' => 'Left spacing',
                'param_name' => 'li_spacing_left',
                'std' => '',
                'value' => array_merge(array('Inherit' => ''), perch_vc_dropdown_options(0, 150, 5, 'px', 'ml-')),
                'edit_field_class' => 'vc_col-sm-3', 
                'description' => 'List item spacing in left',
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => array('fontawesome', 'image'), 
                ),
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( 'Icon Image', 'perch' ),
                'param_name' => 'image',
                'value' => get_template_directory_uri(). '/images/icon.png',
                'dependency' => array(
                     'element' => 'icon_type',
                    'value' => 'image' 
                ),
                'edit_field_class' => 'vc_col-sm-8',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Icon size', 'perch' ),
                'param_name' => 'icon_size',
                'value' => self::vc_element_icon_size(),             
                'std' => '',
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => array('image', 'fontawesome'), 
                ),   
                'edit_field_class' => 'vc_col-sm-4',          
            ),   
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Icon color', 'perch' ),
                'param_name' => 'icon_color',
                'value' => self::vc_color_options(false),
                'std' => 'preset-color',
                'admin_label' => true,               
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => array('fontawesome'), 
                ),
                'edit_field_class' => 'vc_col-sm-4',
            ), 
            array(
                'type' => 'dropdown',
                'heading' => __( 'Predefinened list style', 'perch' ),
                'param_name' => 'predefined_list_style',
                'std' => $list_type_std,
                'value' => $list_type_options,
                'dependency' => array(
                     'element' => 'list_type',
                    'value' => 'ul' 
                ),
                'edit_field_class' => 'vc_col-sm-4',
            ),             
            self::vc_icon_set( 'fontawesome', 'icon_fontawesome', 'fa fa-check', 'icon_type' ),
            array(
                'type' => 'param_group',
                'save_always' => true,
                'heading' => __( 'List Group', 'perch' ),
                'param_name' => 'list_group',                
                'value' => urlencode( json_encode( array(
                    array(
                         'title' => 'High Converting Landing Page',
                         'subtitle' => '',
                    ),
                    array(
                         'title' => 'Beautiful User Interface',
                         'subtitle' => '',
                    ),
                ) ) ),
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => __( 'List title', 'perch' ),
                        'param_name' => 'title',
                        'description' => '',
                        'value' => 'The easy element for creativity & design',
                        'admin_label' => true 
                    ),
                     array(
                        'type' => 'textarea',
                        'heading' => __( 'Description', 'perch' ),
                        'param_name' => 'subtitle',
                        'description' => '',
                        'value' => '',
                    ),
                ),
            ),            
            self::list_group_typography('title', 'List title', 'tag:p'),
            self::list_group_typography('subtitle', 'List Sub-Title', 'tag:p'),
        );

        

        $array = apply_filters( 'perch/list_group_args', $array );

        return $array;
    }

    public static function list_group_html($atts){
        $map_arr = self::list_group_args();
        extract(PerchVcMap::get_vc_element_atts_array($map_arr));
        $_atts = $atts;
        // typo attr
        $typo_atts = $atts;
        $typo_atts['periodic_animation'] = '';
        $typo_atts['css_animation'] = '';

        extract($atts);

        $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($list_group) : array();
        if($list_type != 'dl'){
            $tag = ($list_type == 'div')? 'div' : 'li';
        }else{
           $tag = 'dd'; 
        }

       

       
        $classes = ( $list_type == 'ul' )? array($predefined_list_style) : array('perch-list');
        $classes[] = (($icon_type != '') && ($predefined_list_style == 'content-list'))?  'fa-ul' : '';  
        $classes[] = ($periodic_animation != 'yes')? self::getCSSAnimation($css_animation) : '';      
        $classes = array_filter($classes);           
        $wrapper_attributes[] = (!empty($classes))?'class="'.implode(' ', $classes).'"' : '';        
        $wrapper_attributes = array_filter($wrapper_attributes);  
        $wrapper_attributes = apply_filters( 'perch_modules/wrapper_attributes', $wrapper_attributes, $atts);           
        
        $icon_html = '';
        $title = isset($atts['title'])? $title : 'icon';

        
        $icon_classes = (($icon_type != '') && ($predefined_list_style == 'content-list'))? array( 'fa-li', 'fa-'.$icon_size ) : array();
        $icon_classes[] = ( $icon_type == 'fontawesome' )? $icon_color : '';
        $icon_classes = array_filter($icon_classes); 

  
        if( ( $icon_type == 'fontawesome' ) && ($icon_fontawesome != '') ){
            wp_enqueue_style( 'font-awesome' );
            $icon_html = '<span class="'.implode(' ', $icon_classes).'"><i class="'.$icon_fontawesome.'"></i></span>';
        }       
        if( $icon_type == 'image' ){
            $icon_html = '<span class="'.implode(' ', $icon_classes).'">
            <img src="'.esc_url($image).'" alt="'.esc_attr($title).'" width="" class="img-fluid">
        </span>';
        }

        

        $output = '<'.esc_attr($list_type).' '.implode( ' ', $wrapper_attributes ).'>';
        if(!empty($paramsArr)):
            $delay = 100;
            $paramsArr = array_filter($paramsArr);
                        
            foreach ($paramsArr as $key => $value) {                 
                $new_title = isset($value['title'])? $value['title'] : '';
                $new_subtitle = isset($value['subtitle'])? $value['subtitle'] : '';
                $typo_atts['title'] =  $new_title;                   
                $typo_atts['subtitle'] =  $new_subtitle; 
                $classes = array();

                if( ( $new_title != '') || ( $new_subtitle != '') ):                                      
                    $li_attr = array();
                    $classes = array($li_spacing_bottom);                    
                    
                    $classes = self::periodic_getCSSAnimation($classes, 'list_li', $_atts);               
                    
                   
                    $classes = array_filter($classes);
                    $li_attr[] = (!empty($classes))? 'class="'.implode(' ', $classes).'"' : ''; 
                    $li_attr = self::periodic_wrapperAttributes($li_attr, 'list_li', $_atts );                   
                    $li_attributes = implode( ' ', $li_attr );
                    
                    
                    $title_html = perch_modules_get_vc_param_html( 'title', $typo_atts );                    
                    $subtitle_html = perch_modules_get_vc_param_html('subtitle', $typo_atts);  

                    $list_atts = $atts;
                    
                    //$li_html = $icon_html.$title_html.$subtitle_html;
                    $li_html = $title_html.$subtitle_html;
                    if( $list_type == 'ul' ){
                        $list_atts['list_type'] = $predefined_list_style;
                        $li_html = apply_filters('perch_modules/content_list/output', $li_html, $list_atts );
                    }                    
                                      

                    $output .= "<{$tag} {$li_attributes}>".$li_html."</{$tag}>";
                    $delay = $delay + 100;
                endif;

            }
        endif;
        $output .= '</'.esc_attr($list_type).'>';

        return $output;

    }

    public static function accordian_group_args(){
        $array = array(
            array(
                'type' => 'dropdown',
                'heading' => 'Bottom spacing',
                'param_name' => 'li_spacing_bottom',
                'std' => '',
                'value' => array_merge(array('Inherit' => ''), perch_vc_dropdown_options(0, 150, 5, 'px', 'mb-')),                
                'description' => 'List item spacing in bottom'
            ),
            array(
                'type' => 'param_group',
                'save_always' => true,
                'heading' => __( 'List Group', 'perch' ),
                'param_name' => 'list_group',                
                'value' => urlencode( json_encode( array(
                    array(
                         'title' => 'Real Time Customization',
                         'subtitle' => 'Real time customization available in frontend & backend. You can edit element tag, class, color etc. Yoy can also enable google fonts for each element.',
                    ),
                ) ) ),
                'params' => array(
                    array(
                        'type' => 'textarea',
                        'heading' => __( 'List title', 'perch' ),
                        'param_name' => 'title',
                        'description' => '',
                        'value' => 'The easy element for creativity & design',
                        'admin_label' => true 
                    ),
                     array(
                        'type' => 'textarea',
                        'heading' => __( 'Description', 'perch' ),
                        'param_name' => 'subtitle',
                        'description' => '',
                        'value' => '',
                    ),
                ),
            ),
            self::list_group_typography('title', 'List title', 'tag:p'),
            self::list_group_typography('subtitle', 'List Sub-Title', 'tag:p'),
        );

        

        $array = apply_filters( 'perch/accordian_group_args', $array );

        return $array;
    }

    public static function accordian_group_html($atts){
        $_atts = $atts;
        $map_arr = self::list_group_args();
        extract(PerchVcMap::get_vc_element_atts_array($map_arr)); 
        // typo attr
        $typo_atts = $atts;
        $typo_atts['periodic_animation'] = '';
        $typo_atts['css_animation'] = '';

        extract($atts);
        $list_type = $tag = 'div';

        $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($list_group) : array();

       
        $classes = array('card-wrapper');
        $classes[] = ($icon_type != '')?  'fa-ul' : '';        
        $classes = array_filter($classes);
        $wrapper_attributes[] = (!empty($classes))?'class="'.implode(' ', $classes).'"' : '';        
        $wrapper_attributes = array_filter($wrapper_attributes);  
        $wrapper_attributes = apply_filters( 'perch_modules/wrapper_attributes', $wrapper_attributes, $atts);           
        
        $icon_html = '';
        $title = isset($atts['title'])? $title : 'icon';

        
        $icon_classes = array( 'fa-li', 'fa-'.$icon_size );        
        $icon_classes[] = ( $icon_type == 'fontawesome' )? $icon_color : '';
        $icon_classes = array_filter($icon_classes); 

  
        if( ( $icon_type == 'fontawesome' ) && ($icon_fontawesome != '') ){
            wp_enqueue_style( 'font-awesome' );
            $icon_html = '<span class="'.implode(' ', $icon_classes).'"><i class="'.$icon_fontawesome.'"></i></span>';
        }       
        if( $icon_type == 'image' ){
            $icon_html = '<span class="'.implode(' ', $icon_classes).'">
            <img src="'.esc_url($image).'" alt="'.esc_attr($title).'" width="" class="img-fluid">
        </span>';
        }

        

        $output = '<'.esc_attr($list_type).' '.implode( ' ', $wrapper_attributes ).'>';
        if(!empty($paramsArr)):
            $delay = 100;
            $paramsArr = array_filter($paramsArr);
            $count = 1;            
            foreach ($paramsArr as $key => $value) {                 
                $new_title = isset($value['title'])? $value['title'] : '';
                $new_subtitle = isset($value['subtitle'])? $value['subtitle'] : '';
                $typo_atts['title'] =  $new_title;                   
                $typo_atts['subtitle'] =  $new_subtitle; 
                $classes = array('card');

                if( ( $new_title != '') || ( $new_subtitle != '') ):                                      
                    $li_attr = array();
                    $classes = array('card', $li_spacing_bottom); 
                    
                    $classes = self::periodic_getCSSAnimation($classes, 'list_li', $_atts);               
                    
                   
                    $classes = array_filter($classes);
                    $li_attr[] = (!empty($classes))? 'class="'.implode(' ', $classes).'"' : ''; 
                    $li_attr = self::periodic_wrapperAttributes($li_attr, 'list_li', $_atts );                   
                    $li_attributes = implode( ' ', $li_attr );

                    $uniqid = uniqid();
                    $heading_id = 'heading-'.$count.$uniqid;                  
                    $heading_class = ($count == 1)?  '' : 'collapsed';               
                    $collapse_id = 'collapse-'.$count.$uniqid;
                    $show = ($count == 1)?  ' show' : '';   
                    $typo_atts['title'] = '<a class="'.esc_attr($heading_class).'" data-toggle="collapse" href="#'.$collapse_id.'" role="button" aria-expanded="true" aria-controls="'.$collapse_id.'">'.$typo_atts['title'].'</a>';
                    
                    
                    $title_html = perch_modules_get_vc_param_html( 'title', $typo_atts );                    
                    $subtitle_html = perch_modules_get_vc_param_html('subtitle', $typo_atts);  
                    
                                 

                    $output .= "<{$tag} {$li_attributes}>
                        <div class='card-header' role='tab' id='{$heading_id}'>{$title_html}</div>
                        <div id='{$collapse_id}' class='collapse{$show}' role='tabpanel' aria-labelledby='{$heading_id}' data-parent='#{$el_id}'>
                        <div class='card-body'>{$subtitle_html}</div>
                        </div>
                    </{$tag}>";
                    $delay = $delay + 100;
                    $count++;
                endif;                

            }
        endif;
        $output .= '</'.esc_attr($list_type).'>';

        return $output;

    }


}
new PerchVcMapListGroup();