<?php
/**
 * Perch class used to implement a Footer Social icons widget. 
 */

class Perch_Store_badges_Widget extends WP_Widget {	

	public function __construct() {
		$widget_ops = array(
			 'classname' => apply_filters('perch_modules/widgets/store_badges/classname', 'footer-stores-badge'),
			'description' => __('Display image group with link', 'perch' )
		);

		parent::__construct( 
			'perch_store_badges', 
			perch_modules_current_theme().' '.__( 'Store Badges', 'perch' ), 
			$widget_ops 
		);
		
	}


	public function widget( $args, $instance ) {
		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		} //!isset( $args[ 'widget_id' ] )		

		$title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
		
		

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );



		echo perch_context_args($args[ 'before_widget' ]);
		echo ( !empty( $title ) ) ? $args[ 'before_title' ] . esc_attr($title) . $args[ 'after_title' ] : '';

		$total = 3;
		$val = array();
		for ($i=0; $i < $total; $i++) {
			$_title = 'params'.'_'.$i.'_title';
			$_link = 'params'.'_'.$i.'_link';
			$_src = 'params'.'_'.$i.'_src';
			$val['title'][$i] = isset( $instance[ $_title ] ) ?  sanitize_text_field( $instance[ $_title ] ) : '';
			$val['link'][$i] = isset( $instance[ $_link ] ) ?  esc_url( $instance[ $_link ] ) : '';
			$val['src'][$i] = isset( $instance[ $_src ] ) ?  esc_url( $instance[ $_src ] ) : '';
			if( $val['link'][$i] != '' ){
				echo '<a href="'.esc_url( $val['link'][$i] ).'" class="store" target="_blank">
					<img class="appstore-original" src="'.esc_url( $val['src'][$i] ).'" alt="'.esc_attr( $val['title'][$i] ).'" width="160" height="50">
				</a>';
			}
			

		}
		

		echo perch_context_args($args[ 'after_widget' ]);
	}	

	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'title' ]     = sanitize_text_field( $new_instance[ 'title' ] );	
		$total = 3;
		for ($i=0; $i < $total; $i++) {
			$title = 'params'.'_'.$i.'_title';
			$link = 'params'.'_'.$i.'_link';
			$src = 'params'.'_'.$i.'_src';
			$instance[ $title ]     = sanitize_text_field( $new_instance[ $title ] );
			$instance[ $link ]     = esc_attr( $new_instance[ $link ] );
			$instance[ $src ]     = esc_attr( $new_instance[ $src ] );
		}

				

		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : 'AppSet&reg; Application';	

		$total = 3;
		$val = array();
		for ($i=0; $i < $total; $i++) {
			$_title = 'params'.'_'.$i.'_title';
			$_link = 'params'.'_'.$i.'_link';
			$_src = 'params'.'_'.$i.'_src';
			$val['title'][$i] = isset( $instance[ $_title ] ) ?  sanitize_text_field( $instance[ $_title ] ) : '';
			$val['link'][$i] = isset( $instance[ $_link ] ) ?  esc_url( $instance[ $_link ] ) : '';
			$val['src'][$i] = isset( $instance[ $_src ] ) ?  esc_url( $instance[ $_src ] ) : '';

		}		
		?>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
        	<?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <?php $total = 3; ?>
        <div class="widget-repeater-groups">
		<?php for ($i=0; $i < $total; $i++) { 
			$_title = 'params'.'_'.$i.'_title';
			$_link = 'params'.'_'.$i.'_link';
			$_src = 'params'.'_'.$i.'_src';
			?> 			
			<p class="widget-repeater-group">
			<label><?php echo esc_attr(__( 'Store badge', 'perch' )).' #'.($i+1); ?></label>
			<input type="text" class="widefat" name="<?php echo esc_attr($this->get_field_name( $_title )); ?>" value="<?php echo esc_attr( $val['title'][$i] )  ?>" placeholder="Badges Title">
			<input type="text" class="widefat" name="<?php echo esc_attr($this->get_field_name( $_link )); ?>" value="<?php echo esc_url( $val['link'][$i] )  ?>" placeholder="http://">
			<div class="perch-upload-container">
		      <input type="text" name="<?php echo esc_attr($this->get_field_name( $_src )); ?>" value="<?php echo esc_url( $val['src'][$i] )  ?>" class="perch-generator-attr perch-generator-upload-value" placeholder="<?php echo esc_attr(__('Image url', 'perch')) ?>">
		      <a href="#" class="button perch-upload-button">
		      <span class="wp-media-buttons-icon"></span>Upload</a>
		      <?php if ($val['src'][$i] != '') : ?>
		      <img src="<?php echo esc_url( $val['src'][$i] ); ?>" alt="<?php echo esc_attr(__('Image url', 'perch')) ?>" width="80">   
		      <?php endif; ?>  
	    	</div>
	    	</p>
			
		<?php } ?>
		</div>       

		<?php
	}
	
}