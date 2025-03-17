<?php

/**
 * Template Name: Payment Success
 */

get_header();

if (isset($_GET['token'])) {
    $order_id = sanitize_text_field($_GET['token']); // PayPal Order ID

    // Capture PayPal Payment
    $payment_data = cf7ra_capture_paypal_payment($order_id);

    if ($payment_data && isset($payment_data['status']) && $payment_data['status'] === 'COMPLETED') {
        // Register User
        $user_email = sanitize_email($_SESSION['cf7ra_email']); // Retrieve from session
        $user_password = sanitize_text_field($_SESSION['cf7ra_password']);
        $cpt_title = sanitize_text_field($_SESSION['cf7ra_cpt_title']);

        $user_id = cf7ra_register_user($user_email, $user_password);
        cf7ra_create_cpt($user_id, $cpt_title);

        echo "<h2>Payment Successful!</h2>";
        echo "<p>Your account has been created, and your listing is now live.</p>";

        // Clear session
        unset($_SESSION['cf7ra_email']);
        unset($_SESSION['cf7ra_password']);
        unset($_SESSION['cf7ra_cpt_title']);
    } else {
        echo "<h2>Payment Failed</h2>";
        echo "<p>There was an issue processing your payment. Please contact support.</p>";
    }
}

get_footer();
