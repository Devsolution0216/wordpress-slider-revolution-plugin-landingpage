<?php
/**
 * Brand metaboxes.
 *
 * Add metaboxes to load in page, post and custom post.
 *
 * @package Brand
 * @since 1.0.0
 */
require_once  get_template_directory() . '/inc/metaboxes/brand-sidebar-metabox.php';
add_action( 'load-post-new.php', array('Brand_Sidebar_Metabox', 'get_instance') );
add_action( 'load-post.php', array('Brand_Sidebar_Metabox', 'get_instance') );

require_once  get_template_directory() . '/inc/metaboxes/brand-hide-elements-metabox.php';
add_action( 'load-post-new.php', array('Brand_Hide_Elements_Metabox', 'get_instance') );
add_action( 'load-post.php', array('Brand_Hide_Elements_Metabox', 'get_instance') );