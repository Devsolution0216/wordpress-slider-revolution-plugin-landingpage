<?php
$opt_name = 'perch_options';
/**
 * Custom function for the callback referenced above
 */
if ( ! function_exists( 'perch_modules_redux_custom_field' ) ) {
    function perch_modules_redux_custom_field( $field, $value ) {
        print_r( $field );
        echo '<br/>';
        print_r( $value );
    }
}

if ( ! function_exists( 'perch_modules_redux_gradient_preview' ) ) {
    function perch_modules_redux_gradient_preview( $field, $value ) {
        print_r( $field );
        echo '<br/>';
        print_r( $value );
        return $field;
    }
}


/**
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 * */
if ( ! function_exists( 'perch_modules_dynamic_section' ) ) {
    function perch_modules_dynamic_section( $sections ) {
        //$sections = array();
        $sections[] = array(
            'title'  => __( 'Section via hook', 'perch' ),
            'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'perch' ),
            'icon'   => 'el el-paper-clip',
            // Leave this as a blank section, no options just some intro text set above.
            'fields' => array()
        );

        return $sections;
    }
}

// Change the arguments after they've been declared, but before the panel is created
add_filter('redux/options/' . $opt_name . '/args', 'perch_modules_change_arguments' );
/**
 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
 * */
if ( ! function_exists( 'perch_modules_change_arguments' ) ) {
    function perch_modules_change_arguments( $args ) {
        $args['dev_mode'] = false;

        return $args;
    }
}

/**
 * Filter hook for filtering the default value of any given field. Very useful in development mode.
 * */
if ( ! function_exists( 'perch_modules_change_defaults' ) ) {
    function perch_modules_change_defaults( $defaults ) {
        $defaults['str_replace'] = 'Testing filter hook!';

        return $defaults;
    }
}

/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'perch_modules_remove_demo' ) ) {
    function perch_modules_remove_demo() {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
}

function perch_modules_removeDemoModeLink() { // Be sure to rename this function to something more unique
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
    }
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
    }
}
add_action('init', 'perch_modules_removeDemoModeLink');

/**
 * Filter hook for filtering the default value of any given field. Very useful in development mode.
 * */
if ( ! function_exists( 'perch_modules_admin_bar_links' ) ) {
    function perch_modules_admin_bar_links($args = array()) {
        // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
		$args['admin_bar_links'][] = array(
		    'id'    => 'redux-docs',
		    'href'  => 'http://docs.reduxframework.com/',
		    'title' => __( 'Documentation', 'perch' ),
		);

		$args['admin_bar_links'][] = array(
		    //'id'    => 'redux-support',
		    'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
		    'title' => __( 'Support', 'perch' ),
		);

		$args['admin_bar_links'][] = array(
		    'id'    => 'redux-extensions',
		    'href'  => 'reduxframework.com/extensions',
		    'title' => __( 'Extensions', 'perch' ),
		);

        return $args;
    }
}

if ( ! function_exists( 'perch_modules_admin_share_icons' ) ) {
    function perch_modules_admin_share_icons($args = array()) {

		// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
		$args['share_icons'][] = array(
		    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
		    'title' => 'Visit us on GitHub',
		    'icon'  => 'el el-github'
		    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
		);
		$args['share_icons'][] = array(
		    'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
		    'title' => 'Like us on Facebook',
		    'icon'  => 'el el-facebook'
		);
		$args['share_icons'][] = array(
		    'url'   => 'http://twitter.com/reduxframework',
		    'title' => 'Follow us on Twitter',
		    'icon'  => 'el el-twitter'
		);
		$args['share_icons'][] = array(
		    'url'   => 'http://www.linkedin.com/company/redux-framework',
		    'title' => 'Find us on LinkedIn',
		    'icon'  => 'el el-linkedin'
		);

		 return $args;

	}
}


add_filter('redux/options/header-gradient/compiler', 'compiler_action', 10, 3);
function compiler_action($options, $css, $changed_values) {
    echo '<h1>'.$changed_values.'</h1>';
     
    print_r ($options);
    print_r ($css);
    print_r ($changed_values);
}