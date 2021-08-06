<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Validations\LoginValidator;
use App\Http\Resources\UserResource;
use App\Validations\Auth\RegisterUserValidator;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function register(Request $request, RegisterUserValidator $v)
    {
        $attributes = $v->validate((array) $request->getParsedBody());

        $user = User::create($attributes);

        return $this->json(UserResource::toArray($user), 201);
    }
    
    public function login(Request $request, LoginValidator $v)
    {
        $credentials = $v->validate((array) $request->getParsedBody());

        $user = AuthService::login($credentials['email'], $credentials['password']);

        if (! $user) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }

        $tokens = AuthService::generateAccessTokens($user);

        return $this->json($tokens);
    }

    public function refresh_token()
    {
        $refresh_token = $_COOKIE['refresh_token'] ?? null;

        if (! $refresh_token || ! $user = User::findByRefreshToken($refresh_token)) {
            return $this->json(['message' => 'Invalid Refresh Token'], 400);
        }

        $tokens = AuthService::generateAccessTokens($user);

        return $this->json($tokens);
    }
}
