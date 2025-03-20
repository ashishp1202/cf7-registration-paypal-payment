<?php

function cf7ra_register_menu()
{
    //add_options_page('CF7 Registration Addon', 'CF7 Registration Addon', 'manage_options', 'cf7ra-settings', 'cf7ra_settings_page');
    add_submenu_page(
        'wpcf7', // Parent slug (Contact Form 7)
        'CF7 Registration Addon', // Page title
        'CF7 Registration Addon', // Menu title
        'manage_options', // Capability
        'cf7ra-settings', // Menu slug
        'cf7ra_settings_page' // Function callback
    );
}

add_action('admin_menu', 'cf7ra_register_menu');

function cf7ra_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // Fetch Contact Form 7 forms
    $forms = get_posts(array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1));

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cf7ra_save_settings'])) {
        update_option('cf7ra_form_id', sanitize_text_field($_POST['cf7ra_form_id']));
        update_option('cf7ra_field_mappings', json_encode($_POST['cf7ra_field_mappings']));
    }

    $selected_form_id = get_option('cf7ra_form_id');
    $field_mappings = json_decode(get_option('cf7ra_field_mappings'), true) ?: [];

?>

    <div class="wrap">
        <h1>CF7 Registration Addon Settings</h1>

        <form method="POST">
            <label>Select Contact Form 7 Form:
                <select name="cf7ra_form_id" id="cf7ra_form_id">
                    <option value="">-- Select Form --</option>
                    <?php foreach ($forms as $form) : ?>
                        <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($selected_form_id, $form->ID); ?>>
                            <?php echo esc_html($form->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <br><br>

            <div id="cf7ra_field_mappings">
                <h3>Field Mapping</h3>
                <p>Select the Contact Form 7 field names to map:</p>

                <label>Email:
                    <select name="cf7ra_field_mappings[email]">
                        <option value="">-- Select Field --</option>
                    </select>
                </label>
                <br>

                <label>Password:
                    <select name="cf7ra_field_mappings[password]">
                        <option value="">-- Select Field --</option>
                    </select>
                </label>
                <br>

                <label>CPT Title:
                    <select name="cf7ra_field_mappings[cpt_title]">
                        <option value="">-- Select Field --</option>
                    </select>
                </label>
                <br>
            </div>

            <input type="submit" name="cf7ra_save_settings" value="Save Settings">
        </form>
    </div>

    <script>
        jQuery(document).ready(function($) {
            function loadFormFields(formId) {
                if (!formId) return;

                $.post(ajaxurl, {
                    action: 'cf7ra_get_form_fields',
                    form_id: formId
                }, function(response) {
                    let fields = JSON.parse(response);
                    $('select[name^="cf7ra_field_mappings"]').empty().append('<option value="">-- Select Field --</option>');
                    fields.forEach(field => {
                        $('select[name^="cf7ra_field_mappings"]').append(`<option value="${field}">${field}</option>`);
                    });
                });
            }

            $('#cf7ra_form_id').change(function() {
                loadFormFields($(this).val());
            });

            if ($('#cf7ra_form_id').val()) {
                loadFormFields($('#cf7ra_form_id').val());
            }
        });
    </script>

<?php
}

function cf7ra_get_form_fields()
{
    if (!isset($_POST['form_id'])) {
        echo json_encode([]);
        wp_die();
    }

    $form_id = sanitize_text_field($_POST['form_id']);
    $form = get_post($form_id);

    if (!$form) {
        echo json_encode([]);
        wp_die();
    }

    // Extract fields from form content
    preg_match_all('/\[(email|text|password)\*?\s+([^\s\]]+)/', $form->post_content, $matches);

    echo json_encode($matches[2]); // List of field names
    wp_die();
}

add_action('wp_ajax_cf7ra_get_form_fields', 'cf7ra_get_form_fields');
