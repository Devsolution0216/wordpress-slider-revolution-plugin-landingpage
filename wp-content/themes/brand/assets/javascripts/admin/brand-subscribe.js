jQuery(function($){

	$("#brand-subscribe-btn").click(function(el){
		el.preventDefault();
		var data = {
			_ajax_nonce: brand_subscribe.nonce,
			action: 'brand_subscribe',
			brand_subscribe_email: $( "input[name='brand_subscribe_email']" ).val(),
		};

		$.post(brand_subscribe.ajaxurl, data, function(res) {
			$('.response-msg span').remove();
			if( ! res.error) {
				$('.response-msg').append('<span>' + res.msg + '</span>');
			} else {
				$('.response-msg').append('<span>' + res.msg + '</span>');
			}
		}).fail(function(xhr, textStatus, e) {
			console.log(xhr.responseText);
		});
	});

});
