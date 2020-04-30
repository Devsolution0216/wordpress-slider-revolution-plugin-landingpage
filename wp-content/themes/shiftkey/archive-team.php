<?php get_header(); ?> 


    <?php get_template_part( 'template-parts/content', 'before' );  ?>


        <?php 
            if ( have_posts() ) : 

                shiftkey_team_archive_content();

                echo '<div class="row">';

                // Start the loop.
                $animation_duration = 300;
                while ( have_posts() ) : the_post();
                    /*
                    * Include the Post-Format-specific template for the content.
                    * If you want to override this in a child theme, then include a file
                    * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                    */
                    ?>

                    <div  <?php post_class('col-md-6 col-lg-4 animated'); ?> data-animation="fadeInUp" data-animation-delay="<?php echo intval($animation_duration) ?>">
                    <?php get_template_part( 'team/content', 'loop' ); ?> 
                    </div>
                    <?php
                    $animation_duration = $animation_duration + 100;
                    // End the loop.
                endwhile;

                    get_template_part( 'team/hiring' ); 

                echo '</div>';

                shiftkey_numeric_posts_nav();

                // If no content, include the "No posts found" template.
                else :

                get_template_part( 'template-parts/post/content', 'none' );           

            endif;
        ?>

        <?php get_template_part( 'template-parts/content', 'after' );   ?>


<?php get_footer(); ?>