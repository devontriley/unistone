<?php

use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;

use Analog\Logger;
use Analog\Handler\File;
use Psr\Log\LogLevel;

function userPhotoUploadLog($message = false) {
    if(!$message) return false;

    $logger = new Logger;
    $logger->handler (File::init (__DIR__ . '/userUploadsLog.txt'));
    $log = $message;
    $logger->log (LogLevel::INFO, $log);
}

function uploadUserPhotosToDropbox() {
    $userEmail = $_POST['userEmail'];

    // TODO: Use PHP Dropbox SDK to fetch access token instead of my own cURL
    $getToken = DropboxAPI::getAccessToken();

    // Email admins to alert dropbox error
    if ( $getToken['error'] ) {
        $html = 'There was an error while user ( '. $userEmail .' ) attempted to upload photos to dropbox.<br /><br />';
        $html .= $getToken['error'];

        wp_mail(
            array('devontriley@gmail.com', 'sydneyr@unistoneimports.com'),
            'There was an error on the website while uploading photos to dropbox',
            $html,
            array( 'Content-Type: text/html; charset=UTF-8' )
        );

        wp_send_json_error(array(
            'error' => $getToken['error']
        ));
    }

    $responseJSON = json_decode($getToken['response']);
    $token = $responseJSON->access_token;

    // Errors array
    $errors = new WP_Error();

    // USI dropbox app credentials
    $clientId = DropboxAPI::getClientID();
    $clientSecret = DropboxAPI::getClientSecret();
    $folderPath = '/USI Team Folder/Website User Uploaded Photos/';

    // Dropbox connection
    $app = new DropboxApp(
        $clientId,
        $clientSecret,
        $token
    );
    $dropbox = new Dropbox($app);

    // Check connection to dropbox
//    if (!empty($app) || !empty($dropbox)) {
//        $errors->add('unable_to_connect_to_dropbox', 'Unable to connect to dropbox. Reload the page and try again.');
//
//        $logMessage = $userEmail . ' was unable to connect to dropbox.';
//        dropboxLog($logMessage);
//
//        wp_send_json_error($errors);
//    }

    // Get object of arrays for multiple files
    $filesToUpload = $_FILES['filesToUpload'];
    $filesCount = count($filesToUpload['name']);
    $filesArray = [];

    // Data to capture and send back in json for each uploaded file
    $fileData = [];

    for ($i = 0; $i < $filesCount; $i++) {
        array_push($filesArray, [
            'error' => $filesToUpload['error'][$i],
            'name' => $filesToUpload['name'][$i],
            'size' => $filesToUpload['size'][$i],
            'tmp_name' => $filesToUpload['tmp_name'][$i],
            'type' => $filesToUpload['type'][$i]
        ]);
    }

    foreach ($filesArray as $file) {
        $img = $file['name'];
        $tmp = $file['tmp_name'];
        $size = $file['size'];

        // Get file size in MB
        $size = $size / 1024 / 1024;
        // valid extensions
        $valid_extensions = array('jpeg', 'jpg', 'png');
        // upload directory
        $path = __DIR__ . '/tempDropboxImages/';
        // get uploaded file's extension
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        // can upload same image using rand function
        $final_image = rand(1000,1000000).'_'.$img;

        // Check if uploaded file is valid type
        if (!in_array($ext, $valid_extensions)) {
            $errors->add('invalid_file_type', 'File '.  $img .' is incorrect file type');
        }

        // Check if uploaded file is correct file size
        if($size > 40) {
            $errors->add('file_too_large', $img .' is too large. Resize this photo and try again.');
        }

        if(is_wp_error($errors) && !empty($errors->errors)) {
            wp_send_json_error($errors);
        }

        // Add final filename to path
        $path = $path . strtolower($final_image);

        // Upload image to temp directory
        $uploadedFile = move_uploaded_file($tmp, $path);

        // Create stream through file stream
        $dropboxFile = new DropboxFile($path);

        // Upload file to dropbox
        $dropboxUploadedFile = $dropbox->upload($dropboxFile, $folderPath.$final_image, ['autorename' => true]);

        // Delete image from temp directory
        $deleteTempFile = unlink($path);

        // Write to log
        $logMessage = $userEmail.' uploaded '. $final_image;
        userPhotoUploadLog($logMessage);

        // Send notification email to admins
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        $adminEmailBody = '';
        ob_start();
        include(get_stylesheet_directory() . '/includes/email/user-photos-uploaded-admin.php');
        $adminEmailBody = ob_get_contents();
        ob_end_clean();

        // Send notification email to admin
        $sendAdminNotification = wp_mail(
        //array('dev@heretic.agency', 'sydneyr@unistoneimports.com', 'marketing@unistoneimports.com'),
            array('devontriley@gmail.com', 'sydneyr@unistoneimports.com'),
            'A website user has uploaded photos to dropbox',
            $adminEmailBody,
            $headers
        );

        array_push($fileData, [
            'dropboxUploadedFile' => $dropboxUploadedFile,
            'img' => $img,
            'tmp' => $tmp,
            'final_image' => $final_image,
            'size' => $size,
            'path' => $path,
            'uploadedFile' => $uploadedFile,
            'deleteTempFile' => $deleteTempFile,
            'sendAdminNotification' => $sendAdminNotification
        ]);
    }

    wp_send_json_success([
        'dir' => __DIR__,
        'fileData' => $fileData
    ]);
}
add_action('wp_ajax_nopriv_upload_user_photos_to_dropbox', 'uploadUserPhotosToDropbox');
add_action('wp_ajax_upload_user_photos_to_dropbox', 'uploadUserPhotosToDropbox');