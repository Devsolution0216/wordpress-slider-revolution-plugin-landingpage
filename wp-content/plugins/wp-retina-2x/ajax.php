<?php

class Meow_WR2X_Ajax {

	public $core = null;

	public function __construct( $core) {
		$this->core = $core;
		add_action( 'wp_ajax_wr2x_generate', array( $this, 'wp_ajax_wr2x_generate' ) );
		add_action( 'wp_ajax_wr2x_delete', array( $this, 'wp_ajax_wr2x_delete' ) );
		add_action( 'wp_ajax_wr2x_delete_full', array( $this, 'wp_ajax_wr2x_delete_full' ) );
		add_action( 'wp_ajax_wr2x_list_all', array( $this, 'wp_ajax_wr2x_list_all' ) );
		add_action( 'wp_ajax_wr2x_replace', array( $this, 'wp_ajax_wr2x_replace' ) );
		add_action( 'wp_ajax_wr2x_upload', array( $this, 'wp_ajax_wr2x_upload' ) );
		add_action( 'wp_ajax_wr2x_retina_upload', array( $this, 'wp_ajax_wr2x_retina_upload' ) );
		add_action( 'wp_ajax_wr2x_retina_details', array( $this, 'wp_ajax_wr2x_retina_details' ) );
	}

	/**
	 * Checks nonce for the specified action
	 * @param string $action
	 */
	function check_nonce( $action ) {
		if ( !wp_verify_nonce( $_POST['nonce'], $action ) ) {
			echo json_encode(
				array (
					'success' => false,
					'message' => __( "Invalid API request.", 'wp-retina-2x' )
				)
			);
			die();
		}
	}

	/**
	 * Checks if the current user has sufficient permissions to perform the Ajax actions
	 */
	function check_capability() {
		$cap = 'upload_files';
		if ( !current_user_can( $cap ) ) {
			echo json_encode(
				array (
					'success' => false,
					'message' => __( "You do not have permission to upload files.", 'wp-retina-2x' )
				)
			);
			die();
		}
	}

	/**
	 *
	 * AJAX SERVER-SIDE
	 *
	 */

	// Using issuesOnly, only the IDs with a PENDING status will be processed
	function wp_ajax_wr2x_list_all( $issuesOnly ) {
		$this->check_nonce( 'wr2x_list_all' );
		$this->check_capability();

		$issuesOnly = intval( $_POST['issuesOnly'] );
		if ( $issuesOnly == 1 ) {
			$ids = $this->core->get_issues();
			echo json_encode(
				array(
					'success' => true,
					'message' => "List of issues only.",
					'ids' => $ids,
					'total' => count( $ids )
			) );
			die;
		}
		$reply = array();
		try {
			$ids = array();
			$total = 0;
			global $wpdb;
			$postids = $wpdb->get_col( "
				SELECT p.ID
				FROM $wpdb->posts p
				WHERE post_status = 'inherit'
				AND post_type = 'attachment'
				AND ( post_mime_type = 'image/jpeg' OR
					post_mime_type = 'image/png' OR
					post_mime_type = 'image/gif' )
			" );
			foreach ($postids as $id) {
				if ( $this->core->is_ignore( $id ) )
					continue;
				array_push( $ids, $id );
				$total++;
			}
			echo json_encode(
				array(
					'success' => true,
					'message' => "List of everything.",
					'ids' => $ids,
					'total' => $total
			) );
			die;
		}
		catch (Exception $e) {
			echo json_encode(
				array(
					'success' => false,
					'message' => $e->getMessage()
			) );
			die;
		}
	}

	function wp_ajax_wr2x_delete_full( $pleaseReturn = false ) {
		if ( !$pleaseReturn ) $this->check_nonce( 'wr2x_delete_full' );
		$this->check_capability();

		if ( !isset( $_POST['attachmentId'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => __( "The attachment ID is missing.", 'wp-retina-2x' )
				)
			);
			die();
		}
		$attachmentId = intval( $_POST['attachmentId'] );
		$originalfile = get_attached_file( $attachmentId );
		$pathinfo = pathinfo( $originalfile );
		$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . $this->core->retina_extension() . $pathinfo['extension'];
		if ( $retina_file && file_exists( $retina_file ) )
			unlink( $retina_file );

		// RESULTS FOR RETINA DASHBOARD
		$info = $this->core->html_get_basic_retina_info_full( $attachmentId, $this->core->retina_info( $attachmentId ) );
		$results[$attachmentId] = $info;

		// Return if that's not the final step.
		if ( $pleaseReturn )
			return $info;

		echo json_encode(
			array(
				'results' => $results,
				'success' => true,
				'message' => __( "Full retina file deleted.", 'wp-retina-2x' )
			)
		);
		die();
	}

	function wp_ajax_wr2x_delete() {
		$this->check_nonce( 'wr2x_delete' );
		$this->check_capability();

		if ( !isset( $_POST['attachmentId'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => __( "The attachment ID is missing.", 'wp-retina-2x' )
				)
			);
			die();
		}

		// Information for the retina version of the full-size
		$attachmentId = intval( $_POST['attachmentId'] );
		$results_full[$attachmentId] = $this->wp_ajax_wr2x_delete_full( true );

		$this->core->delete_attachment( $attachmentId, true );
		$meta = wp_get_attachment_metadata( $attachmentId );

		// RESULTS FOR RETINA DASHBOARD
		$this->core->update_issue_status( $attachmentId );
		$info = $this->core->html_get_basic_retina_info( $attachmentId, $this->core->retina_info( $attachmentId ) );
		$results[$attachmentId] = $info;
		echo json_encode(
			array(
				'results' => $results,
				'results_full' => $results_full,
				'success' => true,
				'message' => __( "Retina files deleted.", 'wp-retina-2x' )
			)
		);
		die();
	}

	function wp_ajax_wr2x_retina_details() {
		$this->check_nonce( 'wr2x_retina_details' );
		$this->check_capability();

		if ( !isset( $_POST['attachmentId'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => __( "The attachment ID is missing.", 'wp-retina-2x' )
				)
			);
			die();
		}

		$attachmentId = intval( $_POST['attachmentId'] );
		$info = $this->core->html_get_details_retina_info( $attachmentId, $this->core->retina_info( $attachmentId ) );
		echo json_encode(
			array(
				'result' => $info,
				'success' => true,
				'message' => __( "Details retrieved.", 'wp-retina-2x' )
			)
		);
		die();
	}

	function wp_ajax_wr2x_generate() {
		$this->check_nonce( 'wr2x_generate' );
		$this->check_capability();

		if ( !isset( $_POST['attachmentId'] ) ) {
			echo json_encode(
				array(
					'success' => false,
					'message' => __( "The attachment ID is missing.", 'wp-retina-2x' )
				)
			);
			die();
		}

		$attachmentId = intval( $_POST['attachmentId'] );

		/**
		 * @param $attachmentId ID of the attchment to regenerate
		 */
		do_action( 'wr2x_before_regenerate', $attachmentId );

		$this->core->delete_attachment( $attachmentId, false );

		// Regenerate the Thumbnails
		$regenerate = get_option( 'wr2x_regenerate_thumbnails', false );
		if ( $regenerate ) {

			/**
			 * @param $attachmentId ID of the attachment to generate thumbnails
			 */
			do_action( 'wr2x_before_generate_thumbnails', $attachmentId );

			$file = get_attached_file( $attachmentId );
			$meta = wp_generate_attachment_metadata( $attachmentId, $file );
			wp_update_attachment_metadata( $attachmentId, $meta );

			/**
			 * @param $attachmentId ID of the attachment that has generated its thumbnails
			 */
			do_action( 'wr2x_generate_thumbnails', $attachmentId );
		}

		// Regenerate Retina
		$meta = wp_get_attachment_metadata( $attachmentId );
		$this->core->generate_images( $meta );

		/**
		 * @param $attachmentId ID of the attachment that has been regenerated
		 */
		do_action( 'wr2x_regenerate', $attachmentId );

		// RESULTS FOR RETINA DASHBOARD
		$info = $this->core->html_get_basic_retina_info( $attachmentId, $this->core->retina_info( $attachmentId ) );
		$results[$attachmentId] = $info;
		echo json_encode(
			array(
				'results' => $results,
				'success' => true,
				'message' => __( "Retina files generated.", 'wp-retina-2x' )
			)
		);
		die();
	}

	function check_get_ajax_uploaded_file() {
		$this->check_capability();

		$tmpfname = $_FILES['file']['tmp_name'];

		// Check if it is an image
		$file_info = getimagesize( $tmpfname );
		if ( empty( $file_info ) ) {
			$this->core->log( "The file is not an image or the upload went wrong." );
			unlink( $tmpfname );
			echo json_encode( array(
				'success' => false,
				'message' => __( "The file is not an image or the upload went wrong.", 'wp-retina-2x' )
			));
			die();
		}

		$filedata = wp_check_filetype_and_ext( $tmpfname, $_POST['filename'] );
		if ( $filedata["ext"] == "" ) {
			$this->core->log( "You cannot use this file (wrong extension? wrong type?)." );
			unlink( $current_file );
			echo json_encode( array(
				'success' => false,
				'message' => __( "You cannot use this file (wrong extension? wrong type?).", 'wp-retina-2x' )
			));
			die();
		}

		$this->core->log( "The temporary file was written successfully." );
		return $tmpfname;
	}

	function wp_ajax_wr2x_upload( $checksNonce = true ) {
		if ( $checksNonce ) $this->check_nonce( 'wr2x_upload' );

		try {
			$tmpfname = $this->check_get_ajax_uploaded_file();
			$attachmentId = (int) $_POST['attachmentId'];
			$meta = wp_get_attachment_metadata( $attachmentId );
			$current_file = get_attached_file( $attachmentId );
			$pathinfo = pathinfo( $current_file );
			$basepath = $pathinfo['dirname'];
			$retinafile = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . $this->core->retina_extension() . $pathinfo['extension'];

			/**
			 * @param $attachmentId ID of the attachment that the uploaded retina image is attached to
			 * @param $retinafile Path to the uploaded retina image
			 */
			do_action( 'wr2x_before_upload_retina', $attachmentId, $retinafile );

			if ( file_exists( $retinafile ) )
				unlink( $retinafile );

			// Insert the new file and delete the temporary one
			list( $width, $height ) = getimagesize( $tmpfname );

			if ( !$this->core->are_dimensions_ok( $width, $height, $meta['width'] * 2, $meta['height'] * 2 ) ) {
				echo json_encode( array(
					'success' => false,
					'message' => "This image has a resolution of ${width}×${height} but your Full Size image requires a retina image of at least " . ( $meta['width'] * 2 ) . "x" . ( $meta['height'] * 2 ) . "."
				));
				die();
			}
			$this->core->resize( $tmpfname, $meta['width'] * 2, $meta['height'] * 2, null, $retinafile );
			chmod( $retinafile, 0644 );
			unlink( $tmpfname );

			/**
			 * @param $attachmentId ID of the attachment that the uploaded retina image is attached to
			 * @param $retinafile Path to the uploaded retina image
			 */
			do_action( 'wr2x_upload_retina', $attachmentId, $retinafile );

			// Get the results
			$info = $this->core->retina_info( $attachmentId );
			$this->core->update_issue_status( $attachmentId );
			$results[$attachmentId] = $this->core->html_get_basic_retina_info_full( $attachmentId, $info );
		}
		catch (Exception $e) {
			echo json_encode( array(
				'success' => false,
				'results' => null,
				'message' => __( "Error: " . $e->getMessage(), 'wp-retina-2x' )
			));
			die();
		}

		echo json_encode( array(
			'success' => true,
			'results' => $results,
			'message' => __( "Uploaded successfully.", 'wp-retina-2x' ),
			'media' => array(
				'id' => $attachmentId,
				'src' => wp_get_attachment_image_src( $attachmentId, 'thumbnail' ),
				'edit_url' => get_edit_post_link( $attachmentId, 'attribute' )
			)
		));
		die();
	}

	function wp_ajax_wr2x_retina_upload() {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$this->check_nonce( 'wr2x_retina_upload' );
		$this->check_capability();

		try {
			$tmpf = $this->check_get_ajax_uploaded_file();
			$ftype = wp_check_filetype( $_POST['filename'] );
			$image = wp_get_image_editor( $tmpf, array ( 'mime_type' => $ftype['type'] ) );
			$size = $image->get_size();

			// Halve the size of the uploaded image
			if ( $size['width'] >= $size['height'] ) $image->resize( round($size['width'] * .5), null );
			else $image->resize( null, round($size['height'] * .5) );
			$image->set_quality( get_option('wr2x_quality', 90) );
			$halved = $image->save( $tmpf . 'H', $ftype['type'] );
			if ( !$halved ) throw new Exception( "Failed to halve the uploaded image" );
			if ( is_wp_error($halved) ) throw new Exception( $halved->get_error_message() );

			// Upload the halved image
			$content = file_get_contents( $halved['path'] );
			if ( $content === false ) throw new Exception( "Couldn't read the uploaded file: {$halved['file']}" );
			$uploaded = wp_upload_bits( $_POST['filename'], null, $content );
			if ( isset($uploaded['error']) && $uploaded['error'] ) throw new Exception( $uploaded['error'] );

			// Register the file as a new attachment
			$attachTo = 0; // TODO Support specifying which post the media attach to
			$attachment = array (
				'post_mime_type' => $ftype['type'],
				'post_parent' => $attachTo,
				'post_title' => preg_replace( '/\.[^.]+$/', '', $_POST['filename'] ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachmentId = wp_insert_attachment( $attachment, $uploaded['file'], $attachTo );
			if ( !$attachmentId ) throw new Exception( "Couldn't add an attachment file: {$uploaded['file']}" );
			if ( is_wp_error($attachmentId) ) throw new Exception( $attachmentId->get_error_message() );
			$meta = wp_generate_attachment_metadata( $attachmentId, $uploaded['file'] );
			wp_update_attachment_metadata( $attachmentId, $meta );

		} catch ( Exception $e ) {
			echo json_encode( array (
				'success' => false,
				'results' => null,
				'message' => __( "Error: " . $e->getMessage(), 'wp-retina-2x' )
			));
			die();
		}
		// Redirect to 'wr2x_upload'
		$_POST['attachmentId'] = $attachmentId;
		$this->wp_ajax_wr2x_upload( false );
	}

	function wp_ajax_wr2x_replace() {
		$this->check_nonce( 'wr2x_replace' );

		$tmpfname = $this->check_get_ajax_uploaded_file();
		$attachmentId = (int) $_POST['attachmentId'];
		$meta = wp_get_attachment_metadata( $attachmentId );
		$current_file = get_attached_file( $attachmentId );

		/**
		 * @param $attachmentId ID of the attachment to replace
		 * @param $tmpfname Path to the temporary file that is to be the replacement
		 */
		do_action( 'wr2x_before_replace', $attachmentId, $tmpfname );

		$this->core->delete_attachment( $attachmentId, false );
		$pathinfo = pathinfo( $current_file );
		$basepath = $pathinfo['dirname'];

		// Let's clean everything first
		if ( wp_attachment_is_image( $attachmentId ) ) {
			$sizes = $this->core->get_image_sizes();
			foreach ($sizes as $name => $attr) {
				if ( isset( $meta['sizes'][$name] ) && isset( $meta['sizes'][$name]['file'] ) && file_exists( trailingslashit( $basepath ) . $meta['sizes'][$name]['file'] ) ) {
					$normal_file = trailingslashit( $basepath ) . $meta['sizes'][$name]['file'];
					$pathinfo = pathinfo( $normal_file );
					$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . $this->core->retina_extension() . $pathinfo['extension'];

					// Test if the file exists and if it is actually a file (and not a dir)
					// Some old WordPress Media Library are sometimes broken and link to directories
					if ( file_exists( $normal_file ) && is_file( $normal_file ) )
						unlink( $normal_file );
					if ( file_exists( $retina_file ) && is_file( $retina_file ) )
						unlink( $retina_file );
				}
			}
		}
		if ( file_exists( $current_file ) )
			unlink( $current_file );

		// Insert the new file and delete the temporary one
		rename( $tmpfname, $current_file );
		chmod( $current_file, 0644 );

		// Generate the images
		wp_update_attachment_metadata( $attachmentId, wp_generate_attachment_metadata( $attachmentId, $current_file ) );
		$meta = wp_get_attachment_metadata( $attachmentId );
		$this->core->generate_images( $meta );

		/**
		 * @param $attachmentId ID of the attachment that has been replaced
		 */
		do_action( 'wr2x_replace', $attachmentId );

		// Get the results
		$info = $this->core->retina_info( $attachmentId );
		$results[$attachmentId] = $this->core->html_get_basic_retina_info( $attachmentId, $info );

		echo json_encode( array(
			'success' => true,
			'results' => $results,
			'message' => __( "Replaced successfully.", 'wp-retina-2x' )
		));
		die();
	}

}

?>
