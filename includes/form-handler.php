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
    add_post_meta($farmPostID, 'cf7ra_field_mappings_current_taxes', $data['current-taxes'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_numbers_of_buildings', $data['numbers-of-buildings'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_farm_capacity', $data['farm-capacity'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_capacity_type', $data['capacity-type'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_house_info', $data['house-info'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_num_of_bedrooms', $data['num-of-bedrooms'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_sq_foot', $data['sq-foot'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_year_built', $data['year-built'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_description_buyers_read', $data['description-buyers-read'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_company_url', $data['company-url'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_virtual_tour_url', $data['virtual-tour-url'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_flyer_pdf_url', $data['flyer-pdf-url'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_street_address', $data['street-address'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_street_address_1', $data['street-address-1'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_address_sip', $data['address-sip'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_address_city', $data['address-city'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_address_state', $data['address-state'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_address_country', $data['address-country'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_address_display', $data['address-display'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_general_location', $data['general-location'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_listing_number', $data['custom-listing-number'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_first_name', $data['first-name'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_last_name', $data['last-name'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_street_address', $data['seller-street-address'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_street_address1', $data['seller-street-address1'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_zip', $data['seller-zip'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_city', $data['seller-city'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_state', $data['seller-state'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_primary_phone', $data['seller-primary-phone'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_sec_phone', $data['seller-sec-phone'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_company_url', $data['seller-company-url'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_calling_time', $data['seller-calling-time'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_email_seller', $data['email-seller'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_retrype_email', $data['seller-retrype-email'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_is_realtor', $data['seller-is-realtor'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_account_password', $data['account-password'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_account_verify_password', $data['account-verify-password'], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_listing_sale_type', $data['listing-sale-type'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_irrigated', $data['irrigated'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_type_of_housing', $data['type-of-housing'][0], true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_listing_number_type', $data['listing-number-type'][0], true);

    /* if ($order && isset($order['links'][1]['href'])) {
        wp_redirect($order['links'][1]['href']); // Redirect user to PayPal
        exit;
    } */
}
