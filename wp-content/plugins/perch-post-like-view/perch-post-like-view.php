<?php
/*
Plugin Name:  WordPress Post Like & view System
Description:  A simple and efficient post like & view system for WordPress.
Version:      1.0.0
Author:       ThemePerch
Author URI:   http://themeperch.net/
License:
Copyright (C) 2015 Jon Masterson
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
define( 'PPLV_VERSION', '1.0' );
define( 'PPLV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Register the stylesheets for the public-facing side of the site.
 * @since    1.0
 */
add_action( 'wp_enqueue_scripts', 'pplv_enqueue_scripts' );
function pplv_enqueue_scripts() {
	
	/*wp_register_style( 'font-awesome', plugins_url( 'fonts/font-awesome/css/font-awesome.min.css', __FILE__), false, false );
	wp_enqueue_style( 'font-awesome' );

	wp_register_style( 'pplv', plugins_url( 'css/pplv.css', __FILE__), false, false );
	wp_enqueue_style( 'pplv' );*/

	wp_enqueue_script( 'pplv-js', plugins_url( 'js/pplv-js.js', __FILE__), array( 'jquery' ), PPLV_VERSION, false );
	wp_localize_script( 'pplv-js', 'pplv', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'like' => __( 'Like', 'pplv' ),
		'unlike' => __( 'Unlike', 'pplv' )
	) ); 
}

require_once( PPLV_PLUGIN_DIR . 'post-like.php' );
require_once( PPLV_PLUGIN_DIR . 'post-view.php' );

