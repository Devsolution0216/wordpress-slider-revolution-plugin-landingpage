<?php

if ( !class_exists( 'MeowApps_Admin' ) ) {

	class MeowApps_Admin {

		public static $logo = 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxIiB2aWV3Qm94PSIwIDAgMTY1IDE2NSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8c3R5bGU+CiAgICAuc3Qye2ZpbGw6IzgwNDYyNX0uc3Qze2ZpbGw6I2ZkYTk2MH0KICA8L3N0eWxlPgogIDxwYXRoIGQ9Ik03MiA3YTc2IDc2IDAgMCAxIDg0IDkxQTc1IDc1IDAgMSAxIDcyIDd6IiBmaWxsPSIjNGE2YjhjIi8+CiAgPHBhdGggZD0iTTQ4IDQ4YzIgNSAyIDEwIDUgMTQgNSA4IDEzIDE3IDIyIDIwbDEtMTBjMS0yIDMtMyA1LTNoMTNjMiAwIDQgMSA1IDNsMyA5IDQtMTBjMi0zIDYtMiA5LTJoMTFjMyAyIDMgNSAzIDhsMiAzN2MwIDMtMSA3LTQgOGgtMTJjLTIgMC0zLTItNS00LTEgMS0yIDMtNCAzLTUgMS05IDEtMTMtMS0zIDItNSAyLTkgMnMtOSAxLTEwLTNjLTItNC0xLTggMC0xMi04LTMtMTUtNy0yMi0xMi03LTctMTUtMTQtMjAtMjMtMy00LTUtOC01LTEzIDEtNCAzLTEwIDYtMTMgNC0zIDEyLTIgMTUgMnoiIGZpbGw9IiMxMDEwMTAiLz4KICA8cGF0aCBjbGFzcz0ic3QyIiBkPSJNNDMgNTFsNCAxMS02IDVoLTZjLTMtNS0zLTExIDAtMTYgMi0yIDYtMyA4IDB6Ii8+CiAgPHBhdGggY2xhc3M9InN0MyIgZD0iTTQ3IDYybDMgNmMwIDMgMCA0LTIgNnMtNCAyLTcgMmwtNi05aDZsNi01eiIvPgogIDxwYXRoIGNsYXNzPSJzdDIiIGQ9Ik01MCA2OGw4IDljLTMgMy01IDYtOSA4bC04LTljMyAwIDUgMCA3LTJzMy0zIDItNnoiLz4KICA8cGF0aCBkPSJNODIgNzRoMTJsNSAxOCAzIDExIDgtMjloMTNsMiA0MmgtOGwtMS0yLTEtMzEtMTAgMzItNyAxLTktMzMtMSAyOS0xIDRoLThsMy00MnoiIGZpbGw9IiNmZmYiLz4KICA8cGF0aCBjbGFzcz0ic3QzIiBkPSJNNTggNzdsNSA1Yy0xIDQtMiA4LTcgOGwtNy01YzQtMiA2LTUgOS04eiIvPgogIDxwYXRoIGNsYXNzPSJzdDIiIGQ9Ik02MyA4Mmw5IDUtNiA5LTEwLTZjNSAwIDYtNCA3LTh6Ii8+CiAgPHBhdGggY2xhc3M9InN0MyIgZD0iTTcyIDg3bDMgMS0xIDExLTgtMyA2LTEweiIvPgo8L3N2Zz4K';

		public static $loaded = false;
		public static $admin_version = "2.4";

		public $prefix; 		// prefix used for actions, filters (mfrh)
		public $mainfile; 	// plugin main file (media-file-renamer.php)
		public $domain; 		// domain used for translation (media-file-renamer)

		public function __construct( $prefix, $mainfile, $domain, $disableReview = false ) {

			// Core Admin (used by all Meow Apps plugins)
			if ( !MeowApps_Admin::$loaded ) {
				if ( is_admin() ) {
					add_action( 'admin_menu', array( $this, 'admin_menu_start' ) );
					add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
					add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
					add_action( 'wp_ajax_meow_perf_load', array( $this, 'wp_ajax_meow_perf_load' ) );
					add_action( 'wp_ajax_meow_file_check', array( $this, 'wp_ajax_meow_file_check' ) );
					if ( isset( $_GET['page'] ) && $_GET['page'] === 'meowapps-main-menu' ) {
						add_filter( 'admin_footer_text',  array( $this, 'admin_footer_text' ), 100000, 1 );
					}
				}
				MeowApps_Admin::$loaded = true;
			}

			// Variables for this plugin
			$this->prefix = $prefix;
			$this->mainfile = $mainfile;
			$this->domain = $domain;
			$this->is_theme = ( strpos( $this->mainfile, '-theme' ) !== false  );

			// If there is no mainfile, it's either a Pro only Plugin (with no Free version available) or a Theme.
			if ( !$this->is_theme ) {
				register_activation_hook( $mainfile, array( $this, 'show_meowapps_create_rating_date' ) );
				if ( is_admin() ) {
					$license = get_option( $this->prefix . '_license', "" );
					if ( ( !empty( $license ) ) && !file_exists( plugin_dir_path( $this->mainfile ) . 'common/meowapps/admin.php' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notices_licensed_free' ) );
					}
					if ( !$disableReview ) {
						$rating_date = $this->create_rating_date();
						if ( time() > $rating_date ) {
							add_action( 'admin_notices', array( $this, 'admin_notices_rating' ) );
						}
					}
				}
			}
			
			add_filter( 'edd_sl_api_request_verify_ssl', array( $this, 'request_verify_ssl' ), 10, 0 );
		}

		function wp_ajax_meow_perf_load() {
			return 'Did nothing but a blank request.';
		}

		function wp_ajax_meow_file_check() {
			$tmpfile = wp_tempnam();
			unlink( $tmpfile );
			return "Created and deleted $tmpfile.";
		}

		function request_verify_ssl() {
			return get_option( 'force_sslverify', false );
		}

		function show_meowapps_create_rating_date() {
			delete_option( 'meowapps_hide_meowapps' );
			$this->create_rating_date();
		}

		function create_rating_date() {
			$rating_date = get_option( $this->prefix . '_rating_date' );
			if ( empty( $rating_date ) ) {
				$two_months = strtotime( '+2 months' );
				$six_months = strtotime( '+4 months' );
				$rating_date = mt_rand( $two_months, $six_months );
				update_option( $this->prefix . '_rating_date', $rating_date, false );
			}
			return $rating_date;
		}

		function admin_notices_rating() {
			if ( isset( $_POST[$this->prefix . '_remind_me'] ) ) {
				$two_weeks = strtotime( '+2 weeks' );
				$six_weeks = strtotime( '+6 weeks' );
				$future_date = mt_rand( $two_weeks, $six_weeks );
				update_option( $this->prefix . '_rating_date', $future_date, false );
				return;
			}
			else if ( isset( $_POST[$this->prefix . '_never_remind_me'] ) ) {
				$twenty_years = strtotime( '+5 years' );
				update_option( $this->prefix . '_rating_date', $twenty_years, false );
				return;
			}
			else if ( isset( $_POST[$this->prefix . '_did_it'] ) ) {
				$twenty_years = strtotime( '+10 years' );
				update_option( $this->prefix . '_rating_date', $twenty_years, false );
				return;
			}
			$rating_date = get_option( $this->prefix . '_rating_date' );
			echo '<div class="notice notice-success" data-rating-date="' . date( 'Y-m-d', $rating_date ) . '">';
				echo '<p style="font-size: 100%;">You have been using <b>' . $this->nice_name_from_file( $this->mainfile  ) . '</b> for some time now. Thank you! Could you kindly share your opinion with me, along with, maybe, features you would like to see implemented? Then, please <a style="font-weight: bold; color: #b926ff;" target="_blank" href="https://wordpress.org/support/plugin/' . $this->nice_short_url_from_file( $this->mainfile ) . '/reviews/?rate=5#new-post">write a little review</a>. That will also bring me joy and motivation, and I will get back to you :) <u>In the case you already have written a review</u>, please check again. Many reviews got removed from WordPress recently.';
			echo '<p>
				<form method="post" action="" style="float: right;">
					<input type="hidden" name="' . $this->prefix . '_never_remind_me" value="true">
					<input type="submit" name="submit" id="submit" class="button button-red" value="Never remind me!">
				</form>
				<form method="post" action="" style="float: right; margin-right: 10px;">
					<input type="hidden" name="' . $this->prefix . '_remind_me" value="true">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Remind me in a few weeks...">
				</form>
				<form method="post" action="" style="float: right; margin-right: 10px;">
					<input type="hidden" name="' . $this->prefix . '_did_it" value="true">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Yes, I did it!">
				</form>
				<div style="clear: both;"></div>
			</p>
			';
			echo '</div>';
		}

		function nice_short_url_from_file( $file ) {
			$info = pathinfo( $file );
			if ( !empty( $info ) ) {
				$info['filename'] = str_replace( '-pro', '', $info['filename'] );
				return $info['filename'];
			}
			return "";
		}

		function nice_name_from_file( $file ) {
			$info = pathinfo( $file );
			if ( !empty( $info ) ) {
				if ( $info['filename'] == 'wplr-sync' ) {
					return "WP/LR Sync";
				}
				$info['filename'] = str_replace( '-', ' ', $info['filename'] );
				$file = ucwords( $info['filename'] );
			}
			return $file;
		}

		function admin_notices_licensed_free() {
			if ( isset( $_POST[$this->prefix . '_reset_sub'] ) ) {
				delete_option( $this->prefix . '_pro_serial' );
				delete_option( $this->prefix . '_license' );
				return;
			}
			echo '<div class="error">';
			echo '<p>It looks like you are using the free version of the plugin (<b>' . $this->nice_name_from_file( $this->mainfile  )	 . '</b>) but a license for the Pro version was also found. The Pro version might have been replaced by the Free version during an update (might be caused by a temporarily issue). If it is the case, <b>please download it again</b> from the <a target="_blank" href="https://store.meowapps.com">Meow Store</a>. If you wish to continue using the free version and clear this message, click on this button.';
			echo '<p>
				<form method="post" action="">
					<input type="hidden" name="' . $this->prefix . '_reset_sub" value="true">
					<input type="submit" name="submit" id="submit" class="button" value="Remove the license">
				</form>
			</p>
			';
			echo '</div>';
		}

		function display_ads() {
			return !get_option( 'meowapps_hide_ads', false );
		}

		function display_title( $title = "Meow Apps",
			$author = "By <a style='text-decoration: none;' href='https://meowapps.com' target='_blank'>Jordy Meow</a>" ) {
			if ( !empty( $this->prefix ) && $title !== "Meow Apps" )
				$title = apply_filters( $this->prefix . '_plugin_title', $title );
			if ( $this->display_ads() ) {
			}
			?>
			<h1 style="line-height: 16px;">
				<img width="42" style="margin-right: 10px; float: left; position: relative; top: -5px;"
					src="<?php echo MeowApps_Admin::$logo ?>"><?php echo $title; ?><br />
				<span style="font-size: 12px"><?php echo $author; ?></span>
			</h1>
			<div style="clear: both;"></div>
			<?php
		}

		function admin_enqueue_scripts() {
			wp_register_style( 'meowapps-core-css', $this->common_url( 'admin.css' ) );
			wp_enqueue_style( 'meowapps-core-css' );
		}

		function admin_menu_start() {
			if ( get_option( 'meowapps_hide_meowapps', false ) ) {
				register_setting( 'general', 'meowapps_hide_meowapps' );
				add_settings_field( 'meowapps_hide_ads', 'Meow Apps Menu', array( $this, 'meowapps_hide_dashboard_callback' ), 'general' );
				return;
			}

			// Creates standard menu if it does NOT exist
			global $submenu;
			if ( !isset( $submenu[ 'meowapps-main-menu' ] ) ) {
				add_menu_page( 'Meow Apps', '<img style="width: 24px; margin-left: -30px; position: absolute; margin-top: -3px;" src="' . MeowApps_Admin::$logo . '" />Meow Apps', 'manage_options', 'meowapps-main-menu',
					array( $this, 'admin_meow_apps' ), '', 82 );
				add_submenu_page( 'meowapps-main-menu', __( 'Dashboard', 'meowapps' ),
					__( 'Dashboard', 'meowapps' ), 'manage_options',
					'meowapps-main-menu', array( $this, 'admin_meow_apps' ) );
			}

			add_settings_section( 'meowapps_common_settings', null, null, 'meowapps_common_settings-menu' );
			add_settings_field( 'meowapps_hide_meowapps', "Main Menu",
				array( $this, 'meowapps_hide_dashboard_callback' ),
				'meowapps_common_settings-menu', 'meowapps_common_settings' );
			add_settings_field( 'meowapps_force_sslverify', "SSL Verify",
				array( $this, 'meowapps_force_sslverify_callback' ),
				'meowapps_common_settings-menu', 'meowapps_common_settings' );
			// add_settings_field( 'meowapps_hide_ads', "Ads",
			// 	array( $this, 'meowapps_hide_ads_callback' ),
			// 	'meowapps_common_settings-menu', 'meowapps_common_settings' );
			register_setting( 'meowapps_common_settings', 'force_sslverify' );
			register_setting( 'meowapps_common_settings', 'meowapps_hide_meowapps' );
			register_setting( 'meowapps_common_settings', 'meowapps_hide_ads' );
		}

		function meowapps_hide_ads_callback() {
			$value = get_option( 'meowapps_hide_ads', null );
			$html = '<input type="checkbox" id="meowapps_hide_ads" name="meowapps_hide_ads" value="1" ' .
				checked( 1, get_option( 'meowapps_hide_ads' ), false ) . '/>';
	    $html .= __( '<label>Hide</label><br /><small>Doesn\'t display the ads.</small>', 'meowapps' );
	    echo $html;
		}

		function meowapps_hide_dashboard_callback() {
			$value = get_option( 'meowapps_hide_meowapps', null );
			$html = '<input type="checkbox" id="meowapps_hide_meowapps" name="meowapps_hide_meowapps" value="1" ' .
				checked( 1, get_option( 'meowapps_hide_meowapps' ), false ) . '/>';
	    $html .= __( '<label>Hide <b>Meow Apps</b> Menu</label><br /><small>Hide Meow Apps menu and all its components, for a cleaner admin. This option will be reset if a new Meow Apps plugin is installed. <b>Once activated, an option will be added in your General settings to display it again.</b></small>', 'meowapps' );
	    echo $html;
		}

		function meowapps_force_sslverify_callback() {
			$value = get_option( 'force_sslverify', null );
			$html = '<input type="checkbox" id="force_sslverify" name="force_sslverify" value="1" ' .
				checked( 1, get_option( 'force_sslverify' ), false ) . '/>';
	    $html .= __( '<label>Force</label><br /><small>Updates and licenses checks are usually made without checking SSL certificates and it is actually fine this way. But if you are intransigent when it comes to SSL matters, this option will force it.</small>', 'meowapps' );
	    echo $html;
		}

		function display_serialkey_box( $url = "https://meowapps.com/" ) {
			$html = '<div class="meow-box">';
      $html .= '<h3 class="' . ( $this->is_registered( $this->prefix ) ? 'meow-bk-blue' : 'meow-bk-red' ) . '">Pro Version ' .
        ( $this->is_registered( $this->prefix ) ? '(enabled)' : '(disabled)' ) . '</h3>';
      $html .= '<div class="inside">';
			echo $html;
			$html = apply_filters( $this->prefix . '_meowapps_license_input', ( 'More information about the Pro version here:
				<a target="_blank" href="' . $url . '">' . $url . '</a>. If you actually bought the Pro version already, please remove the current plugin and download the Pro version from your account at the <a target="_blank" href="https://store.meowapps.com/account/downloads/">Meow Apps Store</a>.' ), $url );
      $html .= '</div>';
      $html .= '</div>';
			echo $html;
		}

		function is_registered() {
			return apply_filters( $this->prefix . '_meowapps_is_registered', false, $this->prefix  );
		}

		function check_install( $plugin ) {
			$pro = false;

			$pluginpath = trailingslashit( plugin_dir_path( __FILE__ ) ) . '../../' . $plugin . '-pro';
			if ( !file_exists( $pluginpath ) ) {
				$pluginpath = trailingslashit( plugin_dir_path( __FILE__ ) ) . '../../' . $plugin;
				if ( !file_exists( $pluginpath ) ) {
					$url = wp_nonce_url( "update.php?action=install-plugin&plugin=$plugin", "install-plugin_$plugin" );
					return "<a href='$url'><small><span class='' style='float: right;'>install</span></small></a>";
				}
			}
			else {
				$pro = true;
				$plugin = $plugin . "-pro";
			}

			$plugin_file = $plugin . '/' . $plugin . '.php';
			if ( is_plugin_active( $plugin_file ) ) {
				if ( $plugin == 'wplr-sync' )
					$pro = true;
				if ( $pro )
					return "<small><span style='float: right;'><span class='dashicons dashicons-heart' style='color: rgba(255, 63, 0, 1); font-size: 30px !important; margin-right: 10px;'></span></span></small>";
				else
					return "<small><span style='float: right;'><span class='dashicons dashicons-yes' style='color: #00b4ff; font-size: 30px !important; margin-right: 10px;'></span></span></small>";
			}
			else {
				$url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $plugin_file ),
					'activate-plugin_' . $plugin_file );
				return '<small><span style="color: black; float: right;">off
				(<a style="color: rgba(30,140,190,1); text-decoration: none;" href="' .
					$url . '">enable</a>)</span></small>';
			}
		}

		function common_url( $file ) {
			die( "Meow Apps: The function common_url( \$file ) needs to be overriden." );
			// Normally, this should be used:
			// return plugin_dir_url( __FILE__ ) . ( '\/common\/' . $file );
		}

		function meowapps_logo_url() {
			return $this->common_url( 'img/meowapps.png' );
		}

		function plugins_loaded() {
			if ( isset( $_GET[ 'tool' ] ) && $_GET[ 'tool' ] == 'error_log' ) {
 				$sec = "5";
 				header( "Refresh: $sec;" );
			}
		}

		function admin_meow_apps() {

			echo '<div class="wrap meow-dashboard">';
			if ( isset( $_GET['tool'] ) && $_GET['tool'] == 'phpinfo' ) {
				echo "<a href=\"javascript:history.go(-1)\">< Go back</a><br /><br />";
				echo '<div id="phpinfo">';
				ob_start();
				phpinfo();
				$pinfo = ob_get_contents();
				ob_end_clean();
				$pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1', $pinfo );
				echo $pinfo;
				echo "</div>";
			}
			else if ( isset( $_GET['tool'] ) && $_GET['tool'] == 'error_log' ) {
				$log_msg = '=== MEOW APPS DEBUG (This is not an error) ===';
				if ( isset( $_POST['write_logs'] ) ) {
					error_log( $log_msg );
				}
				$errorpath = ini_get( 'error_log' );
				echo "<a href=\"javascript:history.go(-1)\">< Go back</a><br /><br />";
				echo '
					<form method="post">
						<input type="hidden" name="write_logs" value="true">
						<input class="button button-primary" type="submit" value="Write in the Error Logs">
					</form><br />';
				echo '<div id="error_log">';
				if ( file_exists( $errorpath ) ) {
					echo "Now (auto-reload every 5 seconds): [" . date( "d-M-Y H:i:s", time() ) . " UTC]<br /><br /><h2 style='margin: 0px;'>Errors (order by latest)</h2>";
					$errors = file_get_contents( $errorpath );
					$errors = explode( "\n", $errors );
					$errors = array_reverse( $errors );
					$errors = implode( "<br />", $errors );
					echo $errors;
				}
				else {
					echo "The PHP Error Logs cannot be found. Please ask your hosting service for it.";
				}
				echo "</div>";

			}
			else {

				?>
				<?php $this->display_title( 'Meow Apps' ); ?>
				<p>
				<?php _e( 'Meow Apps is run by Jordy Meow, a photographer and software developer living in Japan (and taking <a target="_blank" href="http://offbeatjapan.org">a lot of photos</a>). Meow Apps is a suite of plugins focusing on photography, imaging, optimization and it teams up with the best players in the community (other themes and plugins developers). For more information, please check <a href="http://meowapps.com" target="_blank">Meow Apps</a>.', 'meowapps' )
				?>
				</p>
				
				<h2 style="margin-bottom: 0px; margin-top: 25px;">Featured Plugins</h2>
				<div class="meow-row meow-featured-plugins">
					<div class="meow-box meow-col meow-span_1_of_2">
						<ul class="">
							<li><img src='<?= $this->common_url( 'img/media-cleaner.jpg' ) ?>' />
								<a href='https://meowapps.com/plugin/media-cleaner/'><b>Media Cleaner</b></a>
								<?php echo $this->check_install( 'media-cleaner' ) ?><br />
								Detect the files which are not in use.</li>
							<li><img src='<?= $this->common_url( 'img/media-file-renamer.jpg' ) ?>' />
								<a href='https://meowapps.com/plugin/media-file-renamer/'><b>Media File Renamer</b></a>
								 <?php echo $this->check_install( 'media-file-renamer' ) ?><br />
								For nicer filenames and a better SEO.</li>
							<li><img src='<?= $this->common_url( 'img/default.png' ) ?>' />
								<a href='https://meowapps.com/plugin/contact-form-block/'><b>Contact Form Block</b></a>
								<?php echo $this->check_install( 'contact-form-block' ) ?><br />
								A simpler, nicer, prettier contact form.</li>
							<!--li><img src='<?= $this->common_url( 'img/wp-retina-2x.jpg' ) ?>' />
								<a href='https://meowapps.com/plugin/wp-retina-2x/'><b>WP Retina 2x</b></a>
								<?php echo $this->check_install( 'wp-retina-2x' ) ?><br />
								The famous plugin that adds Retina support.</li-->

						</ul>
					</div>
					<div class="meow-box meow-col meow-span_1_of_2 ">
						<ul class="">
							<li><img src='<?= $this->common_url( 'img/meow-gallery.jpg' ) ?>' />
								<a href='https://meowapps.com/plugin/meow-gallery/'><b>Meow Gallery</b></a>
								<?php echo $this->check_install( 'meow-gallery' ) ?><br />
								Beautiful but lightweight gallery with many layouts. The only one that allows you to uninstall it without losing anything.</li>
							<li><img src='<?= $this->common_url( 'img/meow-lightbox.jpg' ) ?>' />
								<a href='https://meowapps.com/plugin/meow-lightbox/'><b>Meow Lightbox</b></a>
								<?php echo $this->check_install( 'meow-lightbox' ) ?><br />
								Pretty and ultra-optimized Lightbox which can also display your EXIF data. You will love it.</li>
							<li><img src='<?= $this->common_url( 'img/wplr-sync.jpg' ) ?>' />
								<a href='https://meowapps.com/plugin/wplr-sync/'><b>WP/LR Sync</b></a>
								<?php echo $this->check_install( 'wplr-sync' ) ?><br />
								Synchronize your Lightroom to your WordPress. This plugin is loved by all the photographers using Lightroom and WordPress.</li>
						</ul>
					</div>
				</div>

				<h2>WordPress Performance & Recommendations</h2>
				<div style="background: white; padding: 5px 15px 5px 15px; box-shadow: 2px 2px 1px rgba(0,0,0,.02); margin-bottom: 15px;">
					<p><?php _e( 'The <b>Empty Request Time</b> helps you analyzing the raw performance of your install by giving you the average time it takes to run an empty request to your server. You can try to disable some plugins (or change their options) then and click on Reset to see how it influences the results. With <b>File Operation Time</b>, you will find out if your server is slow with files. An excellent install would have an Empty Request Time of less than 500 ms. Keep it absolutely under 2,000 ms. File Operation Time should take only a few milliseconds more than the Empty Request Time. For more information about this, <a href="https://meowapps.com/clean-optimize-wordpress/#Optimize_your_Empty_Request_Time" target="_blank">click here</a>.', 'meowapps' ); ?></p>
				</div>

				<div style="float: left; margin-right: 10px; text-align: center; padding: 10px; background: white; width: 200px; border: 1px solid #e2e2e2;">
					<div style='font-size: 14px; line-height: 14px; margin-bottom: 20px;'>Empty Request Time</div>
					<div style='font-size: 32px; line-height: 32px; margin-bottom: 10px;' id='meow-perf-load-average'>N/A</div>
					<div style='font-size: 12px; line-height: 12px; margin-bottom: 20px;'>Based on 
						<span id='meow-perf-load-count'>0</span> request(s)
					</div>
					<input type='submit' style='text-align: center; width: 100%;' id="meow-perf-reset" value="Reset" class="button button-primary">
				</div>
				
				<div style="float: left; margin-right: 10px; text-align: center; padding: 10px; background: white; width: 200px; border: 1px solid #e2e2e2;">
					<div style='font-size: 14px; line-height: 14px; margin-bottom: 20px;'>File Operation Time</div>
					<div style='font-size: 32px; line-height: 32px; margin-bottom: 10px;' id='meow-file-check-time'>N/A</div>
					<div style='font-size: 12px; line-height: 12px; margin-bottom: 20px;'>Create temporary file and delete it.</div>
					<input type='submit' style='text-align: center; width: 100%;' id="meow-file-check-start" value="Check" class="button button-primary">
				</div>

				<div style="clear: both;"></div>

				<script>
					(function ($) {
						var calls = 0;
						var times = [];

						$('#meow-perf-reset').on('click', function () {
							calls = 0;
							times = [];
							$('#meow-perf-load-average').text('N/A');
							$('#meow-perf-load-count').text('0');
						});

						function perfLoad() {
							var start = new Date().getTime();
							$.ajax(ajaxurl, {
								method: 'post',
								dataType: 'json',
								data: {
									action: 'meow_perf_load',
								}
							}).done(function (response) {
								var end = new Date().getTime();
								var time = end - start;
								calls++;
								times.push(time);
								var sum = times.reduce(function(a, b) { return a + b; });
								var avg = Math.ceil(sum / times.length);
								$('#meow-perf-load-average').text(avg + ' ms');
								$('#meow-perf-load-count').text(calls);
								setTimeout(perfLoad, 5000);
							});
						};

						function perfFile() {
							var start = new Date().getTime();
							$.ajax(ajaxurl, {
								method: 'post',
								dataType: 'json',
								data: {
									action: 'meow_file_check',
								}
							}).done(function (response) {
								var end = new Date().getTime();
								var time = end - start;
								$('#meow-file-check-time').text(time + ' ms');
								$('#meow-file-check-start').text('Check');
							});
						};

						$('#meow-file-check-start').on('click', function () {
							$('#meow-file-check-start').text('...');
							perfFile();
						});

						setTimeout(perfLoad, 1500);
						
					})(jQuery);
				</script>

				<div style="background: white; padding: 5px 15px 5px 15px; box-shadow: 2px 2px 1px rgba(0,0,0,.02); margin-top: 15px;">
					<p>
						<?php _e( 'Too many WordPress installs are blown-up with useless and/or huge plugins, and bad practices. But that is because most users are overwhelmed by the diversity and immensity of the WordPress jungle. One rule of thumb is to keep your install the simplest as possible, with the least number of plugins (avoiding heavy ones too) and a good hosting service (avoid VPS except if you know exactly what you are doing). Articles are kept being updated on the Meow Apps website, with all the latest recommendations: ', 'meowapps' )?>
						<a href='https://meowapps.com/debugging-wordpress/' target='_blank'>
							How To Debug</a>, 
						<a href='https://meowapps.com/seo-optimization/' target='_blank'>
							SEO Checklist & Optimization</a>, 
						<a href='https://meowapps.com/clean-optimize-wordpress/' target='_blank'>
							Clean Up and Optimize</a>, 
						<a href='https://meowapps.com/optimize-images-cdn/' target='_blank'>
							Optimize Images</a>, 
						<a href='https://meowapps.com/best-hosting-services-wordpress/' target='_blank'>
							Best Hosting Services</a>.
					</p>
				</div>

				<h2 style="margin-bottom: 0px; margin-top: 25px;">Common Options & Tools</h2>
				<div class="meow-row">
					<div class="meow-box meow-col meow-span_2_of_3">
						<h3><span class="dashicons dashicons-admin-tools"></span> Common</h3>
						<div class="inside">
							<form method="post" action="options.php">
								<?php settings_fields( 'meowapps_common_settings' ); ?>
								<?php do_settings_sections( 'meowapps_common_settings-menu' ); ?>
								<?php submit_button(); ?>
							</form>
						</div>
					</div>

					<div class="meow-box meow-col meow-span_1_of_3">
						<h3><span class="dashicons dashicons-admin-tools"></span> Debug</h3>
						<div class="inside">
							<ul>
								<li><a href="?page=meowapps-main-menu&amp;tool=error_log">Display Error Log</a></li>
								<li><a href="?page=meowapps-main-menu&amp;tool=phpinfo">Display PHP Info</a></li>
							</ul>
						</div>
					</div>

					<div class="meow-box meow-col meow-span_1_of_3">
						<h3><span class="dashicons dashicons-admin-tools"></span> Post Types (used by this install)</h3>
						<div class="inside">
							<?php
								global $wpdb;
								// Maybe we could avoid to check more post_types.
								// SELECT post_type, COUNT(*) FROM `wp_posts` GROUP BY post_type
								$types = $wpdb->get_results( "SELECT post_type as 'type', COUNT(*) as 'count' FROM $wpdb->posts GROUP BY post_type" );
								$result = array();
								foreach( $types as $type )
									array_push( $result, "{$type->type} ({$type->count})" );
								echo implode( $result, ', ' );
							?>
						</div>
					</div>
				</div>

				<?php
			}
		}

		function admin_footer_text( $current ) {
			return "Thanks for using <a href='https://meowapps.com'>Meow Apps</a>! This is the Meow Admin " . 
				MeowApps_Admin::$admin_version . ". <br /><i>Loaded from " . __FILE__ . "</i>";
		}

		// HELPERS

		static function size_shortname( $name ) {
			$name = preg_split( '[_-]', $name );
			$short = strtoupper( substr( $name[0], 0, 1 ) );
			if ( count( $name ) > 1 )
				$short .= strtoupper( substr( $name[1], 0, 1 ) );
			return $short;
		}

	}

}

if ( file_exists( plugin_dir_path( __FILE__ ) . '/meowapps/admin.php' ) ) {
	require( 'meowapps/admin.php' );
}

?>
