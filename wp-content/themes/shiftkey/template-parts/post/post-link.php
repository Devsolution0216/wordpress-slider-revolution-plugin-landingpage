<?php
$read_more_text = shiftkey_get_option( 'read_more_text', 'More Details' );
$read_more_text = sprintf( _x('%s', 'Read more text', 'shiftkey'), $read_more_text );
?>
<div class="blog-post-link">
	<h5 class="h5-xs"><a href="<?php the_permalink(); ?>"><?php echo esc_attr($read_more_text); ?></a></h5>	
	<div class="footer-meta"><?php shiftkey_footer_entry_meta(); ?></div>
</div><!-- Post Link -->
