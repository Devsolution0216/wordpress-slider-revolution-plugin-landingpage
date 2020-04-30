<div id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?>  data-wow-delay="0.1s">
	
	<?php do_action( 'shiftkey_post_before' ); ?>	

	<?php do_action( 'shiftkey_post_content' ); ?>

	<?php do_action( 'shiftkey_post_after' ); ?>

</div>	<!-- END BLOG POST #post-<?php the_ID(); ?> -->

