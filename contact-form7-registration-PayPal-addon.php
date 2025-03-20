<?php

/**
 * Plugin Name: Contact Form 7 Registration & PayPal Addon
 * Description: A CF7 addon that registers users after payment and creates CPT.
 * Version: 1.0
 * Author: Ashish Prajapat
 */

if (!defined('ABSPATH')) {
    exit;
}
define('PAYPAL_CLIENT_ID', 'your-client-id');
define('PAYPAL_SECRET', 'your-secret-key');
define('PAYPAL_MODE', 'sandbox'); // Change to 'live' for production

define('CF7RA_PATH', plugin_dir_path(__FILE__));

require_once CF7RA_PATH . 'includes/admin-settings.php';
require_once CF7RA_PATH . 'includes/form-handler.php';
require_once CF7RA_PATH . 'includes/paypal-api.php';
require_once CF7RA_PATH . 'includes/user-registration.php';
require_once CF7RA_PATH . 'includes/cpt-handler.php';

function cf7ra_enqueue_scripts()
{
    wp_enqueue_style('cf7ra-admin-style', plugin_dir_url(__FILE__) . 'assets/styles.css');
    wp_enqueue_script('cf7ra-admin-js', plugin_dir_url(__FILE__) . 'assets/admin.js', array('jquery'), null, true);
}

add_action('admin_enqueue_scripts', 'cf7ra_enqueue_scripts');
