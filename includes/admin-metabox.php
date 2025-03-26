<?php
add_action('add_meta_boxes', 'action__cf7ra_add_meta_boxes');
function action__cf7ra_add_meta_boxes()
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
        '<td>    <input type="text" id="my_custom_field" name="my_custom_field" value="' . esc_attr($form_id) . '" style="width:100%;" /></td>' .
        '</tr>';

    echo '</table>';
}

add_action('save_post', 'cf7ra_save_custom_meta_box_data');

function cf7ra_save_custom_meta_box_data($post_id)
{

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (isset($_POST['post_type']) && $_POST['post_type'] === 'farm_listing') {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Sanitize and save
    if (isset($_POST['my_custom_field'])) {
        $sanitized = sanitize_text_field($_POST['my_custom_field']);
        update_post_meta($post_id, '_my_custom_field', $sanitized);
    }
}
