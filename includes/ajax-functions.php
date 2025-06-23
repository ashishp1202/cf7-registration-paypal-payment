<?php
// ajax call for delete action
function delete_farm_listing()
{
    if (!isset($_POST['nonce'], $_POST['post_id']) || !is_user_logged_in()) {
        wp_send_json_error(['message' => 'Invalid request']);
    }

    $post_id = intval($_POST['post_id']);
    $nonce = $_POST['nonce'];

    if (!wp_verify_nonce($nonce, 'delete_listing_' . $post_id)) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    if (get_post_field('post_author', $post_id) != get_current_user_id()) {
        wp_send_json_error(['message' => 'You do not have permission to delete this post']);
    }

    if (wp_delete_post($post_id, true)) {
        wp_send_json_success(['message' => 'Listing deleted']);
    } else {
        wp_send_json_error(['message' => 'Failed to delete listing']);
    }
}
add_action('wp_ajax_delete_farm_listing', 'delete_farm_listing');

// ajax call for Add to Fav
function add_to_fav_farm_listing()
{

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'You need to login first to add to favorite list.']);
    }
    $user_id = get_current_user_id();
    $post_id = intval($_POST['post_id']);
    $favorites = get_user_meta($user_id, 'favorite_posts', true);
    if (!is_array($favorites)) {
        $favorites = [];
    }
    $favorites[] = $post_id;
    update_user_meta($user_id, 'favorite_posts', $favorites);
    wp_send_json_success(['message' => 'Added to Favorites']);
}
add_action('wp_ajax_addtofav_farm_listing', 'add_to_fav_farm_listing');
add_action('wp_ajax_nopriv_addtofav_farm_listing', 'add_to_fav_farm_listing');

// ajax call for remove from Fav
function remove_from_fav_farm_listing()
{

    $post_id = intval($_POST['post_id']);

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'You need to login first to remove from favorite list.']);
    }

    $user_id = get_current_user_id();
    $post_id = intval($_POST['post_id']);
    $favorites = get_user_meta($user_id, 'favorite_posts', true);

    if (!is_array($favorites)) {
        $favorites = [];
    }


    // REMOVE from favorites
    $favorites = array_diff($favorites, [$post_id]);
    update_user_meta($user_id, 'favorite_posts', $favorites);
    wp_send_json_success(['message' => 'Removed from Favorites']);
}
add_action('wp_ajax_removefromfav_farm_listing', 'remove_from_fav_farm_listing');

function fn_search_farm_listing()
{
    $metaQuery = array('relation' => 'AND');
    $html = '';
    if (isset($_POST['farmPrice']) && !empty($_POST['farmPrice'])) {
        $farmPrice = $_POST['farmPrice'];
        $metaQuery[] = array(
            'key'     => 'cf7ra_field_mappings_asking_price',
            'value'   => explode('-', $farmPrice),
            'type'    => 'numeric',
            'compare' => 'BETWEEN',
        );
    }
    if (isset($_POST['farmLocation']) && !empty($_POST['farmLocation'])) {
        $farmLocation = $_POST['farmLocation'];
        $metaQuery[] = array(
            'key'     => 'cf7ra_field_mappings_address_state',
            'value'   => $farmLocation,
            'compare' => '=',
        );
    }
    if (isset($_POST['farmLandsize']) && !empty($_POST['farmLandsize'])) {
        $farmLandsize = $_POST['farmLandsize'];
        $metaQuery[] = array(
            'key'     => 'cf7ra_field_mappings_total_acres',
            'value'   => explode('-', $farmLandsize),
            'type'    => 'numeric',
            'compare' => 'BETWEEN',
        );
    }
    if (isset($_POST['farmCowcapacity']) && !empty($_POST['farmCowcapacity'])) {
        $farmCowcapacity = $_POST['farmCowcapacity'];
        $metaQuery[] = array(
            'key'     => 'cf7ra_field_mappings_farm_capacity',
            'value'   => explode('-', $farmCowcapacity),
            'type'    => 'numeric',
            'compare' => 'BETWEEN',
        );
    }

    $args = array(
        'post_type' => 'farm_listing',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => $metaQuery,
    );
    $listings = new WP_Query($args);
    if ($listings->have_posts()) {
        while ($listings->have_posts()):
            $listings->the_post();
            $post_id = get_the_ID();

            $farm_capacity = get_post_meta($post_id, 'cf7ra_field_mappings_farm_capacity', true);
            $asking_price = get_post_meta($post_id, 'cf7ra_field_mappings_asking_price', true);
            $land_unit_type = get_post_meta($post_id, 'cf7ra_field_mappings_land_unit_type', true);
            if ($land_unit_type === 'Acre') {
                $land_unit = get_post_meta($post_id, 'cf7ra_field_mappings_total_acres', true);
            } else {
                $land_unit = get_post_meta($post_id, 'cf7ra_field_mappings_total_hectare', true);
            }
            $user_id = get_current_user_id();
            if ($user_id) {
                $favorites = get_user_meta($user_id, 'favorite_posts', true);
                $is_favorite = is_array($favorites) && in_array($post_id, $favorites);
            }
            $is_favoriteCSS = (!$is_favorite) ? 'inline-block;' : 'none';
            $is_NotfavoriteCSS = ($is_favorite) ? 'inline-block;' : 'none';
            $html .= '<div class="cell-md-4"><div class="farm-listing-item">
                    <div class="farm-listing-item-image">
                        <a href="' . get_permalink($post_id) . '">
                            <img src="' . get_the_post_thumbnail_url($post_id, 'large') . '" />
                        </a>
                    </div>
                    <div class="farm-listing-item-capicity">
                        <h3 style="font-size: larger;">' . get_the_title($post_id) . '</h3>
                        <p><b>Asking Price: $</b>' . $asking_price . '</p>
                        <p><b>Land Area: </b>' . $land_unit . " " . $land_unit_type . '</p>
                        <p><b>Capacity: ' . $farm_capacity . ' Cows</b></p>
                    </div>
                    <div class="ajax-btn-wrap">
                        <a class="view-farm-detail-btn" href="' . get_permalink($post_id) . '">View more</a>

                        <a class="add-remove-favorites add-to-fav-trigger"  style="display:' . $is_favoriteCSS . '" href="javascript:void();" data-post-id="' . $post_id . ' ?>"
                            title="Add to Favorites"><i class="fa-regular fa-heart"></i></a>

                        <a class="add-remove-favorites remove-from-fav-trigger" style="display:' . $is_NotfavoriteCSS . '" href="javascript:void();" data-post-id="' . $post_id . '"title="Remove from Favorites"><i class="fa-solid fa-heart"></i></a>

                    </div>
                </div>
            </div>';
        endwhile;
    } else {
        wp_send_json_error(['message' => 'No Dairy Farm found.']);
    }
    wp_reset_postdata();
    wp_send_json_success(['message' => 'Added to Favorites', 'html' => $html]);
}
add_action('wp_ajax_search_farm_listing', 'fn_search_farm_listing');
add_action('wp_ajax_nopriv_search_farm_listing', 'fn_search_farm_listing');
