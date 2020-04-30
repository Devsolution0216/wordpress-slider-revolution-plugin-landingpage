jQuery(document).ready(function( $ ) {

	//initialize swiper when document ready
  var mySwiper = new Swiper ('.swiper-container', {
		// Optional parameters
	 init: false,
	 direction: 'horizontal',
	 parallax:true,
	 preloadImages: false,
		 lazy: {
			 loadPrevNext: true,
	 },
	 effect: 'slide',
	 cubeEffect:{
  	slideShadows: false,
  	shadow: false
	 },
	 autoplay: {
		delay: parseInt( swiper_settings.delay ),
	 },
	 loop: true,
	 speed: parseInt( swiper_settings.speed ),

	 // Mousewheel Control
	 mousewheel: {
		 forceToAxis: true,
 	 },

	 keyboardControl:true,

	 // If we need pagination
	 pagination: {
	     el: '.swiper-pagination',
	     type: 'fraction',
	 },

	 // Navigation arrows
	 navigation: {
    nextEl: '.brand-swiper-button-next',
    prevEl: '.brand-swiper-button-prev',
   },

	 on: {
		 transitionEnd: function (mySwiper) {
 			$( '.swiper-slide.swiper-slide-active' ).addClass('animation' );
 		},

		 transitionStart: function (mySwiper) {
 	 		$( '.swiper-slide' ).removeClass('animation' );
	 		var text_color = $( '.swiper-slide.swiper-slide-active .inner-slide' ).attr( 'data-text-color' );
	 		$( '.swiper-pagination-fraction, .swiper-buttons-holder' ).css( 'color', text_color );
	 		$( '.brand-swiper-button-prev svg, .brand-swiper-button-next svg' ).css( 'fill', text_color );
  	}
	}

 })

 if( swiper_settings.lazy_loading == false ) {
	 mySwiper.params.preloadImages = true;
	 mySwiper.params.lazy.enabled = false;
 }
 if( swiper_settings.autoplay == false ) {
	 mySwiper.params.autoplay.enabled = false;
 }
 mySwiper.init();
 console.log(mySwiper.params.autoplay);

});
