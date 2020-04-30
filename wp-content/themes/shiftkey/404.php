<?php get_header(); ?>	
		

		<div id="page-404" class="hero-section division m-bottom-100">
				<div class="row">


					<!-- 404 PAGE TEXT -->
					<div class="col-md-10 offset-md-1">
						<div class="404-txt text-center">

							<!-- Image -->
							<div class="error-404-img">
								<img class="img-fluid" src="<?php echo SHIFTKEY_URI; ?>/images/error-404.png" alt="<?php echo esc_attr__( '404 page', 'shiftkey' ); ?>">
							</div>

							<!-- Text -->
							<h2><?php echo esc_html__('Page Not Found', 'shiftkey'); ?></h2>
							<h5 class="h5-md"><?php echo esc_html__('The page you are looking for might have been moved , renamed or might never existed', 'shiftkey'); ?> </h5>

							<!-- Button -->
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-tra-white primary-color-hover"><?php echo esc_html__('Go Back To Home', 'shiftkey'); ?></a>			

						</div>
					</div>	<!-- END 404 PAGE TEXT -->


				</div>	  <!-- End row -->
		</div>
		 			

<?php get_footer(); ?>