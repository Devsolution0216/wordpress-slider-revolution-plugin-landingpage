<?php

class Perch_How_can_We_Help_Widget extends WP_Widget {

	

	public function __construct() {

		$widget_ops = array(
			'classname' => apply_filters('perch_modules/widgets/how_can_we_help/classname', 'how-can-we-help'),
			'description' => 'Display button link with Title and description'
		);

		parent::__construct( 
			'perch_how_can_we_help', 
			perch_modules_current_theme().' '.__( 'How Can We Help', 'perch' ), 
			$widget_ops 
		);
	}



	public function widget( $args, $instance ) {

		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		} //!isset( $args[ 'widget_id' ] )
		

		$title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : 'How Can We Help?';
		$desc     = isset( $instance[ 'desc' ] ) ? esc_attr( $instance[ 'desc' ] ) : '';
		

		$button_text = ( !empty( $instance[ 'button_text' ] ) ) ? $instance[ 'button_text' ] : 'Get in touch';
		$link = ( !empty( $instance[ 'link' ] ) ) ? $instance[ 'link' ] : '#';	
		?>

		<div class="help clearfix newsletter-widget mb-40">
			<h4 class="widget-title"><?php echo esc_attr($title); ?></h4>
			<div class="widget-subscribe-form">
			<p class="caps_large"><?php echo esc_attr(nl2br($desc)); ?></p>
			<a href="<?php echo esc_url($link); ?>" class="btn btn-md"><?php echo esc_attr($button_text); ?></a>
			</div>
		</div>
		<?php
	}	

	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'title' ]     = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'desc' ]    =  $new_instance[ 'desc' ];
		$instance[ 'button_text' ]    =  $new_instance[ 'button_text' ];
		$instance[ 'link' ]    =  $new_instance[ 'link' ];
		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : 'How Can We Help?';
		$desc     = isset( $instance[ 'desc' ] ) ? $instance[ 'desc' ] : 'At vero eos et accusam et justou dolores et ea rebum tet clita kasd gubergren no sea takimata.';
		$button_text = ( !empty( $instance[ 'button_text' ] ) ) ? $instance[ 'button_text' ] : 'Get in touch';
		$link = ( !empty( $instance[ 'link' ] ) ) ? $instance[ 'link' ] : '#';
		?>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
        	<?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'desc' )); ?>">
        	<?php _e( 'Description:', 'perch' ); ?></label>
        <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( 'desc' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'desc' )); ?>"><?php echo sanitize_text_field($desc); ?></textarea></p>
        

        <p><label for="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>">
        	<?php _e( 'Button Text:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'button_text' )); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'link' )); ?>">
        	<?php _e( 'Button URL:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'link' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'link' )); ?>" type="text" value="<?php echo esc_attr($link); ?>" /></p> 

		<?php
	}
}