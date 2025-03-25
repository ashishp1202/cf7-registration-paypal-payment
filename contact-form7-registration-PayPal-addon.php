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
define('PAYPAL_CLIENT_ID', 'ATsfAaoG9pp29JWmmBMUam3HWcDKX1-fHqaSr1SzF_9cpUiYBkxJuXJqrZRqWjT2s1bLKQsKCMsumWVG');
define('PAYPAL_SECRET', 'EIZKykn7afjWNkG81rmKZX_pjk2eX0XN2pfhZqXedCdxL6U_jk-MKYtbE-EVCIgVZxilVVc9HRGgcja-Dairy');
define('PAYPAL_MODE', 'sandbox'); // Change to 'live' for production

define('CF7RA_PATH', plugin_dir_path(__FILE__));
ini_set('display_errors', 1);
require_once CF7RA_PATH . 'includes/admin-settings.php';
require_once CF7RA_PATH . 'includes/form-handler.php';
require_once CF7RA_PATH . 'includes/paypal-api.php';
require_once CF7RA_PATH . 'includes/user-registration.php';
require_once CF7RA_PATH . 'includes/cpt-handler.php';
require_once CF7RA_PATH . 'includes/shortcodes.php';

function cf7ra_enqueue_scripts()
{
    wp_enqueue_style('cf7ra-admin-style', plugin_dir_url(__FILE__) . 'assets/styles.css');
    wp_enqueue_script('cf7ra-admin-js', plugin_dir_url(__FILE__) . 'assets/admin.js', array('jquery'), null, true);
}

//add_action('admin_enqueue_scripts', 'cf7ra_enqueue_scripts');

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
