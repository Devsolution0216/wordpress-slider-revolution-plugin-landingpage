jQuery( function ( $ ) {
	'use strict';

	var $boxes = $( '.wrap .postbox' );

	/**
	 * Setup tab data for all meta boxes
	 */
	function setupTabData() {
		$boxes.each( function () {
			$( this ).data( 'tab', $( this ).find( '.rwmb-settings-tab' ).data( 'tab' ) );
		} );
	}

	/**
	 * Toggle meta boxes.
	 */
	function toggleMetaBox() {
		$( '.if-js-closed' ).removeClass( 'if-js-closed' ).addClass( 'closed' );
		postboxes.add_postbox_toggles( MBSettingsPage.pageHook );
	}

	/**
	 * Switch tab.
	 */
	function switchTab() {
		setupTabData();
		$( '.nav-tab-wrapper' ).on( 'click', 'a', function ( e ) {
			var $this = $( this ),
				tab = $this.attr( 'href' ).substr( 5 );
			$this.siblings().removeClass( 'nav-tab-active' ).end().addClass( 'nav-tab-active' );
			$boxes.each( function () {
				$( this )[tab === $( this ).data( 'tab' ) ? 'show' : 'hide']();
			} );
		} );
	}

	/**
	 * Detect active tab when page is loaded
	 */
	function detectActiveTab() {
		$( '.nav-tab:first' ).trigger( 'click' );
		$( '.nav-tab' ).filter( '[href="' + location.hash + '"]' ).trigger( 'click' );
	}

	toggleMetaBox();
	switchTab();
	detectActiveTab();
} );
