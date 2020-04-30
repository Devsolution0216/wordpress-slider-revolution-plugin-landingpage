<?php if( Shiftkey_Header_Config::breadcrumbs_display_is_on() && !is_front_page() && function_exists('bcn_display_list')) : ?>
<div id="breadcrumb">
	<div class="row">						
		<div class="col">
			<div class="breadcrumb-nav">
				<nav aria-label="breadcrumb">
				  	<ol class="breadcrumb">
				    	<?php if(function_exists('bcn_display_list')){ bcn_display_list(); } ?>
				  	</ol>
				</nav>
			</div>
		</div>
	</div> 
</div>

<?php endif; ?>