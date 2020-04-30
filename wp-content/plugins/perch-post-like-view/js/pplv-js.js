(function( $ ) {
	'use strict';
	$(document).on('click', '.perch-button', function() {
		var button = $(this);
		var post_id = button.attr('data-post-id');
		var security = button.attr('data-nonce');
		var iscomment = button.attr('data-iscomment');
		var allbuttons;
		if ( iscomment === '1' ) { /* Comments can have same id */
			allbuttons = $('.perch-comment-button-'+post_id);
		} else {
			allbuttons = $('.perch-button-'+post_id);
		}
		var icon_old_class = button.find('i').attr('class');
		var icon_loader_class = 'fa fa-spinner';
		var icon_like_class = 'fa fa-heart';
		var icon_unlike_class = 'fa fa-heart-o';
		

		var loader = allbuttons.next('#perch-loader');
		if (post_id !== '') {
			$.ajax({
				type: 'POST',
				url: pplv.ajaxurl,
				data : {
					action : 'process_simple_like',
					post_id : post_id,
					nonce : security,
					is_comment : iscomment,
				},
				beforeSend:function(){
					button.find('i').attr('class', icon_loader_class);
				},	
				success: function(response){
					var icon = response.icon;
					var count = response.count;
					allbuttons.html(icon+count);

					button.find('i').attr('class', icon_old_class);

					if(response.status === 'unliked') {
						var like_text = pplv.like;
						allbuttons.prop('title', like_text);
						allbuttons.removeClass('liked');
						button.find('i').attr('class', icon_unlike_class);
					} else {
						var unlike_text = pplv.unlike;
						allbuttons.prop('title', unlike_text);
						allbuttons.addClass('liked');
						button.find('i').attr('class', icon_like_class);
					}

										
				}
			});
			
		}
		return false;
	});
})( jQuery );
