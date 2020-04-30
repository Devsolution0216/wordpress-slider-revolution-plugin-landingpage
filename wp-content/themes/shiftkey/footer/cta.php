<?php
if( Shiftkey_Footer_Config::cta_area_is_on() && !is_404() ):

$cta_title = shiftkey_get_option( 'cta_title', esc_attr__('Stay Updated With Our News', 'shiftkey') );	
$cta_title = sprintf( _x('%s', 'Footer CTA title', 'shiftkey'), $cta_title );
$sub = esc_attr__('Donec vel sapien augue integer urna vel turpis cursus porta, mauris sed augue luctus dolor velna auctor congue tempus magna integer', 'shiftkey');
$cta_subtitle = shiftkey_get_option( 'cta_subtitle', $sub );
$cta_subtitle = sprintf( _x('%s', 'Footer CTA subtitle', 'shiftkey'), $cta_subtitle );
$button_text = shiftkey_get_option( 'cta_button_text', esc_attr__('Let\'s Started', 'shiftkey') );
$button_text = sprintf( _x('%s', 'Footer CTA button text', 'shiftkey'), $button_text );
$button_link = shiftkey_get_option( 'cta_button_link', '#' );
?>
<section id="cta-1" class="blue-textured cta-section division parallax">
	<div class="container white-color">
		<div class="row d-flex align-items-center">
			<div class="col-lg-8">
				<div class="cta-txt">					
					<h4 class="h4-lg"><?php echo shiftkey_parse_text(esc_attr($cta_title)); ?></h4><!-- Title -->						
					<p class="p-md"><?php echo shiftkey_parse_text(esc_attr($cta_subtitle)); ?></p><!-- Text -->
				</div>
			</div><!-- CALL TO ACTION TEXT -->
			
			<div class="col-lg-3 offset-lg-1">
				<div class="cta-btn text-right">
					<a href="<?php echo esc_url($button_link) ?>" class="btn btn-md btn-primary tra-hover"><?php echo esc_attr($button_text) ?></a>
				</div>
			</div><!-- CALL TO ACTION BUTTON -->	


		</div>	 <!-- End row -->
	</div>	   <!-- End container -->	
	<div class="parallax-inner"></div>
</section>
<?php endif; ?>