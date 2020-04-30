<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Brand
 */

get_header('single');

	do_action( 'brand_before_singular_content' ); ?>

	<div id="primary" class="container content-area">
		<main id="main" class="row site-main" role="main">
			<div id="primary-content">
			<?php
			while ( have_posts() ) : the_post();

				do_action( 'brand_inside_singular_content_above' );

				get_template_part( 'template-parts/content-single', get_post_format() );

			endwhile; // End of the loop.
			?>
       		</div>
       		<div id="secondary-content">
       			<?php brand_get_sidebar(); ?>
	   		</div>
        </main><!-- #main -->
	</div><!-- #primary -->

	<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) : ?>
		<div id="comments-wrapper">
			<?php comments_template(); ?>
		</div> <?php
	endif; ?>

	<div id="pagination">
		<div id="btn-older" class="desktop"><?php previous_post_link( '%link', '%title' ); ?></div>
		<div id="btn-newer" class="desktop"><?php next_post_link( '%link', '%title' ); ?></div>
		<div id="btn-older" class="mobile"><?php previous_post_link( '%link', '&larr;' ); ?></div>
		<div id="btn-newer" class="mobile"><?php next_post_link( '%link', '&rarr;' ); ?></div>
	</div> <!-- End Pagination -->



<?php

get_footer();
