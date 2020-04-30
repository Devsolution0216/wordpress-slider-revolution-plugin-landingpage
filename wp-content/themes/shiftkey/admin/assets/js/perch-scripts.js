// JavaScript Document
jQuery(document).ready(function($) {
		
	"use strict";

	$( '.perch-vc-carousel' ).each(function(){

		var carousel = $(this);
		var type = carousel.data( 'carousel_type' );
		var column_lg = parseInt(carousel.data( 'column_lg' ));
		var column_md = parseInt(carousel.data( 'column_md' ));
		var column_sm = parseInt(carousel.data( 'column_sm' ));
		var column_xs = parseInt(carousel.data( 'column_xs' ));
		var autoplay = Boolean(carousel.data( 'autoplay' ));
		var center = Boolean(carousel.data( 'center' ));
		var dots = Boolean(carousel.data( 'dots' ));
		var navs = Boolean(carousel.data( 'navs' ));
		var loop = Boolean(carousel.data( 'loop' ));
		var margin = parseInt(carousel.data( 'margin' ));


		if( carousel.hasClass( 'perch-vc-carousel-owl' )  && ( type == 'owl' ) ){
			var owl_options = {
					items: column_lg,
					loop: loop,
					autoplay:autoplay,
					rtl: $('body').hasClass('rtl')? true : false,
					dots: dots,
					nav : navs,
					dotsEach: true,
					navText: ["<",">"],
					margin: margin,
					center: center,
					animateOut: 'fadeOut',
					animateIn: 'fadeIn',
					autoplayTimeout: 4500,
					autoplayHoverPause: true,
					smartSpeed: 1500,
					responsive:{
						0:{
							items: column_xs
						},
						600:{
							items: column_xs
						},
						768:{
							items: column_sm
						},
						991:{
							items: column_md
						},
						1000:{
							items: column_lg
						}
					}
			};
			carousel.owlCarousel(owl_options);
		}

		if( carousel.hasClass( 'perch-vc-carousel-slick' ) && ( type == 'slick' ) ){
			var slick_options = {
				centerMode: center,
				autoplay: autoplay,
				centerPadding: '60px',
				rtl: $('body').hasClass('rtl')? true : false,
				speed: 800,			
				slidesToShow: column_lg,
				dots: dots,
				arrows: navs,
				responsive: [
					{
						breakpoint: 1199,
						settings: {
						arrows: navs,
						centerMode: center,
						centerPadding: margin+'px',
						slidesToShow: column_lg
						}
					},
					{
						breakpoint: 991,
						settings: {
						arrows: navs,
						centerMode: center,
						centerPadding: margin+'px',
						slidesToShow: column_lg
						}
					},
					{
						breakpoint: 800,
						settings: {
						arrows: navs,
						centerMode: center,
						centerPadding: margin+'px',
						slidesToShow: column_md
						}
					},
					{
						breakpoint: 767,
						settings: {
						arrows: navs,
						centerMode: center,
						centerPadding: margin+'px',
						slidesToShow: column_sm
						}
					},
					{
						breakpoint: 648,
						settings: {
						arrows: navs,
						centerMode: center,
						centerPadding: margin+'px',
						slidesToShow: column_xs
						}
					}
				]
			};

			
			carousel.slick(slick_options);
		}

		

	})


	
		



});	