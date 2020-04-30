<?php
/**
 * Functions file
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

if( !function_exists( 'yith_wcwl_is_wishlist' ) ){
	/**
	 * Check if we're printing wishlist shortcode
	 *
	 * @return bool
	 * @since 2.0.0
	 */
	function yith_wcwl_is_wishlist(){
		global $yith_wcwl_is_wishlist;

		return $yith_wcwl_is_wishlist;
	}
}

if( !function_exists( 'yith_wcwl_is_wishlist_page' ) ){
	/**
	 * Check if current page is wishlist
	 *
	 * @return bool
	 * @since 2.0.13
	 */
	function yith_wcwl_is_wishlist_page(){
		$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );

		if( ! $wishlist_page_id ){
			return false;
		}

		return apply_filters( 'yith_wcwl_is_wishlist_page', is_page( $wishlist_page_id ) );
	}
}

if( !function_exists( 'yith_wcwl_locate_template' ) ) {
	/**
	 * Locate the templates and return the path of the file found
	 *
	 * @param string $path
	 * @param array $var
	 * @return string
	 * @since 1.0.0
	 */
	function yith_wcwl_locate_template( $path, $var = NULL ){
		$woocommerce_base = WC()->template_path();

		$template_woocommerce_path =  $woocommerce_base . $path;
		$template_path = '/' . $path;
		$plugin_path = YITH_WCWL_DIR . 'templates/' . $path;

		$located = locate_template( array(
			$template_woocommerce_path, // Search in <theme>/woocommerce/
			$template_path,             // Search in <theme>/
		) );

		if( ! $located && file_exists( $plugin_path ) ){
			return apply_filters( 'yith_wcwl_locate_template', $plugin_path, $path );
		}

		return apply_filters( 'yith_wcwl_locate_template', $located, $path );
	}
}

if( !function_exists( 'yith_wcwl_get_template' ) ) {
	/**
	 * Retrieve a template file.
	 *
	 * @param string $path
	 * @param mixed $var
	 * @param bool $return
	 * @return string|void
	 * @since 1.0.0
	 */
	function yith_wcwl_get_template( $path, $var = null, $return = false ) {
		$located = yith_wcwl_locate_template( $path, $var );

		if ( $var && is_array( $var ) ) {
			$atts = $var;
			extract( $var );
		}

		if( $return ) {
			ob_start();
		}

		// include file located
		include( $located );

		if( $return ) {
			return ob_get_clean();
		}
	}
}

if( !function_exists( 'yith_wcwl_get_template_part' ) ) {
	/**
	 * Search and include a template part
	 *
	 * @param $template string Template to include
	 * @param $template_part string Template part
	 * @param $template_layout string Template variation
	 * @param $var array Array of variables to be passed to template
	 * @param $return bool Whether to return template or print it
	 * @return string|null
	 */
	function yith_wcwl_get_template_part( $template = '', $template_part = '', $template_layout = '', $var = array(), $return = false ) {
		if ( ! empty( $template_part ) ) {
			$template_part = '-' . $template_part;
		}

		if ( ! empty( $template_layout ) ) {
			$template_layout = '-' . $template_layout;
		}

		$template_hierarchy = apply_filters( 'yith_wcwl_template_part_hierarchy', array_merge(
			! yith_wcwl_is_mobile() ? array() : array(
				"wishlist-{$template}{$template_layout}{$template_part}-mobile.php",
				"wishlist-{$template}{$template_part}-mobile.php"
			),
			array(
				"wishlist-{$template}{$template_layout}{$template_part}.php",
				"wishlist-{$template}{$template_part}.php"
			)
		),$template, $template_part, $template_layout, $var );

		foreach( $template_hierarchy as $filename ){
			$located  = yith_wcwl_locate_template( $filename );

			if ( $located ) {
				return yith_wcwl_get_template( $filename, $var, $return );
			}
		}
	}
}

if( !function_exists( 'yith_wcwl_count_products' ) ) {
	/**
	 * Retrieve the number of products in the wishlist.
	 *
	 * @param $wishlist_token string|bool Optional wishlist token
	 *
	 * @return int
	 * @since 1.0.0
	 */
	function yith_wcwl_count_products( $wishlist_token = false ) {
		return YITH_WCWL()->count_products( $wishlist_token );
	}
}

if( !function_exists( 'yith_wcwl_count_all_products' ) ) {
	/**
	 * Retrieve the number of products in all the wishlists.
	 *
	 * @return int
	 * @since 2.0.13
	 */
	function yith_wcwl_count_all_products() {
		return YITH_WCWL()->count_all_products();
	}
}

if( !function_exists( 'yith_wcwl_count_add_to_wishlist' ) ){
	/**
	 * Count number of times a product was added to users wishlists
	 *
	 * @param $product_id int|bool Product id
	 *
	 * @return int Number of times the product was added to wishlists
	 * @since 2.0.13
	 */
	function yith_wcwl_count_add_to_wishlist( $product_id = false ){
		return YITH_WCWL()->count_add_to_wishlist( $product_id );
	}
}

if( !function_exists( 'yith_wcwl_get_count_text' ) ){
	/**
	 * Returns the label that states how many users added a specific product to wishlist
	 *
	 * @param $product_id int|bool Product id or false, when you want to use global product as reference
	 * @return string Label with count of items
	 */
	function yith_wcwl_get_count_text( $product_id = false ){
		$count = yith_wcwl_count_add_to_wishlist( $product_id );
		$current_user_count = $count ? YITH_WCWL_Wishlist_Factory::get_times_current_user_added_count( $product_id ) : 0;

		// if no user added to wishlist, return empty string
		if( ! $count ){
			return apply_filters( 'yith_wcwl_count_text_empty', '', $product_id );
		}
		elseif( ! $current_user_count ){
			$count_text = sprintf( _n( '%d user', '%d users', $count, 'yith-woocommerce-wishlist' ), $count );
			$text = _n( 'has this item in wishlist', 'have this item in wishlist', $count, 'yith-woocommerce-wishlist' );
		}
		elseif( $count == $current_user_count ){
			$count_text = __( 'You\'re the first', 'yith-woocommerce-wishlist' );
			$text = __( 'to add this item in wishlist', 'yith-woocommerce-wishlist' );
		}
		else {
			$other_count = $count - $current_user_count;
			$count_text = sprintf( _n( 'You and %s user', 'You and %d users', $other_count, 'yith-woocommerce-wishlist' ), $other_count == 1 ? 'another' : $other_count );
			$text = __( 'have this item in wishlist', 'yith-woocommerce-wishlist' );
		}

		$label = sprintf( '<div class="count-add-to-wishlist"><span class="count">%s</span> %s</div>', $count_text, $text );

		return apply_filters( 'yith_wcwl_count_text', $label, $product_id, $current_user_count, $count );
	}
}

if( !function_exists( 'yith_frontend_css_color_picker' ) ) {
	/**
	 * Output a colour picker input box.
	 *
	 * This function is not of the plugin YITH WCWL. It is from WooCommerce.
	 * We redeclare it only because it is needed in the tab "Styles" where it is not available.
	 * The original function name is woocommerce_frontend_css_colorpicker and it is declared in
	 * wp-content/plugins/woocommerce/admin/settings/settings-frontend-styles.php
	 *
	 * @access public
	 * @deprecated
	 * @param mixed $name
	 * @param mixed $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 * @return void
	 */
	function yith_frontend_css_color_picker( $name, $id, $value, $desc = '' ) {
		_deprecated_function( 'yith_frontend_css_color_picker', '3.0.0' );

		$value = ! empty( $value ) ? $value : '#ffffff';

		echo '<div  class="color_box">
				  <table><tr><td>
				  <strong>' . $name . '</strong>
				  <input name="' . esc_attr( $id ). '" id="' . $id . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick colorpickpreview" style="background-color: ' . $value . '" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
				  </td></tr></table>
			  </div>';

	}
}

if( !function_exists( 'yith_wcwl_get_cookie_expiration' ) ){
	function yith_wcwl_get_cookie_expiration(){
		return intval( apply_filters( 'yith_wcwl_cookie_expiration', 60 * 60 * 24 * 30 ) );
	}
}

if( !function_exists( 'yith_setcookie' ) ) {
	/**
	 * Create a cookie.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param int $time
	 * @param bool $secure
	 * @param bool $httponly
	 * @return bool
	 * @since 1.0.0
	 */
	function yith_setcookie( $name, $value = array(), $time = null, $secure = false, $httponly = false ) {
		if( ! apply_filters( 'yith_wcwl_set_cookie', true ) || empty( $name ) ){
			return false;
		}

		$time = $time != null ? $time : time() + yith_wcwl_get_cookie_expiration();

		$value = json_encode( stripslashes_deep( $value ) );
		$expiration = apply_filters( 'yith_wcwl_cookie_expiration_time', $time ); // Default 30 days

		$_COOKIE[ $name ] = $value;
		wc_setcookie( $name, $value, $expiration, $secure, $httponly );

		return true;
	}
}

if( !function_exists( 'yith_getcookie' ) ) {
	/**
	 * Retrieve the value of a cookie.
	 *
	 * @param string $name
	 * @return mixed
	 * @since 1.0.0
	 */
	function yith_getcookie( $name ) {
		if( isset( $_COOKIE[$name] ) ) {
			return json_decode( stripslashes( $_COOKIE[$name] ), true );
		}

		return array();
	}
}

if( !function_exists ( 'yith_destroycookie' ) ) {
	/**
	 * Destroy a cookie.
	 *
	 * @param string $name
	 * @return void
	 * @since 1.0.0
	 */
	function yith_destroycookie( $name ) {
		yith_setcookie( $name, array(), time() - 3600 );
	}
	}

	if( !function_exists( 'yith_wcwl_object_id' ) ){
	/**
	 * Retrieve translated page id, if wpml is installed
	 *
	 * @param $id int Original page id
	 * @return int Translation id
	 * @since 1.0.0
	 */
	function yith_wcwl_object_id( $id ){
		if( function_exists( 'wpml_object_id_filter' ) ){
			return wpml_object_id_filter( $id, 'page', true );
		}
		elseif( function_exists( 'icl_object_id' ) ){
			return icl_object_id( $id, 'page', true );
		}
		else{
			return $id;
		}
	}
}

if( !function_exists( 'yith_wcwl_get_hidden_products' ) ){
	/**
	 * Retrieves a list of hidden products, whatever WC version is running
	 *
	 * WC switched from meta _visibility to product_visibility taxonomy since version 3.0.0,
	 * forcing a split handling (Thank you, WC!)
	 *
	 * @return array List of hidden product ids
	 * @since 2.1.1
	 */
	function yith_wcwl_get_hidden_products(){
		if( version_compare( WC()->version, '3.0.0', '<' ) ){
			$hidden_products = get_posts( array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => 'visible'
					)
				)
			) );
		}
		else{
			$hidden_products = wc_get_products( array(
				'limit' => -1,
				'status' => 'publish',
				'return' => 'ids',
				'visibility' => 'hidden'
			) );
		}

		/**
		 * array_filter was added to prevent errors when previous query returns for some reason just 0 index
		 * @since 2.2.6
		 */
		return apply_filters( 'yith_wcwl_hidden_products', array_filter( $hidden_products ) );
	}
}

if( !function_exists( 'yith_wcwl_get_wishlist' ) ){
	/**
	 * Retrieves wishlist by ID
	 *
	 * @param $wishlist_id int|string Wishlist ID or Wishlist Token
	 * @return \YITH_WCWL_Wishlist|bool Wishlist object; false on error
	 */
	function yith_wcwl_get_wishlist( $wishlist_id ){
		return YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id );
	}
}

if( !function_exists( 'yith_wcwl_merge_in_array' ) ){
	/**
	 * Merges an array of items into a specific position of an array
	 *
	 * @param $array array Origin array
	 * @param $element array Elements to merge
	 * @param $pivot string Index to use as pivot
	 * @param $position string Where elements should be merged (before or after the pivot)
	 */
	function yith_wcwl_merge_in_array( $array, $element, $pivot, $position = 'after' ){
		// search for the pivot inside array
		if( false === $pos = array_search( $pivot, array_keys( $array ) ) ){
			return $array;
		}

		// separate array into chunks
		$i = $position == 'after' ? 1 : 0;
		$part_1 = array_slice( $array, 0, $pos + $i );
		$part_2 = array_slice( $array, $pos + $i );

		return array_merge( $part_1, $element, $part_2 );
	}
}

if( !function_exists( 'yith_wcwl_get_plugin_icons' ) ){
	/**
	 * Return array of available icons
	 *
	 * @param $none_label string Label to use for none option
	 * @param $custom_label string Label to use for custom option
	 *
	 * @return array Array of available icons, in class => name format
	 */
	function yith_wcwl_get_plugin_icons( $none_label = '', $custom_label = '' ){
	    $icons = json_decode( file_get_contents( YITH_WCWL_DIR . 'assets/js/admin/yith-wcwl-icons.json' ), true );

		$icons['none'] = $none_label ? $none_label : __( 'None', 'yith-woocommerce-wishlist' );
		$icons['custom'] = $custom_label ? $custom_label : __( 'Custom', 'yith-woocommerce-wishlist' );

		return $icons;
	}
}

if( !function_exists( 'yith_wcwl_maybe_format_field_array' ) ){
	/**
	 * Take a field structure from plugin saved data, and format it as required by WC to print fields
	 *
	 * @param $field_structure array Array of fields as saved on db
	 * @return array Array of fields as required by WC
	 */
	function yith_wcwl_maybe_format_field_array( $field_structure ){
		$fields = array();

		if( empty( $field_structure ) ){
			return array();
		}

		foreach( $field_structure as $field ) {
			if ( isset( $field['active'] ) && $field['active'] != 'yes' ) {
				continue;
			}

			if ( empty( $field['label'] ) ) {
				continue;
			}

			// format type
			$field_id = sanitize_title_with_dashes( $field['label'] );

			// format options, if needed
			if ( ! empty( $field['options'] ) ) {
				$options     = array();
				$raw_options = explode( '|', $field['options'] );

				if ( ! empty( $raw_options ) ) {
					foreach ( $raw_options as $raw_option ) {
						if( strpos( $raw_option, '::' ) === false ){
							continue;
						}

						list( $id, $value ) = explode( '::', $raw_option );
						$options[ $id ] = $value;
					}
				}

				$field['options'] = $options;
			}

			// format class
			$field['class'] = array( 'form-row-' . $field['position'] );

			// format requires
			$field['required'] = isset( $field['required'] ) && 'yes' == $field['required'];

			// set custom attributes when field is required
			if ( $field['required'] ) {
				$field['custom_attributes'] = array(
					'required' => 'required'
				);
			}

			// if type requires options, but no options was defined, skip field printing
			if ( in_array( $field['type'], array( 'select', 'radio' ) ) && empty( $field['options'] ) ) {
				continue;
			}

			$fields[ $field_id ] = $field;
		}

		return $fields;
	}
}

if( !function_exists( 'yith_wcwl_get_privacy_label' ) ){
	/**
	 * Returns privacy label
	 *
	 * @param $privacy int Privacy value
	 * @param $extended bool Whether to show extended or simplified label
	 * @return string Privacy label
	 * @since 3.0.0
	 */
	function yith_wcwl_get_privacy_label( $privacy, $extended = false ){

		switch( $privacy ){
			case 1:
				$privacy_label = 'shared';
				$privacy_text = __( 'Shared', 'yith-woocommerce-wishlist' );

				if( $extended ){
					$privacy_text = '<b>' . $privacy_text . '</b> - ';
                    $privacy_text .= __( 'Only people with a link to this list can see it', 'yith-woocommerce-wishlist' );
				}

				break;
			case 2:
				$privacy_label = 'private';
				$privacy_text = __( 'Private', 'yith-woocommerce-wishlist' );

				if( $extended ){
					$privacy_text = '<b>' . $privacy_text . '</b> - ';
                    $privacy_text .= __( 'Only you can see this list', 'yith-woocommerce-wishlist' );
				}

				break;
			default:
				$privacy_label = 'public';
				$privacy_text = __( 'Public', 'yith-woocommerce-wishlist' );

				if( $extended ){
					$privacy_text = '<b>' . $privacy_text . '</b> - ';
					$privacy_text .= __( 'Anyone can search for and see this list', 'yith-woocommerce-wishlist' );
				}

				break;
		}

		return apply_filters( "yith_wcwl_{$privacy_label}_wishlist_visibility", $privacy_text, $extended );
	}
}

if( !function_exists( 'yith_wcwl_get_privacy_value' ) ){
	/**
	 * Returns privacy numeric value
	 *
	 * @param $privacy_label string Privacy label
	 * @return int Privacy value
	 * @since 3.0.0
	 */
	function yith_wcwl_get_privacy_value( $privacy_label ) {

		switch ( $privacy_label ) {
			case 'shared':
				$privacy_value = 1;
				break;
			case 'private':
				$privacy_value = 2;
				break;
			default:
				$privacy_value = 0;
				break;
		}

		return $privacy_value;
	}
}

if( !function_exists( 'yith_wcwl_get_current_url' ) ){
	/**
	 * Retrieves current url
	 *
	 * @return string Current url
	 * @since 3.0.0
	 */
	function yith_wcwl_get_current_url(){
		global $wp;

		return add_query_arg( $wp->query_vars, home_url( $wp->request ) );
	}
}

if( !function_exists( 'yith_wcwl_is_single' ) ){
	/**
	 * Returns true if it finds that you're printing a single product
	 * Should return false in any loop (including the ones inside single product page)
	 *
	 * @return bool Whether you're currently on single product template
	 * @since 3.0.0
	 */
	function yith_wcwl_is_single(){
		return apply_filters( 'yith_wcwl_is_single', is_product() && 'related' != wc_get_loop_prop('name') && ! wc_get_loop_prop( 'is_shortcode' ) );
	}
}

if( !function_exists( 'yith_wcwl_is_mobile' ) ){
	/**
	 * Returns true if we're currently on mobile view
	 *
	 * @return bool Whether you're currently on mobile view
	 * @since 3.0.0
	 */
	function yith_wcwl_is_mobile(){
		global $yith_wcwl_is_mobile;

		return wp_is_mobile() || $yith_wcwl_is_mobile;
	}
}
