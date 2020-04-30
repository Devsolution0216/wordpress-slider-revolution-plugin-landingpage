// JavaScript Document
	jQuery(document).ready(function($) {
			
		"use strict";

		$(window).on('load', function() {
	
		"use strict";
						
		/*----------------------------------------------------*/
		/*	Preloader
		/*----------------------------------------------------*/
		
		$("#loader").delay(100).fadeOut();
		$("#loader-wrapper").delay(100).fadeOut("fast");
				
		$(window).stellar({});

		//parallax scripts
         $('section.vc_parallax').each(function(){
            var opacity = $(this).data('opacity');
            var size = $(this).data('size');
            var position = $(this).data('position');
            var repeat = $(this).data('repeat');
            var attachment = $(this).data('attachment');
            var width = $(this).data('width');
            $(this).find('.vc_parallax-inner').css({
                opacity: opacity,
                backgroundSize: size,
                backgroundPosition: position,
                backgroundRepeat: repeat,
                backgroundAttachment: attachment,
                width: width
            });
        });
         
		
	});	

	
	


	$(window).on('scroll', function() {
		
		"use strict";
								
		/*----------------------------------------------------*/
		/*	Navigtion Menu Scroll
		/*----------------------------------------------------*/	
		
		var b = $(window).scrollTop();
		
		if( b > 72 ){		
			$(".navbar").addClass("scroll");
		} else {
			$(".navbar").removeClass("scroll");
		}				

	});

	$('table').addClass('table');
	$('blockquote').addClass('blockquote');
	$('.sidebar-div select, .footer-widget select, .orderby').selectize({
		create: false,
	});

	$('.vc_row').each(function(){
    	 if($(this).children('.wpb_column').length > 1){
    	 	$(this).addClass('mul-cols');
	    	$(this).children('.wpb_column:last-child').addClass('last-column');
	    }
    });
    $('.row-inner-wrap').each(function(){
    	 if($(this).children('.wpb_column').length > 1){
    	 	$(this).addClass('mul-cols');
	    	$(this).children('.wpb_column:last-child').addClass('last-column');
	    }
    });



		/*----------------------------------------------------*/
		/*	Animated Scroll To Anchor
		/*----------------------------------------------------*/
		
		$('.header a[href^="#"], .page a.btn[href^="#"], .page a.internal-link[href^="#"]').on('click', function (e) {
			
			e.preventDefault();

			var target = this.hash,
			$target = jQuery(target);
			if( $target.length > 0 ){
				if( $(window).width() < 768 ){
					$('.navbar-toggler').trigger( 'click' );
				}				
				$('html, body').stop().animate({
					'scrollTop': $target.offset().top - 60 // - 200px (nav-height)
				}, 'slow', 'easeInSine', function () {					
					window.location.hash = '1' + target;

				});
			}
			
			
		});
		
		
		/*----------------------------------------------------*/
		/*	ScrollUp
		/*----------------------------------------------------*/
		
		$.scrollUp = function (options) {

			// Defaults
			var defaults = {
				scrollName: 'scrollUp', // Element ID
				topDistance: 600, // Distance from top before showing element (px)
				topSpeed: 800, // Speed back to top (ms)
				animation: 'fade', // Fade, slide, none
				animationInSpeed: 200, // Animation in speed (ms)
				animationOutSpeed: 200, // Animation out speed (ms)
				scrollText: '', // Text for element
				scrollImg: false, // Set true to use image
				activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'
			};

			var o = $.extend({}, defaults, options),
				scrollId = '#' + o.scrollName;

			// Create element
			$('<a/>', {
				id: o.scrollName,
				href: '#top',
				title: o.scrollText
			}).appendTo('body');
			
			// If not using an image display text
			if (!o.scrollImg) {
				$(scrollId).html('<i class="fas fa-arrow-up"></i>');
			}

			// Minium CSS to make the magic happen
			$(scrollId).css({'display':'none','position': 'fixed','z-index': '2147483647'});

			// Active point overlay
			if (o.activeOverlay) {
				$("body").append("<div id='"+ o.scrollName +"-active'></div>");
				$(scrollId+"-active").css({ 'position': 'absolute', 'top': o.topDistance+'px', 'width': '100%', 'border-top': '1px dotted '+o.activeOverlay, 'z-index': '2147483647' });
			}

			// Scroll function
			$(window).on('scroll', function(){	
				switch (o.animation) {
					case "fade":
						$( ($(window).scrollTop() > o.topDistance) ? $(scrollId).fadeIn(o.animationInSpeed) : $(scrollId).fadeOut(o.animationOutSpeed) );
						break;
					case "slide":
						$( ($(window).scrollTop() > o.topDistance) ? $(scrollId).slideDown(o.animationInSpeed) : $(scrollId).slideUp(o.animationOutSpeed) );
						break;
					default:
						$( ($(window).scrollTop() > o.topDistance) ? $(scrollId).show(0) : $(scrollId).hide(0) );
				}
			});

			// To the top
			$(scrollId).on('click', function(event){
				$('html, body').animate({scrollTop:0}, o.topSpeed);
				event.preventDefault();
			});

		};
		
		$.scrollUp();


		/* count control */
    $('.count-control').on('click', function(){
    	var $quantity_input = $(this).closest('.quantity').find('.qty');
        var old = parseInt($quantity_input.val());
        if($(this).hasClass('plus')){
          if(parseInt($quantity_input.attr('max')) != -1 ){
            if( (parseInt($quantity_input.attr('max'))-1) >= old ){
             $quantity_input.val(old+1);
            }
          }else{
            $quantity_input.val(old+1);
          }             

        }else{
          if(old > 1){
            $quantity_input.val(old-1);
          }     
        }

        $(this).closest('form').find('button[type="submit"]').prop('disabled', false);
        return false;
    });

    /*product-gallery-carousel*/
    $('.product-gallery-carousel').owlCarousel({
         margin: 15,
         nav: false,
         dots: true,
         items: 3
    });


		/*----------------------------------------------------*/
	/*	Video Link #1 Lightbox
	/*----------------------------------------------------*/
	$('.video-popup1').each(function(){		
		var videoUrl = $(this).attr( 'href' );
		$(this).magnificPopup({
		    type: 'iframe',
		  	  
				iframe: {
					patterns: {
						youtube: {
			   
							index: 'youtube.com',
							src: videoUrl
				
								}
							}
						}		  		  
		});
	});


	/*----------------------------------------------------*/
	/*	Video Link #2 Lightbox
	/*----------------------------------------------------*/
	$('.video-popup2').each(function(){
		var videoUrl = $(this).attr( 'href' );
		$(this).magnificPopup({
		    type: 'iframe',
		  	  
				iframe: {
					patterns: {
						youtube: {
			   
							index: 'youtube.com',
							src: videoUrl
				
								}
							}
						}		  		  
		});
	});

		/*----------------------------------------------------*/
		/*	Statistic Counter
		/*----------------------------------------------------*/
	
		$('.count-element').each(function () {
			$(this).appear(function() {
				$(this).prop('Counter',0).animate({
					Counter: $(this).text()
				}, {
					duration: 4000,
					easing: 'swing',
					step: function (now) {
						$(this).text(Math.ceil(now));
					}
				});
			},{accX: 0, accY: 0});
		});


		/*----------------------------------------------------*/
		/*	Testimonials Rotator
		/*----------------------------------------------------*/
	
		var owl = $('.reviews-holder');
			owl.owlCarousel({
				items: 3,
				loop:true,
				autoplay:true,
				navBy: 1,
				autoplayTimeout: 4500,
				autoplayHoverPause: false,
				smartSpeed: 1500,
				responsive:{
					0:{
						items:1
					},
					767:{
						items:1
					},
					768:{
						items:2
					},
					991:{
						items:3
					},
					1000:{
						items:3
					}
				}
		});


		/*----------------------------------------------------*/
		/*	Reviews Grid
		/*----------------------------------------------------*/

		$('.grid-loaded').imagesLoaded(function () {
	        var $grid = $('.masonry-wrap').isotope({
	            itemSelector: '.review-2',
	            percentPosition: true,
	            transitionDuration: '0.7s',
	            masonry: {
	              columnWidth: '.review-2',
	            }
	        });	        
	    });


		/*----------------------------------------------------*/
		/*	Brands Logo Rotator
		/*----------------------------------------------------*/
	
		var owl = $('.brands-carousel');
			owl.owlCarousel({
				items: 6,
				loop:true,
				autoplay:true,
				navBy: 1,
				autoplayTimeout: 4000,
				autoplayHoverPause: false,
				smartSpeed: 2000,
				responsive:{
					0:{
						items:2
					},
					550:{
						items:3
					},
					767:{
						items:3
					},
					768:{
						items:4
					},
					991:{
						items:4
					},				
					1000:{
						items:6
					}
				}
		});



		/*----------------------------------------------------*/
		/*	Bottom Quick Form
		/*----------------------------------------------------*/
		var bottomFormHeight = $('.bottom-form-holder').outerHeight() - 25;

		$('.quick-form-holder').delay(1000).animate({bottom: -bottomFormHeight}, function(){
			$(this).find('.quick-form-header').addClass('closed');
		}); 
		
		
		$('.quick-form-header').on('click', function(){
			if ($(this).hasClass('closed')){
				$(this).closest('.quick-form-holder').css({display:'block'}).animate({bottom: 0}, function(){
					$(this).find('.quick-form-header').removeClass('closed');
				});
			} else {
				$(this).closest('.quick-form-holder').animate({bottom: -bottomFormHeight}, function(){
					$(this).find('.quick-form-header').addClass('closed');
				});
			}
			
			return false;
		});



		/*----------------------------------------------------*/
		/*	Newsletter Subscribe Form
		/*----------------------------------------------------*/
		if( $('.mailchimp-newsletter-form').length > 0 ){
			$('.mailchimp-newsletter-form').ajaxChimp({
	        language: 'cm',
	        url: 'http://dsathemes.us3.list-manage.com/subscribe/post?u=af1a6c0b23340d7b339c085b4&id=344a494a6e'
	            //http://xxx.xxx.list-manage.com/subscribe/post?u=xxx&id=xxx
			});

			$.ajaxChimp.translations.cm = {
				'submit': 'Submitting...',
				0: 'We have sent you a confirmation email',
				1: 'Please enter your email address',
				2: 'An email address must contain a single @',
				3: 'The domain portion of the email address is invalid (the portion after the @: )',
				4: 'The username portion of the email address is invalid (the portion before the @: )',
				5: 'This email address looks fake or invalid. Please enter a real email address'
			};	
		}

		if( SHIFTKEY.animation == 'on' ){
	    var shiftkeyAnimation = new WOW({
	      boxClass:     'wow',      // default
	      animateClass: 'animated',
	    });
	    shiftkeyAnimation.init();
  }


	});