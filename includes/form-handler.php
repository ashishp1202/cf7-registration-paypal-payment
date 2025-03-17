<?php

add_action('wpcf7_mail_sent', 'cf7ra_handle_form_submission');

function cf7ra_handle_form_submission($contact_form)
{
    $form_id = get_option('cf7ra_form_id');
    if ($contact_form->id() != $form_id) {
        return;
    }

    $submission = WPCF7_Submission::get_instance();
    if (!$submission) {
        return;
    }

    $data = $submission->get_posted_data();
    $field_mappings = json_decode(get_option('cf7ra_field_mappings'), true);

    $email = sanitize_email($data[$field_mappings['email']] ?? '');
    $password = sanitize_text_field($data[$field_mappings['password']] ?? '');
    $cpt_title = sanitize_text_field($data[$field_mappings['cpt_title']] ?? '');

    if (empty($email) || empty($password)) {
        return;
    }

    $payment_success = cf7ra_process_paypal_payment($email);

    if ($payment_success) {
        $user_id = cf7ra_register_user($email, $password);
        cf7ra_create_cpt($user_id, $cpt_title);
    }
}
