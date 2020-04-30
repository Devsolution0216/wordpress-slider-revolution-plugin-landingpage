<?php
	$search_placeholder = shiftkey_get_option( 'search_placeholder', 'Search' );
?>
<div id="search-field" class="search-widget">
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>"  role="search" method="get" id="searchform" class="search-form">  
        <div class="input-group mb-3">
		  	<input class="form-control" placeholder="<?php echo sprintf(_x( '%s', 'Search placeholder text', 'shiftkey'), $search_placeholder) ?>" aria-label="Search" aria-describedby="search-field" type="text" name="s" value="<?php echo get_search_query(); ?>">
		 	<div class="input-group-append">
		    	<button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
		 	</div>
		</div>
    </form>
</div>

								
	
