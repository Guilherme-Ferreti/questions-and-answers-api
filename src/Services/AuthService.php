<?php

namespace App\Services;

use App\Models\User;
use App\Validations\LoginValidator;

class AuthService
{
    public static function login(string $email, string $password): User|false
    {
        $validator = new LoginValidator(compact('email', 'password'));

        if ($validator->fails()) {
            return false;
        }

        $validated = $validator->getValidData();

        $user = User::findByEmail($validated['email']);

        if (! $user || ! password_verify($validated['password'], $user->password)) {
            return false;
        }

        return $user;
    }

    public static function generateAccessTokens(User $user): array
    {
        $user->refresh_token = md5(uniqid(rand(), true));

        $user->update();

        setcookie(
            name: 'refresh_token', 
            value: $user->refresh_token,
            httponly: true
        );

        $expires_at = time() + 10;

        $auth_token = encrypt(json_encode([
            'user_id' => $user->id,
            'expires_at' => $expires_at,
        ]));

        return [
            'auth_token' => $auth_token,
            'expires_at' => $expires_at,
            'refresh_token' => $user->refresh_token,
        ];
    }
}
