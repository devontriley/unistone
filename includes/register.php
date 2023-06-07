<?php

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function usiRegisterSubmission() {
    $formValues = $_POST['formValues'];
    $errors = array();
    $userExists = false;

    foreach($formValues as $formItem)
    {
        $name = $formItem['name'];
        $value = $formItem['value'];

        // All fields are required, if field is empty, add error and move to next field
        if(!$value) {
            $errors[$name] = array(
                'error' => 'Required'
            );
            continue;
        }

        // Email validation
        if($name === 'email') {
            $userExists = email_exists($value);

            if(!validateEmail($value)) {
                $errors[$name] = array(
                    'error' => 'Invalid email address'
                );
            }
        }
    }

    wp_send_json_success(array(
        'formValues' => $formValues,
        'errors' => $errors,
        'userExists' => $userExists
    ));
}
add_action('wp_ajax_nopriv_usiRegisterSubmission', 'usiRegisterSubmission');
add_action('wp_ajax_usiRegisterSubmission', 'usiRegisterSubmission');

function usiAddNewUser()
{
    $formValues = $_POST['formValues'];
    $userdata = array(
        'user_login' =>  $formValues['email'],
        'user_email' => $formValues['email'],
        'first_name' =>  $formValues['firstName'],
        'last_name' => $formValues['lastName'],
        'user_pass'  => $formValues['password'],
        'role' => $formValues['accountType']
    );

    $user_id = wp_insert_user($userdata);

    if($user_id) {
        // Add pending account status
        add_user_meta($user_id, 'account_status', 'awaiting_admin_review');
        // Add user business name
        add_user_meta($user_id, 'business_name', $formValues['businessName']);

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $userEmailBody = '';
        ob_start();
        include(get_stylesheet_directory() . '/includes/email/account-created-user.php');
        $emailBody = ob_get_contents();
        ob_end_clean();

        // Send notification email to user
        $sendUserNotification = wp_mail(
            $userdata['user_email'],
            'Universal Stone Account Registration',
            $emailBody,
            $headers
        );

        $adminEmailBody = '';
        ob_start();
        include(get_stylesheet_directory() . '/includes/email/account-created-admin.php');
        $adminEmailBody = ob_get_contents();
        ob_end_clean();

        // Send notification email to admin
        $sendAdminNotification = wp_mail(
            array('dev@heretic.agency', 'sydneyr@unistoneimports.com', 'marketing@unistoneimports.com'),
            'A new user has registered on the Universal Stone website',
            $adminEmailBody,
            $headers
        );

        // Try adding a second role for testing
        // $user = get_user_by('ID', $user_id);
        // $user->add_role( 'um_vendor');
    }

    wp_send_json_success(array(
        'formValues' => $formValues,
        'userData' => $userdata,
        'newUser' => $user_id,
        'sendUserNotification' => $sendUserNotification,
        'sendAdminNotification' => $sendAdminNotification
    ));
}
add_action('wp_ajax_nopriv_usiAddNewUser', 'usiAddNewUser');
add_action('wp_ajax_usiAddNewUser', 'usiAddNewUser');