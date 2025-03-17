<?php

function cf7ra_register_user($email, $password)
{
    $user_id = wp_create_user($email, $password, $email);
    wp_update_user(array('ID' => $user_id, 'role' => 'subscriber'));
    return $user_id;
}
