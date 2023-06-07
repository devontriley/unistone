<?php

function usiLoginSubmission() {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $recaptchaToken = $_POST['recaptchaToken'];

    // Verify Google Recaptcha token
    $verifyRecaptchaResponse = verifyRecaptcha($recaptchaToken);

    // TODO: Uncomment the below code once we've had some traffic and we can see the average scores for user logins
    // Return error if score is below 0.5
//    if($verifyRecaptchaResponse['response']->score < 0.5) {
//        wp_send_json_error(array(
//            'recaptcha' => $verifyRecaptchaResponse,
//            'error_message' => 'Google Recaptcha failed'
//        ));
//        wp_die();
//    }

    //$verifyNonce = check_ajax_referer('usi_login_form', 'nonce');

    // Authenticate user
    $user = wp_authenticate($email, $password);

    // If user is not authenticated, return error and die
    if(is_wp_error($user)) {
        $errorCode = $user->get_error_code();
        $message = $user->get_error_message();
        $errorMessage = ($errorCode === 'incorrect_password') ? 'Incorrect password' : (($errorCode === 'invalid_username') ? 'Incorrect username' : 'Unknown error');
        wp_send_json_error(array(
            'error_code' => $errorCode,
            'error' => $message,
            'error_message' => $errorMessage
        ));
        wp_die();
    }

    $userID = $user->ID;

    // Get user account_status
    $status = get_user_meta($userID, 'account_status', true);

    // Get user roles
    $user_roles = $user->roles;

    if($status === 'awaiting_admin_review') {
        wp_send_json_error(array(
           'error' => 'Your account is pending review. We\'ll send you an email once your account is approved.'
        ));
        wp_die();
    }

    $signon = wp_signon(array(
        'user_login' => $email,
        'user_password' => $password,
        'remember' => false
    ));

    // If sign on fails, return error and die
    if(is_wp_error($signon)) {
        wp_send_json_error(array(
            'errors' => $signon
        ));
        wp_die();
    }

    // Redirect user after login
    $siteURL = get_home_url();
    $redirectURL = '';
    foreach($user_roles as $role) {
        switch($role) {
            case 'administrator':
                $redirectURL = get_admin_url();
                break;
            case 'um_authorized-dealer':
                $redirectURL = $siteURL.'/dealer-information-east';
                break;
            case 'um_vendor':
                $redirectURL = $siteURL.'/vendor';
                break;
            case 'um_s1-west':
                $redirectURL = $siteURL.'/dealer-information-s1-west';
                break;
            case 'um_s-1':
                $redirectURL = $siteURL.'/dealer-information-s1-east';
                break;
            case 'um_residential-end-user':
                $redirectURL = $siteURL.'/homeowner';
                break;
            case 'um_hr-manager':
                $redirectURL = $siteURL;
                break;
            case 'um_contractor':
                $redirectURL = $siteURL.'/contractor-information';
                break;
            case 'um_authorized-dealer-west':
                $redirectURL = $siteURL.'/dealer-information-west';
                break;
            case 'um_authorized-ns-dealer':
                $redirectURL = $siteURL.'/dealer-information-ns-east';
                break;
            case 'um_designer-architect':
                $redirectURL = $siteURL.'/architect-designer-information';
                break;
            default:
                $redirectURL = $siteURL;
        }
    }

    wp_send_json_success(array(
        'verifyNonce' => $verifyNonce,
        'signon' => $signon,
        'user' => $user,
        'email' => $email,
        'password' => $password,
        'status' => $status,
        'roles' => $user_roles,
        'redirect' => $redirectURL,
        'recaptcha' => $verifyRecaptchaResponse
    ));
    wp_die();
}
add_action('wp_ajax_nopriv_usiLoginSubmission', 'usiLoginSubmission');
add_action('wp_ajax_usiLoginSubmission', 'usiLoginSubmission');


function verifyRecaptcha($token)
{
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $method = 'POST';
    $secret = GOOGLE_RECAPTCHA_SECRET;
    $data = array(
        'response' => $token,
        'secret' => $secret
    );

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data
    );

    $curl = curl_init();

    curl_setopt_array($curl, $options);

    $curlResponse = curl_exec($curl);

    curl_close($curl);

    return array(
        'token' => $token,
        'response' => json_decode($curlResponse)
    );
}