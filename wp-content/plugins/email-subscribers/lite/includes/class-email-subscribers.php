<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Email_Subscribers' ) ) {

	/**
	 * The file that defines the core plugin class
	 *
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @link       http://example.com
	 * @since      4.0
	 *
	 * @package    Email_Subscribers
	 * @subpackage Email_Subscribers/includes
	 */

	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      4.0
	 * @package    Email_Subscribers
	 * @subpackage Email_Subscribers/includes
	 * @author     Your Name <email@example.com>
	 */
	class Email_Subscribers {
		/**
		 *
		 * @since 4.2.1
		 *
		 * @var Email_Subscribers The one true Email_Subscribers
		 *
		 */
		private static $instance;

		/**
		 * ES_Queue object
		 *
		 * @since 4.2.1
		 * @var object|ES_Queue
		 *
		 */
		public $queue;

		/**
		 * ES_DB_Queue object
		 *
		 * @since 4.2.1
		 * @var object|ES_DB_Queue
		 *
		 */
		public $queue_db;
		/**
		 * ES_Actions object
		 *
		 * @since 4.2.1
		 * @var object|ES_Actions
		 *
		 */
		public $actions;

		/**
		 * ES_Cron object
		 *
		 * @since 4.3.1
		 * @var object|ES_Cron
		 *
		 */
		public $cron;

		/**
		 * ES_Compatibility object
		 *
		 * @since 4.3.9
		 * @var object|ES_Compatibility
		 *
		 */
		public $compatibiloty;

		/**
		 * ES_DB_Actions object
		 *
		 * @since 4.2.1
		 * @var object|ES_DB_Actions
		 *
		 */
		public $actions_db;

		/**
		 * Feedback object
		 *
		 * @since 4.2.1
		 *
		 * @var $feedback
		 *
		 */
		public $feedback;

		/**
		 * Tracker Object
		 *
		 * @since 4.2.1
		 *
		 * @var $tracker
		 *
		 */
		public $tracker;

		/**
		 * @since 4.2.1
		 *
		 * @var object|ES_Campaigns_Table
		 */
		public $campaigns;

		/**
		 * ES_DB_Campaigns object
		 *
		 * @since 4.2.1
		 *
		 * @var object|ES_DB_Campaigns
		 *
		 */
		public $campaigns_db;

		/**
		 * @since 4.2.1
		 * @var object|ES_Lists_Table
		 *
		 */
		public $lists;

		/**
		 * @since 4.2.1
		 *
		 * @var object|ES_DB_Lists
		 *
		 */
		public $lists_db;

		/**
		 * @since 4.2.1
		 * @var object|ES_Forms_Table
		 *
		 */
		public $forms;

		/**
		 * @since 4.2.1
		 *
		 * @var object|ES_DB_Forms
		 */
		public $forms_db;

		/**
		 * @since 4.2.1
		 *
		 * @var object|ES_Contacts_Table
		 */
		public $contacts;

		/**
		 * @since 4.2.1
		 *
		 * @var object|ES_DB_Contacts
		 */
		public $contacts_db;

		/**
		 * @since 4.2.2
		 *
		 * @var object|ES_DB_Blocked_Emails
		 */
		public $blocked_emails_db;

		/**
		 * @since 4.2.4
		 *
		 * @var object|ES_DB_Links
		 */
		public $links_db;

		/**
		 * @since 4.3.5
		 *
		 * @var object|ES_DB_Lists_Contacts
		 */
		public $lists_contacts_db;

		/**
		 *
		 * @since 4.2.1
		 *
		 * @var object|ES_Integrations
		 *
		 */
		public $integrations;

		/**
		 * @since 4.2.1
		 *
		 * @var object|IG_Logger
		 *
		 */
		public $logger;

		/**
		 * @since 4.3.1
		 *
		 * @var object|ES_Mailer
		 */
		public $mailer;

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    4.0
		 * @access   protected
		 * @var      Email_Subscribers_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    4.0
		 * @access   protected
		 * @var      string $email_subscribers The string used to uniquely identify this plugin.
		 */
		protected $email_subscribers;

		/**
		 * The current version of the plugin.
		 *
		 * @since    4.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    4.0
		 */
		public function __construct() {

		}

		/**
		 * Add Admin Notices
		 *
		 * @since 4.0.0
		 */
		public function add_admin_notice() {
			global $ig_es_tracker;

			$active_plugins = $ig_es_tracker::get_active_plugins();

			$args['url']     = 'https://www.icegram.com/';
			$args['include'] = ES_PLUGIN_DIR . 'lite/includes/notices/views/ig-es-offer.php';
			ES_Admin_Notices::add_custom_notice( 'bfcm_2019', $args );

			$screen_id = $this->get_current_screen_id();
			// Don't show admin notices on Dashboard if onboarding is not yet completed.
			$is_onboarding_complete = get_option( 'ig_es_onboarding_complete', false );

			// We don't have ig_es_onboarding_complete option if somebody is migrating from older version
			if ( ( 'toplevel_page_es_dashboard' === $screen_id ) && ( ! $is_onboarding_complete || $is_onboarding_complete == 'no' ) ) {
				return;
			}

			//cron notice
			$notice_option = get_option( 'ig_es_wp_cron_notice' );

			$show_notice = true;
			$show_notice = apply_filters( 'ig_es_show_wp_cron_notice', $show_notice );

			if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON && $notice_option != 'yes' && $show_notice ) {
				$es_cron_url            = 'https://www.icegram.com/documentation/how-to-enable-the-wordpress-cron/?utm_source=es&utm_medium=in_app&utm_campaign=view_admin_notice';
				$cpanel_url             = 'https://www.icegram.com/documentation/es-how-to-schedule-cron-emails-in-cpanel/?utm_source=es&utm_medium=in_app&utm_campaign=view_admin_notice';
				$es_pro_url             = 'https://www.icegram.com/documentation/es-how-to-schedule-cron-emails-in-cpanel/?utm_source=es&utm_medium=in_app&utm_campaign=view_admin_notice';
				$disable_wp_cron_notice = sprintf( __( 'WordPress Cron is disable on your site. Email notifications from Email Subscribers plugin will not be sent automatically. <a href="%s" target="_blank" >Here\'s how you can enable it.</a>', 'email-subscribers' ), $es_cron_url );
				$disable_wp_cron_notice .= '<br/>' . sprintf( __( 'Or schedule Cron in <a href="%s" target="_blank">cPanel</a>', 'email-subscribers' ), $cpanel_url );
				$disable_wp_cron_notice .= '<br/>' . sprintf( __( 'Or use <strong><a href="%s" target="_blank">Email Subscribers Pro</a></strong> for automatic Cron support', 'email-subscribers' ), $es_pro_url );
				$html                   = '<div class="notice notice-warning" style="background-color: #FFF;"><p style="letter-spacing: 0.6px;">' . $disable_wp_cron_notice . '<a style="float:right" class="es-admin-btn es-admin-btn-secondary " href="' . admin_url() . '?es_dismiss_admin_notice=1&option_name=wp_cron_notice">' . __( 'OK, I Got it!',
						'email-subscribers' ) . '</a></p></div>';
				$args['html']           = $html;
				ES_Admin_Notices::add_custom_notice( 'show_wp_cron', $args );
			}

		}

		/**
		 * Dismiss Admin Notices
		 *
		 * @since 4.0.0
		 */
		public function es_dismiss_admin_notice() {
			$es_dismiss_admin_notice = ig_es_get_request_data( 'es_dismiss_admin_notice' );
			$option_name             = ig_es_get_request_data( 'option_name' );
			if ( $es_dismiss_admin_notice == '1' && ! empty( $option_name ) ) {
				update_option( 'ig_es_' . $option_name, 'yes' );
				if ( in_array( $option_name, array( 'redirect_upsale_notice', 'dismiss_upsale_notice', 'dismiss_star_notice', 'star_notice_done' ) ) ) {
					update_option( 'ig_es_' . $option_name . '_date', ig_get_current_date_time() );
				}
				if ( $option_name === 'star_notice_done' ) {
					header( "Location: https://wordpress.org/support/plugin/email-subscribers/reviews/" );
					exit();
				}
				if ( $option_name === 'redirect_upsale_notice' ) {
					header( "Location: https://www.icegram.com/email-subscribers-starter-plan-pricing/?utm_source=es&utm_medium=es_upsale_banner&utm_campaign=es_upsale" );
					exit();
				}
				if ( $option_name === 'offer_pre_halloween_done_2019' || $option_name === 'offer_halloween_done_2019' || $option_name === 'offer_last_day_halloween_done_2019' ) {
					$url = "https://www.icegram.com/?utm_source=in_app&utm_medium=es_banner&utm_campaign=" . $option_name;
					header( "Location: {$url}" );
					exit();
				} else {
					$referer = wp_get_referer();
					wp_safe_redirect( $referer );
				}
				exit();
			}
		}

		/**
		 * Define Contstants
		 *
		 * @since 4.0.0
		 */
		public function define_constants() {

			global $wpdb;

			$upload_dir = wp_upload_dir( null, false );

			if ( ! defined( 'EMAIL_SUBSCRIBERS_SLUG' ) ) {
				define( 'EMAIL_SUBSCRIBERS_SLUG', 'email-subscribers' );
			}

			if ( ! defined( 'IG_LOG_DIR' ) ) {
				define( 'IG_LOG_DIR', $upload_dir['basedir'] . '/ig-logs/' );
			}

			if ( ! defined( 'EMAIL_SUBSCRIBERS_INCLUDES_DIR' ) ) {
				define( 'EMAIL_SUBSCRIBERS_INCLUDES_DIR', __DIR__ . '/includes' );
			}

			if ( ! defined( 'EMAIL_SUBSCRIBERS_DIR' ) ) {
				define( 'EMAIL_SUBSCRIBERS_DIR', WP_PLUGIN_DIR . '/email-subscribers' );
			}

			if ( ! defined( 'ES_EMAILLIST_TABLE' ) ) {
				define( 'ES_EMAILLIST_TABLE', $wpdb->prefix . 'es_emaillist' );
			}

			if ( ! defined( 'EMAIL_LIST_TABLE' ) ) {
				define( 'EMAIL_LIST_TABLE', $wpdb->prefix . 'es_lists' );
			}
			if ( ! defined( 'EMAIL_SUBSCRIBERS_NOTIFICATION_TABLE' ) ) {
				define( 'EMAIL_SUBSCRIBERS_NOTIFICATION_TABLE', $wpdb->prefix . 'es_notification' );
			}

			if ( ! defined( 'EMAIL_SUBSCRIBERS_STATS_TABLE' ) ) {
				define( 'EMAIL_SUBSCRIBERS_STATS_TABLE', $wpdb->prefix . 'es_deliverreport' );
			}
			if ( ! defined( 'EMAIL_SUBSCRIBERS_SENT_TABLE' ) ) {
				define( 'EMAIL_SUBSCRIBERS_SENT_TABLE', $wpdb->prefix . 'es_sentdetails' );
			}

			if ( ! defined( 'EMAIL_TEMPLATES_TABLE' ) ) {
				define( 'EMAIL_TEMPLATES_TABLE', $wpdb->prefix . 'es_templates' );
			}
			if ( ! defined( 'EMAIL_SUBSCRIBERS_ADVANCED_FORM' ) ) {
				define( 'EMAIL_SUBSCRIBERS_ADVANCED_FORM', $wpdb->prefix . 'es_advanced_form' );
			}
			if ( ! defined( 'EMAIL_SUBSCRIBERS_LIST_MAX' ) ) {
				define( 'EMAIL_SUBSCRIBERS_LIST_MAX', 40 );
			}
			if ( ! defined( 'EMAIL_SUBSCRIBERS_CRON_INTERVAL' ) ) {
				define( 'EMAIL_SUBSCRIBERS_CRON_INTERVAL', 300 );
			}
			if ( ! defined( 'IG_CAMPAIGNS_TABLE' ) ) {
				define( 'IG_CAMPAIGNS_TABLE', $wpdb->prefix . 'ig_campaigns' );
			}
			if ( ! defined( 'IG_CONTACTS_TABLE' ) ) {
				define( 'IG_CONTACTS_TABLE', $wpdb->prefix . 'ig_contacts' );
			}
			if ( ! defined( 'IG_CONTACTS_IPS_TABLE' ) ) {
				define( 'IG_CONTACTS_IPS_TABLE', $wpdb->prefix . 'ig_contacts_ips' );
			}
			if ( ! defined( 'IG_FORMS_TABLE' ) ) {
				define( 'IG_FORMS_TABLE', $wpdb->prefix . 'ig_forms' );
			}
			if ( ! defined( 'IG_LISTS_TABLE' ) ) {
				define( 'IG_LISTS_TABLE', $wpdb->prefix . 'ig_lists' );
			}
			if ( ! defined( 'IG_LISTS_CONTACTS_TABLE' ) ) {
				define( 'IG_LISTS_CONTACTS_TABLE', $wpdb->prefix . 'ig_lists_contacts' );
			}
			if ( ! defined( 'IG_MAILING_QUEUE_TABLE' ) ) {
				define( 'IG_MAILING_QUEUE_TABLE', $wpdb->prefix . 'ig_mailing_queue' );
			}
			if ( ! defined( 'IG_SENDING_QUEUE_TABLE' ) ) {
				define( 'IG_SENDING_QUEUE_TABLE', $wpdb->prefix . 'ig_sending_queue' );
			}
			if ( ! defined( 'IG_BLOCKED_EMAILS_TABLE' ) ) {
				define( 'IG_BLOCKED_EMAILS_TABLE', $wpdb->prefix . 'ig_blocked_emails' );
			}
			if ( ! defined( 'IG_ACTIONS_TABLE' ) ) {
				define( 'IG_ACTIONS_TABLE', $wpdb->prefix . 'ig_actions' );
			}
			if ( ! defined( 'IG_LINKS_TABLE' ) ) {
				define( 'IG_LINKS_TABLE', $wpdb->prefix . 'ig_links' );
			}

			if ( ! defined( 'IG_CONTACT_META_TABLE' ) ) {
				define( 'IG_CONTACT_META_TABLE', $wpdb->prefix . 'ig_contactmeta' );
			}

			if ( ! defined( 'IG_QUEUE_TABLE' ) ) {
				define( 'IG_QUEUE_TABLE', $wpdb->prefix . 'ig_queue' );
			}

			if ( ! defined( 'IG_EMAIL_STATUS_IN_QUEUE' ) ) {
				define( 'IG_EMAIL_STATUS_IN_QUEUE', 'in_queue' );
			}
			if ( ! defined( 'IG_EMAIL_STATUS_SENDING' ) ) {
				define( 'IG_EMAIL_STATUS_SENDING', 'sending' );
			}
			if ( ! defined( 'IG_EMAIL_STATUS_SENT' ) ) {
				define( 'IG_EMAIL_STATUS_SENT', 'sent' );
			}
			if ( ! defined( 'IG_SINGLE_OPTIN' ) ) {
				define( 'IG_SINGLE_OPTIN', 1 );
			}
			if ( ! defined( 'IG_DOUBLE_OPTIN' ) ) {
				define( 'IG_DOUBLE_OPTIN', 2 );
			}
			if ( ! defined( 'IG_CAMPAIGN_TYPE_POST_NOTIFICATION' ) ) {
				define( 'IG_CAMPAIGN_TYPE_POST_NOTIFICATION', 'post_notification' );
			}
			if ( ! defined( 'IG_CAMPAIGN_TYPE_NEWSLETTER' ) ) {
				define( 'IG_CAMPAIGN_TYPE_NEWSLETTER', 'newsletter' );
			}
			if ( ! defined( 'IG_CAMPAIGN_TYPE_POST_DIGEST' ) ) {
				define( 'IG_CAMPAIGN_TYPE_POST_DIGEST', 'post_digest' );
			}
			if ( ! defined( 'IG_CAMPAIGN_TYPE_SEQUENCE' ) ) {
				define( 'IG_CAMPAIGN_TYPE_SEQUENCE', 'sequence' );
			}

			if ( ! defined( 'IG_CAMPAIGN_TYPE_SEQUENCE_MESSAGE' ) ) {
				define( 'IG_CAMPAIGN_TYPE_SEQUENCE_MESSAGE', 'sequence_message' );
			}

			if ( ! defined( 'IG_DEFAULT_BATCH_SIZE' ) ) {
				define( 'IG_DEFAULT_BATCH_SIZE', 100 );
			}
			if ( ! defined( 'IG_MAX_MEMORY_LIMIT' ) ) {
				define( 'IG_MAX_MEMORY_LIMIT', '-1' );
			}
			if ( ! defined( 'IG_SET_TIME_LIMIT' ) ) {
				define( 'IG_SET_TIME_LIMIT', 0 );
			}
			if ( ! defined( 'IG_DEFAULT_LIST' ) ) {
				define( 'IG_DEFAULT_LIST', 'Test' );
			}
			if ( ! defined( 'IG_MAIN_LIST' ) ) {
				define( 'IG_MAIN_LIST', 'Main' );
			}
			if ( ! defined( 'IG_CONTACT_SUBSCRIBE' ) ) {
				define( 'IG_CONTACT_SUBSCRIBE', 1 );
			}
			if ( ! defined( 'IG_MESSAGE_SENT' ) ) {
				define( 'IG_MESSAGE_SENT', 2 );
			}
			if ( ! defined( 'IG_MESSAGE_OPEN' ) ) {
				define( 'IG_MESSAGE_OPEN', 3 );
			}
			if ( ! defined( 'IG_LINK_CLICK' ) ) {
				define( 'IG_LINK_CLICK', 4 );
			}
			if ( ! defined( 'IG_CONTACT_UNSUBSCRIBE' ) ) {
				define( 'IG_CONTACT_UNSUBSCRIBE', 5 );
			}
			if ( ! defined( 'IG_MESSAGE_SOFT_BOUNCE' ) ) {
				define( 'IG_MESSAGE_SOFT_BOUNCE', 6 );
			}
			if ( ! defined( 'IG_MESSAGE_HARD_BOUNCE' ) ) {
				define( 'IG_MESSAGE_HARD_BOUNCE', 7 );
			}
			if ( ! defined( 'IG_MESSAGE_ERROR' ) ) {
				define( 'IG_MESSAGE_ERROR', 8 );
			}
			if ( ! defined( 'IG_ES_CRON_INTERVAL' ) ) {
				define( 'IG_ES_CRON_INTERVAL', 15 * MINUTE_IN_SECONDS );
			}
			if ( ! defined( 'IG_ES_MAX_EMAIL_SEND_AT_ONCE' ) ) {
				define( 'IG_ES_MAX_EMAIL_SEND_AT_ONCE', 30 );
			}


			if ( ! defined( 'IG_ES_CAMPAIGN_STATUS_IN_ACTIVE' ) ) {
				define( 'IG_ES_CAMPAIGN_STATUS_IN_ACTIVE', 0 );
			}

			if ( ! defined( 'IG_ES_CAMPAIGN_STATUS_ACTIVE' ) ) {
				define( 'IG_ES_CAMPAIGN_STATUS_ACTIVE', 1 );
			}

			if ( ! defined( 'IG_ES_CAMPAIGN_STATUS_SCHEDULED' ) ) {
				define( 'IG_ES_CAMPAIGN_STATUS_SCHEDULED', 2 );
			}

			if ( ! defined( 'IG_ES_CAMPAIGN_STATUS_QUEUED' ) ) {
				define( 'IG_ES_CAMPAIGN_STATUS_QUEUED', 3 );
			}

			if ( ! defined( 'IG_ES_CAMPAIGN_STATUS_PAUSED' ) ) {
				define( 'IG_ES_CAMPAIGN_STATUS_PAUSED', 4 );
			}

			if ( ! defined( 'IG_ES_CAMPAIGN_STATUS_FINISHED' ) ) {
				define( 'IG_ES_CAMPAIGN_STATUS_FINISHED', 5 );
			}
		}

		/**
		 * Define Constant
		 *
		 * @param $constant
		 * @param $value
		 *
		 * @since 4.2.0
		 */
		function define( $constant, $value ) {
			if ( ! defined( $constant ) ) {
				define( $constant, $value );
			}
		}


		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Email_Subscribers_Loader. Orchestrates the hooks of the plugin.
		 * - Email_Subscribers_Admin. Defines all hooks for the admin area.
		 * - Email_Subscribers_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    4.0
		 * @access   private
		 */
		private function load_dependencies() {

			$files_to_load = array(
				'lite/includes/class-email-subscribers-loader.php',
				'lite/includes/class-email-subscribers-i18n.php',

				// Logs
				'lite/includes/logs/class-ig-logger-interface.php',
				'lite/includes/logs/class-ig-log-handler-interface.php',
				'lite/includes/logs/class-ig-log-handler.php',
				'lite/includes/logs/log-handlers/class-ig-log-handler-file.php',
				'lite/includes/logs/class-ig-log-levels.php',
				'lite/includes/class-ig-logger.php',

				// Admin Notices
				'lite/includes/notices/class-es-admin-notices.php',

				// Database class files
				'lite/includes/db/class-es-db.php',
				'lite/includes/db/class-es-db-queue.php',
				'lite/includes/db/class-es-db-mailing-queue.php',
				'lite/includes/db/class-es-db-lists.php',
				'lite/includes/db/class-es-db-links.php',
				'lite/includes/db/class-es-db-contacts.php',
				'lite/includes/db/class-es-db-lists-contacts.php',
				'lite/includes/db/class-es-db-sending-queue.php',
				'lite/includes/db/class-es-db-notifications.php',
				'lite/includes/db/class-es-db-campaigns.php',
				'lite/includes/db/class-es-db-forms.php',
				'lite/includes/db/class-es-db-blocked-emails.php',
				'lite/includes/db/class-es-db-actions.php',

				// Mailers
				'lite/includes/mailers/class-es-base-mailer.php',
				'lite/includes/mailers/class-es-pepipost-mailer.php',
				'lite/includes/mailers/class-es-phpmail-mailer.php',
				'lite/includes/mailers/class-es-wpmail-mailer.php',

				// Common Class
				'lite/includes/class-es-common.php',

				// Classes
				'lite/includes/classes/class-es-cache.php',
				'lite/includes/classes/class-es-mailer.php',
				'lite/includes/classes/class-es-message.php',
				'lite/includes/classes/class-es-lists-table.php',
				'lite/includes/classes/class-es-contacts-table.php',
				'lite/includes/classes/class-es-post-notifications.php',
				'lite/includes/classes/class-es-templates-table.php',
				'lite/includes/classes/class-es-campaigns-table.php',
				'lite/includes/classes/class-es-reports-table.php',
				'lite/includes/classes/class-es-reports-data.php',
				'lite/includes/classes/class-es-forms-table.php',
				'lite/includes/classes/class-es-queue.php',
				'lite/includes/classes/class-es-cron.php',
				'lite/includes/classes/class-es-newsletters.php',
				'lite/includes/classes/class-es-tools.php',
				'lite/includes/classes/class-es-admin-settings.php',
				'lite/includes/classes/class-es-widget.php',
				'lite/includes/classes/class-es-old-widget.php',
				'lite/includes/classes/class-es-form-widget.php',
				'lite/includes/classes/class-es-export-subscribers.php',
				'lite/includes/classes/class-es-import-subscribers.php',
				'lite/includes/classes/class-es-info.php',
				'lite/includes/classes/class-es-handle-post-notification.php',
				'lite/includes/classes/class-es-handle-subscription.php',
				'lite/includes/classes/class-es-handle-sync-wp-user.php',
				'lite/includes/classes/class-es-subscription-throttling.php',
				'lite/includes/classes/class-es-actions.php',
				'lite/includes/classes/class-es-tracking.php',
				'lite/includes/classes/class-es-compatibility.php',

				// Core Functions
				'lite/includes/es-core-functions.php',

				// Install/ Update
				'lite/includes/upgrade/es-update-functions.php',
				'lite/includes/class-es-install.php',

				// Public Classes
				'lite/public/class-email-subscribers-public.php',
				'lite/admin/partials/admin-header.php',
				'lite/public/partials/class-es-shortcode.php',

				// Backward Compatibility
				'lite/includes/es-backward.php',
				'lite/admin/class-email-subscribers-admin.php',

				// Pro Feature
				'lite/includes/pro-features.php',

				// Feedback Class
				'lite/includes/feedback/class-ig-tracker.php',
				'lite/includes/feedback/class-ig-feedback.php',
				'lite/includes/feedback.php',

				//Load Starter & Pro files if exists
				'starter/class-es-utils.php',
				'pro/pro-class-email-subscribers.php',
				'starter/starter-class-email-subscribers.php',

				'starter/mailers/class-es-smtp-mailer.php',
			);

			foreach ( $files_to_load as $file ) {
				if ( is_file( ES_PLUGIN_DIR . $file ) ) {
					require_once ES_PLUGIN_DIR . $file;
				}
			}

			add_shortcode( 'email-subscribers', array( 'ES_Shortcode', 'render_es_subscription_shortcode' ) );
			add_shortcode( 'email-subscribers-advanced-form', array( 'ES_Shortcode', 'render_es_advanced_form' ) );
			add_shortcode( 'email-subscribers-form', array( 'ES_Shortcode', 'render_es_form' ) );

			$this->loader = new Email_Subscribers_Loader();

		}

		/**
		 * Set Localization.
		 *
		 * @since   1.0.0
		 */
		private function set_locale() {

			$plugin_i18n = new Email_Subscribers_I18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    4.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new Email_Subscribers_Admin( $this->get_email_subscribers(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'plugins_loaded' );

			$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'save_screen_options', 20, 3 );

			$this->loader->add_action( 'wp_ajax_count_contacts_by_list', $plugin_admin, 'count_contacts_by_list' );
			$this->loader->add_action( 'wp_ajax_get_template_content', $plugin_admin, 'get_template_content' );

			$this->loader->add_action( 'in_admin_header', $plugin_admin, 'remove_other_admin_notices' );
		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    4.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new Email_Subscribers_Public( $this->get_email_subscribers(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'init', $plugin_public, 'es_email_subscribe_init' );
			$this->loader->add_action( 'ig_es_add_contact', $plugin_public, 'add_contact', 10, 2 );
			$this->loader->add_action( 'ig_es_confirm_unsubscription', $plugin_public, 'confirm_unsubscription', 10, 2 );
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    4.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @return    string    The name of the plugin.
		 * @since     4.0
		 */
		public function get_email_subscribers() {
			return $this->email_subscribers;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return    Email_Subscribers_Loader    Orchestrates the hooks of the plugin.
		 * @since     4.0
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @return    string    The version number of the plugin.
		 * @since     4.0
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Is ES PRO?
		 *
		 * @return bool
		 *
		 * @since 4.3.0
		 */
		public function is_pro() {
			return file_exists( ES_PLUGIN_DIR . 'pro/pro-class-email-subscribers.php' );
		}

		/**
		 * Is ES Starter?
		 *
		 * @return bool
		 *
		 * @since 4.3.0
		 */
		public function is_starter() {
			return file_exists( ES_PLUGIN_DIR . 'starter/starter-class-email-subscribers.php' );
		}

		/**
		 * Get all ES admin screens
		 *
		 * @return array|mixed|void
		 *
		 * @since 4.3.8
		 */
		public function get_es_admin_screens() {

			// TODO: Can be updated with a version check when https://core.trac.wordpress.org/ticket/18857 is fixed
			$prefix = sanitize_title( __( 'Email Subscribers', 'email-subscribers' ) );

			$screens = array(
				"es_template",
				"edit-es_template",
				"toplevel_page_es_dashboard",
				"{$prefix}_page_es_subscribers",
				"{$prefix}_page_es_lists",
				"{$prefix}_page_es_forms",
				"{$prefix}_page_es_campaigns",
				"{$prefix}_page_es_newsletters",
				"{$prefix}_page_es_notifications",
				"{$prefix}_page_es_reports",
				"{$prefix}_page_es_settings",
				"{$prefix}_page_es_tools",
				"{$prefix}_page_es_general_information",
				"{$prefix}_page_es_pricing",
				"{$prefix}_page_es_sequence",
			);

			$screens = apply_filters( 'ig_es_admin_screens', $screens );

			return $screens;
		}

		/**
		 * Is es admin screen?
		 *
		 * @return bool
		 *
		 * @since 4.3.8
		 */
		public function is_es_admin_screen() {

			$current_screen_id = $this->get_current_screen_id();

			$es_admin_screens = $this->get_es_admin_screens();

			if ( in_array( $current_screen_id, $es_admin_screens ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Get Current Screen Id
		 *
		 * @return string
		 *
		 * @since 4.3.8
		 */
		public function get_current_screen_id() {

			$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

			if ( ! $current_screen instanceof WP_Screen ) {
				return '';
			}

			$current_screen = get_current_screen();

			return ( $current_screen ? $current_screen->id : '' );
		}

		/**
		 * Register Widget Class
		 *
		 * @since 4.0.0
		 */
		public function register_es_widget() {
			register_widget( 'ES_Form_Widget' );
		}

		/**
		 * Log Fatal Errors on Shutdown
		 *
		 * @since 4.3.1
		 */
		public function log_errors() {
			$error = error_get_last();
			if ( in_array( $error['type'], array( E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR ), true ) ) {
				$logger = get_ig_logger();

				$logger->critical(
					sprintf( __( '%1$s in %2$s on line %3$s', 'email-subscribers' ), $error['message'], $error['file'], $error['line'] ) . PHP_EOL,
					array(
						'source' => 'fatal-errors',
					)
				);

				do_action( 'ig_es_shutdown_error', $error );
			}
		}

		/**
		 * Return a true instance of a class
		 *
		 * @return Email_Subscribers
		 *
		 * @since 4.2.1
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Email_Subscribers ) ) {
				global $ig_es_feedback;

				self::$instance = new Email_Subscribers();

				require_once plugin_dir_path( __FILE__ ) . 'class-email-subscribers-activator.php';
				require_once plugin_dir_path( __FILE__ ) . 'class-email-subscribers-deactivator.php';

				self::$instance->email_subscribers = 'email-subscribers';

				self::$instance->define_constants();
				self::$instance->load_dependencies();
				self::$instance->set_locale();
				self::$instance->define_admin_hooks();
				self::$instance->define_public_hooks();

				self::$instance->logger = get_ig_logger();

				self::$instance->mailer = new ES_Mailer();

				add_action( 'widgets_init', array( self::$instance, 'register_es_widget' ) );

				self::$instance->queue_db          = new ES_DB_Queue();
				self::$instance->actions_db        = new ES_DB_Actions();
				self::$instance->campaigns_db      = new ES_DB_Campaigns();
				self::$instance->lists_db          = new ES_DB_Lists();
				self::$instance->forms_db          = new ES_DB_Forms();
				self::$instance->contacts_db       = new ES_DB_Contacts();
				self::$instance->lists_contacts_db = new ES_DB_Lists_Contacts();
				self::$instance->blocked_emails_db = new ES_DB_Blocked_Emails();
				self::$instance->links_db          = new ES_DB_Links();
				self::$instance->queue             = new ES_Queue();
				self::$instance->actions           = new ES_Actions();
				self::$instance->cron              = new ES_Cron();
				self::$instance->compatibiloty     = new ES_Compatibility();

				if ( is_admin() ) {
					$ig_es_feedback_class = 'IG_Feedback_V_' . str_replace( '.', '_', IG_ES_FEEDBACK_TRACKER_VERSION );

					$name         = 'Email Subscribers';
					$plugin       = 'email-subscribers';
					$plugin_abbr  = 'ig_es';
					$event_prefix = 'esfree.';
					if ( self::$instance->is_pro() ) {
						$name         = 'Email Subscribers PRO';
						$plugin       = 'email-subscribers-newsletters-pro';
						$event_prefix = 'espro.';
					} elseif ( self::$instance->is_starter() ) {
						$name         = 'Email Subscribers Starter';
						$plugin       = 'email-subscribers-newsletters-starter';
						$event_prefix = 'esstarter.';
					}


					$ig_es_feedback = new $ig_es_feedback_class( $name, $plugin, $plugin_abbr, $event_prefix, false );

					$ig_es_feedback->render_deactivate_feedback();
				}

				add_action( 'admin_init', array( self::$instance, 'add_admin_notice' ) );
				// add_action( 'admin_init', array( self::$instance, 'es_dismiss_admin_notice' ) );

				if ( ! post_type_exists( 'es_template' ) ) {
					add_action( 'init', array( 'Email_Subscribers_Activator', 'register_email_templates' ) );
				}

			}

			return self::$instance;
		}
	}
}
