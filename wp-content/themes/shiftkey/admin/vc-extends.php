<?php
include SHIFTKEY_DIR . '/admin/vc-icons.php';

if ( function_exists( 'vc_set_as_theme' ) ):
    vc_set_as_theme( $disable_updater = false );
endif;
$dir = get_template_directory() . '/vc_templates';
vc_set_shortcodes_templates_dir( $dir );

function shiftkey_vc_column_class_options(){
    $array = array(
        '' => 'None',
        'content-boxes' => 'Content box',
        'content-txt' => 'Content Text', 
        'hero-txt' => 'Hero Text',
        'box-rounded' => 'Rounded border',
        'hero-img' => 'Hero image',
        'content-img' => 'Content image',
        'features-img' => 'Features image',
        'download-img' => 'Download image',
    );
    $new_arr = array();
    foreach ($array as $key => $value) {
        $new_arr["{$value}"] = $key;
    }
    return $new_arr;
}

add_filter( 'vc_font_container_output_data', 'shiftkey_vc_font_container_output_data', 10, 4 );
function shiftkey_vc_font_container_output_data( $data, $fields, $values, $settings ){
   $r = json_encode($values);
    $data['color'] = '
        <div class="vc_row-fluid vc_column vc_col-sm-4">
            <div class="wpb_element_label">' . __( 'Text color', 'shiftkey' ) . '</div>
            <div class="vc_font_container_form_field-color-container">
                <select class="vc_font_container_form_field-color-input">';
        $colors = shiftkey_vc_color_options(false);
        foreach ( $colors as $color ) {
            $data['color'] .= '<option value="' . $color . '" class="' . $color . '" ' . ( isset($values['color']) && ($values['color'] == $color) ? 'selected' : '' ) . '>' . $color . '</option>';
        }
        $data['color'] .= '
                </select>
            </div>';
        if ( isset( $fields['color_description'] ) && strlen( $fields['color_description'] ) > 0 ) {
            $data['color'] .= '
            <span class="vc_description clear">' . $fields['color_description'] . '</span>
            ';
        }

    $data['color'] .= '</div>';

    

    return $data;            
}


function shiftkey_vc_hero_options(){
    $array = array('Layout 1', 'Layout 2', 'Layout 3', 'Layout 4', 'Layout 5',
        'Layout 6', 'Layout 7', 'Layout 8', 'Layout 9', 'Layout 10',
        'Layout 11', 'Layout 12');
    $new_arr = array();
    foreach ($array as $key => $value) {
        $new_arr["{$value}"] = ($key+1);
    }
    return $new_arr;
}

function shiftkey_vc_global_color_options(){
    $arr = array();

    $colors = shiftkey_default_color_classes();
    foreach ($colors as $key => $value) {
        $color_name = $value['label'];
        $color_class = $key;
        $arr[$color_name] = $color_class;
    }

    return $arr;
}

function shiftkey_vc_style_options($name = "Style", $total = 3){

    $new_arr = array();
    $new_arr["None"] = '';
    for ($i = 1; $i <= $total; $i++) { 
        $new_arr["{$name} {$i}"] = $i;
    }
    return $new_arr;
}

function shiftkey_vc_section_type_params( $param_name = '', $optiontype = "", $total = 3, $dep="" ){
    if( $param_name == '' ) return false;
    if( $optiontype == '' ) return false;

    return array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Choose', 'shiftkey' ).' '.esc_attr($optiontype),
            'param_name' => $param_name,
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_style_options($optiontype, $total),
            'description' => esc_attr__( 'You need to add also hero element in this section, then it worked perfectly. Hero style select mean changes the default background, font size, spacing etc. of this section.', 'shiftkey' ),
            'std'  => '',
            'edit_field_class' => 'vc_col-sm-4',
            'description' => '',
            'dependency' => array(
                'element' => 'section_type',
                'value' => $dep 
            ) 
        );
}

function shiftkey_vc_row_type_params( $param_name = '', $optiontype = "", $total = 3, $dep="" ){
    if( $param_name == '' ) return false;
    if( $optiontype == '' ) return false;

    return array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Choose', 'shiftkey' ).' '.esc_attr($optiontype),
            'param_name' => $param_name,
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_style_options($optiontype, $total),
            'description' => esc_attr__( 'You need to add also hero element in this section, then it worked perfectly. Hero style select mean changes the default background, font size, spacing etc. of this section.', 'shiftkey' ),
            'std'  => '',
            'description' => '',
            'dependency' => array(
                'element' => 'row_type',
                'value' => $dep 
            ) 
        );
}

function shiftkey_vc_column_type_params( $param_name = '', $optiontype = "", $total = 3, $dep="" ){
    if( $param_name == '' ) return false;
    if( $optiontype == '' ) return false;

    return array(
             'type' => 'dropdown',
            'heading' => esc_attr__( 'Choose', 'shiftkey' ).' '.esc_attr($optiontype),
            'param_name' => $param_name,
            'group' => 'Shiftkey Settings',
            'value' => shiftkey_vc_style_options($optiontype, $total),
            'description' => esc_attr__( 'You need to add also hero element in this section, then it worked perfectly. Hero style select mean changes the default background, font size, spacing etc. of this section.', 'shiftkey' ),
            'std'  => '',
            'description' => '',
            'dependency' => array(
                'element' => 'column_inner_style',
                'value' => $dep 
            ) 
        );
}



if ( !function_exists( 'shiftkey_get_posts_dropdown' ) ):
    function shiftkey_get_posts_dropdown( $args = array( ) ) {
        global $wpdb, $post;
        $dropdown  = array( );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $dropdown[ get_the_ID() ] = get_the_title();
            } //$the_query->have_posts()
        } //$the_query->have_posts()
        wp_reset_postdata();
        return $dropdown;
    }
endif;
if ( !function_exists( 'shiftkey_get_terms' ) ):
    function shiftkey_get_terms( $tax = 'category', $key = 'id' ) {
        $terms = array( );
        if ( !taxonomy_exists( $tax ) )
            return false;
        if ( $key === 'id' )
            foreach ( (array) get_terms( $tax, array(
                 'hide_empty' => false 
            ) ) as $term )
                $terms[ $term->term_id ] = $term->name;
        elseif ( $key === 'slug' )
            foreach ( (array) get_terms( $tax, array(
                 'hide_empty' => false 
            ) ) as $term )
                $terms[ $term->slug ] = $term->name;
        return $terms;
    }
endif;

function shiftkey_vc_icontype_dropdown( $name = 'icon_type', $value = array( 'flaticon' => 'flaticon', 'Linecons' => 'linecons', 'Entypo' => 'entypo', 'Typicons' => 'typicons', 'Openiconic' => 'openiconic', 'Fontawesome' => 'fontawesome' ) ) {
    return array(
         'type' => 'dropdown',
        'heading' => esc_attr__( 'Icon type', 'shiftkey' ),
        'param_name' => $name,
        'description' => '',
        'value' => $value 
    );
}

function shiftkey_vc_icon_set( $arr, $type, $name = 'icon_fontawesome', $value = '', $dependency = '' ) {
    $arr = array(
         'type' => 'iconpicker',
        'heading' => esc_attr__( 'Icon', 'shiftkey' ),
        'param_name' => $name,
        'value' => $value,
        'settings' => array(
             'emptyIcon' => true,
            'type' => $type,
            'iconsPerPage' => 4000 
        ),
        'description' => esc_attr__( 'Select icon from library.', 'shiftkey' ) ,
    );
    if ( $dependency != '' ) {
        $arr[ 'dependency' ][ 'element' ] = $dependency;
        $arr[ 'dependency' ][ 'value' ]   = $type;
    } //$dependency != ''
    return $arr;
}

add_filter( 'perch_modules/vc_icon_set', 'shiftkey_vc_icon_set', 10, 5);

function shiftkey_vc_animation_duration( $label = false, $default = 300 ){
    return array(
                 'type' => 'textfield',
                'value' => intval($default),
                'heading' => esc_attr__( 'Animation delay', 'shiftkey' ) ,
                'param_name' => 'animation_delay',
                'admin_label' => $label,
                'description' => esc_attr__( 'Number only', 'shiftkey' ),                
                'group' => esc_attr__( 'Animation', 'shiftkey' ),   
                'dependency' => array(
                    'element' => 'css_animation',
                    'value_not_equal_to' => 'none'
                )             
            );
}

function shiftkey_vc_animation_type($std = ''){
    $output = vc_map_add_css_animation();
    $output['group'] = esc_attr__( 'Animation', 'shiftkey' );

    if( $std != '' ) $output['std'] = esc_attr($std);

    return $output;
}

add_filter( 'shiftkey_vc_element_params', 'shiftkey_vc_element_params_callback' );
function shiftkey_vc_element_params_callback($args){
    $args['params'][] = shiftkey_vc_animation_type();
    $args['params'][] = shiftkey_vc_animation_duration();
    return $args;
}

function shiftkey_animation_attr($css_animation, $animation_delay = 100){
    $output = '';
    if($css_animation == '') return $output;  
    $output .= ' data-wow-delay="'.intval($animation_delay).'ms"';

    return $output;
}

function shiftkey_vc_spacing_options($type='padding', $pos = 'bottom'){
    $total = apply_filters('shiftkey_vc_spacing_total', 120);
    $arr = array();
    $prefix = ($type == 'padding')? 'p' : 'm';
    $_pos = ($pos == 'bottom')? 'b' : 't';
    $prefix = $prefix.$_pos.'-';
    $arr = array(
        __('Inherit', 'shiftkey') => '',     
    );
    for ($i=0; $i <= $total; $i+=5) { 
        $name = ucfirst($type).' '.$pos. ' '.$i.'px';
       $arr[$name] = $prefix.$i; 
    }
    return $arr;
}


function shiftkey_vc_spacing_options_param($type = 'padding', $pos = 'bottom', $name = ''){
    $prefix = ($type == 'padding')? 'p' : 'm';
    $param_name = $prefix.$pos;
    $param_name = ( $name != '' )? $name : $param_name;
    $heading = ucfirst($type).' '.$pos;
    return array(
                'type' => 'dropdown',
                'heading' => $heading,
                'param_name' => $param_name,
                'value' => shiftkey_vc_spacing_options($type, $pos),
                'group' => esc_attr__( 'Spacing option', 'shiftkey' ),
                'edit_field_class' => 'vc_col-sm-6', 
            );
}

function shiftkey_vc_content_list_group(){
    return array(
            'type' => 'param_group',
            'save_always' => true,
            'heading' => esc_attr__( 'Content list', 'shiftkey' ),
            'param_name' => 'content_list',
            'value' => urlencode( json_encode( array(
                array( 'title' => 'Fully Responsive Design' ),
                array( 'title' => 'Bootstrap 4.0 Based' ),
                array( 'title' => 'Google Analytics Ready' ),
                array( 'title' => 'Cross Browser Compatability' ),
                array( 'title' => 'Developer Friendly Commented Code' ),
                array( 'title' => 'and much more...' ),
            ) ) ),
            'params' => array(
                 array(
                    'type' => 'textarea',
                    'heading' => esc_attr__( 'Title', 'shiftkey' ),
                    'param_name' => 'title',
                    'description' => '',
                    'value' => '',
                    'admin_label' => true 
                ),
            ),            
            'dependency' => array(
                'element' => 'enable_list',
                'value' => 'yes'
            )  
        );
}

if( !function_exists('shiftkey_target_param_list') ):
function shiftkey_target_param_list() {
    return array(
        __( 'Same window', 'shiftkey' ) => '_self',
        __( 'New window', 'shiftkey' ) => '_blank',
    );
}
endif;


function shiftkey_vc_counter_group(){
    return array(
        'type' => 'param_group',
        'save_always' => true,
        'heading' => esc_attr__( 'Counter up', 'shiftkey' ),
        'param_name' => 'counter_group',
        'value' => urlencode( json_encode( array(
            array(
                 'title' => 'Happy Clients',
                'count' => '438',
                'prefix' => '3,',
            ),
            array(
                 'title' => 'Tickets Closed',
                'count' => '263',
                'prefix' => '1,',
            ),
        ) ) ),
        'params' => array(
            array(
                 'type' => 'textfield',
                'heading' => esc_attr__( 'Counter Prefix', 'shiftkey' ),
                'param_name' => 'prefix',
                'description' => '',
                'value' => '3,',
                'admin_label' => true 
            ),
            array(
                 'type' => 'textfield',
                'heading' => esc_attr__( 'Count', 'shiftkey' ),
                'param_name' => 'count',
                'description' => 'Number only',
                'value' => '' ,
                'admin_label' => true 
            ),
             array(
                 'type' => 'textfield',
                'heading' => esc_attr__( 'Title', 'shiftkey' ),
                'param_name' => 'title',
                'description' => '',
                'value' => '',
                'admin_label' => true 
            ),
            
        ),
        'dependency' => array(
            'element' => 'display',
            'value' => 'counter'
        ),
        'group' => esc_attr__( 'Content bottom', 'shiftkey' ),  
    );
}

function shiftkey_vc_techs_group(){
    return array(
        'type' => 'param_group',
        'save_always' => true,
        'heading' => esc_attr__( 'Techs', 'shiftkey' ),
        'param_name' => 'techs_group',
        'value' => urlencode( json_encode( array(
            array(
                 'title' => 'HTML5',
                'icon' => 'fa fa-html5',
                'image' => ''
            ),
            array(
                 'title' => 'CSS3',
                'icon' => 'fa fa-css3',
                'image' => ''
            ),
            array(
                 'title' => 'jsfiddle',
                'icon' => 'fa fa-jsfiddle',
                'image' => ''
            ),
            array(
                 'title' => 'git',
                'icon' => 'fa fa-git',
                'image' => ''
            ),
            array(
                 'title' => 'WordPress',
                'icon' => 'fa fa-wordpress',
                'image' => ''
            ),
            array(
                 'title' => 'mixcloud',
                'icon' => 'fa fa-mixcloud',
                'image' => ''
            ),
        ) ) ),
        'params' => array(
             array(
                'type' => 'textfield',
                'heading' => esc_attr__( 'Title', 'shiftkey' ),
                'param_name' => 'title',
                'description' => '',
                'value' => '',
                'admin_label' => true 
            ),
             shiftkey_vc_icon_set( 'fontawesome', 'icon' ),
             array(
                'type' => 'image_upload',
                'heading' => esc_attr__( 'Icon Image', 'shiftkey' ),
                'param_name' => 'image',
                'description' => 'You can use image instead of Icon',
                'value' => '' 
            ),
        ),
        'dependency' => array(
            'element' => 'display',
            'value' => 'techs'
        ),
        'group' => esc_attr__( 'Content bottom', 'shiftkey' ),  
    );
}

function shiftkey_vc_strategy_list_group($group = true){
    $output = array(
            'type' => 'param_group',
            'save_always' => true,
            'heading' => esc_attr__( 'Content group', 'shiftkey' ),
            'param_name' => 'strategy_list',
            'value' => urlencode( json_encode( array(
                array(
                     'title' => 'Vitae auctor integer congue magna at pretium purus pretium ligula rutrum luctus risus velna auctor congue tempus undo magna ',
                ),
                array(
                     'title' => 'An enim nullam tempor sapien gravida donec enim ipsum porta blandit justo integer odio velna vitae auctor integer luctus',
                ),
            ) ) ),
            'params' => array(
                 array(
                    'type' => 'textarea',
                    'heading' => esc_attr__( 'Description', 'shiftkey' ),
                    'param_name' => 'title',
                    'description' => '',
                    'value' => '',
                    'admin_label' => true 
                ),
            ),
        );

    if($group) $output['group'] = __( 'Content', 'shiftkey' );

    return $output;
}

function shiftkey_vc_get_strategy_list( $type = 'list', $paramsArr = array() , $duration = 400  ){
    if( empty($paramsArr) ) return false;
   
    if( $type == 'list' ){
        echo '<ul class="content-list">';
            foreach ($paramsArr as $key => $value): 
                extract($value);                                    
                echo '<li class="wow fadeInUp" data-wow-delay="'.intval($duration).'ms">';
                    echo wpautop($title);                 
                echo '</li>';
                $duration = $duration + 100;
            endforeach;
        echo '</ul>';
    }else{
        foreach ($paramsArr as $key => $value): 
            extract($value);                                    
            echo '<div class="wow fadeInUp" data-wow-delay="'.intval($duration).'ms">';
                echo wpautop($title);                 
            echo '</div>';
            $duration = $duration + 100;
        endforeach;
    }
}

function shiftkey_vc_element_display_option(){
    return array(
                    'None' => 'none',
                    'Video link' => 'video',                    
                    'Counter' => 'counter',
                    'Techs' => 'techs',
                );
}

function shiftkey_vc_element_icon_size(){
    return array(
                    'Default' => 'icon',
                    'Extra small' => 'xs',                    
                    'Small' => 'sm',
                    'Medium' => 'md',
                    'Large' => 'lg',
                    '2X size' => '2x',
                    '3X size' => '3x',
                    '5X size' => '5x',
                    '7X size' => '7x',
                    '10X size' => '10x',
                );
}

function shiftkey_vc_get_content_list_group( $paramsArr = array(), $animation = '', $delay = '100'){
    if(empty($paramsArr)) return false;
    echo '<ul class="content-list">';
    foreach ($paramsArr as $key => $value):                     
        echo '<li class="wow '.esc_attr($animation).'" data-wow-delay="'. intval($delay).'ms">'.esc_attr($value['title']).'</li>';
        $delay = $delay + 100; 
    endforeach; 
    echo '</ul>';
}

add_filter( 'perch_modules/service_style', 'shiftkey_service_style' );
function shiftkey_service_style( $args ){
    $total = 7;
    $prefix = 'sbox-';
    $label_prefix = __( 'Service box', 'shiftkey' );
    $args = array('Default' => '');
    for ($i=1; $i <= $total; $i++) { 
        $args[$label_prefix.' '.$i] = $prefix.$i;
    }
    
    return $args;
}

add_filter( 'shiftkey_vc_templates_param_group', 'shiftkey_vc_templates_param_group' );
function shiftkey_vc_templates_param_group($output){
    $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($output) : array();
    $new_array = array();
    if( !empty($paramsArr) ){
        foreach ($paramsArr as $key => $value) {
            $new_array[] = shiftkey_vc_image_url_filter($value);
        }
    }
    return urlencode(json_encode($new_array));
}

function shiftkey_vc_image_url_filter($arr){
    if( !empty($arr) ){

    }
    return $arr;
}


/**
* vc_map default values
* @param array
* @return array
*/
function shiftkey_vc_get_params_value($args = array(), $_content = NULL){
    $array = array();
    if( !isset($args['base']) || !isset($args['params']) ){
        return $array;
    }

    $newarray = array();
    $map_arr = $args['params'];
    foreach ( $map_arr as $key => $value) {
        $param_name = isset($value['param_name'])? $value['param_name'] : '';
            $std = '';
            if(isset($value['value']) ){
                if( is_array($value['value']) ) {                    
                    if(!isset($value['std'])){
                        $array = $value['value']; reset($array); $std = key($array);
                    }else{
                        $std = $value['std'];
                    }
                }else {
                    $std = $value['value'];
                }
            }
            $std = isset($value['std'])? $value['std'] : $std;

            if( $param_name != '' ){
                $newarray[$param_name] = $std;
            }
    }
    $newarray['content'] = $_content;


    if( !empty($newarray) ) $array = $newarray;

    return $array;
}


if ( function_exists( 'vc_set_as_theme' ) ):
    vc_set_as_theme( $disable_updater = false );
    $list = array(
         'page',
        'post',
        'team',
        'portfolio',
        'service',
        'job' 
    );
    vc_set_default_editor_post_types( $list );
endif;

add_action( 'vc_after_init_base', 'add_more_custom_layouts' );
function add_more_custom_layouts() {
  global $vc_row_layouts;
  $new_layouts = array(
      'cells' => '512_112_12',
      'mask' => '424',
      'title' => 'Custom 5/12 + 1/12 + 6/12',
      'icon_class' => '512_112_12' 
    );
  array_push( $vc_row_layouts,  $new_layouts );

  $new_layouts = array(
      'cells' => '12_112_512',
      'mask' => '424',
      'title' => 'Custom 6/12 + 1/12 + 5/12',
      'icon_class' => '12_112_512' 
    );
  array_push( $vc_row_layouts,  $new_layouts );

  $new_layouts = array(
      'cells' => '112_56_112',
      'mask' => '424',
      'title' => 'Custom 1/12 + 10/12 + 1/12',
      'icon_class' => '112_56_112' 
    );
  array_push( $vc_row_layouts,  $new_layouts );

  
}

function shiftkey_vc_template_image_button_group( $type = 'dark' ){
    $array = array();
    $array['dark'] =  urlencode( json_encode( array(
                      array(
                          'button_type' => 'img_btn', 
                          'img_btn' => get_template_directory_uri(). '/images/appstore.png',
                          'img_btn_size' => '160',                          
                          'button_text' => 'Appstore',
                          'button_url' => '#',
                          'button_target' => '_blank',
                      ),
                      array(
                        'button_type' => 'img_btn', 
                        'img_btn' => get_template_directory_uri(). '/images/googleplay.png',
                        'img_btn_size' => '160',                          
                        'button_text' => 'Googleplay',
                        'button_url' => '#',
                        'button_target' => '_blank',
                      ),
                  ) ) );

    $array['white'] =  urlencode( json_encode( array(
                      array(
                          'button_type' => 'img_btn', 
                          'img_btn' => get_template_directory_uri(). '/images/appstore-tra-white.png',
                          'img_btn_size' => '160',                          
                          'button_text' => 'Appstore',
                          'button_url' => '#',
                          'button_target' => '_blank',
                      ),
                      array(
                        'button_type' => 'img_btn', 
                        'img_btn' => get_template_directory_uri(). '/images/googleplay-tra-white.png',
                        'img_btn_size' => '160',                          
                        'button_text' => 'Googleplay',
                        'button_url' => '#',
                        'button_target' => '_blank',
                      ),
                  ) ) );

    $output = isset($array[$type])? $array[$type] : $array['dark'];
    return $output;
}

add_filter( 'vc_render_template_preview_include_template', 'shiftkey_vc_render_template_preview_include_template', 10 );
function shiftkey_vc_render_template_preview_include_template($template){
    $_template = 'editors/shiftkey-template-preview.tpl.php';
    $template = $_template;
    return $template;
}

add_filter( 'vc_get_all_templates', 'shiftkey_vc_get_all_templates' );
function shiftkey_vc_get_all_templates($data){
    if( $data[1]['category'] == 'default_templates' ) {     
        $data[1]['category_name'] = 'Shiftkey templates';
        $data[1]['category_description'] = 'Saved templates of Shiftkey theme.';     
    }
    
    return $data;
}
/* global vc include files */
foreach ( glob( SHIFTKEY_DIR . "/vc-extends/*.php" ) as $filename ) {
    include $filename;
} 