		<?php 
		/**
		 * Default template location: shiftkey/footer
		 *
		 * @hooked shiftkey_newsletter_form_template_part - 10
		 */
		do_action( 'shiftkey/footer/before' ); 
		?>
		
		<footer id="<?php shiftkey_footer_id(); ?>" <?php shiftkey_footer_class(); ?>>
			<div class="container">

				<?php 
				/**
				 * Default template location: shiftkey/footer
				 *
				 * @hooked shiftkey_footer_widget_area_template_part - 10
				 * @hooked shiftkey_footer_copyright_template_part - 20
				 */
				do_action( 'shiftkey/footer' ); 
				?>				
				

			</div> <!-- End .container -->										
		</footer> <!-- End footer -->

		<?php 
		/**
		 * Default template location: shiftkey/footer
		 *
		 * @hooked shiftkey_quick_contact_form_template_part - 10
		 */
		do_action( 'shiftkey/footer/after' ); 
		?>
		
	</div>	<!-- End #<?php shiftkey_wrapper_id(); ?> (Start in header.php) -->

<?php wp_footer(); ?>

</body>
</html>
