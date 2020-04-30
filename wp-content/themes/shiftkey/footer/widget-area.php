<?php if ( Shiftkey_Footer_Config::widget_area_is_on() && !Shiftkey_Footer_Config::footer_widget_area_is_empty() && !is_404() ): ?>
<div class="row wide-40">
	<?php
	$classArr = Shiftkey_Footer_Config::footer_widget_area_get_column_sizes();	
	$classArr = apply_filters( 'shiftkey_footer_widget_area_column_classes', $classArr );

    $total = shiftkey_get_option( 'footer_widget_area_column', '4' );
    for( $i=1; $i<=$total; $i++ ):
        $class = ($total == 4)? $classArr[$i-1] : 'col-md-'.(12/$total);       
        $sidebar = 'footer-'.$i;
        if ( is_active_sidebar( $sidebar ) ) :
        	 $class .= ' ';
			?>
			<div class="<?php echo esc_attr($class) ?>">
	            <?php  dynamic_sidebar( $sidebar ); ?>
	        </div>
			<?php 
		else:
			echo '<div class="'.esc_attr($class).'"></div>';	
		endif;
	endfor;
	?>
</div>
<?php endif; ?>