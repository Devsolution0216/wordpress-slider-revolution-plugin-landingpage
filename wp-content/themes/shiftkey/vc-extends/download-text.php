<?php
add_filter( 'perch_modules/vc/perch_download_text', 'shiftkey_vc_download_text_default_args' );
function shiftkey_vc_download_text_default_args( $args ){
	$default = array(
        'title' => 'Capture & Share Your Best Memories', 
        'title_font_container' => 'tag:h2|size:lg',
        'subtitle' => 'Feugiat eros, ac tincidunt ligula massa in faucibus orci luctus posuere cubilia curae integer congue leo metus',
        'subtitle_font_container' => 'tag:p|size:lg',  
        'enable_buttons' => 'yes',  
        'params' => urlencode( json_encode( array(
                      array(
                          'button_type' => 'img_btn', 
                          'img_btn' => get_template_directory_uri(). '/images/store_badges/appstore-tra-white.png',
                          'img_btn_size' => '160',                          
                          'button_text' => 'Appstore',
                          'button_url' => '#',
                          'button_target' => '_blank',
                      ),
                      array(
                        'button_type' => 'img_btn', 
                        'img_btn' => get_template_directory_uri(). '/images/store_badges/googleplay-tra-white.png',
                        'img_btn_size' => '160',                          
                        'button_text' => 'Googleplay',
                        'button_url' => '#',
                        'button_target' => '_blank',
                      ),
                  ) ) ),  
        'buttons_desc' => '',
        'periodic_animation' => 'yes',
        'animation_interval' => '100',
        'el_class' => 'download-txt white-color',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}
