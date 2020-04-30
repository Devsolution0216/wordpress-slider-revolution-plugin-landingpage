<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Brand
 */

?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if( ! brand_is_hidden( 'headline' ) && ! brand_use_sections() ) {
		 brand_title();
		}

			$brand_settings = wp_parse_args(
				get_option( 'brand_settings', array() ),
				brand_get_defaults()
			); ?>
    	<div class="entry-content">
        	<?php
				the_content();
				wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'brand' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
        <footer class="entry-footer entry-meta">
			  	<?php brand_entry_footer(); ?>
			</footer><!-- .entry-footer -->
    </article> <!-- #post-## -->
