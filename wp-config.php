<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'O%$t|xAra#=eKg[a<R*&]N4vs|i81A30)$L< B$ib([-:!0dxv8GN2w&WS:g(z5Y' );
define( 'SECURE_AUTH_KEY',  'yZyc,5d#(^6q@<HdF0ml?}eKOr>=ka$GH$iEAX uzsHv9G0T^,]:u_YzR/_!dHW_' );
define( 'LOGGED_IN_KEY',    'HI>`SXk@ ;<Fihn}::p|GzgLi2hhJVx-TlcdC8|=yW/I0t./Tjv-3]O2<T,sC,gY' );
define( 'NONCE_KEY',        'k(TzaDF-/>W.{,6`5LQk|KOetF-bv>G((w2ETV]^/}NoiWvYq^|BW>,-X9h!h>}(' );
define( 'AUTH_SALT',        ')9cifL,q&dap}kUQAJG;9dZo;`U%FCi{OMXdy] pv4v{+<ZV[E>?,Lqq3w+Zc@X2' );
define( 'SECURE_AUTH_SALT', '78F&E#)Lp:N.pMG<PeH>YM%uK*4W-ZQOabyeCT6[HY}L-DzZ~xrX,WG281`L@kgJ' );
define( 'LOGGED_IN_SALT',   'Y$=??F0lA4!qTC^4qh7V.>1B[hHl)gp:keF;_;$<rG(6m>0|H9na{k<12g=akg9:' );
define( 'NONCE_SALT',       '6K~>]:3z_n%e1xMNrS+`3%SS$6ke7Bydl=QHNnZDv<;Xh08|.qKG(mYU2B.:jnfu' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
