<?php
/*
Plugin Name: WP Retina 2x
Plugin URI: https://meowapps.com
Description: Make your website look beautiful and crisp on modern displays by creating + displaying retina images.
Version: 5.6.0
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: wp-retina-2x
Domain Path: /languages

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html

Originally developed for two of my websites:
- Jordy Meow (https://offbeatjapan.org)
- Haikyo (https://haikyo.org)
*/

if ( class_exists( 'Meow_WR2X_Core' ) ) {
  function mfrh_admin_notices() {
    echo '<div class="error"><p>Thanks for installing the Pro version of WP Retina 2x :) However, the free version is still enabled. Please disable or uninstall it.</p></div>';
  }
  add_action( 'admin_notices', 'mfrh_admin_notices' );
  return;
}

global $wr2x_picturefill, $wr2x_retinajs, $wr2x_lazysizes,
	$wr2x_retina_image, $wr2x_core;

$wr2x_version = '5.6.0';
$wr2x_retinajs = '2.0.0';
$wr2x_picturefill = '3.0.2';
$wr2x_lazysizes = '5.1.1';
$wr2x_retina_image = '1.7.2';

// Admin
require( 'wr2x_admin.php');
$wr2x_admin = new Meow_WR2X_Admin( 'wr2x', __FILE__, 'wp-retina-2x' );

// Core
require( 'core.php' );
$wr2x_core = new Meow_WR2X_Core( $wr2x_admin );
$wr2x_admin->core = $wr2x_core;

?>
