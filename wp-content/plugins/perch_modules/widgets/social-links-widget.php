<?php
/**
 * Perch class used to implement a Footer Social icons widget. 
 */
class Perch_Social_links extends WP_Widget {	

	public function __construct() {
		$widget_ops = array(
			 'classname' => 'social-widget',
			'description' => __('Display company social links', 'perch' )
		);

		parent::__construct( 
			'perch-social-icons', 
			perch_modules_current_theme().' '.__( 'Social Links', 'perch' ), 
			$widget_ops 
		);		
	}


	public function widget( $args, $instance ) {
		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		} //!isset( $args[ 'widget_id' ] )		

		$title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
		$display_inline = isset( $instance[ 'display_inline' ] ) ? $instance[ 'display_inline' ] : false;
		$align_right = isset( $instance[ 'align_right' ] ) ? $instance[ 'align_right' ] : false;
		

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );		

		echo perch_context_args($args[ 'before_widget' ]);
		echo ( !empty( $title ) ) ? $args[ 'before_title' ] . esc_attr($title) . $args[ 'after_title' ] : '';
		

		$social_icons = apply_filters('perch_modules/social_icons', array());

		$_args = array(
			'wrapclass' => 'footer-socials-links mb-25',
			'linkwrap' => 'h5',
			'linkwrapclass' => 'h5-sm',
			'wrap' => 'div',
			'linktext' => true,
			'icon' => false,
			'iconprefix' => 'foo'
		);

		if($align_right) $_args[ 'wrapclass' ] .= ' text-right';
		if($display_inline){
			$_args[ 'wrap' ] = 'ul';
			$_args[ 'linkwrap' ] = 'li';
			$_args[ 'linkwrapclass' ] = 'list-inline-item strong';
			$_args[ 'wrapclass' ] .= ' list-inline';
		}

		echo '<div class="widget-content flow-me-widget">';
		echo perch_get_social_icons($social_icons, $_args);	
		echo '</div>';

		echo perch_context_args($args[ 'after_widget' ]);	
	}	

	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'title' ]     = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'display_inline' ] = isset( $new_instance[ 'display_inline' ] ) ? (bool) $new_instance[ 'display_inline' ] : false;
		$instance[ 'align_right' ] = isset( $new_instance[ 'align_right' ] ) ? (bool) $new_instance[ 'align_right' ] : true;


		return $instance;
	}



	public function form( $instance ) {
		$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : '';	
		$display_inline = isset( $instance[ 'display_inline' ] ) ? (bool) $instance[ 'display_inline' ] : false;
		$align_right = isset( $instance[ 'align_right' ] ) ? (bool) $instance[ 'align_right' ] : true;
		?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
        	<?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><?php _e('Social icons can be edit from Theme options > General options', 'perch')?></p>

        <p><input class="checkbox" type="checkbox"<?php	checked( $align_right ); ?> id="<?php	echo esc_attr($this->get_field_id( 'align_right' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'align_right' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'align_right' )); ?>"><?php _e( 'Align right?', 'perch' ); ?></label></p>

         <p><input class="checkbox" type="checkbox"<?php	checked( $display_inline ); ?> id="<?php	echo esc_attr($this->get_field_id( 'display_inline' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'display_inline' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'display_inline' )); ?>"><?php _e( 'Display inline?', 'perch' ); ?></label></p>

        
		<?php
	}
}