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

        update_option('cf7ra_paypal_mode', sanitize_text_field($_POST['cf7ra_paypal_mode']));
        update_option('cf7ra_sanboxclientid', sanitize_text_field($_POST['cf7ra_sanboxclientid']));
        update_option('cf7ra_sanboxsecretkey', sanitize_text_field($_POST['cf7ra_sanboxsecretkey']));
        update_option('cf7ra_liveclientid', sanitize_text_field($_POST['cf7ra_liveclientid']));
        update_option('cf7ra_livesecretkey', sanitize_text_field($_POST['cf7ra_livesecretkey']));

        update_option('cf7ra_field_mappings', json_encode($_POST['cf7ra_field_mappings']));
        update_option('cf7ra_account_page_id', intval($_POST['cf7ra_account_page_id']));
    }

    $selected_form_id = get_option('cf7ra_form_id');
    $field_mappings = json_decode(get_option('cf7ra_field_mappings'), true) ?: [];
    $account_page_id = get_option('cf7ra_account_page_id');
    $pages = get_pages(); // Get all WordPress pages

    $cf7ra_paypal_mode = get_option('cf7ra_paypal_mode');
    $sandbox_client_id = get_option('cf7ra_sanboxclientid');
    $sandbox_secret_key = get_option('cf7ra_sanboxsecretkey');
    $live_client_id = get_option('cf7ra_liveclientid');
    $live_secret_key = get_option('cf7ra_livesecretkey');

?>

    <div class="wrap">
        <h1>CF7 Registration Addon Settings</h1>

        <form method="POST">
            <table>
                <tr>
                    <td><label>My Account Page:</label></td>
                    <td>
                        <select name="cf7ra_account_page_id">
                            <option value="">-- Select Page --</option>
                            <?php foreach ($pages as $page) : ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($account_page_id, $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label>Select Contact Form 7 Form:</label></td>
                    <td>
                        <select name="cf7ra_form_id" id="cf7ra_form_id">
                            <option value="">-- Select Form --</option>
                            <?php foreach ($forms as $form) : ?>
                                <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($selected_form_id, $form->ID); ?>>
                                    <?php echo esc_html($form->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <h3>PayPal Keys</h3>
            <table>
                <tr>
                    <td><label>Select PayPal Mode:</label></td>
                    <td>
                        <select name="cf7ra_paypal_mode">
                            <option value="">-- Select Mode --</option>
                            <option value="sandbox" <?php selected($cf7ra_paypal_mode, 'sandbox'); ?>>Sandbox</option>
                            <option value="live" <?php selected($cf7ra_paypal_mode, 'live'); ?>>Live</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Sandbox Client ID</td>
                    <td><input type="text" id="sanboxclientid" name="cf7ra_sanboxclientid" value="<?php echo esc_attr($sandbox_client_id); ?>" /></td>
                </tr>
                <tr>
                    <td>Sandbox Secret Key</td>
                    <td><input type="text" id="sanboxsecretkey" name="cf7ra_sanboxsecretkey" value="<?php echo esc_attr($sandbox_secret_key); ?>" /></td>
                </tr>

                <tr>
                    <td>Live Client ID</td>
                    <td><input type="text" id="liveclientid" name="cf7ra_liveclientid" value="<?php echo esc_attr($live_client_id); ?>" /></td>
                </tr>
                <tr>
                    <td>Live Secret Key</td>
                    <td><input type="text" id="livesecretkey" name="cf7ra_livesecretkey" value="<?php echo esc_attr($live_secret_key); ?>" /></td>
                </tr>
            </table>
            <h3>Field Mapping</h3>
            <p>Select the Contact Form 7 field names to map:</p>
            <table>
                <thead>
                    <tr>
                        <td>
                            <h3>Meta Field Names</h3>
                        </td>
                        <td>
                            <h3>Contact Form 7 Fields Name</h3>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Listing Title</b></td>
                    </tr>
                    <tr>
                        <td>Title</td>
                        <td><select name="cf7ra_field_mappings[cpt_title]" id="cf7ra_cpt_title_field">
                                <option value="">-- Select Field --</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Seller's / Account Contact Information</b></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><select name="cf7ra_field_mappings[firstname]" id="cf7ra_firstname_field">
                                <option value="">-- Select Field --</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><select name="cf7ra_field_mappings[lastname]" id="cf7ra_lastname_field">
                                <option value="">-- Select Field --</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td><select name="cf7ra_field_mappings[email]" id="cf7ra_email_field">
                                <option value="">-- Select Field --</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><select name="cf7ra_field_mappings[password]" id="cf7ra_password_field">
                                <option value="">-- Select Field --</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <input type="submit" name="cf7ra_save_settings" value="Save Settings" class="button button-primary">
        </form>
    </div>

    <script>
        jQuery(document).ready(function($) {
            let savedMappings = <?php echo json_encode($field_mappings); ?>;

            function loadFormFields(formId) {
                if (!formId) return;

                $.post(ajaxurl, {
                    action: 'cf7ra_get_form_fields',
                    form_id: formId
                }, function(response) {
                    let fields = JSON.parse(response);
                    const mapFields = {
                        email: '#cf7ra_email_field',
                        password: '#cf7ra_password_field',
                        cpt_title: '#cf7ra_cpt_title_field',
                        firstname: '#cf7ra_firstname_field',
                        lastname: '#cf7ra_lastname_field'
                    };

                    Object.entries(mapFields).forEach(([key, selector]) => {
                        const select = $(selector);
                        select.empty().append('<option value="">-- Select Field --</option>');

                        fields.forEach(field => {
                            const isSelected = (savedMappings[key] && savedMappings[key] === field) ? 'selected' : '';
                            select.append(`<option value="${field}" ${isSelected}>${field}</option>`);
                        });
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
    if (!current_user_can('manage_options') || !isset($_POST['form_id'])) {
        echo json_encode([]);
        wp_die();
    }

    $form_id = intval($_POST['form_id']);
    $form = get_post($form_id);

    if (!$form) {
        echo json_encode([]);
        wp_die();
    }

    preg_match_all('/\[(?:email|text|password|tel|textarea|select|radio|checkbox).*? ([^\s\]]+)/', $form->post_content, $matches);
    echo json_encode($matches[1]);
    wp_die();
}

add_action('wp_ajax_cf7ra_get_form_fields', 'cf7ra_get_form_fields');
