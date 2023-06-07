<?php

function usiEditProfileSubmission()
{
    $verifyNonce = check_ajax_referer('usi_edit_user_profile', 'nonce');

    // If nonce is not valid, return error and die
    if(!$verifyNonce) {
        wp_send_json_error(array(
            'errors' => 'Nonce not valid'
        ));
        wp_die();
    }

    $formValues = $_POST['formValues'];
    $email = sanitize_email($_POST['email']);
    $user = get_user_by('email', $email);

    // If user is not found, return error and die
    if(!$user) {
        wp_send_json_error(array(
            'errors' => 'User not found'
        ));
        wp_die();
    }

    $userID = $user->ID;
    $status = get_user_meta($userID, 'account_status', true);
    $user_roles = $user->roles;

    if($status === 'awaiting_admin_review') {
        wp_send_json_error(array(
            'error' => 'Awaiting admin review'
        ));
        wp_die();
    }

    $formValuesObj = array();
    foreach($formValues as $value) :
        // Skip keys that begin with underscore
        if($value['name'][0] === '_') continue;
        // Skip blank form fields
        if(!$value['value']) continue;
        $formValuesObj[$value['name']] = sanitize_text_field($value['value']);
    endforeach;

    $updatedFields = array();
    foreach($formValuesObj as $key => $value) :
        $updatedFields[$key] = update_user_meta($userID, $key, $value);
    endforeach;

    // Redirect user to profile page after updating
    $redirectURL = esc_url(get_home_url().'/user-profile');

    wp_send_json_success(array(
        'verifyNonce' => $verifyNonce,
        'user' => $user,
        'email' => $email,
        'formValues' => $formValues,
        'formValuesObj' => $formValuesObj,
        'updatedFields' => $updatedFields,
        'status' => $status,
        'roles' => $user_roles,
        'redirect' => $redirectURL
    ));
    wp_die();
}
add_action('wp_ajax_nopriv_usiEditProfileSubmission', 'usiEditProfileSubmission');
add_action('wp_ajax_usiEditProfileSubmission', 'usiEditProfileSubmission');