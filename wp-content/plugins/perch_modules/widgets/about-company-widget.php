<?php

class Perch_About_me_Widget extends WP_Widget {



  function __construct() {

      $widget_ops = array(
         'classname' => apply_filters('perch_modules/widgets/about_us/classname', 'footer-info'),
        'description' => __('Display title, description & social icons', 'perch' )
      );
      // Instantiate the parent object
      parent::__construct( 
        'perch_about_us', 
        perch_modules_current_theme().' '.__( 'About Us', 'perch' ), 
        $widget_ops 
      );
    
  }


  function widget( $args, $instance ) {
    $title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';    
    $image = ( !empty( $instance[ 'image' ] ) ) ? $instance[ 'image' ] : '';    
    $width = ( !empty( $instance[ 'width' ] ) ) ? $instance[ 'width' ] : '';  	
    $description = isset( $instance[ 'description' ] )? $instance[ 'description' ] : '';    
   
    $social_links = isset( $instance[ 'social_links' ] ) ? $instance[ 'social_links' ] : false;  

    echo perch_context_args($args['before_widget']);

    if ( $image ) {
      $width_attr = ( $width != '' )? ' width="'.intval($width).'"' : '';
      echo '<img src="'.esc_url($image).'" alt="' . esc_attr($title) . '" class="img-fluid"'.$width_attr.'>';
    } //$title

    if ( $title ) {
      echo '<h4 class="h4-md txt-up txt-900">' . esc_attr($title) . '</h4>';
    } //$title

    echo '<p>'.do_shortcode($description).'</p>';

    if( $social_links ){
      do_action('perch_modules/widgets/social_links');
    } 

    echo perch_context_args($args['after_widget']);
  }

  function update( $new_instance, $old_instance ) {
    // Save widget options
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['image'] = ( ! empty( $new_instance['image'] ) ) ? strip_tags( $new_instance['image'] ) : '';
    $instance['width'] = ( ! empty( $new_instance['width'] ) ) ? strip_tags( $new_instance['width'] ) : '';
    $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
    $instance[ 'social_links' ] = isset( $new_instance[ 'social_links' ] ) ? (bool) $new_instance[ 'social_links' ] : false;

    return $instance;
  }

  function form( $instance ) {
    $title = isset( $instance[ 'title' ] )? $instance[ 'title' ] : 'APPSET.';
    $image = isset( $instance[ 'image' ] )? $instance[ 'image' ] : '';
    $width = isset( $instance[ 'width' ] )? $instance[ 'width' ] : '';    
    $description = isset( $instance[ 'description' ] )? $instance[ 'description' ] : 'Aliquam orci nullam tempor sapien orci gravida donec enim ipsum porta justo integer at velna vitae auctor integer congue magna';
    $social_links = isset( $instance[ 'social_links' ] ) ? (bool) $instance[ 'social_links' ] : false; 

    ?>

    <p><label for="<?php echo esc_attr($this->get_field_id( 'image' )); ?>">
      <div class="perch-upload-container">
          <input  id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image' )); ?>" type="text" class="perch-generator-attr perch-generator-upload-value" placeholder="<?php echo esc_attr(__('Image url', 'perch')) ?>" value="<?php echo esc_url($image); ?>">
          <a href="#" class="button perch-upload-button"><span class="wp-media-buttons-icon"></span>Upload</a>
          <input type="text" size="10" name="<?php echo esc_attr($this->get_field_name( 'width' )); ?>" placeholder="<?php echo esc_attr(__('Width Max.', 'perch')) ?>" value="<?php echo esc_attr($width); ?>">
          <?php if ($image != '') : ?>
          <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(__('Image url', 'perch')) ?>" width="80">   
          <?php endif; ?>  
     </div>     
    <p>

    <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
          <?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php _e( 'Description:', 'perch' ); ?></label> 
      <textarea cols="20" rows="4" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'description' )); ?>"><?php echo esc_attr( $description ); ?></textarea></p>

    <p><input class="checkbox" type="checkbox"<?php checked( $social_links ); ?> id="<?php echo esc_attr($this->get_field_id( 'social_links' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'social_links' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'social_links' )); ?>"><?php _e( 'Display social links?', 'perch' ); ?></label></p>    

    <?php
  }
}