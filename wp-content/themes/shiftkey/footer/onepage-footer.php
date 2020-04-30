<?php 
$onepage_footer_display = get_post_meta( get_the_ID(), 'onepage_footer_display', true );
$footer_bg_style = get_post_meta( get_the_ID(), 'footer_bg_style', true );
if( $onepage_footer_display == 'on' ):
?>
<!-- FOOTER-1
============================================= -->
<footer id="footer-1" class="footer p-top-100 division <?php echo esc_attr($footer_bg_style) ?>">
    <div class="container">

        <!-- FOOTER CONTENT -->
        <div class="row">
            <?php get_template_part( 'footer/widget-area' ); ?>
        </div>    <!-- END FOOTER CONTENT -->

        <?php get_template_part( 'footer/copyright' ); ?>

    </div>     <!-- End container -->                                       
</footer>   <!-- END FOOTER-1 -->
<?php endif; ?>