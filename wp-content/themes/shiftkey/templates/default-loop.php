<?php 
if ( $posts->have_posts() ):  
$info = $posts->info;
extract($info);
?>
<div class="row">
    <?php     
    // Posts are found   
    while ( $posts->have_posts() ) :
        $posts->the_post();
        global $post;        
        ?>      

        <div class="col-md-<?php echo intval(12/$column) ?>">
            <div class="blog-post<?php echo !has_post_thumbnail()? ' blog-post-no-thumb' : '' ?>">
                <?php if( has_post_thumbnail() ): ?>
                <div class="blog-post-img">                    
                    <div class="post-category"><p><?php shiftkey_post_category();?></p></div><!-- Post Category -->
                    <?php $image_url = get_the_post_thumbnail_url( $post->ID, $img_size ); ?>  
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
                    <?php if( $excerpt_length != 0 ): ?>
                    <P><?php echo wp_trim_words(get_the_excerpt($post), intval($excerpt_length), '...');?></P>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <?php
    endwhile; 
   ?>   
</div>
<?php 
// Posts not found
else :
    echo '<h4>' . __( 'Posts not found', 'shiftkey' ) . '</h4>';
endif; 





