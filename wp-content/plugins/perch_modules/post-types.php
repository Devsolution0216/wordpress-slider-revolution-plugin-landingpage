<?php
if ( ! function_exists('perch_portfolio_post_type') ) {
// Register Custom Post Type
function perch_portfolio_post_type() {
	$title = _x( 'Portfolios', 'Post Type General Name', 'perch' );
	$title = apply_filters( 'perch_portfolio_post_type_title', $title );

	$labels = array(
		'name'                  => esc_attr($title),
		'singular_name'         => _x( 'Portfolio', 'Post Type Singular Name', 'perch' ),
		'menu_name'             => __( 'Portfolio', 'perch' ),
		'name_admin_bar'        => __( 'Portfolio', 'perch' ),
		'archives'              => __( 'Portfolio Archives', 'perch' ),
		'parent_item_colon'     => __( 'Parent Portfolio:', 'perch' ),
		'all_items'             => __( 'All Portfolio Items', 'perch' ),
		'add_new_item'          => __( 'Add New Portfolio', 'perch' ),
		'add_new'               => __( 'Add New', 'perch' ),
		'new_item'              => __( 'New Portfolio', 'perch' ),
		'edit_item'             => __( 'Edit Portfolio', 'perch' ),
		'update_item'           => __( 'Update Portfolio', 'perch' ),
		'view_item'             => __( 'View Portfolio', 'perch' ),
		'search_items'          => __( 'Search Portfolio', 'perch' ),
		'not_found'             => __( 'Not found', 'perch' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'perch' ),
		'featured_image'        => __( 'Featured Image', 'perch' ),
		'set_featured_image'    => __( 'Set featured image', 'perch' ),
		'remove_featured_image' => __( 'Remove featured image', 'perch' ),
		'use_featured_image'    => __( 'Use as featured image', 'perch' ),
		'insert_into_item'      => __( 'Insert into Portfolio', 'perch' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Portfolio', 'perch' ),
		'items_list'            => __( 'Portfolio list', 'perch' ),
		'items_list_navigation' => __( 'Portfolio list navigation', 'perch' ),
		'filter_items_list'     => __( 'Filter Portfolio list', 'perch' ),
	);

	$args = array(
		'label'                 => __( 'Portfolio', 'perch' ),
		'description'           => __( 'Post Type Description', 'perch' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'portfolio_category', 'tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',	
	);

	$slug = apply_filters( 'perch_portfolio_post_type_slug', 'portfolio' );
	$args['rewrite'] = array('slug' => $slug,'with_front' => false);

	if( !in_array('portfolio', perch_disable_post_type_arr()) ){
		register_post_type( 'portfolio', $args );
	}

}
add_action( 'init', 'perch_portfolio_post_type', 0 );
}


if ( ! function_exists( 'perch_portfolio_category_taxonomy' ) ) {
// Register Custom Taxonomy
function perch_portfolio_category_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'perch' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'perch' ),
		'menu_name'                  => __( 'Category', 'perch' ),
		'all_items'                  => __( 'All Categories', 'perch' ),
		'parent_item'                => __( 'Parent Category', 'perch' ),
		'parent_item_colon'          => __( 'Parent Category:', 'perch' ),
		'new_item_name'              => __( 'New Category Name', 'perch' ),
		'add_new_item'               => __( 'Add New Category', 'perch' ),
		'edit_item'                  => __( 'Edit Category', 'perch' ),
		'update_item'                => __( 'Update Category', 'perch' ),
		'view_item'                  => __( 'View Category', 'perch' ),
		'separate_items_with_commas' => __( 'Separate Categories with commas', 'perch' ),
		'add_or_remove_items'        => __( 'Add or remove Categories', 'perch' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'perch' ),
		'popular_items'              => __( 'Popular Categories', 'perch' ),
		'search_items'               => __( 'Search Items', 'perch' ),
		'not_found'                  => __( 'Not Found', 'perch' ),
		'no_terms'                   => __( 'No Categories', 'perch' ),
		'items_list'                 => __( 'Categories list', 'perch' ),
		'items_list_navigation'      => __( 'Categories list navigation', 'perch' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'           => array( 'slug' => apply_filters('perch_portfolio_category_slug', 'portfolio_category') ),
	);

	register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );

}
add_action( 'init', 'perch_portfolio_category_taxonomy', 0 );
}



if ( ! function_exists('perch_team_post_type') ) {
// Register Custom Post Type
function perch_team_post_type() {

	$title = _x( 'Team', 'Post Type General Name', 'perch' );
	$title = apply_filters( 'perch_team_post_type_title', $title );

	$labels = array(
		'name'                  => esc_attr($title),
		'singular_name'         => _x( 'Team', 'Post Type Singular Name', 'perch' ),
		'menu_name'             => __( 'Team', 'perch' ),
		'name_admin_bar'        => __( 'Team', 'perch' ),
		'archives'              => __( 'Team Archives', 'perch' ),
		'parent_item_colon'     => __( 'Parent Team:', 'perch' ),
		'all_items'             => __( 'All Team', 'perch' ),
		'add_new_item'          => __( 'Add New Team', 'perch' ),
		'add_new'               => __( 'Add New', 'perch' ),
		'new_item'              => __( 'New Team', 'perch' ),
		'edit_item'             => __( 'Edit Team', 'perch' ),
		'update_item'           => __( 'Update Team', 'perch' ),
		'view_item'             => __( 'View Team', 'perch' ),
		'search_items'          => __( 'Search Team', 'perch' ),
		'not_found'             => __( 'Not found', 'perch' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'perch' ),
		'featured_image'        => __( 'Featured Image', 'perch' ),
		'set_featured_image'    => __( 'Set featured image', 'perch' ),
		'remove_featured_image' => __( 'Remove featured image', 'perch' ),
		'use_featured_image'    => __( 'Use as featured image', 'perch' ),
		'insert_into_item'      => __( 'Insert into Team', 'perch' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Team', 'perch' ),
		'items_list'            => __( 'Team list', 'perch' ),
		'items_list_navigation' => __( 'Team list navigation', 'perch' ),
		'filter_items_list'     => __( 'Filter Team list', 'perch' ),
	);

	$args = array(
		'label'                 => __( 'Team', 'perch' ),
		'description'           => __( 'Post Type Description', 'perch' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);

	$slug = apply_filters( 'perch_team_post_type_slug', 'team' );
	$args['rewrite'] = array('slug' => $slug,'with_front' => false);

	if( !in_array('team', perch_disable_post_type_arr()) ){
		register_post_type( 'team', $args );
	}

}
add_action( 'init', 'perch_team_post_type', 0 );
}