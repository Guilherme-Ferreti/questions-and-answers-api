<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use App\Validations\Auth\RegisterUserValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function register(Request $request, Response $response)
    {
        $validator = new RegisterUserValidator((array) $request->getParsedBody());

        if ($validator->fails()) {
            return $this->json($response, [
                'errors' => $validator->errors()->toArray(),
            ], 400);
        }

        $user = User::create($validator->getValidData());

        return $this->json($response, [
            'user' => UserResource::toArray($user),
        ], 201);
    }
    
    public function login(Request $request, Response $response)
    {
        $parsedBody = (array) $request->getParsedBody();

        $user = AuthService::login($parsedBody['email'] ?? '', $parsedBody['password'] ?? '');

        if (! $user) {
            return $this->json($response, [
                'error' => 'Invalid credentials',
            ], 401);
        }

        $auth_token = AuthService::createAuthToken($user->id);

        $refresh_token = AuthService::updateRefreshToken($user);

        return $this->json($response, [
            'token' => $auth_token['value'],
            'expires_at' => $auth_token['expires_at'],
            'refresh_token' => $refresh_token,
        ]);
    }
}
