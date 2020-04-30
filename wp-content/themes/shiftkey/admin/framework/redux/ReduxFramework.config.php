<?php
/**
 * ReduxFramework Shiftkey Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( ! class_exists( 'Redux' ) ) {
    return;
}


// This is your option name where all the Redux data is stored.
$opt_name = "shiftkey_options";

// This line is only for altering the demo. Can be easily removed.
$opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

/*
 *
 * --> Used within different fields.  Search for ACTUAL DECLARATION for field examples
 *
 */

$sampleHTML = '';
if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
    Redux_Functions::initWpFilesystem();

    global $wp_filesystem;

    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
}

// Background Patterns Reader
$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
$sample_patterns      = array();

if ( is_dir( $sample_patterns_path ) ) {

    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
        $sample_patterns = array();

        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                $name              = explode( '.', $sample_patterns_file );
                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                $sample_patterns[] = array(
                    'alt' => $name,
                    'img' => $sample_patterns_url . $sample_patterns_file
                );
            }
        }
    }
}

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    'display_name'         => $theme->get( 'Name' ),
    // Name that appears at the top of your panel
    'display_version'      => $theme->get( 'Version' ),
    // Version that appears at the top of your panel
    'menu_type'            => 'submenu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => esc_attr__( 'Theme Options', 'shiftkey' ),
    'page_title'           => sprintf(__( '%s Options', 'shiftkey' ), $theme->get( 'Name' )),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    'google_update_weekly' => false,
    'async_typography'     => false,
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => false,
    'admin_bar_icon'       => '',
    'admin_bar_priority'   => 50,
    'global_variable'      => 'shiftkey_options',
    'dev_mode'             => false,
    'update_notice'        => false,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => false,
    // Enable basic customizer support
    'open_expanded'     => false,                    // Allow you to start the panel in an expanded way initially.
    'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => '',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'shiftkey-options',
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    'footer_credit'     => '',                 

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn'              => false,
    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

$args['share_icons'] = array();
$args['admin_bar_links'] = array();





// Panel Intro text -> before the form
if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
    if ( ! empty( $args['global_variable'] ) ) {
        $v = $args['global_variable'];
    } else {
        $v = str_replace( '-', '_', $args['opt_name'] );
    }
    $args['intro_text'] = '';
} else {
    $args['intro_text'] = '';
}

// Add content after the form.
$args['footer_text'] = '';
Redux::setArgs( $opt_name, $args );
$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => esc_attr__( 'Theme Information 1', 'shiftkey' ),
        'content' => esc_attr__( '<p>This is the tab content, HTML is allowed.</p>', 'shiftkey' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => esc_attr__( 'Theme Information 2', 'shiftkey' ),
        'content' => esc_attr__( '<p>This is the tab content, HTML is allowed.</p>', 'shiftkey' )
    )
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'shiftkey' );
//Redux::setHelpSidebar( $opt_name, $content );

// -> START Global options
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Global options', 'shiftkey' ),
    'id'               => 'global',
    'desc'             => esc_attr__( 'General settings of theme.', 'shiftkey' ),
    'customizer_width' => '400px',
    'icon'             => 'el el-website'
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'General', 'shiftkey' ),
    'id'               => 'general-settings',
    'subsection'       => true,
    'customizer_width' => '450px',   
    'fields'           => shiftkey_general_options(),
) );
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Admin settings', 'shiftkey' ),
    'id'               => 'advanced-settings',
    'subsection'       => true,
    'customizer_width' => '500px',
    'desc'             => esc_attr__( 'You can change wp-admin settings', 'shiftkey' ),
    'fields'           => shiftkey_general_advanced_options(),
) );

// -> START header options
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Header options', 'shiftkey' ),
    'id'               => 'header-options',
    'desc'             => esc_attr__( 'General settings of theme header.', 'shiftkey' ),
    'customizer_width' => '400px',
    'icon'             => 'dashicons dashicons-welcome-add-page',
    'fields'           => shiftkey_header_default_options(),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Logo settings', 'shiftkey' ),
    'id'               => 'logo-settings',
    'subsection'       => true,
    'customizer_width' => '450px',   
    'fields'           => shiftkey_header_options(),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Navbar settings', 'shiftkey' ),
    'id'               => 'navbar-settings',
    'subsection'       => true,
    'customizer_width' => '500px',
    'desc'             => esc_attr__( 'Navigation settings of theme', 'shiftkey' ),
    'fields'           => shiftkey_header_navbar_options(),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Navbar icons', 'shiftkey' ),
    'id'               => 'navbar-icons-settings',
    'subsection'       => true,
    'customizer_width' => '550px',
    'desc'             => esc_attr__( 'Navigation icon settings of theme', 'shiftkey' ),
    'fields'           => shiftkey_header_navbar_icons_options(),
));



Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Header background', 'shiftkey' ),
    'id'               => 'header-bg-settings',
    'subsection'       => true,
    'customizer_width' => '600px',
    'desc'             => esc_attr__( 'Header background settings of theme', 'shiftkey' ),
    'fields'           => shiftkey_header_background_options(),
) );



// -> START Footer
if( function_exists('shiftkey_footer_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Footer options', 'shiftkey' ),
    'id'     => 'footer',
    'icon'   => 'dashicons dashicons-admin-page',
    'fields' => shiftkey_footer_options(),        
) );
endif;

if( function_exists('shiftkey_footer_newsletter_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Newsletter & CTA', 'shiftkey' ),
    'id'     => 'footer-newsletter-form-settings',
    'subsection'       => true,
    'customizer_width' => '450px',
    'fields' => shiftkey_footer_newsletter_options(),        
) );
endif;

if( function_exists('shiftkey_widget_area_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Widget area', 'shiftkey' ),
    'id'     => 'footer-widget-area-settings',
    'subsection'       => true,
    'customizer_width' => '500px',
    'fields' => shiftkey_widget_area_options(),        
) );
endif;

if( function_exists('shiftkey_copyright_bar_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Copyright bar', 'shiftkey' ),
    'id'     => 'footer-copyright-bar-settings',
    'subsection'       => true,
    'customizer_width' => '550px',
    'fields' => shiftkey_copyright_bar_options(),        
) );
endif;

if( function_exists('shiftkey_quick_contact_form_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Bottom sticky form', 'shiftkey' ),
    'id'     => 'footer-bottom-sticky-settings',
    'subsection'       => true,
    'customizer_width' => '600px',
    'fields' => shiftkey_quick_contact_form_options(),        
) );
endif;

// -> START Blog
if( function_exists('shiftkey_blog_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Blog options', 'shiftkey' ),
    'id'     => 'blog',
    'icon'   => 'dashicons dashicons-admin-post',
    'fields' => shiftkey_blog_options(),        
) );
endif;

if( function_exists('shiftkey_single_post_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Single post', 'shiftkey' ),
    'id'     => 'single-post',
    'subsection'       => true,
    'customizer_width' => '450px',
    'fields' => shiftkey_single_post_options(),        
) );
endif;

if( function_exists('is_woocommerce') ):
    // -> START Shop
    if( function_exists('shiftkey_woo_options') ):
    Redux::setSection( $opt_name, array(
        'title'  => esc_attr__( 'Shop options', 'shiftkey' ),
        'id'     => 'shop',
        'icon'   => 'dashicons dashicons-products',
        'fields' => shiftkey_woo_options(),        
    ) );
    endif;

    if( function_exists('shiftkey_single_product_options') ):
    Redux::setSection( $opt_name, array(
        'title'  => esc_attr__( 'Single Product', 'shiftkey' ),
        'id'     => 'single-product',
        'subsection'       => true,
        'customizer_width' => '450px',
        'fields' => shiftkey_single_product_options(),        
    ) );
    endif;
endif;

// -> START Typography
if( function_exists('shiftkey_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'  => esc_attr__( 'Typography', 'shiftkey' ),
    'id'     => 'typography',
    'icon'   => 'el el-font',
    'fields' => shiftkey_typography_options(),        
) );
endif;

if( function_exists('shiftkey_paragraph_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Paragraph', 'shiftkey' ),
    'id'               => 'p-settings',
    'subsection'       => true,
    'customizer_width' => '450px',   
    'fields'           => shiftkey_paragraph_typography_options(),
));
endif;

if( function_exists('shiftkey_h1_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Heading 1', 'shiftkey' ),
    'id'               => 'h1-settings',
    'subsection'       => true,
    'customizer_width' => '500px',   
    'fields'           => shiftkey_h1_typography_options(),
));
endif;

if( function_exists('shiftkey_h2_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Heading 2', 'shiftkey' ),
    'id'               => 'h2-settings',
    'subsection'       => true,
    'customizer_width' => '550px',   
    'fields'           => shiftkey_h2_typography_options(),
));
endif;

if( function_exists('shiftkey_h3_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Heading 3', 'shiftkey' ),
    'id'               => 'h3-settings',
    'subsection'       => true,
    'customizer_width' => '600px',   
    'fields'           => shiftkey_h3_typography_options(),
));
endif;

if( function_exists('shiftkey_h4_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Heading 4', 'shiftkey' ),
    'id'               => 'h4-settings',
    'subsection'       => true,
    'customizer_width' => '650px',   
    'fields'           => shiftkey_h4_typography_options(),
));
endif;

if( function_exists('shiftkey_h5_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Heading 5', 'shiftkey' ),
    'id'               => 'h5-settings',
    'subsection'       => true,
    'customizer_width' => '700px',   
    'fields'           => shiftkey_h5_typography_options(),
));
endif;

if( function_exists('shiftkey_menu_typography_options') ):
Redux::setSection( $opt_name, array(
    'title'            => esc_attr__( 'Menu link', 'shiftkey' ),
    'id'               => 'menu-settings',
    'subsection'       => true,
    'customizer_width' => '750px',   
    'fields'           => shiftkey_menu_typography_options(),
));
endif;

// -> START Color Selection
Redux::setSection( $opt_name, array(
    'title' => esc_attr__( 'Color Selection', 'shiftkey' ),
    'id'    => 'color',
    'desc'  => esc_attr__( '', 'shiftkey' ),
    'icon'  => 'el el-brush',
    'fields'           => shiftkey_styling_options(),
    
) );


if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
    $section = array(
        'icon'   => 'el el-list-alt',
        'title'  => esc_attr__( 'Documentation', 'shiftkey' ),
        'fields' => array(
            array(
                'id'       => '17',
                'type'     => 'raw',
                'markdown' => true,
                'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                //'content' => 'Raw content here',
            ),
        ),
    );
    Redux::setSection( $opt_name, $section );
}
/*
 * <--- END SECTIONS
 */

// If Redux is running as a plugin, this will remove the demo notice and links
//add_action( 'redux/loaded', 'shiftkey_remove_demo' );

// Function to test the compiler hook and demo CSS output.
// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.

// Change the default value of a field after it's been set, but before it's been useds
//add_filter('redux/options/' . $opt_name . '/defaults', 'shiftkey_change_defaults' );

// Dynamically add a section. Can be also used to modify sections/fields
//add_filter('redux/options/' . $opt_name . '/sections', 'shiftkey_dynamic_section');


/**
 * Custom function for the callback validation referenced above
 * */
if ( ! function_exists( 'shiftkey_redux_validate_callback_function' ) ) {
    function shiftkey_redux_validate_callback_function( $field, $value, $existing_value ) {
        $error   = false;
        $warning = false;

        //do your validation
        if ( $value == 1 ) {
            $error = true;
            $value = $existing_value;
        } elseif ( $value == 2 ) {
            $warning = true;
            $value   = $existing_value;
        }

        $return['value'] = $value;

        if ( $error == true ) {
            $field['msg']    = 'your custom error message';
            $return['error'] = $field;
        }

        if ( $warning == true ) {
            $field['msg']      = 'your custom warning message';
            $return['warning'] = $field;
        }

        return $return;
    }
}