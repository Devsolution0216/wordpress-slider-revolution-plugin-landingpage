<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_singular( 'product' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="posts-section division pb-30">	
		<div class="container">	
		<?php		
		$columns =  (count($related_products) == 2)? 2 : $columns;
		$related_title = shiftkey_get_option( 'related_product_title', 'Keep Shopping: Related Products' );		
		if( $related_title != '' ):
		?>
		<div class="row">	
			<div class="col-lg-10 offset-lg-1 section-title">	
				<h3 class="h3-sm"><?php printf( _x( '%s','Related Products title', 'shiftkey' ), esc_attr($related_title)) ?></h3>									
			</div>
		</div>
		<?php endif; ?>
		

			<div class="row">			
			<?php foreach ( $related_products as $related_product ) : ?>
				<div class="col-xs-12 col-md-<?php echo intval(12/$columns); ?> mb-50 text-center product-item shiftkey-has-gallery">
					<div class="loop-item">
					<?php
					 	$post_object = get_post( $related_product->get_id() );
						setup_postdata( $GLOBALS['post'] =& $post_object );
						wc_get_template_part( 'content', 'related-product' ); 
					?>
					</div>
				</div>	
			<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif;
wp_reset_postdata();