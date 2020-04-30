<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Brand
 */

get_header(); ?>

	<div id="primary" class="container content-area">
		<main id="main" class="row site-main" role="main">
			<div id="primary-content">
				<?php
				if( ! brand_is_hidden( 'headline' ) && ! brand_use_sections() ) {
				 brand_title();
				}  ?>
				<section class="error-404 not-found">
					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'brand' ); ?></p>

						<?php
							get_search_form();
						?>

					</div><!-- .page-content -->
				</section><!-- .error-404 -->
        	</div> <!-- #primary-content -->

        	<div id="secondary-content">
       			<?php brand_get_sidebar(); ?>
	   		</div>	<!-- #secondary-content -->
				<div class="clearfix"></div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
