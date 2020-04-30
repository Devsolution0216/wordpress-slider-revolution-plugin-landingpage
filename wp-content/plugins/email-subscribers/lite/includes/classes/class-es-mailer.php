<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Mailer' ) ) {
	/**
	 * Class ES_Mailer_New
	 *
	 * @since 4.0.0
	 * @since 4.3.2 Modified structure. Made it OOP based
	 */
	class ES_Mailer {
		/**
		 * Store Link data
		 *
		 * @since 4.3.2
		 * @var array
		 *
		 */
		var $link_data = array();

		/**
		 * Is limits set?
		 *
		 * @since 4.3.2
		 * @var bool
		 *
		 */
		var $limits_set = false;

		/**
		 * Max execution time
		 *
		 * @since 4.3.2
		 * @var int
		 *
		 */
		var $time_limit = 0;

		/**
		 * Start time of email sending
		 *
		 * @since 4.3.2
		 * @var int
		 *
		 */
		var $time_start = 0;

		/**
		 * Maximum email send count
		 *
		 * @since 4.3.2
		 * @var int
		 *
		 */
		var $email_limit = 0;

		/**
		 * Keep map of email => id data
		 *
		 * @since 4.3.2
		 * @var array
		 *
		 */
		var $email_id_map = array();

		/**
		 * @since 4.3.2
		 * @var bool
		 *
		 */
		var $add_unsubscribe_link = true;

		/**
		 * @since 4.3.2
		 * @var bool
		 *
		 */
		var $add_tracking_pixel = true;

		/**
		 * Added Logger Context
		 *
		 * @since 4.3.2
		 * @var array
		 *
		 */
		public $logger_context = array(
			'source' => 'ig_es_mailer'
		);

		/**
		 * @since 4.3.2
		 * @var object|ES_Base_Mailer
		 */
		var $mailer;

		/**
		 * ES_Mailer constructor.
		 *
		 * @since 4.3.2
		 */
		public function __construct() {

			$ig_es_mailer_settings = get_option( 'ig_es_mailer_settings', array() );

			$mailer = ! empty( $ig_es_mailer_settings['mailer'] ) ? $ig_es_mailer_settings['mailer'] : 'wpmail';

			$mailer_class = 'ES_' . ucfirst( $mailer ) . '_Mailer';

			// If we don't found mailer class, fallback to WP Mail.
			if ( ! class_exists( $mailer_class ) ) {
				$mailer_class = 'ES_Wpmail_Mailer';
			}

			$this->mailer = new $mailer_class();
		}

		/**
		 * Check whether max execution time reaches to it's maximum or
		 * reached email quota
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function limits_exceeded() {

			if ( ! $this->limits_set ) {

				@set_time_limit( IG_ES_CRON_INTERVAL + 30 );

				// Set 95% of max_execution_time as a max limit. We can reduce it as well
				$max_time = (int) ( @ini_get( 'max_execution_time' ) * 0.95 );
				if ( $max_time == 0 || $max_time > IG_ES_CRON_INTERVAL ) {
					$max_time = (int) ( IG_ES_CRON_INTERVAL * 0.95 );
				}

				$this->time_limit = $this->time_start + $max_time;

				$this->email_limit = $this->get_total_emails_send_now();

				// We are doing heavy lifting..allocate more memory
				if ( function_exists( 'memory_get_usage' ) && ( (int) @ini_get( 'memory_limit' ) < 128 ) ) {
					@ini_set( 'memory_limit', '256M' );
				}

				$this->limits_set = true;
			}

			if ( time() > $this->time_limit ) {
				return true;
			}

			if ( $this->email_limit <= 0 ) {
				return true;
			}

			return false;
		}

		/**
		 * Send Sign up Notifications to admins
		 *
		 * @param $data
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function send_add_new_contact_notification_to_admins( $data ) {

			if ( ! $this->can_send_add_new_contact_notification() ) {
				return false;
			}

			$admin_emails = $this->get_admin_emails();

			if ( ! empty( $admin_emails ) && is_array( $admin_emails ) && count( $admin_emails ) > 0 ) {

				$name  = ! empty( $data['name'] ) ? $data['name'] : '';
				$email = ! empty( $data['email'] ) ? $data['email'] : '';
				$list  = ! empty( $data['list_name'] ) ? $data['list_name'] : '';

				$subject = $this->get_admin_new_contact_email_subject();

				$content = $this->get_admin_new_contact_email_content();

				$content = str_replace( '{{NAME}}', $name, $content );
				$content = str_replace( '{{EMAIL}}', $email, $content );
				$content = str_replace( '{{GROUP}}', '{{LIST}}', $content );
				$content = str_replace( '{{LIST}}', $list, $content );

				$this->add_unsubscribe_link = false;
				$this->add_tracking_pixel   = false;
				$this->send( $subject, $content, $admin_emails, $data );

				return true;
			}

			return false;
		}


		/**
		 * Get new contact email subject
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_admin_new_contact_email_subject() {
			return stripslashes( get_option( 'ig_es_admin_new_contact_email_subject', '' ) );
		}

		/**
		 * Get new contact email content
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_admin_new_contact_email_content() {
			return wpautop( stripslashes( get_option( 'ig_es_admin_new_contact_email_content', '' ) ) );
		}

		/**
		 * Can send add new contact to admin?
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function can_send_add_new_contact_notification() {
			$ig_es_notify_admin = get_option( 'ig_es_notify_admin', 'no' );

			if ( 'yes' === $ig_es_notify_admin ) {
				return true;
			}

			return false;
		}

		/**
		 * Send Double Optin Email
		 *
		 * @param $emails
		 * @param array $merge_tags
		 *
		 * @since 4.3.2
		 */
		public function send_double_optin_email( $emails, $merge_tags = array() ) {

			$subject = $this->get_confirmation_email_subject();
			$content = $this->get_confirmation_email_content();

			if ( empty( $subject ) || empty( $content ) ) {
				return false;
			}

			$content = str_replace( "{{LINK}}", "{{SUBSCRIBE-LINK}}", $content );

			$this->add_unsubscribe_link = false;
			$this->add_tracking_pixel   = false;

			return $this->send( $subject, $content, $emails, $merge_tags );
		}

		/**
		 * Get Confirmation Email Content
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_confirmation_email_content() {
			return wpautop( stripslashes( get_option( 'ig_es_confirmation_mail_content', '' ) ) );
		}

		/**
		 * Get Confirmation Email Subject
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 *
		 * @modify 4.3.12
		 */
		public function get_confirmation_email_subject() {
			$subject = stripslashes( get_option( 'ig_es_confirmation_mail_subject', '' ) );

			if ( empty( $subject ) ) {
				$subject = __( 'Thanks!', 'email-subscribers' );
			}

			return $subject;
		}

		/**
		 * Send Cron Admin Emails
		 *
		 * @param string $notification_guid
		 *
		 * @since 4.3.2
		 */
		public function send_cron_admin_email( $notification_guid = '' ) {

			if ( ! $this->can_send_cron_admin_email() ) {
				return;
			}

			$admin_emails = $this->get_admin_emails();

			if ( ! empty( $admin_emails ) && ! empty( $notification_guid ) && is_array( $admin_emails ) && count( $admin_emails ) > 0 ) {

				$notification = ES_DB_Mailing_Queue::get_notification_by_hash( $notification_guid );

				$subject = $this->get_cron_admin_email_subject();
				$content = $this->get_cron_admin_email_content();

				if ( ! empty( $content ) && isset( $notification['subject'] ) ) {

					$subject = str_replace( '{{SUBJECT}}', $notification['subject'], $subject );

					$email_count  = $notification['count'];
					$post_subject = $notification['subject'];
					$cron_date    = date( 'Y-m-d h:i:s' );

					$content = str_replace( '{{DATE}}', $cron_date, $content );
					$content = str_replace( '{{COUNT}}', $email_count, $content );
					$content = str_replace( '{{SUBJECT}}', $post_subject, $content );

					$this->add_unsubscribe_link = false;
					$this->add_tracking_pixel   = false;

					$this->send( $subject, $content, $admin_emails );
				}
			}
		}

		/**
		 * Get cron admin email subject
		 *
		 * @return mixed|void
		 *
		 * @since 4.3.2
		 */
		public function get_cron_admin_email_subject() {
			return get_option( 'ig_es_cron_admin_email_subject', __( 'Campaign Sent!', 'email-subscribers' ) );
		}

		/**
		 * Get cron admin email content
		 *
		 * @return mixed|void
		 *
		 * @since 4.3.2
		 */
		public function get_cron_admin_email_content() {
			return wpautop( get_option( 'ig_es_cron_admin_email', '' ) );
		}

		/**
		 * Can send cron admin email?
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function can_send_cron_admin_email() {

			$notify_admin = get_option( 'ig_es_enable_cron_admin_email', 'yes' );

			if ( 'yes' === $notify_admin ) {
				return true;
			}

			return false;
		}

		/**
		 * Get admin emails
		 *
		 * @return array
		 *
		 * @since 4.3.2
		 */
		public function get_admin_emails() {

			$admin_email_addresses = get_option( 'ig_es_admin_emails', '' );

			return explode( ',', $admin_email_addresses );
		}

		/**
		 * Send Welcome email after subscription
		 *
		 * @param $email
		 * @param $data
		 *
		 * @since 4.1.13
		 */
		public function send_welcome_email( $email, $data = array() ) {

			if ( $this->can_send_welcome_email() ) {

				// Prepare Welcome Email Subject
				$subject = $this->get_welcome_email_subject();

				// Prepare Welcome Email Content
				$content = $this->get_welcome_email_content();

				// Backward Compatibility...Earlier we used to use {{LINK}} for Unsubscribe link
				$content = str_replace( "{{LINK}}", "{{UNSUBSCRIBE-LINK}}", $content );

				// Don't add Unsubscribe link. It should be there in content
				$this->add_unsubscribe_link = false;
				$this->add_tracking_pixel   = false;
				// Send Email
				$this->send( $subject, $content, $email, $data );
			}

		}

		/**
		 * Get Welcome Email Subject
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_welcome_email_subject() {
			return stripslashes( get_option( 'ig_es_welcome_email_subject', '' ) );
		}

		/**
		 * Get Welcome Email Message
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_welcome_email_content() {
			return wpautop( stripslashes( get_option( 'ig_es_welcome_email_content', '' ) ) );
		}

		/**
		 * Enable Welcome Email?
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function can_send_welcome_email() {
			// Enable Welcome Email?
			$enable_welcome_email = get_option( 'ig_es_enable_welcome_email', 'no' );

			if ( $enable_welcome_email === 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Send Test Email
		 *
		 * @param string $email
		 * @param array $merge_tags
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function send_test_email( $email = '', $subject = '', $content = '', $merge_tags = array() ) {

			if ( empty( $email ) ) {
				return false;
			}

			if ( empty( $subject ) ) {
				$subject = $this->get_test_email_subject();
			}

			if ( empty( $content ) ) {
				$content = $this->get_test_email_content();
			}

			// Disable
			$this->add_unsubscribe_link = false;
			$this->add_tracking_pixel   = false;

			return $this->send( $subject, $content, $email, $merge_tags );
		}

		/**
		 * Get Test Email Subject
		 *
		 * @param string $email
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_test_email_subject( $email = '' ) {
			return 'Email Subscribers: ' . sprintf( esc_html__( 'Test email to %s', 'email-subscribers' ), $email );
		}

		/**
		 * Get test email content
		 *
		 * @return false|string
		 *
		 * @since 4.3.2
		 */
		public function get_test_email_content() {
			ob_start();
			?>
            <html>
            <head></head>
            <body>
            <p>Congrats, test email was sent successfully!</p>
            <p>Thank you for trying out Email Subscribers. We are on a mission to make the best Email Marketing Automation plugin for WordPress.</p>
            <p>If you find this plugin useful, please consider giving us <a href="https://wordpress.org/support/plugin/email-subscribers/reviews/?filter=5">5 stars review</a> on WordPress!</p>
            <p>Nirav Mehta</p>
            <p>Founder, <a href="https://www.icegram.com/">Icegram</a></p>
            </body>
            </html>

			<?php
			$content = ob_get_clean();

			return $content;
		}

		/**
		 * Send Email
		 *
		 * @param $subject
		 * @param $content
		 * @param array $emails
		 * @param array $merge_tags
		 * @param bool $nl2br
		 *
		 * @return mixed
		 *
		 * @since 4.3.2
		 */
		public function send( $subject, $content, $emails = array(), $merge_tags = array(), $nl2br = false ) {

			ignore_user_abort( true );

			$this->time_start = time();
			$message_id       = ! empty( $merge_tags['message_id'] ) ? $merge_tags['message_id'] : 0;
			$campaign_id      = ! empty( $merge_tags['campaign_id'] ) ? $merge_tags['campaign_id'] : 0;

			$subject = $this->prepare_subject( $subject );

			$content = $this->prepare_content( $content, $merge_tags, $nl2br );

			$response = array();

			if ( ! is_array( $emails ) ) {
				$emails = array( $emails );
			}

			if ( 0 === $campaign_id ) {
				$this->email_id_map = ES()->contacts_db->get_email_id_map( (array) $emails );
			} else {
				$this->email_id_map = ES_DB_Sending_Queue::get_emails_id_map_by_campaign( $campaign_id, $emails );
			}

			foreach ( (array) $emails as $email ) {

				// Clean it.
				$email = trim( $email );

				$response['status'] = 'SUCCESS';

				// Don't find contact_id?
				$contact_id = ! empty( $this->email_id_map[ $email ] ) ? $this->email_id_map[ $email ] : 0;

				$merge_tags['contact_id'] = $contact_id;

				$merge_tags = array_merge( $merge_tags, $this->get_contact_merge_tags( $contact_id ) );

				$this->link_data = array(
					'message_id'  => $message_id,
					'campaign_id' => $campaign_id,
					'contact_id'  => $contact_id,
					'email'       => $email,
					'guid'        => ig_es_get_data( $merge_tags, 'hash', '' )
				);

				do_action( 'ig_es_before_message_send', $contact_id, $campaign_id, $message_id );

				$message = $this->build_message( $subject, $content, $email, $merge_tags, $nl2br );

				//object | WP_Error
				$send_response = $this->mailer->send( $message );

				// Error Sending Email?
				if ( is_wp_error( $send_response ) ) {
					$response['status']  = 'ERROR';
					$response['message'] = $send_response->get_error_messages();

					do_action( 'ig_es_email_sending_error', $contact_id, $campaign_id, $message_id, $response );

					//TODO: Log somewhere
				}

				do_action( 'ig_es_message_sent', $contact_id, $campaign_id, $message_id );

				// Reduce Email Sending Limit for this hour
				$this->email_limit --;

				if ( $this->limits_exceeded() ) {
					break;
				}
			}

			return $response;

		}

		/**
		 * Prepare ES_Message object
		 *
		 * @param $subject
		 * @param $body
		 * @param $email
		 * @param array $merge_tags
		 *
		 * @return ES_Message
		 *
		 * @since 4.3.2
		 */
		public function build_message( $subject, $body, $email, $merge_tags = array(), $nl2br = false ) {

			$message = new ES_Message();

			$sender_name  = $this->get_from_name();
			$sender_email = $this->get_from_email();

			$subject = html_entity_decode( $subject, ENT_QUOTES, get_bloginfo( 'charset' ) );

			$message->from      = $sender_email;
			$message->from_name = $sender_name;
			$message->to        = $email;
			$message->subject   = $subject;
			$message->body      = $body;

			$headers = array(
				"From: \"$sender_name\" <$sender_email>",
				"Return-Path: <" . $sender_email . ">",
				"Reply-To: \"" . $sender_name . "\" <" . $sender_email . ">",
				"Content-Type: text/html; charset=\"" . get_bloginfo( 'charset' ) . "\""
			);

			$message->headers = $headers;

			//$email       = ig_es_get_data( $merge_tags, 'email', '' );
			$hash        = ig_es_get_data( $merge_tags, 'hash', '' );
			$campaign_id = ig_es_get_data( $merge_tags, 'campaign_id', 0 );
			$contact_id  = ig_es_get_data( $merge_tags, 'contact_id', 0 );
			$message_id  = ig_es_get_data( $merge_tags, 'message_id', 0 );

			$link_data = array(
				'message_id'  => $message_id,
				'campaign_id' => $campaign_id,
				'contact_id'  => $contact_id,
				'email'       => $email,
				'guid'        => $hash
			);

			$message->body = preg_replace( '/data-json=".*?"/is', '', $message->body );
			$message->body = preg_replace( '/  +/s', ' ', $message->body );

			$message->subject = $this->replace_merge_tags( $message->subject, $merge_tags );

			// Can Track Clicks? Replace Links
			$message->body = $this->replace_links( $message->body, $link_data );

			// Unsubscribe Text
			$unsubscribe_message = $this->get_unsubscribe_text();

			// Can Track Email Open? Add pixel.
			$email_tracking_image = $this->get_tracking_pixel();

			$message->body = $message->body . $unsubscribe_message . $email_tracking_image;

			if ( $nl2br ) {
				$message->body = nl2br( $message->body );
			}

			$message->body = $this->replace_merge_tags( $message->body, $merge_tags );

			$message->body_text = $this->convert_to_text( $message->body );

			/*
			 * TODO: Enable after Fixing preheader issue.
			$campaign_id = ! empty( $merge_tags['campaign_id'] ) ? $merge_tags['campaign_id'] : 0;
			$message->body = $this->set_pre_header_text( $message->body, $campaign_id );
            */

			return $message;
		}

		/**
		 * Set Pre header text
		 *
		 * @param $content
		 * @param int $campaign_id
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function set_pre_header_text( $content, $campaign_id = 0 ) {

			if ( ! empty( $campaign_id ) ) {

				$meta = ES()->campaigns_db->get_campaign_meta_by_id( $campaign_id );

				$pre_header_text = ! empty( $meta['pre_header'] ) ? $meta['pre_header'] : '';

				if ( ! empty( $pre_header_text ) ) {
					$content = '<span class="es_preheader" style="display: none !important; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">' . $pre_header_text . '</span>' . $content;
				}
			}

			return $content;
		}

		/**
		 * Prepare Subject
		 *
		 * @param $subject
		 *
		 * @return mixed
		 *
		 * @since 4.3.2
		 */
		public function prepare_subject( $subject ) {
			return $subject;
		}

		/**
		 * Prepare Content
		 *
		 * @param $content
		 *
		 * @return string|string[]|null
		 *
		 * @since 4.3.2
		 */
		public function prepare_content( $content, $merge_tags = array(), $nl2br = false ) {
			// Convert text equivalent of smilies to images.
			$content = convert_smilies( wptexturize( $content ) );

			if ( isset( $GLOBALS['wp_embed'] ) ) {
				$content = $GLOBALS['wp_embed']->autoembed( $content );
			}

			// Replaces double line-breaks with paragraph elements.
			//$content = wpautop( $content );

			// Have shortcode? Execute it.
			$content = do_shortcode( shortcode_unautop( $content ) );

			// Format Templates.
			$data['content']     = $content;
			$campaign_id         = ! empty( $merge_tags['campaign_id'] ) ? $merge_tags['campaign_id'] : 0;
			$data['tmpl_id']     = ! empty( $campaign_id ) ? ES()->campaigns_db->get_template_id_by_campaign( $campaign_id ) : 0;
			$data['campaign_id'] = $campaign_id;

			$data = apply_filters( 'es_after_process_template_body', $data );

			$content = $data['content'];

			return $content;
		}

		/**
		 * Get contact merge tags
		 *
		 * @param int $contact_id
		 *
		 * @return array
		 *
		 * @since 4.3.2
		 */
		public function get_contact_merge_tags( $contact_id = 0 ) {
			$merge_tags = array();

			if ( 0 != $contact_id ) {
				$contact_details = ES()->contacts_db->get_details_by_ids( array( $contact_id ) );
				if ( is_array( $contact_details ) ) {
					$contact_details = array_shift( $contact_details );

					$first_name = $contact_details['first_name'];
					$last_name  = $contact_details['last_name'];

					$merge_tags['first_name'] = $first_name;
					$merge_tags['last_name']  = $last_name;
					$merge_tags['name']       = ES_Common::prepare_name_from_first_name_last_name( $first_name, $last_name );
					$merge_tags['hash']       = $contact_details['hash'];
					$merge_tags['email']      = $contact_details['email'];
				}
			}

			return $merge_tags;
		}

		/**
		 * Replace Merge Tags
		 *
		 * @param string $content
		 * @param array $merge_tags
		 *
		 * @return mixed|string
		 *
		 * @since 4.3.2
		 */
		public function replace_merge_tags( $content = '', $merge_tags = array() ) {

			$blog_name      = get_option( 'blogname' );
			$total_contacts = ES()->contacts_db->get_total_contacts();
			$site_url       = home_url( '/' );

			$name        = ig_es_get_data( $merge_tags, 'name', '' );
			$first_name  = ig_es_get_data( $merge_tags, 'first_name', '' );
			$last_name   = ig_es_get_data( $merge_tags, 'last_name', '' );
			$list_name   = ig_es_get_data( $merge_tags, 'list_name', '' );
			$hash        = ig_es_get_data( $merge_tags, 'hash', '' );
			$email       = ig_es_get_data( $merge_tags, 'email', '' );
			$contact_id  = ig_es_get_data( $merge_tags, 'contact_id', 0 );
			$campaign_id = ig_es_get_data( $merge_tags, 'campaign_id', 0 );
			$message_id  = ig_es_get_data( $merge_tags, 'message_id', 0 );

			$link_data = array(
				'message_id'  => $message_id,
				'campaign_id' => $campaign_id,
				'contact_id'  => $contact_id,
				'email'       => $email,
				'guid'        => $hash
			);

			$this->link_data = $link_data;

			$subscribe_link   = $this->get_subscribe_link( $link_data );
			$unsubscribe_link = $this->get_unsubscribe_link( $link_data );

			$content = str_replace( "{{NAME}}", $name, $content );
			$content = str_replace( "{{FIRSTNAME}}", $first_name, $content );
			$content = str_replace( "{{LASTNAME}}", $last_name, $content );
			$content = str_replace( "{{EMAIL}}", $email, $content );

			// TODO: This is a quick workaround to handle <a href="{{LINK}}?utm_source=abc" >
			// TODO: Implement some good solution

			$content = str_replace( "{{LINK}}?", "{{LINK}}&", $content );
			$content = str_replace( "{{LINK}}", $subscribe_link, $content );

			$content = str_replace( "{{SUBSCRIBE-LINK}}?", "{{SUBSCRIBE-LINK}}&", $content );
			$content = str_replace( "{{SUBSCRIBE-LINK}}", $subscribe_link, $content );

			$content = str_replace( "{{UNSUBSCRIBE-LINK}}?", "{{UNSUBSCRIBE-LINK}}&", $content );
			$content = str_replace( "{{UNSUBSCRIBE-LINK}}", $unsubscribe_link, $content );

			$content = str_replace( "{{TOTAL-CONTACTS}}", $total_contacts, $content );
			$content = str_replace( "{{GROUP}}", $list_name, $content );
			$content = str_replace( "{{LIST}}", $list_name, $content );
			$content = str_replace( "{{SITENAME}}", $blog_name, $content );
			$content = str_replace( "{{SITEURL}}", $site_url, $content );

			return $content;
		}

		/**
		 * @param $html
		 * @param bool $links_only
		 *
		 * @return mixed|string|string[]|null
		 *
		 * @since 4.0.0
		 * @since 4.3.2
		 */
		public function convert_to_text( $html, $links_only = false ) {

			if ( $links_only ) {
				$links = '/< *a[^>]*href *= *"([^#]*)"[^>]*>(.*)< *\/ *a *>/Uis';
				$text  = preg_replace( $links, '${2} [${1}]', $html );
				$text  = str_replace( array( ' ', '&nbsp;' ), ' ', strip_tags( $text ) );
				$text  = @html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );

				return trim( $text );

			} else {
				require_once ES_PLUGIN_DIR . 'lite/includes/libraries/class-es-html2text.php';
				$htmlconverter = new ES_Html2Text( $html, array( 'width' => 200, 'do_links' => 'table' ) );

				$text = trim( $htmlconverter->get_text() );
				$text = preg_replace( '/\s*$^\s*/mu', "\n\n", $text );
				$text = preg_replace( '/[ \t]+/u', ' ', $text );

				return $text;

			}
		}

		/**
		 * Replace links with tracking link
		 *
		 * @param $content
		 * @param $data
		 *
		 * @return string|string[]|null
		 *
		 * @since 4.2.4
		 * @since 4.3.2
		 */
		public function replace_links( $content = '', $link_data = array() ) {

			if ( $this->can_track_clicks() ) {

				if ( empty( $link_data ) ) {
					$link_data = $this->link_data;
				}

				$link_data['action'] = 'click';

				// get all links from the basecontent
				preg_match_all( '# href=(\'|")?(https?[^\'"]+)(\'|")?#', $content, $links );

				$links = $links[2];

				if ( empty( $links ) ) {
					return $content;
				}

				$inserted_links = array();

				$campaign_id = ! empty( $link_data['campaign_id'] ) ? $link_data['campaign_id'] : 0;
				$message_id  = ! empty( $link_data['message_id'] ) ? $link_data['message_id'] : 0;
				$contact_id  = ! empty( $link_data['contact_id'] ) ? $link_data['contact_id'] : 0;


				foreach ( $links as $link ) {

					if ( ! isset( $inserted_links[ $link ] ) ) {
						$index = 0;
					} else {
						$index = $inserted_links[ $link ] + 1;
					}

					$inserted_links[ $link ] = $index;

					$result = ES()->links_db->get_link_by_campaign_id( $link, $campaign_id, $message_id, $index );

					if ( is_array( $result ) && count( $result ) > 0 ) {
						$hash = $result[0]['hash'];
					} else {

						$hash = ES_Common::generate_hash( 12 );

						$link_data = array(
							'link'        => $link,
							'message_id'  => $message_id,
							'campaign_id' => $campaign_id,
							'hash'        => $hash,
							'i'           => $index
						);

						ES()->links_db->insert( $link_data );
					}

					$data = array(
						'action'    => 'click',
						'link_hash' => $hash,
						'contact_id' => $contact_id
					);

					$new_link = $this->prepare_link( $data );

					$link     = ' href="' . $link . '"';
					$new_link = ' href="' . $new_link . '"';

					if ( ( $pos = strpos( $content, $link ) ) !== false ) {
						$content = preg_replace( '/' . preg_quote( $link, '/' ) . '/', $new_link, $content, 1 );
					}
				}
			}

			return $content;
		}

		/**
		 * Get Sender Name
		 *
		 * @return mixed|string|void
		 *
		 * @since 4.3.2
		 */
		public function get_from_name() {

			$site_title = get_bloginfo();

			$from_name = get_option( 'ig_es_from_name', '' );

			$from_name = ! empty( $from_name ) ? $from_name : $site_title;

			return $from_name;
		}

		/**
		 * Get Sender Email
		 *
		 * @return mixed|void
		 *
		 * @since 4.3.2
		 */
		public function get_from_email() {

			$admin_email = get_option( 'admin_email', '' );

			$from_email = get_option( 'ig_es_from_email', '' );

			$from_email = ! empty( $from_email ) ? $from_email : $admin_email;

			return $from_email;
		}

		/**
		 * Allow click tracking?
		 *
		 * @param int $contact_id
		 * @param int $campaign_id
		 *
		 * @return mixed|void
		 *
		 * @since 4.3.2
		 */
		public function can_track_clicks( $contact_id = 0, $campaign_id = 0 ) {
			$is_track_clicks = false;

			return apply_filters( 'ig_es_track_clicks', $is_track_clicks, $contact_id, $campaign_id );
		}

		/**
		 * Allow tracking email open?
		 *
		 * @param int $contact_id
		 * @param int $campaign_id
		 *
		 * @return bool
		 *
		 * @since 4.3.2
		 */
		public function can_track_open( $contact_id = 0, $campaign_id = 0 ) {

			if ( $this->add_tracking_pixel ) {

				$is_track_email_opens = get_option( 'ig_es_track_email_opens', 'yes' );

				$is_track_email_opens = apply_filters( 'ig_es_track_open', $is_track_email_opens, $contact_id, $campaign_id );

				if ( 'yes' === $is_track_email_opens ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Get Tracking pixel
		 *
		 * @param array $data
		 *
		 * @return string
		 *
		 * @since 4.2.0
		 */
		public function get_tracking_pixel( $link_data = array() ) {

			$tracking_image = '';

			if ( $this->can_track_open() ) {

				if ( empty( $link_data ) ) {
					$link_data = $this->link_data;
				}

				$link_data['action'] = 'open';

				$url = $this->prepare_link( $link_data );

				$tracking_image = "<img src='{$url}' width='1' height='1' alt=''/>";
			}

			return $tracking_image;
		}

		/**
		 * Get link
		 *
		 * @param array $data
		 *
		 * @return string
		 *
		 * @since 4.0.0
		 * @since 4.2.0
		 * @since 4.3.2
		 */
		public function prepare_link( $data = array() ) {

			/**
			 * We are getting different data like action, message_id, campaign_id, contact_id, guid, email etc in $data
			 */
			$action = ! empty( $data['action'] ) ? $data['action'] : '';

			if ( 'subscribe' === $action ) {
				$action = 'optin';
			}

			$link = add_query_arg( 'es', $action, site_url( '/' ) );

			$data = ig_es_encode_request_data( $data );

			$link = add_query_arg( 'hash', $data, $link );

			return $link;
		}

		/**
		 * Get Unsubscribe text
		 *
		 * @param $link_data
		 *
		 * @return mixed|string|void
		 *
		 * @since 4.3.2
		 */
		public function get_unsubscribe_text( $link_data = array() ) {

			$text = '';

			if ( $this->add_unsubscribe_link ) {

				if ( empty( $link_data ) ) {
					$link_data = $this->link_data;
				}

				$unsubscribe_link = $this->get_unsubscribe_link( $link_data );

				$text = get_option( 'ig_es_unsubscribe_link_content', '' );

				$text = stripslashes( $text );
				$text = str_replace( "{{LINK}}", "{{UNSUBSCRIBE-LINK}}", $text );
				$text = str_replace( "{{UNSUBSCRIBE-LINK}}", $unsubscribe_link, $text );
			}

			return $text;
		}

		/**
		 * Get Unsubscribe link
		 *
		 * @param $link_data
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_unsubscribe_link( $link_data = array() ) {
			$link_data['action'] = 'unsubscribe';

			return $this->prepare_link( $link_data );
		}

		/**
		 * Get Subscribe link
		 *
		 * @param $link_data
		 *
		 * @return string
		 *
		 * @since 4.3.2
		 */
		public function get_subscribe_link( $link_data ) {
			$link_data['action'] = 'subscribe';

			return $this->prepare_link( $link_data );
		}

		/**
		 * How many emails we can send now?
		 *
		 * @since 4.3.5
		 */
		public function get_total_emails_send_now( $max_send = 100000 ) {

			$current_date = ig_es_get_current_date();
			$current_hour = ig_es_get_current_hour();

			//Get total emails sent in this hour
			$email_sent_data = ES_Common::get_ig_option( 'email_sent_data', array() );

			$total_emails_sent = 0;
			if ( is_array( $email_sent_data ) && ! empty( $email_sent_data[ $current_date ] ) && ! empty( $email_sent_data[ $current_date ][ $current_hour ] ) ) {
				$total_emails_sent = $email_sent_data[ $current_date ][ $current_hour ];
			}

			// Get hourly limit
			$can_total_emails_send_in_hour = ES_Common::get_ig_option( 'hourly_email_send_limit', 300 );

			// Is limit exceed?
			if ( $total_emails_sent >= $can_total_emails_send_in_hour ) {
				return 0;
			}

			// Still, you can send these many emails.
			$total_emails_can_send_now = $can_total_emails_send_in_hour - $total_emails_sent;

			// We can send more emails but if we get the count, send only those
			if ( ( $max_send > 0 ) && ( $max_send < $total_emails_can_send_now ) ) {
				$total_emails_can_send_now = $max_send;
			}

			// Do we have max email sending limit at once set?
			$can_total_emails_send_at_once = $this->get_max_email_send_at_once_count();

			if ( $can_total_emails_send_at_once < $total_emails_can_send_now ) {
				$total_emails_can_send_now = $can_total_emails_send_at_once;
			}

			return $total_emails_can_send_now;
		}

		/**
		 * Get max email send at once count
		 *
		 * @return int
		 *
		 * @since 4.3.5
		 */
		public function get_max_email_send_at_once_count() {
			$max_count = (int) ES_Common::get_ig_option( 'max_email_send_at_once', IG_ES_MAX_EMAIL_SEND_AT_ONCE );

			if ( $max_count <= 0 ) {
				$max_count = IG_ES_MAX_EMAIL_SEND_AT_ONCE;
			}

			return $max_count;
		}
	}
}