<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Email_General.
 *
 * @since 4.0
 */
Class ES_Common {
	/**
	 * Convert email subscribe templates.
	 *
	 * @param string $template Get email subscribe templates.
	 * @param string $name Get subscriber name.
	 * @param string $email Get subscriber email.
	 *
	 * @return string $convert_template
	 *
	 * @since 4.0
	 */
	public static function convert_es_templates( $template, $name, $email, $es_templ_id = 0 ) {
		$convert_template = str_replace( "{{NAME}}", $name, $template );
		$convert_template = str_replace( "{{EMAIL}}", $email, $convert_template );

		return $convert_template;
	}

	/**
	 * Process template body
	 *
	 * @param $content
	 * @param int $tmpl_id
	 * @param int $campaign_id
	 *
	 * @return mixed|string
	 *
	 * @since 4.0.0
	 */
	public static function es_process_template_body( $content, $tmpl_id = 0, $campaign_id = 0 ) {
		$content = convert_smilies( wptexturize( $content ) );

		if ( isset( $GLOBALS['wp_embed'] ) ) {
			$content = $GLOBALS['wp_embed']->autoembed( $content );
		}

		$content             = wpautop( $content );
		$content             = do_shortcode( shortcode_unautop( $content ) );
		$data['content']     = $content;
		$data['tmpl_id']     = $tmpl_id;
		$data['campaign_id'] = $campaign_id;
		$data                = apply_filters( 'es_after_process_template_body', $data );
		$content             = $data['content'];
		//total contacts
		$total_contacts = ES()->contacts_db->count_active_contacts_by_list_id();
		$content        = str_replace( "{{TOTAL-CONTACTS}}", $total_contacts, $content );
		//blog title
		$blog_name = get_option( 'blogname' );
		$content   = str_replace( "{{SITENAME}}", $blog_name, $content );
		// site url
		$site_url = home_url( '/' );
		$content  = str_replace( "{{SITEURL}}", $site_url, $content );

		/*TODO: Enable it once Pre header issue fix
		$meta = ES()->campaigns_db->get_campaign_meta_by_id( $campaign_id );
		$meta['pre_header'] = !empty($meta['pre_header']) ? $meta['pre_header'] : '';
		if( !empty( $meta['pre_header'] )){
			$content = '<span class="es_preheader" style="display: none !important; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">'.$meta['pre_header'].'</span>'.$content;
		}
		*/

		return $content;
	}

	/**
	 * Get Statuses key name map
	 *
	 * @param bool $reverse
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 */
	public static function get_statuses_key_name_map( $reverse = false ) {

		$statuses = array(
			'subscribed'   => __( 'Subscribed', 'email-subscribers' ),
			'unconfirmed'  => __( 'Unconfirmed', 'email-subscribers' ),
			'unsubscribed' => __( 'Unsubscribed', 'email-subscribers' )
		);

		if ( $reverse ) {
			$statuses = array_flip( $statuses );
		}

		return $statuses;
	}

	/**
	 * Prepare Statuses dropdown
	 *
	 * @param string $selected
	 * @param string $default_label
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function prepare_statuses_dropdown_options( $selected = '', $default_label = '' ) {

		if ( empty( $default_label ) ) {
			$default_label = __( 'Select Status', 'email-subscribers' );
		}

		$default_status[0] = $default_label;

		$statuses = self::get_statuses_key_name_map();
		$statuses = array_merge( $default_status, $statuses );

		$dropdown = '';
		foreach ( $statuses as $key => $status ) {
			$dropdown .= "<option value='{$key}'";

			if ( strtolower( $selected ) === strtolower( $key ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$status}</option>";
		}

		return $dropdown;
	}

	/**
	 * Prepare list dropdown
	 *
	 * @param string $selected
	 * @param string $default_label
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function prepare_list_dropdown_options( $selected = '', $default_label = 'Select List' ) {

		$default_option[0] = __( $default_label, 'email-subscribers' );

		$lists = ES()->lists_db->get_list_id_name_map();
		$lists = $default_option + $lists;

		$dropdown = '';
		foreach ( $lists as $key => $list ) {
			$dropdown .= "<option value='{$key}'";

			if ( $selected == $key ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$list}</option>";
		}

		return $dropdown;
	}

	/**
	 * @param string $selected
	 * @param string $default_label
	 *
	 * @return string
	 *
	 * @since 4.2.0
	 * @since 4.3.2 Removed $where condition to find forms
	 */
	public static function prepare_form_dropdown_options( $selected = '', $default_label = 'Select Form' ) {

		$forms = ES()->forms_db->get_id_name_map();

		if ( ! is_null( $default_label ) ) {
			$default_option[0] = __( $default_label, 'email-subscribers' );
			$forms             = $default_option + $forms;
		}

		$dropdown = '';
		foreach ( $forms as $key => $form ) {
			$dropdown .= "<option value='{$key}'";

			if ( $selected == $key ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$form}</option>";
		}

		return $dropdown;
	}

	/**
	 * Generate GUID
	 *
	 * @param int $length
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function generate_guid( $length = 6 ) {

		$str        = 'abcdefghijklmnopqrstuvwxyz';
		$random_str = array();
		for ( $i = 1; $i <= 5; $i ++ ) {
			$random_str[] = substr( str_shuffle( $str ), 0, $length );
		}

		$guid = implode( '-', $random_str );

		return $guid;
	}

	/**
	 * Prepare template dropdown options
	 *
	 * @param string $type
	 * @param string $selected
	 *
	 * @return string
	 */
	public static function prepare_templates_dropdown_options( $type = 'newsletter', $selected = '' ) {

		$default_template_option = new stdClass();

		$default_template_option->ID         = '';
		$default_template_option->post_title = __( 'Select Template', 'email-subscribers' );

		$default_template_option = array( $default_template_option );

		$templates = self::get_templates( $type );

		if ( is_array( $templates ) ) {
			$templates = array_merge( $default_template_option, $templates );
		}


		$dropdown = '';
		foreach ( $templates as $key => $template ) {
			$es_templ_thumbnail = ( ! empty( $template->ID ) ) ? get_the_post_thumbnail( $template->ID, array( '200', '200' ) ) : '<img src="' . ES_PLUGIN_URL . 'images/envelope.png" />';
			$dropdown           .= "<option data-img='" . $es_templ_thumbnail . "' value='{$template->ID}'";

			if ( absint( $selected ) === absint( $template->ID ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$template->post_title}</option>";
		}

		return $dropdown;

	}

	/**
	 * Prepare status dropdown options
	 *
	 * @param $selected
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function prepare_status_dropdown_options( $selected ) {
		$statuses = array(
			'1' => __( 'Active', 'email-subscribers' ),
			'0' => __( 'Inactive', 'email-subscribers' )
		);

		$dropdown = '';
		foreach ( $statuses as $key => $status ) {
			$dropdown .= "<option value='{$key}'";

			if ( strtolower( $selected ) === strtolower( $key ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$status}</option>";
		}

		return $dropdown;
	}

	/**
	 * Get ES Templates
	 *
	 * @param string $type
	 *
	 * @return int[]|WP_Post[]
	 *
	 * @since 4.0.0
	 */
	public static function get_templates( $type = 'newsletter' ) {

		$es_args = array(
			'posts_per_page'   => - 1,
			'post_type'        => 'es_template',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'meta_query'       => array(
				array(
					'key'     => 'es_template_type',
					'value'   => $type,
					'compare' => '='
				)
			)
		);

		$es_templates = get_posts( $es_args );

		return $es_templates;

	}

	/**
	 * Prepare categories checkboxes
	 *
	 * @param array $category_names
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function prepare_categories_html( $category_names = array() ) {
		$categories = get_terms( array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
		) );
		if ( ! is_array( $category_names ) ) {
			$category_names = array();
		}
		$checked_selected = ! in_array( 'All', $category_names ) ? "checked='checked'" : '';
		$category_html    = '<tr><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input class="es-note-category-parent" type="radio" ' . $checked_selected . ' value="selected_cat"  name="es_note_cat_parent">' . __( 'Select Categories', 'email-subscribers' ) . '</td></tr>';
		foreach ( $categories as $category ) {

			if ( in_array( $category->term_id, $category_names ) ) {
				$checked = "checked='checked'";
			} else {
				$checked = "";
			}

			$category_html .= '<tr class="es-note-child-category"><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input type="checkbox" ' . $checked . ' value="' . $category->term_id . '" id="es_note_cat[]" name="es_note_cat[]">' . $category->name . '</td></tr>';
		}
		$checked_all = in_array( 'All', $category_names ) ? "checked='checked'" : '';
		$all_html    = '<tr><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input class="es-note-category-parent" type="radio" ' . $checked_all . ' value="{a}All{a}"  name="es_note_cat_parent">' . __( 'All Categories (Also include all categories which will create later)', 'email-subscribers' ) . '</td></tr>';

		return $all_html . $category_html;
	}

	/**
	 * Prepare custom post types checkboxes
	 *
	 * @param $custom_post_types
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function prepare_custom_post_type_checkbox( $custom_post_types ) {
		$args       = array( 'public' => true, 'exclude_from_search' => false, '_builtin' => false );
		$output     = 'names';
		$operator   = 'and';
		$post_types = get_post_types( $args, $output, $operator );
		if ( ! empty( $post_types ) ) {
			$custom_post_type_html = '';
			foreach ( $post_types as $post_type ) {
				$post_type_search = '{T}' . $post_type . '{T}';
				if ( is_array( $custom_post_types ) && in_array( $post_type_search, $custom_post_types ) ) {
					$checked = "checked='checked'";
				} else {
					$checked = "";
				}
				$custom_post_type_html .= '<tr><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input type="checkbox" ' . $checked . ' value="{T}' . $post_type . '{T}" id="es_note_cat[]" class="es_custom_post_type" name="es_note_cat[]">' . $post_type . '</td></tr>';
			}

		} else {
			$custom_post_type_html = '<tr>' . __( 'No Custom Post Types Available', 'email-subscribers' ) . '</tr>';
		}

		return $custom_post_type_html;
	}

	/**
	 * Get Opt-in types
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 */
	public static function get_optin_types() {

		$types = array(
			'single_opt_in' => __( 'Single Opt-In', 'email-subscribers' ),
			'double_opt_in' => __( 'Double Opt-In', 'email-subscribers' )
		);

		return $types;
	}

	/**
	 * Get Image sizes
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 */
	public static function get_image_sizes() {
		$sizes = array(
			'full'      => __( 'Full Size', 'email-subscribers' ),
			'medium'    => __( 'Medium Size', 'email-subscribers' ),
			'thumbnail' => __( 'Thumbnail', 'email-subscribers' )
		);

		return $sizes;
	}

	/**
	 * Get IG Option
	 *
	 * @param $option
	 * @param null $default
	 *
	 * @return mixed|void|null
	 *
	 * @since 4.0.15
	 */
	public static function get_ig_option( $option, $default = null ) {

		if ( empty( $option ) ) {
			return null;
		}

		$option_prefix = 'ig_es_';

		return get_option( $option_prefix . $option, $default );

	}

	/**
	 * Set ig option
	 *
	 * @param $option
	 * @param $value
	 *
	 * @return bool|null
	 *
	 * @since 4.0.15
	 */
	public static function set_ig_option( $option, $value ) {

		if ( empty( $option ) ) {
			return null;
		}

		$option_prefix = 'ig_es_';

		return update_option( $option_prefix . $option, $value, false );

	}

	/**
	 * Delete email subscriber options
	 *
	 * @param string $option
	 *
	 * @return bool|null
	 *
	 * @since 4.0.15
	 */
	public static function delete_ig_option( $option = null ) {
		if ( empty( $option ) ) {
			return null;
		}

		$option_prefix = 'ig_es_';

		return delete_option( $option_prefix . $option );
	}

	/**
	 * Convert categories array to string
	 *
	 * @param array $categories
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function convert_categories_array_to_string( $categories = array() ) {

		$categories_str = '';

		if ( is_array( $categories ) && count( $categories ) > 0 ) {
			$categories_str = "##" . implode( '##', $categories ) . "##";
			$categories_str = wp_specialchars_decode( $categories_str, ENT_QUOTES );
		}

		return $categories_str;
	}

	/**
	 * Convert categories string to array
	 *
	 * @param string $categories_str
	 * @param bool $keep_ids
	 *
	 * @return array|mixed
	 *
	 * @since 4.0.0
	 */
	public static function convert_categories_string_to_array( $categories_str = '', $keep_ids = true ) {
		$categories = array();
		if ( strlen( $categories_str ) > 0 ) {
			$categories_str = trim( trim( $categories_str ), '##' );
			$categories     = explode( '##', $categories_str );
			$categories     = str_replace( '{a}', '', $categories );

			if ( ! $keep_ids ) {
				$categories = array_map( array( 'ES_Common', 'convert_id_to_name' ), $categories );
			}
		}

		return $categories;
	}

	/**
	 * Convert Category id to name
	 *
	 * @param $category
	 *
	 * @return string
	 *
	 * @since 4.1.0
	 */
	public static function convert_id_to_name( $category ) {
		if ( $category != 'All' ) {
			return get_cat_name( $category );
		} else {
			return $category;
		}
	}

	/**
	 * Get category id based on name
	 *
	 * @param $category
	 *
	 * @return int|string
	 *
	 * @since 4.1.0
	 */
	public static function convert_name_to_id( $category ) {
		if ( strpos( $category, '{T}' ) === false ) {
			$category = wp_specialchars_decode( addslashes( $category ) );

			return get_cat_ID( $category );
		} else {
			return $category;
		}
	}

	/**
	 * Prepare categories ids string
	 *
	 * @param string $category
	 *
	 * @return string
	 *
	 * @since 4.1.0
	 */
	public static function prepare_category_string( $category = '' ) {
		$category_str = '';
		if ( ! empty( $category ) ) {
			$category_str = "##" . $category . "##";
		}

		return $category_str;
	}

	/**
	 * Prepare custom post types string
	 *
	 * @param string $post_type
	 *
	 * @return string
	 *
	 * @since 4.1.0
	 */
	public static function prepare_custom_post_type_string( $post_type = '' ) {
		$post_type_str = '';
		if ( ! empty( $post_type ) ) {
			$post_type_str = "##{T}" . $post_type . "{T}##";
		}

		return $post_type_str;
	}

	/**
	 * Convert categories name string into ids string
	 *
	 * @param $categories_str
	 *
	 * @return string
	 *
	 * @since 4.1.0
	 */
	public static function prepare_categories_migration_string( $categories_str ) {
		$categories     = self::convert_categories_string_to_array( $categories_str, true );
		$categories     = array_map( array( 'ES_Common', 'convert_name_to_id' ), $categories );
		$categories_str = self::convert_categories_array_to_string( $categories );

		return $categories_str;

	}

	/**
	 * Prepare first name & last name from name
	 *
	 * @param string $name
	 *
	 * @return array
	 *
	 * @since 4.0.0
	 */
	public static function prepare_first_name_last_name( $name = '' ) {
		$result = array(
			'first_name' => '',
			'last_name'  => ''
		);

		if ( ! empty( $name ) ) {
			// Find out first name and last name
			$name_parts = explode( ' ', $name );
			$last_name  = '';
			if ( count( $name_parts ) > 1 ) {
				$first_name = array_shift( $name_parts );
				$last_name  = implode( ' ', $name_parts );
			} else {
				$first_name = array_shift( $name_parts );
			}

			$result['first_name'] = trim( $first_name );
			$result['last_name']  = trim( $last_name );
		}


		return $result;
	}

	/**
	 * Prepare name from first name & last name
	 *
	 * @param string $first_name
	 * @param string $last_name
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function prepare_name_from_first_name_last_name( $first_name = '', $last_name = '' ) {
		$first_name = trim( $first_name );
		$last_name  = trim( $last_name );

		return trim( $first_name . ' ' . $last_name );
	}

	/**
	 * Get name from email
	 *
	 * @param $email
	 *
	 * @return string
	 *
	 * @since 4.0.0
	 */
	public static function get_name_from_email( $email ) {
		$name = strstr( $email, '@', true );

		return trim( $name );
	}

	/**
	 * Migrate Older wigets into newer one.
	 *
	 * @since 4.0.5
	 */
	public static function migrate_widgets() {
		/**
		 * Option - 1 -> ES < 3.x email-subscribers
		 * Option - 2 -> ES < 4.0.4 email_subscriber_widget
		 * Option - 3 -> ES > 4.0.5 email-subscribers-form
		 *
		 *
		 *   - Fetch Option 1 from options table
		 *   - Create a form
		 *   - Set a new option with Option - 3 (title, form_id)
		 *
		 *   - Fetch Option 2 from options table
		 *   - Create a form with data
		 *   - Set a new option with Option - 3 (title, form_id)
		 *
		 *   - Fetch sidebar_widgets option from options table
		 *   - Change Option 1 with Option 3
		 *   - Change Option 2 with Option 3
		 */
		$es_3_widget_option   = 'widget_email-subscribers';
		$es_4_widget_option   = 'widget_email_subscriber_widget';
		$latest_widget_option = 'widget_email-subscribers-form';

		$es_3_widget_option_data = get_option( $es_3_widget_option, '' );
		if ( ! empty( $es_3_widget_option_data ) ) {
			$es_3_widget_option_data = maybe_unserialize( $es_3_widget_option_data );

			if ( is_array( $es_3_widget_option_data ) ) {
				foreach ( $es_3_widget_option_data as $key => $data ) {
					if ( is_array( $data ) && count( $data ) > 0 && isset( $data['es_title'] ) && isset( $data['es_desc'] ) && isset( $data['es_name'] ) && isset( $data['es_group'] ) ) {
						$title        = ! empty( $data['es_title'] ) ? $data['es_title'] : '';
						$name_visible = ( ! empty( $data['es_name'] ) && 'yes' === strtolower( $data['es_name'] ) ) ? 'yes' : 'no';
						$desc         = ! empty( $data['es_desc'] ) ? $data['es_desc'] : '';
						$group        = ! empty( $data['es_group'] ) ? $data['es_group'] : '';

						$list = ES()->lists_db->get_list_by_name( $group );

						$list_id = 1;
						if ( ! empty( $list ) ) {
							$list_id = $list['id'];
						}

						$name = 'Widget - ' . $title;

						$data = array(
							'name'          => $name,
							'desc'          => $desc,
							'name_visible'  => $name_visible,
							'name_required' => 'no',
							'list_visible'  => 'no',
							'lists'         => array( $list_id ),
							'af_id'         => 0
						);

						$prepared_form_data = ES_Forms_Table::prepare_form_data( $data );

						$inserted_form_id = ES()->forms_db->add_form( $prepared_form_data );

						$data_to_set = array(
							'title'   => $title,
							'form_id' => $inserted_form_id
						);

						$es_3_widget_option_data[ $key ] = $data_to_set;
					}

				}

				update_option( $latest_widget_option, $es_3_widget_option_data );
			}
		}

		$es_4_widget_option_data = get_option( $es_4_widget_option, '' );
		if ( ! empty( $es_4_widget_option_data ) ) {
			$es_4_widget_option_data = maybe_unserialize( $es_4_widget_option_data );

			if ( is_array( $es_4_widget_option_data ) ) {
				foreach ( $es_4_widget_option_data as $key => $data ) {
					if ( is_array( $data ) && count( $data ) > 0 && isset( $data['title'] ) && isset( $data['short_desc'] ) && isset( $data['display_name'] ) && isset( $data['subscribers_group'] ) ) {
						$title        = ! empty( $data['title'] ) ? $data['title'] : '';
						$name_visible = ( ! empty( $data['display_name'] ) && 'yes' === strtolower( $data['display_name'] ) ) ? 'yes' : 'no';
						$desc         = ! empty( $data['short_desc'] ) ? $data['short_desc'] : '';
						$list_id      = ! empty( $data['subscribers_group'] ) ? $data['subscribers_group'] : '';

						if ( empty( $list_id ) ) {
							$list_id = 1;
						}

						$name = 'Widget - ' . $title;

						$data = array(
							'name'          => $name,
							'desc'          => $desc,
							'name_visible'  => $name_visible,
							'name_required' => 'no',
							'list_visible'  => 'no',
							'lists'         => array( $list_id ),
							'af_id'         => 0
						);

						$prepared_form_data = ES_Forms_Table::prepare_form_data( $data );

						$inserted_form_id = ES()->forms_db->add_form( $prepared_form_data );

						$data_to_set = array(
							'title'   => $title,
							'form_id' => $inserted_form_id
						);

						$es_4_widget_option_data[ $key ] = $data_to_set;
					}
				}

				update_option( $latest_widget_option, $es_4_widget_option_data );
			}
		}

		//Update sidebars_widgets options.
		$sidebars_widgets = get_option( 'sidebars_widgets', '' );
		if ( ! empty( $sidebars_widgets ) ) {
			$widgets_data = maybe_unserialize( $sidebars_widgets );

			if ( is_array( $widgets_data ) && count( $widgets_data ) > 0 ) {
				foreach ( $widgets_data as $key => $data ) {
					if ( is_array( $data ) && count( $data ) > 0 ) {
						foreach ( $data as $k => $v ) {
							if ( strstr( $v, 'email-subscribers-' ) ) {
								$v                          = str_replace( 'email-subscribers-', 'email-subscribers-form-', $v );
								$widgets_data[ $key ][ $k ] = $v;
							}

							if ( strstr( $v, 'email_subscriber_widget-' ) ) {
								$v                          = str_replace( 'email_subscriber_widget-', 'email-subscribers-form-', $v );
								$widgets_data[ $key ][ $k ] = $v;
							}
						}
					}
				}

				update_option( 'sidebars_widgets', $widgets_data );
			}

		}

	}

	/**
	 * Filter Category
	 *
	 * @param $category
	 *
	 * @return string
	 *
	 * @sinc 4.1.0
	 */
	public static function temp_filter_category( $category ) {
		return trim( trim( $category ), '#' );
	}

	/**
	 * Show Message
	 *
	 * @param string $message
	 * @param string $status
	 * @param bool $is_dismissible
	 *
	 * @since 4.1.0
	 */
	public static function show_message( $message = '', $status = 'success', $is_dismissible = true ) {

		$class = 'notice notice-success';
		if ( 'error' === $status ) {
			$class = 'notice notice-error';
		}

		if ( $is_dismissible ) {
			$class .= ' is-dismissible';
		}

		echo "<div class='{$class}'><p>{$message}</p></div>";
	}

	/**
	 * Prepare navigation tabs
	 *
	 * @param $navigation_tabs
	 *
	 * @since 4.1.0
	 */
	public static function prepare_main_header_navigation( $navigation_tabs ) {

		foreach ( $navigation_tabs as $key => $navigation ) {
			$action          = ! empty( $navigation['action'] ) ? $navigation['action'] : '';
			$action_label    = ! empty( $navigation['label'] ) ? $navigation['label'] : '';
			$url             = ! empty( $navigation['url'] ) ? $navigation['url'] : '';
			$indicator_class = ! empty( $navigation['indicator_type'] ) ? 'ig-es-indicator-' . $navigation['indicator_type'] : 'ig-es-indicator-new';
			$show_indicator  = ! empty( $navigation['indicator_option'] ) ? ( ( get_option( $navigation['indicator_option'], 'yes' ) === 'yes' ) ? true : false ) : false;
			$indicator_label = ! empty( $navigation['indicator_label'] ) ? $navigation['indicator_label'] : '';
			$is_imp          = ! empty( $navigation['is_imp'] ) ? $navigation['is_imp'] : false;
			?>

            <a href="<?php echo $url; ?>" class="page-title-action<?php if ( $is_imp ) {
				echo " es-imp-button";
			} ?>"><?php echo $action_label; ?>
				<?php if ( $show_indicator ) { ?>
                    <span class="ig-es-indicator <?php echo $indicator_class; ?>">
                                <?php echo $indicator_label ?>
                            </span>

				<?php } ?>
            </a>
		<?php }
	}

	/**
	 * Prepare information box to show different kind of information
	 * info | warnings | success | error
	 *
	 * @param $info
	 * @param $content_html
	 *
	 * @since 4.1.0
	 */
	public static function prepare_information_box( $info, $content_html ) {

		$default_args = array(
			'type'       => 'info',
			'center'     => true,
			'box_shadow' => true,
			'show_icon'  => true
		);

		$info = wp_parse_args( $info, $default_args );

		$type          = $info['type'];
		$show_icon     = $info['show_icon'];
		$is_center     = $info['center'];
		$is_box_shadow = $info['box_shadow'];

		$div_class = 'ig-es-information-box';
		if ( $is_center ) {
			$div_class .= ' ig-es-center';
		}

		if ( $is_box_shadow ) {
			$div_class .= ' ig-es-box-shadow';
		}

		if ( $type ) {
			$div_class .= ' ig-es-' . $type;
		}

		?>

        <div class="<?php echo $div_class; ?>">
            <div class="ig-vertical-align">
				<?php if ( $show_icon ) { ?>
                    <div class="ig-es-icon text-center">
                        <span class="dashicons ig-es-icon-<?php echo $type; ?>"></span>
                    </div>
				<?php } ?>
                <div class="ig-es-info-message">
					<?php echo $content_html; ?>
                </div>
            </div>
        </div>

		<?php
	}

	/**
	 * Render Quick Feedback Widget
	 *
	 * @param $params
	 *
	 * @since 4.1.0
	 */
	public static function render_feedback_widget( $params ) {
		global $ig_es_feedback;

		$feedback = $ig_es_feedback;

		if ( ! $feedback->can_show_feedback_widget() ) {
			return;
		}

		$default_params = array(
			'set_transient' => true,
			'force'         => false,
			'show_once'     => false
		);

		$params = wp_parse_args( $params, $default_params );

		if ( ! empty( $params['event'] ) ) {

			$event = $feedback->event_prefix . $params['event'];
			$force = ! empty( $params['force'] ) ? $params['force'] : false;

			$can_show = false;

			if ( $force ) {
				$can_show = true;
			} else {
				if ( ! $feedback->is_event_transient_set( $event ) ) {
					$can_show = true;

					$feedback_data = $feedback->get_event_feedback_data( $feedback->plugin_abbr, $event );
					if ( count( $feedback_data ) > 0 ) {
						$show_once              = $params['show_once'];
						$feedback_data          = array_reverse( $feedback_data );
						$last_feedback_given_on = $feedback_data[0]['created_on'];

						// If event feedback given within 45 days or show event only once?
						// Don't show now
						if ( $show_once || ( strtotime( $last_feedback_given_on ) > strtotime( '-45 days' ) ) ) {
							$can_show = false;
						}
					}
				}
			}

			if ( $can_show ) {
				if ( 'star' === $params['type'] ) {
					$feedback->render_stars( $params );
				} elseif ( 'emoji' === $params['type'] ) {
					$feedback->render_emoji( $params );
				} elseif ( 'feedback' === $params['type'] ) {
					$feedback->render_general_feedback( $params );
				} elseif ( 'fb' === $params['type'] ) {
					/**
					 * We are not calling home for this event and we want to show
					 * this Widget only once. So, we are storing feedback data now.
					 */
					$feedback->set_feedback_data( 'ig_es', $event );
					$feedback->render_fb_widget( $params );
				} elseif ( 'poll' === $params['type'] ) {
					$feedback->set_feedback_data( 'ig_es', $event );
					$feedback->render_poll_widget( $params );
				}
			}
		}

	}

	/**
	 * Get all ES settings
	 *
	 * @return array
	 *
	 * @since 4.1.0
	 */
	public static function get_all_settings() {

		global $wpdb;

		$query = "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'ig_es_%' AND option_name != 'ig_es_managed_blocked_domains' ";

		$results = $wpdb->get_results( $query, ARRAY_A );

		$options_name_value_map = array();
		if ( count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$options_name_value_map[ $result['option_name'] ] = $result['option_value'];
			}
		}

		return $options_name_value_map;
	}

	/**
	 * Get plugin meta info
	 *
	 * @return array
	 *
	 * @since 4.1.0
	 */
	public static function get_ig_es_meta_info() {

		$total_contacts           = ES()->contacts_db->count();
		$total_lists              = ES()->lists_db->count_lists();
		$total_newsletters        = ES()->campaigns_db->get_total_newsletters();
		$total_post_notifications = ES()->campaigns_db->get_total_post_notifications();

		$meta_info = array(
			'total_contacts'           => $total_contacts,
			'total_lists'              => $total_lists,
			'total_newsletters'        => $total_newsletters,
			'total_post_notifications' => $total_post_notifications,
			'settings'                 => self::get_all_settings()
		);

		return $meta_info;
	}

	/**
	 * Get coma(,) separated lists name based on list ids
	 *
	 * @param array $list_ids
	 *
	 * @return string
	 *
	 * @since 4.1.13
	 */
	public static function prepare_list_name_by_ids( $list_ids = array() ) {
		$list_name = '';
		if ( is_array( $list_ids ) && count( $list_ids ) > 0 ) {
			$lists_id_name_map = ES()->lists_db->get_list_id_name_map();
			$lists_name        = array();
			foreach ( $list_ids as $list_id ) {
				if ( ! empty( $lists_id_name_map[ $list_id ] ) ) {
					$lists_name[] = $lists_id_name_map[ $list_id ];
				}
			}

			$list_name = implode( ', ', $lists_name );
		}

		return $list_name;
	}

	/**
	 * Update Total Email Sent count
	 *
	 * @since 4.1.15
	 */
	public static function update_total_email_sent_count() {

		$current_date = ig_es_get_current_date();
		$current_hour = ig_es_get_current_hour();

		$email_sent_data_option = 'email_sent_data';

		//Get total emails sent in this hour
		$email_sent_data = ES_Common::get_ig_option( $email_sent_data_option, array() );

		$total_emails_sent = 0;
		$data              = array();
		if ( is_array( $email_sent_data ) && ! empty( $email_sent_data[ $current_date ] ) && ! empty( $email_sent_data[ $current_date ][ $current_hour ] ) ) {
			$total_emails_sent = $email_sent_data[ $current_date ][ $current_hour ];
		}

		$total_emails_sent += 1;
		// We want to store only current hour data.
		$data[ $current_date ][ $current_hour ] = $total_emails_sent;

		ES_Common::set_ig_option( $email_sent_data_option, $data );

	}

	/**
	 * Check whether user has access to this page
	 *
	 * @param $page
	 *
	 * @return bool|mixed|void
	 *
	 * @since 4.2.3
	 */
	public static function ig_es_can_access( $page ) {

		$user = wp_get_current_user();

		if ( ! $user->exists() ) {
			return false;
		}

		$default_permission = 'manage_options';

		$can_access = $user->has_cap( $default_permission );

		// Is Admin? Have full access
		if ( $can_access ) {
			return true;
		}

		// We are using this filter in ES Premium to check permission
		return apply_filters( 'ig_es_can_access', $can_access, $page );

	}

	/**
	 * Get accessible submenus
	 *
	 * @return array|mixed|void
	 *
	 * @since 4.2.3
	 */
	public static function ig_es_get_accessible_sub_menus() {

		$sub_menus = array();

		$user = wp_get_current_user();

		if ( ! $user->exists() ) {
			return $sub_menus;
		}

		$default_permission = 'manage_options';
		$is_administrator   = $user->has_cap( $default_permission );

		// Is user administrator? User has access to all submenus
		if ( $is_administrator ) {
			$sub_menus = array(
				'dashboard',
				'audience',
				'reports',
				'forms',
				'campaigns',
				'sequences',
				'settings'
			);

			return $sub_menus;
		}

		// We are using this in ES Premium
		$sub_menus = apply_filters( 'ig_es_accessible_sub_menus', $sub_menus );

		return array_unique( $sub_menus );
	}

	/**
	 * Generate Hash
	 *
	 * @param $length
	 *
	 * @return false|string
	 *
	 * @since 4.2.4
	 */
	public static function generate_hash( $length ) {

		$length = ( $length ) ? $length : 12;

		return substr( md5( uniqid() . uniqid() . wp_rand( $length, 64 ) ), 0, $length );
	}
}
