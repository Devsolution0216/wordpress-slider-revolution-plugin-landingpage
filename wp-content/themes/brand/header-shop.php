<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Brand
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="top-searchform">  <a href="#" id="close-search"> </a> <?php get_search_form(); ?> </div>
<div id="mobile-menu-wrapper">
	<a id="mobile-menu-close-button" href="#"></a>
	<div class="mobile-menu-inner">
    <?php
		wp_nav_menu( array(
			'theme_location'   => 'primary',
			'container'        => 'nav',
			'container_class' => '',
			//'depth'           => 2
			)
		);

		get_sidebar( 'mobile' );
	?>
	</div> <!-- .mobile-menu-inner -->
</div> <!-- #mobile-menu-wrapper -->

<?php do_action('brand_before_wrapper'); ?>
<div id="wrapper">
    <?php do_action('brand_before_header');
	if( ! brand_no_header() ) { ?>
	<div id="header-wrapper" <?php brand_header_wrapper_class() ?>>

    <?php do_action('brand_before_inside_header');
		if( is_front_page() && function_exists( 'the_custom_header_markup' ) ) {
			the_custom_header_markup();
		}
    do_action('brand_after_inside_header'); ?>
	</div> <!-- #header-wrapper --> <?php
	}
  do_action('brand_after_header');

 if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
 	<header class="page-header main-title">
		<div class="main-title-inner container">
			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
			<?php do_action( 'brand_after_woocommerce_title' ); ?>
		</div> <!-- .main-title-inner -->
	</header> <!-- .page-header -->

 <?php endif; ?>

 <div id="content" <?php brand_content_class( 'site-content' ) ?> >
