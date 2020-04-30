<?php
function perch_default_color_classes(){
  $array = array(
    'tra' => array('label' => 'Transparent color', 'value' => 'transparent' ),
    'light' => array('label' => 'Light color', 'value' => '#fff' ),
    'white' => array('label' => 'White color', 'value' => '#fff' ),   
    'black' => array('label' => 'Black color', 'value' => '#333' ),   
    'preset' => array('label' => 'Preset color', 'value' => '#389bf2'), 
    'preset2' => array('label' => 'Preset color 2', 'value' => '#389bf2'), 
    'dark' => array('label' => 'Dark color', 'value' => '#222', 'color' => '#000' ),
    'lightdark' => array('label' => 'Dark color - Light', 'value' => '#252d35'),
    'deepdark' => array('label' => 'Dark color - Deep', 'value' => '#1a1a1a'),
    'lightgrey' => array('label' => 'Grey color - Light', 'value' => '#f2f2f2', 'color' => '#ccc'),
    'grey' => array('label' => 'Grey color', 'value' => '#ede9e6', 'color' => '#666'),
    'deepgrey' => array('label' => 'Grey color - Deep', 'value' => '#ddd'),
    'rose' => array('label' => 'Rose color', 'value' => '#ff3366'),    
    'red' => array('label' => 'Red color', 'value' => '#e35029'),
    'tomato' => array('label' => 'Tomato color', 'value' => '#eb2f5b'),
    'coral' => array('label' => 'Red color - Coral', 'value' => '#ea5c5a'),
    'yellow' => array('label' => 'Yellow color', 'value' => '#fed841', 'color' => '#fcb80b'),    
    'green' => array('label' => 'Green color', 'value' => '#42a045', 'color' => '#56a959'),
    'lightgreen' => array('label' => 'Green color - Light', 'value' => '#59BD56', 'color' => '#22bc3f'),
    'deepgreen' => array('label' => 'Green color - Deep', 'value' => '#009587'),
    'blue' => array('label' => 'Blue color', 'value' => '#2154cf', 'color' => '#3176ed'),
    'lightblue' => array('label' => 'Blue color - Light', 'value' => '#1e88e5'),
    'skyblue' => array('label' => 'Blue color - Skyblue', 'value' => '#01b7de'),
    'deepblue' => array('label' => 'Blue color - Deep', 'value' => '#004861'),
    'tinyblue' => array('label' => 'Blue color - Tiny', 'value' => '#e6f9fa'),
    'purple' => array('label' => 'Purple color', 'value' => '#6e45e2'),
    'deeppurple' => array('label' => 'Purple color - Deep', 'value' => '#510fa7', 'color' => '#004861'),
    'lightpurple' => array('label' => 'Purple color - Light', 'value' => '#715fef'),
  );
  return apply_filters( 'perch_default_color_classes', $array);
}

function perch_get_dark_color_classes(){
    $array = array( 'preset', 'preset2', 'black', 'deepdark', 'dark', 'lightdark', 'rose', 'red', 'green', 'lightgreen', 'deepgreen', 'blue','skyblue', 'deepblue', 'lightblue', 'purple','deeppurple', 'lightpurple', 'tomato', 'coral', 'tra-dark' );
    return $array;
}

function perch_default_dark_color_classes( $args = array() ){

    if ( is_array( $args ) ) {
        extract( shortcode_atts( array(
             'prefix' => 'bg-',
            'postfix' => '',
        ), $args ) );
    }

    $array = array( 'preset', 'preset2', 'black', 'deepdark', 'dark', 'lightdark', 'rose', 'red', 'green', 'lightgreen', 'deepgreen', 'blue','skyblue', 'deepblue', 'lightblue', 'purple','deeppurple', 'lightpurple', 'tomato', 'coral', 'tra-dark' );

    if($prefix != ''){
        $oldarr = $array;
        $array = array();
        foreach ($oldarr as $key => $value) {
            $array[] = $prefix.$value;
        }
    }

    if($postfix != ''){
        $oldarr = $array;
        $array = array();
        foreach ($oldarr as $key => $value) {
            $array[] = $value.$postfix;
        }
    }


    return apply_filters( 'perch_default_dark_color_classes', $array);
}

if ( !function_exists( 'perch_parse_text' ) ):
    function perch_parse_text( $text, $args = array( ) ) {
        if ( is_array( $args ) ) {
            extract( shortcode_atts( array(
                 'tag' => 'span',
                'tagclass' => '',
                'before' => '',
                'after' => '' 
            ), $args ) );
        } //is_array( $args )
        else {
            extract( shortcode_atts( array(
                'tag' => $args,
                'tagclass' => 'preset-color',
                'before' => '',
                'after' => '' 
            ), $args ) );
        }

        $text = esc_attr($text);
        
        preg_match_all( "/\{([^\}]*)\}/", $text, $matches );
        $tagclass = trim($tagclass);
        
        if ( !empty( $matches ) ) {
            foreach ( $matches[ 1 ] as $value ) {
                $find    = "{{$value}}";
                $replace = "{$before}<{$tag} class='{$tagclass}'>{$value}</{$tag}>{$after}";
                $text    = str_replace( $find, $replace, $text );
            } //$matches[1] as $value
        } //!empty( $matches )
        return  $text;
    }
endif;

function perch_get_parse_text($text = '', $args = array()){

    if( $text == '' ) return false;

    extract( shortcode_atts( array(
            'highlight_text' => '',
            'highlight_text_underline' => 'underline-yellow',
            'highlight_text_color' => '',
            'highlight_text_bg' => '', 
            'highlight_text_weight' => '', 
            'highlight_text_tag' => 'span', 
        ), $args ));

    if( $highlight_text == '' ) return $text;

    $classes = array();
    $classes[] = $highlight_text_underline;
    $classes[] = ($highlight_text_color != '')? $highlight_text_color.'-color' : '';
    $classes[] = ($highlight_text_bg != '')? $highlight_text_bg : '';
    $classes[] = ($highlight_text_weight != '')? $highlight_text_weight : '';
    $classes = array_filter($classes);

    $parse_args = array(
        'tag' => $highlight_text_tag,
        'tagclass' => implode(' ', $classes),
        'before' => '',
        'after' => ''
    );

    $parse_args = ( $highlight_text != '' )? $parse_args : array();

    return perch_parse_text($text, $parse_args);
}

function perch_get_parse_text_html($text = '', $args = array(), $type = 'title'){
    if( $text == '' ) return false;
    if( $type == '' ) return false;

    $text = esc_attr($text);

    shortcode_atts( array(
            $type.'_tag' => '',
            $type.'_size' => '',
            $type.'_weight' => '', 
            $type.'_color' => '', 
             $type.'_style' => '',
             $type.'_class' => '',
        ), $args );

    $echo = (isset( $args['echo'] ) && $args['echo'])? $args['echo'] : true;

    //print_r($args);

    $tag = isset($args[ $type.'_tag' ])? $args[ $type.'_tag' ] : 'div';
    $size = isset($args[ $type.'_size' ])? $args[ $type.'_size' ] : '';
    $color = isset($args[ $type.'_color' ])? $args[ $type.'_color' ] : '';
    $weight = isset($args[ $type.'_weight' ])? $args[ $type.'_weight' ] : '';
    $class = isset($args[ $type.'_class' ])? $args[ $type.'_class' ] : '';
    $style = isset($args[ $type.'_style' ])? $args[ $type.'_style' ] : '';

    $style = ( $style != '' )? ' style="'.$style.'"' : '';


    $tagclassArr = array();
    $tagclassArr[] = ($size != '')? $tag. '-'.$size : '';
    $tagclassArr[] = $weight;         
    $tagclassArr[] = $class;         
    $tagclassArr[] = ($color != '')? $color : '';
    $tagclassArr = array_filter($tagclassArr);
    $tagclassclass = implode( ' ', $tagclassArr );

    

    if( $echo ){
        $text = perch_get_parse_text($text, $args);    
        return ($tag != '')?"<{$tag} class='{$tagclassclass}'{$style}>".$text."</{$tag}>" : $text;
    }else{
        return $args;
    }
    

}  

if ( !function_exists( 'perch_get_terms_choices' ) ):
    function perch_get_terms_choices( $tax = 'category', $key = 'slug' ) {
        $terms = array( );
        if ( !taxonomy_exists( $tax ) )
            return false;
        if ( $key === 'id' )
            foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term )
                $terms[] = array(
                    'label' => $term->name,
                    'value' => $term->term_id
                );
        elseif ( $key === 'slug' )
            foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term )
                $terms[] = array(
                    'label' => $term->name,
                    'value' => $term->slug
                );

        return $terms;
    }
endif;

/**
* Get size information for all currently-registered image sizes.
*
* @global $_wp_additional_image_sizes
* @uses   get_intermediate_image_sizes()
* @return array $sizes Data for all currently-registered image sizes.
*/
function perch_get_image_sizes( ) {
    global $_wp_additional_image_sizes;
    $sizes = array( );
    foreach ( get_intermediate_image_sizes() as $_size ) {
        if ( in_array( $_size, array(
             'thumbnail',
            'medium',
            'medium_large',
            'large',
            'full' 
        ) ) ) {
            $sizes[ $_size ][ 'width' ]  = get_option( "{$_size}_size_w" );
            $sizes[ $_size ][ 'height' ] = get_option( "{$_size}_size_h" );
            $sizes[ $_size ][ 'crop' ]   = (bool) get_option( "{$_size}_crop" );
        } //in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) )
        elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array(
                 'width' => $_wp_additional_image_sizes[ $_size ][ 'width' ],
                'height' => $_wp_additional_image_sizes[ $_size ][ 'height' ],
                'crop' => $_wp_additional_image_sizes[ $_size ][ 'crop' ] 
            );
        } //isset( $_wp_additional_image_sizes[ $_size ] )
    } //get_intermediate_image_sizes() as $_size
    return $sizes;
}
function perch_get_image_sizes_Arr( ) {
    $sizes = perch_get_image_sizes();
    $arr   = array( );
    foreach ( $sizes as $key => $value ) {
        $arr[ $key ] = $key . ' - ' . $value[ 'width' ] . 'X' . $value[ 'height' ] . ' - ' . ( ( $value[ 'crop' ] ) ? 'Cropped' : '' );
    } //$sizes as $key => $value
    return $arr;
}

function perch_vc_icon_class_options(){
    $arr = array(
        __('None', 'perch') => 'none',
        __('Rotation 90°', 'perch') => 'fa-rotate-90',
         __('Rotation 180°', 'perch') => 'fa-rotate-180',
         __('Rotation 270°', 'perch') => 'fa-rotate-270',
         __('Mirrors icon horizontally', 'perch') => 'fa-flip-horizontal',
         __('Mirrors icon vertically', 'perch') => 'fa-flip-vertical',
         __('Spinner', 'perch') => 'fa-spin',
    );
    return $arr;
}

function perch_vc_tag_options(){
    $arr = array(
        __('Default', 'perch') => '',
        __('H1', 'perch') => 'h1',
        __('H2', 'perch') => 'h2',
        __('H3', 'perch') => 'h3',
        __('H4', 'perch') => 'h4',
        __('H5', 'perch') => 'h5',
        __('H6', 'perch') => 'h6',
        __('P', 'perch') => 'p',                
        __('Span', 'perch') => 'span',       
        __('Small', 'perch') => 'small',       
        __('Strong', 'perch') => 'strong', 
        __('Div', 'perch') => 'div',
        __('Footer', 'perch') => 'footer', 
         __('Underline', 'perch') => 'u',      
        __('Blockquote', 'perch') => 'blockquote',               
        __('Address', 'perch') => 'address',       
        __('em', 'perch') => 'em',       
        __('Del', 'perch') => 'del',       
        __('Mark', 'perch') => 'mark',       
        __('S', 'perch') => 's',       
        __('Ins', 'perch') => 'ins',       
        __('Code', 'perch') => 'code',       
        __('Pre', 'perch') => 'pre',       
        __('Var', 'perch') => 'var',       
        __('kbd', 'perch') => 'kbd',       
        __('samp', 'perch') => 'samp',       
             
    );

    return $arr;
}

function perch_vc_icontype_dropdown( $name = 'icon_type', $value = array( 'flaticon' => 'flaticon', 'Linecons' => 'linecons', 'Entypo' => 'entypo', 'Typicons' => 'typicons', 'Openiconic' => 'openiconic', 'Fontawesome' => 'fontawesome' ) ) {
    return array(
         'type' => 'dropdown',
        'heading' => __( 'Icon type', 'perch' ),
        'param_name' => $name,
        'description' => '',
        'value' => $value 
    );
}
function perch_vc_icon_set( $type, $name = 'icon_fontawesome', $value = '', $dependency = '' ) {
    $arr = array(
         'type' => 'iconpicker',
        'heading' => __( 'Icon', 'perch' ),
        'param_name' => $name,
        'value' => $value,
        'settings' => array(
             'emptyIcon' => true,
            'type' => $type,
            'iconsPerPage' => 4000 
        ),
        'description' => __( 'Select icon from library.', 'perch' ) ,
    );
    if ( $dependency != '' ) {
        $arr[ 'dependency' ][ 'element' ] = $dependency;
        $arr[ 'dependency' ][ 'value' ]   = $type;
    } //$dependency != ''
    return $arr;
}

function perch_vc_animation_duration( $label = false, $default = 300 ){
    return array(
                 'type' => 'textfield',
                'value' => intval($default),
                'heading' => __( 'Animation delay', 'perch' ) ,
                'param_name' => 'animation_delay',
                'admin_label' => $label,
                'description' => __( 'Number only', 'perch' ),                
                'group' => __( 'Animation', 'perch' ),   
                'dependency' => array(
                    'element' => 'css_animation',
                    'value_not_equal_to' => 'none'
                )             
            );
}

function perch_vc_animation_type($std = ''){
    $output = vc_map_add_css_animation();
    $output['group'] = __( 'Animation', 'perch' );

    if( $std != '' ) $output['std'] = esc_attr($std);

    return $output;
}

function perch_vc_underline_color_options(){
    $arr = array(
        __('None', 'perch') => 'none',
        __('Image', 'perch') => 'underline-image',
         __('Font weight bold', 'perch') => 'font-weight-bold',
         __('Font weight thin', 'perch') => 'txt-300',
         __('Font weight thiner', 'perch') => 'txt-100',
         __('Italic text', 'perch') => 'font-italic',
         __('Indicates uppercased text', 'perch') => 'text-uppercase',
    );

    $colors = perch_default_color_classes();
    foreach ($colors as $key => $value) {
        $color_name = $value['label'];
        $color_class = 'underline-'.$key;
        $arr[$color_name] = $color_class;
    }

    return $arr;
}
function perch_vc_global_color_options(){
    $arr = array();

    $colors = perch_default_color_classes();
    foreach ($colors as $key => $value) {
        $color_name = $value['label'];
        $color_class = $key;
        $arr[$color_name] = $color_class;
    }

    return $arr;
}

function perch_vc_background_options(){
    $output = array();

    $arr = perch_bg_color_options();
   $output['Transparent'] = 'bg-tra';
    foreach ($arr as $value) {
        $key = $value['label'];
        $output[$key] =  $value['value'];
    }
    return $output;
}

function perch_bg_color_options(){
    $arr = array(
        array( 'label' => 'Transparent dark', 'value' =>  'bg-tra-dark' )
    );
    $colors = perch_default_color_classes();
    foreach ($colors as $key => $value) {
        $color_name = $value['label'];
        $color_class = 'bg-'.$key;
        $arr[] = array( 'label' => $color_name, 'value' =>  $color_class ); 
    }
    return $arr;
}

function perch_navscrool_bg_color_options(){
    $arr = array();
    $colors = perch_default_color_classes();
    foreach ($colors as $key => $value) {
        $color_name = $value['label'];
        $color_class = $key .'-scroll';
        $arr[] = array( 'label' => $color_name, 'value' =>  $color_class ); 
    }
    return $arr;
}

function perch_vc_color_options($coloronly = false, $prefix = '', $postfix = '' ){
    $arr = perch_bg_color_options();
    $colorArr = array('Default' => 'default');
    $newarr = array('Default' => 'default-color');
    foreach ($arr as $key => $value) {
        $newkey = $value['label'];        
        $newvalue = $value['value'];
        $newvalue = str_replace( 'bg-', '', $newvalue );
        $newvalue = trim($prefix.$newvalue.$postfix);
        $colorArr[$newkey] = $newvalue;
        $newvalue = $newvalue. '-color';
        $newvalue = trim($prefix.$newvalue.$postfix);
        $newarr[$newkey] = $newvalue;
    }
    if($coloronly){
        return $colorArr;
    }else{
        return $newarr;
    }    
}



function perch_btn_style_options($vcoptions = false){
    $arr = array(
            array( 'label' => 'Default',  'value' => 'btn-default' ),
            array( 'label' => 'Transparent white',  'value' => 'btn-tra-white tra-hover' ),
        );
    $vcArr = array(
        'Default' => 'btn-default',
        'Transparent white' => 'btn-tra-white tra-hover',
    );
     $colorarr = perch_default_color_classes();
     foreach ($colorarr as $key => $value) {
         $arr[] = array( 'label' => $value['label'],  'value' => 'btn-'.$key );
         $vcArr[$value['label']] = 'btn-'.$key;
         if( $key != 'tra' ){
            $arr[] = array( 'label' => $value['label'] . ' Transparent background',  'value' => 'btn-tra-'.$key );
            $vcArr[$value['label']. ' Transparent background'] = 'btn-tra-'.$key;
         }
         
     }
     if($vcoptions){
        return $vcArr;
     }else{
        return $arr;
     }
    
}

function perch_spacing_options(){
    $arr = array();

    for ($i=0; $i < 15 ; $i++) { 
      $value =  $i*10;
      $arr[] = array( 'label' => 'Wide '.intval($value),  'value' => 'wide-'.intval($value) );
    }
    return $arr;
}

function perch_vc_padding_options(){
    $output = array();
    $output['None'] = '';
    for ($i=0; $i <= 12 ; $i++) { 
        $output['Wide '. ($i * 10)] = 'wide-'. ($i * 10);
    }
    return $output;
}

function perch_vc_text_size_options(){
    return array(
        __('Default', 'perch') => '',
        __('Small', 'perch') => 'p-sm',
        __('Medium', 'perch') => 'p-md',
        __('large', 'perch') => 'p-lg',
        __('Font weight bold', 'perch') => 'p-lg font-weight-bold',
         __('Italic text', 'perch') => 'p-lg font-italic',
         __('Indicates uppercased text', 'perch') => 'p-lg text-uppercase',
    );
}

function perch_vc_size_class_options(){
    $arr = array(
        __('Default', 'perch') => '',
        __('Huge', 'perch') => 'huge',
        __('Extra large', 'perch') => 'xl',
        __('Large', 'perch') => 'lg',
        __('Medium', 'perch') => 'md',
        __('Small', 'perch') => 'sm',
        __('Extra small', 'perch') => 'xs',
    );

    return $arr;
}

function perch_vc_weight_class_options(){
    $arr = array(
        __('Default', 'perch') => '',
        __('Font weight thin', 'perch') => 'txt-300',    
        __('Font weight normal', 'perch') => 'txt-400',
        __('Font weight 500', 'perch') => 'txt-500',    
        __('Font weight 600', 'perch') => 'txt-600',    
        __('Font weight Bold', 'perch') => 'txt-700',    
        __('Font weight 800', 'perch') => 'txt-800',    
        __('Font weight 900', 'perch') => 'txt-900',  
         __('Section id', 'perch') => 'section-id',  
    );

    return $arr;
}


function perch_vc_size_options_param($param_name, $heading='', $std = '', $group='', $dep=array()){    
   
    $arr = array(
                'type' => 'dropdown',
                'heading' => $heading,
                'param_name' => $param_name,
                'value' => perch_vc_size_class_options(),
                'group' => __( 'Design option', 'perch' ),
            );
    if( $std != '' ) $arr['std'] = $std;
    if( $group != '' ) $arr['group'] = $group;
    if( !empty($dep) ) $arr['dependency'] = $dep;
    
    return $arr;
}

function perch_vc_weight_options_param($param_name, $heading='', $std = '', $group='', $dep=array()){    
    $arr = array(
                'type' => 'dropdown',
                'heading' => $heading,
                'param_name' => $param_name,
                'value' => perch_vc_weight_class_options(),
                'group' => __( 'Design option', 'perch' ),
            );
    if( $std != '' ) $arr['std'] = $std;
    if( $group != '' ) $arr['group'] = $group;
    if( !empty($dep) ) $arr['dependency'] = $dep;
    
    return $arr;
}

function perch_vc_color_options_param($param_name, $heading='', $std = '', $group='', $dep=array()){    
    $arr = array(
                'type' => 'dropdown',
                'heading' => $heading,
                'param_name' => $param_name,
                'value' => perch_vc_color_options(true),
                'group' => __( 'Design option', 'perch' ),
            );
    if( $std != '' ) $arr['std'] = $std;
    if( $group != '' ) $arr['group'] = $group;
    if( !empty($dep) ) $arr['dependency'] = $dep;
    
    return $arr;
}

function perch_vc_spacing_options($type='padding', $pos = 'bottom'){
    $total = apply_filters('perch_modules/vc_spacing_total', 200);
    $arr = array();
   
    $prefix = apply_filters( 'perch_modules/'.$type.'_'.$pos.'_class_prefix', '' );
    $arr = array(
        __('Inherit', 'perch') => '',     
    );
    for ($i=0; $i <= $total; $i+=5) { 
        $name = ucfirst($type).' '.$pos. ' '.$i.'px';
       $arr[$name] = $prefix.$i; 
    }
    return $arr;
}

function perch_vc_spacing_options_param($type = 'padding', $pos = 'bottom', $name = ''){   
    $prefix = ($type == 'padding')? 'p' : 'm'; 
    $param_name = $prefix.$pos;
    $param_name = ( $name != '' )? $name : $param_name;
    $heading = ucfirst($type).' '.$pos;
    return array(
                'type' => 'dropdown',
                'heading' => $heading,
                'param_name' => $param_name,
                'value' => perch_vc_spacing_options($type, $pos),
                'group' => __( 'Design Options', 'perch' ),
                'edit_field_class' => 'vc_col-sm-6', 
            );
}

function perch_vc_tag_options_param($param_name, $heading='', $std = '', $group='', $dep=array()){    
    
    $arr = array(
                'type' => 'dropdown',
                'heading' => $heading,
                'param_name' => $param_name,
                'value' => perch_vc_tag_options(),
                'group' => __( 'Design option', 'perch' ),
                'save_always' => true,
            );
    if( $std != '' ) $arr['std'] = $std;
    if( $group != '' ) $arr['group'] = $group;
    if( !empty($dep) ) $arr['dependency'] = $dep;

	return $arr;
}

function perch_vc_content_list_group(){
    return array(
            'type' => 'param_group',
            'save_always' => true,
            'heading' => __( 'Content list', 'perch' ),
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
                    'heading' => __( 'Title', 'perch' ),
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

if( !function_exists('perch_target_param_list') ):
function perch_target_param_list() {
    return array(
        __( 'Same window', 'perch' ) => '_self',
        __( 'New window', 'perch' ) => '_blank',
    );
}
endif;


function perch_vc_counter_group(){
    return array(
        'type' => 'param_group',
        'save_always' => true,
        'heading' => __( 'Counter up', 'perch' ),
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
                'heading' => __( 'Counter Prefix', 'perch' ),
                'param_name' => 'prefix',
                'description' => '',
                'value' => '3,',
                'admin_label' => true 
            ),
            array(
                 'type' => 'textfield',
                'heading' => __( 'Count', 'perch' ),
                'param_name' => 'count',
                'description' => 'Number only',
                'value' => '' ,
                'admin_label' => true 
            ),
             array(
                 'type' => 'textfield',
                'heading' => __( 'Title', 'perch' ),
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
        'group' => __( 'Content bottom', 'perch' ),  
    );
}

function perch_vc_techs_group(){
    return array(
        'type' => 'param_group',
        'save_always' => true,
        'heading' => __( 'Techs', 'perch' ),
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
                'heading' => __( 'Title', 'perch' ),
                'param_name' => 'title',
                'description' => '',
                'value' => '',
                'admin_label' => true 
            ),
             perch_vc_icon_set( 'fontawesome', 'icon' ),
             array(
                'type' => 'image_upload',
                'heading' => __( 'Icon Image', 'perch' ),
                'param_name' => 'image',
                'description' => 'You can use image instead of Icon',
                'value' => '' 
            ),
        ),
        'dependency' => array(
            'element' => 'display',
            'value' => 'techs'
        ),
        'group' => __( 'Content bottom', 'perch' ),  
    );
}

function perch_vc_strategy_list_group($group = true){
    $output = array(
            'type' => 'param_group',
            'save_always' => true,
            'heading' => __( 'Content group', 'perch' ),
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
                    'heading' => __( 'Description', 'perch' ),
                    'param_name' => 'title',
                    'description' => '',
                    'value' => '',
                    'admin_label' => true 
                ),
            ),
        );

    if($group) $output['group'] = __( 'Content', 'perch' );

    return $output;
}

function perch_vc_get_strategy_list( $type = 'list', $paramsArr = array() , $duration = 400  ){
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

function perch_vc_element_display_option(){
    return array(
                    'None' => 'none',
                    'Video link' => 'video',                    
                    'Counter' => 'counter',
                    'Techs' => 'techs',
                );
}

function perch_vc_element_icon_size(){
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

function perch_vc_get_content_list_group( $paramsArr = array(), $animation = '', $delay = '100'){
    if(empty($paramsArr)) return false;
    echo '<ul class="content-list">';
    foreach ($paramsArr as $key => $value):                     
        echo '<li class="wow '.esc_attr($animation).'" data-wow-delay="'. intval($delay).'ms">'.esc_attr($value['title']).'</li>';
        $delay = $delay + 100; 
    endforeach; 
    echo '</ul>';
}

function perch_button_groups_param() {
    return array(
        array(
             'type' => 'dropdown',
            'heading' => __( 'Button type', 'perch' ),
            'param_name' => 'button_type',
            'value' => array(
                 'Text button' => 'text_btn',
                'Image button' => 'img_btn' 
            ),
            'save_always' => true, 
            'admin_label' => true
        ),
        array(
            'type' => 'image_upload',
            'heading' => __( 'Button Image', 'perch' ),
            'param_name' => 'img_btn',
            'value' => get_template_directory_uri(). '/images/googleplay.png',
            'dependency' => array(
                 'element' => 'button_type',
                'value' => 'img_btn' 
            ),
        ),
        array(
            'type' => 'textfield',
            'heading' => __( 'Button size', 'perch' ),
            'param_name' => 'img_btn_size',
            'value' => '160',
            'dependency' => array(
                 'element' => 'button_type',
                'value' => 'img_btn' 
            ),
        ),
        array(
            'type' => 'textfield',
            'value' => 'Get Started Now',
            'heading' => 'Button title',
            'param_name' => 'button_text',
            'admin_label' => true,
        ),
        array(
            'type' => 'textfield',
            'value' => '#',
            'heading' => 'Button URL',
            'param_name' => 'button_url',
        ),
        perch_vc_icon_set('fontawesome','icon','fa fa-angle-double-right'),
        array(
             'type' => 'dropdown',
            'heading' => __( 'Button target', 'perch' ),
            'param_name' => 'button_target',
            'value' => array(
                 'Open link in a self tab' => '_self',
                'Open link in a new tab' => '_blank' 
            ) 
        ),
        array(
             'type' => 'dropdown',
            'heading' => __( 'Button style', 'perch' ),
            'param_name' => 'button_style',
            'std' => 'btn-preset',
            'value' => perch_btn_style_options(true),
            'dependency' => array(
                    'element' => 'button_type',
                    'value' => 'text_btn' 
                ),  
        ),
        array(
             'type' => 'dropdown',
            'std' => 'btn-normal',
            'value' => array( 
                'Default' => '',
                'Medium' => 'btn-md',                
                'Large' => 'btn-lg',
                'Small' => 'btn-sm',               
                
            ),
            'heading' => 'Button size',
            'param_name' => 'button_size',
            'dependency' => array(
                    'element' => 'button_type',
                    'value' => 'text_btn' 
                ),   
        ) 
    );
}
function perch_get_button_groups( $buttons = array(), $extra_class = '' ) {
    if ( empty( $buttons ) )
        return;

    $darkcolorArr = perch_default_dark_color_classes(array('prefix' => 'btn-'));   
    $darkcolortraArr = perch_default_dark_color_classes(array('prefix' => 'btn-tra-'));

    $output = '';
    foreach ( $buttons as $key => $value ):
        extract( shortcode_atts( array(
            'button_type' => 'text_btn', 
            'img_btn' => get_template_directory_uri(). '/images/googleplay.png',
            'img_btn_size' => '160',
            'icon_position' => 'icon_position-right',
            'button_text' => 'Get Started Now',
            'button_url' => '#',
            'button_target' => '_self',
            'button_style' => 'btn-preset',
            'button_size' => '',
            'icon' => 'fa fa-angle-double-right'
        ), $value ) );
        $iconClass              = array();
        $iconClass[ ]           = $icon;
        $iconClass              = array_filter( $iconClass );

        if( $button_type == 'text_btn' ){
             $buttonClass = array('btn','btn-arrow'); 
             $btntxt = perch_parse_text( $button_text, array( 'tag' => 'strong') );
             $buttonClass[]         = $button_style;
            $buttonClass[]         = $button_size;
        }else{
            $icon_position = '';
            $buttonClass =  array('img-btn');
            $btntxt = '<img src="'.esc_url($img_btn).'" alt="'.esc_attr($button_text).'" class="store-btn" width="'.intval($img_btn_size).'">';
        }
        $buttonClass[] = $extra_class;

            
        if(in_array( $button_style, $darkcolorArr)){
            $buttonClass[] = 'btn-type-dark';
        }
        if(in_array( $button_style, $darkcolortraArr)){
            $buttonClass[] = 'btn-hover-type-dark';
        }
 
        
        $buttonClass            = array_filter( $buttonClass );
        $buttonAttr             = array( );
        $buttonAttr[ 'target' ] = $button_target;
        $buttonAttr[ 'href' ]   = esc_url( $button_url );
        $buttonAttr[ 'title' ]  = esc_attr( $button_text );
        $buttonAttr[ 'class' ]  = implode( ' ', $buttonClass );
        $attr                   = '';
        foreach ( $buttonAttr as $key => $value ) {
            $attr .= ' ' . $key . '="' . $value . '"';
        } //$buttonAttr as $key => $value
       
        if ( $icon != '' ) {
            $icon = '<i class="' . implode( ' ', $iconClass ) . '"></i>';
        } //$icon_perch != ''
        $output .= '<a' . $attr . '><span>';
        $output .= $btntxt;
        $output .= ( $icon_position == 'icon_position-right' ) ? $icon : '';
        $output .= '</span></a>';
    endforeach;
    return $output;
}

function perch_modules_shortcode_classes($atts, $base){
    $css_class = $mtop = $mbottom = $pleft = $pright = $el_class = $el_id = $align = $display_as = $css_animation = '';
    extract($atts);
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, perch_shortcode_custom_css_class( $css, ' ' ), $base , $atts );
    

    $classes = array(             
            $css_class,
            $mtop, 
            $mbottom,
            $pleft,  
            $pright,
            PerchVcMap::getExtraClass( $el_class ), 
            PerchVcMap::getCSSAnimation( $css_animation, $atts ),
            // custom class
            $align,
            $display_as,
        );       
    $classes = array_filter($classes);
    $classes = array_unique($classes);

    return $classes;    
}

function perch_modules_shortcode_wrapper_attributes($atts, $base){
    $classes = perch_modules_shortcode_classes($atts, $base );
    extract($atts);
    $wrapper_attributes = array();
        if ( ! empty( $el_id ) ) {
            $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
        }
    $wrapper_attributes[] = (!empty($classes))?'class="'.trim(implode(' ', $classes)).'"' : '';
    $wrapper_attributes = array_filter($wrapper_attributes);

    $wrapper_attributes = apply_filters( 'perch_modules/wrapper_attributes', $wrapper_attributes, $atts);

    return $wrapper_attributes;    
}

/**
* vc_map default values
* @param array
*
* @return array
*/
function perch_modules_vc_get_params_value($args = array(), $_content = NULL){
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
    $newarray['template'] = $args['base'];
    $newarray['base'] = $args['base'];


    if( !empty($newarray) ) $array = $newarray;

    return $array;
}

function perch_modules_parse_text( $text, $args = array() ) {

   
    extract( shortcode_atts( array( 
        'inner_tag'  => 'span',     
        'all_classes' => '',
        'inline_css' => '',
        'before' => '',
        'after' => '' 
    ), $args ) );
    
    $tag = ( $inner_tag != '' )?$inner_tag : 'span';

    $text = esc_attr($text);
    
    preg_match_all( "/\{([^\}]*)\}/", $text, $matches );

    if ( !empty( $matches ) ) {     
        foreach ( $matches[ 1 ] as $value ) {
            $find    = "{{$value}}";
            $replace = "{$before}<{$tag} $all_classes{$inline_css}>{$value}</{$tag}>{$after}";
            $text    = str_replace( $find, $replace, $text );
        } //$matches[1] as $value
    } //!empty( $matches )
  
    return  $text;
}

function perch_get_social_icons( $social_icons = array( ), $args = array( ) ) {
    if ( empty( $social_icons ) )
        return;
    $output = '';
    extract( shortcode_atts( array(
        'wrap' => 'ul',
        'wrapclass' => '',
        'linkwrapbefore' => '',
        'linkwrap' => 'li',
        'linkwrapclass' => '',
        'linkclass' => '',
        'iconprefix' => 'foo',
        'iconclass' => '',
        'linktext' => false, 
        'icon' => true, 
    ), $args ) );
    $output = ( $wrap != '' ) ? '<' . esc_attr( $wrap ) . ( ( $wrapclass != '' ) ? ' class="' . esc_attr( $wrapclass ) . '"' : '' ) . '>' : '';
    $output .= ( $linkwrapbefore != '' ) ? wpautop( $linkwrapbefore ) : '';
    $linkbefore = ( $linkwrap != '' ) ? '<' . esc_attr( $linkwrap ) . ( ( $linkwrapclass != '' ) ? ' class="' . esc_attr( $linkwrapclass ) . '"' : '' ) . '>' : '';
    $linkafter  = ( $linkwrap != '' ) ? '</' . esc_attr( $linkwrap ) . '>' : '';
    
    foreach ( $social_icons as $key => $value ) {
        $url        = isset( $value[ 'icon_link' ][ 'input' ] ) ? $value[ 'icon_link' ][ 'input' ] : '';
        $title      = isset( $value[ 'title' ] ) ? $value[ 'title' ] : '';

        $_linkclass = array();
        $_linkclass[]  = ( $linkclass != '' ) ? esc_attr( $linkclass ) : '';
        $_linkclass[]  = ( $iconprefix != '' ) ? esc_attr($iconprefix).'-'. sanitize_title($title) : '';
        $_linkclass = array_filter($_linkclass);
        if( !empty($_linkclass) ) $linkclass = implode(' ', $_linkclass);

        $icon_class = isset( $value[ 'icon_link' ][ 'icon' ] ) ? $value[ 'icon_link' ][ 'icon' ] : '';
        $icon_class .= ( $iconclass ) ? ' ' . $iconclass : '';

        $iconhtml = ($icon)? '<i class="fa ' . esc_attr( $icon_class ) . '"></i>' : '';
        $linktexthtml =  ( $linktext ) ? '<span>' . esc_attr( $title ) . '</span>' : ''; 

        $output .= $linkbefore . 
        '<a target="_blank" href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" class="' . trim($linkclass) . '">
          '.$iconhtml.'
          '.$linktexthtml.'
          </a>' 
      . $linkafter;
    } //$social_icons as $key => $value
    $output .= ( $wrap != '' ) ? '</' . esc_attr( $wrap ) . '>' : '';
    return $output;
}

if ( ! function_exists( 'perch_get_trim_words' ) ) :
function perch_get_trim_words($content='', $count=30, $ext='', $wrap=false, $btn=false, $btnclass="read-more", $btntext=''){
    global $post;
    if($count == 0) return false;
    
    if($content == '') $content = get_the_excerpt($post->ID);
    if($wrap){
        $output =  '<p>'.wp_trim_words( $content, $count, $ext ).'</p>';
    }else{
        $output =  wp_trim_words( $content, $count, $ext );
    }
    
    if($btn){
        $readmore_text = shiftkey_get_option( 'read_more_text', 'Read More' );
        $readmore_text = ($btntext == '')? $readmore_text : $btntext;

        $output .= '<a class="'.esc_attr($btnclass).'" href="'.get_permalink($post->ID).'">'.
        sprintf( _x( '%s','Continue Reading text', 'shiftkey' ), esc_attr($readmore_text)).' <i class="fa fa-long-arrow-right"></i></a>';
    }

    return $output;
          
}
endif;

if ( ! function_exists( 'perch_get_the_term_list' ) ) :
function perch_get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $name = true ) {
    $terms = get_the_terms( $id, $taxonomy );
    if ( is_wp_error( $terms ) )
        return $terms;
    if ( empty( $terms ) )
        return false;
    $links = array( );
    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        } //is_wp_error( $link )
        $links[ ] = ( $name ) ? $term->name : $term->slug;
    } //$terms as $term
    /**    
    * Filters the term links for a given taxonomy.    
    *    
    * The dynamic portion of the filter name, `$taxonomy`, refers    
    * to the taxonomy slug.
    * @param array $links An array of term links.    
    */
    $term_links = apply_filters( "term_links-$taxonomy", $links );
    return $before . join( $sep, $term_links ) . $after;
}
endif;