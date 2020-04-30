<?php
$logo  = Shiftkey_Header_Config::get_logo(); 
$logo_white = Shiftkey_Header_Config::get_logo(false);
$dimensions = shiftkey_get_option( 'logo_dimensions', array('width'  => '125px','height' => '30px') );
?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand logo-black" rel="home"><img src="<?php echo esc_url($logo); ?>"<?php echo ($dimensions['width'] != '0px')? ' width="'.intval($dimensions['width']).'"' : ''; ?> height="<?php echo intval($dimensions['height']) ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand logo-white" rel="home"><img src="<?php echo esc_url($logo_white); ?>"<?php echo ($dimensions['width'] != '0px')? ' width="'.intval($dimensions['width']).'"' : ''; ?> height="<?php echo intval($dimensions['height']) ?>" alt="<?php bloginfo( 'name' ); ?>"></a>