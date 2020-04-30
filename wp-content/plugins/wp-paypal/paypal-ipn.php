<?php

function wp_paypal_process_ipn() {
    if (!empty($_GET['wp_paypal_ipn']) && $_GET['wp_paypal_ipn'] == '1') {
        $ipn_response = !empty($_POST) ? $_POST : false;
        if (!$ipn_response) {
            wp_die( "Empty PayPal IPN Request", "PayPal IPN", array( 'response' => 200 ) );
            return;
        }
        wp_paypal_debug_log("Received IPN from PayPal", true);
        wp_paypal_debug_log_array($ipn_response, true);
        $paypal_adr = "https://www.paypal.com/cgi-bin/webscr";
        if (WP_PAYPAL_USE_SANDBOX) {
            $paypal_adr = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        }
        wp_paypal_debug_log("Checking if IPN response is valid via ".$paypal_adr, true);
        // Get received values from post data
        $validate_ipn = array('cmd' => '_notify-validate');
        $validate_ipn += stripslashes_deep($ipn_response);
        // Send back post vars to paypal
        $params = array(
            'body' => $validate_ipn,
            'sslverify' => false,
            'timeout' => 60,
            'httpversion' => '1.1',
            'compress' => false,
            'decompress' => false,
            'user-agent' => 'WP PayPal/' . WP_PAYPAL_VERSION
        );
        wp_paypal_debug_log("IPN Request: ", true);
        wp_paypal_debug_log_array($params, true);
        // Post back to get a response
        $response = wp_remote_post($paypal_adr, $params);
        wp_paypal_debug_log("IPN Response: ", true);
        wp_paypal_debug_log_array($response, true);
        // check to see if the request was valid
        $ipn_verified = false;
        if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
            header( 'HTTP/1.1 200 OK' );
            wp_paypal_debug_log("Received valid response from PayPal", true);
            $ipn_verified = true;
        }
        
        if(!$ipn_verified){
            wp_paypal_debug_log("Received invalid response from PayPal", false);
            if (is_wp_error($response)) {
                wp_paypal_debug_log("Error response: ".$response->get_error_message(), false);
            }
            wp_die( "PayPal IPN Request Failure", "PayPal IPN", array( 'response' => 200 ) );
            return;
        }
        //process data
        $payment_status = '';
        if (isset($ipn_response['payment_status'])) {
            $payment_status = sanitize_text_field($ipn_response['payment_status']);
            wp_paypal_debug_log("Payment Status - " . $payment_status, true);
        }
        
        //Only check the payment_status when it's not a subscription signup
        if(isset($ipn_response['txn_type']) && $ipn_response['txn_type'] != 'subscr_signup'){
            if ($payment_status != 'Completed') {  //only process a completed payment
                wp_paypal_debug_log("This payment cannot be processed", false);
                return;
            }
        }
        $txn_id = '';
        if (isset($ipn_response['txn_id'])) {
            $txn_id = sanitize_text_field($ipn_response['txn_id']);
        } else {  //do not process empty transaction id
            return;
        }
        $args = array(
            'post_type' => 'wp_paypal_order',
            'meta_query' => array(
                array(
                    'key' => '_txn_id',
                    'value' => $txn_id,
                    'compare' => '=',
                ),
            ),
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {  //a record already exists
            wp_paypal_debug_log("An order with this transaction ID already exists. This payment will not be processed.", false);
            return;
        }
        $first_name = '';
        if (isset($ipn_response['first_name'])) {
            $first_name = sanitize_text_field($ipn_response['first_name']);
        }
        $last_name = '';
        if (isset($ipn_response['last_name'])) {
            $last_name = sanitize_text_field($ipn_response['last_name']);
        }
        $mc_gross = '';
        if (isset($ipn_response['mc_gross'])) {
            $mc_gross = sanitize_text_field($ipn_response['mc_gross']);
        }
        $receiver_email = '';
        if (isset($ipn_response['receiver_email'])) {
            $receiver_email = sanitize_text_field($ipn_response['receiver_email']);
            $seller_email = get_option('wp_paypal_email');
            if ($seller_email != $receiver_email) {
                wp_paypal_debug_log("Seller PayPal email (".$seller_email.") and Receiver PayPal email (".$receiver_email.") do not match. This payment cannot be processed.", false);
                return;
            }
        }
        $wp_paypal_order = array(
            'post_title' => 'order',
            'post_type' => 'wp_paypal_order',
            'post_content' => '',
            'post_status' => 'publish',
        );
        wp_paypal_debug_log("Updating order information", true);
        $post_id = wp_insert_post($wp_paypal_order);  //insert a new order
        $post_updated = false;
        if ($post_id > 0) {
            $post_content = print_r($ipn_response, true);
            $updated_post = array(
                'ID' => $post_id,
                'post_title' => $post_id,
                'post_type' => 'wp_paypal_order',
                'post_content' => $post_content
            );
            $updated_post_id = wp_update_post($updated_post);  //update the order
            if ($updated_post_id > 0) {  //successfully updated
                $post_updated = true;
            }
        }
        //save order information
        if ($post_updated) {
            update_post_meta($post_id, '_txn_id', $txn_id);
            update_post_meta($post_id, '_first_name', $first_name);
            update_post_meta($post_id, '_last_name', $last_name);
            update_post_meta($post_id, '_mc_gross', $mc_gross);
            update_post_meta($post_id, '_payment_status', $payment_status);
            wp_paypal_debug_log("Order information updated", true);
            do_action('wp_paypal_order_processed', $post_id);
            $ipn_response['order_id'] = $post_id;
            do_action('wp_paypal_ipn_processed', $ipn_response);
        } else {
            wp_paypal_debug_log("Order information could not be updated", false);
            return;
        }
        wp_paypal_debug_log("IPN processing completed", true, true);
    }
}