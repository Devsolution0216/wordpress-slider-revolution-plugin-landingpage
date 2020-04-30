<?php 
$quickform_shortcode = shiftkey_get_option( 'quickform_shortcode', '' );
if( Shiftkey_Footer_Config::quickform_area_is_on() && ( $quickform_shortcode != '' ) ):
	$quickform_title = shiftkey_get_option( 'quickform_title', esc_attr__('Quick Contact Form', 'shiftkey') );
	$quickform_title = sprintf( _x('%s', 'Contact form title', 'shiftkey'), $quickform_title );	
	$title_avatar = shiftkey_get_option( 'title_avatar', array('url' => SHIFTKEY_URI.'/images/assistant-avatar.jpg') );
?>
<div id="sticky-form">
	<div class="nb-form quick-form-holder">

	<div class="quick-form-header">	
		<p class="nb-form-title"><?php echo esc_attr($quickform_title) ?></p>
		<?php if( !empty($title_avatar) ): ?>
		<img class="assistant-avatar" src="<?php echo esc_url($title_avatar['url']) ?>" alt="<?php echo esc_attr($quickform_title) ?>">
		<?php endif; ?>	
	</div>            		
    	


    	<!-- QUICK FORM -->
        <div class="bottom-form-holder">		
			<div class="quick-form">																			
				<?php echo do_shortcode($quickform_shortcode); ?>
			</div>						
		</div>

	</div>
</div>	  <!-- END BOTTOM QUICK FORM -->
<?php endif; ?>