<?php

use Analog\Logger;
use Analog\Handler\File;
use Psr\Log\LogLevel;

function sapReportsSyncLog($logLevel, $message = false) {
    if(!$message) return false;
    $sapLogger = new Logger;
    $sapLogger->handler (File::init (__DIR__ . '/sapReportSyncLog.txt'));
    $sapLogger->log($logLevel, $message);
}

function deleteFilesInDirectory($directory) {
    if (!is_dir($directory)) {
        return;
    }

    $files = glob($directory . '/*');

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}

function fetch_sap_reports() {
    // TODO: Use PHP Dropbox SDK to fetch access token instead of my own cURL
    $getToken = DropboxAPI::getAccessToken();

    $responseJSON = json_decode($getToken['response']);
    $token = $responseJSON->access_token;

    // TODO: Use PHP Dropbox SDK to download zip file
    // Dropbox API endpoint for file download
    $downloadUrl = 'https://content.dropboxapi.com/2/files/download_zip';

    // Path to the file you want to download from Dropbox
    $filePath = '/SAP Reports';

    // Destination path where the downloaded file will be saved
    $destinationPath = get_template_directory() . '/includes/sapReports/SAP Reports' . basename($filePath) . '.zip';

    // Set up cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $downloadUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Dropbox-API-Arg: ' . json_encode(['path' => $filePath])
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);

    sapReportsSyncLog(LogLevel::INFO, json_encode($response));

    // Check for any errors
    if (curl_errno($ch)) {
        echo 'Error downloading file: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Get the HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL
    curl_close($ch);

    // Check the HTTP status code
    if ($httpCode === 200) {
        // Save the downloaded file
        file_put_contents($destinationPath, $response);

        // Delete current files in SAP Reports directory
        deleteFilesInDirectory(get_template_directory().'/includes/sapReports/SAP Reports');

        // Unzip the file
        $zip = new ZipArchive;
        if ($zip->open($destinationPath) === true) {
            $zip->extractTo( get_template_directory() . '/includes/sapReports/' );
            $zip->close();

            // Delete zip file
            unlink($destinationPath);
        } else {
            // Log the unsuccessful unzip
            $logMessage = 'Zip file unzip failure: ' . $destinationPath;
            sapReportsSyncLog(LogLevel::ERROR, $logMessage);
        }
    } else {
        // Log the unsuccessful download of the zip file
        $logMessage = 'Zip file was not saved: ' . $destinationPath;
        sapReportsSyncLog(LogLevel::ERROR, $logMessage);
    }
}