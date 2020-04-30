<?php
/**
 * The template for displaying comments
 */
if ( post_password_required() ) {
	return;
}
?>


<div id="comments" class="comments-and-replay single-post-comments single-<?php echo esc_attr( get_post_type() ) ?>-comments mt-80 mb-0">
	<?php if ( have_comments() ) : ?>		
		<h5 class="h5-lg mb-50">
			<?php
			$comments_number = get_comments_number();
			if ( '0' === $comments_number ) {
				
			}elseif ('1' === $comments_number) {
				echo ' <span>'.$comments_number.'</span> '. esc_attr__('Comment', 'shiftkey' );
			} else {
				echo ' <span>'.$comments_number.'</span> '. esc_attr__('Comments', 'shiftkey' );
			}
			?>
		</h5>
	<?php endif; // have_comments() ?>
	
		<?php if ( have_comments() ) : ?>
			<?php shiftkey_comment_nav(); ?>		
			<ul class="media-list comment-list">
				<?php
					wp_list_comments( array(
						'style'       => 'ul',
						'short_ping'  => true,
						'avatar_size' => '60',
						'callback' => 'shiftkey_comment_callback',
						'max_depth' => 3,
					) );
				?>
			</ul><!-- .comment-list -->

			<?php shiftkey_comment_nav(); ?>

			<?php
				// If comments are closed and there are comments, let's leave a little note, shall we?
				if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
			?>
				<p class="no-comments"><?php esc_attr_e( 'Comments are closed.', 'shiftkey' ); ?></p>
			<?php endif; ?>			
		<?php endif; // have_comments() ?>

		<?php shiftkey_comment_form(); ?>	
	
</div><!-- .comments-area -->