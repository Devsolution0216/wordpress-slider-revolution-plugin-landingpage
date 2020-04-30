<?php
add_filter( 'perch_modules/icon_type_options', 'shiftkey_vc_icon_type_options' );
function shiftkey_vc_icon_type_options($args){    
    $args['Flaticon'] = 'flaticon';
    return $args;
}

add_filter( 'perch_modules/icon_type_std', 'shiftkey_vc_icon_type_std' );
function shiftkey_vc_icon_type_std($std){
    $std = 'flaticon';
    return $std;
}

add_filter('perch_modules/vc_icon_type_element', 'shiftkey_vc_tonicon_icon_type_element');
function shiftkey_vc_tonicon_icon_type_element($args){
    $args = array(
        shiftkey_vc_icon_set( array(), 'tonicons', 'icon_tonicons', 'ti-Line-Key-2', 'icon_type')
    );
    return $args;
}

add_filter('perch_modules/vc_icon_type_element', 'shiftkey_vc_flaticon_icon_type_element');
function shiftkey_vc_flaticon_icon_type_element($args){
    $args = array(
        shiftkey_vc_icon_set( array(), 'flaticon', 'icon_flaticon', 'flaticon-237-padlock', 'icon_type')
    );
    return $args;
}

add_filter('perch_modules/buttons/common_class', 'shiftkey_buttons_common_class');
function shiftkey_buttons_common_class($args){
    $args = array('btn');
    return $args;
}

add_filter('perch_modules/button/default_icon', 'shiftkey_button_default_icon');
function shiftkey_button_default_icon(){
	return '';
}

add_filter('perch_modules/content_list/list_type/value', 'shiftkey_content_list_type_options');
function shiftkey_content_list_type_options($args){
    $args = array(
        'Box list 1' => 'box-list m-top-15',
        'Box list 2' => 'cbox',
        'Content list' => 'content-list',
    );
    return $args;
}

add_filter('perch_modules/content_list/list_type/std', 'shiftkey_content_list_type_std');
function shiftkey_content_list_type_std(){
    return 'box-list m-top-15';
}
add_filter('perch_modules/simple_content_list_type', 'shiftkey_simple_content_list_type');
function shiftkey_simple_content_list_type($list_type){
    if( $list_type == 'box-list m-top-15' ){
        $list_type = 'box-lists';
    }

    return $list_type;
}

add_filter('perch_modules/content_list_class', 'shiftkey_content_list_class');
function shiftkey_content_list_class(){
    return 'content-list';
}

add_filter('perch_modules/simple_content_list/output', 'shiftkey_simple_content_list_type_output', 10, 2);
function shiftkey_simple_content_list_type_output($value, $atts){
    switch ($atts['list_type']) {
        case 'box-list m-top-15':
            $value = '<div class="box-list m-top-15">                         
                <div class="box-list-icon"><i class="fas fa-genderless"></i></div>
                <p>'.$value.'</p>                            
            </div>';
            break;
        case 'cbox':
            $value = '<div class="cbox-1">
                        <i class="fas fa-circle"></i>
                        <div class="cbox-1-txt grey-color"><p>'.$value.'</p></div>
                    </div>';
            break; 
        default:
           $value = '<p class="p-md">'.$value.'</p>';
            break;
    }
   
    return $value;
}

add_filter('perch_modules/buttons/buttons_desc', 'shiftkey_buttons_desc_output', 10, 2);
function shiftkey_buttons_desc_output($value, $atts){
    extract($atts);
    $value = ($buttons_desc != '')? '<p class="os-version">'.esc_attr($buttons_desc).'</p>' : '';
    return $value;
}

add_filter('perch_modules/content_list/output', 'shiftkey_content_list_type_output', 10, 2);
function shiftkey_content_list_type_output($value, $atts){
    $list_type = $icon_fontawesome = $icon_size = $icon_type = $li_spacing_left = $icon_html = '';        
    extract($atts); 

    if('option-list theme-list mt-30' == $list_type){
        $icon_fontawesome = ( $icon_fontawesome != '' )? $icon_fontawesome : 'fas fa-check';
        $value = '<div class="cbox-4"><span class="white-color"><i class="'.esc_attr($icon_fontawesome).'"></i></span><div class="cbox-4-txt '.esc_attr($li_spacing_left).'">'.$value.'</div></div>';
    }

    if('content-list' == $list_type){
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
        $value = $icon_html.'<div class="'.esc_attr($li_spacing_left).'">'.$value.'</div>';
    } 

    if('description-list' == $list_type){
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
        $value = $icon_html.'<div class="'.esc_attr($li_spacing_left).'">'.$value.'<div>';
    } 


    return $value;
}



add_filter( 'perch_modules/admin_logo', 'shiftkey_login_logo' );
function shiftkey_login_logo( $css ) {
    $logo = ( function_exists( 'shiftkey_get_option' ) ) ? shiftkey_get_option( 'admin_logo', SHIFTKEY_URI . '/images/logo.png' ) : SHIFTKEY_URI . '/images/logo.png';
    $logo = is_array($logo)? $logo['url'] : $logo;
    $css = '<style>body #login h1 a {
            background-image: url(' . esc_url( $logo ) . ');
            background-position: bottom center;  
            background-size: 100% auto;
            width: 150px; 
        }</style>';

    return $css;
}

add_filter('bcn_display_attributes', 'shiftkey_bcn_display_attributes_filter', 10, 3);
function shiftkey_bcn_display_attributes_filter($attribs, $types, $id){
    $extra_attribs = array('class' => array('breadcrumb-item'));
    //For the current item we need to add a little more info
    if(is_array($types) && in_array('current-item', $types))
    {
        $extra_attribs['class'][] = 'active';
        $extra_attribs['aria-current'] = array('page');
    }
    $atribs_array = array();
    preg_match_all('/([a-zA-Z]+)=["\']([a-zA-Z0-9\-\_ ]*)["\']/i', $attribs, $matches);
    if(isset($matches[1]))
    {
        foreach ($matches[1] as $key => $tag)
        {
            if(isset($matches[2][$key]))
            {
                $atribs_array[$tag] = explode(' ', $matches[2][$key]);
            }
        }
    }
    $merged_attribs = array_merge_recursive($atribs_array , $extra_attribs);
    $output = '';
    foreach($merged_attribs as $tag => $vals)
    {
        $output .= sprintf(' %1$s="%2$s"', $tag, implode(' ', $vals));
    }
    return $output;
}

add_filter( 'perch_modules/modal_popup_html', 'shiftkey_modal_popup_html', 10, 2 );
function shiftkey_modal_popup_html( $output, $atts ){
    $output = $enable_modal_popup = $modal_popup_title_color = $modal_popup_icon_color = '';
        extract($atts);

        if( $enable_modal_popup == 'yes' ){ 
            $classes = array('modal-video', shiftkey_margin_top_class_prefix().'10', $modal_popup_title_color);
            $classes = PerchVcMap::periodic_getCSSAnimation($classes, 'hero-content', $atts);

            $wrapper_attributes = array();
            $wrapper_attributes[] = (!empty($classes))? ' class="'.implode(' ', $classes).'"' : ''; 
            $wrapper_attributes = PerchVcMap::periodic_wrapperAttributes($wrapper_attributes, 'hero-content', $atts );
                    
            
            $atts['css_animation'] = '';
            $output = '<div '. implode( ' ', $wrapper_attributes ).'>
                <a class="video-popup2" href="'.esc_url($modal_popup_url).'">
                     <i class="fas fa-play-circle '.$modal_popup_icon_color.'"></i> 
                    '.perch_modules_parse_text($modal_popup_title ).'  
                </a>       
            </div>'; 
            
        } 
        return $output;
}

add_filter('perch_modules/vc_admin_view', 'shiftkey_vc_admin_view' );
function shiftkey_vc_admin_view($output){    
    $output = shiftkey_get_option('vc_admin_view', 'full');
    return $output;
}