/* GENERATE RETINA IMAGES ACTION */

var current;
var maxPhpSize = wr2x_admin_server.maxFileSize;
var ids = [];
var errors = 0;
var ajax_action = "generate"; // generate | delete

function wr2x_display_please_refresh() {
	wr2x_refresh_progress_status();
	jQuery('#wr2x_progression').html(jQuery('#wr2x_progression').html() + " - " + wr2x_admin_server.i18n.Refresh);
}

function wr2x_refresh_progress_status() {
	var errortext = "";
	if ( errors > 0 ) {
		errortext = ' - ' + errors + ' error(s)';
	}
	jQuery('#wr2x_progression').text(current + "/" + ids.length +
		" (" + Math.round(current / ids.length * 100) + "%)" + errortext);
}

function wr2x_do_next () {
	var data = { action: 'wr2x_' + ajax_action, attachmentId: ids[current - 1] };
	data.nonce = wr2x_admin_server.nonce[data.action];

	wr2x_refresh_progress_status();
	jQuery.post(ajaxurl, data, function (response) {
		try {
			reply = jQuery.parseJSON(response);
		}
		catch (e) {
			reply = null;
		}
		if ( !reply || !reply.success )
			errors++;
		else {
			wr2x_refresh_media_sizes(reply.results);
			if (reply.results_full)
				wr2x_refresh_full(reply.results_full);
		}
		if (++current <= ids.length)
			wr2x_do_next();
		else {
			current--;
			wr2x_display_please_refresh();
		}
	}).fail(function () {
		errors++;
		if (++current <= ids.length)
			wr2x_do_next();
		else {
			current--;
			wr2x_display_please_refresh();
		}
	});
}

function wr2x_do_all () {
	current = 1;
	ids = [];
	errors = 0;
	var data = { action: 'wr2x_list_all', issuesOnly: 0 };
	data.nonce = wr2x_admin_server.nonce[data.action];

	jQuery('#wr2x_progression').text(wr2x_admin_server.i18n.Wait);
	jQuery.post(ajaxurl, data, function (response) {
		reply = jQuery.parseJSON(response);
		if (reply.success = false) {
			alert('Error: ' + reply.message);
			return;
		}
		if (reply.total == 0) {
			jQuery('#wr2x_progression').html(wr2x_admin_server.i18n.Nothing_to_do);
			return;
		}
		ids = reply.ids;
		jQuery('#wr2x_progression').text(current + "/" + ids.length + " (" + Math.round(current / ids.length * 100) + "%)");
		wr2x_do_next();
	});
}

function wr2x_delete_all () {
	ajax_action = 'delete';
	wr2x_do_all();
}

function wr2x_generate_all () {
	ajax_action = 'generate';
	wr2x_do_all();
}

// Refresh the dashboard retina full with the results from the Ajax operation (Upload)
function wr2x_refresh_full (results) {
	jQuery.each(results, function (id, html) {
		jQuery('#wr2x-info-full-' + id).html(html);
		jQuery('#wr2x-info-full-' + id + ' img').attr('src', jQuery('#wr2x-info-full-' + id + ' img').attr('src')+'?'+ Math.random());
		jQuery('#wr2x-info-full-' + id + ' img').on('click', function (evt) {
			wr2x_delete_full( jQuery(evt.target).parents('.wr2x-file-row').attr('postid') );
		});
	});
}

// Refresh the dashboard media sizes with the results from the Ajax operation (Replace or Generate)
function wr2x_refresh_media_sizes (results) {
	jQuery.each(results, function (id, html) {
		jQuery('#wr2x-info-' + id).html(html);
	});
}

function wr2x_generate (attachmentId, retinaDashboard) {
	var data = { action: 'wr2x_generate', attachmentId: attachmentId };
	data.nonce = wr2x_admin_server.nonce[data.action];

	jQuery('#wr2x_generate_button_' + attachmentId).text(wr2x_admin_server.i18n.Wait);
	jQuery.post(ajaxurl, data, function (response) {
		var reply = jQuery.parseJSON(response);
		if (!reply.success) {
			alert(reply.message);
			return;
		}
		jQuery('#wr2x_generate_button_' + attachmentId).html(wr2x_admin_server.i18n.Generate);
		wr2x_refresh_media_sizes(reply.results);
	});
}

/* REPLACE FUNCTION */

function wr2x_stop_propagation(evt) {
	evt.stopPropagation();
	evt.preventDefault();
}

function wr2x_delete_full(attachmentId) {
	var data = {
		action: 'wr2x_delete_full',
		isAjax: true,
		attachmentId: attachmentId
	};
	data.nonce = wr2x_admin_server.nonce[data.action];

	jQuery.post(ajaxurl, data, function (response) {
		var data = jQuery.parseJSON(response);
		if (data.success === false) {
			alert(data.message);
		}
		else {
			wr2x_refresh_full(data.results);
			wr2x_display_please_refresh();
		}
	});
}

function wr2x_load_details(attachmentId) {
	var data = {
		action: 'wr2x_retina_details',
		isAjax: true,
		attachmentId: attachmentId
	};
	data.nonce = wr2x_admin_server.nonce[data.action];

	jQuery.post(ajaxurl, data, function (response) {
		var data = jQuery.parseJSON(response);
		if (data.success === false) {
			alert(data.message);
		}
		else {
			jQuery('#meow-modal-info .loading').css('display', 'none');
			jQuery('#meow-modal-info .content').html(data.result);
		}
	});
}

function wr2x_filedropped (evt) {
	wr2x_stop_propagation(evt);
	var files = evt.dataTransfer.files;
	var count = files.length;
	if (count < 0) {
		return;
	}

	var wr2x_replace = jQuery(evt.target).parent().hasClass('wr2x-fullsize-replace');
	var wr2x_upload = jQuery(evt.target).parent().hasClass('wr2x-fullsize-retina-upload');

	function wr2x_handleprogress(prg) {
		console.debug("Upload of " + prg.srcElement.filename + ": " + prg.loaded / prg.total * 100 + "%");
	}

	function wr2x_uploadFile(file, attachmentId, filename) {
		var action = "";
		if (wr2x_replace) {
			action = 'wr2x_replace';
		}
		else if (wr2x_upload) {
			action = 'wr2x_upload';
		}
		else {
			alert("Unknown command. Contact the developer.");
		}
		var data = new FormData();
	data.append('file', file);
		data.append('action', action);
		data.append('attachmentId', attachmentId);
		data.append('isAjax', true);
		data.append('filename', filename);
		data.append('nonce', wr2x_admin_server.nonce[action]);

		// var data = {
		// 	action: action,
		// 	isAjax: true,
		// 	filename: evt.target.filename,
		// 	data: form_data,
		// 	attachmentId: attachmentId
		// };

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			contentType: false,
			processData: false,
			data: data,
			success: function (response) {
				jQuery('[postid=' + attachmentId + '] td').removeClass('wr2x-loading-file');
				jQuery('[postid=' + attachmentId + '] .wr2x-dragdrop').removeClass('wr2x-hover-drop');
				try {
					var data = jQuery.parseJSON(response);
				}
				catch (e) {
					alert("The server-side returned an abnormal response. Check your PHP error logs and also your browser console (WP Retina 2x will try to display it there).");
					console.debug(response);
					return;
				}
				if (wr2x_replace) {
					var imgSelector = '[postid=' + attachmentId + '] .wr2x-info-thumbnail img';
					jQuery(imgSelector).attr('src', jQuery(imgSelector).attr('src')+'?'+ Math.random());
				}
				if (wr2x_upload) {
					var imgSelector = '[postid=' + attachmentId + '] .wr2x-info-full img';
					jQuery(imgSelector).attr('src', jQuery(imgSelector).attr('src')+'?'+ Math.random());
				}
				if (data.success === false) {
					alert(data.message);
				}
				else {
					if ( wr2x_replace ) {
						wr2x_refresh_media_sizes(data.results);
					}
					else if ( wr2x_upload ) {
						wr2x_refresh_full(data.results);
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				jQuery('[postid=' + attachmentId + '] td').removeClass('wr2x-loading-file');
				jQuery('[postid=' + attachmentId + '] .wr2x-dragdrop').removeClass('wr2x-hover-drop');
				alert("An error occurred on the server-side. Please check your PHP error logs.");
		  }
		});
	}
	var file = files[0];
	if (file.size > maxPhpSize) {
		jQuery(this).removeClass('wr2x-hover-drop');
		alert( "Your PHP configuration only allows file upload of a maximum of " + (maxPhpSize / 1000000) + "MB." );
		return;
	}
	var postId = jQuery(evt.target).parents('.wr2x-file-row').attr('postid');
	jQuery(evt.target).parents('td').addClass('wr2x-loading-file');
	wr2x_uploadFile(file, postId, file.name);
}

jQuery(document).ready(function () {
	jQuery('.wr2x-dragdrop').on('dragenter', function (evt) {
		wr2x_stop_propagation(evt);
		jQuery(this).addClass('wr2x-hover-drop');
	});

	jQuery('.wr2x-dragdrop').on('dragover', function (evt) {
		wr2x_stop_propagation(evt);
		jQuery(this).addClass('wr2x-hover-drop');
	});

	jQuery('.wr2x-dragdrop').on('dragleave', function (evt) {
		wr2x_stop_propagation(evt);
		jQuery(this).removeClass('wr2x-hover-drop');
	});

	jQuery('.wr2x-dragdrop').on('dragexit', wr2x_stop_propagation);

	jQuery('.wr2x-dragdrop').each(function (index, elem) {
		this.addEventListener('drop', wr2x_filedropped);
	});

	jQuery('.wr2x-info, .wr2x-button-view').on('click', function (evt) {
		jQuery('#meow-modal-info-backdrop').css('display', 'block');
		jQuery('#meow-modal-info .content').html("");
		jQuery('#meow-modal-info .loading').css('display', 'block');
		jQuery('#meow-modal-info').css('display', 'block');
		jQuery('#meow-modal-info').focus();
		var postid = jQuery(evt.target).parents('.wr2x-info').attr('postid');
		if (!postid)
			postid = jQuery(evt.target).parents('.wr2x-file-row').attr('postid');
		wr2x_load_details(postid);
	});

	jQuery('#meow-modal-info .close, #meow-modal-info-backdrop').on('click', function (evt) {
		jQuery('#meow-modal-info').css('display', 'none');
		jQuery('#meow-modal-info-backdrop').css('display', 'none');
	});

	jQuery('.wr2x-info-full img').on('click', function (evt) {
		wr2x_delete_full( jQuery(evt.target).parents('.wr2x-file-row').attr('postid') );
	});

	jQuery('#meow-modal-info').bind('keydown', function (evt) {
		if (evt.keyCode === 27) {
			jQuery('#meow-modal-info').css('display', 'none');
			jQuery('#meow-modal-info-backdrop').css('display', 'none');
		}
	});

	/**
	 * Retina Uploader
	 */
	(function ($) {
		/**
		 * @constructor
		 */
		function Upload(File) {
			if (!this.validate(File)) return; // Invalid file
			this.file = File;
			this.loaded = 0;
			this.total = 0;
			this.doms = {
				wrap: null,
				filename: null,
				progress: null,
				percent: null,
				bar: null
			};
			this.request();
		}
		Upload.prototype.getProgress = function (Mul = 1) {
			if (!this.total) return 0;
			var r = (this.loaded / this.total) * Mul;
			return Math.round(r * 10) / 10;
		}
		Upload.prototype.validate = function (File) {
			var err;
			if (!'type' in File || !File.type)
				err = 'Unknown File Type';
			else if (!File.type.match(/^image\//)) // Not image
				err = 'Unsupported File Type';

			if (err) {
				console.error(err);
				alert(err);
				return false;
			}
			return true;
		}
		Upload.prototype.request = function () {
			var self = this;
			var action = 'wr2x_retina_upload';
			var data = new FormData();
			data.append('action', action);
			data.append('isAjax', true);
			data.append('nonce', wr2x_admin_server.nonce[action]);
			data.append('file', this.file);
			data.append('filename', this.file.name);

			this.show();

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				contentType: false,
				processData: false,
				data: data,
				// Custom XHR
				xhr: function () {
					var xhr = new XMLHttpRequest();
					// Watch upload progress
					xhr.upload.addEventListener('progress', function (ev) {
						if (!ev.lengthComputable) return xhr;
						self.loaded = ev.loaded;
						self.total = ev.total;
						self.update();
					}, false);
					return xhr;
				}

			}).done(function (response) {
				try {
					response = $.parseJSON(response);
				} catch (e) { // Malformed Response
					self.abort();
					console.error(e);
					alert('Invalid Response');
					return;
				}
				if (!response.success) { // App Error
					self.abort();
					var msg = 'message' in response ?
						response.message : 'Error';
					console.error(msg);
					alert(msg);
					return;
				}
				// Remove the progress indicator
				self.doms.progress.remove();

				// Edit Link
				$('<a class="edit-attachment">')
					.attr('href', response.media.edit_url)
					.attr('target', '_blank')
					.text('Edit')
					.prependTo(self.doms.wrap);

				// Show the thumbnail
				$('<img class="pinkynail">')
					.attr('src', response.media.src[0])
					.prependTo(self.doms.wrap);

				// Just mocking the built-in behavior
				self.doms.filename
					.removeClass('original')
					.addClass('new');

			}).fail(function (e) { // HTTP Error
				self.abort();
				var msg = e.status + ' ' + e.statusText;
				console.error(msg);
				alert(msg + '\n' + 'An error occurred on the server-side. Please check your PHP error logs.');
			});
		}
		Upload.prototype.show = function () {
			// Ideal HTML:
			// <div class="media-item child-of-0" id="media-item">
			//   <div class="progress">
			//     <div class="percent">100%</div>
			//     <div class="bar" style="width: 200px;"></div>
			//   </div>
			//   <div class="filename original">image.jpg</div>
			// </div>
			this.doms.wrap = $('<div class="media-item wr2x-retina-uploaded">');
			this.doms.filename = $('<div class="filename original">')
				.text(this.file.name)
				.appendTo(this.doms.wrap);

			this.doms.wrap.appendTo('#media-items'); // First Appearance
		}
		Upload.prototype.update = function () {
			if (!this.doms.progress) { // Initialize the progress bar
				this.doms.progress = $('<div class="progress">').prependTo(this.doms.wrap);
				this.doms.percent = $('<div class="percent">').appendTo(this.doms.progress);
				this.doms.bar = $('<div class="bar">').appendTo(this.doms.progress);
			}
			this.doms.percent.text(this.getProgress(100) + '%');
			this.doms.bar.css('width', this.getProgress(200) + 'px');
		}
		Upload.prototype.abort = function () {
			this.doms.wrap.remove();
		}

		/** Initialize DOMs **/

		// Drag & Drop Area
		var dnd = $('#wr2x_drag-drop-area')
		dnd.on('dragenter dragover', function (ev) {
			wr2x_stop_propagation(ev);
			$(this).addClass('wr2x-hover-drop');

		}).on('dragleave dragexit', function (ev) {
			wr2x_stop_propagation(ev);
			$(this).removeClass('wr2x-hover-drop');

		}).on('drop', function (ev) {
			wr2x_stop_propagation(ev);
			$(this).removeClass('wr2x-hover-drop');
			var _ev = ev.originalEvent;
			var files = _ev.dataTransfer.files;
			for (var i = 0; i < files.length; i++) new Upload(files[i]);
		});

		// File Selector
		var selector = $('#wr2x_file-selector');
		selector.on('change', function (ev) {
			var files = ev.target.files;
			for (var i = 0; i < files.length; i++) new Upload(files[i]);
		});
		var btn = $('#wr2x_file-select-button');
		btn.on('click', function (ev) {
			selector.trigger('click');
		});

	})(jQuery);
});
