<?php
/**
 * The sidebar containing the mobile widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Brand
 */

if ( ! is_active_sidebar( 'sidebar-mobile' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area sidebar-mobile" role="complementary">
	<?php dynamic_sidebar( 'sidebar-mobile' ); ?>
</aside><!-- #secondary -->
