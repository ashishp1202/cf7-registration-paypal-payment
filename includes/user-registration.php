<?php

function cf7ra_register_user($email, $password)
{
    $user_id = wp_create_user($email, $password, $email);
    wp_update_user(array('ID' => $user_id, 'role' => 'farm_seller'));
    return $user_id;
}

add_filter('login_redirect', 'cf7ra_redirect_custom_user', 10, 3);

function cf7ra_redirect_custom_user($redirect_to, $request, $user)
{
    if (isset($user->roles) && in_array('farm_seller', $user->roles)) {
        $account_page_id = get_option('cf7ra_account_page_id');
        if ($account_page_id) {
            return get_permalink($account_page_id);
        }
    }
    return $redirect_to;
}
