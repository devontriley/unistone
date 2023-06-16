<?php

class DropboxAPI {
    private static $refreshToken = DROPBOX_REFRESH_TOKEN;
    private static $clientID = DROPBOX_CLIENT_ID;
    private static $clientSecret = DROPBOX_CLIENT_SECRET;
    private static $baseURL = 'https://api.dropbox.com/';

    public static function getClientID() {
        return self::$clientID;
    }

    public static function getClientSecret() {
        return self::$clientSecret;
    }

    public static function getRefreshToken() {
        return self::$refreshToken;
    }

    private static function getBaseURL() {
        return self::$baseURL;
    }

    // Use refresh token which has no expiration to retrieve a new short-lived access token
    public static function getAccessToken() {
        if ( ! self::$refreshToken ) {
            return false;
        }

        $response = null;
        $error = null;

        $url = self::$baseURL . 'oauth2/token/';
        $params = http_build_query(array(
            'grant_type' => 'refresh_token',
            'refresh_token' => self::$refreshToken ,
            'client_id' => self::$clientID,
            'client_secret' => self::$clientSecret
        ));
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        try {
            $response = curl_exec($ch);

            // Check for errors
            if ( curl_errno($ch) ) {
                throw new Exception(curl_error($ch));
            }
        } catch (Exception $e ) {
            $error = 'cURL Error: ' . $e->getMessage();
        }

        return array(
            'response' => $response,
            'error' => $error
        );
    }
}