<?php if( shiftkey_get_page_template_type() == '' ): ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="page-content"><?php the_content(); ?></div>
	</div>
<?php else: ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
	<?php the_content(); ?>
	</div>
<?php endif; ?>	