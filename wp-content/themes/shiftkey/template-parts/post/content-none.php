<div class="error">
	<div class="row">
		<div class="col-md-12">
			<?php
			$title = shiftkey_get_option( '404_title', '404');
			?>
			<h3 class="h3-xs"><?php 
			$subtitle = shiftkey_get_option( '404_subtitle', '{Sorry}, but nothing matched your search terms.');
			echo shiftkey_parse_color_text(esc_attr($subtitle)); 
			?></h3>
			<?php 
			$content = shiftkey_get_option('404_content');
			if( !empty($content) ){
				echo wpautop($content);
			}else{				
				echo '<p>'.sprintf(__('Please try again with some different keywords. Use the search field below to find something else or go back to %1s to start from scratch.', 'shiftkey'), '<a href="'.get_home_url().'" class="primary-color">Homepage</a>').'</p>';
			 } ?>

			 <div class="error-search mt-50">
				<?php echo get_search_form() ?>
			</div>
		</div>
	</div><!-- .row -->
</div>

