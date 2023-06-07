<?php

// Redirect user trying to access wp-login.php to custom login page
function redirect_to_custom_login() {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $login_url = home_url('login');
        wp_redirect($login_url);
        exit;
    }
}
add_action('login_form_login', 'redirect_to_custom_login');

function sendPasswordResetEmail()
{
    $email = $_POST['email'];

    if(!$email) {
        wp_send_json_error(array(
            'error_message' => 'Please enter your email address'
        ));
    }

    $user = get_user_by('email', $email);

    if(!$user) {
        wp_send_json_error(array(
            'error_message' => 'No user found with this email address'
        ));
    }

    // Validate the nonce for this action.
    check_ajax_referer( 'usi_reset_password_form', 'security' );

    // Send the password reset link.
    $results = retrieve_password( $user->user_login );

    if($results) {
        wp_send_json_success('Reset password has been sent');
    } else {
        wp_send_json_error(array(
            'error_message' => $results->get_error_message()
        ));
    }
}
add_action('wp_ajax_nopriv_sendPasswordResetEmail', 'sendPasswordResetEmail');
add_action('wp_ajax_sendPasswordResetEmail', 'sendPasswordResetEmail');