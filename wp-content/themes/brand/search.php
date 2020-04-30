<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Brand
 */

get_header(); ?>

	<section id="primary" class=" container content-area">
		<main id="main" class="row site-main" role="main">
			<div id="primary-content">
			<?php
			if( ! brand_is_hidden( 'headline' ) && ! brand_use_sections() ) {
			 brand_title();
			}

			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/**
				 	* Run the loop for the search to output the results.
				 	* If you want to overload this in a child theme then include a file
				 	* called content-search.php and that will be used instead.
				 	*/
					get_template_part( 'template-parts/content', 'search' );

				endwhile;

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>

        </div> <!-- #primary-content -->

        <div id="secondary-content">
       			<?php brand_get_sidebar(); ?>
	   		</div>	<!-- #secondary-content -->
		</main><!-- #main -->
	</section><!-- #primary -->

	<!-- Pagination -->
	<?php
		if( ! brand_is_jet_module_active( 'infinite-scroll' ) ) :
			the_posts_navigation( array( 'prev_text' => __( 'previous', 'brand' ), 'next_text' => __( 'next', 'brand' ) ) );
		endif;

get_footer();
