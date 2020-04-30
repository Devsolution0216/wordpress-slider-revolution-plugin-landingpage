<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
  * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="search-field" class="search-widget">
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'shiftkey' ); ?></label>
		<div class="input-group mb-3">
			<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'shiftkey' ); ?>" value="<?php echo get_search_query(); ?>" name="s" class="form-control" />
			<div class="input-group-append">
		    	<button class="btn btn-lightgreen1" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
		 	</div>
		 </div>
		<input type="hidden" name="post_type" value="product" />
	</form>
</div>