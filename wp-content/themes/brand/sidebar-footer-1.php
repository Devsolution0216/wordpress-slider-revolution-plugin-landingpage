<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Brand
 */

if ( ! is_active_sidebar( 'sidebar-footer-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area sidebar-footer-1" role="complementary">
	<?php dynamic_sidebar( 'sidebar-footer-1' ); ?>
</aside><!-- #secondary -->
