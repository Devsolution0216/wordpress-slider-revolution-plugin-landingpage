<?php
/**
 * Perch class used to implement a Recent Posts widget. 
 */
class Perch_Widget_Recent_Posts extends WP_Widget {
	/**
	 * Sets up a new Recent Posts widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			 'classname' => 'widget_recent_posts',
			'description' => __( "Your site&#8217;s most recent Posts.", "perch" ) 
		);

		parent::__construct( 
			'recent-posts', 
			perch_modules_current_theme().' '.__( 'Recent Posts', 'perch' ), 
			$widget_ops 
		);
		
		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( !isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		} //!isset( $args[ 'widget_id' ] )		

		$title = ( !empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : __( 'Recent Posts', 'perch' );		

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );		

		$number = ( !empty( $instance[ 'number' ] ) ) ? absint( $instance[ 'number' ] ) : 5;
		if ( !$number )
			$number = 5;
		$show_date = isset( $instance[ 'show_date' ] ) ? $instance[ 'show_date' ] : false;		
		/**
		 * Filter the arperchents for the Recent Posts widget.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			 'posts_per_page' => $number,
			'no_found_rows' => true,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true 
		) ) );		

		if ( $r->have_posts() ): ?>
        <?php echo perch_context_args($args[ 'before_widget' ]); ?>
        <?php
			if ( $title ) {
				echo perch_context_args($args[ 'before_title' ] . esc_attr($title) . $args[ 'after_title' ]);
			} //$title
		?>

		<ul class="popular-posts">
        <?php
        	$count = 1;
        	$image_size = apply_filters('perch_modules/widgets/recent_posts_widget/image_size', 'thumbnail');
			while ( $r->have_posts() ):
				$r->the_post(); $li_class = has_post_thumbnail()? 'has-thumb' : 'no-thumb'; ?>
				<li class="clearfix d-flex align-items-center <?php echo esc_attr($li_class); ?>">
					<?php if( has_post_thumbnail() ): ?>
						<?php the_post_thumbnail( esc_attr($image_size), array('class' => 'img-fluid') ); ?>
					<?php endif; ?>
					<div class="post-summary">
					<a class="rose-hover" href="<?php the_permalink(); ?>"><?php echo get_the_title() ? perch_get_trim_words(get_the_title(), 10, ' ...') : the_ID(); ?></a>
					<?php if ( $show_date ): ?>
						<p><?php echo get_the_date(); ?></p>
					<?php endif; ?>
					</div>
				</li>  
        <?php 
	        $count++; 
	        endwhile;        
	    ?>
       </ul>

        <?php
			echo perch_context_args($args[ 'after_widget' ]); ?>
        <?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		endif;
	}	

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance[ 'title' ]     = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'number' ]    = (int) $new_instance[ 'number' ];
		$instance[ 'show_date' ] = isset( $new_instance[ 'show_date' ] ) ? (bool) $new_instance[ 'show_date' ] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : '';
		$number    = isset( $instance[ 'number' ] ) ? absint( $instance[ 'number' ] ) : 5;
		$show_date = isset( $instance[ 'show_date' ] ) ? (bool) $instance[ 'show_date' ] : false; 
		?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
        	<?php _e( 'Title:', 'perch' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php _e( 'Number of posts to show:', 'perch' ); ?></label>
        <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="1" value="<?php echo absint($number); ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox"<?php	checked( $show_date ); ?> id="<?php	echo esc_attr($this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php _e( 'Display post date?', 'perch' ); ?></label></p>
		<?php
	}
}