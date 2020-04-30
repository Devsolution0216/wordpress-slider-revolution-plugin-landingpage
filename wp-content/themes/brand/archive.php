<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Brand
 */

get_header(); ?>

    <div id="primary" class="container content-area">
    	<main id="main" class="row site-main" role="main">
     		<div id="primary-content">
				  <?php brand_title(); ?>

					<div class="brand-grid">
			<?php
				if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
				 	* Include the Post-Format-specific template for the content.
				 	* If you want to override this in a child theme, then include a file
				 	* called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 	*/
					if( 'classic' === brand_posts_listing_style() ) {
					get_template_part( 'template-parts/content', get_post_format() );
				} else {
					get_template_part( 'template-parts/content-image-full-width', get_post_format() );
				}

			endwhile; ?>
				</div> <!-- .brand-grid --> <?php

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
        </div> <!-- #primary-content -->

        <div id="secondary-content">
       			<?php brand_get_sidebar(); ?>
	   		</div>	<!-- #secondary-content -->
		</main><!-- #main -->
	</div><!-- #primary -->

	<!-- Pagination -->
	<?php
	if( ! brand_is_jet_module_active( 'infinite-scroll' ) ) :
		the_posts_navigation( array( 'prev_text' => __( 'previous', 'brand' ), 'next_text' => __( 'next', 'brand' ) ) );
	endif;

get_footer();
