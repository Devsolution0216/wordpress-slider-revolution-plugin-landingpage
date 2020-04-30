<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Brand
 */

if ( ! is_active_sidebar( 'sidebar-footer-2' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area sidebar-footer-2" role="complementary">
	<?php dynamic_sidebar( 'sidebar-footer-2' ); ?>
</aside><!-- #secondary -->
