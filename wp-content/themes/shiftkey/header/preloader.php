<?php 
$preloader_display = shiftkey_get_option( 'preloader_display', 'default' );
if( $preloader_display != 'none' ):
	$custom_preloader = shiftkey_get_option( 'custom_preloader', array( 'url' => SHIFTKEY_URI . '/images/preloader.png') );
?>

<!-- PRELOADER SPINNER -->
<div id="loader-wrapper">
	<?php if( $preloader_display == 'default' ): ?>
	<div id="loader">
		<span class="cssload-loader">
			<span class="cssload-loader-inner"></span>
		</span>
	</div>
	<?php endif; ?>

	<?php if( $preloader_display == 'custom' ): ?>
		<div class="preloader-img text-center"><img src="<?php echo esc_url($custom_preloader['url']) ?>" alt="<?php echo esc_attr(apply_filters('shiftkey_vc_image_upload_alt', '')); ?>"></div>
	<?php endif; ?>
		
</div>
<?php endif; ?>