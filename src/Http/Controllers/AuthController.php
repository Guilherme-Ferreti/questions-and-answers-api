<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Validations\LoginValidator;
use App\Http\Resources\UserResource;
use App\Validations\Auth\RegisterUserValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function register(Request $request, Response $response, RegisterUserValidator $v)
    {
        $attributes = $v->validate((array) $request->getParsedBody());

        $user = User::create($attributes);

        return $this->json($response, [
            'user' => UserResource::toArray($user),
        ], 201);
    }
    
    public function login(Request $request, Response $response, LoginValidator $v)
    {
        $credentials = $v->validate((array) $request->getParsedBody());

        $user = AuthService::login($credentials['email'], $credentials['password']);

        if (! $user) {
            return $this->json($response, [
                'error' => 'Invalid credentials',
            ], 401);
        }

        $tokens = AuthService::generateAccessTokens($user);

        return $this->json($response, $tokens);
    }

    public function refresh_token(Request $request, Response $response)
    {
        $refresh_token = $_COOKIE['refresh_token'] ?? null;

        if (! $refresh_token) {
            return $this->json($response, [
                'error' => 'Invalid Refresh Token',
            ]);
        }

        $user = User::findByRefreshToken($refresh_token);

        if (! $user) {
            return $this->json($response, [
                'error' => 'Invalid Refresh Token - No user',
            ]);
        }

        $tokens = AuthService::generateAccessTokens($user);

        return $this->json($response, $tokens);
    }
}
