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
        $land_unit_type = get_post_meta($post_id, 'cf7ra_field_mappings_land_unit_type', true);
        $total_acres = get_post_meta($post_id, 'cf7ra_field_mappings_total_acres', true);
        $total_hectare = get_post_meta($post_id, 'cf7ra_field_mappings_total_hectare', true);
        $land_description = get_post_meta($post_id, 'cf7ra_field_mappings_land_description', true);
        ?>
        <table>
            <tr>
                <td>Listing Plan</td>
                <td><?php echo $listing_plan; ?></td>
            </tr>
            <tr>
                <td>Asking Price</td>
                <td><?php echo $asking_price; ?></td>
            </tr>
            <tr>
                <td>Asking price for monthly lease/rent</td>
                <td><?php echo $asking_price_rent_lease; ?></td>
            </tr>
            <tr>
                <td>Land Unit Type</td>
                <td><?php echo $land_unit_type; ?></td>
            </tr>
            <tr>
                <td>Total Acres</td>
                <td><?php echo $total_acres; ?></td>
            </tr>
            <tr>
                <td>Total Hectare</td>
                <td><?php echo $total_hectare; ?></td>
            </tr>
            <tr>
                <td>Land Description</td>
                <td><?php echo $land_description; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php get_footer(); ?>