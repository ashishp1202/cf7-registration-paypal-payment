<?php

/**
 * Plugin Name: Contact Form 7 Registration & PayPal Addon
 * Description: A CF7 addon that registers users after payment and creates CPT.
 * Version: 1.0
 * Author: Ashish Prajapat
 * Text Domain: cf7-reg-paypal-addon
 */

if (!defined('ABSPATH')) {
    exit;
}

$cf7ra_paypal_mode = get_option('cf7ra_paypal_mode');
$sandbox_client_id = get_option('cf7ra_sanboxclientid');
$sandbox_secret_key = get_option('cf7ra_sanboxsecretkey');
$live_client_id = get_option('cf7ra_liveclientid');
$live_secret_key = get_option('cf7ra_livesecretkey');

// Choose credentials based on mode
if ($cf7ra_paypal_mode === 'live') {
    define('PAYPAL_CLIENT_ID', $live_client_id);
    define('PAYPAL_SECRET', $live_secret_key);
} else {
    define('PAYPAL_CLIENT_ID', $sandbox_client_id);
    define('PAYPAL_SECRET', $sandbox_secret_key);
}

//define('PAYPAL_CLIENT_ID', $sandbox_client_id);
//define('PAYPAL_SECRET', $sandbox_secret_key);
define('PAYPAL_MODE', $cf7ra_paypal_mode); // Change to 'live' for production

define('CF7RA_PATH', plugin_dir_path(__FILE__));
//ini_set('display_errors', 1);
require_once CF7RA_PATH . 'includes/admin-settings.php';
require_once CF7RA_PATH . 'includes/form-handler.php';
//require_once CF7RA_PATH . 'includes/paypal-api.php';
require_once CF7RA_PATH . 'includes/user-registration.php';
require_once CF7RA_PATH . 'includes/cpt-handler.php';
require_once CF7RA_PATH . 'includes/shortcodes.php';
require_once CF7RA_PATH . 'includes/admin-metabox.php';
require_once CF7RA_PATH . 'includes/template-loader.php';
require_once CF7RA_PATH . 'includes/ajax-functions.php';

function cf7ra_enqueue_scripts()
{
    wp_enqueue_style('cf7ra-admin-style', plugin_dir_url(__FILE__) . 'assets/styles.css');
    wp_enqueue_script('cf7ra-admin-js', plugin_dir_url(__FILE__) . 'assets/admin.js', array('jquery'), null, true);
}

//add_action('admin_enqueue_scripts', 'cf7ra_enqueue_scripts');

add_action('wp_enqueue_scripts', 'cf7ra_enqueue_frontend_assets');

function cf7ra_enqueue_frontend_assets()
{
    // Only load on frontend and for logged-in users (optional)
    if (!is_admin()) {
        wp_enqueue_style('cf7ra-frontend-style', plugin_dir_url(__FILE__) . 'assets/front-end/frontend.css');
        wp_enqueue_script('cf7ra-frontend-script', plugin_dir_url(__FILE__) . 'assets/front-end/frontend.js', array('jquery'), null, true);

        if (is_singular('farm_listing')) {
            wp_enqueue_style('cf7ra-common-style', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/css/common.css');
            wp_enqueue_style('cf7ra-responsive-style', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/css/responsive.css');
            wp_enqueue_style('cf7ra-slick-theme-style', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/css/slick-theme.css');
            wp_enqueue_style('cf7ra-slick-style', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/css/slick.css');
            wp_enqueue_style('cf7ra-singlestyle-style', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/css/style.css');
            wp_enqueue_style('cf7ra-utilities-style', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/css/utilities.css');
            wp_enqueue_script('cf7ra-slickmin-script', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/js/slick.min.js', array('jquery'), null, true);
            wp_enqueue_script('cf7ra-frontendscr-script', plugin_dir_url(__FILE__) . '/assets/front-end/farm-single/js/script.js', array('jquery'), null, true);
        }


        wp_localize_script('cf7ra-frontend-script', 'deleteListing', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('delete_listing_nonce')
        ));
    }
}
// Create custom role on plugin activation
register_activation_hook(__FILE__, 'cf7ra_add_farm_seller_user_role');

function cf7ra_add_farm_seller_user_role()
{
    add_role('farm_seller', 'Farm Seller', array(
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
        'upload_files' => false,
    ));
}
