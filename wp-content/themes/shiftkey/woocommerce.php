<?php get_header(); ?>	
	<?php do_action('shiftkey_content_before');	?>

		<?php
		if ( have_posts() ) :

			 woocommerce_content(); 

		endif;
		?>

	<?php do_action('shiftkey_content_after');	?>

	<?php do_action('shiftkey/woocommerce/footer'); ?>
	 			

<?php get_footer(); ?>