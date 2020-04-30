<?php
/**
 * Generates navigation according to settings.
 *
 *
 * @package Brand
 * @since 1.0.0
 */

add_action( 'brand_before_header', 'brand_navigation_before_header', 5 );
add_action( 'brand_before_slider', 'brand_navigation_before_header', 5 );
function brand_navigation_before_header() {
	if( brand_is_hidden( 'navigation' ) ) {
     return '';
	}
	brand_navigation();
	brand_mobile_nav_bar();

}

if ( ! function_exists( 'brand_navigation' ) ) :

	function brand_navigation() {
		do_action('brand_before_navigation'); ?>
    	<div id="main-nav-wrapper" <?php brand_nav_wrapper_class() ?>>
				<div id="main-nav-container" class="container">
      		<?php
					$logos = brand_logo_urls();
					if( $logos['logo'] !== 0 ) {
						$logo_url = $logos['logo'];
						printf( // WPCS: XSS ok.
							'<a href="%1$s" title="%2$s" rel="home">
								<img id="logo" src="%3$s" alt="%2$s" title="%2$s" />
							</a>',
								esc_url( home_url( '/' ) ),
								esc_attr( get_bloginfo( 'name', 'display' ) ),
								esc_url( $logo_url )
						);

					} else {
						brand_site_title();
					}
					$brand_settings = wp_parse_args(
						get_option( 'brand_settings', array() ),
						brand_get_defaults()
					);

					if( 'yes' === $brand_settings['compact_menu'] ) {
						brand_compact_navigation();
					} else {
						wp_nav_menu( array(
							'theme_location'  => 'primary',
							'container'       => 'nav',
							'container_class' => 'main-nav',
							'fallback_cb'     => 'brand_primary_menu_fb',
							)
						);
					}
					?>
				</div>
    	</div><!-- main-nav-wrapper -->
    	<?php do_action('brand_after_navigation');
	}

endif;

if ( ! function_exists( 'brand_compact_navigation' ) ) :

	function brand_compact_navigation() {
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);
		$search_icon = 'enabled' === $brand_settings['nav_search'] ? '<a href="#" class="search-form-icon"><i class="fa fa-fw fa-search menu-search-form-icon"></i></a>' : '';
		?>
		<div class="compact-menu-icons">
			<?php echo $search_icon; // WPCS: XSS ok. ?>
			<a id="mobile-menu-button" class="c-hamburger c-hamburger--htx">
				<span>toggle menu</span>
			</a>
		</div>
		<?php
	}

endif;
