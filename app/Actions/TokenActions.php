<?php

namespace App\Actions;

use League\OAuth2\Client\Token\AccessToken;

class TokenActions
{
    public static function mySaveToken(array $accessToken)
    {
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            $data = [
                'accessToken'  => $accessToken['accessToken'],
                'expires'      => $accessToken['expires'],
                'refreshToken' => $accessToken['refreshToken'],
                'baseDomain'   => $accessToken['baseDomain'],
            ];

            file_put_contents(__DIR__ . '\..\..\TOKEN_FILE.txt', json_encode($data));
        } else {
            exit('Invalid access token ' . var_export($accessToken, true));
        }
    }

    /**
     * @return AccessToken|void
     */
    public static function myGetToken()
    {
        if (! file_exists(__DIR__ . '\..\..\TOKEN_FILE.txt')) {
            exit('Access token file not found');
        }

        $accessToken = json_decode(file_get_contents(__DIR__ . '\..\..\TOKEN_FILE.txt'), true);
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            return new AccessToken([
                'access_token'  => $accessToken['accessToken'],
                'refresh_token' => $accessToken['refreshToken'],
                'expires'       => $accessToken['expires'],
                'baseDomain'    => $accessToken['baseDomain'],
            ]);
        } else {
            exit('Invalid access token ' . var_export($accessToken, true));
        }
    }

}
