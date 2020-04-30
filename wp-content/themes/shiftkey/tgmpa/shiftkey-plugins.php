<?php
require_once get_template_directory() . '/tgmpa/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'shiftkey_register_required_plugins' );

if( !function_exists('shiftkey_register_required_plugins') ):
function shiftkey_register_required_plugins( ) {
    $plugins = array(
        array(
             'name' => esc_attr__( 'Visual Composer', 'shiftkey' ), // The plugin name.
            'slug' => 'js_composer', // The plugin slug (typically the folder name).
            'source' => get_template_directory() . '/tgmpa/plugins/js_composer-6.0.5.zip', // The plugin source.
            'required' => true,
            'version' => '6.0.5',
            'force_activation' => false, 
            'force_deactivation' => false, 
            'external_url' => '', 
            'is_callable' => '' 
        ),       
        array(
             'name' => esc_attr__( 'Shiftkey modules', 'shiftkey' ), // The plugin name.
            'slug' => 'perch_modules', // The plugin slug (typically the folder name).
            'source' => get_template_directory() . '/tgmpa/plugins/perch_modules.zip', // The plugin source.
            'required' => true,
            'version' => '1.3.0.1',
            'force_activation' => false,
            'force_deactivation' => false 
        ),
        array(
             'name' => esc_attr__( 'Shiftkey post likes & view count', 'shiftkey' ), // The plugin name.
            'slug' => 'perch-post-like-view', // The plugin slug (typically the folder name).
            'source' => get_template_directory() . '/tgmpa/plugins/perch-post-like-view.zip', // The plugin source.
            'required' => true,
            'version' => '1.0',
            'force_activation' => false,
            'force_deactivation' => false 
        ), 
        array(
             'name' => esc_attr__( 'Envato market', 'shiftkey' ), // The plugin name.
            'slug' => 'envato-market', // The plugin slug (typically the folder name).
            'source' => get_template_directory() . '/tgmpa/plugins/envato-market.zip', // The plugin source.
            'required' => true,
            'version' => '2.0.1',
            'force_activation' => false,
            'force_deactivation' => false 
        ),
        array(
             'name' => esc_attr__( 'Contact Form 7', 'shiftkey' ),
            'slug' => 'contact-form-7',
            'required' => true 
        ),
        array(
             'name' => esc_attr__( 'Breadcrumb NavXT', 'shiftkey' ),
            'slug' => 'breadcrumb-navxt',
            'required' => true 
        ),
        array(
             'name' => esc_attr__( 'Email Subscription', 'shiftkey' ),
            'slug' => 'email-subscribers',
            'required' => true 
        ),
        array(
             'name' => esc_attr__( 'WP User Avatar', 'shiftkey' ),
            'slug' => 'wp-user-avatar',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'WP Retina 2x', 'shiftkey' ),
            'slug' => 'wp-retina-2x',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'Regenerate Thumbnails', 'shiftkey' ),
            'slug' => 'regenerate-thumbnails',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'One Click Demo Import', 'shiftkey' ),
            'slug' => 'one-click-demo-import',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'Woocommerce', 'shiftkey' ),
            'slug' => 'woocommerce',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'Variation Swatches for WooCommerce', 'shiftkey' ),
            'slug' => 'variation-swatches-for-woocommerce',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'Woocommerce quick view', 'shiftkey' ),
            'slug' => 'yith-woocommerce-quick-view',
            'required' => false 
        ),
        array(
             'name' => esc_attr__( 'Woocommerce wishlist', 'shiftkey' ),
            'slug' => 'yith-woocommerce-wishlist',
            'required' => false 
        ), 
    );
    $config  = array(
         'id' => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '', // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'themes.php', // Parent menu slug.
        'capability' => 'edit_theme_options', 
        'has_notices' => true, // Show admin notices or not.
        'dismissable' => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message' => '' // Message to output right before the plugins table.
    );
    tgmpa( $plugins, $config );
}

endif;