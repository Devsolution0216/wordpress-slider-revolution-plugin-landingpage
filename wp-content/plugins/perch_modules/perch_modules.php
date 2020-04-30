<?php
/*
Plugin Name: Perch Modules
Plugin URI: http://themeperch.net/
Description: Just another Shortcode and post type plugin. Simple but flexible.
Author: ThemePerch
Author URI: http://themeforest.net/user/themeperch?ref=themeperch
Text Domain: perch
Domain Path: /lang/
Version: 1.3.0.1
*/

define( 'PERCH_MODULES_VERSION', '1.3.0.1' );

define( 'PERCH_MODULES_REQUIRED_WP_VERSION', '4.3' );

define( 'PERCH_MODULES_PLUGIN', __FILE__ );

define( 'PERCH_MODULES_PLUGIN_BASENAME', plugin_basename( PERCH_MODULES_PLUGIN ) );

define( 'PERCH_MODULES_PLUGIN_NAME', trim( dirname( PERCH_MODULES_PLUGIN_BASENAME ), '/' ) );

define( 'PERCH_MODULES_DIR', untrailingslashit( dirname( PERCH_MODULES_PLUGIN ) ) );

// Deprecated, not used in the plugin core. Use wpcf7_plugin_url() instead.
define( 'PERCH_MODULES_URI', untrailingslashit( plugins_url( '', PERCH_MODULES_PLUGIN ) ) );

if ( !class_exists( 'ReduxFramework' ) 
  && file_exists( PERCH_MODULES_DIR . '/ReduxFramework/redux-framework/redux-framework.php' ) ) {
    require_once( PERCH_MODULES_DIR . '/ReduxFramework/redux-framework/redux-framework.php' );
}

if ( class_exists( 'ReduxFramework' ) 
  && file_exists( PERCH_MODULES_DIR . '/ReduxFramework/redux-vendor-support/redux-vendor-support.php' ) ) {
    require_once( PERCH_MODULES_DIR . '/ReduxFramework/redux-vendor-support/redux-vendor-support.php' );
}

add_action('plugins_loaded', 'perch_modules_load_textdomain');
function perch_modules_load_textdomain() {
	load_plugin_textdomain( 'perch', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

require_once PERCH_MODULES_DIR . '/scripts.php';
require_once PERCH_MODULES_DIR . '/social-share.php';

if( !function_exists('perch_get_posts_dropdown') ):
function perch_get_posts_dropdown( $args = array() ) {
    global $wpdb, $post;

    $dropdown = array();
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post(); 
            $dropdown[get_the_ID()] = get_the_title();
        }
    }
    wp_reset_postdata();

    return $dropdown;
}
endif;

if( !function_exists('perch_disable_post_type_arr') ):
function perch_disable_post_type_arr(){
    if(function_exists('ot_get_option')){
      return ot_get_option('disable_post_type', array());
    }else{
      return array();
    }
}
endif;

if( !function_exists('perch_get_terms') ):
function perch_get_terms( $tax = 'category', $key = 'id' ) {
    $terms = array();

    if(!taxonomy_exists($tax)) return false;

    if ( $key === 'id' ) foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term ) $terms[$term->term_id] = $term->name;
      elseif ( $key === 'slug' ) foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term ) $terms[$term->slug] = $term->name;
        return $terms;
}
endif;

if(!function_exists('perch_number_settings_field')):
function perch_number_settings_field( $settings, $value ) {
   return '<div class="my_param_block">'
             .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field" type="number" min="'.intval($settings['min']).'" max="'.intval($settings['max']).'" step="'.intval($settings['step']).'" value="' . esc_attr( $value ) . '" />' .
             '</div>'; // This is html markup that will be outputted in content elements edit form
}
endif;

if(!function_exists('perch_vc_image_upload_settings_field')):
function perch_vc_image_upload_settings_field($settings, $value){
  return '<div class="perch-upload-container">
      <input type="text" name="' . esc_attr( $settings['param_name'] ) . '" value="'.esc_url($value).'" class="wpb_vc_param_value wpb-textinput perch-generator-attr perch-generator-upload-value" />
      <a href="javascript:;" class="button perch-upload-button"><span class="wp-media-buttons-icon"></span>'.__( 'Media manager', 'perch' ).'</a>
      <img width="80" src="'.esc_url($value).'" alt="Image URL">     
    </div>';
}
endif;

if(!function_exists('perch_perch_select_settings_field')):
function perch_perch_select_settings_field( $args, $value ) {
    $selected = is_array($value)? $value : explode(',', $value);
    $args = wp_parse_args( $args, array(
        'param_name'       => '',
        'heading'     => '',
        'class'    => 'wpb_vc_param_value wpb-input wpb-select dropdown',
        'multiple' => '',
        'size'     => '',
        'disabled' => '',
        'selected' => $selected,
        'none'     => '',
        'value'  => array(),
        'style' => '',
        'format'   => 'keyval', // keyval/idtext
        'noselect' => '' // return options without <select> tag
      ) );
    $options = array();
    if ( !is_array( $args['value'] ) ) $args['value'] = array();
     if ( $args['param_name'] ) $name = ' name="' . $args['param_name'] . '"';
    if ( $args['param_name'] ) $args['param_name'] = ' id="' . $args['param_name'] . '"';   
    if ( $args['class'] ) $args['class'] = ' class="' . $args['class'] . '"';
    if ( $args['style'] ) $args['style'] = ' style="' . esc_attr( $args['style'] ) . '"';
    if ( $args['multiple'] ) $args['multiple'] = ' multiple="multiple"';
    if ( $args['disabled'] ) $args['disabled'] = ' disabled="disabled"';
    if ( $args['size'] ) $args['size'] = ' size="' . $args['size'] . '"';
    if ( $args['none'] && $args['format'] === 'keyval' ) $args['options'][0] = $args['none'];
    if ( $args['none'] && $args['format'] === 'idtext' ) array_unshift( $args['options'], array( 'id' => '0', 'text' => $args['none'] ) );
    
    // keyval loop
    // $args['options'] = array(
    //   id => text,
    //   id => text
    // );
    if ( $args['format'] === 'keyval' ) foreach ( $args['value'] as $id => $text ) {
        $options[] = '<option value="' . (string) $id . '">' . (string) $text . '</option>';
      }
    // idtext loop
    // $args['options'] = array(
    //   array( id => id, text => text ),
    //   array( id => id, text => text )
    // );
    elseif ( $args['format'] === 'idtext' ) foreach ( $args['options'] as $option ) {
        if ( isset( $option['id'] ) && isset( $option['text'] ) )
          $options[] = '<option value="' . (string) $option['id'] . '">' . (string) $option['text'] . '</option>';
      }
    $options = implode( '', $options );

    if(is_array($args['selected'])){
        foreach ($args['selected'] as $key => $value) {
          $options = str_replace( 'value="' . $value . '"', 'value="' . $value . '" selected="selected"', $options );
        }
    }else{
      $options = str_replace( 'value="' . $args['selected'] . '"', 'value="' . $args['selected'] . '" selected="selected"', $options );
    }
    
    $output = ( $args['noselect'] ) ? $options : '<select' .$name. $args['param_name'] . $args['class'] . $args['multiple'] . $args['size'] . $args['disabled'] . $args['style'] . '>' . $options . '</select>';
   // $output .= '<input type="hidden" '.$name.' value="'.$value.'">';
    return '<div class="perch_select_param_block">'.$output.'</div>';
}
endif;

if(function_exists('vc_add_shortcode_param')):
if(function_exists('perch_number_settings_field')){ 
  vc_add_shortcode_param( 'number', 'perch_number_settings_field' );
}

if(function_exists('perch_perch_select_settings_field')){ 
  vc_add_shortcode_param( 'perch_select', 'perch_perch_select_settings_field' );
}

if(function_exists('perch_vc_image_upload_settings_field')){ 
  vc_add_shortcode_param( 'image_upload', 'perch_vc_image_upload_settings_field' );
}   
endif;
    


function perch_archive_page(){
  global $wpdb;
    

    // Create post object
    $my_post = array(
      'post_title'    => 'Team member',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type'     => 'page',
    );

    // Insert the post into the database
    $page = get_page_by_title( 'Team member' );
    if(get_post_status($page->ID) != 'publish'){
      $post_id = wp_insert_post( $my_post, '' );
      update_post_meta($post_id, 'header_image', get_template_directory_uri().'/images/team/team-header-bg.jpg');
      delete_option('team_archive_id');
      add_option('team_archive_id', $post_id);
    }
    
}

function perch_modules_supported_themes(){
    $array = array( 'Shiftkey', 'Genemy', 'Pergo', 'Appset', 'Landpick', 'Nextapp', 'Daisy', 'Bookpress', 'Golfa', 'Landx' );
    return apply_filters( 'perch_modules/supported_themes', $array );
}

function perch_modules_current_theme(){
   return apply_filters( 'perch_modules/current_theme', 'Shiftkey' );
}

function is_perch_modules_supported_theme(){
  $theme = wp_get_theme();
  if ( in_array($theme->name, perch_modules_supported_themes()) || 
    in_array($theme->parent_theme, perch_modules_supported_themes()) ) 
    return true;
  else
    return false;
}
require_once PERCH_MODULES_DIR . '/helpers.php';
require_once PERCH_MODULES_DIR . '/meta-boxes/inc.php';
require_once PERCH_MODULES_DIR . '/google-web-fonts.php';
require_once PERCH_MODULES_DIR . '/bootstrap-iconpicker/iconpicker.php';


if ( is_perch_modules_supported_theme() ){  
  require_once PERCH_MODULES_DIR . '/post-types.php';

  if( defined( 'WPB_VC_VERSION' ) ) {
    require_once PERCH_MODULES_DIR . '/shortcodes.php';
    require_once PERCH_MODULES_DIR . '/vc-extends.php';   
  }
	require_once PERCH_MODULES_DIR . '/widgets/widgets.php';

}else{
	function perch_modules_admin_notice__error() {
		$class = 'notice notice-error';
    $current_theme = perch_modules_current_theme();
		$message = sprintf(__( 'Oops! An error has occurred. <strong>%s shortcode and Post type plugin</strong> is disabled. This plugin only worked when <strong>%s</strong> theme is activated.', 'perch' ), $current_theme, $current_theme);
		echo '<div class="'.esc_attr($class).'"><p>'.force_balance_tags($message).'</p></div>'; 
	}
	add_action( 'admin_notices', 'perch_modules_admin_notice__error' );
}

if( !function_exists('perch_module_get_posts_dropdown') ):
function perch_module_get_posts_dropdown( $args = array() ) {
    global $wpdb, $post;

    $dropdown = array();
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post(); 
            $dropdown[get_the_ID()] = get_the_title();
        }
    }
    wp_reset_postdata();

    return $dropdown;
}
endif;

if( !function_exists('perch_module_get_terms') ):
function perch_module_get_terms( $tax = 'category', $key = 'id' ) {
    $terms = array();

    if(!taxonomy_exists($tax)) return false;

    if ( $key === 'id' ) foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term ) $terms[$term->term_id] = $term->name;
      elseif ( $key === 'slug' ) foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term ) $terms[$term->slug] = $term->name;
        return $terms;
}
endif;

if(!function_exists('perch_module_number_settings_field')):
function perch_module_number_settings_field( $settings, $value ) {
   return '<div class="my_param_block">'
             .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field" type="number" min="'.intval($settings['min']).'" max="'.intval($settings['max']).'" step="'.intval($settings['step']).'" value="' . esc_attr( $value ) . '" />' .
             '</div>'; // This is html markup that will be outputted in content elements edit form
}
endif;

if(!function_exists('perch_module_vc_image_upload_settings_field')):
function perch_module_vc_image_upload_settings_field($settings, $value){
  return '<div class="perch-upload-container">
      <input type="text" name="' . esc_attr( $settings['param_name'] ) . '" value="'.esc_url($value).'" class="wpb_vc_param_value wpb-textinput perch-generator-attr perch-generator-upload-value" />
      <a href="javascript:;" class="button perch-upload-button"><span class="wp-media-buttons-icon"></span>'.__( 'Media manager', 'perch' ).'</a>
      <img width="80" src="'.esc_url($value).'" alt="Image URL">     
    </div>';
}
endif;

if(!function_exists('perch_module_perch_select_settings_field')):
function perch_module_perch_select_settings_field( $args, $value ) {
    $selected = is_array($value)? $value : explode(',', $value);
    $args = wp_parse_args( $args, array(
        'param_name'       => '',
        'heading'     => '',
        'class'    => 'wpb_vc_param_value wpb-input wpb-select dropdown',
        'multiple' => '',
        'size'     => '',
        'disabled' => '',
        'selected' => $selected,
        'none'     => '',
        'value'  => array(),
        'style' => '',
        'format'   => 'keyval', // keyval/idtext
        'noselect' => '' // return options without <select> tag
      ) );
    $options = array();
    if ( !is_array( $args['value'] ) ) $args['value'] = array();
     if ( $args['param_name'] ) $name = ' name="' . $args['param_name'] . '"';
    if ( $args['param_name'] ) $args['param_name'] = ' id="' . $args['param_name'] . '"';   
    if ( $args['class'] ) $args['class'] = ' class="' . $args['class'] . '"';
    if ( $args['style'] ) $args['style'] = ' style="' . esc_attr( $args['style'] ) . '"';
    if ( $args['multiple'] ) $args['multiple'] = ' multiple="multiple"';
    if ( $args['disabled'] ) $args['disabled'] = ' disabled="disabled"';
    if ( $args['size'] ) $args['size'] = ' size="' . $args['size'] . '"';
    if ( $args['none'] && $args['format'] === 'keyval' ) $args['options'][0] = $args['none'];
    if ( $args['none'] && $args['format'] === 'idtext' ) array_unshift( $args['options'], array( 'id' => '0', 'text' => $args['none'] ) );
    
    // keyval loop
    // $args['options'] = array(
    //   id => text,
    //   id => text
    // );
    if ( $args['format'] === 'keyval' ) foreach ( $args['value'] as $id => $text ) {
        $options[] = '<option value="' . (string) $id . '">' . (string) $text . '</option>';
      }
    // idtext loop
    // $args['options'] = array(
    //   array( id => id, text => text ),
    //   array( id => id, text => text )
    // );
    elseif ( $args['format'] === 'idtext' ) foreach ( $args['options'] as $option ) {
        if ( isset( $option['id'] ) && isset( $option['text'] ) )
          $options[] = '<option value="' . (string) $option['id'] . '">' . (string) $option['text'] . '</option>';
      }
    $options = implode( '', $options );

    if(is_array($args['selected'])){
        foreach ($args['selected'] as $key => $value) {
          $options = str_replace( 'value="' . $value . '"', 'value="' . $value . '" selected="selected"', $options );
        }
    }else{
      $options = str_replace( 'value="' . $args['selected'] . '"', 'value="' . $args['selected'] . '" selected="selected"', $options );
    }
    
    $output = ( $args['noselect'] ) ? $options : '<select' .$name. $args['param_name'] . $args['class'] . $args['multiple'] . $args['size'] . $args['disabled'] . $args['style'] . '>' . $options . '</select>';
   // $output .= '<input type="hidden" '.$name.' value="'.$value.'">';
    return '<div class="perch_select_param_block">'.$output.'</div>';
}
endif;

/**
        
 * Add new fields above 'Update' button.        
 */    
function perch_additional_profile_fields( $user ) {
        
    $title =  get_the_author_meta( 'title', $user->ID );
    ?>
   
    <table class="form-table">
     <tr>
       <th><label for="title"><?php echo esc_attr(__('Title', 'perch')); ?></label></th>
       <td>
        <input name="title" id="title" value="<?php echo esc_attr($title) ?>" class="regular-text" type="text">
       </td>
     </tr>
    </table>
    <?php
} 
        
add_action( 'show_user_profile', 'perch_additional_profile_fields' );        
add_action( 'edit_user_profile', 'perch_additional_profile_fields' );

/**    
 * Save additional profile fields.
 */   
function perch_save_profile_fields( $user_id ) {
        
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
     return false;
    }
    if ( empty( $_POST['title'] ) ) {
     return false;
    }
    update_usermeta( $user_id, 'title', $_POST['title'] );
}
        
add_action( 'personal_options_update', 'perch_save_profile_fields' );
add_action( 'edit_user_profile_update', 'perch_save_profile_fields' );