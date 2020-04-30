<?php

class Meow_WR2X_MediaLibrary {

	public $core = null;

	public function __construct( $core ) {
		$this->core = $core;
		add_filter( 'manage_media_columns', array( $this, 'manage_media_columns' ) );
		add_action( 'manage_media_custom_column', array( $this, 'manage_media_custom_column' ), 10, 2 );
		add_action( 'admin_footer', array( $this, 'admin_footer_library' ) );
	}

	function manage_media_columns( $cols ) {
		$cols["Retina"] = "Retina";
		return $cols;
	}

	function manage_media_custom_column( $column_name, $id ) {
		if ( $column_name == 'Retina' ) {
			$info = $this->core->retina_info( $id );
			if ( empty( $info ) )
				return;
	    $info = $this->core->html_get_basic_retina_info( $id, $info );
	    echo "<a style='' onclick='wr2x_generate(" . $id . ", true)' id='wr2x_generate_button_" .
				$id . "' class='wr2x-button'>" . __( "GENERATE", 'wp-retina-2x' ) . "</a><br />";
			echo '<div class="wr2x-info" postid="' . $id . '" id="wr2x-info-' . $id . '">';
			echo $info;
	    echo '</div>';
	  }
	  else if ( $column_name == 'Retina-Actions' ) {
	  }
	}

	function admin_footer_library() {
		$screen = get_current_screen();
		if ( $screen->base != 'upload' && $screen->base != 'media_page_wp-retina-2x' )
			return;
		?>
		<div id="meow-modal-info-backdrop" style="display: none;">
		</div>

		<div id="meow-modal-info" style="display: none;" tabindex="1">
			<div class="close">X</div>
			<h2 style="margin-top: 0px;">Retina Details</h2>
			<div class="loading">
				<img src="<?php echo plugin_dir_url( __FILE__ ); ?>loading.gif" />
			</div>
			<div class="content">
			</div>
		</div>
		<?php
	}

}


?>
