<?php
/**
 * Perch class used to implement a Footer Instafeed Carouel widget. 
 */
class Perch_Footer_Subscription_Form extends WP_Widget {	

	public function __construct() {
		$widget_ops = array(
			 'classname' => 'newsletter-widget footer-form mb-20',
			'description' => '' 
		);

		parent::__construct( 
			'perch-subscription-form', 
			perch_modules_current_theme().' '.__( 'Email Subscribers', 'perch' ), 
			$widget_ops 
		);			
	}


	public function widget( $args, $instance ) {
		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		} //!isset( $args[ 'widget_id' ] )
		

		$title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
		$desc     = isset( $instance[ 'desc' ] ) ? esc_attr( $instance[ 'desc' ] ) : '';
		$button_text     = isset( $instance[ 'button_text' ] ) ? esc_attr( $instance[ 'button_text' ] ) : 'far fa-envelope';
		$placeholder = ( !empty( $instance['placeholder'] ) ) ? $instance[ 'placeholder' ] : __('Email Address', 'perch');
		$group = ( !empty( $instance[ 'group' ] ) ) ? $instance[ 'group' ] : '';		

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		echo perch_context_args($args[ 'before_widget' ]);
		echo ( !empty( $title ) ) ? $args[ 'before_title' ] . esc_attr($title) . $args[ 'after_title' ] : '';

		if( $desc != '' ){
			echo '<p class="mb-20">'.nl2br($desc).'</p>';
		}
		
		?>

			
		<?php 				
			if(class_exists('PerchNewsletter')){
				$_args = array();
				$_args['email'] = esc_attr($placeholder);
				$_args['button_text'] = esc_attr($button_text);
				$_args['button_style'] = '';
				$_args['es_group'] = esc_attr($group);
				$_args['enable_name'] = 'no';
				$_args['es_desc'] = '';
				$_args['es_pre'] = '';
				$_args['name'] = '';
				echo PerchNewsletter::es_get_widget_form( $_args );
			}else{
				echo 'Please Install Theme Required & Recommended PLugins.';
			}
		?>
					
		<?php
		echo perch_context_args($args[ 'after_widget' ]);
	}	

	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'title' ]     = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'desc' ]    =  $new_instance[ 'desc' ];
		$instance[ 'button_text' ]    =  $new_instance[ 'button_text' ];
		$instance[ 'placeholder' ]     = $new_instance[ 'placeholder' ];
		$instance[ 'group' ]    =  $new_instance[ 'group' ];		
		return $instance;
	}



	public function form( $instance ) {
		$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : 'Subscribe Us:';
		$button_text     = isset( $instance[ 'button_text' ] ) ? esc_attr( $instance[ 'button_text' ] ) : 'far fa-envelope';
		$desc     = isset( $instance[ 'desc' ] ) ? $instance[ 'desc' ] : 'We don\'t share your personal information with anyone. Check out our Privacy Policy for more information ';
		$placeholder = ( !empty( $instance[ 'placeholder' ] ) ) ? $instance[ 'placeholder' ] : 'Email Address';
		$group = ( !empty( $instance[ 'group' ] ) ) ? $instance[ 'group' ] : '';
		?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
        	<?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'desc' )); ?>">
        	<?php _e( 'Description:', 'perch' ); ?></label>
        <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( 'desc' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'desc' )); ?>"><?php echo sanitize_text_field($desc); ?></textarea></p>


        <p><label for="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>">
        	<?php _e( 'Email Placeholder:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'placeholder' )); ?>" type="text" value="<?php echo esc_attr($placeholder); ?>" /></p>


     <p><label for="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>">
        	<?php _e( 'Button icon:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'button_text' )); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" /></p>


        <p><label for="<?php echo esc_attr($this->get_field_id( 'group' )); ?>">
        	<?php _e( 'Group(optional):', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'group' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'group' )); ?>" type="text" value="<?php echo esc_attr($group); ?>" /></p>        

		<?php
	}
}