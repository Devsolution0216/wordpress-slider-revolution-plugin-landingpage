jQuery( function ( $ ) {
	'use strict';

	// Global variables
	var $parent = $( '#parent_id' );

	// List of selectors for each type of element.
	// Made for compatibility with classic and Gutenberg editors.
	var selectors = {
		'template': ['#page_template', '.editor-page-attributes__template .components-select-control__input'],
		'post_format': ['input[name="post_format"]', '.editor-post-format .components-select-control__input'],
		'parent': ['#parent_id'], // No selector in Gutenberg, thus not working.
	};
	var elements = {};

	var $document = $( document );

	var initialCheck = false;

	function initElements() {
		_.forEach( selectors, function( list, key ) {
			var selector = _.find( list, function( selector ) {
				return $( selector ).length;
			} );
			elements[key] = $( selector );
		} );
	}

	// Callback functions to check for each condition
	var checkCallbacks = {
		template   : function ( templates ) {
			var value = initialCheck ? elements.template.val() : MBShowHideData.template,
				result = -1 !== templates.indexOf( value );
			console.log( 'Check by template:', result );
			return result;
		},
		post_format: function ( formats ) {
			// Make sure registered formats in lowercase
			formats = formats.map( function ( format ) {
				return format.toLowerCase();
			} );

			var value = MBShowHideData.post_format;
			if ( initialCheck ) {
				value = elements.post_format.is( 'select' ) ? elements.post_format.val() : elements.post_format.filter( ':checked' ).val();
			}
			if ( !value || 0 == value ) {
				value = 'standard';
			}

			var result = -1 != formats.indexOf( value );
			console.log( 'Check by post format:', result );
			return result;
		},
		taxonomy   : function ( taxonomy, terms ) {
			var values = [],
				$inputs = $( '#' + taxonomy + 'checklist :checked' );

			$inputs.each( function () {
				var $input = $( this ),
					text = $.trim( $input.parent().text() );
				values.push( parseInt( $input.val() ) );
				values.push( text );
			} );

			for ( var i = 0, len = values.length; i < len; i++ ) {
				if ( -1 != terms.indexOf( values[i] ) ) {
					return true;
				}
			}
			return false;
		},
		input_value: function ( inputValues, relation ) {
			relation = relation || 'OR';

			for ( var i in inputValues ) {
				var $element = $( i ),
					value = $.trim( $element.val() ),
					checked = null;

				if ( $element.is( ':checkbox' ) ) {
					checked = $element.is( ':checked' ) === !!inputValues[i];
				}

				if ( $element.is( ':radio' ) ) {
					value = $.trim( $element.filter( ':checked' ).val() );
				}

				if ( 'OR' == relation ) {
					if ( ( value == inputValues[i] && checked === null ) || checked === true ) {
						return true;
					}
				} else {
					if ( ( value != inputValues[i] && checked === null ) || checked === false ) {
						return false;
					}
				}
			}
			return relation != 'OR';
		},
		is_child   : function () {
			var value = initialCheck ? elements.parent.val() : MBShowHideData.parent,
				result = !! parseInt( value );
			console.log( 'Check by is child:', result );
			return result;
		}
	};

	// Callback functions to addEventListeners for "change" event in each condition
	var addEventListenersCallbacks = {
		/**
		 * Check by page templates
		 *
		 * @param callback Callback function
		 *
		 * @return bool
		 */
		template   : function ( callback ) {
			$document.on( 'change', selectors.template.join( ',' ), callback );
		},
		post_format: function ( callback ) {
			$document.on( 'change', selectors.post_format.join( ',' ), callback );
		},
		taxonomy   : function ( taxonomy, callback ) {
			// Fire "change" event when click on popular category
			// See wp-admin/js/post.js
			$( '#' + taxonomy + 'checklist-pop' ).on( 'click', 'input', function () {
				var t = $( this ), val = t.val(), id = t.attr( 'id' );
				if ( !val ) {
					return;
				}

				var tax = id.replace( 'in-popular-', '' ).replace( '-' + val, '' );
				$( '#in-' + tax + '-' + val ).trigger( 'change' );
			} );

			$( '#' + taxonomy + 'checklist' ).on( 'change', 'input', callback );
		},
		input_value: function ( callback, selector ) {
			$( selector ).on( 'change', callback );
		},
		is_child   : function ( callback ) {
			$document.on( 'change', selectors.parent.join( ',' ), callback );
		}
	};

	/**
	 * Add JS addEventListenersers to check conditions to show/hide a meta box
	 * @param type
	 * @param conditions
	 * @param $metaBox
	 */
	function maybeShowHide( type, conditions, $metaBox ) {
		var condition = checkAllConditions( conditions );

		if ( 'show' == type ) {
			condition ? $metaBox.show() : $metaBox.hide();
		} else {
			condition ? $metaBox.hide() : $metaBox.show();
		}
	}

	/**
	 * Check all conditions
	 * @param conditions Array of all conditions
	 *
	 * @return bool
	 */
	function checkAllConditions( conditions ) {
		// Don't change "global" conditions
		var localConditions = $.extend( {}, conditions );

		var relation = localConditions.hasOwnProperty( 'relation' ) ? localConditions['relation'].toUpperCase() : 'OR',
			value;

		// For better loop of checking terms
		if ( localConditions.hasOwnProperty( 'relation' ) ) {
			delete localConditions['relation'];
		}

		initElements();

		var checkBy = ['template', 'post_format', 'input_value', 'is_child'],
			by, condition;

		for ( var i = 0, l = checkBy.length; i < l; i++ ) {
			by = checkBy[i];

			if ( !localConditions.hasOwnProperty( by ) ) {
				continue;
			}

			// Call callback function to check for each condition
			condition = checkCallbacks[by]( localConditions[by], relation );

			if ( 'OR' == relation ) {
				value = typeof value == 'undefined' ? condition : value || condition;
				if ( value ) {
					break;
				}
			} else {
				value = typeof value == 'undefined' ? condition : value && condition;
				if ( !value ) {
					break;
				}
			}

			delete localConditions[by];
		}

		// By taxonomy, including category and post format
		// Note that we unset all other parameters, so we can safely loop in the localConditions array
		if ( localConditions.length ) {
			for ( var taxonomy in localConditions ) {
				if ( !localConditions.hasOwnProperty( taxonomy ) ) {
					continue;
				}

				// Call callback function to check for each condition
				condition = checkCallbacks['taxonomy']( taxonomy, localConditions[taxonomy] );

				if ( 'OR' == relation ) {
					value = typeof value == 'undefined' ? condition : value || condition;
					if ( value ) {
						break;
					}
				} else {
					value = typeof value == 'undefined' ? condition : value && condition;
					if ( !value ) {
						break;
					}
				}
			}
		}

		initialCheck = true;

		return value;
	}

	/**
	 * Add event addEventListenersers for "change" event
	 * This will re-check all conditions to show/hide meta box
	 * @param type
	 * @param conditions
	 * @param $metaBox
	 */
	function addEventListeners( type, conditions, $metaBox ) {
		// Don't change "global" conditions
		var localConditions = $.extend( {}, conditions );

		// For better loop of checking terms
		if ( localConditions.hasOwnProperty( 'relation' ) ) {
			delete localConditions['relation'];
		}

		var checkBy = ['template', 'post_format', 'input_value', 'is_child'], by;
		for ( var i = 0, l = checkBy.length; i < l; i++ ) {
			by = checkBy[i];

			if ( ! localConditions.hasOwnProperty( by ) ) {
				continue;
			}

			if ( 'input_value' != by ) {
				// Call callback function to check for each condition
				addEventListenersCallbacks[by]( function () {
					maybeShowHide( type, conditions, $metaBox );
				} );
				delete localConditions[by];
				continue;
			}

			// Input values
			for ( var selector in localConditions[by] ) {
				// Call callback function to check for each condition
				addEventListenersCallbacks[by]( function () {
					maybeShowHide( type, conditions, $metaBox );
				}, selector );
			}
			delete localConditions[by];

		}

		// By taxonomy, including category and post format
		// Note that we unset all other parameters, so we can safely loop in the localConditions array
		if ( !localConditions.length ) {
			for ( var taxonomy in localConditions ) {
				if ( ! localConditions.hasOwnProperty( taxonomy ) ) {
					continue;
				}

				// Call callback function to check for each condition
				addEventListenersCallbacks['taxonomy']( taxonomy, function () {
					maybeShowHide( type, conditions, $metaBox );
				} );
			}
		}
	}

	function init() {
		$( '.mb-show-hide' ).each( function () {
			var $this = $( this ),
				$metaBox = $this.closest( '.postbox' ),
				conditions;

			// Check for show rules
			if ( $this.data( 'show' ) ) {
				conditions = $this.data( 'show' );
				maybeShowHide( 'show', conditions, $metaBox );
			}

			// Check for hide rules
			if ( $this.data( 'hide' ) ) {
				conditions = $this.data( 'hide' );
				maybeShowHide( 'hide', conditions, $metaBox );
			}
		} );
	}

	function initEventListeners() {
		$( '.mb-show-hide' ).each( function () {
			var $this = $( this ),
				$metaBox = $this.closest( '.postbox' ),
				conditions;

			// Check for show rules
			if ( $this.data( 'show' ) ) {
				conditions = $this.data( 'show' );
				addEventListeners( 'show', conditions, $metaBox );
			}

			// Check for hide rules
			if ( $this.data( 'hide' ) ) {
				conditions = $this.data( 'hide' );
				addEventListeners( 'hide', conditions, $metaBox );
			}
		} );
	}

	// Run the code after Gutenberg has done rendering.
	// https://stackoverflow.com/a/34999925/371240
	setTimeout( function() {
		window.requestAnimationFrame( function() {
			initElements();
			init();
			initEventListeners();
		} );
	}, 1000 );
} );
