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

		$content = apply_filters( 'the_content', get_the_content() );
		$video = false;

		// Only get video from the content if a playlist isn't present.
		if ( false === strpos( $content, 'wp-playlist-script' ) ) {
			$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
		}

		if ( ! empty( $video ) ) :
			foreach ( $video as $video_html ) {
				echo '<div class="entry-video">';
				echo $video_html; // WPCS: XSS ok.
				echo '</div>';
			}
		endif;

		if( has_post_thumbnail() && empty( $video ) ) { ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'post-full' ); ?>
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
					the_excerpt( sprintf(
						/* translators: %s: Name of current post. */
						wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'brand' ), array( 'span' => array( 'class' => array() ) ) ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					) );

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
