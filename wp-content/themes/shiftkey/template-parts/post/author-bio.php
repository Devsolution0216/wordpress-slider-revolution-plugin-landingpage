<?php
/**
 * The template for displaying Author info
 *
 * @since 1.0.0
 */

if ( (bool) get_the_author_meta( 'description' ) ) : ?>
<div class="author-senoff">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?>
	<div class="author-senoff-txt">
		<h5 class="h5-sm author-title">
			<span class="author-heading">
				<?php printf( __( 'Published by %s', 'shiftkey' ), esc_html( get_the_author() )); ?>
			</span>
		</h5>
		<p class="author-description">
			<?php the_author_meta( 'description' ); ?>
			<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php echo esc_attr__( 'View more posts', 'shiftkey' ); ?>
			</a>
		</p><!-- .author-description -->
	</div>
</div><!-- .author-bio -->
<?php endif; ?>
