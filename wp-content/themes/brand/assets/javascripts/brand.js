/**
 * File animations.js.
 *
 * @summary Triggers hover animation on mobile devices.
 *
 * @author Massimo Sanfelice - Maxsdesign
 * @package Brand
 * @since Brand 1.8.3
 */

( function($){

	$('.brand_portfolio').bind('touchstart touchend', function(e) {
        $(this).toggleClass('hover');
    });

})(jQuery);
;/**
 * File ios-fixed.js.
 *
 * @summary Avoids issues when position:fixed or background-attachment:fixed are used on iOS devices.
 *
 * @author Massimo Sanfelice - Maxsdesign
 * @package Brand
 * @since Brand 1.6.3
 */

( function($){

if( /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream ) {
  $('.brand-parallax').css('background-attachment', 'scroll');
  $('#wp-custom-header img').css('position', 'absolute');
  $('body').addClass('is-ios-device');
}

})(jQuery);
;/**
 * File menu.js.
 *
 * @summary Manages main menu navigation.
 *
 * @author Massimo Sanfelice - Maxsdesign
 * @package Brand
 * @since Brand 1.0.0
 */

jQuery(document).ready(function( $ ) {

	function nodeExists( el ) {
		node = document.querySelector( el )
  	return document.body.contains(node);
	}

	function setHeaderPadding() {
		if ( nodeExists( ".mobile-nav-bar" ) ) {
			var mobNavHeight = $( ".mobile-nav-bar" ).outerHeight() + 20;
			var navHeight = $( "#main-nav-wrapper" ).outerHeight() + 20;
			if( $(".mobile-nav-bar").css('display') !== 'none') {
				$( '.brand-header-content, \
				.brand-has-header-image.front-page-slider .inner-slide, \
				.portfolio-header' ).css( 'padding-top', mobNavHeight + 'px' );
				$( '.brand-header-content, \
				.brand-has-header-image.front-page-slider .inner-slide, \
				.portfolio-header' ).css( 'padding-bottom', mobNavHeight + 'px' );
			}
			else {
				$( '.brand-header-content, \
				.brand-has-header-image.front-page-slider .inner-slide, \
				.portfolio-header' ).css( 'padding-top', navHeight + 'px' );
				$( '.brand-header-content, \
				.brand-has-header-image.front-page-slider .inner-slide, \
				.portfolio-header' ).css( 'padding-bottom', navHeight + 'px' );
			}
		}
	}

	setHeaderPadding();
	window.addEventListener( "resize", setHeaderPadding );

	function setNavHeight() {
		if ( nodeExists( "#main-nav-wrapper" ) &&  ( nodeExists( "#header-wrapper" ) === false && nodeExists( "#header-portfolio-wrapper" ) === false ) ) {
			var navHeight = $( "#main-nav-wrapper" ).outerHeight();
			$( "#nav-placeholder" ).remove();
				$( '<div id="nav-placeholder" style="height:' + navHeight + 'px;"></div>' ).insertAfter( "#main-nav-wrapper" );
		}

		if ( nodeExists( ".mobile-nav-bar" ) &&  ( nodeExists( "#header-wrapper" ) === false && nodeExists( "#header-portfolio-wrapper" ) === false ) ) {
			var mobNavHeight = $( ".mobile-nav-bar" ).outerHeight();
			$( "#mob-nav-placeholder" ).remove();
			$( '<div id="mob-nav-placeholder" style="height:' + mobNavHeight + 'px;"></div>' ).insertAfter( ".mobile-nav-bar" );
		}
	}
	setNavHeight();
	window.addEventListener( "resize", setNavHeight );

	window.onscroll = function() { scrollNav(); };

	function scrollNav() {
    if ( $( window ).scrollTop() > 2 ) {
        $( '#main-nav-wrapper, .mobile-nav-bar' ).addClass( 'scroll' );
    } else {
        $( '#main-nav-wrapper, .mobile-nav-bar' ).removeClass( 'scroll' );
    }
	}

	$( "a.search-form-icon, .search-item a" ).click(function( event ) {
		if($("div#top-searchform").hasClass("search-bar-slide-in") === false)	{
			$("div#top-searchform").addClass("search-bar-slide-in");
			$("#wrapper").css('position','fixed');
		}
    	event.preventDefault();
	});

	$( "a#close-search" ).click(function( event ) {
		if($("div#top-searchform").hasClass("search-bar-slide-in"))	{
			$("div#top-searchform").removeClass("search-bar-slide-in");
			$("#wrapper").removeAttr('style');
		}
    	event.preventDefault();
	});

	$(document).on( 'click', '.vertical-main-nav #main-nav-wrapper .menu > li.menu-item-has-children > a', function( e ) {
			e.preventDefault();
			$(this).closest('.menu > li.menu-item-has-children').addClass('root-item');
			$('.menu > li.menu-item-has-children.root-item').toggleClass('is-hover');
			$('.menu > li.menu-item-has-children.root-item').not('.is-hover').removeClass('root-item');
	});

	$(document).on( 'click', '.vertical-main-nav #main-nav-wrapper .sub-menu li.menu-item-has-children > a', function( e ) {
			e.preventDefault();
			$(this).parent().not('.root-item').toggleClass('is-hover');
	});

});
; /**
 *
 * @summary   Manages mobile menu navigation.
 *
 * @since     1.0
 * @requires jquery.js
 * @author Massimo Sanfelice - Maxsdesign
 */

'use strict';

jQuery(document).ready(function( $ ) {

	$(window).scroll(function() {
		$('#animatedElement').each(function(){
		var imagePos = $(this).offset().top;
		var topOfWindow = $(window).scrollTop();
			if (imagePos < topOfWindow+400) {
				$(this).addClass("slideLeft");
			}
		});
	});

	$( "#mobile-menu-button, #mobile-menu-close-button" ).click(function( event ) {
 		if($("#mobile-menu-wrapper").hasClass("menuIn"))	{
			$("#mobile-menu-wrapper").removeClass("menuIn");
      $('#cloned-nav, #back-button').remove();
      $('#mobile-menu-wrapper nav > ul').removeClass('hide-nav').addClass('show-nav');
		}
		else if( $( "#mobile-menu-wrapper").hasClass("menuIn") === false ) {
		$( "#mobile-menu-wrapper").addClass("menuIn" );
		}
		else {
			$( "#mobile-menu-wrapper" ).addClass( "menuIn" );
		}
    	event.preventDefault();

    });

	$("body").delegate("#mobile-menu-wrapper li.menu-item-has-children a", "click", function(event) {
		if (!document.getElementById("cloned-nav")) {
			var cloned_nav = document.createElement('div');
			cloned_nav.setAttribute('id', 'cloned-nav');
			var menu_list = document.createElement('ul');
			var back_button = document.createElement('a');
			back_button.setAttribute('id', 'back-button');
			//$('<div id="cloned-nav"></a><ul> </ul> </div>').appendTo("#mobile-menu-wrapper nav");
			//$('<a id="back-button">').prependTo("#cloned-nav");
		}
		if ($('#cloned-nav').hasClass('show-nav')) {
			setTimeout(function(){
				$('#cloned-nav').removeClass('show-nav').addClass('hide-nav');
			}, 100);
		}
		var parent = $(this).parent();
		var parentId = parent.attr('id');
		var list = $('#' + parentId  +  ' ul:first').clone();
		if($("#mobile-menu-wrapper .menu" + " #" + parentId).parent().hasClass('menu')) {

		}
		if ($("#mobile-menu-wrapper .menu" + " #" + parentId).parent().hasClass(parentId) === false) {
			$("#mobile-menu-wrapper .menu" + " #" + parentId).parent().addClass(parentId);
		}
		var backId;
		if($("#mobile-menu-wrapper .menu" + " #" + parentId).parent().hasClass('menu')) {
			backId = 'menu';
		} else {
			backId = parentId;
		}
		setTimeout(function(){
			//$('#' + parentId  +  ' ul:first').removeClass('sub-menu').addClass('show-nav');
			$(parent).parent('ul').removeClass('show-nav').addClass('hide-nav');
		}, 400);
		setTimeout(function(){
			$(cloned_nav).appendTo("#mobile-menu-wrapper nav");
			$(menu_list).appendTo("#cloned-nav");
			$(back_button).prependTo("#cloned-nav");
			$('#cloned-nav').removeClass('hide-nav').addClass('show-nav');
			$("#cloned-nav ul").replaceWith(list);
			$("a#back-button").replaceWith('<a id="back-button" href="#" data-back-id="' + backId + '"> BACK </a>');
			$('#cloned-nav ul:first').removeClass('sub-menu');
		}, 700);
		event.preventDefault();
	});

	$("body").delegate("#mobile-menu-wrapper a#back-button", "click", function(event) {
		var backlist = $(this).attr("data-back-id");
		if (backlist === 'menu') {
			setTimeout(function(){
				$('#cloned-nav').removeClass('show-nav').addClass('hide-nav');
			}, 100);
			setTimeout(function(){
				$('#mobile-menu-wrapper .menu').removeClass('hide-nav').addClass('show-nav');
				$('#cloned-nav').remove();
				//$('#back-button').remove();
			}, 700);
		} else {
			var parentId = $('ul.' + backlist).parent().attr('id');
			if ($('#cloned-nav').hasClass('show-nav')) {
				setTimeout(function(){
					$('#cloned-nav').removeClass('show-nav').addClass('hide-nav');
				}, 100);
			}

			if ($("#mobile-menu-wrapper .menu" + " #" + parentId).parent().hasClass(parentId) === false) {
				$("#mobile-menu-wrapper .menu" + " #" + parentId).parent().addClass(parentId);
			}
			var backId = parentId;
			var list = $('ul.' + backlist).clone();
			setTimeout(function(){
				$('#cloned-nav').removeClass('hide-nav').addClass('show-nav');
				$("#cloned-nav ul").replaceWith(list);
				if ($("#mobile-menu-wrapper" + " #" + parentId).parent().hasClass('menu')) {
					$("#mobile-menu-wrapper a#back-button").replaceWith('<a id="back-button" href="#" data-back-id="menu"> BACK </a>');
				} else {
					$("#mobile-menu-wrapper a#back-button").replaceWith('<a id="back-button" href="#" data-back-id="' + backId + '"> BACK </a>');
				}
				$('#cloned-nav ul:first').removeClass('sub-menu');
			}, 700);
		}

		event.preventDefault();
	});

});
;/**
*
* @summary   Provide a smooth page scroll to an anchor on the same page.
*
* @version   1.0
* @requires jquery.js
* @author Massimo Sanfelice - Maxsdesign
*/

( function($){
	function brandGetFixedNavHeight() {
		var navHeight;
		if ( $( ".sticky-nav .mobile-nav-bar" ).length && $( ".mobile-nav-bar" ).css( 'display' ) !== 'none' ) {
			navHeight = $( ".mobile-nav-bar" ).outerHeight();
		} else if( $( ".sticky-nav #main-nav-wrapper" ).length && $( "#main-nav-wrapper" ).css( 'display' ) !== 'none' ) {
			navHeight = $( "#main-nav-wrapper" ).outerHeight();
		} else {
			navHeight = 0;
		}
		return navHeight;
	}

	// Select all links with hashes
	$( 'body' ).on( 'click', 'a[href*="#"]:not([href="#"])', function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
      &&
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
				// Get the offset minus the nav height
				var targetOffset = target.offset().top - brandGetFixedNavHeight();
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
				if($("#mobile-menu-wrapper").hasClass("menuIn"))	{
					$("#mobile-menu-wrapper").removeClass("menuIn");
		      $('#cloned-nav, #back-button').remove();
		      $('#mobile-menu-wrapper > nav > ul').removeClass('hide-nav').addClass('show-nav');
				}
				console.log(brandGetFixedNavHeight());
        $('html, body').animate({
          scrollTop: targetOffset
        }, 1000);
      }
    }
  });

})(jQuery);
