<?php

/**
 * Class TA_WC_Variation_Swatches_Admin
 */
class TA_WC_Variation_Swatches_Admin {
	/**
	 * The single instance of the class
	 *
	 * @var TA_WC_Variation_Swatches_Admin
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return TA_WC_Variation_Swatches_Admin
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'includes' ) );
		add_action( 'admin_init', array( $this, 'init_attribute_hooks' ) );
		add_action( 'admin_print_scripts', array( $this, 'enqueue_scripts' ) );

		// Restore attributes
		add_action( 'admin_notices', array( $this, 'restore_attributes_notice' ) );
		add_action( 'admin_init', array( $this, 'restore_attribute_types' ) );

		// Display attribute fields
		add_action( 'tawcvs_product_attribute_field', array( $this, 'attribute_fields' ), 10, 3 );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once( dirname( __FILE__ ) . '/class-admin-product.php' );
	}

	/**
	 * Init hooks for adding fields to attribute screen
	 * Save new term meta
	 * Add thumbnail column for attribute term
	 */
	public function init_attribute_hooks() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( empty( $attribute_taxonomies ) ) {
			return;
		}

		foreach ( $attribute_taxonomies as $tax ) {
			add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
			add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );

			add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array( $this, 'add_attribute_columns' ) );
			add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
		}

		add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 2 );
		add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 2 );
	}

	/**
	 * Load stylesheet and scripts in edit product attribute screen
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( strpos( $screen->id, 'edit-pa_' ) === false && strpos( $screen->id, 'product' ) === false ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'tawcvs-admin', plugins_url( '/assets/css/admin.css', dirname( __FILE__ ) ), array( 'wp-color-picker' ), '20160615' );
		wp_enqueue_script( 'tawcvs-admin', plugins_url( '/assets/js/admin.js', dirname( __FILE__ ) ), array( 'jquery', 'wp-color-picker', 'wp-util' ), '20170113', true );

		wp_localize_script(
			'tawcvs-admin',
			'tawcvs',
			array(
				'i18n'        => array(
					'mediaTitle'  => esc_html__( 'Choose an image', 'wcvs' ),
					'mediaButton' => esc_html__( 'Use image', 'wcvs' ),
				),
				'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png'
			)
		);
	}

	/**
	 * Display a notice of restoring attribute types
	 */
	public function restore_attributes_notice() {
		if ( get_transient( 'tawcvs_attribute_taxonomies' ) && ! get_option( 'tawcvs_restore_attributes_time' ) ) {
			?>
			<div class="notice-warning notice is-dismissible">
				<p>
					<?php
					esc_html_e( 'Found a backup of product attributes types. This backup was generated at', 'wcvs' );
					echo ' ' . date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), get_option( 'tawcvs_backup_attributes_time' ) ) . '.';
					?>
				</p>
				<p>
					<a href="<?php echo esc_url( add_query_arg( array( 'tawcvs_action' => 'restore_attributes_types', 'tawcvs_nonce' => wp_create_nonce( 'restore_attributes_types' ) ) ) ); ?>">
						<strong><?php esc_html_e( 'Restore product attributes types', 'wcvs' ); ?></strong>
					</a>
					|
					<a href="<?php echo esc_url( add_query_arg( array( 'tawcvs_action' => 'dismiss_restore_notice', 'tawcvs_nonce' => wp_create_nonce( 'dismiss_restore_notice' ) ) ) ); ?>">
						<strong><?php esc_html_e( 'Dismiss this notice', 'wcvs' ); ?></strong>
					</a>
				</p>
			</div>
			<?php
		} elseif ( isset( $_GET['tawcvs_message'] ) && 'restored' == $_GET['tawcvs_message'] ) {
			?>
			<div class="notice-warning settings-error notice is-dismissible">
				<p><?php esc_html_e( 'All attributes types have been restored.', 'wcvs' ) ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Restore attribute types
	 */
	public function restore_attribute_types() {
		if ( ! isset( $_GET['tawcvs_action'] ) || ! isset( $_GET['tawcvs_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['tawcvs_nonce'], $_GET['tawcvs_action'] ) ) {
			return;
		}

		if ( 'restore_attributes_types' == $_GET['tawcvs_action'] ) {
			global $wpdb;

			$attribute_taxnomies = get_transient( 'tawcvs_attribute_taxonomies' );

			foreach ( $attribute_taxnomies as $id => $attribute ) {
				$wpdb->update(
					$wpdb->prefix . 'woocommerce_attribute_taxonomies',
					array( 'attribute_type' => $attribute->attribute_type ),
					array( 'attribute_id' => $id ),
					array( '%s' ),
					array( '%d' )
				);
			}

			update_option( 'tawcvs_restore_attributes_time', time() );
			delete_transient( 'tawcvs_attribute_taxonomies' );
			delete_transient( 'wc_attribute_taxonomies' );

			$url = remove_query_arg( array( 'tawcvs_action', 'tawcvs_nonce' ) );
			$url = add_query_arg( array( 'tawcvs_message' => 'restored' ), $url );
		} elseif ( 'dismiss_restore_notice' == $_GET['tawcvs_action'] ) {
			update_option( 'tawcvs_restore_attributes_time', 'ignored' );
			$url = remove_query_arg( array( 'tawcvs_action', 'tawcvs_nonce' ) );
		}

		if ( isset( $url ) ) {
			wp_redirect( $url );
			exit;
		}
	}

	/**
	 * Create hook to add fields to add attribute term screen
	 *
	 * @param string $taxonomy
	 */
	public function add_attribute_fields( $taxonomy ) {
		$attr = TA_WCVS()->get_tax_attribute( $taxonomy );

		do_action( 'tawcvs_product_attribute_field', $attr->attribute_type, '', 'add' );
	}

	/**
	 * Create hook to fields to edit attribute term screen
	 *
	 * @param object $term
	 * @param string $taxonomy
	 */
	public function edit_attribute_fields( $term, $taxonomy ) {
		$attr  = TA_WCVS()->get_tax_attribute( $taxonomy );
		$value = get_term_meta( $term->term_id, $attr->attribute_type, true );

		do_action( 'tawcvs_product_attribute_field', $attr->attribute_type, $value, 'edit' );
	}

	/**
	 * Print HTML of custom fields on attribute term screens
	 *
	 * @param $type
	 * @param $value
	 * @param $form
	 */
	public function attribute_fields( $type, $value, $form ) {
		// Return if this is a default attribute type
		if ( in_array( $type, array( 'select', 'text' ) ) ) {
			return;
		}

		// Print the open tag of field container
		printf(
			'<%s class="form-field">%s<label for="term-%s">%s</label>%s',
			'edit' == $form ? 'tr' : 'div',
			'edit' == $form ? '<th>' : '',
			esc_attr( $type ),
			TA_WCVS()->types[$type],
			'edit' == $form ? '</th><td>' : ''
		);

		switch ( $type ) {
			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				?>
				<div class="tawcvs-term-image-thumbnail" style="float:left;margin-right:10px;">
					<img src="<?php echo esc_url( $image ) ?>" width="60px" height="60px" />
				</div>
				<div style="line-height:60px;">
					<input type="hidden" class="tawcvs-term-image" name="image" value="<?php echo esc_attr( $value ) ?>" />
					<button type="button" class="tawcvs-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'wcvs' ); ?></button>
					<button type="button" class="tawcvs-remove-image-button button <?php echo $value ? '' : 'hidden' ?>"><?php esc_html_e( 'Remove image', 'wcvs' ); ?></button>
				</div>
				<?php
				break;

			default:
				?>
				<input type="text" id="term-<?php echo esc_attr( $type ) ?>" name="<?php echo esc_attr( $type ) ?>" value="<?php echo esc_attr( $value ) ?>" />
				<?php
				break;
		}

		// Print the close tag of field container
		echo 'edit' == $form ? '</td></tr>' : '</div>';
	}

	/**
	 * Save term meta
	 *
	 * @param int $term_id
	 * @param int $tt_id
	 */
	public function save_term_meta( $term_id, $tt_id ) {
		foreach ( TA_WCVS()->types as $type => $label ) {
			if ( isset( $_POST[$type] ) ) {
				update_term_meta( $term_id, $type, sanitize_text_field( $_POST[$type] ) );
			}
		}
	}

	/**
	 * Add thumbnail column to column list
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function add_attribute_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = '';
		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Render thumbnail HTML depend on attribute type
	 *
	 * @param $columns
	 * @param $column
	 * @param $term_id
	 */
	public function add_attribute_column_content( $columns, $column, $term_id ) {
		$attr  = TA_WCVS()->get_tax_attribute( $_REQUEST['taxonomy'] );
		$value = get_term_meta( $term_id, $attr->attribute_type, true );

		switch ( $attr->attribute_type ) {
			case 'color':
				printf( '<div class="swatch-preview swatch-color" style="background-color:%s;"></div>', esc_attr( $value ) );
				break;

			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				printf( '<img class="swatch-preview swatch-image" src="%s" width="44px" height="44px">', esc_url( $image ) );
				break;

			case 'label':
				printf( '<div class="swatch-preview swatch-label">%s</div>', esc_html( $value ) );
				break;
		}
	}
}
