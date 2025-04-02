<?php

function cf7ra_get_paypal_token()
{
    $url = (PAYPAL_MODE == 'sandbox') ? "https://api-m.sandbox.paypal.com/v1/oauth2/token" : "https://api-m.paypal.com/v1/oauth2/token";

    $args = array(
        'body'        => array('grant_type' => 'client_credentials'),
        'headers'     => array(
            'Authorization' => 'Basic ' . base64_encode(PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ),
        'timeout'     => 45,
        'sslverify'   => false,
    );

    $response = wp_remote_post($url, $args);
    /* echo "<pre>";
    print_r($response);
    exit(); */
    if (is_wp_error($response)) {
        return false;
    }


    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['access_token'] ?? false;
}

function cf7ra_create_paypal_order($amount, $currency = 'USD', $return_url, $cancel_url)
{
    $access_token = cf7ra_get_paypal_token();
    if (!$access_token) return false;

    $url = (PAYPAL_MODE == 'sandbox') ? "https://api-m.sandbox.paypal.com/v2/checkout/orders" : "https://api-m.paypal.com/v2/checkout/orders";

    $args = array(
        'body'    => json_encode(array(
            'intent' => 'CAPTURE',
            'purchase_units' => array(
                array(
                    'amount' => array(
                        'currency_code' => $currency,
                        'value'         => $amount
                    )
                )
            ),
            'application_context' => array(
                'return_url' => $return_url,
                'cancel_url' => $cancel_url
            )
        )),
        'headers' => array(
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type'  => 'application/json',
        ),
        'timeout' => 45,
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return false;
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}


function cf7ra_create_paypal_subscription_plan($amount, $interval, $currency = 'USD')
{
    $access_token = cf7ra_get_paypal_token();
    if (!$access_token) return false;

    $url = (PAYPAL_MODE == 'sandbox') ? "https://api-m.sandbox.paypal.com/v1/billing/plans" : "https://api-m.paypal.com/v1/billing/plans";

    $args = array(
        'body'    => json_encode(array(
            'product_id' => 'YOUR_PRODUCT_ID', // Replace with actual product ID
            'name'       => "Subscription Plan - $interval",
            'billing_cycles' => array(
                array(
                    'frequency' => array(
                        'interval_unit'  => strtoupper($interval), // "MONTH" or "YEAR"
                        'interval_count' => ($interval == '6months') ? 6 : 1,
                    ),
                    'tenure_type' => 'REGULAR',
                    'sequence'    => 1,
                    'total_cycles' => 0,
                    'pricing_scheme' => array(
                        'fixed_price' => array(
                            'value'         => $amount,
                            'currency_code' => $currency,
                        ),
                    ),
                ),
            ),
            'payment_preferences' => array(
                'auto_bill_outstanding' => true,
            ),
        )),
        'headers' => array(
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type'  => 'application/json',
        ),
        'timeout' => 45,
    );

    $response = wp_remote_post($url, $args);
    echo "<pre>";
    print_r($response);
    exit();
    if (is_wp_error($response)) {
        return false;
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

function cf7ra_capture_paypal_payment($order_id)
{
    $access_token = cf7ra_get_paypal_token();
    if (!$access_token) return false;

    $url = (PAYPAL_MODE == 'sandbox') ? "https://api-m.sandbox.paypal.com/v2/checkout/orders/$order_id/capture" : "https://api-m.paypal.com/v2/checkout/orders/$order_id/capture";

    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type'  => 'application/json',
        ),
        'method'  => 'POST',
        'timeout' => 45,
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return false;
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

function cf7ra_extract_transaction_data($response)
{
    if (!$response || !isset($response['status']) || $response['status'] !== 'COMPLETED') {
        return false;
    }

    $transaction = $response['purchase_units'][0]['payments']['captures'][0];

    return [
        'transaction_id' => $transaction['id'],
        'amount'         => $transaction['amount']['value'],
        'currency'       => $transaction['amount']['currency_code'],
        'payer_email'    => $response['payer']['email_address'],
        'payer_name'     => $response['payer']['name']['given_name'] . ' ' . $response['payer']['name']['surname'],
    ];
}
