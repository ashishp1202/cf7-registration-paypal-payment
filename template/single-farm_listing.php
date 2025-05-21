<?php
get_header(); ?>

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
        <table>
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
        </table>
    </div>
</div>

<?php get_footer(); ?>