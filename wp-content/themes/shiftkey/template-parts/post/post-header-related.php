<?php global $post; ?>
<div class="blog-post">
	<?php if( has_post_thumbnail() ): ?>
    <div class="blog-post-img">                    
        <div class="post-category"><p><?php shiftkey_post_category();?></p></div><!-- Post Category -->
        <?php $image_url = get_the_post_thumbnail_url( $post->ID, 'shiftkey-400x300-crop' ); ?>  
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr( get_the_title($post->ID) ) ?>" class="img-fluid"> <!-- Image -->
        
        <div class="blog-post-avatar">            
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 140 ); ?>           
        </div><!-- Author Avatar -->  
    </div><!-- BLOG POST IMAGE -->
	<?php endif; ?>
    
    <div class="blog-post-txt">

        
        <p class="post-meta">
            <?php shiftkey_post_author(); ?> 
            <?php shiftkey_post_date(); ?>
        </p><!-- Post Data -->
        
        <?php shiftkey_sticky_post_text(); ?>
        <a href="<?php the_permalink() ?>">
            <?php the_title(); ?>
        </a><!-- Link -->
    </div>

</div>
