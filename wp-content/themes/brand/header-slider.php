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
			)
		);

		get_sidebar( 'mobile' );
	?>
	</div> <!-- .mobile-menu-inner -->
</div> <!-- #mobile-menu-wrapper -->

<?php do_action('brand_before_wrapper'); ?>
<div id="wrapper">
		<?php do_action('brand_before_slider');
if( ! brand_no_header() ) { ?>
	<div id="header-wrapper" <?php brand_header_wrapper_class( 'swiper-container' ) ?>>
		<div class="swiper-wrapper">
		<?php do_action('brand_before_inside_slider');
		if( is_front_page() ) {
			$brand_settings = wp_parse_args(
		    get_option( 'brand_settings', array() ),
		    brand_get_defaults()
		  );
			$slides_number = $brand_settings['slides_number'];
			for( $i = 1; $i <= $slides_number; $i++ ) {
				if( ! empty( $brand_settings['brand_slide_image' . $i] ) ) { ?>
					<div class="swiper-slide">
						<?php if( false !== $brand_settings['lazy_loading_slider'] )  {?>
						<div id="<?php echo 'brand-slide-' . $i ?>" data-background="<?php echo esc_url( $brand_settings['brand_slide_image' . $i] ); ?>" class="inner-slide swiper-lazy" data-text-color="<?php echo esc_attr( $brand_settings['brand_slide_text_color' . $i] ); ?>" style="">
							<div class="swiper-lazy-preloader"></div>
						<?php } else { ?>
							<div id="<?php echo 'brand-slide-' . $i ?>" class="inner-slide" data-text-color="<?php echo esc_attr( $brand_settings['brand_slide_text_color' . $i] ); ?>" style="">
						<?php } ?>
							<div class="slide-content container">
								<h1> <?php echo esc_html( $brand_settings['brand_slide_title' . $i] ) ?> </h1>
								<h2> <?php echo esc_html( $brand_settings['brand_slide_subtitle' . $i] ) ?> </h2> <?php
								if( ! empty( $brand_settings['brand_slide_button_url' . $i] ) ) { ?>
									<a href="<?php echo esc_url( $brand_settings['brand_slide_button_url' . $i] ) ?>" class="button"><?php echo esc_html( $brand_settings['brand_slide_button_text' . $i] ) ?> </a> <?php
								} ?>
							</div>
						</div>
					</div> <?php
				}
			}
		}
    do_action('brand_after_inside_slider'); ?>
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-buttons-holder">
		<div class="brand-swiper-button-prev">
			<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="3.9632mm" width="10.412mm" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 36.894531 14.04297">
				<g transform="matrix(-1 0 0 -1 299.11 247.96)">
					<path d="m291.04 233.92q2.5977 2.832 4.4922 4.3164 1.8945 1.4648 3.5742 2.1875v0.8789q-1.9336 0.9375-3.75 2.4024-1.8164 1.4453-4.3359 4.2578h-1.5039q1.8359-3.9258 3.8477-6.0352h-31.152v-1.9726h31.152q-1.4844-1.8945-2.0703-2.8125-0.58594-0.91797-1.7383-3.2227h1.4844z"/>
				</g>
			</svg>
		</div>
  	<div class="brand-swiper-button-next">
			<svg xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="3.9632mm" width="10.412mm" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" viewBox="0 0 36.894531 14.04297">
				<g transform="translate(-262.21 -233.92)">
					<path d="m291.04 233.92q2.5977 2.832 4.4922 4.3164 1.8945 1.4648 3.5742 2.1875v0.8789q-1.9336 0.9375-3.75 2.4024-1.8164 1.4453-4.3359 4.2578h-1.5039q1.8359-3.9258 3.8477-6.0352h-31.152v-1.9726h31.152q-1.4844-1.8945-2.0703-2.8125-0.58594-0.91797-1.7383-3.2227h1.4844z"/>
				</g>
			</svg>
		</div>
	</div>
</div> <!-- #header-wrapper --> <?php
}
do_action('brand_after_slider');
?>

 <div id="content" <?php brand_content_class( 'site-content' ) ?> >
