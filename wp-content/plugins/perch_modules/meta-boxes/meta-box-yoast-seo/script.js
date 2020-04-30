/* global jQuery, YoastSEO, MBYoastSEO */
(function ( $, fields, document ) {
	'use strict';

	/**
	 * The analyze module for Yoast SEO.
	 */
	var module = {
		timeout: undefined,

		// Initialize
		init: function () {
			addEventListener( 'load', module.load );

			// Add new cloned fields.
			$( document ).on( 'clone', 'input[class*="rwmb"], textarea[class*="rwmb"], select[class*="rwmb"], button[class*="rwmb"]', module.addNewField );
		},

		// Load plugin and add hooks.
		load: function () {
			YoastSEO.app.registerPlugin( 'MetaBox', {status: 'loading'} );

			// Make sure clone fields are added.
			getClonedFields();

			// Update Yoast SEO analyzer when fields are updated.
			fields.map( module.listenToField );

			YoastSEO.app.pluginReady( 'MetaBox' );
			YoastSEO.app.registerModification( 'content', module.addContent, 'MetaBox', 5 );

			// Make the Yoast SEO analyzer works for existing content when page loads.
			module.update();
		},

		// Add content to Yoast SEO Analyzer.
		addContent: function ( content ) {
			fields.map( function ( fieldId ) {
				content += ' ' + getFieldContent( fieldId );
			} );
			return content;
		},

		// Listen to field change and update Yoast SEO analyzer.
		listenToField: function( fieldId ) {
			if ( isEditor( fieldId ) ) {
				tinymce.get( fieldId ).on( 'keyup', module.update );
				return;
			}
			var field = document.getElementById( fieldId );
			if ( field ) {
				field.addEventListener( 'keyup', module.update );
			}
		},

		// Update the YoastSEO result. Use debounce technique, which triggers only when keys stop being pressed.
		update: function () {
			clearTimeout( module.timeout );
			module.timeout = setTimeout( function () {
				YoastSEO.app.refresh();
			}, 250 );
		},

		/**
		 * Add new cloned field to the list and listen to its change.
		 */
		addNewField: function() {
			if ( -1 === fields.indexOf( this.id ) ) {
				fields.push( this.id );
				module.listenToField( this.id );
			}
		}
	};

	/**
	 * Get clone fields.
	 */
	function getClonedFields() {
		fields.map( function ( fieldId ) {
			var elements = document.querySelectorAll( '[id^=' + fieldId + '_]' );
			Array.prototype.forEach.call( elements, function( element ) {
				if ( -1 === fields.indexOf( element.id ) ) {
					fields.push( element.id );
				}
			} );
		} );
	}

	/**
	 * Get field content.
	 * Works for normal inputs and TinyMCE editors.
	 *
	 * @param fieldId The field ID
	 * @returns string
	 */
	function getFieldContent( fieldId ) {
		var field = document.getElementById( fieldId );
		if ( field ) {
			var content = isEditor( fieldId ) ? tinymce.get( fieldId ).getContent() : field.value;
			return content ? content : '';
		}
		return '';
	}

	/**
	 * Check if the field is a TinyMCE editor.
	 *
	 * @param fieldId The field ID
	 * @returns boolean
	 */
	function isEditor( fieldId ) {
		return typeof tinymce !== 'undefined' && tinymce.get( fieldId ) !== null;
	}

	// Run on document ready.
	$( module.init );
})( jQuery, MBYoastSEO, document );
