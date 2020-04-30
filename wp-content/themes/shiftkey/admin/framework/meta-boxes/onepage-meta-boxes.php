<?php
/**
 * Initialize the meta boxes. 
 */

add_action( 'admin_init', 'shiftkey_general_onepage_meta_boxes' );
if( !function_exists('shiftkey_general_onepage_meta_boxes') ):
function shiftkey_general_onepage_meta_boxes() {
    $screen = get_current_screen(); 

    $my_meta_box = array(
        'id'        => 'shiftkey_template_sttings_boxes',
        'title'     => esc_attr__('Shiftkey page template Settings', 'shiftkey'),
        'desc'      => '',
        'pages'     => array( 'page' ),
        'context'   => 'side',
        'priority'  => 'high',
        'fields'    =>  array(
            array(
                'id' => 'shiftkey_template_type',
                'label' => esc_attr__('Page template type', 'shiftkey'),
                'desc' => '',
                'std' => '',
                'type' => 'select',
                'choices' => array(           
                array( 'label' => 'Default', 'value' => '' ),
                array( 'label' => 'Landing page template', 'value' => 'landing' ),
                array( 'label' => 'Front page template', 'value' => 'frontpage' )
                )
            ),
        ),

      );
    ot_register_meta_box( $my_meta_box );   


    $navarr = array(
            array(
                 'id' => 'nav_general_option_tab',
                'label' => esc_attr__( 'General settings', 'shiftkey' ),
                'desc' => esc_attr__( 'Display social Icon or Buttons', 'shiftkey' ),
                'type' => 'tab',
                'section' => 'header_options',
                //'class' => 'half-column-size', 
            ),
            array(
                'id' => 'one_page_wp_nav',
                'label' => esc_attr__('Select Nav menu', 'shiftkey'),
                'desc' => '<a href="' . admin_url( 'nav-menus.php' ) . '" class="nav-link">' . __( 'Add a menu', 'shiftkey' ) . '</a>',
                'std' => '',
                'type' => 'select',
                'choices' => shiftkey_get_terms_choices('nav_menu')
            ),
        ); 



      $my_meta_box = array(
        'id'        => 'shiftkey_onepage_sttings_boxes',
        'title'     => esc_attr__('Landing Page Navbar Settings', 'shiftkey'),
        'desc'      => '',
        'pages'     => array( 'page' ),
        'context'   => 'normal',
        'priority'  => 'high',
        'fields'    =>  array_merge($navarr, shiftkey_header_options())

      );
      ot_register_meta_box( $my_meta_box );

      $my_meta_box = array(
        'id'        => 'shiftkey_onepage_footer_sttings_boxes',
        'title'     => esc_attr__('Landing Page footer Settings', 'shiftkey'),
        'desc'      => '',
        'pages'     => array( 'page' ),
        'context'   => 'normal',
        'priority'  => 'high',
        'fields'    => array(
            array(
                 'id' => 'onepage_footer_display',
                'label' => esc_attr__( 'Footer display', 'shiftkey' ),
                'type' => 'on-off',
                'std' => 'on',
                //'class' => 'half-column-size', 
            ),
            array(
                 'id' => 'quickform_area',
                'label' => esc_attr__( 'Quick contact form Display','shiftkey' ),
                'desc' => esc_attr__( 'Display in bottom of page','shiftkey' ),
                'std' => 'off',
                'type' => 'on-off',
                'section' => 'footer_options' 
            ),
            array(
                'id' => 'footer_bg_style',
                'label' => esc_attr__('Footer background style', 'shiftkey'),
                'desc' => '',
                'std' => shiftkey_get_option( 'footer_bg_style', 'bg-tra' ),
                'type' => 'select',
                'choices' => shiftkey_bg_color_options(),
            ),
        )

      );
      ot_register_meta_box( $my_meta_box );

}
endif;