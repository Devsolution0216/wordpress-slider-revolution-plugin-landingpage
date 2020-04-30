<div id="navbarSupportedContent" class="collapse navbar-collapse">	

	<?php do_action( 'shiftkey/header/menu/before' ); ?>

	<?php 
      $args = array(
        'container'       => '',		        
        'menu_class'      => '',
        'theme_location'  => 'primary',
        'depth'           => 2,
        'walker'          => new Shiftkey_Walker_Menu(),
        'fallback_cb'     => 'Shiftkey_Walker_Menu::fallback',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
      );
      $args = apply_filters( 'shiftkey_navbar_style_args', $args );
      wp_nav_menu( $args );
    ?>

  	<?php do_action( 'shiftkey/header/menu/after' ); ?>

    <?php
    ?>

</div>	<!-- End Navigation Menu -->