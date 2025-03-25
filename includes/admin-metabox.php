<?php
add_action('add_meta_boxes',      'action__cf7ra_add_meta_boxes');
function action__add_meta_boxes()
{
    add_meta_box('cf7ra_-data', __('Farm Data', 'cf7-reg-paypal-addon'),  'cf7ra__show_from_data', 'farm_listing', 'normal', 'high');
}
function cf7ra__show_from_data($post)
{

    $form_id = get_post_meta($post->ID, 'cf7ra_field_mappings_form_id', true);


    echo '<table class="cf7pap-box-data form-table">' .
        '<style>.inside-field td, .inside-field th{ padding-top: 5px; padding-bottom: 5px;}</style>';

    echo '<tr class="form-field">' .
        '<th scope="row">' .
        '<label for="hcf_author">' . __('Form ID', 'cf7-reg-paypal-addon') . '</label>' .
        '</th>' .
        '<td>' . $form_id . '</td>' .
        '</tr>';

    echo '</table>';
}
