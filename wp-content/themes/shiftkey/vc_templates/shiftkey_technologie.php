<?php
$args = shiftkey_technologie_shortcode_vc(true);
$args['content'] = $content;
$atts = shortcode_atts( $args, $atts);
extract($atts);
$params = '';
if( $display == 'counter' ){
	$params = $counter_group;
}elseif( $display == 'techs' ){
	$params = $techs_group;
}

$listArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($strategy_list) : array();

$paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($params) : array();
$animation_class = 'fadeInUp';
if( $display == 'list' ){
	$order = ($position == 'yes')? 'col-md-6 col-lg-5 offset-lg-1' : 'col-md-6 col-lg-5';
	$container_class = ($position == 'yes')? 'col-md-6 col-lg-5' : 'col-md-6 col-lg-6 offset-lg-1 order-md-last order-lg-last';
	$video_class = ( $video_popup == 'yes' )? 'video-preview' : '';
}
$about_class = ( $position == 'yes' )? '' : ' ind-45';
?>

<div class="content-section division">
	<div class="row d-flex align-items-center">

	 	<div class="<?php echo esc_attr($content_order) ?>">
	 		<div class="content-txt">
				<?php if( $display == 'techs' ): ?>					
				<div class="tools-list mt-25" data-wow-delay="0.6s">						
					<p><?php echo esc_attr($tech_title); ?></p>
					<?php if( !empty($paramsArr) ): ?>						
						<?php foreach ($paramsArr as $key => $value): ?>
							<?php if( isset($value['icon']) ): ?>							
							<span class="html-ico">
								<?php if( isset($value['image']) && ($value['image'] != '') ): ?>
								<img src="<?php echo esc_url($value['image']) ?>" alt="<?php echo esc_attr($value['title']) ?>">
								<?php else: ?>
								<i class="<?php echo esc_attr($value['icon']) ?>"></i>
								<?php endif; ?>
							</span>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>	
	 		</div>
	 	</div><!-- END ABOUT TEXT -->
	 	
		<div class="<?php echo esc_attr($img_order) ?>">	
			<?php if( $image != '' ): ?>			
	 		<div class="<?php echo esc_attr($video_class); ?> content-4-img wow <?php echo esc_attr($animation_class) ?>"  data-wow-delay="0.4s">
				<?php else: ?>
					<picture>
						<?php do_action( 'shiftkey_responsive_images', 'image', $atts ); ?>													
						<img class="img-fluid" src="<?php echo esc_url($image) ?>" alt="<?php echo esc_attr($subtitle) ?>">
					</picture>	
				<?php endif; ?>
	 		</div>
	 		<?php endif; ?>
		</div>
		
	</div>	   <!-- End row -->			
</div><!-- END ABOUT-3 -->	
