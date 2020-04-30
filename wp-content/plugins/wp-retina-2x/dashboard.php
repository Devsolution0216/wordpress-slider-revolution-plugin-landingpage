<?php

class Meow_WR2X_Dashboard {

	public $core = null;

	public function __construct( $core ) {
		$this->core = $core;
		add_action( 'admin_menu', array( $this, 'admin_menu_dashboard' ) );
	}

	function admin_menu_dashboard () {
		$flagged = count( $this->core->get_issues() );
		$warning_title = __( "Retina images", 'wp-retina-2x' );
		$menu_label = sprintf( __( 'Retina %s' ), "<span class='update-plugins count-$flagged' title='$warning_title'><span class='update-count'>" . number_format_i18n( $flagged ) . "</span></span>" );
		add_media_page( 'Retina', $menu_label, 'manage_options', 'wp-retina-2x', array( $this, 'dashboard' ) );
	}

	function dashboard() {
		$refresh = isset ( $_GET[ 'refresh' ] ) ? sanitize_text_field( $_GET[ 'refresh' ] ) : 0;
		$clearlogs = isset ( $_GET[ 'clearlogs' ] ) ? sanitize_text_field( $_GET[ 'clearlogs' ] ) : 0;
		$ignore = isset ( $_GET[ 'ignore' ] ) ? sanitize_text_field( $_GET[ 'ignore' ] ) : false;
		if ( $ignore ) {
			if ( !$this->core->admin->is_registered() ) {
				echo "<div class='error' style='margin-top: 20px;'><p>";
				_e( "Ignore is a Pro feature.", 'wp-retina-2x' );
				echo "</p></div>";
			}
			else
				$this->core->add_ignore( $ignore );
		}
		if ( $refresh ) {
			$this->core->calculate_issues();
		}
		if ( $clearlogs ) {
			if ( file_exists( plugin_dir_path( __FILE__ ) . '/wp-retina-2x.log' ) ) {
				unlink( plugin_dir_path( __FILE__ ) . '/wp-retina-2x.log' );
			}
		}

		$hide_ads = get_option( 'meowapps_hide_ads', false );
		$view = isset( $_GET[ 'view' ] ) ? sanitize_text_field( $_GET[ 'view' ] ) : 'issues';
		$paged = isset( $_GET[ 'paged' ] ) ? sanitize_text_field( $_GET[ 'paged' ] ) : 1;
		$s = isset( $_GET[ 's' ] ) && !empty( $_GET[ 's' ] ) ? sanitize_text_field( $_GET[ 's' ] ) : null;
		$issues = $count = 0;

		$posts_per_page = get_user_meta( get_current_user_id(), 'upload_per_page', true );
		if ( empty( $posts_per_page ) )
			$posts_per_page = 20;
		$issues = $this->core->get_issues();
		$ignored = $this->core->get_ignores();

		echo '<div class="wrap">';
	  echo $this->core->admin->display_title( "WP Retina 2x" );
		echo '<p></p>';

		if ( $this->core->admin->is_registered() && $view == 'issues' ) {
			global $wpdb;
			$totalcount = $wpdb->get_var( $wpdb->prepare( "
				SELECT COUNT(*)
				FROM $wpdb->posts p
				WHERE post_status = 'inherit'
				AND post_type = 'attachment'" . $this->core->create_sql_if_wpml_original() . "
				AND post_title LIKE %s
				AND ( post_mime_type = 'image/jpeg' OR
				post_mime_type = 'image/png' OR
				post_mime_type = 'image/gif' )
			", '%' . $s . '%' ) );
			$postin = count( $issues ) < 1 ? array( -1 ) : $issues;
			$query = new WP_Query(
				array(
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post__in' => $postin,
					'paged' => $paged,
					'posts_per_page' => $posts_per_page,
					's' => $s
				)
			);
		}
		else if ( $this->core->admin->is_registered() && $view == 'ignored' ) {
			global $wpdb;
			$totalcount = $wpdb->get_var( $wpdb->prepare( "
				SELECT COUNT(*)
				FROM $wpdb->posts p
				WHERE post_status = 'inherit'
				AND post_type = 'attachment'" . $this->core->create_sql_if_wpml_original() . "
				AND post_title LIKE %s
				AND ( post_mime_type = 'image/jpeg' OR
				post_mime_type = 'image/jpg' OR
				post_mime_type = 'image/png' OR
				post_mime_type = 'image/gif' )
			", '%' . $s . '%' ) );
			$postin = count( $ignored ) < 1 ? array( -1 ) : $ignored;
			$query = new WP_Query(
				array(
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post__in' => $postin,
					'paged' => $paged,
					'posts_per_page' => $posts_per_page,
					's' => $s
				)
			);
		}
		else {
			$query = new WP_Query(
				array(
					'post_status' => 'inherit',
					'post_type' => 'attachment',
					'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
					'paged' => $paged,
					'posts_per_page' => $posts_per_page,
					's' => $s
				)
			);

			//$s
			$totalcount = $query->found_posts;
		}

		$issues_count = count( $issues );

		// If 'search', then we need to clean-up the issues count
		if ( $s && $issues_count > 0 ) {
			global $wpdb;
			$issues_count = $wpdb->get_var( $wpdb->prepare( "
				SELECT COUNT(*)
				FROM $wpdb->posts p
				WHERE id IN ( " . implode( ',', $issues ) . " )" . $this->core->create_sql_if_wpml_original() . "
				AND post_title LIKE %s
			", '%' . $s . '%' ) );
		}

		$results = array();
		$count = $query->found_posts;
		$pagescount = $query->max_num_pages;
		foreach ( $query->posts as $post ) {
			$info = $this->core->retina_info( $post->ID );
			array_push( $results, array( 'post' => $post, 'info' => $info ) );
		}
		?>

		<div style='background: #FFF; padding: 5px; border-radius: 4px; height: 28px; box-shadow: 0px 0px 6px #C2C2C2;'>

			<!-- REFRESH -->
			<a id='wr2x_refresh' href='?page=wp-retina-2x&view=issues&refresh=true' class='button' style='float: left;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-controls-repeat"></span><?php _e("Refresh", 'wp-retina-2x'); ?></a>

			<!-- SEARCH -->
			<form id="posts-filter" action="upload.php" method="get">
				<p class="search-box" style='margin-left: 5px; float: left;'>
					<input type="search" name="s" value="<?php echo $s ? $s : ""; ?>">
					<input type="hidden" name="page" value="wp-retina-2x">
					<input type="hidden" name="view" value="<?php echo $view; ?>">
					<input type="hidden" name="paged" value="<?php echo $paged; ?>">
					<input type="submit" class="button" value="Search">
				</p>
			</form>

			<!-- REMOVE BUTTON ALL -->
			<a id='wr2x_remove_button_all' onclick='wr2x_delete_all()' class='button button-red' style='float: right;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-trash"></span><?php _e("Bulk Delete (Retina Only)", 'wp-retina-2x'); ?></a>

			<!-- GENERATE ALL -->
			<a id='wr2x_generate_button_all' onclick='wr2x_generate_all()' class='button-primary' style='float: right; margin-right: 5px;'><span style="top: 3px; position: relative; left: -5px;" class="dashicons dashicons-controls-play"></span><?php _e("Bulk Generate (Thumbnails & Retina)", 'wp-retina-2x'); ?></a>


			<!-- PROGRESS -->
			<span style='margin-left: 12px; font-size: 13px; top: 5px; position: relative; color: #24547C; font-weight: bold;' id='wr2x_progression'></span>

		</div>

		<?php
			if (isset ( $_GET[ 'clearlogs' ] ) ? $_GET[ 'clearlogs' ] : 0) {
				echo "<div class='updated' style='margin-top: 20px;'><p>";
				_e( "The logs have been cleared.", 'wp-retina-2x' );
				echo "</p></div>";
			}

			$active_sizes = $this->core->get_active_image_sizes();
			$full_size_needed = get_option( "wr2x_full_size" );

			$max_width = 0;
			$max_height = 0;
			foreach ( $active_sizes as $name => $active_size ) {
				if ( $active_size['height'] != 9999 && $active_size['height'] > $max_height ) {
					$max_height = $active_size['height'];
				}
				if ( $active_size['width'] != 9999 && $active_size['width'] > $max_width ) {
					$max_width = $active_size['width'];
				}
			}
			$max_width = $max_width * 2;
			$max_height = $max_height * 2;

			$upload_max_size = $this->core->get_max_filesize();
		?>

		<p>
			<?php printf( __( 'Based on your <i>image sizes</i> settings, the full-size images should be uploaded at a resolution of at least <b>%d×%d</b> for the plugin to be able generate the <b>%d retina images</b>. Please note that it vares depending on your needs for each image (you will need to discuss this with your developer).', 'wp-retina-2x' ), $max_width, $max_height, count( $active_sizes ) ); ?>
			<?php if ( $full_size_needed ) printf( __(  "You <b>also need</b> to upload a retina image for the Full-Size image (might be <b>%d×%d</b>).", 'wp-retina-2x' ), $max_width * 2, $max_height * 2 ); ?>
			<?php _e("You can upload or replace the images by drag & drop.", 'wp-retina-2x' ); ?>
			<?php printf( __( "Your PHP configuration allows uploads of <b>%dMB</b> maximum.", 'wp-retina-2x'), $upload_max_size / 1000000 ); ?>

			<?php
				if ( file_exists( plugin_dir_path( __FILE__ ) . '/wp-retina-2x.log' ) ) {
					printf( __( 'The <a target="_blank" href="%s/wp-retina-2x.log">log file</a> is available. You can also <a href="?page=wp-retina-2x&view=issues&clearlogs=true">clear</a> it.', 'wp-retina-2x' ), plugin_dir_url( __FILE__ ) );
				}
			?>
		</p>

		<?php
			$method = get_option( 'wr2x_method' );
			$cdn = get_option( 'wr2x_cdn_domain' );
			$disable_responsive = get_option( 'wr2x_disable_responsive', false );
			$keep_src = get_option( 'wr2x_picturefill_keep_src', false );

			if ( $method == 'HTML Rewrite' || $method == 'Retina-Images' || $disable_responsive ) {
				echo '<div class="error"><p>';
				echo __( '<b>WARNING</b>. You are using an option that will be removed in a future release. The plan is to remove two methods (HTML Rewrite and Retina-Images), and Disable Responsive. Those options are not necessary, and it is better to keep the plugin clean and focus. This warning message will go away if you avoid using those options (and will disappear in a future release). If you are using one of those options and really would like to keep it, please contact the support.', 'wp-retina-2x' );
				echo '</p></div>';
			}
		?>

		<?php
		if ( !$this->core->admin->is_registered() && !get_option( "wr2x_hide_pro", false ) ) {
			echo '<div class="updated"><p>';
			echo __( '<b>Only Pro users have access to the features of this dashboard.</b> As a standard user, the dashboard allow you to Bulk Generate, Bulk Delete and access the Retina Logs. If you wish to stay a standard user and never see this dashboard aver again, you can hide it in the settings.<br /><br />The Pro version of the plugin allows you to <b>replace directly an image already uploaded in the Media Library</b> by a simple drag & drop, upload a <b>retina image for a full-size image</b>, use <b>lazy-loading</b> to load your images (for better performance) and, more importantly, <b>supports the developer</b> :)<br /><br /><a class="button-primary" href="https://store.meowapps.com/wp-retina-2x/" target="_blank">Get WP Retina 2x Pro</a>', 'wp-retina-2x' );
			echo '</p></div>';
		}
		?>

		<div id='wr2x-pages'>
		<?php
		echo paginate_links(array(
		  'base' => '?page=wp-retina-2x&s=' . urlencode($s) . '&view=' . $view . '%_%',
	      'current' => $paged,
	      'format' => '&paged=%#%',
	      'total' => $pagescount,
	      'prev_next' => false
	    ));
		?>
		</div>

		<ul class="subsubsub">
			<li class="all"><a <?php if ( $view == 'all' ) echo "class='current'"; ?> href='?page=wp-retina-2x&s=<?php echo $s; ?>&view=all'><?php _e( "All", 'wp-retina-2x' ); ?></a><span class="count">(<?php echo $totalcount; ?>)</span></li> |

			<?php if ( $this->core->admin->is_registered() ): ?>

			<li class="all"><a <?php if ( $view == 'issues' ) echo "class='current'"; ?> href='?page=wp-retina-2x&s=<?php echo $s; ?>&view=issues'><?php _e( "Issues", 'wp-retina-2x' ); ?></a><span class="count">(<?php echo $issues_count; ?>)</span></li> |
			<li class="all"><a <?php if ( $view == 'ignored' ) echo "class='current'"; ?> href='?page=wp-retina-2x&s=<?php echo $s; ?>&view=ignored'><?php _e( "Ignored", 'wp-retina-2x' ); ?></a><span class="count">(<?php echo count( $ignored ); ?>)</span></li>

			<?php else: ?>

			<li class="all"><span><?php _e( "Issues", 'wp-retina-2x' ); ?></span> <span class="count">(<?php echo $issues_count; ?>)</span></li> |
			<li class="all"><span><?php _e( "Ignored", 'wp-retina-2x' ); ?></span> <span class="count">(<?php echo count( $ignored ); ?>)</span></li>

			<?php endif; ?>


		</ul>
		<table class='wp-list-table widefat fixed media wr2x-table'>
			<thead><tr>
				<?php
				echo "<th style='width: 56px;'>Thumbnail</th>";
				echo "<th style=' width: 360px;'>" . __( "Base image", 'wp-retina-2x' ) . "</th>";
				echo "<th style=''>" . __( "Media Sizes<br />Retina-ized", 'wp-retina-2x' ) . "</th>";
				echo "<th style=''>" . __( "Full-Size<br/><b>Replace</b>", 'wp-retina-2x' ) . "</th>";
				echo "<th style=''>" . __( "Full-Size Retina", 'wp-retina-2x' ) . "</th>";
				echo "<th style=''>" . __( "Full-Size Retina<br/><b>Upload</b>", 'wp-retina-2x' ) . "</th>";
				?>
			</tr></thead>
			<tbody>
				<?php
				foreach ($results as $index => $attr) {
					$post = $attr['post'];
					$info = $attr['info'];
					$meta = wp_get_attachment_metadata( $post->ID );
					// Let's clean the issues status
					if ( $view != 'issues' ) {
						$this->core->update_issue_status( $post->ID, $issues, $info );
					}
					$original_width = ( isset( $meta ) && isset( $meta['width'] ) ) ? $meta['width'] : null;
					$original_height = ( isset( $meta ) && isset( $meta['height'] ) ) ? $meta['height'] : null;

					$attachmentsrc = wp_get_attachment_image_src( $post->ID, 'thumbnail' );
					echo "<tr class='wr2x-file-row' postId='" . $post->ID . "'>";

					if ( !$original_width || !$original_height ) {
						echo "<td colspan='2' style='padding: 15px;'>The metadata for the <a href='media.php?attachment_id={$post->ID}&action=edit'>Media #{$post->ID}</a> is broken. You can try <b>Generate</b> for this media (in the Media Library), <b>Bulk Generate</b>, or a <b>Full-Size Replace</b>.</td>";
					}
					else {
						echo "<td class='wr2x-image wr2x-info-thumbnail'><img src='" . $attachmentsrc[0] . "' /></td>";
						echo "<td class='wr2x-title'><a href='media.php?attachment_id=" . $post->ID . "&action=edit'>" . ( $post->post_title ? $post->post_title : '<i>Untitled</i>' ) . '</a><br />' .
							"<span class='resolution'>Full-Size: <span class='" . ( $original_width < $max_width ? "red" : "" ) . "'>" . $original_width . "</span>×<span class='" . ( $original_height < $max_height ? "red" : "" ) . "'>" . $original_height . "</span></span>";
						echo "<div class='actions'>";
						echo "<a style='position: relative;' onclick='wr2x_generate(" . $post->ID . ", true)' id='wr2x_generate_button_" . $post->ID . "' class='wr2x-button'>" . __( "GENERATE", 'wp-retina-2x' ) . "</a>";
						if ( !$this->core->is_ignore( $post->ID ) )
							echo " <a href='?page=wp-retina-2x&view=" . $view . "&paged=" . $paged . "&ignore=" . $post->ID . "' id='wr2x_generate_button_" . $post->ID . "' class='wr2x-button wr2x-button-ignore'>" . __( "IGNORE", 'wp-retina-2x' ) . "</a>";
						echo " <a style='position: relative;' class='wr2x-button wr2x-button-view'>" . __( "DETAILS", 'wp-retina-2x' ) . "</a>";
						echo "</div></td>";
					}

					// Media Sizes Retina-ized
					echo '<td id="wr2x-info-' . $post->ID . '" style="padding-top: 10px;" class="wr2x-info">';
					if ( $original_width && $original_height )
						echo $this->core->html_get_basic_retina_info( $post, $info );
					echo "</td>";

					if ( $this->core->admin->is_registered() ) {
						// Full-Size Replace
						echo "<td class='wr2x-fullsize-replace'><div class='wr2x-dragdrop'></div>";
						echo "</td>";
						// Full-Size Retina
						echo '<td id="wr2x-info-full-' . $post->ID . '" class="wr2x-image wr2x-info-full">';
						echo $this->core->html_get_basic_retina_info_full( $post->ID, $info );
						echo "</td>";
						// Full-Size Retina Upload
						echo "<td class='wr2x-fullsize-retina-upload'>";
						echo "<div class='wr2x-dragdrop'></div>";
						echo "</td>";
					}
					else
						echo "<td colspan='3' style='text-align: center; background: #F9F9F9;'><small><br />PRO VERSION ONLY</small></td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
		</div>

		<?php
	}
}
?>
