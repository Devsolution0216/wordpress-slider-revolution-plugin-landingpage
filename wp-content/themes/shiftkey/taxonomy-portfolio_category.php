<?php get_header(); ?> 


    <?php get_template_part( 'template-parts/content', 'before' );  ?>


        <?php 
            if ( have_posts() ) : 

                shiftkey_portfolio_archive_content();

                echo '<div id="portfolio-1" class="row">';

                // Start the loop.
                $animation_duration = 100;
                while ( have_posts() ) : the_post();
                    /*
                    * Include the Post-Format-specific template for the content.
                    * If you want to override this in a child theme, then include a file
                    * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                    */
                    ?>
                    <div <?php post_class('portfolio-item col-md-6 col-lg-4 mt-30 wow fadeInUp'); ?>  data-wow-delay="<?php echo intval($animation_duration) ?>ms">
                    <?php get_template_part( 'portfolio/content', 'loop' ); ?>
                    </div>
                    <?php             

                    $animation_duration = $animation_duration + 100;
                    // End the loop.
                endwhile;

                echo '</div>';

                shiftkey_numeric_posts_nav();

                // If no content, include the "No posts found" template.
                else :

                get_template_part( 'template-parts/post/content', 'none' );           

            endif;
        ?>

        <?php get_template_part( 'template-parts/content', 'after' );   ?>


<?php get_footer(); ?>