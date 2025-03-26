<?php
//add_action('wpcf7_mail_sent', 'cf7ra_handle_form_submission');

add_action('wpcf7_submit', 'cf7ra_handle_form_submission', 10, 2);

function cf7ra_handle_form_submission($contact_form, $result)
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
    echo "<pre>";
    print_r($data);
    exit();
    $field_mappings = json_decode(get_option('cf7ra_field_mappings'), true);

    $email = sanitize_email($data[$field_mappings['email']] ?? '');
    $password = sanitize_text_field($data[$field_mappings['password']] ?? '');
    $cpt_title = sanitize_text_field($data[$field_mappings['cpt_title']] ?? '');
    $firstname = sanitize_text_field($data[$field_mappings['firstname']] ?? '');
    $lastname = sanitize_text_field($data[$field_mappings['lastname']] ?? '');
    $plan = sanitize_text_field($data['payment_plan'] ?? 'one_time');

    // Store user details in session
    session_start();
    $_SESSION['cf7ra_email'] = $email;
    $_SESSION['cf7ra_password'] = $password;
    $_SESSION['cf7ra_cpt_title'] = $cpt_title;

    $return_url = site_url('/payment-success');
    $cancel_url = site_url('/payment-cancel');

    /* if ($plan == 'one_time') {
        $order = cf7ra_create_paypal_order(50, 'USD', $return_url, $cancel_url);
    } elseif ($plan == 'monthly') {
        $order = cf7ra_create_paypal_subscription_plan(10, 'MONTH', 'USD');
    } else {
        $order = cf7ra_create_paypal_subscription_plan(50, '6months', 'USD');
    } */
    // Register User
    $user_email = sanitize_email($_SESSION['cf7ra_email']); // Retrieve from session
    $user_password = sanitize_text_field($_SESSION['cf7ra_password']);
    $cpt_title = sanitize_text_field($_SESSION['cf7ra_cpt_title']);
    $user_id = username_exists($user_email);
    if (! $user_id && false == email_exists($user_email)) {
        $user_id = cf7ra_register_user($user_email, $user_password);
        update_user_meta($user_id, "first_name",  $firstname);
        update_user_meta($user_id, "last_name",  $lastname);
    }

    $farmPostID = cf7ra_create_cpt($user_id, $cpt_title);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_listing_plan', $data['listing-plan'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_asking_price', $data['asking-price'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_asking_price_rent_lease', $data['asking-price-rent-lease'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_land_unit_type', $data['land-unit-type'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_total_acres', $data['total-acres'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_total_hectare', $data['total-hectare'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_land_description', $data['land-description'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_current-taxes', $data['current-taxes'], true);

    /* if ($order && isset($order['links'][1]['href'])) {
        wp_redirect($order['links'][1]['href']); // Redirect user to PayPal
        exit;
    } */
}
