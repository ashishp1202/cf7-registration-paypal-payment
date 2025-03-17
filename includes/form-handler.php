<?php

add_action('wpcf7_mail_sent', 'cf7ra_handle_form_submission');

function cf7ra_handle_form_submission($contact_form)
{
    $form_id = get_option('cf7ra_form_id');
    if ($contact_form->id() != $form_id) {
        return;
    }

    $submission = WPCF7_Submission::get_instance();
    if (!$submission) {
        return;
    }

    $data = $submission->get_posted_data();
    $field_mappings = json_decode(get_option('cf7ra_field_mappings'), true);

    $email = sanitize_email($data[$field_mappings['email']] ?? '');
    $password = sanitize_text_field($data[$field_mappings['password']] ?? '');
    $cpt_title = sanitize_text_field($data[$field_mappings['cpt_title']] ?? '');
    $plan = sanitize_text_field($data['payment_plan'] ?? 'one_time');

    // Store user details in session
    session_start();
    $_SESSION['cf7ra_email'] = $email;
    $_SESSION['cf7ra_password'] = $password;
    $_SESSION['cf7ra_cpt_title'] = $cpt_title;

    $return_url = site_url('/payment-success');
    $cancel_url = site_url('/payment-cancel');

    if ($plan == 'one_time') {
        $order = cf7ra_create_paypal_order(50, 'USD', $return_url, $cancel_url);
    } elseif ($plan == 'monthly') {
        $order = cf7ra_create_paypal_subscription_plan(10, 'MONTH', 'USD');
    } else {
        $order = cf7ra_create_paypal_subscription_plan(50, '6months', 'USD');
    }

    if ($order && isset($order['links'][1]['href'])) {
        wp_redirect($order['links'][1]['href']); // Redirect user to PayPal
        exit;
    }
}
