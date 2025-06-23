<?php
get_header(); ?>
<div class="main">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="content">
            <?php the_content(); ?>
            <?php

            $post_id = get_the_ID();
            $user_id = get_current_user_id();
            if ($user_id) {
                $favorites = get_user_meta($user_id, 'favorite_posts', true);
                $is_favorite = is_array($favorites) && in_array($post_id, $favorites);
            }
            $listing_plan = get_post_meta($post_id, 'cf7ra_field_mappings_listing_plan', true);
            $asking_price = get_post_meta($post_id, 'cf7ra_field_mappings_asking_price', true);
            $asking_price_rent_lease = get_post_meta($post_id, 'cf7ra_field_mappings_asking_price_rent_lease', true);
            $listhouse_infoing_plan = get_post_meta($post_id, 'cf7ra_field_mappings_house_info', true);
            $land_unit_type = get_post_meta($post_id, 'cf7ra_field_mappings_land_unit_type', true);
            $total_acres = get_post_meta($post_id, 'cf7ra_field_mappings_total_acres', true);
            $total_hectare = get_post_meta($post_id, 'cf7ra_field_mappings_total_hectare', true);
            $farm_capacity = get_post_meta($post_id, 'cf7ra_field_mappings_farm_capacity', true);
            $capacity_type = get_post_meta($post_id, 'cf7ra_field_mappings_capacity_type', true);
            $land_description = get_post_meta($post_id, 'cf7ra_field_mappings_land_description', true);
            $short_description = get_post_meta($post_id, 'cf7ra_field_mappings_land_description', true);
            $sq_foot = get_post_meta($post_id, 'cf7ra_field_mappings_sq_foot', true);
            $num_of_bedrooms = get_post_meta($post_id, 'cf7ra_field_mappings_num_of_bedrooms', true);
            $num_of_bathrooms = get_post_meta($post_id, 'cf7ra_field_mappings_num_of_bathrooms', true);
            $street_address = get_post_meta($post_id, 'cf7ra_field_mappings_street_address', true);
            $address_city = get_post_meta($post_id, 'cf7ra_field_mappings_address_city', true);
            $address_state = get_post_meta($post_id, 'cf7ra_field_mappings_address_state', true);
            ?>
            <?php
            $post_id = get_the_ID();
            $photo_url = get_post_meta($post_id, 'cf7ra_field_mappings_photo_upload', true);
            $land_unit_type = get_post_meta($post_id, 'cf7ra_field_mappings_land_unit_type', true);
            if ($land_unit_type === 'Acre') {
                $land_unit = get_post_meta($post_id, 'cf7ra_field_mappings_total_acres', true);
            } else {
                $land_unit = get_post_meta($post_id, 'cf7ra_field_mappings_total_hectare', true);
            }
            $description_buyers_read = get_post_meta($post_id, 'cf7ra_field_mappings_description_buyers_read', true);
            $farm_status = get_post_meta($post_id, 'cf7ra_field_mappings_farm_status', true);

            ?>
            <?php if (!empty($photo_url)) { ?>
                <section class="home-banner-slider-section">
                    <div class="row">
                        <div class="cell-md-8">
                            <div class="banner-slider">
                                <?php foreach ($photo_url as $image_url) { ?>
                                    <div class="slider-item">
                                        <a href="<?php echo $image_url; ?>" data-fancybox="gallery">
                                            <img src="<?php echo $image_url; ?>" alt="image">
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="cell-md-4">
                            <div class="banner-slider-thumb-grid">
                                <?php foreach ($photo_url as $image_url) { ?>
                                    <div class="banner-grid-item"><img src="<?php echo $image_url; ?>" alt="image"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <section class="home-detail-section">

                <div class="home-price-wrap">
                    <div class="row align-items-center justify-content-between">
                        <div class="cell-md-6">
                            <div class="home-price"><b>Asking Price: $</b><?php echo (!empty($asking_price) && is_numeric($asking_price)) ? number_format($asking_price) : $asking_price; ?></div>
                        </div>


                        <div class="cell-md-6">
                            <div class="header-btn-wrap text-right">
                                <button style="<?php if (!$is_favorite) {
                                                    echo 'display: inline-block;';
                                                } else {
                                                    echo 'display: none;';
                                                } ?>" href="javascript:void();"
                                    class="btn uppercase add-remove-favorites add-to-fav-trigger"
                                    data-post-id="<?php echo $post_id; ?>" title="Add to Favorites">
                                    <i class="fa-regular fa-heart"></i>
                                    <span>Add to Favorites</span>
                                </button>


                                <button class="btn uppercase add-remove-favorites remove-from-fav-trigger" style="<?php if ($is_favorite) {
                                                                                                                        echo 'display: inline-block;';
                                                                                                                    } else {
                                                                                                                        echo 'display: none;';
                                                                                                                    } ?>" href="javascript:void();" data-post-id="<?php echo $post_id; ?>"
                                    title="Remove from Favorites"><i class="fa-solid fa-heart"></i><span>Remove from
                                        Favorites</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="home-feature-section">
                <div class="row">
                    <div class="cell-md-12">
                        <div class="home-feature-item">
                            <div class="home-feature-item-title-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 25 25"
                                    class="feather inline-block  ">
                                    <g fill="none" stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </g>
                                </svg>
                                <div class="home-feature-item-title">Highlights</div>
                            </div>

                            <ul class="home-feature-item-list">
                                <li class="home-feature">
                                    <span class="bold">Status :</span> <?php echo ($farm_status && !empty($farm_status)) ? $farm_status : 'Active'; ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">Price :</span>
                                    <?php echo (!empty($asking_price) && is_numeric($asking_price)) ? number_format($asking_price) : $asking_price; ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">Land :</span> <?php echo $land_unit . " " . $land_unit_type; ?>
                                </li>

                                <li class="home-feature">
                                    <span class="bold">Heifer Facilities: </span>
                                    <?php echo get_post_meta($post_id, 'cf7ra_field_mappings_type_of_housing', true); ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">House Sq Ft :</span> <?php echo $sq_foot . "sq. ft."; ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">House Bedrooms :</span> <?php echo $num_of_bedrooms; ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">House Bathrooms :</span> <?php echo $num_of_bathrooms; ?>
                                </li>

                                <li class="home-feature">
                                    <span class="bold">Land Desc :</span> <?php echo $short_description; ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">Address :</span>
                                    <?php echo $street_address . ", " . $address_city . ", " . $address_state; ?>
                                </li>



                            </ul>
                        </div>
                    </div>

                    <div class="cell-md-12">
                        <div class="home-feature-item">
                            <div class="home-feature-item-title-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 25 25"
                                    class="feather inline-block  ">
                                    <g fill="none" stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </g>
                                </svg>
                                <div class="home-feature-item-title">House Description</div>
                            </div>

                            <div class="home-feature-item-content farm-house-full-description">
                                <?php echo nl2br(esc_html($description_buyers_read)); ?>
                            </div>

                            <?php
                            if (str_word_count(strip_tags($description_buyers_read)) > 200): ?>
                                <button href="javascript:void(0);" class="btn uppercase read-more-toggle">Show More</button>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php get_footer(); ?>