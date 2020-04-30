<?php
foreach ( glob( PERCH_MODULES_DIR . "/widgets/*-widget.php" ) as $filename ) {
    include $filename;
} //glob( PERCH_MODULES_DIR . "/admin/widgets/*.php" ) as $filename
if ( !function_exists( 'perch_custom_register_widgets' ) ):
    function perch_custom_register_widgets( ) {
        if ( class_exists( 'Perch_About_me_Widget' ) ) {
            register_widget( 'Perch_About_me_Widget' );
        } //class_exists( 'Perch_About_me_Widget' )
        /*if( class_exists('Perch_Testimonial_Widget') ){         
        register_widget( 'Perch_Testimonial_Widget' );         
        }        
        */
        if ( class_exists( 'Perch_Widget_Recent_Posts' ) ) {
            register_widget( 'Perch_Widget_Recent_Posts' );
        } //class_exists( 'Perch_Widget_Recent_Posts' )
        if ( class_exists( 'Perch_How_can_We_Help_Widget' ) ) {
            register_widget( 'Perch_How_can_We_Help_Widget' );
        } //class_exists( 'Perch_How_can_We_Help_Widget' )
        if ( class_exists( 'Perch_Footer_Subscription_Form' ) ) {
            register_widget( 'Perch_Footer_Subscription_Form' );
        } //class_exists( 'Perch_Footer_Subscription_Form' )
        if ( class_exists( 'Perch_Get_In_Touch' ) ) {
            register_widget( 'Perch_Get_In_Touch' );
        } //class_exists( 'Perch_Get_In_Touch' )
        if ( class_exists( 'Perch_Social_links' ) ) {
            register_widget( 'Perch_Social_links' );
        } //class_exists( 'Perch_Social_links' )
        if ( class_exists( 'Perch_Download_link_Widget' ) ) {
            register_widget( 'Perch_Download_link_Widget' );
        } //class_exists( 'Perch_Download_link_Widget' )

         if ( class_exists( 'Perch_Store_badges_Widget' ) ) {
		    register_widget( 'Perch_Store_badges_Widget' );
		}

    }
    add_action( 'widgets_init', 'perch_custom_register_widgets' );
endif;

function perch_context_args($context){
    return $context;
}