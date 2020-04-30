<?php if ( $posts->have_posts() ): extract($posts->info);  ?>
	<div class="woocommerce">
	<div class="product-slider">

		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>			

               <div class="slide-item text-center">
					<div class="loop-item">
					<?php

						global $post;
					 	$post_object = get_post( $post->ID );

						setup_postdata( $GLOBALS['post'] =& $post_object );
						wc_get_template_part( 'content', 'related-product' ); 
					?>
					</div>
				</div>	
		<?php endwhile; ?>		

		</div>
		</div>

	<?php if( $read_more != '' ): ?>	
	<div class="pagination-area">
	    <div class="load-more text-center">	  
	        <a href="<?php echo esc_url(get_permalink(get_option( 'woocommerce_shop_page_id' ))) ?>" data-paged="1" class="btn load-product"><?php echo esc_attr($read_more); ?></a>
	    </div>
	</div>
	<?php endif; ?>
</div><!-- .product-isotope-template -->
<?php
	// Posts not found
	else :
		echo '<h4>' . __( 'Product not found', 'shiftkey' ) . '</h4>';
	endif;
?>