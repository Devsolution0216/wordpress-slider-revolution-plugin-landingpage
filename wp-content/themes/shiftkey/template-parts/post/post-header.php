<div class="blog-post-txt">
	<?php if ( 'post' === get_post_type() ): ?>
	<p class="post-meta">
		<?php shiftkey_post_author( false ); ?> 
		<?php shiftkey_post_date(); ?>			
	</p>
	<?php shiftkey_sticky_post_text(); ?>
	<?php endif; ?>
	<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3><!-- Post Title -->
	
	<?php the_excerpt(); ?><!-- Post Text -->	
	
</div><!-- BLOG POST TEXT -->
