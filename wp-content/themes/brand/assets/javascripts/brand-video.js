( function( $ ) {

  function brandVideoRatio() {
    /*var videoWidth = $('.entry-video iframe').outerWidth();
    var videoHeight = videoWidth * 9 / 16;
    var videoSingleWidth = $('.single-format-video iframe').outerWidth();
    var videoSingleHeight = videoSingleWidth * 9 / 16;
    $('.entry-video iframe, .entry-video').css( 'height', videoHeight + 'px');
    $('.single-format-video iframe').css( 'height', videoSingleHeight + 'px'); */
		var videoWidth = $('article.format-video').width();
    var videoHeight = videoWidth * 9 / 16;
    $('.entry-video iframe, .entry-video, .single-format-video iframe, .single-format-video .wp-video').css( 'height', videoHeight + 'px');
		$('.entry-video iframe, .entry-video, .single-format-video iframe, .single-format-video .wp-video').css( 'width', videoWidth  + 'px');
  }

  brandVideoRatio();
  window.addEventListener( "resize", brandVideoRatio );

} )( jQuery );
