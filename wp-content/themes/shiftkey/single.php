<?php get_header(); ?>

	
		<?php get_template_part( 'template-parts/content', 'before' );	?>	

			<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/post/content-single', get_post_format() );
	

			endwhile;
			
		else :

			get_template_part( 'template-parts/post/content', 'none' );

		endif;
		?>

	<?php get_template_part( 'template-parts/content', 'after' );	?>

		 			

<?php get_footer(); ?>