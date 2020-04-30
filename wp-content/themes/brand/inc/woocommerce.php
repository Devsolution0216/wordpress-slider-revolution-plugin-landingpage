<?php
if( function_exists("is_woocommerce") ) {

	// Enqueues styles and scripts for Woocommerce.
	function brand_enqueue_woocommerce_scripts() {
		wp_enqueue_style('brand-woocommerce', get_template_directory_uri() . '/assets/css/brand-woocommerce.css', array(), BRAND_VER );
		wp_enqueue_style( 'dashicons' );
	}
	add_action( 'wp_enqueue_scripts', 'brand_enqueue_woocommerce_scripts' );

	//Unhooks woocommerce breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

	//Hooks woocommerce breadcrumbs after title
	add_action( 'brand_after_woocommerce_title', 'woocommerce_breadcrumb', 10);

	//Unhooks woocommerce content wrapper
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
  remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// Sets start wrapper for woocommerce content
	function brand_wrapper_start() { ?>
    <div id="primary" class="container content-area">
    	<main id="main" class="row site-main" role="main">
    		<div id="primary-content">
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <?php
	 }

	// Sets end wrapper for woocommerce content
	function brand_wrapper_end() { ?>
        </article>
      </div> <!-- #primary-content -->

      <div id="secondary-content">
        <?php get_sidebar(); ?>
      </div>	<!-- #secondary-content -->
    </main><!-- #main -->
  </div><!-- #primary --> <?php
	}

	// Hooks brand content wrapper
	add_action('woocommerce_before_main_content', 'brand_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'brand_wrapper_end', 10);

	// Declares woocommerce support
	function woocommerce_support() {
    	add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
	}

	add_action( 'after_setup_theme', 'woocommerce_support' );

	/**
	 * Changes number of products per row.
	 *
	 * @since Brand 1.6.3
	 *
	 * @return number Number of columns.
	 */
	 if (!function_exists('brand_loop_columns')) {
	 	function brand_loop_columns() {

			$brand_settings = wp_parse_args(
				get_option( 'brand_settings', array() ),
				brand_get_defaults()
			);

			return $brand_settings['woocommerce_products_cols']; // products per row
	 	}
	 }
	 add_filter('loop_shop_columns', 'brand_loop_columns');

	 /**
		* Change number of related products on product page
	  * Set your own value for 'posts_per_page'
		*
		* @since Brand 1.8.9
		*
		* @return number Number of columns.
	 */
	 add_filter( 'woocommerce_output_related_products_args', 'brand_related_products_args' );
	   function brand_related_products_args( $args ) {
			 $brand_settings = wp_parse_args(
 				get_option( 'brand_settings', array() ),
 				brand_get_defaults()
 			);

	 	$args['columns'] = absint( $brand_settings['woocommerce_products_cols'] );
	 	return $args;
	 }

 }
