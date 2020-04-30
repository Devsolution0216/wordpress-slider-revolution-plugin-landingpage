<?php do_action( 'shiftkey_post_single_before' ); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php do_action( 'shiftkey_post_before' ); ?>
	

	<div class="single-post-title mb-35 text-center">	
		<div class="post-title-meta">
			<span><?php echo shiftkey_get_the_term_list( get_the_ID(), 'category', '', ', ', '', false ) ?></span>
		</div>					
		<h3 class="h3-md"><?php the_title(); ?></h3><!-- Post Title -->				
		<div class="single-post-data clearfix">				
			<p class="post-meta">
				<?php echo shiftkey_post_author(); ?>
				<?php echo shiftkey_post_date(false); ?> 								
			</p>				
		</div>	<!-- End Post Author -->
	</div>

	<?php
	$enable_lead_text = get_post_meta( get_the_ID(), 'enable_lead_text', true );
	$enable_lead_text = ( $enable_lead_text == '' )? false : $enable_lead_text;
	if( $enable_lead_text ):
		$post_lead_text = get_post_meta( get_the_ID(), 'post_lead_text', true );
		echo '<div class="single-post-txt mb-45">'.do_shortcode($post_lead_text).'</div>';
	endif;
	?>


	<?php 
	$enable_featured_image = get_post_meta( get_the_ID(), 'enable_featured_image', true );
	$enable_featured_image = ( $enable_featured_image == '' )? false : $enable_featured_image;
	if( $enable_featured_image && has_post_thumbnail() ):
	$classes = array('blog-post-img mb-40');
	$image_size = 'post-thumbnail';		
	$classes = array_filter($classes);
	$enable_video_popup = get_post_meta( get_the_ID(), 'enable_video_popup', true );
	$video_link = get_post_meta( get_the_ID(), 'video_link', true );
	?>			
	<div class="<?php echo esc_attr( implode(' ', $classes) ) ?>">
		<?php 
		if( $enable_video_popup && $video_link != '' ): ?>
			<div class="video-preview text-center">
				<a class="video-popup1" href="<?php echo esc_url($video_link) ?>">
				<div class="video-btn play-icon-tra"><div class="video-block-wrapper"><i class="fas fa-play"></i></div></div>
				<?php 			
				endif; 	
				the_post_thumbnail( esc_attr($image_size), array('class' => 'img-fluid') ); 
				if( $enable_video_popup && $video_link != '' ): ?>	
				</a>
			</div>
		<?php endif; ?>	
	</div><!-- BLOG POST IMAGE -->
	<?php endif; ?>

	
	<div class="single-post-txt mb-45">
		<div class="entry-content"><?php the_content(); ?></div>

	<?php get_template_part( 'template-parts/post/wp', 'link-pages' ); ?>

	<?php
	$single_post_share = shiftkey_get_option( 'single_post_share', 'off' );
	if( (is_singular('post') && ($single_post_share == 'on')) || shiftkey_post_tag()):	
		?>

		<div class="post-share-links">

			<?php if(shiftkey_post_tag()): ?>							
			<div class="post-tags-list"><?php echo shiftkey_post_tag('<span>', '', '</span>'); ?></div><!-- POST TAGS -->
			<?php endif; ?>		
						
			<?php 
			$args = array(
				'wrapclass' => 'share-social-icons clearfix',
				'before' => '<div class="post-share-list">',
				'after' => '</div><!-- POST SHARE ICONS -->',
			);
			shiftkey_social_share(true, $args); 
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'shiftkey_post_after' ); ?>
	</div>

</div>	<!-- END BLOG POST #post-<?php the_ID(); ?> -->

<?php do_action( 'shiftkey_post_single_after' ); ?>

