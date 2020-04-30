<?php
/**
 * Brand sidebar metabox.
 *
 * Add metabox for sidebar settings in page, post and custom post.
 *
 * @package Brand
 * @since 1.0.0
 */

	class Brand_Sidebar_Metabox
	{
		protected static $instance = null;

		protected $errors = array();

		public function __construct() {
        	add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        	add_action( 'save_post', array( $this, 'save' ) );
    	}

		public static function get_instance() {
            if (null == self::$instance) {
                self::$instance = new self;
            }

            return self::$instance;
        }

		public function add_meta_box( $post_type ) {
        	$post_types = array( 'post', 'page', 'product', 'download' );
        	if ( in_array( $post_type, $post_types )) {
            	add_meta_box(
                	'brand_sidebar_box',            // Unique ID
                	__('Sidebar', 'brand'),      // Box title
                	array( $this, 'render_form'), // Content callback
                	$post_type,
					'normal'
            	);
        	}
    	}

		public function save( $post_id ) {
			if ( ! $this->is_nonce_ok( 'brand_sidebar_box' ) ) {
        		return $post_id;
    		}

    		// Ignore auto saves
    		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
       			return $post_id;
    		}

    		// Check the user's permissions
    		if ( !current_user_can( 'edit_posts', $post_id ) ) {
        		return $post_id;
    		}
			$show_sidebar = in_array( $_POST['brand_show_sidebar'], array( 'no', 'left', 'right', 'default' ), true ) === true ? $_POST['brand_show_sidebar'] : 'default';
			update_post_meta( $post_id, '_brand_show_sidebar', $show_sidebar );
		}

		 public function render_form( $post )
		 {
		    $value = get_post_meta( $post->ID, '_brand_show_sidebar', true );
				$show_sidebar = isset($value) ? $value : 'no';
    		?>
				<div class="maxsdesign-form-section">
					<div class="maxsdesign-field-description">
						<h4> <?php esc_html_e( 'Show sidebar', 'brand' ) ?> </h4>
						</div>
						<div class="maxsdesign-field-content">
                <select name="brand_show_sidebar" id="brand_show_sidebar">
									<option value="default" <?php if ( 'default' === $show_sidebar ) echo 'selected'; ?>> <?php esc_html_e( 'Use global settings', 'brand' ) ?> </option>
									<option value="no" <?php if ( 'no' === $show_sidebar ) echo 'selected'; ?>><?php esc_html_e( 'No', 'brand' ) ?></option>
            			<option value="left" <?php if ( 'left' === $show_sidebar ) echo 'selected'; ?>> <?php esc_html_e( 'Left', 'brand' ) ?> </option>
            			<option value="right" <?php if ( 'right' === $show_sidebar ) echo 'selected'; ?>> <?php esc_html_e( 'Right', 'brand' ) ?> </option>
        				</select>
                <?php $this->render_nonce_field('brand_sidebar_box'); ?>
						</div>
				</div>
			<?php
    	}

		/**
 		* A helper function for creating and rendering a nonce field.
 		*
 		* @param   $nonce_label  string  An internal (shorter) nonce name
 		*/
		private function render_nonce_field( $nonce_label ) {
    		$nonce_field_name = 'brand_' . $nonce_label . '_nonce';
    		$nonce_name = 'brand_' . $nonce_label;

   			 wp_nonce_field( $nonce_name, $nonce_field_name );
		}

		/**
 		* A helper function for checking the product meta box nonce.
 		*
 		* @param   $nonce_label string  An internal (shorter) nonce name
 		* @return  mixed   False if nonce is not OK. 1 or 2 if nonce is OK (@see wp_verify_nonce)
 		*/
		private function is_nonce_ok( $nonce_label ) {
    		$nonce_field_name = 'brand_' . $nonce_label . '_nonce';
    		$nonce_name = 'brand_' . $nonce_label;

    		if ( ! isset( $_POST[ $nonce_field_name ] ) ) {
        		return false;
    		}

    		$nonce = $_POST[ $nonce_field_name ];

    		return wp_verify_nonce( $nonce, $nonce_name );
		}

	}
