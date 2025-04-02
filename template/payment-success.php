<?php

/**
 * Template Name: CF7RA Payment Success
 * Template Post Type: page
 */

get_header();

if (!isset($_GET['token'])) {
    echo '<p>Invalid payment session.</p>';
    get_footer();
    exit;
}

$order_id = sanitize_text_field($_GET['token']);


// Capture the payment
$payment_response = cf7ra_capture_paypal_payment($order_id);

if ($payment_response && isset($payment_response['status']) && $payment_response['status'] === 'COMPLETED') {
    // Register the user
    $user_id = cf7ra_register_user($order_data['email'], $order_data['password']);
    $txn = cf7ra_extract_transaction_data($payment_response);

    if ($txn) {
        update_post_meta($post_id, 'paypal_transaction_id', $txn['transaction_id']);
        update_post_meta($post_id, 'paypal_payer_email', $txn['payer_email']);
        update_post_meta($post_id, 'paypal_amount_paid', $txn['amount']);
        update_post_meta($post_id, 'paypal_currency', $txn['currency']);
    }
    // Create the CPT entry
    cf7ra_create_cpt($user_id, $order_data['cpt_title']);

    echo '<p>Payment successful! Your account has been created.</p>';
} else {
    echo '<p>Payment failed. Please try again.</p>';
}

get_footer();
