<?php
//add_action('wpcf7_mail_sent', 'cf7ra_handle_form_submission');

add_action('wpcf7_submit', 'cf7ra_handle_form_submission');

function cf7ra_handle_form_submission($contact_form)
{
    $form_id = get_option('cf7ra_form_id');

    if ($contact_form->id() != $form_id) {
        return;
    }

    $submission = WPCF7_Submission::get_instance();
    $uploaded_files = $submission->uploaded_files();
    $uploaded_urls = [];

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
    //  $plan = sanitize_text_field($data['payment_plan'] ?? 'monthly');

    // Store user details in session
    session_start();
    $_SESSION['cf7ra_email'] = $email;
    $_SESSION['cf7ra_password'] = $password;
    $_SESSION['cf7ra_cpt_title'] = $cpt_title;

    $return_url = site_url('/payment-success');
    $cancel_url = site_url('/payment-cancel');


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

    /* add_post_meta($farmPostID, 'cf7ra_field_mappings_listing_plan', $data['listing-plan'][0], true);
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
    add_post_meta($farmPostID, 'cf7ra_field_mappings_num_of_bathrooms', $data['num-of-bathrooms'][0], true);
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
    add_post_meta($farmPostID, 'cf7ra_field_mappings_custom_seller_state', $data['seller-state'][0], true);
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

    $housing_name = isset($data['housing_name']) ? implode(',', $data['housing_name']) : '';
    add_post_meta($farmPostID, 'cf7ra_field_mappings_housing_name', $housing_name, true);

    $milk_facility = isset($data['milk_facility']) ? implode(',', $data['milk_facility']) : '';
    add_post_meta($farmPostID, 'cf7ra_field_mappings_milk_facility', $milk_facility, true);

    $feed_storage  = isset($data['feed_storage']) ? implode(',', $data['feed_storage']) : '';
    add_post_meta($farmPostID, 'cf7ra_field_mappings_feed_storage', $feed_storage, true);

    add_post_meta($farmPostID, 'cf7ra_field_mappings_manure_storage', $data['manure_storage'][0], true);

    // $displayPrimaryPhone = isset($data['displayPrimaryPhone']) ? implode(',', $data['displayPrimaryPhone']) : '';
    $displayPrimaryPhone = $data['displayPrimaryPhone'][0];
    add_post_meta($farmPostID, 'cf7ra_field_mappings_primary_phone', $displayPrimaryPhone, true);

    // $displayAlternatePhone = isset($data['displayAlternatePhone']) ? implode(',', $data['displayAlternatePhone']) : '';
    $displayAlternatePhone = $data['displayAlternatePhone'][0];
    add_post_meta($farmPostID, 'cf7ra_field_mappings_sec_phone', $displayAlternatePhone, true);

    // $sameProperty = isset($data['sameProperty']) ? implode(',', $data['sameProperty']) : '';
    $sameProperty = $data['sameProperty'][0];
    add_post_meta($farmPostID, 'cf7ra_field_mappings_same_property', $sameProperty, true);

    // $displaystreetAddress = isset($data['displaystreetAddress']) ? implode(',', $data['displaystreetAddress']) : '';
    $displaystreetAddress = $data['displaystreetAddress'][0];
    add_post_meta($farmPostID, 'cf7ra_field_mappings_display_street_address', $displaystreetAddress, true);

    // $displaylastName = isset($data['displaylastName']) ? implode(',', $data['displaylastName']) : '';
    $displaylastName = $data['displaylastName'][0];
    add_post_meta($farmPostID, 'cf7ra_field_mappings_last_name', $displaylastName, true);

    // $displayFirstName = isset($data['displayFirstName']) ? implode(',', $data['displayFirstName']) : '';
    $displayFirstName = $data['displayFirstName'][0];
    add_post_meta($farmPostID, 'cf7ra_field_mappings_first_name', $displayFirstName, true);
    add_post_meta($farmPostID, 'cf7ra_field_mappings_photo_desc', $data['photo-description'], true); */

    foreach ($data as $key => $value) {
        if ($key !== 'account-password' && $key !== 'account-verify-password') {
            // Replace hyphens with underscores
            $meta_key = 'cf7ra_field_mappings_' . str_replace('-', '_', $key);

            // Handle arrays: flatten to comma-separated string
            if (is_array($value)) {
                $value = array_filter($value); // remove empty values
                $value = implode(', ', $value);
            }

            if ($key === 'asking-price') {
                $value = (int) preg_replace('/\D/', '', $value);
            }
            // Skip saving if the value is still empty
            if (!empty($value) || $value === '0') {
                add_post_meta($farmPostID, $meta_key, sanitize_text_field($value), true);
            }
        }
    }
    add_post_meta($farmPostID, 'cf7ra_field_mappings_farm_status', 'Active', true);

    // Check if 'multiplefile' field has uploaded files
    if (!empty($uploaded_files['multiplefile'])) {
        $files = $uploaded_files['multiplefile'];
        // Ensure $files is an array
        if (!is_array($files)) {
            $files = [$files];
        }
        $i = 1;
        foreach ($files as $file_path) {
            if (file_exists($file_path)) {
                $file_name = basename($file_path);
                $file_type = wp_check_filetype($file_name, null);

                // Prepare the file array
                $upload = [
                    'name'     => $file_name,
                    'type'     => $file_type['type'],
                    'tmp_name' => $file_path,
                    'error'    => 0,
                    'size'     => filesize($file_path),
                ];

                // Include necessary WordPress files
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Upload the file to the Media Library
                $overrides = ['test_form' => false];
                $file = wp_handle_sideload($upload, $overrides);

                if (!isset($file['error'])) {
                    $attachment = [
                        'post_mime_type' => $file['type'],
                        'post_title'     => sanitize_file_name($file_name),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ];

                    $attach_id = wp_insert_attachment($attachment, $file['file']);
                    if ($i == 1)
                        set_post_thumbnail($farmPostID, $attach_id);

                    $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // Get the URL of the uploaded file
                    $url = wp_get_attachment_url($attach_id);
                    $uploaded_urls[] = $url;
                }
            }
            $i++;
        }

        // Save the URLs to post meta
        if (!empty($uploaded_urls)) {
            // Replace 'your_post_id' with the actual post ID
            update_post_meta($farmPostID, 'cf7ra_field_mappings_photo_upload', $uploaded_urls);
        }
    }

    /* if (isset($files['photo-upload'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Move file into Media Library
        $attachment_id = media_handle_sideload([
            'name'     => basename($files['photo-upload'][0]),
            'tmp_name' => $files['photo-upload'][0]
        ], 0);

        if (!is_wp_error($attachment_id)) {
            $url = wp_get_attachment_url($attachment_id);
            add_post_meta($farmPostID, 'cf7ra_field_mappings_photo_upload', esc_url($url));
        }
    } */



    /* if ($plan == 'one_time') {
        $order = cf7ra_create_paypal_order(50, 'USD', $return_url, $cancel_url);
    } elseif ($plan == 'monthly') {
        $order = cf7ra_create_paypal_subscription_plan(10, 'MONTH', 'USD');
    } else {
        $order = cf7ra_create_paypal_subscription_plan(50, '6months', 'USD');
    }
    foreach ($order['links'] as $link) {
        if ($link['rel'] === 'approve') {
            $GLOBALS['cf7ra_next_redirect'] = $link['href'];
            $contact_form->skip_mail = true; // Optional: skip CF7 email if needed
            return;
        }
    } */
}
add_filter('wpcf7_ajax_json_echo', 'cf7ra_custom_json_redirect', 10, 2);

function cf7ra_custom_json_redirect($response, $result)
{
    if (!empty($GLOBALS['cf7ra_next_redirect'])) {
        $response['redirect_to'] = esc_url_raw($GLOBALS['cf7ra_next_redirect']);
    }
    return $response;
}

add_filter('wpcf7_spam', '__return_false');
add_filter('wpcf7_skip_mail', '__return_false');
add_filter('wpcf7_remove_uploaded_file', '__return_false');
