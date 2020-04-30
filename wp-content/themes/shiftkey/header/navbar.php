<header id="<?php shiftkey_header_id(); ?>" <?php shiftkey_header_class('navbar-style2'); ?>>
	<nav <?php shiftkey_navbar_class(); ?>>
		<div class="container">
			<?php
			/**
			 * Hook: shiftkey/header/logo.
			 * Default template file location: shiftkey/header
			 *
			 * @hooked shiftkey_header_logo - 10
			 */
			 do_action( 'shiftkey/header/logo' ); 
			?>

			<?php
			/**
			 * Hook: shiftkey/header/menu.
			 * Default template file location: shiftkey/header
			 *
			 * @hooked shiftkey_header_mobile_menu_icon - 10
			 * @hooked shiftkey_header_nav_menu - 15
			 */
			 do_action( 'shiftkey/header/menu' ); 
			?>

		</div><!-- End container -->
	</nav><!-- End navbar -->
</header><!-- End header -->
