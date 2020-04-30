( function ( $, window, document, wp ) {
	'use strict';

	var conditions = window.conditions,
		watchedElements = [],
		inputSelectors = 'input[class*="rwmb"], textarea[class*="rwmb"], select[class*="rwmb"], button[class*="rwmb"]',
		initialCheck = false;

	function isGutenbergActive() {
		return ! _.isUndefined( wp ) && ! _.isUndefined( wp.blocks );
	}

	/**
	 * Selector cache.
	 *
	 * @link https://ttmm.io/tech/selector-caching-jquery/
	 * @param $scope Scope (jQuery element). Pass empty string to use global scope.
	 */
	function SelectorCache( $scope ) {
		this.collection = {};
		this.$scope = $scope;
	}
	SelectorCache.prototype.get = function ( selector ) {
		if ( undefined === this.collection[selector] ) {
			this.collection[selector] = this.$scope ? this.$scope.find( selector ) : $( selector );
		}

		return this.collection[selector];
	};

	var globalSelectorCache = new SelectorCache();

	function getSelectorCache( $scope ) {
		return $scope ? new SelectorCache( $scope ) : globalSelectorCache;
	}

	/**
	 * Check if an array contains a value using soft comparison.
	 * Used when users set post_category = [1, 2] or ['1', '2']. Both should work.
	 * Note: Array.indexOf(), Array.includes(), _.contains() use strict comparison.
	 */
	function contains( list, value ) {
		var i = list.length;
		while ( i-- ) {
			if ( list[i] == value ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Compare two variables.
	 *
	 * @return boolean
	 */
	function compare( needle, haystack, operator ) {
		if ( needle === null || typeof needle === 'undefined' ) {
			needle = '';
		}

		switch ( operator ) {
		case '=':
			if ( ! Array.isArray( needle ) || ! Array.isArray( haystack ) ) {
				return needle == haystack;
			}
			// Simple comparison for 2 arrays.
			var ok1 = needle.every( function ( value ) {
				return contains( haystack, value );
			} );
			var ok2 = haystack.every( function ( value ) {
				return contains( needle, value );
			} );
			return ok1 && ok2;

		case '>=':
			return needle >= haystack;

		case '>':
			return needle > haystack;

		case '<=':
			return needle <= haystack;

		case '<':
			return needle < haystack;

		case 'contains':
			if ( Array.isArray( needle ) ) {
				return contains( needle, haystack );
			} else if ( typeof needle === 'string' ) {
				return needle.indexOf( haystack ) !== -1;
			}
			return needle == haystack;

		case 'in':
			if ( ! Array.isArray( needle ) ) {
				return needle == haystack || contains( haystack, needle );
			}
			// If needle is an array, 'in' means if any of needle's value in haystack.
			var found = false;
			needle.forEach( function ( value ) {
				if ( value == haystack || contains( haystack, value ) ) {
					found = true;
				}
			} );
			return found;

		case 'start_with':
		case 'starts with':
			return needle.indexOf( haystack ) === 0;

		case 'end_with':
		case 'ends with':
			haystack = new RegExp( haystack + '$' );
			return haystack.test( needle );

		case 'match':
			haystack = new RegExp( haystack );
			return haystack.test( needle );

		case 'between':
			if ( Array.isArray( haystack ) && typeof haystack[0] !== 'undefined' && typeof haystack[1] !== 'undefined' ) {
				return needle >= haystack[0] && needle <= haystack[1];
			}
		}
	}

	/**
	 * Get field value.
	 *
	 * @param fieldName     Field name.
	 * @param selectorCache An instance of SelectorCache.
	 */
	function getFieldValue( fieldName, selectorCache ) {
		if ( ! initialCheck && 'page_template' === fieldName ) {
			return MBConditionalLogicData.page_template;
		}
		if ( ! initialCheck && 'post_format' === fieldName ) {
			return MBConditionalLogicData.post_format;
		}

		// Allows user define conditional logic by callback
		if ( compare( fieldName, '(', 'contains' ) ) {
			return eval( fieldName );
		}

		var $field = compare( fieldName, '#', 'start_with' ) ? selectorCache.get( fieldName ) : selectorCache.get( '#' + fieldName ),
			value = $field.val();
		if ( $field.length && $field.attr( 'type' ) !== 'checkbox' && typeof value !== 'undefined' && value != null ) {
			return value;
		}

		var selector = null,
			isMultiple = false;

		// Try to find the element via [name] attribute.
		if ( selectorCache.get( '[name="' + fieldName + '"]' ).length ) {
			selector = '[name="' + fieldName + '"]';
		} else if ( selectorCache.get( '[name*="' + fieldName + '"]' ).length ) {
			selector = '[name*="' + fieldName + '"]';
		} else if ( selectorCache.get( '[name*="' + fieldName + '[]"]' ).length ) {
			selector = '[name*="' + fieldName + '[]"]';
			isMultiple = true;
		}

		if ( 'post_format' === fieldName ) {
			selector = getPostFormatSelector();
		}
		if ( 'page_template' === fieldName ) {
			selector = getPageTemplateSelector();
		}

		if ( null === selector ) {
			return 0;
		}

		var $selector = selectorCache.get( selector ),
			selectorType = $selector.attr( 'type' );
		selectorType = selectorType ? selectorType : $selector.prop( 'tagName' );

		var isSelectTree = 'SELECT' === selectorType && isMultiple;

		if ( ['checkbox', 'radio', 'hidden'].indexOf( selectorType ) === -1 && ! isSelectTree ) {
			return $selector.val();
		}

		// If user selected a checkbox, radio, or select tree, return array of selected fields, or value of singular field.
		var values = [],
			$elements = [];

		if ( selectorType === 'hidden' && fieldName !== 'post_category' && ! compare( selector, 'tax_input', 'contains' ) ) {
			$elements = $selector;
		} else if ( isSelectTree ) {
			$elements = $selector;
		} else {
			$elements = $selector.filter( ':checked' );
		}

		$elements.each( function () {
			values.push( this.value );
		} );

		return values.length > 1 ? values : values.pop();
	}

	/**
	 * Check if logics attached to fields is correct or not.
	 * If a field is hidden by Conditional Logic, then all dependent fields are hidden also.
	 *
	 * @param  logics Array of logic applied to field.
	 * @param  $field Current field (input) element (jQuery object).
	 * @return boolean
	 */
	function isLogicCorrect( logics, $field ) {
		var relation = typeof logics.relation !== 'undefined' ? logics.relation.toLowerCase() : 'and',
			success = relation === 'and';

		logics.when.forEach( function ( logic ) {
			// Skip check if we already know the result.
			if ( relation === 'and' && ! success ) {
				return;
			}
			if ( relation === 'or' && success ) {
				return;
			}

			// Get scope of current field. Scope is only applied for Group field.
			// A scope is a group or whole meta box which contains event source and current field.
			var $scope = getFieldScope( $field ),
				selectorCache = getSelectorCache( $scope ),
				dependentFieldSelector = guessSelector( logic[0], selectorCache );

			if ( ! dependentFieldSelector ) {
				return;
			}

			var $dependentField = selectorCache.get( dependentFieldSelector ),
				isDependentFieldVisible = $dependentField.closest( '.rwmb-field' ).attr( 'data-visible' );

			if ( 'hidden' === isDependentFieldVisible ) {
				success = 'hidden';
				return;
			}

			var dependentValue = getFieldValue( logic[0], selectorCache ),
				operator = logic[1],
				value = logic[2],
				check,
				negative = false;

			console.log( dependentValue, operator, value );

			// Cast to string if array has 1 element and its a string
			if ( Array.isArray( dependentValue ) && dependentValue.length === 1 ) {
				dependentValue = dependentValue[0];
			}

			// Allows user using NOT statement.
			if ( compare( operator, 'not', 'contains' ) || compare( operator, '!', 'contains' ) ) {
				negative = true;
				operator = operator.replace( 'not', '' );
				operator = operator.replace( '!', '' );
			}

			operator = operator.trim();

			if ( $.isNumeric( dependentValue ) ) {
				dependentValue = parseInt( dependentValue );
			}

			check = compare( dependentValue, value, operator );

			if ( negative ) {
				check = ! check;
			}

			success = relation === 'and' ? success && check : success || check;
		} );

		return success;
	}

	function getFieldScope( $field ) {
		// $field is empty when checking logic of outside conditions.
		if ( ! $field ) {
			return '';
		}

		// If the current field is in a group clone, then all the logics must be within this group.
		var $groupClone = $field.closest( '.rwmb-group-clone' );
		if ( $groupClone.length ) {
			return $groupClone;
		}

		// If Gutenberg is active.
		if ( isGutenbergActive() ) {
			return $( '#editor' );
		}

		// Global scope. Should be the closest 'form', since in the frontend, users can insert the same meta box in multiple forms.
		// In the backend, edit 'form' wraps almost everything. So it should be okay.
		var $form = $field.closest( 'form' );
		return $form.length ? $form : '';
	}

	function runConditionalLogic( $scope ) {
		// Log run time for performance tracking.
		// console.time( 'Run Conditional Logic' );

		// Run only for the new cloned group (when click add clone button) if possible.
		var $conditions = $scope ? $scope.find( '.rwmb-conditions' ) : $( '.rwmb-conditions' );
		$conditions.each( function () {
			var $this = $( this ),
				conditions = $this.data( 'conditions' ),
				action = typeof conditions['hidden'] !== 'undefined' ? 'hidden' : 'visible',
				logic = conditions[action],
				logicApply = isLogicCorrect( logic, $this ),
				$element = $this.parent();

			if ( ! $element.hasClass( 'rwmb-field' ) ) {
				$element = $element.closest( '.postbox' );
			}

			toggle( $element, logicApply, action );
		} );

		// Show run time.
		// Test 001-visibility-broken: 20 clones < 300ms.
		// console.timeEnd( 'Run Conditional Logic' );

		// Outside conditions.
		_.each( conditions, function ( logics, field ) {
			_.each( logics, function ( logic, action ) {
				if ( typeof logic.when === 'undefined' ) {
					return;
				}

				var selector = guessSelector( field, globalSelectorCache ),
					$element = globalSelectorCache.get( selector ),
					logicApply = isLogicCorrect( logic, '' );

				toggle( $element, logicApply, action );
			} );
		} );
	}

	function toggle( $element, logic, action ) {
		if ( logic === true ) {
			action === 'visible' ? applyVisible( $element ) : applyHidden( $element );
		} else if ( logic === false ) {
			action === 'visible' ? applyHidden( $element ) : applyVisible( $element );
		} else if ( logic === 'hidden' ) {
			applyHidden( $element );
		}
	}

	function getWatchedElements() {
		$( '.rwmb-conditions' ).each( function () {
			var fieldConditions = $( this ).data( 'conditions' ),
				action = typeof fieldConditions['hidden'] !== 'undefined' ? 'hidden' : 'visible',
				logic = fieldConditions[action];

			logic.when.forEach( addWatchedElement, this );
		} );

		// Outside conditions.
		_.each( conditions, function ( logics ) {
			_.each( logics, function ( logic ) {
				if ( typeof logic.when === 'undefined' ) {
					return;
				}
				logic.when.forEach( addWatchedElement, null );
			} );
		} );

		watchedElements = watchedElements.join();
	}

	function addWatchedElement( logic ) {
		if ( compare( logic[0], '(', 'contains' ) ) {
			return;
		}

		// Find selector within correct scope to speed up.
		var $scope = null;
		if ( null !== this ) {
			$scope = getFieldScope( $( this ) );
		}
		var selectorCache = getSelectorCache( $scope ),
			selector = guessSelector( logic[0], selectorCache );

		if ( selector && watchedElements.indexOf( selector ) === -1 ) {
			watchedElements.push( selector );
		}
	}

	/**
	 * Guess the selector by field name
	 *
	 * @param  fieldName Field Name.
	 * @param  selectorCache An instance of SelectorCache.
	 * @return string    CSS selector.
	 */
	function guessSelector( fieldName, selectorCache ) {
		if ( compare( fieldName, '(', 'contains' ) ) {
			return '';
		}
		if ( ! selectorCache ) {
			selectorCache = globalSelectorCache;
		}

		if ( isUserDefinedSelector( fieldName ) ) {
			return fieldName;
		}

		if ( 'post_format' === fieldName ) {
			return getPostFormatSelector();
		}
		if ( 'page_template' === fieldName ) {
			return getPageTemplateSelector();
		}

		var selectors = [
			fieldName,
			'#' + fieldName,
			'[name="' + fieldName + '"]',
			'[name^="' + fieldName + '"]',
			'[name*="' + fieldName + '"]'
		];
		var selector = _.find( selectors, function( pattern ) {
			return selectorCache.get( pattern ).length > 0;
		} );

		return selector ? selector : '';
	}

	function isUserDefinedSelector( fieldName ) {
		return compare( fieldName, '.', 'starts with' ) ||
			compare( fieldName, '#', 'starts with' ) ||
			compare( fieldName, '[name', 'contains' ) ||
			compare( fieldName, '>', 'contains' ) ||
			compare( fieldName, '*', 'contains' ) ||
			compare( fieldName, '~', 'contains' );
	}

	function getPostFormatSelector() {
		return isGutenbergActive() ? '.editor-post-format .components-select-control__input' : 'input[name="post_format"]';
	}

	function getPageTemplateSelector() {
		return isGutenbergActive() ? '.editor-page-attributes__template .components-select-control__input' : '#page_template';
	}

	function getToggleType( $element ) {
		var toggleType = 'display',
			hasToggleTypeDefined = $element.closest( '.postbox' ).find( '.mbc-toggle-type' );
		if ( hasToggleTypeDefined.length ) {
			toggleType = hasToggleTypeDefined.data( 'toggle_type' );
		}

		return toggleType;
	}

	/**
	 * Show an element.
	 *
	 * @param $element Element: jQuery object.
	 */
	function applyVisible( $element ) {
		// If element is a field, get the field wrapper.
		var $field = $element.closest( '.rwmb-field' );
		if ( $field.length ) {
			$element = $field;
		}

		var toggleType = getToggleType( $element ),
			func = {
				display: 'show',
				slide: 'slideDown',
				fade: 'fadeIn'
			};
		if ( func.hasOwnProperty( toggleType ) ) {
			$element[func[toggleType]]();
		} else {
			$element.css( 'visibility', 'visible' );
		}

		$element.attr( 'data-visible', 'visible' );

		// Reset the required attribute for inputs.
		$element.find( inputSelectors ).each( function() {
			var $this = $( this ),
				$field = $this.closest( '.rwmb-field.required' );
			if ( $field.length ) {
				$this.prop('required', true );
			}
		} );
	}

	/**
	 * Hide an element.
	 *
	 * @param $element Element: jQuery object.
	 */
	function applyHidden( $element ) {
		// If element is a field, get the field wrapper.
		var $field = $element.closest( '.rwmb-field' );
		if ( $field.length ) {
			$element = $field;
		}

		var toggleType = getToggleType( $element ),
			func = {
				display: 'hide',
				slide: 'slideUp',
				fade: 'fadeOut'
			};
		if ( func.hasOwnProperty( toggleType ) ) {
			$element[func[toggleType]]();
		} else {
			$element.css( 'visibility', 'hidden' );
		}

		$element.attr( 'data-visible', 'hidden' );

		// Remove required attribute for inputs and trigger a custom event.
		$element.find( inputSelectors ).each( function() {
			var $this = $( this ),
				required = $this.attr( 'required' );
			if ( required ) {
				$this.prop( 'required', false );
			}
			$this.trigger( 'cl_hide' );
		} );
	}

	function init() {
		runConditionalLogic();
		getWatchedElements();

		initialCheck = true;
	}

	// Run when page finishes loading to improve performance.
	// https://github.com/wpmetabox/meta-box/issues/1195.
	$( window ).on( 'load', function () {
		// Load conditional logic by default.
		init();

		var $document = $( document );

		// Listening eventSource apply conditional logic when eventSource is change.
		if ( watchedElements.length > 1 ) {
			$document.on( 'change keyup', watchedElements, function() {
				var $scope = getFieldScope( $( this ) );
				runConditionalLogic( $scope );
			} );
		}

		// Featured image replaces HTML, thus the event listening above doesn't work.
		// We have to detect DOM change.
		if ( -1 !== watchedElements.indexOf( '_thumbnail_id' ) ) {
			$( '#postimagediv' ).on( 'DOMSubtreeModified', runConditionalLogic );
		}

		// For groups.
		$document.on( 'clone_completed',function( event, $group ) {
			runConditionalLogic( $group );
		} );
	} );
} )( jQuery, window, document, wp );
