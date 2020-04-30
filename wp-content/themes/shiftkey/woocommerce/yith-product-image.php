<?php
defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;


$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );
?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out; height: 100%">
	<figure class="h-100 d-inline-blockwoocommerce-product-gallery__wrapper">
		<?php
		if ( has_post_thumbnail() ) {
			$srcArr  = wp_get_attachment_image_src($post_thumbnail_id, 'full');
			$src = $srcArr[0];
		} else {
			$src = esc_url( wc_placeholder_img_src() );	
		}

		echo '<div class="quickview-image" style="background-image: url('.esc_url($src).')"></div>';		

		?>
	</figure>
</div>