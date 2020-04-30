<?php

function wp_paypal_order_page() {
    $labels = array(
        'name' => __('Orders', 'wp-paypal'),
        'singular_name' => __('Order', 'wp-paypal'),
        'menu_name' => __('WP PayPal', 'wp-paypal'),
        'name_admin_bar' => __('Order', 'wp-paypal'),
        'add_new' => __('Add New', 'wp-paypal'),
        'add_new_item' => __('Add New Order', 'wp-paypal'),
        'new_item' => __('New Order', 'wp-paypal'),
        'edit_item' => __('Edit Order', 'wp-paypal'),
        'view_item' => __('View Order', 'wp-paypal'),
        'all_items' => __('All Orders', 'wp-paypal'),
        'search_items' => __('Search Orders', 'wp-paypal'),
        'parent_item_colon' => __('Parent Orders:', 'wp-paypal'),
        'not_found' => __('No Orders found.', 'wp-paypal'),
        'not_found_in_trash' => __('No orders found in Trash.', 'wp-paypal')
    );
    
    $capability = 'manage_options';
    $capabilities = array(
        'edit_post' => $capability,
        'read_post' => $capability,
        'delete_post' => $capability,
        'create_posts' => $capability,
        'edit_posts' => $capability,
        'edit_others_posts' => $capability,
        'publish_posts' => $capability,
        'read_private_posts' => $capability,
        'read' => $capability,
        'delete_posts' => $capability,
        'delete_private_posts' => $capability,
        'delete_published_posts' => $capability,
        'delete_others_posts' => $capability,
        'edit_private_posts' => $capability,
        'edit_published_posts' => $capability
    );
    
    $args = array(
        'labels' => $labels,
        'public' => false,
        'exclude_from_search' => true,
 	'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => current_user_can('manage_options') ? true : false,
        'query_var' => false,
        'rewrite' => false,
        'capabilities' => $capabilities,
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('editor')
    );

    register_post_type('wp_paypal_order', $args);
}

function wp_paypal_order_columns($columns) {
    unset($columns['title']);
    unset($columns['date']);
    $edited_columns = array(
        'title' => __('Order', 'wp-paypal'),
        'txn_id' => __('Transaction ID', 'wp-paypal'),
        'first_name' => __('First Name', 'wp-paypal'),
        'last_name' => __('Last Name', 'wp-paypal'),
        'mc_gross' => __('Total', 'wp-paypal'),
        'payment_status' => __('Payment Status', 'wp-paypal'),
        'date' => __('Date', 'wp-paypal')
    );
    return array_merge($columns, $edited_columns);
}

//meta boxes
function wp_paypal_order_meta_box($post) {
    $payment_status = get_post_meta($post->ID, '_payment_status', true);
    $payment_type = get_post_meta($post->ID, '_payment_type', true);
    $txn_id = get_post_meta($post->ID, '_txn_id', true);
    // Add an nonce field so we can check for it later.
    wp_nonce_field('wppaypal_meta_box', 'wppaypal_meta_box_nonce');
    ?>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="_payment_status"><?php _e('Payment Status', 'wp-paypal'); ?></label></th>
                <td><input name="_payment_status" type="text" id="_payment_status" value="<?php echo $payment_status; ?>" class="regular-text">
                    <p class="description">Item Name</p></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="_payment_type"><?php _e('Payment Type', 'wp-paypal'); ?></label></th>
                <td><input name="_payment_type" type="text" id="_payment_type" value="<?php echo $payment_type; ?>" class="regular-text">
                    <p class="description">Item Name</p></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="_txn_id"><?php _e('Transaction ID', 'wp-paypal'); ?></label></th>
                <td><input name="_txn_id" type="text" id="_txn_id" value="<?php echo $txn_id; ?>" class="regular-text">
                    <p class="description">Item Name</p></td>
            </tr>
        </tbody>

    </table>

    <?php
}

function wp_paypal_custom_column($column, $post_id) {
    switch ($column) {
        case 'title' :
            echo $post_id;
            break;
        case 'txn_id' :
            echo get_post_meta($post_id, '_txn_id', true);
            break;
        case 'first_name' :
            echo get_post_meta($post_id, '_first_name', true);
            break;
        case 'last_name' :
            echo get_post_meta($post_id, '_last_name', true);
            break;
        case 'mc_gross' :
            echo get_post_meta($post_id, '_mc_gross', true);
            break;
        case 'payment_status' :
            echo get_post_meta($post_id, '_payment_status', true);
            break;
    }
}

function wp_paypal_save_meta_box_data($post_id) {
    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */
    // Check if our nonce is set.
    if (!isset($_POST['wppaypal_meta_box_nonce'])) {
        return;
    }
    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['wppaypal_meta_box_nonce'], 'wppaypal_meta_box')) {
        return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    /* OK, it's safe for us to save the data now. */
    // Make sure that it is set.
    if (isset($_POST['_payment_status'])) {
        $payment_status = sanitize_text_field($_POST['_payment_status']);
        update_post_meta($post_id, '_payment_status', $payment_status);
    }
}

add_action('save_post', 'wp_paypal_save_meta_box_data');