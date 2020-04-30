/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	wp.customize( 'brand-link-hover-color', function( value ) {
		value.bind( function( to ) {
			$('style.link-color').remove();
			$('body').prepend('<style type="text/css" class="link-color"> nav.main-nav ul > li:after, nav.main-nav li ul {border-top: 3px solid ' + to + ';} a:hover, a.light:hover, .button-link a:hover, button:hover, html input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover {color: ' + to + '; } .button-link a:hover, button:hover, html input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, input:focus, textarea:focus, .form-control {border-color:' + to + ';} </style>');
		} );
	} );

	wp.customize( 'brand-header-bg-color', function( value ) {
		value.bind( function( to ) {
			$('#header-overlay').css( {
				'background-color': to
			} );
		} );
	} );

	wp.customize( 'brand_settings[header_width]', function( value ) {
		value.bind( function( to ) {
			$('#header-wrapper').removeClass('container');
			if(to == 'boxed') {
				$('#header-wrapper').addClass('container');
			}
		} );
	} );

	wp.customize( 'brand_settings[header_alignment]', function( value ) {
		value.bind( function( to ) {
			$( '#header-wrapper, #header-portfolio-wrapper' ).css( { 'text-align': to } );
		} );
	} );

	wp.customize( 'brand_settings[container_width]', function( value ) {
		value.bind( function( to ) {
			$('.container, #content > .page-header-featured').css( {
				'max-width': to + 'px'
			} );
			$('.page-template-full-width.no-sidebar #content, \
			.no-sidebar.page-template-full-width #primary, \
			.no-sidebar.page-template-full-width #main-nav-wrapper.container, \
			.no-sidebar.page-template-full-width #header-wrapper.container, \
			.no-sidebar.page-template-full-width #footer.container').css( {
				'max-width': '100%'
			} );
		} );
	} );

	wp.customize( 'brand_settings[nav_width]', function( value ) {
		value.bind( function( to ) {
			$('#main-nav-wrapper').removeClass('container');
			if(to == 'boxed') {
				$('#main-nav-wrapper').addClass('container');
			} else {
				$( '#main-nav-wrapper' ).css( {
					'max-width': '100%'
				} );
			}
		} );
	} );

	wp.customize( 'brand_settings[nav_alignment]', function( value ) {
		value.bind( function( to ) {
			$( '#main-nav-wrapper, #sticky-nav-wrapper' ).css( {
				'text-align': to
			} );
		} );
	} );

	wp.customize( 'brand_settings[nav_orientation]', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass('horizontal-main-nav vertical-main-nav');
			if(to == 'horizontal') {
				$('body').addClass('horizontal-main-nav');
			} else {
				$('body').addClass('vertical-main-nav');
			}
		} );
	} );

	wp.customize( 'brand_settings[sticky_menu]', function( value ) {
		value.bind( function( to ) {
			if(to == 'yes') {
				window.onscroll = function() {resizeNav();};
			}
		} );
	} );

	wp.customize( 'header_bg_color', function( value ) {
		value.bind( function( to ) {
			$('#header-wrapper').css( {
				'background-color': to
			} );
		} );
	} );

	// Header color overlay.
	wp.customize( 'brand-header-image-color-overlay', function( value ) {
		value.bind( function( to ) {
			$('style.header-color-overlay').remove();
			$('body').prepend('<style type="text/css" class="header-color-overlay"> #header-wrapper:before {background-color: ' + to + ';} </style>');
		} );
	} );

	wp.customize( 'brand-header-image-color-overlay-opacity', function( value ) {
		value.bind( function( to ) {
			$('style.header-color-overlay-opacity').remove();
			$('body').prepend('<style type="text/css" class="header-color-overlay-opacity"> #header-wrapper:before {opacity: ' + to + ';} </style>');
		} );
	} );

	// Page title
	wp.customize( 'brand_settings[page_title_alignment]', function( value ) {
		value.bind( function( to ) {
			$( '.main-title' ).css( {
				'text-align': to
			} );
		} );
	} );

	// Content width
	wp.customize( 'brand_settings[content_width]', function( value ) {
		value.bind( function( to ) {
			$('#content').removeClass('container');
			if(to == 'boxed') {
				$('#content').addClass('container');
			}
		} );
	} );

	// Body base colors
	wp.customize( 'brand_settings[body_text_color]', function( value ) {
		value.bind( function( to ) {
			$('body').css( {
				'color': to
			} );
		} );
	} );

	wp.customize( 'brand_settings[link_color]', function( value ) {
		value.bind( function( to ) {
			$('style.brand-link-color').remove();
			$('body').prepend('<style type="text/css" class="brand-link-color"> \
			a, a:visited, \
			.woocommerce div.product .woocommerce-tabs ul.tabs li a {color: ' + to + ';} </style>');
		} );
	} );

	wp.customize( 'brand_settings[link_hover_color]', function( value ) {
		value.bind( function( to ) {
			$('style.brand-link-hover-color').remove();
			$('body').prepend('<style type="text/css" class="brand-link-hover-color"> \
			a:hover, a:focus, a:active, \
			.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, \
			.woocommerce div.product .woocommerce-tabs ul.tabs li.active a {color: ' + to + ';} </style>');
		} );
	} );

	// Footer widgets width
	wp.customize( 'brand_settings[footer_width]', function( value ) {
		value.bind( function( to ) {
			$('#footer').removeClass('container');
			if(to == 'boxed') {
				$('#footer').addClass('container');
			} else {
				$( '#footer' ).css( {
					'max-width': '100%'
				} );
			}
		} );
	} );

	// Whether a header image is available.
	function hasHeaderImage() {
		var image = wp.customize( 'header_image' )();
		return '' !== image && 'remove-header' !== image;
	}

	// Whether a header video is available.
	function hasHeaderVideo() {
		var externalVideo = wp.customize( 'external_header_video' )(),
			video = wp.customize( 'header_video' )();

		return '' !== externalVideo || ( 0 !== video && '' !== video );
	}

	// Toggle a body class if a custom header exists.
	$.each( [ 'external_header_video', 'header_image', 'header_video' ], function( index, settingId ) {
		wp.customize( settingId, function( setting ) {
			setting.bind(function() {
				$('style.brand-custom-header').remove();
				if ( hasHeaderImage() ) {
					var image = wp.customize( 'header_image' )();
					$('head').append('<style type="text/css" class="brand-custom-header"> #header-wrapper:before {background-image: url(' + image + ');} </style>');
				}
				if ( ( hasHeaderImage() || hasHeaderVideo() ) && $( document.body ).hasClass( 'home' ) ) {
					$( document.body ).addClass( 'has-front-page-custom-header' );
				} else {
					$( document.body ).removeClass( 'has-front-page-custom-header' );
					$('head').append('<style type="text/css" class="brand-custom-header"> #header-wrapper:before {background-image: none;} </style>');
				}
			} );
		} );
	} );

} )( jQuery );
