<?php
function brand_settings_page() {
    add_theme_page(
        'Brand',
        'Brand',
        'edit_theme_options',
        'brand_setting_page',
        'brand_render_settings_page'
      );
}

function brand_render_settings_page() {
  if( ! current_user_can( 'edit_theme_options' ) ) {
    return;
  }
  ?>
  <div class="wrap">
    <h2 class="plugin-title">Brand Theme <?php echo BRAND_VER // WPCS: XSS ok. ?> </h2>
    <div class="subscribe">
          <h2> GETTING STARTED </h2>
      <p class="description"> <?php esc_html_e( 'Start build your site with Brand theme using the Customizer.', 'brand' ) ?> </p>
      <p class="button-link">
        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ) ?>" class="button button-primary"> Customize </a>
      </p>
      <div class="clearfix"></div>
    </div>
    <div class="support">
          <h2> SUPPORT </h2>
      <h4> Need help? Our team is here for you. </h4>
      <p class="description"> <?php esc_html_e( 'We offer free, full support for Brand Theme. We will answer you as soon as possible.', 'brand' ) ?> </p>
      <p>
        <a href="https://www.wp-brandtheme.com/documentation/" target="_blank">View Brand documentation </a>
        <a href="https://wordpress.org/support/theme/brand" target="_blank"> Ask in the forum </a>
      </p>
    </div>
    <div class="subscribe-form">
          <h2> <?php esc_html_e( 'Join our Newsletter', 'brand' ) ?> </h2>
      <p class="description"> <?php esc_html_e( 'Subscribe now and receive tips on using Brand Theme and its extensions, tutorials and news from the WordPress world and special offers.', 'brand' ) ?> </p>
			<p class="description"> <?php esc_html_e( 'Plus a 30% discount on the Brand Extensions Bundle.', 'brand' ) ?> </p>
			<p> <input name="brand_subscribe_email" placeholder="Your email address" type="email"> </p>
      <p class="button-link">
        <a href="https://www.wp-brandtheme.com/subscribe/" id="brand-subscribe-btn" target="_blank" class="button button-primary"> Subscribe Now </a>
      </p>
			<p class="response-msg"></p>
      <div class="clearfix"></div>
    </div>
  </div>
  <?php
}
add_action( 'admin_menu', 'brand_settings_page' );

function brand_subscribe() {
	check_ajax_referer( 'brand_subscribe_nonce' );
	if( isset( $_POST['brand_subscribe_email'] ) ) {
		$email = sanitize_email( $_POST['brand_subscribe_email'] );
		$args = array(
			'brand_subscribe_email' => $email,
		);
		$response = wp_remote_post( 'https://api.wp-brandtheme.com/1.0/' . md5( $email ), array( 'body' => $args ) );
		if( is_wp_error( $response ) ) {
			$response = array(
				'msg'   => $response->get_error_message(),
				'error' => true,
			);
			wp_send_json( $response );
		}
		$response = json_decode( wp_remote_retrieve_body( $response ) );
		$response = array(
			'msg'   => $response->msg,
			'error' => $response->error,
		);
		wp_send_json( $response );
	}
}
add_action( 'wp_ajax_brand_subscribe', 'brand_subscribe' );
