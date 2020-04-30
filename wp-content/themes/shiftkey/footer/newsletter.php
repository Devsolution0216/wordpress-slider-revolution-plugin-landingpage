<?php
if( Shiftkey_Footer_Config::newsletter_area_is_on() ):
$newsletter_title = shiftkey_get_option( 'newsletter_title', esc_attr__('Subscribe to Shiftkey Update', 'shiftkey') );	
$newsletter_title = sprintf( _x('%s', 'Newsletter title', 'shiftkey'), esc_attr($newsletter_title) );
$newsletter_placeholder = shiftkey_get_option( 'newsletter_placeholder', esc_attr__('Your email address', 'shiftkey') );
$placeholder = sprintf( _x('%s', 'Newsletter placeholder', 'shiftkey'), esc_attr($newsletter_placeholder) );
$sub = esc_attr__('Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero at tempus, blandit posuere ligula varius congue porta feugiat', 'shiftkey');
$newsletter_subtitle = shiftkey_get_option( 'newsletter_subtitle', $sub );
$newsletter_subtitle = sprintf( _x('%s', 'Newsletter subtitle', 'shiftkey'), $newsletter_subtitle );
$newsletter_footer = shiftkey_get_option( 'newsletter_footer', '' );
?>
<section id="<?php shiftkey_newsletter_id(); ?>" <?php shiftkey_newsletter_class('footer-newsletter white-color'); ?>>
	<div class="container">
		
		<div class="row">	
			<div class="col-lg-10 offset-lg-1 section-title">
				<h3 class="h3-lg"><?php echo shiftkey_parse_text(esc_attr($newsletter_title)); ?></h3><!-- Title 	-->
				<p class="p-md"><?php echo shiftkey_parse_text(esc_attr($newsletter_subtitle)); ?></p><!-- Text -->
			</div>
		</div>	 <!-- END SECTION TITLE -->	
		
		<div class="row">
			<div class="offset-lg-2 col-lg-8">								
				<?php 
					if(class_exists('PerchNewsletter')){
						$args = array();
						$group = 'shiftkey';						

						$args['email'] = esc_attr($placeholder);								
						$args['button_style'] = '';						
						$args['es_group'] = esc_attr($group);
						$args['enable_name'] = false;
						$args['es_desc'] = '';
						$args['es_pre'] = '';
						$args['name'] = '';
						$args['el_class'] = 'newsletter-form newsletter-form-simple';
						$args['button_text'] = esc_attr__('Sign Up', 'shiftkey');
						$args['form_button_style'] = 'text_button';
						$args['button_extra_class'] = 'btn btn-primary-color tra-hover';

						$args['inline_form'] = 'yes';
						echo PerchNewsletter::es_get_form_simple($args);
					}else{
						echo esc_attr__('Please Install Theme Required & Recommended PLugins.', 'shiftkey');
					}
					?>
				<?php if( $newsletter_footer != '' ): ?>
				<p class="p-sm mt-10"><?php echo do_shortcode($newsletter_footer); ?></p><!-- Small Text -->
				<?php endif; ?>
			</div>
		</div>	  <!-- END NEWSLETTER FORM -->

	</div>	   <!-- End container -->	
	<div class="parallax-inner"></div>
</section>
<?php 
endif; ?>
