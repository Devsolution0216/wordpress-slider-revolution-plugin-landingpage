<?php

require __DIR__ . '/vendor/autoload.php';

class Meow_WR2X_Core {

	public $admin = null;

	public function __construct( $admin ) {
		$this->admin = $admin;
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'wp_generate_attachment_metadata' ) );
		add_action( 'delete_attachment', array( $this, 'delete_attachment' ) );
		add_filter( 'generate_rewrite_rules', array( 'Meow_WR2X_Admin', 'generate_rewrite_rules' ) );
		add_filter( 'retina_validate_src', array( $this, 'validate_src' ) );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'calculate_image_srcset' ), 1000, 3 );
		add_action( 'init', array( $this, 'init' ) );
		include( __DIR__ . '/api.php' );

		if ( get_option( 'wr2x_big_image_size_threshold', false ) ) {
			add_filter( 'big_image_size_threshold', array( $this, 'big_image_size_threshold' ) );
		}

		// In Admin
		if ( is_admin() ) {
			include( __DIR__ . '/ajax.php' );
			new Meow_WR2X_Ajax( $this );
			if ( !get_option( "wr2x_hide_retina_dashboard" ) ) {
				include( __DIR__ . '/dashboard.php' );
				new Meow_WR2X_Dashboard( $this );
			}
			if ( !get_option( "wr2x_hide_retina_column" ) ) {
				include( __DIR__ . '/media-library.php' );
				new Meow_WR2X_MediaLibrary( $this );
			}
		}
	}

	function big_image_size_threshold() {
		return false;
	}

	function is_rest() {
    if ( empty( $_SERVER[ 'REQUEST_URI' ] ) )
    	return false;
    $rest_prefix = trailingslashit( rest_get_url_prefix() );
    return strpos( $_SERVER[ 'REQUEST_URI' ], $rest_prefix ) !== false ? true : false;
	}

	function init() {
		//load_plugin_textdomain( 'wp-retina-2x', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( get_option( 'wr2x_disable_medium_large' ) ) {
			remove_image_size( 'medium_large' );
			add_filter( 'image_size_names_choose', array( $this, 'unset_medium_large' ) );
			add_filter( 'intermediate_image_sizes_advanced', array( $this, 'unset_medium_large' ) );
		}

		if ( $this->is_rest() ) {
			return;
		}

		if ( is_admin() ) {
			wp_register_style( 'wr2x-admin-css', plugins_url( '/wr2x_admin.css', __FILE__ ) );
			wp_enqueue_style( 'wr2x-admin-css' );
			if ( !get_option( "wr2x_retina_admin" ) )
				return;
		}

		$method = get_option( "wr2x_method" );
		if ( $method == "Picturefill" ) {
			add_action( 'wp_head', array( $this, 'picture_buffer_start' ) );
			add_action( 'wp_footer', array( $this, 'picture_buffer_end' ) );
		}
		else if ( $method == 'HTML Rewrite' ) {
			$is_retina = false;
			if ( isset( $_COOKIE['devicePixelRatio'] ) ) {
				$is_retina = ceil( floatval( $_COOKIE['devicePixelRatio'] ) ) > 1;
			}
			if ( $is_retina || $this->is_debug() ) {
				add_action( 'wp_head', array( $this, 'buffer_start' ) );
				add_action( 'wp_footer', array( $this, 'buffer_end' ) );
			}
		}

	}

	function unset_medium_large( $sizes ) {
		unset( $sizes['medium_large'] );
		return $sizes;
	}

	/**
	 *
	 * PICTURE METHOD
	 *
	 */

	function is_supported_image( $url ) {
		$wr2x_supported_image = array( 'jpg', 'jpeg', 'png', 'gif' );
		$ext = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		if ( !in_array( $ext, $wr2x_supported_image ) ) {
			$this->log( "Extension (" . $ext . ") is not " . implode( ', ', $wr2x_supported_image ) . "." );
			return false;
		}
		return true;
	}

	function picture_buffer_start() {
		ob_start( array( $this, "picture_rewrite" ) );
		$this->log( "* HTML REWRITE FOR PICTUREFILL" );
	}

	function picture_buffer_end() {
		@ob_end_flush();
	}

	// Replace the IMG tags by PICTURE tags with SRCSET
	function picture_rewrite( $buffer ) {
		if ( !isset( $buffer ) || trim( $buffer ) === '' )
			return $buffer;
		$html = new KubAT\PhpSimple\HtmlDomParser();
		$lazysize = get_option( "wr2x_picturefill_lazysizes" ) && $this->admin->is_registered();
		$killSrc = !get_option( "wr2x_picturefill_keep_src" );
		$nodes_count = 0;
		$nodes_replaced = 0;
		$html = $html->str_get_html( $buffer );
		if ( !$html ) {
			$this->log( "The HTML buffer is null, another plugin might block the process." );
			return $buffer;
		}

		// IMG TAGS
		foreach( $html->find( 'img' ) as $element ) {
			$nodes_count++;
			$parent = $element->parent();
			if ( $parent->tag == "picture" ) {
				$this->log("The img tag is inside a picture tag. Tag ignored.");
				continue;
			}
			else {
				$valid = apply_filters( "wr2x_validate_src", $element->src );
				if ( empty( $valid ) ) {
					$nodes_count--;
					continue;
				}

				// Original HTML
				$from = substr( $element, 0 );

				// SRC-SET already exists, let's check if LazySize is used
				if ( !empty( $element->srcset ) ) {
					if ( $lazysize ) {
						$this->log( "The src-set has already been created but it will be modifid to data-srcset for lazyload." );
						$element->class = $element->class . ' lazyload';
						$element->{'data-srcset'} =  $element->srcset;
						if ( $killSrc ) {
							$element->src = null;
						}
						else {
							// If SRC is kept, to avoid it to load before LazySizes kicks in, we set srcset to a blank 1x1 pixel.
							$element->srcset = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
						}
						$to = $element;
						$buffer = str_replace( trim( $from, "</> "), trim( $to, "</> " ), $buffer );
						$this->log( "The img tag '$from' was rewritten to '$to'" );
						$nodes_replaced++;
					}
					else {
						$this->log( "The src-set has already been created. Tag ignored." );
					}
					continue;
				}

				// Process of SRC-SET creation
				if ( !$this->is_supported_image( $element->src ) ) {
					$nodes_count--;
					continue;
				}
				$retina_url = $this->get_retina_from_url( $element->src );
				$retina_url = apply_filters( 'wr2x_img_retina_url', $retina_url );
				if ( $retina_url != null ) {
					$retina_url = $this->cdn_this( $retina_url );
					$img_url = $this->cdn_this( $element->src );
					$img_url  = apply_filters( 'wr2x_img_url', $img_url  );
					if ( $lazysize ) {
						$element->class = $element->class . ' lazyload';
						$element->{'data-srcset'} =  "$img_url, $retina_url 2x";
					}
					else
						$element->srcset = "$img_url, $retina_url 2x";
					if ( $killSrc )
						$element->src = null;
					else {
						$img_src = apply_filters( 'wr2x_img_src', $element->src  );
						$element->src = $this->cdn_this( $img_src );
					}
					$to = $element;
					$buffer = str_replace( trim( $from, "</> "), trim( $to, "</> " ), $buffer );
					$this->log( "The img tag '$from' was rewritten to '$to'" );
					$nodes_replaced++;
				}
				else {
					$this->log( "The img tag was not rewritten. No retina for '" . $element->src . "'." );
				}
			}
		}
		$this->log( "$nodes_replaced/$nodes_count img tags were replaced." );

		// INLINE CSS BACKGROUND
		if ( get_option( 'wr2x_picturefill_css_background', false ) && $this->admin->is_registered() ) {
			// Standard CSS background
			preg_match_all( "/url(?:\(['\"]?)(.*?)(?:['\"]?\))/", $buffer, $matches );
			//error_log( print_r( $matches, 1 ) );
			if ( count( $matches ) == 2 ) {
				$match_css = $matches[0];
				$match_url = $matches[1];
			}
			// Lazy CSS background
			preg_match_all( "/data-background=(?:['\"])(.*?)(?:['\"])/", $buffer, $matches );
			if ( count( $matches ) == 2 ) {
				$match_css = array_merge( $match_css, $matches[0] );
				$match_url = array_merge( $match_url, $matches[1] );
			}
			// Lazy CSS background
			preg_match_all( "/data-bigimg=(?:['\"])(.*?)(?:['\"])/", $buffer, $matches );
			if ( count( $matches ) == 2 ) {
				$match_css = array_merge( $match_css, $matches[0] );
				$match_url = array_merge( $match_url, $matches[1] );
			}
			$nodes_count = 0;
			$nodes_replaced = 0;
			for ( $c = 0; $c < count( $match_css ); $c++ ) {
				$css = $match_css[$c];
				$url = $match_url[$c];
				if ( !$this->is_supported_image( $url ) )
					continue;
				$nodes_count++;
				$retina_url = $this->get_retina_from_url( $url );
				$retina_url = apply_filters( 'wr2x_img_retina_url', $retina_url );
				if ( $retina_url != null ) {
					$retina_url = $this->cdn_this( $retina_url );
					$minibuffer = str_replace( $url, $retina_url, $css );
					$buffer = str_replace( $css, $minibuffer, $buffer );
					$this->log( "The background src '$css' was rewritten to '$minibuffer'" );
					$nodes_replaced++;
				}
				else {
					$this->log( "The background src was not rewritten. No retina for '" . $url . "'." );
				}
			}
			$this->log( "$nodes_replaced/$nodes_count background src were replaced." );
		}

		$html->clear();
		return $buffer;
	}

	/**
	 *
	 * HTML REWRITE METHOD
	 *
	 */

	function buffer_start () {
		ob_start( array( $this, "html_rewrite" ) );
		$this->log( "* HTML REWRITE" );
	}

	function buffer_end () {
		@ob_end_flush();
	}

	// Replace the images by retina images (if available)
	function html_rewrite( $buffer ) {
		if ( !isset( $buffer ) || trim( $buffer ) === '' )
			return $buffer;
		$nodes_count = 0;
		$nodes_replaced = 0;
		$doc = new DOMDocument();
		@$doc->loadHTML( $buffer ); // = ($doc->strictErrorChecking = false;)
		$imageTags = $doc->getElementsByTagName('img');
		foreach ( $imageTags as $tag ) {
			$nodes_count++;
			$img_pathinfo = $this->get_pathinfo_from_image_src( $tag->getAttribute('src') );
			$filepath = trailingslashit( $this->get_upload_root() ) . $img_pathinfo;
			$system_retina = $this->get_retina( $filepath );
			if ( $system_retina != null ) {
				$retina_pathinfo = $this->cdn_this( ltrim( str_replace( $this->get_upload_root(), "", $system_retina ), '/' ) );
				$buffer = str_replace( $img_pathinfo, $retina_pathinfo, $buffer );
				$this->log( "The img src '$img_pathinfo' was replaced by '$retina_pathinfo'" );
				$nodes_replaced++;
			}
			else {
				$this->log( "The file '$system_retina' was not found. Tag not modified." );
			}
		}
		$this->log( "$nodes_replaced/$nodes_count were replaced." );
		return $buffer;
	}


	// Converts PHP INI size type (e.g. 24M) to int
	function parse_ini_size( $size ) {
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
		$size = preg_replace('/[^0-9\.]/', '', $size);
		if ( $unit )
			return round( $size * pow( 1024, stripos( 'bkmgtpezy', $unit[0] ) ) );
		else
			round( $size );
	}

	function get_max_filesize() {
		if ( defined ('HHVM_VERSION' ) ) {
			$post_max_size = ini_get( 'post_max_size' ) ? (int)$this->parse_ini_size( ini_get( 'post_max_size' ) ) : (int)ini_get( 'hhvm.server.max_post_size' );
			$upload_max_filesize = ini_get( 'upload_max_filesize' ) ? (int)$this->parse_ini_size( ini_get( 'upload_max_filesize' ) ) :
				(int)ini_get( 'hhvm.server.upload.upload_max_file_size' );
		}
		else {
			$post_max_size = (int)$this->parse_ini_size( ini_get( 'post_max_size' ) );
			$upload_max_filesize = (int)$this->parse_ini_size( ini_get( 'upload_max_filesize' ) );
		}
		$max = min( $post_max_size, $upload_max_filesize );
		return $max > 0 ? $max : 66600000;
	}

	/**
	 *
	 * RESPONSIVE IMAGES METHOD
	 *
	 */

	function calculate_image_srcset( $srcset, $size ) {
		if ( get_option( "wr2x_disable_responsive" ) )
			return null;
		$method = get_option( "wr2x_method"  );
		if ( $method == "none" )
			return $srcset;
		$count = 0;
		$total = 0;
		$retinized_srcset = $srcset;
		if ( empty( $srcset ) )
			return $srcset;
		foreach ( $srcset as $s => $cfg ) {
			$total++;
			$retina = $this->cdn_this( $this->get_retina_from_url( $cfg['url'] ) );
			if ( !empty( $retina ) ) {
				$count++;
				$retinized_srcset[(int)$s * 2] = array(
					'url' => $retina,
					'descriptor' => 'w',
					'value' => (int)$s * 2 );
			}
		}
		$this->log( "WP's srcset: " . $count . " retina files added out of " . $total . " image sizes" );
		return $retinized_srcset;
	}

	/**
	 *
	 * ISSUES CALCULATION AND FUNCTIONS
	 *
	 */

	// Compares two images dimensions (resolutions) against each while accepting an margin error
	function are_dimensions_ok( $width, $height, $retina_width, $retina_height ) {
		$w_margin = $width - $retina_width;
		$h_margin = $height - $retina_height;
		return ( $w_margin >= -2 && $h_margin >= -2 );
	}

	// UPDATE THE ISSUE STATUS OF THIS ATTACHMENT
	function update_issue_status( $attachmentId, $issues = null, $info = null ) {
		if ( $this->is_ignore( $attachmentId ) )
			return;
		if ( $issues == null )
			$issues = $this->get_issues();
		if ( $info == null )
			$info = $this->retina_info( $attachmentId );
		$consideredIssue = in_array( $attachmentId, $issues );
		$realIssue = $this->info_has_issues( $info );
		if ( $consideredIssue && !$realIssue )
			$this->remove_issue( $attachmentId );
		else if ( !$consideredIssue && $realIssue )
			$this->add_issue( $attachmentId );
		return $realIssue;
	}

	function get_issues() {
		$issues = get_transient( 'wr2x_issues' );
		if ( !$issues || !is_array( $issues ) ) {
			$issues = array();
			set_transient( 'wr2x_issues', $issues );
		}
		return $issues;
	}

	// CHECK IF THE 'INFO' OBJECT CONTAINS ISSUE (RETURN TRUE OR FALSE)
	function info_has_issues( $info ) {
		foreach ( $info as $aindex => $aval ) {
			if ( is_array( $aval ) || $aval == 'PENDING' )
				return true;
		}
		return false;
	}

	function calculate_issues() {
		global $wpdb;
		$postids = $wpdb->get_col( "
			SELECT p.ID FROM $wpdb->posts p
			WHERE post_status = 'inherit'
			AND post_type = 'attachment'" . $this->create_sql_if_wpml_original() . "
			AND ( post_mime_type = 'image/jpeg' OR
				post_mime_type = 'image/jpg' OR
				post_mime_type = 'image/png' OR
				post_mime_type = 'image/gif' )
		" );
		$issues = array();
		foreach ( $postids as $id ) {
			$info = $this->retina_info( $id );
			if ( $this->info_has_issues( $info ) )
				array_push( $issues, $id );

		}
		set_transient( 'wr2x_ignores', array() );
		set_transient( 'wr2x_issues', $issues );
	}

	function add_issue( $attachmentId ) {
		if ( $this->is_ignore( $attachmentId ) )
			return;
		$issues = $this->get_issues();
		if ( !in_array( $attachmentId, $issues ) ) {
			array_push( $issues, $attachmentId );
			set_transient( 'wr2x_issues', $issues );
		}
		return $issues;
	}

	function remove_issue( $attachmentId, $onlyIgnore = false ) {
		$issues = array_diff( $this->get_issues(), array( $attachmentId ) );
		set_transient( 'wr2x_issues', $issues );
		if ( !$onlyIgnore )
			$this->remove_ignore( $attachmentId );
		return $issues;
	}

	// IGNORE

	function get_ignores( $force = false ) {
		$ignores = get_transient( 'wr2x_ignores' );
		if ( !$ignores || !is_array( $ignores ) ) {
			$ignores = array();
			set_transient( 'wr2x_ignores', $ignores );
		}
		return $ignores;
	}

	function is_ignore( $attachmentId ) {
		$ignores = $this->get_ignores();
		return in_array( $attachmentId, $this->get_ignores() );
	}

	function remove_ignore( $attachmentId ) {
		$ignores = $this->get_ignores();
		$ignores = array_diff( $ignores, array( $attachmentId ) );
		set_transient( 'wr2x_ignores', $ignores );
		return $ignores;
	}

	function add_ignore( $attachmentId ) {
		$ignores = $this->get_ignores();
		if ( !in_array( $attachmentId, $ignores ) ) {
			array_push( $ignores, $attachmentId );
			set_transient( 'wr2x_ignores', $ignores );
		}
		$this->remove_issue( $attachmentId, true );
		return $ignores;
	}

	/**
	 *
	 * INFORMATION ABOUT THE RETINA IMAGE IN HTML
	 *
	 */

	function html_get_basic_retina_info_full( $attachmentId, $retina_info ) {
		$status = ( isset( $retina_info ) && isset( $retina_info['full-size'] ) ) ? $retina_info['full-size'] : 'IGNORED';
		if ( $status == 'EXISTS' ) {
			return '<ul class="meow-sized-images"><li class="meow-bk-blue" title="full-size"></li></ul>';
		}
		else if ( is_array( $status ) ) {
			return '<ul class="meow-sized-images"><li class="meow-bk-orange" title="full-size"></li></ul>';
		}
		else if ( $status == 'IGNORED' ) {
			return __( "N/A", "wp-retina-2x" );
		}
		return $status;
	}

	function format_title( $i, $size ) {
		return $i . ' (' . ( $size['width'] * 2 ) . 'x' . ( $size['height'] * 2 ) . ')';
	}

	// Information for the 'Media Sizes Retina-ized' Column in the Retina Dashboard
	function html_get_basic_retina_info( $attachmentId, $retina_info ) {
		$sizes = $this->get_active_image_sizes();
		$result = '<ul class="meow-sized-images" postid="' . ( is_integer( $attachmentId ) ? $attachmentId : $attachmentId->ID ) . '">';
		foreach ( $sizes as $i => $size ) {
			$status = ( isset( $retina_info ) && isset( $retina_info[$i] ) ) ? $retina_info[$i] : null;
			if ( is_array( $status ) )
				$result .= '<li class="meow-bk-red" title="' . $this->format_title( $i, $size ) . '">'
					. MeowApps_Admin::size_shortname( $i ) . '</li>';
			else if ( $status == 'EXISTS' )
				$result .= '<li class="meow-bk-blue" title="' . $this->format_title( $i, $size ) . '">'
					. MeowApps_Admin::size_shortname( $i ) . '</li>';
			else if ( $status == 'PENDING' )
				$result .= '<li class="meow-bk-orange" title="' . $this->format_title( $i, $size ) . '">'
					. MeowApps_Admin::size_shortname( $i ) . '</li>';
			else if ( $status == 'MISSING' )
				$result .= '<li class="meow-bk-red" title="' . $this->format_title( $i, $size ) . '">'
					. MeowApps_Admin::size_shortname( $i ) . '</li>';
			else if ( $status == 'IGNORED' )
				$result .= '<li class="meow-bk-gray" title="' . $this->format_title( $i, $size ) . '">'
					. MeowApps_Admin::size_shortname( $i ) . '</li>';
			else {
				error_log( "Retina: This status is not recognized: " . $status );
			}
		}
		$result .= '</ul>';
		return $result;
	}

	// Information for Details in the Retina Dashboard
	function html_get_details_retina_info( $post, $retina_info ) {
		if ( !$this->admin->is_registered() ) {
			return __( "PRO VERSION ONLY", 'wp-retina-2x' );
		}

		$sizes = $this->get_image_sizes();
		$total = 0; $possible = 0; $issue = 0; $ignored = 0; $retina = 0;

		$postinfo = get_post( $post, OBJECT );
		$meta = wp_get_attachment_metadata( $post );
		$fullsize_file = get_attached_file( $post );
		$pathinfo_system = pathinfo( $fullsize_file );
		$pathinfo = pathinfo( $meta['file'] );
		$uploads = wp_upload_dir();
		$basepath_url = trailingslashit( $uploads['baseurl'] ) . $pathinfo['dirname'];
		if ( get_option( "wr2x_full_size" ) ) {
			$sizes['full-size']['file'] = $pathinfo['basename'];
			$sizes['full-size']['width'] = $meta['width'];
			$sizes['full-size']['height'] = $meta['height'];
			$meta['sizes']['full-size']['file'] = $pathinfo['basename'];
			$meta['sizes']['full-size']['width'] = $meta['width'];
			$meta['sizes']['full-size']['height'] = $meta['height'];
		}
		$result = "<p>This screen displays all the image sizes set-up by your WordPress configuration with the Retina details.</p>";
		$result .= "<br /><a target='_blank' href='" . trailingslashit( $uploads['baseurl'] ) . $meta['file'] . "'><img src='" . trailingslashit( $uploads['baseurl'] ) . $meta['file'] . "' height='100px' style='float: left; margin-right: 10px;' /></a><div class='base-info'>";
		$result .= "Title: <b>" . ( $postinfo->post_title ? $postinfo->post_title : '<i>Untitled</i>' ) . "</b><br />";
		$result .= "Full-size: <b>" . $meta['width'] . "×" . $meta['height'] . "</b><br />";
		$result .= "Image URL: <a target='_blank' href='" . trailingslashit( $uploads['baseurl'] ) . $meta['file'] . "'>" . trailingslashit( $uploads['baseurl'] ) . $meta['file'] . "</a><br />";
		$result .= "Image Path: " . $fullsize_file . "<br />";
		$result .= "</div><div style='clear: both;'></div><br />";
		$result .= "<div class='scrollable-info'>";

		foreach ( $sizes as $i => $sizemeta ) {
			$total++;
			$normal_file_system = ""; $retina_file_system = "";
			$normal_file = ""; $retina_file = ""; $width = ""; $height = "";

			if ( isset( $retina_info[$i] ) && $retina_info[$i] == 'IGNORED' ) {
				$status = "IGNORED";
			}
			else if ( !isset( $meta['sizes'] ) ) {
				$statusText  = __( "The metadata is broken! This is not related to the retina plugin. You should probably use a plugin to re-generate the missing metadata and images.", 'wp-retina-2x' );
				$status = "MISSING";
			}
			else if ( !isset( $meta['sizes'][$i] ) ) {
				$statusText  = sprintf( __( "The image size '%s' could not be found. You probably changed your image sizes but this specific image was not re-build. This is not related to the retina plugin. You should probably use a plugin to re-generate the missing metadata and images.", 'wp-retina-2x' ), $i );
				$status = "MISSING";
			}
			else {
				$normal_file_system = trailingslashit( $pathinfo_system['dirname'] ) . $meta['sizes'][$i]['file'];
				$retina_file_system = $this->get_retina( $normal_file_system );
				$normal_file = trailingslashit( $basepath_url ) . $meta['sizes'][$i]['file'];
				$retina_file = $this->get_retina_from_url( $normal_file );
				$status = ( isset( $retina_info ) && isset( $retina_info[$i] ) ) ? $retina_info[$i] : null;
				$width = $meta['sizes'][$i]['width'];
				$height = $meta['sizes'][$i]['height'];
			}

			$result .= "<h3>";

			// Status Icon
			if ( is_array( $status ) && $i == 'full-size' ) {
				$result .= '<div class="meow-sized-image meow-bk-red"></div>';
				$statusText = sprintf( __( "The retina version of the Full-Size image is missing.<br />Full Size Retina has been checked in the Settings and this image is therefore required.<br />Please drag & drop an image of at least <b>%dx%d</b> in the <b>Full-Size Retina Upload</b> column.", 'wp-retina-2x' ), $status['width'], $status['height'] );
			}
			else if ( is_array( $status ) ) {
				$result .= '<div class="meow-sized-image meow-bk-red"></div>';
				$statusText = sprintf( __( "The Full-Size image is too small (<b>%dx%d</b>) and this size cannot be generated.<br />Please upload an image of at least <b>%dx%d</b>.", 'wp-retina-2x' ), $meta['width'], $meta['height'], $status['width'], $status['height'] );
				$issue++;
			}
			else if ( $status == 'EXISTS' ) {
				$result .= '<div class="meow-sized-image meow-bk-blue"></div>';
				$statusText = "";
				$retina++;
			}
			else if ( $status == 'PENDING' ) {
				$result .= '<div class="meow-sized-image meow-bk-orange"></div>';
				$statusText = __( "The retina image can be created. Please use the 'GENERATE' button.", 'wp-retina-2x' );
				$possible++;
			}
			else if ( $status == 'MISSING' ) {
				$result .= '<div class="meow-sized-image meow-bk-gray"></div>';
				$statusText = __( "The standard image normally created by WordPress is missing.", 'wp-retina-2x' );
				$total--;
			}
			else if ( $status == 'IGNORED' ) {
				$result .= '<div class="meow-sized-image meow-bk-gray"></div>';
				$statusText = __( "This size is ignored by your retina settings.", 'wp-retina-2x' );
				$ignored++;
				$total--;
			}

			$result .= "&nbsp;Size: $i</h3><p>$statusText</p>";

			if ( !is_array( $status ) && $status !== 'IGNORED' && $status !== 'MISSING'  ) {
				$result .= "<table><tr><th>Normal (" . $width . "×" . $height. ")</th><th>Retina 2x (" . $width * 2 . "×" . $height * 2 . ")</th></tr><tr><td><a target='_blank' href='$normal_file'><img src='$normal_file' width='100'></a></td><td><a target='_blank' href='$retina_file'><img src='$retina_file' width='100'></a></td></tr></table>";
				$result .= "<p><small>";
				$result .= "Image URL: <a target='_blank' href='$normal_file'>$normal_file</a><br />";
				$result .= "Retina URL: <a target='_blank' href='$retina_file'>$retina_file</a><br />";
				$result .= "Image Path: $normal_file_system<br />";
				$result .= "Retina Path: $retina_file_system<br />";
				$result .= "</small></p>";
			}
		}
		$result .= "</table>";
		$result .= "</div>";
		return $result;
	}

	/**
	 *
	 * WP RETINA 2X CORE
	 *
	 */

	// Get WordPress upload directory
	function get_upload_root() {
		$uploads = wp_upload_dir();
		return $uploads['basedir'];
	}

	function get_upload_root_url() {
		$uploads = wp_upload_dir();
		return $uploads['baseurl'];
	}

	// Get WordPress directory
	function get_wordpress_root() {
		return ABSPATH;
	}

	// Resize the image
	function resize( $file_path, $width, $height, $crop, $newfile, $customCrop = false ) {
		$crop_params = $crop == '1' ? true : $crop;
		$orig_size = getimagesize( $file_path );
		$image_src = array ();
		$image_src[0] = $file_path;
		$image_src[1] = $orig_size[0];
		$image_src[2] = $orig_size[1];
		$file_info = pathinfo( $file_path );
		$newfile_info = pathinfo( $newfile );
		$extension = '.' . $newfile_info['extension'];
		$no_ext_path = $file_info['dirname'] . '/' . $file_info['filename'];
		$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . "-tmp" . $extension;
		$image = wp_get_image_editor( $file_path );

		if ( is_wp_error( $image ) ) {
			$this->log( "Resize failure: " . $image->get_error_message() );
			error_log( "Resize failure: " . $image->get_error_message() );
			return null;
		}

		// Resize or use Custom Crop
		if ( !$customCrop )
			$image->resize( $width, $height, $crop_params );
		else
			$image->crop( $customCrop['x'] * $customCrop['scale'], $customCrop['y'] * $customCrop['scale'], $customCrop['w'] * $customCrop['scale'], $customCrop['h'] * $customCrop['scale'], $width, $height, false );

		// Quality
		$quality = get_option( 'wr2x_quality', 90 );
		$image->set_quality( $quality );

		$saved = $image->save( $cropped_img_path );
		if ( is_wp_error( $saved ) ) {
			$error = $saved->get_error_message();
			trigger_error( "Retina: Could not create/resize image " . $file_path . " to " . $newfile . ": " . $error , E_WARNING );
			error_log( "Retina: Could not create/resize image " . $file_path . " to " . $newfile . ":" . $error );
			return null;
		}
		if ( rename( $saved['path'], $newfile ) )
			$cropped_img_path = $newfile;
		else {
			trigger_error( "Retina: Could not move " . $saved['path'] . " to " . $newfile . "." , E_WARNING );
			error_log( "Retina: Could not move " . $saved['path'] . " to " . $newfile . "." );
			return null;
		}
		$new_img_size = getimagesize( $cropped_img_path );
		$new_img = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
		$vt_image = array ( 'url' => $new_img, 'width' => $new_img_size[0], 'height' => $new_img_size[1] );
		return $vt_image;
	}

	// Return the retina file if there is any (system path)
	function get_retina( $file ) {
		$pathinfo = pathinfo( $file ) ;
		if ( empty( $pathinfo ) || !isset( $pathinfo['dirname'] ) ) {
			if ( empty( $file ) ) {
				$this->log( "An empty filename was given to $this->get_retina()." );
				error_log( "An empty filename was given to $this->get_retina()." );
			}
			else {
				$this->log( "Pathinfo is null for " . $file . "." );
				error_log( "Pathinfo is null for " . $file . "." );
			}
			return null;
		}
		$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] .
			$this->retina_extension() . ( isset( $pathinfo['extension'] ) ? $pathinfo['extension'] : "" );
		if ( file_exists( $retina_file ) )
			return $retina_file;
		$this->log( "Retina file at '{$retina_file}' does not exist." );
		return null;
	}

	function get_retina_from_remote_url( $url ) {
		$over_http = get_option( 'wr2x_over_http_check', false ) && $this->admin->is_registered();
		if ( !$over_http )
			return null;
		$potential_retina_url = $this->rewrite_url_to_retina( $url );
		$response = wp_remote_head( $potential_retina_url, array(
			'user-agent' => "MeowApps-Retina",
			'sslverify' => false,
			'timeout' => 10
		));
		if ( is_array( $response ) && is_array( $response['response'] ) && isset( $response['response']['code'] ) ) {
			if ( $response['response']['code'] == 200 ) {
				$this->log( "Retina URL: " . $potential_retina_url, true);
				return $potential_retina_url;
			}
			else
				$this->log( "Remote head failed with code " . $response['response']['code'] . "." );
		}
		$this->log( "Retina URL couldn't be found (URL -> Retina URL).", true);
	}

	// Return retina URL from the image URL
	function get_retina_from_url( $url ) {
		$this->log( "Standard URL: " . $url, true);
		$over_http = get_option( 'wr2x_over_http_check', false ) && $this->admin->is_registered();
		$filepath = $this->from_url_to_system( $url );
		if ( empty ( $filepath ) )
			return $this->get_retina_from_remote_url( $url );
		$this->log( "Standard PATH: " . $filepath, true);
		$system_retina = $this->get_retina( $filepath );
		if ( empty ( $system_retina ) )
			return $this->get_retina_from_remote_url( $url );
		$this->log( "Retina PATH: " . $system_retina, true);
		$retina_url = $this->rewrite_url_to_retina( $url );
		$this->log( "Retina URL: " . $retina_url, true);
		return $retina_url;
	}

	// Get the filepath from the URL
	function from_url_to_system( $url ) {
		$img_pathinfo = $this->get_pathinfo_from_image_src( $url );
		$filepath = trailingslashit( $this->get_wordpress_root() ) . $img_pathinfo;
		if ( file_exists( $filepath ) )
			return $filepath;
		$filepath = trailingslashit( $this->get_upload_root() ) . $img_pathinfo;
		if ( file_exists( $filepath ) )
			return $filepath;
		$this->log( "Standard PATH couldn't be found (URL -> System).", true);
		return null;
	}

	function rewrite_url_to_retina( $url ) {
		$whereisdot = strrpos( $url, '.' );
		$url = substr( $url, 0, $whereisdot ) . $this->retina_extension() . substr( $url, $whereisdot + 1 );
		return $url;
	}

	// Clean the PathInfo of the IMG SRC.
	// IMPORTANT: This function STRIPS THE UPLOAD FOLDER if it's found
	// REASON: The reason is that on some installs the uploads folder is linked to a different "unlogical" physical folder
	// http://wordpress.org/support/topic/cant-find-retina-file-with-custom-uploads-constant?replies=3#post-5078892
	function get_pathinfo_from_image_src( $image_src ) {
		$uploads_url = trailingslashit( $this->get_upload_root_url() );
		if ( strpos( $image_src, $uploads_url ) === 0 )
			return ltrim( substr( $image_src, strlen( $uploads_url ) ), '/');
		else if ( strpos( $image_src, wp_make_link_relative( $uploads_url ) ) === 0 )
			return ltrim( substr( $image_src, strlen( wp_make_link_relative( $uploads_url ) ) ), '/');
		$img_info = parse_url( $image_src );
		return ltrim( $img_info['path'], '/' );
	}

	// Rename this filename with CDN
	function cdn_this( $url ) {
		$cdn_domain = "";
		if ( $this->admin->is_registered() )
			$cdn_domain = get_option( "wr2x_cdn_domain" );
		if ( empty( $cdn_domain ) )
			return $url;

		$home_url = parse_url( home_url() );
		$uploads_url = trailingslashit( $this->get_upload_root_url() );
		$uploads_url_cdn = str_replace( $home_url['host'], $cdn_domain, $uploads_url );
		// Perform additional CDN check (Issue #1631 by Martin)
		if ( strpos( $url, $uploads_url_cdn ) === 0 ) {
			$this->log( "URL already has CDN: $url" );
			return $url;
		}
		$this->log( "URL before CDN: $url" );
		$site_url = preg_replace( '#^https?://#', '', rtrim( get_site_url(), '/' ) );
		$new_url = str_replace( $site_url, $cdn_domain, $url );
		$this->log( "URL with CDN: $new_url" );
		return $new_url;
	}

	// function admin_menu() {
	// 	add_options_page( 'Retina', 'Retina', 'manage_options', 'wr2x_settings', 'wr2x_settings_page' );
	// }

	function get_image_sizes() {
		$sizes = array();
		global $_wp_additional_image_sizes;
		foreach ( get_intermediate_image_sizes() as $s ) {
			$crop = false;
			if ( isset( $_wp_additional_image_sizes[$s] ) ) {
				$width = intval($_wp_additional_image_sizes[$s]['width']);
				$height = intval($_wp_additional_image_sizes[$s]['height']);
				$crop = $_wp_additional_image_sizes[$s]['crop'];
			} else {
				$width = get_option( $s . '_size_w' );
				$height = get_option( $s . '_size_h' );
				$crop = get_option( $s . '_crop' );
			}
			$sizes[$s] = array( 'width' => $width, 'height' => $height, 'crop' => $crop );
		}
		if ( get_option( 'wr2x_disable_medium_large' ) )
			unset( $sizes['medium_large'] );
		return $sizes;
	}

	function get_active_image_sizes() {
		$sizes = $this->get_image_sizes();
		$active_sizes = array();
		$ignore = get_option( "wr2x_ignore_sizes", array() );
		if ( empty( $ignore ) )
			$ignore = array();
		$ignore = array_keys( $ignore );
		foreach ( $sizes as $name => $attr ) {
			$validSize = !empty( $attr['width'] ) || !empty( $attr['height'] );
			if ( $validSize && !in_array( $name, $ignore ) ) {
				$active_sizes[$name] = $attr;
			}
		}
		return $active_sizes;
	}

	function is_wpml_installed() {
		return function_exists( 'icl_object_id' ) && !class_exists( 'Polylang' );
	}

	// SQL Query if WPML with an AND to check if the p.ID (p is attachment) is indeed an original
	// That is to limit the SQL that queries all the attachments
	function create_sql_if_wpml_original() {
		$whereIsOriginal = "";
		if ( $this->is_wpml_installed() ) {
			global $wpdb;
			global $sitepress;
			$tbl_wpml = $wpdb->prefix . "icl_translations";
			$language = $sitepress->get_default_language();
			$whereIsOriginal = " AND p.ID IN (SELECT element_id FROM $tbl_wpml WHERE element_type = 'post_attachment' AND language_code = '$language') ";
		}
		return $whereIsOriginal;
	}

	function is_debug() {
		static $debug = -1;
		if ( $debug == -1 ) {
			$debug = get_option( "wr2x_debug" );
		}
		return !!$debug;
	}

	function log( $data, $isExtra = false ) {
		if ( !$this->is_debug() )
			return;
		$fh = fopen( trailingslashit( dirname(__FILE__) ) . 'wp-retina-2x.log', 'a' );
		$date = date( "Y-m-d H:i:s" );
		fwrite( $fh, "$date: {$data}\n" );
		fclose( $fh );
	}

	// Based on http://wordpress.stackexchange.com/questions/6645/turn-a-url-into-an-attachment-post-id
	function get_attachment_id( $file ) {
		$query = array(
			'post_type' => 'attachment',
			'meta_query' => array(
				array(
					'key'		=> '_wp_attached_file',
					'value'		=> ltrim( $file, '/' )
				)
			)
		);
		$posts = get_posts( $query );
		foreach( $posts as $post )
			return $post->ID;
		return false;
	}

	// Return the retina extension followed by a dot
	function retina_extension() {
		return '@2x.';
	}

	function is_image_meta( $meta ) {
		if ( !isset( $meta ) )
			return false;
		if ( !isset( $meta['sizes'] ) )
			return false;
		if ( !isset( $meta['width'], $meta['height'] ) )
			return false;
		return true;
	}

	function retina_info( $id ) {
		$result = array();
		$meta = wp_get_attachment_metadata( $id );
		if ( !$this->is_image_meta( $meta ) )
			return $result;
		$original_width = $meta['width'];
		$original_height = $meta['height'];
		$sizes = $this->get_image_sizes();
		$required_files = true;
		$originalfile = get_attached_file( $id );
		$pathinfo = pathinfo( $originalfile );
		$basepath = $pathinfo['dirname'];
		$ignore = get_option( "wr2x_ignore_sizes", array() );
		if ( empty( $ignore ) )
			$ignore = array();
		$ignore = array_keys( $ignore );

		// Full-Size (if required in the settings)
		$fullsize_required = get_option( "wr2x_full_size" ) && $this->admin->is_registered();
		$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . $this->retina_extension() . $pathinfo['extension'];
		if ( $retina_file && file_exists( $retina_file ) )
			$result['full-size'] = 'EXISTS';
		else if ( $fullsize_required && $retina_file )
			$result['full-size'] = array( 'width' => $original_width * 2, 'height' => $original_height * 2 );
		//}

		if ( $sizes ) {
			foreach ( $sizes as $name => $attr ) {
				$validSize = !empty( $attr['width'] ) || !empty( $attr['height'] );
				if ( !$validSize || in_array( $name, $ignore ) ) {
					$result[$name] = 'IGNORED';
					continue;
				}
				// Check if the file related to this size is present
				$pathinfo = null;
				$retina_file = null;

				if ( isset( $meta['sizes'][$name]['width'] ) && isset( $meta['sizes'][$name]['height']) && isset($meta['sizes'][$name]) && isset($meta['sizes'][$name]['file']) && file_exists( trailingslashit( $basepath ) . $meta['sizes'][$name]['file'] ) ) {
					$normal_file = trailingslashit( $basepath ) . $meta['sizes'][$name]['file'];
					$pathinfo = pathinfo( $normal_file ) ;
					$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . $this->retina_extension() . $pathinfo['extension'];
				}
				// None of the file exist
				else {
					$result[$name] = 'MISSING';
					$required_files = false;
					continue;
				}

				// The retina file exists
				if ( $retina_file && file_exists( $retina_file ) ) {
					$result[$name] = 'EXISTS';
					continue;
				}
				// The size file exists
				else if ( $retina_file )
					$result[$name] = 'PENDING';

				// The retina file exists
				$required_width = $meta['sizes'][$name]['width'] * 2;
				$required_height = $meta['sizes'][$name]['height'] * 2;
				if ( !$this->are_dimensions_ok( $original_width, $original_height, $required_width, $required_height ) ) {
					$result[$name] = array( 'width' => $required_width, 'height' => $required_height );
				}
			}
		}
		return $result;
	}

	function delete_attachment( $attach_id, $deleteFullSize = true ) {
		$meta = wp_get_attachment_metadata( $attach_id );
		$this->delete_images( $meta, $deleteFullSize );
		$this->remove_issue( $attach_id );
	}

	function wp_generate_attachment_metadata( $meta ) {
		if ( get_option( "wr2x_auto_generate" ) == true )
			if ( $this->is_image_meta( $meta ) )
				$this->generate_images( $meta );
			return $meta;
	}

	/**
	 * @param mixed[] $meta
	 * int       width
	 * int       height
	 * string    file
	 * mixed[][] sizes
	 */
	function generate_images( $meta ) {
		global $_wp_additional_image_sizes;
		$sizes = $this->get_image_sizes();
		if ( !isset( $meta['file'] ) ) return;

		$uploads = wp_upload_dir();

		// Check if the full-size-retina version of the generation source exists.
		// If it exists, replace the file path and its dimensions
		if ( $retina = $this->get_retina( $uploads['basedir'] . '/' . $meta['file'] ) ) {
			$meta['file'] = substr( $retina, strlen( $uploads['basedir'] ) + 1 );
			$dim = getimagesize( $retina );
			$meta['width']  = $dim[0];
			$meta['height'] = $dim[1];
		}

		$originalfile = $meta['file'];
		$pathinfo = pathinfo( $originalfile );
		$original_basename = $pathinfo['basename'];
		$basepath = trailingslashit( $uploads['basedir'] ) . $pathinfo['dirname'];

		$ignore = get_option( "wr2x_ignore_sizes" );
		if ( empty( $ignore ) )
			$ignore = array();
		$ignore = array_keys( $ignore );
		$issue = false;
		$id = $this->get_attachment_id( $meta['file'] );

		/**
		 * @param $id ID of the attachment whose retina image is to be generated
		 */
		do_action( 'wr2x_before_generate_retina', $id );

		$this->log("* GENERATE RETINA FOR ATTACHMENT '{$meta['file']}'");
		$this->log( "Full-Size is {$original_basename}." );

		foreach ( $sizes as $name => $attr ) {
			$normal_file = "";
			if ( in_array( $name, $ignore ) ) {
				$this->log( "Retina for {$name} ignored (settings)." );
				continue;
			}
			// Is the file related to this size there?
			$pathinfo = null;
			$retina_file = null;

			if ( isset( $meta['sizes'][$name] ) && isset( $meta['sizes'][$name]['file'] ) ) {
				$normal_file = trailingslashit( $basepath ) . $meta['sizes'][$name]['file'];
				$pathinfo = pathinfo( $normal_file ) ;
				$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . $this->retina_extension() . $pathinfo['extension'];
			}

			if ( $retina_file && file_exists( $retina_file ) ) {
				$this->log( "Base for {$name} is '{$normal_file }'." );
				$this->log( "Retina for {$name} already exists: '$retina_file'." );
				continue;
			}
			if ( $retina_file ) {
				$originalfile = trailingslashit( $pathinfo['dirname'] ) . $original_basename;

				if ( !file_exists( $originalfile ) ) {
					$this->log( "[ERROR] Original file '{$originalfile}' cannot be found." );
					return $meta;
				}

				// Maybe that new image is exactly the size of the original image.
				// In that case, let's make a copy of it.
				if ( $meta['sizes'][$name]['width'] * 2 == $meta['width'] && $meta['sizes'][$name]['height'] * 2 == $meta['height'] ) {
					copy ( $originalfile, $retina_file );
					$this->log( "Retina for {$name} created: '{$retina_file}' (as a copy of the full-size)." );
				}
				// Otherwise let's resize (if the original size is big enough).
				else if ( $this->are_dimensions_ok( $meta['width'], $meta['height'], $meta['sizes'][$name]['width'] * 2, $meta['sizes'][$name]['height'] * 2 ) ) {
					// Change proposed by Nicscott01, slighlty modified by Jordy (+isset)
					// (https://wordpress.org/support/topic/issue-with-crop-position?replies=4#post-6200271)
					$crop = isset( $_wp_additional_image_sizes[$name] ) ? $_wp_additional_image_sizes[$name]['crop'] : true;
					$customCrop = apply_filters( 'wr2x_custom_crop', null, $id, $name );

					// // Support for Manual Image Crop
					// // If the size of the image was manually cropped, let's keep it.
					// if ( class_exists( 'ManualImageCrop' ) && isset( $meta['micSelectedArea'] ) && isset( $meta['micSelectedArea'][$name] ) && isset( $meta['micSelectedArea'][$name]['scale'] ) ) {
					// 	$customCrop = $meta['micSelectedArea'][$name];
					// }

					$image = $this->resize( $originalfile, $meta['sizes'][$name]['width'] * 2,
						$meta['sizes'][$name]['height'] * 2, $crop, $retina_file, $customCrop );
				}
				if ( !file_exists( $retina_file ) ) {
					$is_ok = apply_filters( "wr2x_last_chance_generate", false, $id, $retina_file,
						$meta['sizes'][$name]['width'] * 2, $meta['sizes'][$name]['height'] * 2 );
					if ( !$is_ok ) {
						$this->log( "[ERROR] Retina for {$name} could not be created. Full-Size is " . $meta['width'] . "x" . $meta['height'] . " but Retina requires a file of at least " . $meta['sizes'][$name]['width'] * 2 . "x" . $meta['sizes'][$name]['height'] * 2 . "." );
						$issue = true;
					}
				}
				else {
					do_action( 'wr2x_retina_file_added', $id, $retina_file, $name );
					$this->log( "Retina for {$name} created: '{$retina_file}'." );
				}
			} else {
				if ( empty( $normal_file ) )
					$this->log( "[ERROR] Base file for '{$name}' does not exist." );
				else
					$this->log( "[ERROR] Base file for '{$name}' cannot be found here: '{$normal_file}'." );
			}
		}

		// Checks attachment ID + issues
		if ( !$id )
			return $meta;
		if ( $issue )
			$this->add_issue( $id );
		else
			$this->remove_issue( $id );

		/**
		 * @param $id ID of the attachment whose retina image has been generated
		 */
		do_action( 'wr2x_generate_retina', $id );

		return $meta;
	}

	function delete_images( $meta, $deleteFullSize = false ) {
		if ( !$this->is_image_meta( $meta ) )
			return $meta;
		$sizes = $meta['sizes'];
		if ( !$sizes || !is_array( $sizes ) )
			return $meta;
		$this->log("* DELETE RETINA FOR ATTACHMENT '{$meta['file']}'");
		$originalfile = $meta['file'];
		$id = $this->get_attachment_id( $originalfile );
		$pathinfo = pathinfo( $originalfile );
		$uploads = wp_upload_dir();
		$basepath = trailingslashit( $uploads['basedir'] ) . $pathinfo['dirname'];
		foreach ( $sizes as $name => $attr ) {
			$pathinfo = pathinfo( $attr['file'] );
			$retina_file = $pathinfo['filename'] . $this->retina_extension() . $pathinfo['extension'];
			if ( file_exists( trailingslashit( $basepath ) . $retina_file ) ) {
				$fullpath = trailingslashit( $basepath ) . $retina_file;
				unlink( $fullpath );
				do_action( 'wr2x_retina_file_removed', $id, $retina_file );
				$this->log("Deleted '$fullpath'.");
			}
		}
		// Remove full-size if there is any
		if ( $deleteFullSize ) {
			$pathinfo = pathinfo( $originalfile );
			$retina_file = $pathinfo[ 'filename' ] . $this->retina_extension() . $pathinfo[ 'extension' ];
			if ( file_exists( trailingslashit( $basepath ) . $retina_file ) ) {
				$fullpath = trailingslashit( $basepath ) . $retina_file;
				unlink( $fullpath );
				do_action( 'wr2x_retina_file_removed', $id, $retina_file );
				$this->log( "Deleted '$fullpath'." );
			}
		}
		return $meta;
	}

	/**
	 *
	 * FILTERS
	 *
	 */

	function validate_src( $src ) {
		if ( preg_match( "/^data:/i", $src ) )
			return null;
		return $src;
	}

	/**
	 *
	 * LOAD SCRIPTS IF REQUIRED
	 *
	 */

	function wp_enqueue_scripts () {
		global $wr2x_version, $wr2x_retinajs, $wr2x_retina_image, $wr2x_picturefill, $wr2x_lazysizes;
		$method = get_option( "wr2x_method" );

		if ( is_admin() && !get_option( "wr2x_retina_admin" ) ) {
			wp_enqueue_script( 'wr2x-admin', plugins_url( '/js/admin.js', __FILE__ ), array(), $wr2x_version, false );

			$nonce = array (
				'wr2x_generate' => null,
				'wr2x_delete' => null,
				'wr2x_delete_full' => null,
				'wr2x_list_all' => null,
				'wr2x_replace' => null,
				'wr2x_upload' => null,
				'wr2x_retina_upload' => null,
				'wr2x_retina_details' => null
			);
			foreach ( array_keys( $nonce ) as $action )
				$nonce[$action] = wp_create_nonce( $action );

			wp_localize_script( 'wr2x-admin', 'wr2x_admin_server', array (
				'maxFileSize' => $this->get_max_filesize(),
				'nonce' => $nonce,
				'i18n' => array (
					'Refresh' => __( "<a href='?page=wp-retina-2x&view=issues&refresh=true'>Refresh</a> this page.", 'wp-retina-2x' ),
					'Wait' => __( "Wait...", 'wp-retina-2x' ),
					'Nothing_to_do' => __( "Nothing to do ;)", 'wp-retina-2x' ),
					'Generate' => __( "GENERATE", 'wp-retina-2x' )
				)
			) );
		}

		// Picturefill
		if ( $method == "Picturefill" ) {
			if ( $this->is_debug() )
				wp_enqueue_script( 'wr2x-debug', plugins_url( '/js/debug.js', __FILE__ ), array(), $wr2x_version, false );
			// Picturefill
			if ( !get_option( "wr2x_picturefill_noscript" ) )
				wp_enqueue_script( 'picturefill', plugins_url( '/js/picturefill.min.js', __FILE__ ), array(), $wr2x_picturefill, false );
			// Lazysizes
			if ( get_option( "wr2x_picturefill_lazysizes" ) && $this->admin->is_registered() )
				wp_enqueue_script( 'lazysizes', plugins_url( '/js/lazysizes.min.js', __FILE__ ), array(), $wr2x_lazysizes, false );
			return;
		}

		// Debug + HTML Rewrite = No JS!
		if ( $this->is_debug() && $method == "HTML Rewrite" ) {
			return;
		}

		// Debug mode, we force the devicePixelRatio to be Retina
		if ( $this->is_debug() )
			wp_enqueue_script( 'wr2x-debug', plugins_url( '/js/debug.js', __FILE__ ), array(), $wr2x_version, false );

		// Retina-Images and HTML Rewrite both need the devicePixelRatio cookie on the server-side
		if ( $method == "Retina-Images" || $method == "HTML Rewrite" )
			wp_enqueue_script( 'retina-images', plugins_url( '/js/retina-cookie.js', __FILE__ ), array(), $wr2x_retina_image, false );

		// Retina.js only needs itself
		if ($method == "retina.js")
			wp_enqueue_script( 'retinajs', plugins_url( '/js/retina.min.js', __FILE__ ), array(), $wr2x_retinajs, true );
	}

}

// Used by WP Rocket (and maybe by other plugins)
function wr2x_is_registered() {
	global $wr2x_core;
	return $wr2x_core->admin->is_registered();
}

?>
