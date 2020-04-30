<?php
function perch_modules_meta_boxes_extension(){
    return apply_filters( 'perch_modules/meta_boxes_extension', array('meta-box') );
}

add_action( 'after_setup_theme', 'perch_modules_load_meta_boxes_extension', 1 );
function perch_modules_load_meta_boxes_extension(){
    $files = perch_modules_meta_boxes_extension();

    if( !empty($files) ){
        /* require the files */
        foreach ( $files as $file ) {
            perch_modules_load_file( PERCH_MODULES_DIR .DIRECTORY_SEPARATOR. "meta-boxes" .DIRECTORY_SEPARATOR. "{$file}" .DIRECTORY_SEPARATOR. "{$file}.php" );
        }
    }
     
}

function perch_modules_load_file($file){
    if( file_exists($file) ){
        include_once($file);
    }    
}

function perch_modules_settings_name(){
    return sanitize_title(perch_modules_current_theme().'_settings');
}

function perch_modules_settings_page(){
    return sanitize_title(perch_modules_current_theme().'-settings');
}

add_filter( 'mb_settings_pages', 'perch_modules_options_page' );
function perch_modules_options_page( $settings_pages ) {
    $settings_pages[] = array(
        'id'          => perch_modules_settings_page(),
        'option_name' => perch_modules_settings_name(),
        'menu_title'  => perch_modules_current_theme().' settings',
        'icon_url'    => 'dashicons-edit',
        'style'       => 'no-boxes',
        'columns'     => 1,
        'tabs'        => array(
            'social_settings' => 'Social settings',
            'button_settings'  => 'Button settings',
            'faq'     => 'FAQ & Help',
        ),
        'position'    => 68,
        'help_tabs' => array(
				    array(
				        'title'   => 'General',
				        'content' => '<p>This tab displays the general information about the theme.</p>',
				    ),
				    array(
				        'title'   => 'Homepage',
				        'content' => '<p>This tab displays the instruction for setting up the homepage.</p>',
				    ),
				),
    );

    /*$settings_pages[] = array(
        'id'          => sanitize_title(perch_modules_current_theme().'-social-settings'),
        'option_name' => sanitize_title(perch_modules_current_theme().'-social-settings'), 
        'menu_title'  => 'Social settings',
        'parent'      => perch_modules_settings_page(),       
    );*/

    /*$settings_pages[] = array(
        'id'          => sanitize_title(perch_modules_current_theme().'-button-settings'),
        'option_name' => sanitize_title(perch_modules_current_theme().'-button-settings'),
        'menu_title'  => 'Button settings',
        'parent'      => perch_modules_settings_page(),
    );*/
    return $settings_pages;
}

require_once PERCH_MODULES_DIR . '/meta-boxes/class.init.php';