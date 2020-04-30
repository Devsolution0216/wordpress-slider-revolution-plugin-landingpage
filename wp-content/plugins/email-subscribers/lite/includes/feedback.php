<?php

/**
 * Get additional system & plugin specific information for feedback
 *
 */
if ( ! function_exists( 'ig_es_get_additional_info' ) ) {

	function ig_es_get_additional_info( $additional_info, $system_info = false ) {
		global $ig_es_tracker;

		$additional_info['version'] = ES_PLUGIN_VERSION;

		if ( $system_info ) {

			$additional_info['active_plugins']   = implode( ', ', $ig_es_tracker::get_active_plugins() );
			$additional_info['inactive_plugins'] = implode( ', ', $ig_es_tracker::get_inactive_plugins() );
			$additional_info['current_theme']    = $ig_es_tracker::get_current_theme_info();
			$additional_info['wp_info']          = $ig_es_tracker::get_wp_info();
			$additional_info['server_info']      = $ig_es_tracker::get_server_info();

			// ES Specific information
			$additional_info['plugin_meta_info'] = ES_Common::get_ig_es_meta_info();
		}

		return $additional_info;
	}
}

add_filter( 'ig_es_additional_feedback_meta_info', 'ig_es_get_additional_info', 10, 2 );

/**
 * Render general feedback on click of "Feedback" button from ES sidebar
 */
function ig_es_render_general_feedback_widget() {

	if ( is_admin() ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! ES()->is_es_admin_screen() ) {
			return;
		}

		$event = 'plugin.feedback';

		$params = array(
			'type'              => 'feedback',
			'event'             => $event,
			'title'             => "Have feedback or question for us?",
			'position'          => 'center',
			'width'             => 700,
			'force'             => true,
			'confirmButtonText' => __( 'Send', 'email-subscribers' ),
			'consent_text'      => __( 'Allow Email Subscribers to track plugin usage. It will help us to understand your issue better. We guarantee no sensitive data is collected.', 'email-subscribers' ),
			'name'              => ''
		);

		ES_Common::render_feedback_widget( $params );
	}
}

add_action( 'admin_footer', 'ig_es_render_general_feedback_widget' );

/**
 * Render Broadcast Created feedback widget.
 *
 * @since 4.1.14
 */
function ig_es_render_broadcast_created_feedback_widget() {

	$event = 'broadcast.created';

	$params = array(
		'type'              => 'emoji',
		'event'             => $event,
		'title'             => "How's your experience sending broadcast?",
		'position'          => 'top-end',
		'width'             => 300,
		'delay'             => 2, // seconds
		'confirmButtonText' => __( 'Send', 'email-subscribers' )
	);

	ES_Common::render_feedback_widget( $params );
}

add_action( 'ig_es_broadcast_created', 'ig_es_render_broadcast_created_feedback_widget' );

/**
 * Render Broadcast Created feedback widget.
 *
 * @since 4.1.14
 */
function ig_es_render_fb_widget() {

	if ( is_admin() ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! ES()->is_es_admin_screen() ) {
			return;
		}

		$total_contacts = ES()->contacts_db->count();

		// Got 25 contacts?
		// It's time to Join Email Subscribers Secret Club on Facebook
		if ( $total_contacts >= 25 ) {

			$event = 'join.fb';

			$params = array(
				'type'              => 'fb',
				'title'             => __( 'Not a member yet?', 'email-subscribers' ),
				'event'             => $event,
				'html'              => '<div style="text-align:center;"> ' . __( 'Join', 'email-subscribers' ) . '<strong> ' . __( 'Email Subscribers Secret Club', 'email-subscribers' ) . '</strong> ' . __( 'on Facebook', 'email-subscribers' ) . '</div>',
				'position'          => 'bottom-center',
				'width'             => 500,
				'delay'             => 2, // seconds
				'confirmButtonText' => '<i class="dashicons dashicons-es dashicons-facebook"></i> ' . __( 'Join Now', 'email-subscribers' ),
				'show_once'         => true
			);

			ES_Common::render_feedback_widget( $params );
		}
	}
}

add_action( 'admin_footer', 'ig_es_render_fb_widget' );

if ( ! function_exists( 'ig_es_review_message_data' ) ) {
	/**
	 * Filter 5 star review data
	 *
	 * @param $review_data
	 *
	 * @return mixed
	 *
	 * @since 4.3.8
	 */
	function ig_es_review_message_data( $review_data ) {

		$review_url = 'https://wordpress.org/support/plugin/email-subscribers/reviews/';
		$icon_url   = ES_PLUGIN_URL . 'lite/admin/images/icon-64.png';
		$message    = __( "<span><p>We hope you're enjoying <b>Email Subscribers</b> plugin! Could you please do us a BIG favor and give us a 5-star rating on WordPress to help us spread the word and boost our motivation?</p>", 'temporary-login-without-password' );

		$review_data['review_url'] = $review_url;
		$review_data['icon_url']   = $icon_url;
		$review_data['message']    = $message;

		return $review_data;
	}
}

add_filter( 'ig_es_review_message_data', 'ig_es_review_message_data', 10 );

if ( ! function_exists( 'ig_es_can_ask_user_for_review' ) ) {
	/**
	 * Can we ask user for 5 star review?
	 *
	 * @return bool
	 *
	 * @since 4.3.8
	 */
	function ig_es_can_ask_user_for_review( $enable, $review_data ) {

		if ( $enable ) {

			if ( ! ES()->is_es_admin_screen() ) {
				return false;
			}

			$total_contacts   = ES()->contacts_db->count_active_contacts_by_list_id();
			$total_email_sent = ES_DB_Mailing_Queue::get_notifications_count();

			// Don't show if - less than 3 post notifications or Newsletters sent OR less than 10 subscribers
			if ( $total_contacts < 10 && $total_email_sent < 3 ) {
				return false;
			}
		}

		return $enable;
	}
}

add_filter( 'ig_es_can_ask_user_for_review', 'ig_es_can_ask_user_for_review', 10, 2 );

/**
 * Render Icegram-Email Subscribers merge feedback widget.
 *
 * @since 4.3.13
 */
function ig_es_render_iges_merge_feedback() {

	global $ig_es_feedback;

	if ( is_admin() ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! ES()->is_es_admin_screen() ) {
			return;
		}

		$total_contacts = ES()->contacts_db->count_active_contacts_by_list_id();

		if ( $total_contacts >= 5 ) {

			$event = 'poll.merge_iges';

			// If user has already given feedback on Icegram page, don't ask them again
			$is_event_tracked = $ig_es_feedback->is_event_tracked( 'ig', $event );

			if ( $is_event_tracked ) {
				return;
			}


			$params = array(
				'type'              => 'poll',
				'title'             => __( 'Subscription forms and CTAs??', 'email-subscribers' ),
				'event'             => $event,
				'desc'              => '<div><p>You use <a href="https://wordpress.org/plugins/email-subscribers" target="_blank"><b>Email Subscribers</b></a> to send email campaigns.</p> <p>Would you like us to include onsite popups and action bars in the plugin as well? This way you can <b>convert visitors to subscribers, drive traffic and run email marketing from a single plugin</b>.</p> <p>Why do we ask?</p> <p>Our <a href="https://wordpress.org/plugins/icegram" target="_blank"><b>Icegram</b></a> plugin already does onsite campaigns. We are thinking of merging Icegram & Email Subscribers into a single plugin.</p> <p><b>Will a comprehensive ConvertKit / MailChimp like email + onsite campaign plugin be useful to you?</b></p> </div>',
				'poll_options'      => array(
					'yes' => array( 'text' => '<b>' . __( 'Yes', 'email-subscribers' ) . '</b>', 'color' => 'green' ),
					'no'  => array( 'text' => '<b>' . __( 'No', 'email-subscribers' ) . '</b>', 'color' => 'red' )
				),
				'allow_multiple'    => false,
				'position'          => 'bottom-center',
				'width'             => 400,
				'delay'             => 2, // seconds
				'confirmButtonText' => __( 'Send my feedback to <b>Icegram team</b>', 'email-subscribers' ),
				'show_once'         => true,
			);

			ES_Common::render_feedback_widget( $params );
		}

	}
}

add_action( 'admin_footer', 'ig_es_render_iges_merge_feedback' );

/**
 * Can load sweetalert js file
 *
 * @param bool $load
 *
 * @return bool
 *
 * @since 4.3.13
 */
function ig_es_can_load_sweetalert_js( $load = false ) {

	if ( ES()->is_es_admin_screen() ) {
		return true;
	}

	return $load;
}

add_filter( 'ig_es_can_load_sweetalert_js', 'ig_es_can_load_sweetalert_js', 10, 1 );