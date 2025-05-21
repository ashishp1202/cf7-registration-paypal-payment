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
        wp_send_json(['message' => 'Please log in.']);
    }
    $user_id = get_current_user_id();
    $post_id = intval($_POST['post_id']);
    $favorites = get_user_meta($user_id, 'favorite_posts', true);
    $favorites[] = $post_id;
    update_user_meta($user_id, 'favorite_posts', $favorites);
    wp_send_json(['message' => 'Added to Favorites']);
}
add_action('wp_ajax_addtofav_farm_listing', 'add_to_fav_farm_listing');

// ajax call for remove from Fav
function remove_from_fav_farm_listing()
{

    $post_id = intval($_POST['post_id']);

    if (!is_user_logged_in()) {
        wp_send_json(['message' => 'Please log in.']);
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
    wp_send_json(['message' => 'Removed from Favorites']);
}
add_action('wp_ajax_removefromfav_farm_listing', 'remove_from_fav_farm_listing');
