<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Brand
 */

?>

    </div> <!-- #content --> <?php
		if( ! brand_is_hidden( 'footer' ) || ! brand_is_hidden( 'footer_widgets' ) ) { ?>
			<footer id="footer" <?php brand_footer_class() ?>>
    	<?php brand_get_footer_sidebars();
			if( ! brand_is_hidden( 'footer' ) ) { ?>
    		<div class="site-info">
      		<div class="container">
          	<?php do_action( 'brand_site_info' ); ?>
      		</div>
    		</div> <!-- .site-info -->
			</footer> <!-- #footer --> <?php
			}
		}
  wp_footer(); ?>

  </div> <!-- #wrapper -->

  </body>
</html>
