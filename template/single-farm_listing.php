<?php
get_header(); ?>
<div class="main">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="content">
            <?php the_content(); ?>
            <?php
            $post_id = get_the_ID();
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
            ?>
            <!-- <table>
                <tr>
                    <td width="50%"><strong>Pricing Plan: </strong></td>
                    <td width="50%"><?php echo $listing_plan; ?></td>
                </tr>
                <tr>
                    <td><strong>Listing Sale Type: </strong></td>
                    <td><?php echo $listhouse_infoing_plan; ?></td>
                </tr>
                <tr>
                    <td><strong>Land Unit Type: </strong></td>
                    <td><?php echo $land_unit_type; ?></td>
                </tr>
                <tr>
                    <td><strong>Asking price: </strong></td>
                    <?php if ($land_unit_type == 'Acre') { ?>
                        <td><?php echo $asking_price; ?></td>
                    <?php } else { ?>
                        <td><?php echo $asking_price_rent_lease; ?></td>
                    <?php } ?>
                </tr>

                <tr>
                    <td><strong>Farm Capacity: </strong></td>
                    <td><?php echo $farm_capacity; ?></td>
                </tr>
                <tr>
                    <td><strong>Farm Type:</strong> </td>
                    <td><?php echo $capacity_type; ?></td>
                </tr>
                <tr>
                    <td><strong>Land Description: </strong></td>
                    <td><?php echo $land_description; ?></td>
                </tr>
            </table> -->
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

            ?>
            <section class="home-header-menu-section">
                <div class="row align-items-center justify-content-between">
                    <div class="cell-md-6">
                        <!-- <ul class="menu-wrap d-flex align-items-center">
                            <li class="menu-item"><a href="#">Search</a></li>
                            <li class="menu-item"><a href="#">Overview</a></li>
                            <li class="menu-item"><a href="#">Payement</a></li>
                            <li class="menu-item"><a href="#">Market Insights</a></li>
                            <li class="menu-item"><a href="#">Schools</a></li>
                        </ul> -->
                    </div>

                    <div class="cell-md-6">
                        <div class="header-btn-wrap">
                            <!-- <a href="#" class="btn uppercase" target="_self">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                                    color="#FFF" height="20" width="20" xmlns="http://www.w3.org/2000/svg"
                                    style="color: rgb(255, 255, 255);">
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                    <path
                                        d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z">
                                    </path>
                                </svg>
                                <span>MAKE AN OFFER</span>
                            </a> -->

                            <a href="#" class="btn uppercase" target="_self">
                                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                                    stroke-linecap="round" stroke-linejoin="round" color="#FFF" height="18" width="18"
                                    xmlns="http://www.w3.org/2000/svg" style="color: rgb(255, 255, 255);">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                    <polyline points="16 6 12 2 8 6"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="15"></line>
                                </svg>
                                <span>Share</span>
                            </a>

                            <a href="#" class="btn uppercase" target="_self">
                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024"
                                    color="#FFF" height="20" width="20" xmlns="http://www.w3.org/2000/svg"
                                    style="color: rgb(255, 255, 255);">
                                    <path
                                        d="M923 283.6a260.04 260.04 0 0 0-56.9-82.8 264.4 264.4 0 0 0-84-55.5A265.34 265.34 0 0 0 679.7 125c-49.3 0-97.4 13.5-139.2 39-10 6.1-19.5 12.8-28.5 20.1-9-7.3-18.5-14-28.5-20.1-41.8-25.5-89.9-39-139.2-39-35.5 0-69.9 6.8-102.4 20.3-31.4 13-59.7 31.7-84 55.5a258.44 258.44 0 0 0-56.9 82.8c-13.9 32.3-21 66.6-21 101.9 0 33.3 6.8 68 20.3 103.3 11.3 29.5 27.5 60.1 48.2 91 32.8 48.9 77.9 99.9 133.9 151.6 92.8 85.7 184.7 144.9 188.6 147.3l23.7 15.2c10.5 6.7 24 6.7 34.5 0l23.7-15.2c3.9-2.5 95.7-61.6 188.6-147.3 56-51.7 101.1-102.7 133.9-151.6 20.7-30.9 37-61.5 48.2-91 13.5-35.3 20.3-70 20.3-103.3.1-35.3-7-69.6-20.9-101.9zM512 814.8S156 586.7 156 385.5C156 283.6 240.3 201 344.3 201c73.1 0 136.5 40.8 167.7 100.4C543.2 241.8 606.6 201 679.7 201c104 0 188.3 82.6 188.3 184.5 0 201.2-356 429.3-356 429.3z">
                                    </path>
                                </svg>
                                <span>Save</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <?php if (!empty($photo_url)) { ?>
                <section class="home-banner-slider-section">
                    <div class="row">
                        <div class="cell-md-8">
                            <div class="banner-slider">
                                <?php foreach ($photo_url as $image_url) { ?>
                                    <div class="slider-item">
                                        <img src="<?php echo $image_url; ?>" alt="image">
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
                <!-- <div class="attribute-wrap">
                    <div class="row align-items-center">
                        <div class="cell-md-12 attribute-item d-flex align-items-center">
                            <div class="attribute-label">Listed by BHGRE</div>
                            <div class="attribute-value"> - Northwest Home Team</div>
                        </div>

                        <div class="cell-md-12 attribute-item d-flex align-items-center">
                            <div class="attribute-label">and </div>
                            <div class="attribute-value"> Northwest Multiple Listing Service</div>
                        </div>
                    </div>
                </div>

                <div class="home-title-wrap">
                    <div class="row align-items-center">
                        <div class="cell-md-12 d-flex align-items-center">
                            <div class="home-title">20910 Bucoda Hwy SE</div>
                            <div class="home-type">Centralia, WA 98531</div>
                        </div>
                    </div>
                </div> -->

                <div class="home-price-wrap">
                    <div class="row align-items-center justify-content-between">
                        <div class="cell-md-5">
                            <div class="home-price">$<?php echo $asking_price; ?></div>
                            <!-- <div class="home-estimate-price-main"> Est.Payment: <span
                                    class="home-est-price">$3,240.49/mo</span></div> -->
                        </div>

                        <div class="cell-md-6">
                            <div class="home-faqs-wrap">
                                <div class="home-faq-item">
                                    <div class="number">3</div>
                                    <div class="value">Bed</div>
                                </div>

                                <div class="home-faq-item">
                                    <div class="number">2</div>
                                    <div class="value">Bath</div>
                                </div>

                                <div class="home-faq-item">
                                    <div class="number"><?php echo $land_unit; ?></div>
                                    <div class="value"><?php echo $land_unit_type; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="home-status-wrap">
                    <div class="home-status"><span class="green"></span>Status: Active</div>
                    <div class="home-qualified">
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                            stroke-linecap="round" stroke-linejoin="round" width="1em" height="1em" color="#5386E4"
                            xmlns="http://www.w3.org/2000/svg" style="color: rgb(83, 134, 228);">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Get Pre-Qualified
                    </div>
                </div> -->
            </section>

            <section class="home-feature-section">
                <div class="row">
                    <div class="cell-md-6">
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
                                    <span class="bold">Price :</span> <?php echo $asking_price; ?>
                                </li>
                                <li class="home-feature">
                                    <span class="bold">Land :</span> <?php echo $land_unit . " " . $land_unit_type; ?>
                                </li>

                                <li class="home-feature">
                                    <span class="bold">Heifer Facilities: </span>
                                    <?php echo get_post_meta($post_id, 'cf7ra_field_mappings_type_of_housing', true); ?>
                                </li>

                                <!-- <div class="home-feature border-bottom">
                                    <span class="bold">Lot/Acreage </span>
                                    12.74
                                </div> -->

                                <!-- <div class="home-feature border-bottom">
                                    <span class="bold">Year Built </span>
                                    2000
                                </div> -->

                                <!-- <div class="home-feature border-bottom">
                                    <span class="bold">Area </span>
                                    454 - Thurston South
                                </div> -->

                                <!-- <div class="home-feature border-bottom">
                                    <span class="bold"><a href="#@">Neighborhood </a></span>
                                    Centralia
                                </div> -->

                                <!-- <div class="home-feature border-bottom">
                                    <span class="bold"><a href="#@">School District </a></span>
                                    Centralia
                                </div> -->

                                <!-- <div class="home-feature border-bottom">
                                    <span class="bold">Listing ID</span>
                                    2373713
                                </div> -->
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

                            <div class="home-feature-item-content">
                                <?php echo $description_buyers_read; ?>
                            </div>
                        </div>
                    </div>



                    <!-- <div class="cell-md-6">
                        <div class="home-feature-item">
                            <div class="home-feature-item-title-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 25 25"
                                    class="feather inline-block  ">
                                    <g fill="none" stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="4 14 10 14 10 20"></polyline>
                                        <polyline points="20 10 14 10 14 4"></polyline>
                                        <line x1="14" y1="10" x2="21" y2="3"></line>
                                        <line x1="3" y1="21" x2="10" y2="14"></line>
                                    </g>
                                </svg>
                                <div class="home-feature-item-title">Interior Features</div>
                            </div>

                            <div class="home-feature-item-content">
                                <div class="home-feature">
                                    <div class="home-feature">Bedrooms and Bathrooms</div>
                                    <ul>
                                        <li>Bedrooms:3</li>
                                        <li>Bathrooms:2</li>
                                        <li>Full Bathrooms:2</li>
                                    </ul>
                                </div>
                                <div class="home-feature"><span class="bold">Appliances: </span>Dishwasher(s),
                                    Dryer(s), Microwave(s), Refrigerator(s), Stove(s)/Range(s), Washer(s)</div>
                                <div class="home-feature"><span class="bold">Fireplaces: </span>Gas,1 Fireplace</div>
                                <div class="home-feature"><span class="bold">Floor: </span>Laminate,Carpet</div>
                                <div class="home-feature"><span class="bold">Above Ground Sq Ft: </span>N/A</div>
                                <div class="home-feature"><span class="bold">Below Ground Sq Ft: </span>N/A</div>
                                <div class="home-feature"><span class="bold">Other: </span>
                                    Bath Off Primary, Ceiling Fan(s), Double Pane/Storm Window, Dining Room, Fireplace,
                                    Laminate, Water Heater, Master on Main</div>
                                <div class="home-feature"><span class="bold">Water: </span>Individual Well</div>
                            </div>
                        </div>
                    </div>

                    <div class="cell-md-6">
                        <div class="home-feature-item">
                            <div class="home-feature-item-title-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 25 25"
                                    class="feather inline-block  ">
                                    <g fill="none" stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="4 14 10 14 10 20"></polyline>
                                        <polyline points="20 10 14 10 14 4"></polyline>
                                        <line x1="14" y1="10" x2="21" y2="3"></line>
                                        <line x1="3" y1="21" x2="10" y2="14"></line>
                                    </g>
                                </svg>
                                <div class="home-feature-item-title">Exterior Features</div>
                            </div>

                            <div class="home-feature-item-content">
                                <div class="home-feature">
                                    <span class="bold">Lot:</span>
                                    Paved, Manufactured Home, Fenced-Partially, Outbuildings, Propane, RV Parking, Shop,
                                    River
                                </div>
                                <div class="home-feature"><span class="bold">Roof: </span>Metal</div>
                                <div class="home-feature"><span class="bold">Others: </span>Wood</div>
                                <div class="home-feature"><span class="bold">Sewer:</span>Septic Tank</div>
                                <div class="home-feature"><span class="bold">Parking Features: </span>Detached
                                    Carport,RV Parking</div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </section>
        </div>
    </div>
</div>
<?php get_footer(); ?>