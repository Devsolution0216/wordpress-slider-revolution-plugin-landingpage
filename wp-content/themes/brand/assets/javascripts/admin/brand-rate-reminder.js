jQuery(function($){

	$(".delete-rate-reminder").click(function(el){
		el.preventDefault();
		var data = {
			_ajax_nonce: brand_rate_reminder.nonce,
			action: 'brand_update_rate_reminder',
			notice: brand_rate_reminder.notice,
			update: 'brand_delete_rate_reminder',
		};

		$.post(brand_rate_reminder.ajaxurl, data, function(res) {
			if( ! res.error) {
				$('.' + brand_rate_reminder.notice).remove();
			} else {
				console.log('There was an error on deleting reminder');
			}
		}).fail(function(xhr, textStatus, e) {
			console.log(xhr.responseText);
		});
	});

	$(".ask-later").click(function(el){
		el.preventDefault();
		var data = {
			_ajax_nonce: brand_rate_reminder.nonce,
			action: 'brand_update_rate_reminder',
			notice: brand_rate_reminder.notice,
			update: 'brand_ask_later',
		};

		$.post(brand_rate_reminder.ajaxurl, data, function(res) {
			if( ! res.error) {
				$('.' + brand_rate_reminder.notice).remove();
			} else {
				console.log('There was an error on deleting reminder ' + res.error_type);
			}
		}).fail(function(xhr, textStatus, e) {
			console.log(xhr.responseText);
		});
	});

});
