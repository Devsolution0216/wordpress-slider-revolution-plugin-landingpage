<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$current_user       = wp_get_current_user();
$current_user_email = $current_user->user_email;

$reports_data = ES_Reports_Data::get_dashboard_reports_data();

$active_contacts    = isset( $reports_data['total_contacts'] ) ? $reports_data['total_contacts'] : 0;
$total_forms        = isset( $reports_data['total_forms'] ) ? $reports_data['total_forms'] : 0;
$total_campaigns    = isset( $reports_data['total_campaigns'] ) ? $reports_data['total_campaigns'] : 0;
$total_lists        = isset( $reports_data['total_lists'] ) ? $reports_data['total_lists'] : 0;
$total_email_opens  = isset( $reports_data['total_email_opens'] ) ? $reports_data['total_email_opens'] : 0;
$total_open_rate    = isset( $reports_data['total_open_rate'] ) ? $reports_data['total_open_rate'] : 0;
$total_message_sent = isset( $reports_data['total_message_sent'] ) ? $reports_data['total_message_sent'] : 0;
$total_links_clicks = isset( $reports_data['total_links_clicks'] ) ? $reports_data['total_links_clicks'] : 0;
$total_click_rate   = isset( $reports_data['total_click_rate'] ) ? $reports_data['total_click_rate'] : 0;
$total_contact_lost = isset( $reports_data['total_message_lost'] ) ? $reports_data['total_message_lost'] : 0;
$total_lost_rate    = isset( $reports_data['total_lost_rate'] ) ? $reports_data['total_lost_rate'] : 0;
$avg_open_rate      = isset( $reports_data['avg_open_rate'] ) ? $reports_data['avg_open_rate'] : 0;
$avg_click_rate     = isset( $reports_data['avg_click_rate'] ) ? $reports_data['avg_click_rate'] : 0;
$contacts_growth    = isset( $reports_data['contacts_growth'] ) ? $reports_data['contacts_growth'] : array();
$campaigns          = isset( $reports_data['campaigns'] ) ? $reports_data['campaigns'] : array();

$labels = $values = '';
if ( ! empty( $contacts_growth ) ) {
	$labels = json_encode( array_keys( $contacts_growth ) );
	$values = json_encode( array_values( $contacts_growth ) );
}

$audience_url              = admin_url( 'admin.php?page=es_subscribers' );
$new_contact_url           = admin_url( 'admin.php?page=es_subscribers&action=new' );
$new_broadcast_url         = admin_url( 'admin.php?page=es_newsletters' );
$new_post_notification_url = admin_url( 'admin.php?page=es_notifications&action=new' );
$new_sequence_url          = admin_url( 'admin.php?page=es_sequence&action=new' );
$new_form_url              = admin_url( 'admin.php?page=es_forms&action=new' );
$new_list_url              = admin_url( 'admin.php?page=es_lists&action=new' );
$new_template_url          = admin_url( 'post-new.php?post_type=es_template' );
$icegram_pricing_url       = 'https://www.icegram.com/email-subscribers-pricing/';
$reports_url               = admin_url( 'admin.php?page=es_reports' );
$templates_url             = admin_url( 'edit.php?post_type=es_template' );
$settings_url              = admin_url( 'admin.php?page=es_settings' );
$facebook_url              = 'https://www.facebook.com/groups/2298909487017349/';

?>
<div class="wrap" id="ig-es-container">
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
					<?php _e( 'Dashboard', 'email_subscribers' ); ?>
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?php echo $audience_url; ?>">
                <span class="shadow-sm rounded-md">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:shadow-outline focus:border-blue-300 transition duration-150 ease-in-out">
                    <?php _e( 'Audience', 'email_subscribers' ); ?>
                </button>
                </span>
                </a>
                <span class="ml-3 shadow-sm rounded-md">
                <div id="ig-es-create-button" class="relative inline-block text-left">
                        <div>
                          <span class="rounded-md shadow-sm">
                            <button type="button"  class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-white text-sm leading-5 font-medium text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-blue transition ease-in-out duration-150">
                              <?php _e( 'Create', 'email_subscribers' ); ?>
                              <svg class="-mr-1 ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                              </svg>
                            </button>
                          </span>
                        </div>
                        <div x-show="open" id="ig-es-create-dropdown" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                          <div class="rounded-md bg-white shadow-xs">
                            <div class="py-1">
                              <a href="<?php echo $new_broadcast_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Broadcast', 'email_subscribers' ); ?></a>
                              
                              <a href="<?php echo $new_post_notification_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Post Notification', 'email_subscribers' ); ?></a>

                              <?php if ( ES()->is_pro() ) { ?>
                                  <a href="<?php echo $new_sequence_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Sequence', 'email_subscribers' ); ?></a>
                              <?php } else { ?>
                                  <a href="<?php echo $icegram_pricing_url; ?>" target="_blank" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Sequence', 'email_subscribers' ); ?>
                                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><?php _e( 'Premium', 'email_subscribers' ); ?></span></a>
                              <?php } ?>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="py-1">
                                    <a href="<?php echo $new_template_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Template', 'email_subscribers' ); ?></a>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="py-1">
                                    <a href="<?php echo $new_form_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Form', 'email_subscribers' ); ?></a>
                                    <a href="<?php echo $new_list_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New List', 'email_subscribers' ); ?></a>
                                    <a href="<?php echo $new_contact_url; ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php _e( 'New Contact', 'email_subscribers' ); ?></a>
                            </div>
                          </div>
                        </div>
                </div>
            </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <section class="md:flex md:items-start md:justify-between sm:px-4 py-4 my-8 sm:px-0 rounded-lg bg-white shadow sm:grid sm:grid-cols-3">
            <div class="flex-1 min-w-0">
                <p class="px-3 text-lg leading-6 font-medium text-gray-400">
                    <span class="text-black"><?php echo $active_contacts; ?></span><?php _e( ' active contacts', 'email_subscribers' ); ?>
                </p>
                <div class="bg-white-100 text-center" id="ig-es-contacts-growth">

                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="px-3 text-lg leading-6 font-medium text-gray-400">
					<?php _e( 'Last 60 days', 'email_subscribers' ); ?>
                </p>
                <div class="sm:grid sm:grid-cols-2">
                    <div class="p-3">
                        <p class="text-2xl leading-none font-bold text-indigo-600">
							<?php echo $total_email_opens; ?>
                        </p>
                        <p class="mt-1 leading-6 font-medium text-gray-500">
							<?php _e( 'Opens', 'email_subscribers' ); ?>
                        </p>
                    </div>
                    <div class="p-3">
                        <p class="text-2xl leading-none font-bold text-indigo-600">
							<?php echo $avg_open_rate; ?> %
                        </p>
                        <p class="mt-1 leading-6 font-medium text-gray-500">
							<?php _e( ' Avg Open Rate', 'email_subscribers' ); ?>
                        </p>
                    </div>
                    <div class="p-3">
                        <p class="text-2xl leading-none font-bold text-indigo-600">
							<?php echo $total_message_sent; ?>
                        </p>
                        <p class="mt-1 leading-6 font-medium text-gray-500">
							<?php _e( 'Messages Sent', 'email_subscribers' ); ?>
                        </p>
                    </div>
                    <div class="p-3">
                        <p class="text-2xl leading-none font-bold text-indigo-600">
							<?php echo $avg_click_rate; ?> %
                        </p>
                        <p class="mt-1 leading-6 font-medium text-gray-500">
							<?php _e( 'Avg Click Rate', 'email_subscribers' ); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="overflow-hidden">
                    <ul>

                        <li class="border-b border-gray-200">
                            <a href="https://www.icegram.com/2bx5" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out" target="_blank">

                                <div class="flex items-center md:justify-between px-2 py-2 sm:px-2">
                                    <div class="text-sm leading-5 text-gray-900">
										<?php _e( 'Prevent Your Email From Landing In Spam', 'email_subscribers' ); ?>
                                    </div>
                                    <div>
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <li class="border-b border-gray-200">
                            <a href="https://www.icegram.com/2bx6" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out" target="_blank">
                                <div class="flex items-center md:justify-between px-2 py-2 sm:px-2">
                                    <div class="text-sm leading-5 text-gray-900">
										<?php _e( '8 Tips To Improve Email Open Rates', 'email_subscribers' ); ?>
                                    </div>
                                    <div>
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </li>

						<?php if ( ! ES()->is_pro() ) { ?>
                            <li class="border-b border-gray-200">
                                <a href="<?php echo $icegram_pricing_url; ?>" target="_blank" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                                    <div class="flex items-center md:justify-between px-2 py-2 sm:px-2">

                                        <div class="text-sm leading-5 text-gray-900">

											<?php _e( 'Create Autoresponder', 'email_subscribers' ); ?> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><?php _e( 'Premium', 'email_subscribers' ); ?></span>

                                        </div>
                                        <div>
                                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </li>
						<?php } ?>
                        <li class="">
                            <div class="text-sm leading-5 text-gray-900 px-2 py-2 sm:px-2">

								<?php _e( 'Jump to: ', 'email_subscribers' ); ?><a href="<?php echo $reports_url; ?>" class="font-bold" target="_blank"><?php _e( 'Reports', 'email_subscribers' ); ?></a> ・<a href="<?php echo $templates_url; ?>" class="font-bold" target="_blank"><?php _e( 'Templates', 'email_subscribers' ); ?></a> ・<a
                                        href="<?php echo $settings_url; ?>"
                                        class="font-bold" target="_blank"><?php _e( 'Settings',
										'email_subscribers' ); ?></a>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>


		<?php
		if ( ES()->is_pro() ) {
			include_once ES_PLUGIN_DIR . '/pro/partials/es-dashboard.php';
		}
		?>

    </main>
</div>

<script type="text/javascript">

	(function ($) {

		$(document).ready(function () {

			// When we click outside, close the dropdown
			$(document).on("click", function(event){
				var $trigger = $("#ig-es-create-button");
				if($trigger !== event.target && !$trigger.has(event.target).length){
					$("#ig-es-create-dropdown").hide();
				}
			});

			// Toggle Dropdown
			$('#ig-es-create-button').click(function() {
                $('#ig-es-create-dropdown').toggle();
            });

			var labels = <?php if ( ! empty( $labels ) ) {
				echo $labels;
			} else {
				echo "''";
			} ?>;

			var values = <?php if ( ! empty( $values ) ) {
				echo $values;
			} else {
				echo "''";
			} ?>;

			if (labels != '' && values != '') {
				const data = {
					labels: labels,
					datasets: [
						{
							values: values
						},
					]
				};

				const chart = new frappe.Chart("#ig-es-contacts-growth", {
					title: "",
					data: data,
					type: 'line',
					colors: ['#743ee2'],
					lineOptions: {
						hideDots: 1,
						heatline: 1
					},
					height: 150,
					axisOptions: {
						xIsSeries: true
					}
				});
			}

		});

	})(jQuery);

</script>
