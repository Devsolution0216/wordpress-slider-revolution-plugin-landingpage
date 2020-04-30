<?php 
if( has_post_thumbnail() ):
$video_link = get_post_meta( get_the_ID(), 'video_link', true );
?>		
<div class="<?php shiftkey_post_format_class('blog-post-img blog-post-vd play-icon-theme'); ?>">
	<?php if( $video_link != '' ): ?>
	<div class="video-preview text-center">
		<a class="video-popup1" href="<?php echo esc_url($video_link) ?>">
		<div class="video-btn play-icon-theme">	
			<div class="video-block-wrapper"><i class="fas fa-play"></i></div>
		</div>
	<?php endif; ?>	

	<?php the_post_thumbnail( 'shiftkey-800x400-crop', array('class' => 'img-fluid') ) ?>	

	<?php if( $video_link != '' ): ?>	
		</a>
	</div>
	<?php endif; ?>	
	<div class="blog-post-avatar">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?> 
	</div>
</div><!-- BLOG POST IMAGE -->
<?php endif; ?>
