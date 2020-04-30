<?php get_header(); ?>
	
		<?php do_action('shiftkey_content_before');	?>
		
		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'page' );

				wp_link_pages( array(					
					'nextpagelink'     => esc_attr__( 'Next', 'shiftkey'),
					'previouspagelink' => esc_attr__( 'Previous', 'shiftkey' ),
					'pagelink'         => '%',
					'echo'             => 1
				) );

			endwhile;

			// If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
			
		else :

			get_template_part( 'template-parts/post/content', 'none' );

		endif;
		?>
		
	<?php do_action('shiftkey_content_after');	?>
	 			

<?php get_footer(); ?>