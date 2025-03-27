<?php
add_action('add_meta_boxes', 'action__cf7ra_add_meta_boxes');
function action__cf7ra_add_meta_boxes()
{
    add_meta_box('cf7ra_-data', __('Farm Data', 'cf7-reg-paypal-addon'),  'cf7ra__show_from_data', 'farm_listing', 'normal', 'high');
}
function cf7ra__show_from_data($post)
{

    $form_id = get_post_meta($post->ID, 'cf7ra_field_mappings_form_id', true);
    $listing_plan = get_post_meta($post->ID, 'cf7ra_field_mappings_listing_plan', true);
    $asking_price = get_post_meta($post->ID, 'cf7ra_field_mappings_asking_price', true);
    $asking_price_rent_lease = get_post_meta($post->ID, 'cf7ra_field_mappings_asking_price_rent_lease', true);
    $land_unit_type = get_post_meta($post->ID, 'cf7ra_field_mappings_land_unit_type', true);
    $total_acres = get_post_meta($post->ID, 'cf7ra_field_mappings_total_acres', true);
    $total_hectare = get_post_meta($post->ID, 'cf7ra_field_mappings_total_hectare', true);
    $land_description = get_post_meta($post->ID, 'cf7ra_field_mappings_land_description', true);
    $current_taxes = get_post_meta($post->ID, 'cf7ra_field_mappings_current_taxes', true);
    $numbers_of_buildings = get_post_meta($post->ID, 'cf7ra_field_mappings_numbers_of_buildings', true);
    $farm_capacity = get_post_meta($post->ID, 'cf7ra_field_mappings_farm_capacity', true);
    $capacity_type = get_post_meta($post->ID, 'cf7ra_field_mappings_capacity_type', true);
    $listhouse_infoing_plan = get_post_meta($post->ID, 'cf7ra_field_mappings_house_info', true);
    $num_of_bedrooms = get_post_meta($post->ID, 'cf7ra_field_mappings_num_of_bedrooms', true);
    $sq_foot = get_post_meta($post->ID, 'cf7ra_field_mappings_sq_foot', true);
    $year_built = get_post_meta($post->ID, 'cf7ra_field_mappings_year_built', true);
    $description_buyers_read = get_post_meta($post->ID, 'cf7ra_field_mappings_description_buyers_read', true);
    $company_url = get_post_meta($post->ID, 'cf7ra_field_mappings_company_url', true);
    $virtual_tour_url = get_post_meta($post->ID, 'cf7ra_field_mappings_virtual_tour_url', true);
    $flyer_pdf_url = get_post_meta($post->ID, 'cf7ra_field_mappings_flyer_pdf_url', true);
    $street_address = get_post_meta($post->ID, 'cf7ra_field_mappings_street_address', true);
    $street_address_1 = get_post_meta($post->ID, 'cf7ra_field_mappings_street_address_1', true);
    $address_sip = get_post_meta($post->ID, 'cf7ra_field_mappings_address_sip', true);
    $address_city = get_post_meta($post->ID, 'cf7ra_field_mappings_address_city', true);
    $address_state = get_post_meta($post->ID, 'cf7ra_field_mappings_address_state',  true);
    $address_country = get_post_meta($post->ID, 'cf7ra_field_mappings_address_country', true);
    $address_display = get_post_meta($post->ID, 'cf7ra_field_mappings_address_display', true);
    $general_location = get_post_meta($post->ID, 'cf7ra_field_mappings_general_location', true);
    $custom_listing_number = get_post_meta($post->ID, 'cf7ra_field_mappings_custom_listing_number', true);
    $first_name = get_post_meta($post->ID, 'cf7ra_field_mappings_first_name', true);
    $last_name = get_post_meta($post->ID, 'cf7ra_field_mappings_last_name', true);
    $seller_street_address = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_street_address', true);
    $seller_street_address1 = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_street_address1', true);
    $seller_zip = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_zip',  true);
    $seller_city = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_city', true);
    $seller_state = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_state', true);
    $seller_primary_phone = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_primary_phone', true);
    $seller_sec_phone = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_sec_phone',  true);
    $seller_company_url = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_company_url', true);
    $seller_calling_time = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_calling_time', true);
    $email_seller = get_post_meta($post->ID, 'cf7ra_field_mappings_email_seller',  true);
    $seller_retrype_email = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_retrype_email', true);
    $seller_is_realtor = get_post_meta($post->ID, 'cf7ra_field_mappings_seller_is_realtor',  true);
    $account_password = get_post_meta($post->ID, 'cf7ra_field_mappings_account_password', true);
    $account_verify_password = get_post_meta($post->ID, 'cf7ra_field_mappings_account_verify_password', true);
    $listing_sale_type = get_post_meta($post->ID, 'cf7ra_field_mappings_listing_sale_type',  true);
    $irrigated = get_post_meta($post->ID, 'cf7ra_field_mappings_irrigated', true);
    $type_of_housing = get_post_meta($post->ID, 'cf7ra_field_mappings_type_of_housing',  true);
    $listing_number_type = get_post_meta($post->ID, 'cf7ra_field_mappings_listing_number_type', true);


    echo '<table class="cf7pap-box-data form-table">' .
        '<style>.inside-field td, .inside-field th{ padding-top: 5px; padding-bottom: 5px;}</style>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Form ID', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_form_id" name="cf7ra_field_mappings_form_id" value="' . esc_attr($form_id) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '</table>';
}

add_action('save_post', 'cf7ra_save_custom_meta_box_data');

function cf7ra_save_custom_meta_box_data($post_id)
{

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (isset($_POST['post_type']) && $_POST['post_type'] === 'farm_listing') {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Sanitize and save
    if (isset($_POST['my_custom_field'])) {
        $sanitized = sanitize_text_field($_POST['my_custom_field']);
        update_post_meta($post_id, '_my_custom_field', $sanitized);
    }
}
