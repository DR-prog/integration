<?php

namespace App\Http\Controllers;

use App\Actions\RegistrationAction;

session_start();

class AuthController extends Controller
{

    public function auth()
    {
        $clientId = "dc443ba3-cc30-4334-a5f2-0e8226762406";
        $clientSecret = "7sP6ugyjuo30h2D53wtvViGQSuqhWQBUJjzbrJjPUf3OcdlkqHsaQtogqstn7Jrf";
        $redirectUri = "https://4d03-176-112-255-83.eu.ngrok.io/auth";
        $apiClient = RegistrationAction::registration($clientId, $clientSecret, $redirectUri);
        echo('Вы успешно зарегестрированы');
    }
}

