<?php
add_filter('theme_page_templates', 'cf7ra_register_plugin_template');
add_filter('template_include', 'cf7ra_load_plugin_template');

function cf7ra_register_plugin_template($templates)
{
    $templates['cf7ra-payment-success.php'] = 'CF7RA Payment Success';
    return $templates;
}

function cf7ra_load_plugin_template($template)
{
    if (is_page()) {
        $page_template = get_page_template_slug(get_queried_object_id());

        if ($page_template === 'cf7ra-payment-success.php') {
            $plugin_template = plugin_dir_path(__FILE__) . 'templates/payment-success.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
    }
    return $template;
}
