<?php

function cf7ra_create_cpt($user_id, $title)
{
    $post_data = array(
        'post_title'   => $title,
        'post_status'  => 'publish',
        'post_author'  => $user_id,
        'post_type'    => 'farm_listing'
    );
    return wp_insert_post($post_data);
}

function cf7ra_register_cpt()
{

    register_post_type('farm_listing', array(
        'label' => 'Farm Listings',
        'public'             => true,
        'publicly_queryable' => true,
        'supports' => array('title', 'editor', 'author'),
    ));
}

add_action('init', 'cf7ra_register_cpt');
