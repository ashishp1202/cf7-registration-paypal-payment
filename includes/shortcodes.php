<?php
add_shortcode('cf7ra_user_listings', 'cf7ra_display_user_listings');

function cf7ra_display_user_listings()
{
    if (!is_user_logged_in()) {
        return '<p>You must be logged in to view your listings.</p>';
    }

    $user_id = get_current_user_id();

    $args = array(
        'post_type'      => 'farm_listing',
        'posts_per_page' => -1,
        'author'         => $user_id,
    );

    $listings = new WP_Query($args);

    if (!$listings->have_posts()) {
        return '<p>You have no listings.</p>';
    }

    ob_start();
    echo '<table class="cf7ra-user-listings">';
?>
    <tr>
        <th>Post title</th>
        <th>Pricing Plans</th>
        <th>Listing Sale Type</th>
        <th>Asking Price</th>
        <th>Land Unit Type</th>
        <th>Actions</th>
    </tr>
    <?php
    while ($listings->have_posts()) : $listings->the_post();
        $listing_plan = get_post_meta(get_the_ID(), 'cf7ra_field_mappings_listing_plan', true);
        $listing_sale_type = get_post_meta(get_the_ID(), 'cf7ra_field_mappings_custom_listing_sale_type',  true);
        $asking_price = get_post_meta(get_the_ID(), 'cf7ra_field_mappings_asking_price', true);
        $land_unit_type = get_post_meta(get_the_ID(), 'cf7ra_field_mappings_land_unit_type', true);
        $post_id = get_the_ID();
        $nonce = wp_create_nonce('delete_listing_' . $post_id);
    ?>
        <tr id="listing-<?php echo $post_id; ?>">
            <td><?php echo get_the_title(); ?></td>
            <td><?php echo $listing_plan; ?></td>
            <td><?php echo $listing_sale_type; ?></td>
            <td><?php echo $asking_price; ?></td>
            <td><?php echo $land_unit_type; ?></td>
            <td><a href="<?php echo add_query_arg('post_id', get_the_ID(), site_url('/edit-listing/')); ?>">Edit</a> / <button class="delete-listing" data-id="<?php echo $post_id; ?>" data-nonce="<?php echo $nonce; ?>">
                    Delete
                </button></td>
        </tr>
<?php
    endwhile;
    echo '</table>';

    wp_reset_postdata();

    return ob_get_clean();
}


// Edit listing shortcode
add_shortcode('cf7ra_edit_listings', 'cf7ra_edit_user_listings');

function cf7ra_edit_user_listings()
{

    if (!is_user_logged_in()) {
        return '<p>You must be logged in to edit your listings.</p>';
    }

    $user_id = get_current_user_id();
    ob_start();
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

    if (! $post_id || get_post_field('post_author', $post_id) != get_current_user_id()) {
        wp_die('You do not have permission to edit this listing.');
    }

    // Process form submission.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cf7ra_edit_nonce'])) {
        if (! wp_verify_nonce($_POST['cf7ra_edit_nonce'], 'cf7ra_edit_listing')) {
            wp_die('Security check failed');
        }

        // Optionally redirect after successful update.
        wp_redirect(add_query_arg('updated', 'true', get_permalink()));
        exit;
    }

    $listing_plan = get_post_meta($post_id, 'cf7ra_field_mappings_listing_plan', true);
    $asking_price = get_post_meta($post_id, 'cf7ra_field_mappings_asking_price', true);
    $asking_price_rent_lease = get_post_meta($post_id, 'cf7ra_field_mappings_asking_price_rent_lease', true);
    $land_unit_type = get_post_meta($post_id, 'cf7ra_field_mappings_land_unit_type', true);
    $total_acres = get_post_meta($post_id, 'cf7ra_field_mappings_total_acres', true);
    $total_hectare = get_post_meta($post_id, 'cf7ra_field_mappings_total_hectare', true);
    $land_description = get_post_meta($post_id, 'cf7ra_field_mappings_land_description', true);
    $current_taxes = get_post_meta($post_id, 'cf7ra_field_mappings_current_taxes', true);
    $numbers_of_buildings = get_post_meta($post_id, 'cf7ra_field_mappings_numbers_of_buildings', true);
    $farm_capacity = get_post_meta($post_id, 'cf7ra_field_mappings_farm_capacity', true);
    $capacity_type = get_post_meta($post_id, 'cf7ra_field_mappings_capacity_type', true);
    $listhouse_infoing_plan = get_post_meta($post_id, 'cf7ra_field_mappings_house_info', true);
    $num_of_bedrooms = get_post_meta($post_id, 'cf7ra_field_mappings_num_of_bedrooms', true);
    $num_of_bathrooms = get_post_meta($post_id, 'cf7ra_field_mappings_num_of_bathrooms', true);
    $sq_foot = get_post_meta($post_id, 'cf7ra_field_mappings_sq_foot', true);
    $year_built = get_post_meta($post_id, 'cf7ra_field_mappings_year_built', true);
    $description_buyers_read = get_post_meta($post_id, 'cf7ra_field_mappings_description_buyers_read', true);
    $company_url = get_post_meta($post_id, 'cf7ra_field_mappings_company_url', true);
    $virtual_tour_url = get_post_meta($post_id, 'cf7ra_field_mappings_virtual_tour_url', true);
    $flyer_pdf_url = get_post_meta($post_id, 'cf7ra_field_mappings_flyer_pdf_url', true);
    $street_address = get_post_meta($post_id, 'cf7ra_field_mappings_street_address', true);
    $street_address_1 = get_post_meta($post_id, 'cf7ra_field_mappings_street_address_1', true);
    $address_sip = get_post_meta($post_id, 'cf7ra_field_mappings_address_sip', true);
    $address_city = get_post_meta($post_id, 'cf7ra_field_mappings_address_city', true);
    $address_state = get_post_meta($post_id, 'cf7ra_field_mappings_address_state',  true);
    $address_country = get_post_meta($post_id, 'cf7ra_field_mappings_address_country', true);
    $address_display = get_post_meta($post_id, 'cf7ra_field_mappings_address_display', true);
    $general_location = get_post_meta($post_id, 'cf7ra_field_mappings_general_location', true);
    $custom_listing_number = get_post_meta($post_id, 'cf7ra_field_mappings_custom_listing_number', true);
    $first_name = get_post_meta($post_id, 'cf7ra_field_mappings_custom_first_name', true);
    $last_name = get_post_meta($post_id, 'cf7ra_field_mappings_custom_last_name', true);
    $seller_street_address = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_street_address', true);
    $seller_street_address1 = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_street_address1', true);
    $seller_zip = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_zip',  true);
    $seller_city = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_city', true);
    $seller_state = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_state', true);
    $seller_primary_phone = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_primary_phone', true);
    $seller_sec_phone = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_sec_phone',  true);
    $seller_company_url = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_company_url', true);
    $seller_calling_time = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_calling_time', true);
    $email_seller = get_post_meta($post_id, 'cf7ra_field_mappings_custom_email_seller',  true);
    $seller_retrype_email = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_retrype_email', true);
    $seller_is_realtor = get_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_is_realtor',  true);
    $account_password = get_post_meta($post_id, 'cf7ra_field_mappings_custom_account_password', true);
    $account_verify_password = get_post_meta($post_id, 'cf7ra_field_mappings_custom_account_verify_password', true);
    $listing_sale_type = get_post_meta($post_id, 'cf7ra_field_mappings_custom_listing_sale_type',  true);
    $irrigated = get_post_meta($post_id, 'cf7ra_field_mappings_custom_irrigated', true);
    $type_of_housing = get_post_meta($post_id, 'cf7ra_field_mappings_type_of_housing',  true);
    $listing_number_type = get_post_meta($post_id, 'cf7ra_field_mappings_listing_number_type', true);
    $manure_storage = get_post_meta($post_id, 'cf7ra_field_mappings_manure_storage', true);
    $housing_name = get_post_meta($post_id, 'cf7ra_field_mappings_housing_name', true);
    $housing_name = explode(',', $housing_name);
    $milk_facility = get_post_meta($post_id, 'cf7ra_field_mappings_milk_facility', true);
    $milk_facility = explode(',', $milk_facility);
    $feed_storage = get_post_meta($post_id, 'cf7ra_field_mappings_feed_storage', true);
    $feed_storage = explode(',', $feed_storage);

    $primary_phone_checkbox = get_post_meta($post->ID, 'cf7ra_field_mappings_primary_phone', true);
    $sec_phone_checkbox = get_post_meta($post->ID, 'cf7ra_field_mappings_sec_phone', true);
    $same_property_checkbox = get_post_meta($post->ID, 'cf7ra_field_mappings_same_property', true);
    $street_address_checkbox = get_post_meta($post->ID, 'cf7ra_field_mappings_street_address', true);
    $lastname_checkbox = get_post_meta($post->ID, 'cf7ra_field_mappings_last_name', true);
    $firstname_checkbox = get_post_meta($post->ID, 'cf7ra_field_mappings_first_name', true);

    echo '<form method="post" action="' . admin_url('admin-post.php') . '">';
    echo '<table class="cf7pap-box-data form-table">' .
        '<style>.inside-field td, .inside-field th{ padding-top: 5px; padding-bottom: 5px;}</style>';

    wp_nonce_field('cf7ra_save_meta_box', 'cf7ra_meta_box_nonce');

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Listing Plan', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td><select name="cf7ra_field_mappings_listing_plan" id="cf7ra_field_mappings_listing_plan" style="width:100%;">' .
        '<option value="Single Listing Plan" ' . selected($listing_plan, 'Single Listing Plan', false) . '>Single Listing Plan</option>' .
        '<option value="Featured Listing Plan" ' . selected($listing_plan, 'Featured Listing Plan', false) . '>Featured Listing Plan</option>' .
        '<option value="Auction Plan" ' . selected($listing_plan, 'Auction Plan', false) . '>Auction Plan</option>' .
        '<option value="Unlimited Use Plan" ' . selected($listing_plan, 'Unlimited Use Plan', false) . '>Unlimited Use Plan</option>' .
        '</select>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Listing Sale Type', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>  
         <input id="listingSale" type="radio" name="cf7ra_field_mappings_custom_listing_sale_type" value="Listing is For Sale" ' . checked($listing_sale_type, 'Listing is For Sale', false) . '>
                             <label for="listingSale">Listing is For Sale</label>
                            <input id="listingLeaseRent" type="radio" name="cf7ra_field_mappings_custom_listing_sale_type" value="Listing is For Lease/Rent" ' . checked($listing_sale_type, 'Listing is For Lease/Rent', false) . '>
                             <label for="listingLeaseRent">Listing is For Lease/Rent</label>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Asking Price', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_asking_price" name="cf7ra_field_mappings_asking_price" value="' . esc_attr($asking_price) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Lease/Rent Per Month', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_asking_price_rent" name="cf7ra_field_mappings_asking_price_rent" value="' . esc_attr($asking_price_rent_lease) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Land Unit Type', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>
        <select id="cf7ra_field_mappings_land_unit_type" name="cf7ra_field_mappings_land_unit_type">' .
        '<option value="Acre" ' . selected($land_unit_type, 'Acre', false) . '>Acre</option>' .
        '<option value="Hectare" ' . selected($land_unit_type, 'Hectare', false) . '>Hectare</option>' .
        '</select>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Total Acres', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_total_acres" name="cf7ra_field_mappings_total_acres" value="' . esc_attr($total_acres) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Total Hectare', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_total_hectare" name="cf7ra_field_mappings_total_hectare" value="' . esc_attr($total_hectare) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Land Description', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="textarea" size="40" id="cf7ra_field_mappings_land_desc" name="cf7ra_field_mappings_land_description" value="' . esc_attr($land_description) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Current Real Estate Taxes', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_current_taxes" name="cf7ra_field_mappings_current_taxes" value="' . esc_attr($current_taxes) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Buildings', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_no_building" name="cf7ra_field_mappings_numbers_of_buildings" value="' . esc_attr($numbers_of_buildings) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Irrigated', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    
         <select id="cf7ra_field_irrigated" name="cf7ra_field_mappings_custom_irrigated">
                                    <option value="Yes" ' . selected($irrigated, 'Yes', false) . '>Yes</option>
                                    <option value="No" ' . selected($irrigated, 'No', false) . '>No</option>
                                 </select>
        </td>' .
        '</tr>';


    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Farm Capacity', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_farm_capacity" name="cf7ra_field_mappings_farm_capacity" value="' . esc_attr($farm_capacity) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Capacity Type', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>
         <select id="cf7ra_field_mappings_capacity_type" name="cf7ra_field_mappings_capacity_type">
                                    <option value="Cows" ' . selected($capacity_type, 'Cows', false) . '>Cows</option>
                                    <option value="Heifers" ' . selected($capacity_type, 'Heifers', false) . '>Heifers</option>
                                    <option value="Calves" ' . selected($capacity_type, 'Calves', false) . '>Calves</option>
                                 </select>
        
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Type of Housing', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>   <input id="stanchion" type="checkbox" name="housing_name[]" value="Stanchion" ' .  (in_array('Stanchion', $housing_name) ? 'checked' : '') . ' />
                                    <label for="stanchion">Stanchion</label>
                                    <input id="tieStalls" type="checkbox" name="housing_name[]" value="Tie Stalls"  ' .  (in_array('Tie Stalls', $housing_name) ? 'checked' : '') . '  />
                                    <label for="tieStalls">Tie Stalls</label>
                                    <input id="freeStalls" type="checkbox" name="housing_name[]" value="Free Stalls" ' .  (in_array('Free Stalls', $housing_name) ? 'checked' : '') . ' />
                                    <label for="freeStalls">Free Stalls</label>
                                    <input id="beddingPack" type="checkbox" name="housing_name[]" value="Bedding Pack" ' .  (in_array('Bedding Pack', $housing_name) ? 'checked' : '') . ' />
                                    <label for="beddingPack">Bedding Pack</label>
                                    <input id="corral" type="checkbox" name="housing_name[]" value="Corral" ' .  (in_array('Corral', $housing_name) ? 'checked' : '') . '/>
                                    <label for="corral">Corral</label></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Milking Facilities', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>
          <input id="parallelParlor" type="checkbox" name="milk_facility[]" value="Parallel Parlor"  ' .  (in_array('Parallel Parlor', $milk_facility) ? 'checked' : '') . '  />
                                    <label for="parallelParlor">Parallel Parlor</label>
                                       <input id="herringboneParlor" type="checkbox" name="milk_facility[]" value="Herringbone Parlor"  ' .  (in_array('Herringbone Parlor', $milk_facility) ? 'checked' : '') . '  />
                                    <label for="herringboneParlor">Herringbone Parlor</label>
                                    <input id="stallBarn" type="checkbox" name="milk_facility[]" value="Stall Barn"  ' .  (in_array('Stall Barn', $milk_facility) ? 'checked' : '') . '  />
                                    <label for="stallBarn">Stall Barn</label>
                                     <input id="rotary" type="checkbox" name="milk_facility[]" value="Rotary"  ' .  (in_array('Rotary', $milk_facility) ? 'checked' : '') . '  />
                                    <label for="rotary">Rotary</label>
        </td>' .
        '</tr>';


    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Feed Storage', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>
          <input id="uprightSilos" type="checkbox" name="feed_storage[]" value="Upright Silos"  ' .  (in_array('Upright Silos', $feed_storage) ? 'checked' : '') . '  />
                                    <label for="uprightSilos">Upright Silos</label>
                                     <input id="bunkerSilos" type="checkbox" name="feed_storage[]" value="Bunker Silos"  ' .  (in_array('Bunker Silos', $feed_storage) ? 'checked' : '') . '  />
                                    <label for="bunkerSilos">Bunker Silos</label>
                                    <input id="agBags" type="checkbox" name="feed_storage[]" value="Ag Bags"  ' .  (in_array('Ag Bags', $feed_storage) ? 'checked' : '') . '  />
                                    <label for="agBags">Ag Bags</label>
                                     <input id="other" type="checkbox" name="feed_storage[]" value="Other"  ' .  (in_array('Other', $feed_storage) ? 'checked' : '') . '  />
                                    <label for="other">Other</label>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Manure Storage', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> 
        <input id="dailyHaul" type="radio" name="cf7ra_field_mappings_manure_storage" value="Daily Haul" ' . checked($manure_storage, 'Daily Haul', false) . '>
                                    <label for="dailyHaul">Daily Haul</label>
                                    <input id="slurrystore" type="radio" name="cf7ra_field_mappings_manure_storage" value="Slurrystore" ' . checked($manure_storage, 'Slurrystore', false) . '>
                                    <label for="slurrystore">Slurrystore</label>
                                    <input id="earthenLagoon" type="radio" name="cf7ra_field_mappings_manure_storage" value="Earthen Lagoon" ' . checked($manure_storage, 'Earthen Lagoon', false) . '>
                                    <label for="earthenLagoon">Earthen Lagoon</label>
                                     <input id="concretePit" type="radio" name="cf7ra_field_mappings_manure_storage" value="Concrete Pit" ' . checked($manure_storage, 'Concrete Pit', false) . '>
                                    <label for="concretePit">Concrete Pit</label>
                                    <input id="none" type="radio" name="cf7ra_field_mappings_manure_storage" value="None" ' . checked($manure_storage, 'None', false) . '>
                                    <label for="none">None</label>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Heifer Facilities', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>  <input id="yes" type="radio" name="cf7ra_field_mappings_type_of_housing" value="Yes" ' . checked($type_of_housing, 'Yes', false) . '>
                                    <label for="yes">Yes</label>
                                    <input id="no" type="radio" name="cf7ra_field_mappings_type_of_housing" value="No" ' . checked($type_of_housing, 'No', false) . '>
                                    <label for="no">No</label>
        
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Listhouse Infoing Plan', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> 
         <select  id="cf7ra_field_mappings_listhouse_infoing_plan" name="cf7ra_field_mappings_house_info">
                                    <option value="True" ' . selected($listhouse_infoing_plan, 'True', false) . '>Listing Includes a Residence</option>
                                    <option value="False" ' . selected($listhouse_infoing_plan, 'False', false) . '>Listing does not include a Residence</option>
                                 </select>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('No. of Bedrooms', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_bedrooms" name="cf7ra_field_mappings_num_of_bedrooms" value="' . esc_attr($num_of_bedrooms) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('No. of Bathrooms', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_num_of_bathrooms" name="cf7ra_field_mappings_num_of_bathrooms" value="' . esc_attr($num_of_bathrooms) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('No. of sq. foot', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_sq_foot" name="cf7ra_field_mappings_sq_foot" value="' . esc_attr($sq_foot) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Year Built', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_year_built" name="cf7ra_field_mappings_year_built" value="' . esc_attr($year_built) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Detail description for potential buyers to read', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="textarea" id="cf7ra_field_mappings_desc_buyers_read" name="cf7ra_field_mappings_description_buyers_read" value="' . esc_attr($description_buyers_read) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Company Url', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="url" id="cf7ra_field_mappings_company_url" name="cf7ra_field_mappings_company_url" value="' . esc_attr($company_url) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Virtual Tour Url', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="url" id="cf7ra_field_mappings_virtual_url" name="cf7ra_field_mappings_virtual_tour_url" value="' . esc_attr($virtual_tour_url) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Flyer PDF Url', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="url" id="cf7ra_field_mappings_flyer_pdf" name="cf7ra_field_mappings_flyer_pdf_url" value="' . esc_attr($flyer_pdf_url) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Street Address', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_street_add" name="cf7ra_field_mappings_street_address" value="' . esc_attr($street_address) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Street Address One', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_street_add_1" name="cf7ra_field_mappings_street_address_1" value="' . esc_attr($street_address_1) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Address Zip', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_add_zip" name="cf7ra_field_mappings_address_sip" value="' . esc_attr($address_sip) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Address City', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_add_city" name="cf7ra_field_mappings_address_city" value="' . esc_attr($address_city) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Address State', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>  
         <select id="cf7ra_field_mappings_address_state" name="cf7ra_field_mappings_address_state">
                                    <option value="--">Select a State/Province</option>
                                    <option value="Alabama" ' . selected($address_state, 'Alabama', false) . '>Alabama</option>
                                    <option value="Alaska" ' . selected($address_state, 'Alaska', false) . '>Alaska</option>
                                    <option value="Alberta" ' . selected($address_state, 'Alberta', false) . '>Alberta</option>
                                    <option value="Arizona" ' . selected($address_state, 'Arizona', false) . '>Arizona</option>
                                    <option value="Arkansas" ' . selected($address_state, 'Arkansas', false) . '>Arkansas</option>
                                    <option value="British Columbia" ' . selected($address_state, 'British Columbia', false) . '>British Columbia</option>
                                    <option value="California" ' . selected($address_state, 'California', false) . '>California</option>
                                    <option value="Colorado" ' . selected($address_state, 'Colorado', false) . '>Colorado</option>
                                    <option value="Connecticut" ' . selected($address_state, 'Connecticut', false) . '>Connecticut</option>
                                    <option value="Delaware" ' . selected($address_state, 'Delaware', false) . '>Delaware</option>
                                    <option value="Florida" ' . selected($address_state, 'Florida', false) . '>Florida</option>
                                    <option value="Georgia" ' . selected($address_state, 'Georgia', false) . '>Georgia</option>
                                    <option value="Hawaii" ' . selected($address_state, 'Hawaii', false) . '>Hawaii</option>
                                    <option value="Idaho" ' . selected($address_state, 'Idaho', false) . '>Idaho</option>
                                    <option value="Illinois" ' . selected($address_state, 'Illinois', false) . '>Illinois</option>
                                    <option value="Indiana" ' . selected($address_state, 'Indiana', false) . '>Indiana</option>
                                    <option value="Iowa" ' . selected($address_state, 'Iowa', false) . '>Iowa</option>
                                    <option value="Kansas" ' . selected($address_state, 'Kansas', false) . '>Kansas</option>
                                    <option value="Kentucky" ' . selected($address_state, 'Kentucky', false) . '>Kentucky</option>
                                    <option value="Louisiana" ' . selected($address_state, 'Louisiana', false) . '>Louisiana</option>
                                    <option value="Maine" ' . selected($address_state, 'Maine', false) . '>Maine</option>
                                    <option value="Manitoba" ' . selected($address_state, 'Manitoba', false) . '>Manitoba</option>
                                    <option value="Maryland" ' . selected($address_state, 'Maryland', false) . '>Maryland</option>
                                    <option value="Massachusetts" ' . selected($address_state, 'Massachusetts', false) . '>Massachusetts</option>
                                    <option value="Michigan" ' . selected($address_state, 'Michigan', false) . '>Michigan</option>
                                    <option value="Minnesota" ' . selected($address_state, 'Minnesota', false) . '>Minnesota</option>
                                    <option value="Mississippi" ' . selected($address_state, 'Mississippi', false) . '>Mississippi</option>
                                    <option value="Missouri" ' . selected($address_state, 'Missouri', false) . '>Missouri</option>
                                    <option value="Montana" ' . selected($address_state, 'Montana', false) . '>Montana</option>
                                    <option value="Nebraska" ' . selected($address_state, 'Nebraska', false) . '>Nebraska</option>
                                    <option value="Nevada" ' . selected($address_state, 'Nevada', false) . '>Nevada</option>
                                    <option value="New Brunswick" ' . selected($address_state, 'New Brunswick', false) . '>New Brunswick</option>
                                    <option value="Newfoundland and Labrador" ' . selected($address_state, 'Newfoundland and Labrador', false) . '>Newfoundland and Labrador</option>
                                    <option value="New Hampshire" ' . selected($address_state, 'New Hampshire', false) . '>New Hampshire</option>
                                    <option value="New Jersey" ' . selected($address_state, 'New Jersey', false) . '>New Jersey</option>
                                    <option value="New Mexico" ' . selected($address_state, 'New Mexico', false) . '>New Mexico</option>
                                    <option value="NNew YorkY" ' . selected($address_state, 'New York', false) . '>New York</option>
                                    <option value="North Carolina" ' . selected($address_state, 'North Carolina', false) . '>North Carolina</option>
                                    <option value="North Dakota" ' . selected($address_state, 'North Dakota', false) . '>North Dakota</option>
                                    <option value="Nova Scotia" ' . selected($address_state, 'Nova Scotia', false) . '>Nova Scotia</option>
                                    <option value="Ohio" ' . selected($address_state, 'Ohio', false) . '>Ohio</option>
                                    <option value="Oklahoma" ' . selected($address_state, 'Oklahoma', false) . '>Oklahoma</option>
                                    <option value="Ontario" ' . selected($address_state, 'Ontario', false) . '>Ontario</option>
                                    <option value="Oregon" ' . selected($address_state, 'Oregon', false) . '>Oregon</option>
                                    <option value="Pennsylvania" ' . selected($address_state, 'Pennsylvania', false) . '>Pennsylvania</option>
                                    <option value="Prince Edward Island" ' . selected($address_state, 'Prince Edward Island', false) . '>Prince Edward Island</option>
                                    <option value="Quebec" ' . selected($address_state, 'Quebec', false) . '>Quebec</option>
                                    <option value="Saskatchewan" ' . selected($address_state, 'Saskatchewan', false) . '>Saskatchewan</option>
                                    <option value="Rhode Island" ' . selected($address_state, 'Rhode Island', false) . '>Rhode Island</option>
                                    <option value="South Carolina" ' . selected($address_state, 'South Carolina', false) . '>South Carolina</option>
                                    <option value="South Dakota" ' . selected($address_state, 'South Dakota', false) . '>South Dakota</option>
                                    <option value="Tennessee" ' . selected($address_state, 'Tennessee', false) . '>Tennessee</option>
                                    <option value="Texas" ' . selected($address_state, 'Texas', false) . '>Texas</option>
                                    <option value="Utah" ' . selected($address_state, 'Utah', false) . '>Utah</option>
                                    <option value="Vermont" ' . selected($address_state, 'Vermont', false) . '>Vermont</option>
                                    <option value="Virginia" ' . selected($address_state, 'Virginia', false) . '>Virginia</option>
                                    <option value="Washington" ' . selected($address_state, 'Washington', false) . '>Washington</option>
                                    <option value="Washington, DC" ' . selected($address_state, 'Washington, DC', false) . '>Washington, DC</option>
                                    <option value="West Virginia" ' . selected($address_state, 'West Virginia', false) . '>West Virginia</option>
                                    <option value="Wisconsin" ' . selected($address_state, 'Wisconsin', false) . '>Wisconsin</option>
                                    <option value="Wyoming" ' . selected($address_state, 'Wyoming', false) . '>Wyoming</option>
                                 </select>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Address Country', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> 
        <select id="cf7ra_field_mappings_add_country" name="cf7ra_field_mappings_address_country">
                                     <option value="--" ' . selected($address_country, "--", false) . '>Select a Country</option>
    <option value="United States" ' . selected($address_country, "United States", false) . '>United States</option>
    <option value="Afghanistan" ' . selected($address_country, "Afghanistan", false) . '>Afghanistan</option>
    <option value="Aland Islands" ' . selected($address_country, "Aland Islands", false) . '>Aland Islands</option>
    <option value="Albania" ' . selected($address_country, "Albania", false) . '>Albania</option>
    <option value="Algeria" ' . selected($address_country, "Algeria", false) . '>Algeria</option>
    <option value="American Samoa" ' . selected($address_country, "American Samoa", false) . '>American Samoa</option>
    <option value="Andorra" ' . selected($address_country, "Andorra", false) . '>Andorra</option>
    <option value="Angola" ' . selected($address_country, "Angola", false) . '>Angola</option>
    <option value="Anguilla" ' . selected($address_country, "Anguilla", false) . '>Anguilla</option>
    <option value="Antigua and Barbuda" ' . selected($address_country, "Antigua and Barbuda", false) . '>Antigua and Barbuda</option>
    <option value="Argentina" ' . selected($address_country, "Argentina", false) . '>Argentina</option>
    <option value="Armenia" ' . selected($address_country, "Armenia", false) . '>Armenia</option>
    <option value="Aruba" ' . selected($address_country, "Aruba", false) . '>Aruba</option>
    <option value="Australia" ' . selected($address_country, "Australia", false) . '>Australia</option>
    <option value="Austria" ' . selected($address_country, "Austria", false) . '>Austria</option>
    <option value="Azerbaijan" ' . selected($address_country, "Azerbaijan", false) . '>Azerbaijan</option>
    <option value="Bahamas" ' . selected($address_country, "Bahamas", false) . '>Bahamas</option>
    <option value="Bahrain" ' . selected($address_country, "Bahrain", false) . '>Bahrain</option>
    <option value="Bangladesh" ' . selected($address_country, "Bangladesh", false) . '>Bangladesh</option>
    <option value="Barbados" ' . selected($address_country, "Barbados", false) . '>Barbados</option>
    <option value="Belarus" ' . selected($address_country, "Belarus", false) . '>Belarus</option>
    <option value="Belgium" ' . selected($address_country, "Belgium", false) . '>Belgium</option>
    <option value="Belize" ' . selected($address_country, "Belize", false) . '>Belize</option>
    <option value="Benin" ' . selected($address_country, "Benin", false) . '>Benin</option>
                                   <option value="Bermuda" ' . selected($address_country, "Bermuda", false) . '>Bermuda</option>
    <option value="Bhutan" ' . selected($address_country, "Bhutan", false) . '>Bhutan</option>
    <option value="Bolivia" ' . selected($address_country, "Bolivia", false) . '>Bolivia</option>
    <option value="Bosnia and Herzegovina" ' . selected($address_country, "Bosnia and Herzegovina", false) . '>Bosnia and Herzegovina</option>
    <option value="Botswana" ' . selected($address_country, "Botswana", false) . '>Botswana</option>
    <option value="Bouvet Island" ' . selected($address_country, "Bouvet Island", false) . '>Bouvet Island</option>
    <option value="Brazil" ' . selected($address_country, "Brazil", false) . '>Brazil</option>
    <option value="British Indian Ocean Territory" ' . selected($address_country, "British Indian Ocean Territory", false) . '>British Indian Ocean Territory</option>
    <option value="British Virgin Islands" ' . selected($address_country, "British Virgin Islands", false) . '>British Virgin Islands</option>
    <option value="Brunei" ' . selected($address_country, "Brunei", false) . '>Brunei</option>
    <option value="Bulgaria" ' . selected($address_country, "Bulgaria", false) . '>Bulgaria</option>
    <option value="Burkina Faso" ' . selected($address_country, "Burkina Faso", false) . '>Burkina Faso</option>
    <option value="Burundi" ' . selected($address_country, "Burundi", false) . '>Burundi</option>
    <option value="Cambodia" ' . selected($address_country, "Cambodia", false) . '>Cambodia</option>
    <option value="Cameroon" ' . selected($address_country, "Cameroon", false) . '>Cameroon</option>
    <option value="Canada" ' . selected($address_country, "Canada", false) . '>Canada</option>
    <option value="Cape Verde" ' . selected($address_country, "Cape Verde", false) . '>Cape Verde</option>
    <option value="Cayman Islands" ' . selected($address_country, "Cayman Islands", false) . '>Cayman Islands</option>
    <option value="Central African Republic" ' . selected($address_country, "Central African Republic", false) . '>Central African Republic</option>
    <option value="Chad" ' . selected($address_country, "Chad", false) . '>Chad</option>
    <option value="Chile" ' . selected($address_country, "Chile", false) . '>Chile</option>
    <option value="China" ' . selected($address_country, "China", false) . '>China</option>
    <option value="Cocos Islands" ' . selected($address_country, "Cocos Islands", false) . '>Cocos Islands</option>
    <option value="Colombia" ' . selected($address_country, "Colombia", false) . '>Colombia</option>
    <option value="Comoros" ' . selected($address_country, "Comoros", false) . '>Comoros</option>
    <option value="Cook Islands" ' . selected($address_country, "Cook Islands", false) . '>Cook Islands</option>
    <option value="Costa Rica" ' . selected($address_country, "Costa Rica", false) . '>Costa Rica</option>
    <option value="Cote D Ivoire" ' . selected($address_country, "Cote D Ivoire", false) . '>Cote DIvoire</option>
    <option value="Croatia" ' . selected($address_country, "Croatia", false) . '>Croatia</option>
    <option value="Cuba" ' . selected($address_country, "Cuba", false) . '>Cuba</option>
    <option value="Cyprus" ' . selected($address_country, "Cyprus", false) . '>Cyprus</option>
    <option value="Czech Republic" ' . selected($address_country, "Czech Republic", false) . '>Czech Republic</option>
                                   <option value="Democratic Republic of the Congo" ' . selected($address_country, "Democratic Republic of the Congo", false) . '>Democratic Republic of the Congo</option>
<option value="Denmark" ' . selected($address_country, "Denmark", false) . '>Denmark</option>
<option value="Djibouti" ' . selected($address_country, "Djibouti", false) . '>Djibouti</option>
<option value="Dominica" ' . selected($address_country, "Dominica", false) . '>Dominica</option>
<option value="Dominican Republic" ' . selected($address_country, "Dominican Republic", false) . '>Dominican Republic</option>
<option value="East Timor" ' . selected($address_country, "East Timor", false) . '>East Timor</option>
<option value="Ecuador" ' . selected($address_country, "Ecuador", false) . '>Ecuador</option>
<option value="Egypt" ' . selected($address_country, "Egypt", false) . '>Egypt</option>
<option value="El Salvador" ' . selected($address_country, "El Salvador", false) . '>El Salvador</option>
<option value="Equatorial Guinea" ' . selected($address_country, "Equatorial Guinea", false) . '>Equatorial Guinea</option>
<option value="Eritrea" ' . selected($address_country, "Eritrea", false) . '>Eritrea</option>
<option value="Estonia" ' . selected($address_country, "Estonia", false) . '>Estonia</option>
<option value="Ethiopia" ' . selected($address_country, "Ethiopia", false) . '>Ethiopia</option>
<option value="Falkland Islands" ' . selected($address_country, "Falkland Islands", false) . '>Falkland Islands</option>
<option value="Faroe Islands" ' . selected($address_country, "Faroe Islands", false) . '>Faroe Islands</option>
<option value="Federated States of Micronesia" ' . selected($address_country, "Federated States of Micronesia", false) . '>Federated States of Micronesia</option>
<option value="Fiji" ' . selected($address_country, "Fiji", false) . '>Fiji</option>
<option value="Finland" ' . selected($address_country, "Finland", false) . '>Finland</option>
<option value="France" ' . selected($address_country, "France", false) . '>France</option>
<option value="French Guiana" ' . selected($address_country, "French Guiana", false) . '>French Guiana</option>
<option value="French Polynesia" ' . selected($address_country, "French Polynesia", false) . '>French Polynesia</option>
<option value="French Southern Territories" ' . selected($address_country, "French Southern Territories", false) . '>French Southern Territories</option>
                                  
<option value="Gabon Republic" ' . selected($address_country, "Gabon Republic", false) . '>Gabon Republic</option>
<option value="Gambia" ' . selected($address_country, "Gambia", false) . '>Gambia</option>
<option value="Georgia" ' . selected($address_country, "Georgia", false) . '>Georgia</option>
<option value="Germany" ' . selected($address_country, "Germany", false) . '>Germany</option>
<option value="Ghana" ' . selected($address_country, "Ghana", false) . '>Ghana</option>
<option value="Gibraltar" ' . selected($address_country, "Gibraltar", false) . '>Gibraltar</option>
<option value="Greece" ' . selected($address_country, "Greece", false) . '>Greece</option>
<option value="Greenland" ' . selected($address_country, "Greenland", false) . '>Greenland</option>
<option value="Grenada" ' . selected($address_country, "Grenada", false) . '>Grenada</option>
<option value="Guadeloupe" ' . selected($address_country, "Guadeloupe", false) . '>Guadeloupe</option>
<option value="Guam" ' . selected($address_country, "Guam", false) . '>Guam</option>
<option value="Guatemala" ' . selected($address_country, "Guatemala", false) . '>Guatemala</option>
<option value="Guernsey" ' . selected($address_country, "Guernsey", false) . '>Guernsey</option>
<option value="Guinea" ' . selected($address_country, "Guinea", false) . '>Guinea</option>
<option value="Guinea Bissau" ' . selected($address_country, "Guinea Bissau", false) . '>Guinea Bissau</option>
<option value="Guyana" ' . selected($address_country, "Guyana", false) . '>Guyana</option>
<option value="Haiti" ' . selected($address_country, "Haiti", false) . '>Haiti</option>
<option value="Heard and McDonald Islands" ' . selected($address_country, "Heard and McDonald Islands", false) . '>Heard and McDonald Islands</option>
<option value="Honduras" ' . selected($address_country, "Honduras", false) . '>Honduras</option>
<option value="Hong Kong" ' . selected($address_country, "Hong Kong", false) . '>Hong Kong</option>
<option value="Hungary" ' . selected($address_country, "Hungary", false) . '>Hungary</option>
<option value="Iceland" ' . selected($address_country, "Iceland", false) . '>Iceland</option>
<option value="India" ' . selected($address_country, "India", false) . '>India</option>
<option value="Indonesia" ' . selected($address_country, "Indonesia", false) . '>Indonesia</option>
<option value="Iran" ' . selected($address_country, "Iran", false) . '>Iran</option>
<option value="Iraq" ' . selected($address_country, "Iraq", false) . '>Iraq</option>
<option value="Ireland" ' . selected($address_country, "Ireland", false) . '>Ireland</option>
<option value="Isle of Man" ' . selected($address_country, "Isle of Man", false) . '>Isle of Man</option>
<option value="Israel" ' . selected($address_country, "Israel", false) . '>Israel</option>
<option value="Italy" ' . selected($address_country, "Italy", false) . '>Italy</option>
<option value="Jamaica" ' . selected($address_country, "Jamaica", false) . '>Jamaica</option>
<option value="Japan" ' . selected($address_country, "Japan", false) . '>Japan</option>
<option value="Jersey" ' . selected($address_country, "Jersey", false) . '>Jersey</option>
<option value="Jordan" ' . selected($address_country, "Jordan", false) . '>Jordan</option>
<option value="Kazakhstan" ' . selected($address_country, "Kazakhstan", false) . '>Kazakhstan</option>
<option value="Kenya" ' . selected($address_country, "Kenya", false) . '>Kenya</option>
<option value="Kiribati" ' . selected($address_country, "Kiribati", false) . '>Kiribati</option>
<option value="Kuwait" ' . selected($address_country, "Kuwait", false) . '>Kuwait</option>
<option value="Kyrgyzstan" ' . selected($address_country, "Kyrgyzstan", false) . '>Kyrgyzstan</option>
<option value="Laos" ' . selected($address_country, "Laos", false) . '>Laos</option>
<option value="Latvia" ' . selected($address_country, "Latvia", false) . '>Latvia</option>
<option value="Lebanon" ' . selected($address_country, "Lebanon", false) . '>Lebanon</option>
<option value="Lesotho" ' . selected($address_country, "Lesotho", false) . '>Lesotho</option>
<option value="Liberia" ' . selected($address_country, "Liberia", false) . '>Liberia</option>
<option value="Libya" ' . selected($address_country, "Libya", false) . '>Libya</option>
<option value="Liechtenstein" ' . selected($address_country, "Liechtenstein", false) . '>Liechtenstein</option>
<option value="Lithuania" ' . selected($address_country, "Lithuania", false) . '>Lithuania</option>
<option value="Luxembourg" ' . selected($address_country, "Luxembourg", false) . '>Luxembourg</option>
<option value="Macau" ' . selected($address_country, "Macau", false) . '>Macau</option>
<option value="Macedonia" ' . selected($address_country, "Macedonia", false) . '>Macedonia</option>
<option value="Madagascar" ' . selected($address_country, "Madagascar", false) . '>Madagascar</option>
<option value="Malawi" ' . selected($address_country, "Malawi", false) . '>Malawi</option>
<option value="Malaysia" ' . selected($address_country, "Malaysia", false) . '>Malaysia</option>
<option value="Maldives" ' . selected($address_country, "Maldives", false) . '>Maldives</option>
<option value="Mali" ' . selected($address_country, "Mali", false) . '>Mali</option>
<option value="Malta" ' . selected($address_country, "Malta", false) . '>Malta</option>
<option value="Marshall Islands" ' . selected($address_country, "Marshall Islands", false) . '>Marshall Islands</option>
<option value="Martinique" ' . selected($address_country, "Martinique", false) . '>Martinique</option>
<option value="Mauritania" ' . selected($address_country, "Mauritania", false) . '>Mauritania</option>
<option value="Mauritius" ' . selected($address_country, "Mauritius", false) . '>Mauritius</option>
<option value="Mayotte" ' . selected($address_country, "Mayotte", false) . '>Mayotte</option>
<option value="Mexico" ' . selected($address_country, "Mexico", false) . '>Mexico</option>
<option value="Moldova" ' . selected($address_country, "Moldova", false) . '>Moldova</option>
<option value="Monaco" ' . selected($address_country, "Monaco", false) . '>Monaco</option>
<option value="Mongolia" ' . selected($address_country, "Mongolia", false) . '>Mongolia</option>
<option value="Montenegro" ' . selected($address_country, "Montenegro", false) . '>Montenegro</option>
<option value="Montserrat" ' . selected($address_country, "Montserrat", false) . '>Montserrat</option>
<option value="Morocco" ' . selected($address_country, "Morocco", false) . '>Morocco</option>
<option value="Mozambique" ' . selected($address_country, "Mozambique", false) . '>Mozambique</option>
<option value="Myanmar" ' . selected($address_country, "Myanmar", false) . '>Myanmar</option>
<option value="Namibia" ' . selected($address_country, "Namibia", false) . '>Namibia</option>
<option value="Nauru" ' . selected($address_country, "Nauru", false) . '>Nauru</option>
<option value="Nepal" ' . selected($address_country, "Nepal", false) . '>Nepal</option>
<option value="Netherlands" ' . selected($address_country, "Netherlands", false) . '>Netherlands</option>
<option value="Norway" ' . selected($address_country, "Norway", false) . '>Norway</option>
                                    <option value="Oman" ' . selected($address_country, 'Oman', false) . '>Oman</option>
<option value="Pakistan" ' . selected($address_country, 'Pakistan', false) . '>Pakistan</option>
<option value="Palau" ' . selected($address_country, 'Palau', false) . '>Palau</option>
<option value="Palestine" ' . selected($address_country, 'Palestine', false) . '>Palestine</option>
<option value="Panama" ' . selected($address_country, 'Panama', false) . '>Panama</option>
<option value="Papua New Guinea" ' . selected($address_country, 'Papua New Guinea', false) . '>Papua New Guinea</option>
<option value="Paraguay" ' . selected($address_country, 'Paraguay', false) . '>Paraguay</option>
<option value="Peru" ' . selected($address_country, 'Peru', false) . '>Peru</option>
<option value="Philippines" ' . selected($address_country, 'Philippines', false) . '>Philippines</option>
<option value="Pitcairn Islands" ' . selected($address_country, 'Pitcairn Islands', false) . '>Pitcairn Islands</option>
<option value="Poland" ' . selected($address_country, 'Poland', false) . '>Poland</option>
<option value="Portugal" ' . selected($address_country, 'Portugal', false) . '>Portugal</option>
<option value="Puerto Rico" ' . selected($address_country, 'Puerto Rico', false) . '>Puerto Rico</option>
<option value="Qatar" ' . selected($address_country, 'Qatar', false) . '>Qatar</option>
<option value="Republic of the Congo" ' . selected($address_country, 'Republic of the Congo', false) . '>Republic of the Congo</option>
<option value="Reunion" ' . selected($address_country, 'Reunion', false) . '>Reunion</option>
<option value="Romania" ' . selected($address_country, 'Romania', false) . '>Romania</option>
<option value="Russia" ' . selected($address_country, 'Russia', false) . '>Russia</option>
<option value="Rwanda" ' . selected($address_country, 'Rwanda', false) . '>Rwanda</option>
<option value="Saint Vincent and the Grenadines" ' . selected($address_country, 'Saint Vincent and the Grenadines', false) . '>Saint Vincent and the Grenadines</option>
<option value="Samoa" ' . selected($address_country, 'Samoa', false) . '>Samoa</option>
<option value="San Marino" ' . selected($address_country, 'San Marino', false) . '>San Marino</option>
<option value="Sao Tome and Príncipe" ' . selected($address_country, 'Sao Tome and Príncipe', false) . '>Sao Tome and Príncipe</option>
<option value="Saudi Arabia" ' . selected($address_country, 'Saudi Arabia', false) . '>Saudi Arabia</option>
<option value="Senegal" ' . selected($address_country, 'Senegal', false) . '>Senegal</option>
<option value="Serbia" ' . selected($address_country, 'Serbia', false) . '>Serbia</option>
<option value="Serbia and Montenegro" ' . selected($address_country, 'Serbia and Montenegro', false) . '>Serbia and Montenegro</option>
<option value="Seychelles" ' . selected($address_country, 'Seychelles', false) . '>Seychelles</option>
<option value="Sierra Leone" ' . selected($address_country, 'Sierra Leone', false) . '>Sierra Leone</option>
<option value="Singapore" ' . selected($address_country, 'Singapore', false) . '>Singapore</option>
<option value="Slovakia" ' . selected($address_country, 'Slovakia', false) . '>Slovakia</option>
<option value="Slovenia" ' . selected($address_country, 'Slovenia', false) . '>Slovenia</option>
<option value="Solomon Islands" ' . selected($address_country, 'Solomon Islands', false) . '>Solomon Islands</option>
<option value="Somalia" ' . selected($address_country, 'Somalia', false) . '>Somalia</option>
<option value="South Africa" ' . selected($address_country, 'South Africa', false) . '>South Africa</option>
<option value="South Korea" ' . selected($address_country, 'South Korea', false) . '>South Korea</option>
<option value="Spain" ' . selected($address_country, 'Spain', false) . '>Spain</option>
<option value="Sri Lanka" ' . selected($address_country, 'Sri Lanka', false) . '>Sri Lanka</option>
<option value="St. Helena" ' . selected($address_country, 'St. Helena', false) . '>St. Helena</option>
<option value="St. Kitts and Nevis" ' . selected($address_country, 'St. Kitts and Nevis', false) . '>St. Kitts and Nevis</option>
<option value="St. Lucia" ' . selected($address_country, 'St. Lucia', false) . '>St. Lucia</option>
<option value="St. Pierre and Miquelon" ' . selected($address_country, 'St. Pierre and Miquelon', false) . '>St. Pierre and Miquelon</option>
<option value="Sudan" ' . selected($address_country, 'Sudan', false) . '>Sudan</option>
<option value="Suriname" ' . selected($address_country, 'Suriname', false) . '>Suriname</option>
<option value="Svalbard and Jan Mayen Islands" ' . selected($address_country, 'Svalbard and Jan Mayen Islands', false) . '>Svalbard and Jan Mayen Islands</option>
<option value="Swaziland" ' . selected($address_country, 'Swaziland', false) . '>Swaziland</option>
<option value="Sweden" ' . selected($address_country, 'Sweden', false) . '>Sweden</option>
<option value="Switzerland" ' . selected($address_country, 'Switzerland', false) . '>Switzerland</option>
<option value="Syria" ' . selected($address_country, 'Syria', false) . '>Syria</option>
<option value="Taiwan" ' . selected($address_country, 'Taiwan', false) . '>Taiwan</option>
<option value="Tajikistan" ' . selected($address_country, 'Tajikistan', false) . '>Tajikistan</option>
<option value="Tanzania" ' . selected($address_country, 'Tanzania', false) . '>Tanzania</option>
<option value="Thailand" ' . selected($address_country, 'Thailand', false) . '>Thailand</option>
<option value="Turkey" ' . selected($address_country, 'Turkey', false) . '>Turkey</option>
<option value="Ukraine" ' . selected($address_country, 'Ukraine', false) . '>Ukraine</option>
<option value="United Arab Emirates" ' . selected($address_country, 'United Arab Emirates', false) . '>United Arab Emirates</option>
<option value="United Kingdom" ' . selected($address_country, 'United Kingdom', false) . '>United Kingdom</option>
<option value="Uruguay" ' . selected($address_country, 'Uruguay', false) . '>Uruguay</option>
<option value="Uzbekistan" ' . selected($address_country, 'Uzbekistan', false) . '>Uzbekistan</option>
<option value="Vietnam" ' . selected($address_country, 'Vietnam', false) . '>Vietnam</option>
<option value="Yemen" ' . selected($address_country, 'Yemen', false) . '>Yemen</option>
<option value="Zambia" ' . selected($address_country, 'Zambia', false) . '>Zambia</option>
<option value="Zimbabwe" ' . selected($address_country, 'Zimbabwe', false) . '>Zimbabwe</option>
                                 </select>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Address Display', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>  
          <select id="cf7ra_field_mappings_add_display" name="cf7ra_field_mappings_address_display">
                                    <option value="">Select how to display the address...</option>
                                    <option value="Show Down to Street Location"  ' . selected($address_display, 'Show Down to Street Location', false) . '>Show Down to Street Location</option>
                                    <option value="Show General City Area" ' . selected($address_display, 'Show General City Area', false) . '>Show General City Area</option>
                                    <option value="Show Only State" ' . selected($address_display, 'Show Only State', false) . '>Show Only State</option>
                                    <option value="Show Only Region" ' . selected($address_display, 'Show Only Region', false) . '>Show Only Region</option>
                                    <option value="Show Only Country" ' . selected($address_display, 'Show Only Country', false) . '>Show Only Country</option>
                                    <option value="Do not show the address" ' . selected($address_display, 'Do not show the address', false) . '>Do not show the address</option>
                                 </select>
        
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('General Location', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_general_loc" name="cf7ra_field_mappings_general_location" value="' . esc_attr($general_location) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Listing no Type', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    
         <input id="randomlyGenerateNumber" name="cf7ra_field_mappings_listing_number_type" type="radio" value="Randomly Generate the Listing Number" ' . checked($listing_number_type, 'Randomly Generate the Listing Number', false) . '>
         <label for="randomlyGenerateNumber">Randomly Generate the Listing Number</label>
         <input id="customNumber" name="cf7ra_field_mappings_listing_number_type" type="radio" value="Custom Listing Number" ' . checked($listing_number_type, 'Custom Listing Number', false) . '>
         <label for="randomlyGenerateNumber">Custom Listing Number</label>                  
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Custom Listing No', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_custom_list_no" name="cf7ra_field_mappings_custom_listing_number" value="' . esc_attr($custom_listing_number) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('First Name', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_first_name" name="cf7ra_field_mappings_custom_first_name" value="' . esc_attr($first_name) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Display Contacts First Name', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> <input id="displayFirstName" type="checkbox" name="cf7ra_field_mappings_first_name" value="Display Contacts First Name" ' .  checked($firstname_checkbox, 'Display Contacts First Name') . '  />
           <label for="displayFirstName">Display Contacts First Name</label></td>' .
        '</tr>';


    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Last Name', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_last_name" name="cf7ra_field_mappings_custom_last_name" value="' . esc_attr($last_name) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Display Contacts Last Name', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> <input id="displaylastName" type="checkbox" name="cf7ra_field_mappings_last_name" value="Display Contacts Last Name" ' .  checked($lastname_checkbox, 'Display Contacts Last Name') . '  />
           <label for="displaylastName">Display Contacts Last Name</label></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Street Address', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_street_add" name="cf7ra_field_mappings_custom_seller_street_address" value="' . esc_attr($seller_street_address) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Same as property', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> <input id="cf7ra_field_mappings_same_property" type="checkbox" name="cf7ra_field_mappings_same_property" value="Same as property" ' .  checked($same_property_checkbox, 'Same as property') . '  />
           <label for="same_property">Same as property</label></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Display Contacts Full Address', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> <input id="cf7ra_field_mappings_street_address" type="checkbox" name="cf7ra_field_mappings_street_address" value="Display Contacts Full Address" ' .  checked($street_address_checkbox, 'Display Contacts Full Address') . '  />
           <label for="displaylastName">Display Contacts Full Address</label></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Street Address One', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_street_add_1" name="cf7ra_field_mappings_custom_seller_street_address1" value="' . esc_attr($seller_street_address1) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Zip', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_zip" name="cf7ra_field_mappings_custom_seller_zip" value="' . esc_attr($seller_zip) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller City', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_city" name="cf7ra_field_mappings_custom_seller_city" value="' . esc_attr($seller_city) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller State', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <select id="cf7ra_field_mappings_custom_seller_state" name="cf7ra_field_mappings_custom_seller_state">
                                    <option value="--">Select a State/Province</option>
                                    <option value="Alabama" ' . selected($seller_state, 'Alabama', false) . '>Alabama</option>
                                    <option value="Alaska" ' . selected($seller_state, 'Alaska', false) . '>Alaska</option>
                                    <option value="Alberta" ' . selected($seller_state, 'Alberta', false) . '>Alberta</option>
                                    <option value="Arizona" ' . selected($seller_state, 'Arizona', false) . '>Arizona</option>
                                    <option value="Arkansas" ' . selected($seller_state, 'Arkansas', false) . '>Arkansas</option>
                                    <option value="British Columbia" ' . selected($seller_state, 'British Columbia', false) . '>British Columbia</option>
                                    <option value="California" ' . selected($seller_state, 'California', false) . '>California</option>
                                    <option value="Colorado" ' . selected($seller_state, 'Colorado', false) . '>Colorado</option>
                                    <option value="Connecticut" ' . selected($seller_state, 'Connecticut', false) . '>Connecticut</option>
                                    <option value="Delaware" ' . selected($seller_state, 'Delaware', false) . '>Delaware</option>
                                    <option value="Florida" ' . selected($seller_state, 'Florida', false) . '>Florida</option>
                                    <option value="Georgia" ' . selected($seller_state, 'Georgia', false) . '>Georgia</option>
                                    <option value="Hawaii" ' . selected($seller_state, 'Hawaii', false) . '>Hawaii</option>
                                    <option value="Idaho" ' . selected($seller_state, 'Idaho', false) . '>Idaho</option>
                                    <option value="Illinois" ' . selected($seller_state, 'Illinois', false) . '>Illinois</option>
                                    <option value="Indiana" ' . selected($seller_state, 'Indiana', false) . '>Indiana</option>
                                    <option value="Iowa" ' . selected($seller_state, 'Iowa', false) . '>Iowa</option>
                                    <option value="Kansas" ' . selected($seller_state, 'Kansas', false) . '>Kansas</option>
                                    <option value="Kentucky" ' . selected($seller_state, 'Kentucky', false) . '>Kentucky</option>
                                    <option value="Louisiana" ' . selected($seller_state, 'Louisiana', false) . '>Louisiana</option>
                                    <option value="Maine" ' . selected($seller_state, 'Maine', false) . '>Maine</option>
                                    <option value="Manitoba" ' . selected($seller_state, 'Manitoba', false) . '>Manitoba</option>
                                    <option value="Maryland" ' . selected($seller_state, 'Maryland', false) . '>Maryland</option>
                                    <option value="Massachusetts" ' . selected($seller_state, 'Massachusetts', false) . '>Massachusetts</option>
                                    <option value="Michigan" ' . selected($seller_state, 'Michigan', false) . '>Michigan</option>
                                    <option value="Minnesota" ' . selected($seller_state, 'Minnesota', false) . '>Minnesota</option>
                                    <option value="Mississippi" ' . selected($seller_state, 'Mississippi', false) . '>Mississippi</option>
                                    <option value="Missouri" ' . selected($seller_state, 'Missouri', false) . '>Missouri</option>
                                    <option value="Montana" ' . selected($seller_state, 'Montana', false) . '>Montana</option>
                                    <option value="Nebraska" ' . selected($seller_state, 'Nebraska', false) . '>Nebraska</option>
                                    <option value="Nevada" ' . selected($seller_state, 'Nevada', false) . '>Nevada</option>
                                    <option value="New Brunswick" ' . selected($seller_state, 'New Brunswick', false) . '>New Brunswick</option>
                                    <option value="Newfoundland and Labrador" ' . selected($seller_state, 'Newfoundland and Labrador', false) . '>Newfoundland and Labrador</option>
                                    <option value="New Hampshire" ' . selected($seller_state, 'New Hampshire', false) . '>New Hampshire</option>
                                    <option value="New Jersey" ' . selected($seller_state, 'New Jersey', false) . '>New Jersey</option>
                                    <option value="New Mexico" ' . selected($seller_state, 'New Mexico', false) . '>New Mexico</option>
                                    <option value="NNew YorkY" ' . selected($seller_state, 'New York', false) . '>New York</option>
                                    <option value="North Carolina" ' . selected($seller_state, 'North Carolina', false) . '>North Carolina</option>
                                    <option value="North Dakota" ' . selected($seller_state, 'North Dakota', false) . '>North Dakota</option>
                                    <option value="Nova Scotia" ' . selected($seller_state, 'Nova Scotia', false) . '>Nova Scotia</option>
                                    <option value="Ohio" ' . selected($seller_state, 'Ohio', false) . '>Ohio</option>
                                    <option value="Oklahoma" ' . selected($seller_state, 'Oklahoma', false) . '>Oklahoma</option>
                                    <option value="Ontario" ' . selected($seller_state, 'Ontario', false) . '>Ontario</option>
                                    <option value="Oregon" ' . selected($seller_state, 'Oregon', false) . '>Oregon</option>
                                    <option value="Pennsylvania" ' . selected($seller_state, 'Pennsylvania', false) . '>Pennsylvania</option>
                                    <option value="Prince Edward Island" ' . selected($seller_state, 'Prince Edward Island', false) . '>Prince Edward Island</option>
                                    <option value="Quebec" ' . selected($seller_state, 'Quebec', false) . '>Quebec</option>
                                    <option value="Saskatchewan" ' . selected($seller_state, 'Saskatchewan', false) . '>Saskatchewan</option>
                                    <option value="Rhode Island" ' . selected($seller_state, 'Rhode Island', false) . '>Rhode Island</option>
                                    <option value="South Carolina" ' . selected($seller_state, 'South Carolina', false) . '>South Carolina</option>
                                    <option value="South Dakota" ' . selected($seller_state, 'South Dakota', false) . '>South Dakota</option>
                                    <option value="Tennessee" ' . selected($seller_state, 'Tennessee', false) . '>Tennessee</option>
                                    <option value="Texas" ' . selected($seller_state, 'Texas', false) . '>Texas</option>
                                    <option value="Utah" ' . selected($seller_state, 'Utah', false) . '>Utah</option>
                                    <option value="Vermont" ' . selected($seller_state, 'Vermont', false) . '>Vermont</option>
                                    <option value="Virginia" ' . selected($seller_state, 'Virginia', false) . '>Virginia</option>
                                    <option value="Washington" ' . selected($seller_state, 'Washington', false) . '>Washington</option>
                                    <option value="Washington, DC" ' . selected($seller_state, 'Washington, DC', false) . '>Washington, DC</option>
                                    <option value="West Virginia" ' . selected($seller_state, 'West Virginia', false) . '>West Virginia</option>
                                    <option value="Wisconsin" ' . selected($seller_state, 'Wisconsin', false) . '>Wisconsin</option>
                                    <option value="Wyoming" ' . selected($seller_state, 'Wyoming', false) . '>Wyoming</option>
                                 </select></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Primary Phone', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_pri_phone" name="cf7ra_field_mappings_custom_seller_primary_phone" value="' . esc_attr($seller_primary_phone) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Display Primary Phone', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> <input id="cf7ra_field_mappings_primary_phone" type="checkbox" name="cf7ra_field_mappings_primary_phone" value="Display Primary Phone" ' .  checked($primary_phone_checkbox, 'Display Primary Phone') . '  />
           <label for="displaylastName">Display Primary Phone</label></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Secondary Phone', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_sec_phone" name="cf7ra_field_mappings_custom_seller_sec_phone" value="' . esc_attr($seller_sec_phone) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Display Alternate Phone', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td> <input id="cf7ra_field_mappings_sec_phone" type="checkbox" name="cf7ra_field_mappings_sec_phone" value="Display Alternate Phone" ' .  checked($sec_phone_checkbox, 'Display Alternate Phone') . '  />
           <label for="displaylastName">Display Alternate Phone</label></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Company Url', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="url" id="cf7ra_field_mappings_seller_comp_url" name="cf7ra_field_mappings_custom_seller_company_url" value="' . esc_attr($seller_company_url) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Calling Time', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>   
         <select id="cf7ra_field_mappings_seller_call_time" name="cf7ra_field_mappings_custom_seller_calling_time">
                                    <option value="Early Morning" ' . selected($seller_calling_time, 'Early Morning', false) . '>Early Morning</option>
                                    <option value="Brunch" ' . selected($seller_calling_time, 'Brunch', false) . '>Brunch</option>
                                    <option value="Noon-ish" ' . selected($seller_calling_time, 'Noon-ish', false) . '>Noon-ish</option>
                                    <option value="Afternoon" ' . selected($seller_calling_time, 'Afternoon', false) . '>Afternoon</option>
                                    <option value="Evening" ' . selected($seller_calling_time, 'Evening', false) . '>Evening</option>
                                    <option value="Night" ' . selected($seller_calling_time, 'Night', false) . '>Night</option>
                                    <option value="Business Hours" ' . selected($seller_calling_time, 'Business Jours', false) . '>Business Hours</option>
                                    <option value="Anytime" ' . selected($seller_calling_time, 'Anytime', false) . '>Anytime</option>
                                    <option value="Unavailable" ' . selected($seller_calling_time, 'Unavailable', false) . '>Unavailable</option>
                                </select>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Email', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_email" name="cf7ra_field_mappings_custom_email_seller" value="' . esc_attr($email_seller) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Seller Retype Email', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_mappings_seller_retype_email" name="cf7ra_field_mappings_custom_seller_retrype_email" value="' . esc_attr($seller_retrype_email) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Are You a Realtor', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>   
         <select  id="cf7ra_field_mappings_retailer" name="cf7ra_field_mappings_custom_seller_is_realtor">
                                    <option value="Yes" ' . selected($seller_is_realtor, 'Yes', false) . '>Yes</option>
                                    <option value="No" ' . selected($seller_is_realtor, 'No', false) . '>No</option>
                                 </select>
        </td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Account Password', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_acc_pass" name="cf7ra_field_mappings_custom_account_password" value="' . esc_attr($account_password) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Account Verify Password', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>    <input type="text" id="cf7ra_field_acc_verify_pass" name="cf7ra_field_mappings_custom_account_verify_password" value="' . esc_attr($account_verify_password) . '" style="width:100%;" /></td>' .
        '</tr>';
    echo '<tr>
        <td colspan="2">
        <input type="hidden" name="action" value="cf7ra_save_meta_box">
         <input type="hidden" name="post_ID" value="' . $post_id . '">
            <input type="submit" name="cf7ra_save_meta_box" class="button-primary" value="Save Settings">
        </td>
    </tr>';

    echo '</table></form>';

    return ob_get_clean();
}

function cf7ra_handle_meta_box_submission()
{
    if (!isset($_POST['cf7ra_meta_box_nonce']) || !wp_verify_nonce($_POST['cf7ra_meta_box_nonce'], 'cf7ra_save_meta_box')) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_posts')) {
        return;
    }

    // Get Post ID
    $post_id = $_POST['post_ID'];

    if (isset($_POST['cf7ra_field_mappings_listing_plan'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_listing_plan', sanitize_text_field($_POST['cf7ra_field_mappings_listing_plan']));
    }

    if (isset($_POST['cf7ra_field_mappings_asking_price'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_asking_price', sanitize_text_field($_POST['cf7ra_field_mappings_asking_price']));
    }

    if (isset($_POST['cf7ra_field_mappings_asking_price_rent'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_asking_price_rent_lease', sanitize_text_field($_POST['cf7ra_field_mappings_asking_price_rent']));
    }

    if (isset($_POST['cf7ra_field_mappings_land_unit_type'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_land_unit_type', sanitize_text_field($_POST['cf7ra_field_mappings_land_unit_type']));
    }

    if (isset($_POST['cf7ra_field_mappings_total_acres'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_total_acres', sanitize_text_field($_POST['cf7ra_field_mappings_total_acres']));
    }

    if (isset($_POST['cf7ra_field_mappings_total_hectare'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_total_hectare', sanitize_text_field($_POST['cf7ra_field_mappings_total_hectare']));
    }

    if (isset($_POST['cf7ra_field_mappings_land_description'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_land_description', sanitize_textarea_field($_POST['cf7ra_field_mappings_land_description']));
    }

    if (isset($_POST['cf7ra_field_mappings_current_taxes'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_current_taxes', sanitize_text_field($_POST['cf7ra_field_mappings_current_taxes']));
    }

    if (isset($_POST['cf7ra_field_mappings_numbers_of_buildings'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_numbers_of_buildings', sanitize_text_field($_POST['cf7ra_field_mappings_numbers_of_buildings']));
    }

    if (isset($_POST['cf7ra_field_mappings_farm_capacity'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_farm_capacity', sanitize_text_field($_POST['cf7ra_field_mappings_farm_capacity']));
    }

    if (isset($_POST['cf7ra_field_mappings_capacity_type'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_capacity_type', sanitize_text_field($_POST['cf7ra_field_mappings_capacity_type']));
    }

    if (isset($_POST['cf7ra_field_mappings_house_info'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_house_info', sanitize_text_field($_POST['cf7ra_field_mappings_house_info']));
    }

    if (isset($_POST['cf7ra_field_mappings_num_of_bedrooms'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_num_of_bedrooms', sanitize_text_field($_POST['cf7ra_field_mappings_num_of_bedrooms']));
    }

    if (isset($_POST['cf7ra_field_mappings_num_of_bathrooms'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_num_of_bathrooms', sanitize_text_field($_POST['cf7ra_field_mappings_num_of_bathrooms']));
    }

    if (isset($_POST['cf7ra_field_mappings_sq_foot'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_sq_foot', sanitize_text_field($_POST['cf7ra_field_mappings_sq_foot']));
    }

    if (isset($_POST['cf7ra_field_mappings_year_built'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_year_built', sanitize_text_field($_POST['cf7ra_field_mappings_year_built']));
    }

    if (isset($_POST['cf7ra_field_mappings_description_buyers_read'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_description_buyers_read', sanitize_textarea_field($_POST['cf7ra_field_mappings_description_buyers_read']));
    }

    if (isset($_POST['cf7ra_field_mappings_company_url'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_company_url', esc_url_raw($_POST['cf7ra_field_mappings_company_url']));
    }

    if (isset($_POST['cf7ra_field_mappings_virtual_tour_url'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_virtual_tour_url', esc_url_raw($_POST['cf7ra_field_mappings_virtual_tour_url']));
    }

    if (isset($_POST['cf7ra_field_mappings_flyer_pdf_url'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_flyer_pdf_url', esc_url_raw($_POST['cf7ra_field_mappings_flyer_pdf_url']));
    }

    if (isset($_POST['cf7ra_field_mappings_street_address'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_street_address', sanitize_text_field($_POST['cf7ra_field_mappings_street_address']));
    }

    if (isset($_POST['cf7ra_field_mappings_street_address_1'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_street_address_1', sanitize_text_field($_POST['cf7ra_field_mappings_street_address_1']));
    }

    if (isset($_POST['cf7ra_field_mappings_address_city'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_address_city', sanitize_text_field($_POST['cf7ra_field_mappings_address_city']));
    }

    if (isset($_POST['cf7ra_field_mappings_address_state'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_address_state', sanitize_text_field($_POST['cf7ra_field_mappings_address_state']));
    }

    if (isset($_POST['cf7ra_field_mappings_address_country'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_address_country', sanitize_text_field($_POST['cf7ra_field_mappings_address_country']));
    }

    if (isset($_POST['cf7ra_field_mappings_address_display'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_address_display', sanitize_text_field($_POST['cf7ra_field_mappings_address_display']));
    }

    if (isset($_POST['cf7ra_field_mappings_general_location'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_general_location', sanitize_text_field($_POST['cf7ra_field_mappings_general_location']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_listing_number'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_listing_number', sanitize_text_field($_POST['cf7ra_field_mappings_custom_listing_number']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_first_name'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_first_name', sanitize_text_field($_POST['cf7ra_field_mappings_custom_first_name']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_last_name'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_last_name', sanitize_text_field($_POST['cf7ra_field_mappings_custom_last_name']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_street_address'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_street_address', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_street_address']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_street_address1'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_street_address1', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_street_address1']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_zip'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_zip', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_zip']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_city'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_city', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_city']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_state'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_state', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_state']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_seller_primary_phone'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_primary_phone', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_primary_phone']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_seller_sec_phone'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_sec_phone', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_sec_phone']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_company_url'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_company_url', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_company_url']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_calling_time'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_calling_time', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_calling_time']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_email_seller'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_email_seller', sanitize_text_field($_POST['cf7ra_field_mappings_custom_email_seller']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_seller_retrype_email'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_retrype_email', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_retrype_email']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_seller_is_realtor'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_seller_is_realtor', sanitize_text_field($_POST['cf7ra_field_mappings_custom_seller_is_realtor']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_account_password'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_account_password', sanitize_text_field($_POST['cf7ra_field_mappings_custom_account_password']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_account_verify_password'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_account_verify_password', sanitize_text_field($_POST['cf7ra_field_mappings_custom_account_verify_password']));
    }

    if (isset($_POST['cf7ra_field_mappings_custom_listing_sale_type'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_listing_sale_type', sanitize_text_field($_POST['cf7ra_field_mappings_custom_listing_sale_type']));
    }
    if (isset($_POST['cf7ra_field_mappings_custom_irrigated'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_custom_irrigated', sanitize_text_field($_POST['cf7ra_field_mappings_custom_irrigated']));
    }
    if (isset($_POST['cf7ra_field_mappings_type_of_housing'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_type_of_housing', sanitize_text_field($_POST['cf7ra_field_mappings_type_of_housing']));
    }

    if (isset($_POST['cf7ra_field_mappings_listing_number_type'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_listing_number_type', sanitize_text_field($_POST['cf7ra_field_mappings_listing_number_type']));
    }

    if (isset($_POST['housing_name'])) {
        $housing_name = $_POST['housing_name']; // Get selected checkboxes as an array
        $housing_name_str = implode(',', $housing_name);
        update_post_meta($post_id, 'cf7ra_field_mappings_housing_name', sanitize_text_field($housing_name_str));
    }

    if (isset($_POST['milk_facility'])) {
        $milk_facility = $_POST['milk_facility']; // Get selected checkboxes as an array
        $milk_facility_str = implode(',', $milk_facility);
        update_post_meta($post_id, 'cf7ra_field_mappings_milk_facility', sanitize_text_field($milk_facility_str));
    }

    if (isset($_POST['feed_storage'])) {
        $feed_storage = $_POST['feed_storage']; // Get selected checkboxes as an array
        $feed_storage_str = implode(',', $feed_storage);
        update_post_meta($post_id, 'cf7ra_field_mappings_feed_storage', sanitize_text_field($feed_storage_str));
    }

    if (isset($_POST['cf7ra_field_mappings_manure_storage'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_manure_storage', sanitize_text_field($_POST['cf7ra_field_mappings_manure_storage']));
    }

    if (isset($_POST['cf7ra_field_mappings_primary_phone'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_primary_phone', sanitize_text_field($_POST['cf7ra_field_mappings_primary_phone']));
    }
    if (isset($_POST['cf7ra_field_mappings_sec_phone'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_sec_phone', sanitize_text_field($_POST['cf7ra_field_mappings_sec_phone']));
    }
    if (isset($_POST['cf7ra_field_mappings_same_property'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_same_property', sanitize_text_field($_POST['cf7ra_field_mappings_same_property']));
    }
    if (isset($_POST['cf7ra_field_mappings_street_address'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_street_address', sanitize_text_field($_POST['cf7ra_field_mappings_street_address']));
    }
    if (isset($_POST['cf7ra_field_mappings_last_name'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_last_name', sanitize_text_field($_POST['cf7ra_field_mappings_last_name']));
    }
    if (isset($_POST['cf7ra_field_mappings_first_name'])) {
        update_post_meta($post_id, 'cf7ra_field_mappings_first_name', sanitize_text_field($_POST['cf7ra_field_mappings_first_name']));
    }

    // Redirect to prevent re-submission
    wp_redirect($_SERVER['HTTP_REFERER']);
    exit;
}
add_action('admin_post_cf7ra_save_meta_box', 'cf7ra_handle_meta_box_submission');
