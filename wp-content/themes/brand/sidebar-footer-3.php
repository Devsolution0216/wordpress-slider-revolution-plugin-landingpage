<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Brand
 */

if ( ! is_active_sidebar( 'sidebar-footer-3' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area sidebar-footer-3" role="complementary">
	<?php dynamic_sidebar( 'sidebar-footer-3' ); ?>
</aside><!-- #secondary -->
