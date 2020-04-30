<?php
/**
 * Perch class used to implement a Footer Social icons widget. 
 */
class Perch_Get_In_Touch extends WP_Widget {	

	public function __construct() {
		$widget_ops = array(
			 'classname' => apply_filters('perch_modules/widgets/get_in_touch/classname', 'get-in-touch'),
			'description' => __('Display company contact info', 'perch' )
		);
		parent::__construct( 
			'perch_get_in_touch', 
			perch_modules_current_theme().' '.__( 'Get in Touch', 'perch' ), 
			$widget_ops 
		);
	}


	public function widget( $args, $instance ) {
		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		} //!isset( $args[ 'widget_id' ] )		

		$title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
		$address = ( !empty( $instance[ 'address' ] ) ) ? $instance[ 'address' ] : '';
		$phone = ( !empty( $instance[ 'phone' ] ) ) ? $instance[ 'phone' ] : '';
		$email = ( !empty( $instance[ 'email' ] ) ) ? $instance[ 'email' ] : '';
		

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		

		echo perch_context_args($args[ 'before_widget' ]);
		echo ( !empty( $title ) ) ? $args[ 'before_title' ] . esc_attr($title) . $args[ 'after_title' ] : '';


		if ( $address != '' ) echo '<p class="caps_large">'.nl2br($address).'</p>';
		echo '<ul>';

		if( $phone != '' ){
			$phoneArr = explode(',', $phone);
			foreach ($phoneArr as $key => $value) {
				echo '<li class="phone"><i class="primary-color perch-Phone2"></i>'.$value.'</li>';
			}
		}

		if( $email != '' ){
			$emailArr = explode(',', $email);
			foreach ($emailArr as $key => $value) {
				echo '<li class="email"><i class="primary-color pe-7s-mail-open-file"></i>'.$value.'</li>';
			}
		}		

		echo '</ul>';

		echo perch_context_args($args[ 'after_widget' ]);
	}	

	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'title' ]     = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'address' ]     = sanitize_text_field( $new_instance[ 'address' ] );
		$instance[ 'phone' ]     = sanitize_text_field( $new_instance[ 'phone' ] );
		$instance[ 'email' ]     = sanitize_text_field( $new_instance[ 'email' ] );		

		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : 'Get in Touch';
		$address     = isset( $instance[ 'address' ] ) ? esc_attr( $instance[ 'address' ] ) : '70 W. Madison Street, Ste. 1400 Chicago, IL 60602';
		$phone     = isset( $instance[ 'phone' ] ) ? esc_attr( $instance[ 'phone' ] ) : '(214) 550-0405, (214) 550-5405';
		$email     = isset( $instance[ 'email' ] ) ? esc_attr( $instance[ 'email' ] ) : 'support@perchtheme.net';
		?>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
        	<?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>


        <p><label for="<?php echo esc_attr($this->get_field_id( 'address' )); ?>">
        	<?php _e( 'Address:', 'perch' ); ?></label>
        	<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( 'address' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'address' )); ?>"><?php echo esc_attr($address); ?></textarea>
       </p>


        <p><label for="<?php echo esc_attr($this->get_field_id( 'phone' )); ?>">
        	<?php _e( 'Phone:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'address' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'phone' )); ?>" type="text" value="<?php echo esc_attr($phone); ?>" /><br><small><?php _e('Multiple phone number is separated bt comma(,)', 'perch') ?></small></p>


        <p><label for="<?php echo esc_attr($this->get_field_id( 'email' )); ?>">
        	<?php _e( 'Email:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'email' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'email' )); ?>" type="text" value="<?php echo esc_attr($email); ?>" /><br><small><?php _e('Multiple email is separated bt comma(,)', 'perch') ?></small></p>
		<?php
	}
}