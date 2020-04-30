<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ES_Reports_Data' ) ) {
	/**
	 * Get Reports Data
	 * Class ES_Reports_Data
	 *
	 * @since 4.3.2
	 */
	class ES_Reports_Data {

		/**
		 * Get total Contacts
		 *
		 * @since 4.3.2
		 */
		public static function get_total_contacts() {
			return ES()->contacts_db->get_total_contacts();
		}

		/**
		 * Get total subscribed contacts in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 * @since 4.3.5 Modified ES_DB_Lists_Contacts::get_total_subscribed_contacts to
		 * ES()->lists_contacts_db->get_total_subscribed_contacts
		 * @since 4.3.6 Modified function name from get_subscribed_contacts_count to get_subscribed_contacts_count
		 */
		public static function get_total_subscribed_contacts( $days = 0 ) {
			return ES()->lists_contacts_db->get_subscribed_contacts_count( $days );
		}

		/**
		 * Get total unsubscribed contacts in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 * @since 4.3.5 Modified ES_DB_Lists_Contacts::get_total_unsubscribed_contacts to
		 * ES()->lists_contacts_db->get_total_unsubscribed_contacts
		 * @since 4.3.6 Modified function name from get_total_unsubscribed_contacts to get_unsubscribed_contacts_count
		 */
		public static function get_total_unsubscribed_contacts( $days = 0 ) {
			return ES()->lists_contacts_db->get_unsubscribed_contacts_count( $days );
		}

		/**
		 * Get total contacts have opened emails in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 *
		 * @modify 4.4.0 Now, we are calculating stats from actions table
		 */
		public static function get_total_contacts_opened_emails( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_contacts_opened_message( $days, $distinct );
		}

		/**
		 * Get total contacts have clicked on links in emails in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.3.2
		 *
		 * @modify 4.4.0
		 */
		public static function get_total_contacts_clicks_links( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_contacts_clicks_links( $days, $distinct );
		}

		/**
		 * Get total emails sent in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 * @since 4.4.0
		 */
		public static function get_total_emails_sent( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_emails_sent( $days, $distinct );
		}

		/**
		 * Get total contacts lost in last $days
		 *
		 * @param int $days
		 *
		 * @return int
		 *
		 *
		 */
		public static function get_total_contact_lost( $days = 60, $distinct = true ) {
			return ES()->actions_db->get_total_contact_lost( $days, $distinct );
		}

		/**
		 * Get contacts growth
		 *
		 * @param int $days
		 *
		 * @return array
		 *
		 * @since 4.4.0
		 */
		public static function get_contacts_growth( $days = 60 ) {
			$contacts = ES()->contacts_db->get_total_contacts_by_date();

			$data = array();
			for ( $i = $days; $i >= 0; $i -- ) {
				$date = date( "Y-m-d", strtotime( '-' . $i . ' days' ) );

				$data[ $date ] = 0;
			}

			$data = array_merge( $data, $contacts );

			return $data;
		}

		/**
		 * Collect dashboard reports data
		 *
		 * @return array
		 *
		 * @since 4.4.0
		 */
		public static function get_dashboard_reports_data( $refresh = false ) {

			/**
			 * - Get Total Contacts
			 * - Get Total Forms
			 * - Get Total Lists
			 * - Get Total Campaigns
			 * - Get Last 3 months contacts data
			 * - Total Email Opened in last 60 days
			 * - Total Message Sent in last 60 days
			 * - Avg. Email Click rate
			 */
			$cache_key = 'dashboard_reports_data';

			if ( ! $refresh ) {

				$cached_data = ES_Cache::get_transient( $cache_key );

				if ( ! empty( $cached_data ) ) {
					return $cached_data;
				}
			}

			$total_contacts  = self::get_total_contacts();
			$total_forms     = ES()->forms_db->count_forms();
			$total_lists     = ES()->lists_db->count_lists();
			$total_campaigns = ES()->campaigns_db->get_total_campaigns();

			$total_email_opens  = self::get_total_contacts_opened_emails( 60, false );
			$total_links_clicks = self::get_total_contacts_clicks_links( 60, false );
			$total_message_sent = self::get_total_emails_sent( 60, false );
			$total_contact_lost = self::get_total_contact_lost( 60, false );

			$contacts_growth = self::get_contacts_growth();

			$total_open_rate = $total_click_rate = $total_lost_rate = 0;
			if ( $total_message_sent > 0 ) {
				$total_open_rate  = ( $total_email_opens ) / $total_message_sent;
				$total_click_rate = ( $total_links_clicks ) / $total_message_sent;
				$total_lost_rate  = ( $total_contact_lost ) / $total_message_sent;
			}

			$avg_open_rate = $avg_click_rate = 0;
			if ( $total_message_sent > 0 ) {
				$avg_open_rate  = ( $total_email_opens * 100 ) / $total_message_sent;
				$avg_click_rate = ( $total_links_clicks * 100 ) / $total_message_sent;
			}

			/**
			 * - Get recent 10 campaigns
			 *      - Get total open (3)
			 *      - Get total clicks (4)
			 *      - Get total unsubscribe (5)
			 */

			$data = array();

			$data = apply_filters( 'ig_es_reports_data', $data );

			$reports_data = array(
				'total_contacts'     => $total_contacts,
				'total_lists'        => $total_lists,
				'total_forms'        => $total_forms,
				'total_campaigns'    => $total_campaigns,
				'total_email_opens'  => $total_email_opens,
				'total_message_sent' => $total_message_sent,
				'total_contact_lost' => $total_contact_lost,
				'avg_open_rate'      => number_format( $avg_open_rate, 2 ),
				'avg_click_rate'     => number_format( $avg_click_rate, 2 ),
				'total_open_rate'    => number_format( $total_open_rate, 2 ),
				'total_click_rate'   => $total_click_rate,
				'total_lost_rate'    => $total_lost_rate,
				'contacts_growth'    => $contacts_growth
			);

			$data = array_merge( $data, $reports_data );

			ES_Cache::set_transient( $cache_key, $data, 1 * HOUR_IN_SECONDS );

			return $data;
		}


	}
}
