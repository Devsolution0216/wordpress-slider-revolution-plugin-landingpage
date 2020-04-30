<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Admin Settings
 *
 * @package    Email_Subscribers
 * @subpackage Email_Subscribers/admin
 * @author     Your Name <email@example.com>
 */
class ES_Newsletters {

	// class instance
	static $instance;

	// class constructor
	public function __construct() {
		add_filter( 'ig_es_refresh_newsletter_content', array( $this, 'refresh_newsletter_content' ), 10, 2 );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function es_newsletters_settings_callback() {


		$submitted      = ig_es_get_request_data( 'submitted' );
		$preview        = ig_es_get_request_data( 'es_broadcast_preview' );
		$broadcast_data = ig_es_get_request_data( 'broadcast_data', array(), false );
		if ( 'preview' !== $preview ) {
			if ( 'submitted' === $submitted ) {

				// $email_sent_type = __('Active', 'email-subscribers');
				$list_id     = ! empty( $broadcast_data['list_ids'] ) ? $broadcast_data['list_ids'] : '';
				$template_id = ! empty( $broadcast_data['template_id'] ) ? $broadcast_data['template_id'] : '';
				$subject     = ! empty( $broadcast_data['subject'] ) ? $broadcast_data['subject'] : '';
				// $template_id = ig_es_get_request_data( 'ig_es_broadcast_base_template_id' );
				if ( empty( $broadcast_data['body'] ) ) {
					// if ( empty( $template_id) ) {
					$message = __( 'Please add message body or select template', 'email-subscribers' );
					ES_Common::show_message( $message, 'error' );
				} elseif ( empty( $list_id ) ) {
					$message = __( 'Please select list.', 'email-subscribers' );
					ES_Common::show_message( $message, 'error' );
				} elseif ( empty( $subject ) ) {
					$message = __( 'Please add the subject', 'email-subscribers' );
					ES_Common::show_message( $message, 'error' );
				} else {
					$broadcast_data['base_template_id'] = $template_id;
					$broadcast_data['list_ids']         = $list_id;
					$broadcast_data['status']           = 1;
					$meta                               = ! empty( $broadcast_data['meta'] ) ? $broadcast_data['meta'] : array();
					$meta['pre_header']                 = $broadcast_data['pre_header'];
					$broadcast_data['meta']             = maybe_serialize( $meta );
					self::es_send_email_callback( $broadcast_data );

					$reports_url = admin_url( 'admin.php?page=es_reports' );
					$message     = __( sprintf( 'A new broadcast has been created successfully! Contacts from selected list will be notified within an hour. Want to notify now? <a href="%s" target="_blank">Click here</a>', $reports_url ), 'email-subscribers' );

					ES_Common::show_message( $message, 'success' );

					do_action( 'ig_es_broadcast_created' );
					$broadcast_data = array();
				}

			}

			$this->prepare_newsletter_settings_form();
		} elseif ( 'preview' === $preview ) {
			// $broadcast_data = ig_es_get_request_data( 'broadcast_data', array(), false );
			if ( empty( $broadcast_data['body'] ) ) {
				$message = __( 'Please add message content', 'email-subscribers' );
				ES_Common::show_message( $message, 'error' );
				$this->prepare_newsletter_settings_form();
			} else {
				//content validation
				$template_data['content']     = ! empty( $broadcast_data['body'] ) ? $broadcast_data['body'] : '';
				$template_data['template_id'] = ! empty( $broadcast_data['template_id'] ) ? $broadcast_data['template_id'] : '';
				$this->es_broadcast_preview_callback( $template_data );

			}

		}
	}

	public function prepare_newsletter_settings_form() {
		$newsletter_data = array();
		$templates       = ES_Common::prepare_templates_dropdown_options( 'newsletter' );
		$lists           = ES_Common::prepare_list_dropdown_options();
		$from_email      = ES_Common::get_ig_option( 'from_email' );

		?>

        <div class="wrap">
            <h2 class="wp-heading-inline"><?php _e( 'Campaigns > Broadcast', 'email-subscribers' ); ?>
                <a href="admin.php?page=es_campaigns" class="page-title-action"><?php _e( 'Campaigns', 'email-subscribers' ) ?></a>
                <a href="edit.php?post_type=es_template" class="page-title-action es-imp-button"><?php _e( 'Manage Templates', 'email-subscribers' ) ?></a>
            </h2>
            <div class="es-form" style="width: 100%;">
                <form method="post" action="#">
                    <div class="es_newsletters_settings_wrapper">
                        <div class="es_campaign_name_wrapper ">
                            <input placeholder="Add Broadcast name" type="text" class="es_newsletter_name" name="broadcast_data[name]" value="">
                            <input id="ig_es_campaign_submit_button" class="es_primary_btn" type="submit" name="submit" value="Send Broadcast">
                            <input type="hidden" name="submitted" value="submitted">
                        </div>
                        <div class="es_settings_left_pan">
                            <div class="es_settings_field">
                                <label><?php _e( 'From Email', 'email-subscribers' ) ?><br/><input type="email" name="broadcast_data[from_email]" value="<?php echo $from_email; ?>"/></label>
                            </div>
                            <div class="es_settings_field">
                                <label><?php _e( 'Design template', 'email-subscribers' ) ?><br/><select name="broadcast_data[template_id]" id="base_template_id"><?php echo $templates ?></select></label>
                            </div>
                            <div class="es_settings_field"><label><?php _e( 'Subject', 'email-subscribers' ) ?><br/><input type="text" id="ig_es_broadcast_subject" name="broadcast_data[subject]" placeholder="<?php _e( 'New Broadcast', 'email-subscribers' ) ?>"/></label></div>
                            <div class="es_settings_field"><label><?php _e( 'Pre Header', 'email-subscribers' ) ?><br/><input placeholder="<?php _e( 'Add Pre header', 'email-subscribers' ); ?>" type="text" name="broadcast_data[pre_header]"/></label></div>
                            <div class="es_settings_field">
                                <label><?php _e( 'Body', 'email-subscribers' ); ?></label>
								<?php
								$body        = ! empty( $broadcast_data['body'] ) ? $broadcast_data['body'] : '';
								$editor_args = array(
									'textarea_name' => 'broadcast_data[body]',
									'textarea_rows' => 40,
									'editor_class'  => 'wp-editor-content',
									'media_buttons' => true,
									'tinymce'       => true,
									'quicktags'     => true,
									'editor_class'  => 'wp-editor-boradcast'
								);
								wp_editor( $body, 'edit-es-boradcast-body', $editor_args ); ?>
                            </div>
							<?php do_action( 'ig_es_after_broadcast_left_pan_settings' ); ?>
                        </div>
                        <div class="es_settings_right_pan">
                            <div class="es_settings_field">
                                <label><?php _e( 'Recipients', 'email-subscribers' ) ?><br/><select name="broadcast_data[list_ids]" id="ig_es_broadcast_list_ids"><?php echo $lists ?></select></label>
	                            <span class="helper" id="ig_es_total_contacts"></span>
                            </div>
                            <hr>
                            <div class="es_settings_field">
                                <label>
                                    <input class="es_secondary_btn" type="submit" id="ig_es_preview_broadcast" value="<?php _e( 'Preview this email in browser', 'email-subscribers' ) ?>">
                                    <input type="hidden" name="es_broadcast_preview" id="es_broadcast_preview">
                                </label>

                            </div>
							<?php do_action( 'ig_es_after_broadcast_right_pan_settings' ); ?>
                        </div>

                    </div>
                </form>
            </div>
            <div clas="es-preview" style="float: right;width: 19%;">
                <div class="es-templ-img"></div>
            </div>
        </div>

		<?php


	}

	public static function es_send_email_callback( $data ) {

		$list_id = ! empty( $data['list_ids'] ) ? $data['list_ids'] : '';

		$title = get_the_title( $data['base_template_id'] );

		$data['type'] = 'newsletter';
		$data['name'] = ! empty( $data['name'] ) ? $data['name'] : $data['subject'];
		$data['slug'] = sanitize_title( sanitize_text_field( $data['name'] ) );

		$data = apply_filters( 'ig_es_broadcast_data', $data );

		if ( ! empty( $data['body'] ) ) {

			$campaign_id = ES()->campaigns_db->save_campaign( $data );

			$data['body'] = ES_Common::es_process_template_body( $data['body'], $data['base_template_id'], $campaign_id );

			$subscribers = ES()->contacts_db->get_active_contacts_by_list_id( $list_id );

			if ( ! empty( $subscribers ) && count( $subscribers ) > 0 ) {
				$guid = ES_Common::generate_guid( 6 );
				$data = array(
					'hash'        => $guid,
					'campaign_id' => $campaign_id,
					'subject'     => $data['subject'],
					'body'        => $data['body'],
					'count'       => count( $subscribers ),
					'status'      => 'In Queue',
					'start_at'    => ! empty( $data['start_at'] ) ? $data['start_at'] : '',
					'finish_at'   => '',
					'created_at'  => ig_get_current_date_time(),
					'updated_at'  => ig_get_current_date_time(),
					'meta'        => maybe_serialize( array( 'type' => 'newsletter' ) )
				);

				$last_report_id = ES_DB_Mailing_Queue::add_notification( $data );

				$delivery_data                     = array();
				$delivery_data['hash']             = $guid;
				$delivery_data['subscribers']      = $subscribers;
				$delivery_data['campaign_id']      = $campaign_id;
				$delivery_data['mailing_queue_id'] = $last_report_id;
				ES_DB_Sending_Queue::do_batch_insert( $delivery_data );
			}
		}

		return;

	}

	public static function refresh_newsletter_content( $content, $args ) {
		$campaign_id        = $args['campaign_id'];
		$template_id        = ES()->campaigns_db->get_template_id_by_campaign( $campaign_id );
		$content['subject'] = ES()->campaigns_db->get_column( 'subject', $campaign_id );
		$content['body']    = ES()->campaigns_db->get_column( 'body', $campaign_id );
		$content['body']    = ES_Common::es_process_template_body( $content['body'], $template_id, $campaign_id );

		return $content;
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function es_broadcast_preview_callback( $template_data ) {

		$template_id = $template_data['template_id'];
		if ( ! empty( $template_data['content'] ) ) {
			$current_user = wp_get_current_user();
			$username     = $current_user->user_login;
			$useremail    = $current_user->user_email;

			$es_template_body = $template_data['content'];

			$es_template_body = ES_Common::es_process_template_body( $es_template_body, $template_id );
			$es_template_body = str_replace( '{{NAME}}', $username, $es_template_body );
			$es_template_body = str_replace( '{{EMAIL}}', $useremail, $es_template_body );

			if ( has_post_thumbnail( $template_id ) ) {
				$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $template_id ), 'full' );
				$image       = '<img src="' . $image_array[0] . '" class="img-responsive" alt="Image for Post ' . $template_id . '" />';
			} else {
				$image = '';
			}

			$html = '';
			$html .= '<style type="text/css">
							.es-sidebar {
								width: 23%;
							    background-color: rgb(230, 230, 230);
							    padding:15px;
							    border-right: 1px solid #bdbdbd;
							}
							.es-preview {
							    float: left;
								padding:15px;
								width: 70%;
								background-color:#FFF;
								font-size:16px;
							}
						</style>
						<div class="wrap">
							<div class="tool-box">
								<div class="es-main" style="display:flex;">
									<div class="es-sidebar">
										<h2 style="margin-bottom:1em;">
											Template Preview					<a class="add-new-h2" target="_blank" href="' . admin_url() . 'admin.php?page=es-general-information">Help</a>
										</h2>
										<p>
											This is how your email may look.<br><br>Note: Different email services (like gmail, yahoo etc) display email content differently. So there could be a slight variation on how your customer will view the email content.				</p>
									</div>
									<div class="es-preview">' . $es_template_body . '</div>
									<div style="clear:both;"></div>
								</div>
								<div style="clear:both;"></div>
								</div>
								</div>';
			echo apply_filters( 'the_content', $html );
		} else {
			echo 'Please publish it or save it as a draft';
		}

	}


}
