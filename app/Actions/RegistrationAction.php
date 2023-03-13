<?php

namespace App\Actions;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Exception;

class RegistrationAction
{
    public static function registration(string $clientId, string $clientSecret, string $redirectUri)
    {
        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        try {
            if (
                !file_exists(__DIR__ . '\..\..\TOKEN_FILE.txt') ||
                (!file_get_contents(__DIR__ . '\..\..\TOKEN_FILE.txt'))
            ) {
                if (isset($_GET['referer'])) {
                    $apiClient->setAccountBaseDomain($_GET['referer']);
                }

                if (!isset($_GET['code'])) {
                    $state = bin2hex(random_bytes(16));
                    $_SESSION['oauth2state'] = $state;
                    if (isset($_GET['button'])) {
                        $apiClient->getOAuthClient()->getOAuthButton(
                            [
                                'title' => 'Установить интеграцию',
                                'compact' => true,
                                'class_name' => 'className',
                                'color' => 'default',
                                'error_callback' => 'handleOauthError',
                                'state' => $state,
                            ]
                        );
                        die;
                    } else {
                        $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
                            'state' => $state,
                            'mode' => 'post_message',
                        ]);
                        header('Location: ' . $authorizationUrl);
                        die;
                    }
                } elseif (
                    empty($_GET['state']) ||
                    empty($_SESSION['oauth2state']) ||
                    ($_GET['state'] !== $_SESSION['oauth2state'])
                ) {
                    unset($_SESSION['oauth2state']);
                    exit('Invalid state');
                }
                try {
                    $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
                    if (!$accessToken->hasExpired()) {
                        TokenActions::mySaveToken([
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $apiClient->getAccountBaseDomain(),
                        ]);
                    }
                } catch (Exception $e) {
                    die((string)$e);
                } catch (AmoCRMoAuthApiException $e) {
                    die("authorization failed");
                }
            } else {
                $accessToken = TokenActions::myGetToken();
                $apiClient->setAccountBaseDomain($accessToken->getValues()['baseDomain']);
                $apiClient->setAccessToken($accessToken);
            }
        } catch (Exception $e) {
            die("Произошла ошибка");
        }
        return $apiClient;
    }
}
