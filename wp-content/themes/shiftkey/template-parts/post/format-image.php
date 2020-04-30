<?php if( has_post_thumbnail() ): ?>			
<div class="<?php shiftkey_post_format_class('blog-post-img'); ?>">
	<?php
	$categories_list = get_the_category_list( '', '', get_the_ID() );
	if( $categories_list ):
	?>
	<div class="post-category">
		<p><?php shiftkey_post_category();?></p>
	</div>
	<?php endif; ?>
	<?php the_post_thumbnail( 'shiftkey-800x400-crop', array('class' => 'img-fluid') ) ?>
	<?php if ( 'post' === get_post_type() ): ?>
	<div class="blog-post-avatar">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?> 
	</div>
	<?php endif; ?>
</div><!-- BLOG POST IMAGE -->
<?php endif; ?>