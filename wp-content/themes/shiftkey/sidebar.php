<?php
$layout = shiftkey_get_layout();
if( $layout != 'full' ):
  $sidebar = shiftkey_get_sidebar();
?>
<aside id="<?php shiftkey_sidebar_id(); ?>" <?php shiftkey_sidebar_class(); ?>>
		<?php do_action( 'shiftkey_sidebar_before' ); ?>

    <?php 
      if ( is_active_sidebar( $sidebar ) ) : ?> 
          <?php dynamic_sidebar( $sidebar ); ?>     
              <?php 
      else:
        $sidebar_class = shiftkey_sidebar_common_class(); 
        $args = 'before_widget=<div class="'.esc_attr(implode(' ', $sidebar_class)).' widget_categories">&after_widget=</div>&before_title=<h5 class="h5-sm widget-title">&after_title=</h5>'; 
        the_widget( 'WP_Widget_Archives', '', $args ); 
        the_widget( 'WP_Widget_Pages', '', $args ); 
      endif; ?>

      <?php do_action( 'shiftkey_sidebar_after' ); ?>	
</aside>	<!-- END SIDEBAR  -->
<?php endif; ?>