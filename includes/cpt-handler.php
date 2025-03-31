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


function custom_cpt_template($template)
{
    if (is_singular('farm_listing')) { // Check if it's a single post of CPT
        $plugin_template = CF7RA_PATH . 'template/single-farm_listing.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'custom_cpt_template');
