<?php
/**
 * Brand header metabox.
 *
 * Add metabox for disabled or enabled navigation, header, title headline, footer.
 *
 * @package Brand
 * @since 1.0.0
 */

	class Brand_Hide_Elements_Metabox
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
        	$post_types = array( 'post', 'page', 'brand_portfolio', 'product', 'download' );
        	if ( in_array( $post_type, $post_types )) {
            	add_meta_box(
                	'brand_hide_elements_box',            // Unique ID
                	__('Hide elements', 'brand'),      // Box header
                	array( $this, 'render_form'), // Content callback
                	$post_type,
									'side'
            	);
        	}
    	}


		public function save( $post_id ) {
			if ( ! $this->is_nonce_ok( 'brand_hide_elements' ) ) {
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

		    $meta = get_post_meta( $post->ID, '_brand_hide_elements', true );
				if ( $meta == '' ) {
        		$meta = array();
    		}
            $meta['hide_navigation']     = isset( $_POST['brand_hide_navigation'] ) ? 1 : 0;
						$meta['hide_header']         = isset( $_POST['brand_hide_header'] ) ? 1 : 0;
						$meta['hide_headline']       = isset( $_POST['brand_hide_headline'] ) ? 1 : 0;
						$meta['hide_footer_widgets'] = isset( $_POST['brand_hide_footer_widgets'] ) ? 1 : 0;
						$meta['hide_footer']         = isset( $_POST['brand_hide_footer'] ) ? 1 : 0;

			update_post_meta( $post_id, '_brand_hide_elements', $meta );
		}

		 public function render_form( $post ) {
		     $brand_hide_elements_meta = get_post_meta( $post->ID, '_brand_hide_elements', true );
         $brand_hide_elements_meta = array(
             'hide_navigation' => isset($brand_hide_elements_meta['hide_navigation']) ? $brand_hide_elements_meta['hide_navigation'] : 0,
						 'hide_header'         => isset($brand_hide_elements_meta['hide_header']) ? $brand_hide_elements_meta['hide_header'] : 0,
						 'hide_headline'       => isset($brand_hide_elements_meta['hide_headline']) ? $brand_hide_elements_meta['hide_headline'] : 0,
						 'hide_footer_widgets' => isset($brand_hide_elements_meta['hide_footer_widgets']) ? $brand_hide_elements_meta['hide_footer_widgets'] : 0,
						 'hide_footer'         => isset($brand_hide_elements_meta['hide_footer']) ? $brand_hide_elements_meta['hide_footer'] : 0,
         ); ?>
						<ul>
							<li>
								<label>
									<input type="checkbox" value="1" name="brand_hide_navigation" id="brand-hide-navigation" <?php if($brand_hide_elements_meta['hide_navigation'] == 1)  echo ' checked' ?> />
									<?php esc_html_e( 'Hide navigation', 'brand' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" value="1" name="brand_hide_header" id="brand-hide-header" <?php if($brand_hide_elements_meta['hide_header'] == 1)  echo ' checked' ?> />
									<?php esc_html_e( 'Hide header', 'brand' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" value="1" name="brand_hide_headline" id="brand-hide-headline" <?php if($brand_hide_elements_meta['hide_headline'] == 1)  echo ' checked' ?> />
									<?php esc_html_e( 'Hide headline', 'brand' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" value="1" name="brand_hide_footer_widgets" id="brand-hide-footer-widgets" <?php if($brand_hide_elements_meta['hide_footer_widgets'] == 1)  echo ' checked' ?> />
									<?php esc_html_e( 'Hide footer widgets', 'brand' ); ?>
								</label>
							</li>
							<li>
								<label>
									<input type="checkbox" value="1" name="brand_hide_footer" id="brand-hide-footer" <?php if($brand_hide_elements_meta['hide_footer'] == 1)  echo ' checked' ?> />
									<?php esc_html_e( 'Hide footer', 'brand' ); ?>
								</label>
							</li>
						</ul>
			<?php
			$this->render_nonce_field('brand_hide_elements');
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
 		* A helper function for checking the meta box nonce.
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
