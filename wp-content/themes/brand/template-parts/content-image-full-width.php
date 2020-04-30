<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Brand
 */

?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('no-padding'); ?>>
		<?php
		if( has_post_thumbnail() ) { ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'full' ); ?>
			</a>
				<?php
		} ?>
		<div class="brand-post-text-wrapper">
      <header class="entry-header">
			  <?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>	'); ?>
        	</header> <!-- .entry-header -->
					<div class="entry-meta">
						<?php brand_posted_on(); ?>
					</div><!-- .entry-meta -->
            <div class="entry-content">
            	<?php
				if ( brand_show_excerpt() === 'yes') {
					the_excerpt( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'brand' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );
				} else if( brand_show_excerpt() === 'no') {
					the_content( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'brand' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );
				}

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'brand' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
            <?php
			if ( 'post' === get_post_type() ) : ?>
				<footer class="entry-footer entry-meta">
					<?php brand_entry_footer(); ?>
				</footer><!-- .entry-footer -->
            <?php
			endif; ?>
			</div> <!-- .brand-post-text-wrapper -->
    </article> <!-- #post-## -->
