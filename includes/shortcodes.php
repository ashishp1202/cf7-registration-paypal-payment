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
    echo '<ul class="cf7ra-user-listings">';
    while ($listings->have_posts()) : $listings->the_post();
        echo '<li>';
        echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
        echo '</li>';
    endwhile;
    echo '</ul>';

    wp_reset_postdata();

    return ob_get_clean();
}
