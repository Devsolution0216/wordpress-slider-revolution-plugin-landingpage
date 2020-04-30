<?php
defined( 'ABSPATH' ) || exit;
/*
* Shiftkey hooks class
*/

add_action( 'shiftkey/preloader', 'shiftkey_preloader_template_part', 10 );
// header hook

add_action( 'shiftkey/header', 'shiftkey_navbar_template_part', 10 );
add_action( 'shiftkey/header', 'shiftkey_banner_template_part', 15 );


add_action( 'shiftkey/header/logo', 'shiftkey_header_logo', 10 );
add_action( 'shiftkey/header/menu', 'shiftkey_header_mobile_menu_icon', 10 );
add_action( 'shiftkey/header/menu', 'shiftkey_header_nav_menu', 15 );
add_action( 'shiftkey/header/menu/after', 'shiftkey_header_nav_menu_after', 10 );
//footer hooks
add_action( 'shiftkey/footer/before', 'shiftkey_related_posts_callback' );
add_action( 'shiftkey/footer/before', 'shiftkey_newsletter_form_template_part' );
add_action( 'shiftkey/footer/before', 'shiftkey_cta_template_part' );
add_action( 'shiftkey/footer', 'shiftkey_footer_widget_area_template_part', 10 );
add_action( 'shiftkey/footer', 'shiftkey_footer_copyright_template_part', 20 );
add_action( 'shiftkey/footer/after', 'shiftkey_quick_contact_form_template_part', 10 );

//posts hook
// archive post
add_action( 'shiftkey_post_content', 'shiftkey_post_format_callback' );
add_action( 'shiftkey_post_content', 'shiftkey_post_header_callback' );
add_action( 'shiftkey_post_content', 'shiftkey_wp_link_pages_callback' );
//add_action( 'shiftkey_post_content', 'shiftkey_post_link_callback' );

add_action( 'shiftkey_loop_content_before', 'shiftkey_blog_loop_content_before_callback', 10, 1 );
add_action( 'shiftkey_loop_content_after', 'shiftkey_loop_related_post_callback', 10, 1 );

//single post
add_action( 'shiftkey_post_single_content', 'shiftkey_post_format_callback' );
add_action( 'shiftkey_post_single_content', 'shiftkey_post_header_single_callback' );
add_action( 'shiftkey_post_single_content', 'shiftkey_wp_link_pages_callback' );

// Related post content
add_action( 'shiftkey_post_single_after', 'shiftkey_author_bio_callback', 10 );
add_action( 'shiftkey_post_single_after', 'shiftkey_post_comment_callback', 20 );
add_action( 'shiftkey_post_related_content', 'shiftkey_post_header_related_callback', 10 );


function shiftkey_author_bio_callback(){
	if ( ! is_singular( 'attachment' ) ) : 
		get_template_part( 'template-parts/post/author', 'bio' ); 
	endif;
}

function shiftkey_post_comment_callback(){
	get_template_part( 'template-parts/post/post-comment' );
}

function shiftkey_related_posts_callback(){
	if( is_singular('post') ){
	get_template_part( 'template-parts/post/related-posts' );
	}
}

function shiftkey_post_content_editor_callback(){	
	if( is_singular('post') ){
		get_template_part( 'template-parts/post/post', 'editor-content' );	
	}else{
		get_template_part( 'template-parts/post/post', 'excerpt' );
	}
}

function shiftkey_post_excerpt_callback(){
	get_template_part( 'template-parts/post/post', 'excerpt' );
}

function shiftkey_post_link_callback(){	
	get_template_part( 'template-parts/post/post', 'link' );	
}

function shiftkey_wp_link_pages_callback(){
	get_template_part( 'template-parts/post/wp', 'link-pages' );
}

function shiftkey_post_header_callback(){	
	get_template_part( 'template-parts/post/post', 'header' );
}

function shiftkey_post_header_single_callback(){	
	get_template_part( 'template-parts/post/post', 'header-single' );
}

function shiftkey_post_header_related_callback(){	
	get_template_part( 'template-parts/post/post', 'header-related' );
}

function shiftkey_blog_loop_content_before_callback($count){
	global $wp_query;
	$wp_query->shiftkey['counter'] = $wp_query->shiftkey['counter'] + 1;
	$sticky = get_option( 'sticky_posts' );

	if( !is_paged() && ($count == 0)  && !is_sticky() && !empty($sticky)){		
		get_template_part( 'template-parts/post/latest-posts', 'title' );		
	}	
}

function shiftkey_loop_related_post_callback($count){
	global $wp_query;
	$count = $wp_query->shiftkey['counter'];
	if( $count == 3 ){		
		get_template_part( 'template-parts/post/loop', 'related' );
	}	
}

function shiftkey_post_format_callback(){	
	global $post;

	$enable_video = get_post_meta( $post->ID, 'enable_video_popup', true );

	if( $enable_video ){
		get_template_part( 'template-parts/post/format', 'video' );
	}else{
		get_template_part( 'template-parts/post/format', 'image' );
	}
}

if( !function_exists( 'shiftkey_cta_template_part' ) ):
function shiftkey_cta_template_part(){	
		get_template_part( 'footer/cta' );	
}
endif;

if( !function_exists( 'shiftkey_newsletter_form_template_part' ) ):
function shiftkey_newsletter_form_template_part(){
	get_template_part( 'footer/newsletter' );
			
}
endif;

if( !function_exists( 'shiftkey_footer_copyright_template_part' ) ):
function shiftkey_footer_copyright_template_part(){
	get_template_part( 'footer/copyright' );
}
endif;

if( !function_exists( 'shiftkey_quick_contact_form_template_part' ) ):
function shiftkey_quick_contact_form_template_part(){
	get_template_part( 'footer/quick-form' );
}
endif;

if( !function_exists( 'shiftkey_footer_widget_area_template_part' ) ):
function shiftkey_footer_widget_area_template_part(){
	get_template_part( 'footer/widget-area' );
}
endif;

if( !function_exists( 'shiftkey_preloader_template_part' ) ):
function shiftkey_preloader_template_part(){
	get_template_part( 'header/preloader' );
}
endif;

if( !function_exists( 'shiftkey_navbar_template_part' ) ):
function shiftkey_navbar_template_part(){
	get_template_part( 'header/navbar', Shiftkey_Header_Config::get_navbar_style() );
}
endif;

if( !function_exists( 'shiftkey_banner_template_part' ) ):
function shiftkey_banner_template_part(){
	get_template_part( 'header/banner' );
}
endif;

if( !function_exists( 'shiftkey_banner_before_template_part' ) ):
function shiftkey_banner_before_template_part(){
	get_template_part( 'header/banner', 'before' );
}
endif;

if( !function_exists( 'shiftkey_breadcrumbs_template_part' ) ):
function shiftkey_breadcrumbs_template_part(){
	get_template_part( 'header/breadcrumbs' );
}
endif;

if( !function_exists( 'shiftkey_header_logo' ) ):
function shiftkey_header_logo(){
	get_template_part( 'header/logo', Shiftkey_Header_Config::get_logo_type() );
}
endif;

if( !function_exists( 'shiftkey_header_mobile_menu_icon' ) ):
function shiftkey_header_mobile_menu_icon(){
	get_template_part( 'header/mobilemenu', 'icon' );
}
endif;

if( !function_exists( 'shiftkey_header_nav_menu' ) ):
function shiftkey_header_nav_menu(){
	get_template_part( 'header/navigation' );
}
endif;

if( !function_exists( 'shiftkey_header_nav_menu_after' ) ):
function shiftkey_header_nav_menu_after(){
	if(Shiftkey_Header_Config::get_navbar_style() == 'style2'){
		get_template_part( 'header/navigation', 'after' );
	}	
}
endif;