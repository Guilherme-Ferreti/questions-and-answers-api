<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    const AUTHENTICATED_USER = 'authenticated_user';

    public static function login(string $email, string $password): User|false
    {
        $user = User::findByEmail($email);

        if (! $user || ! password_verify($password, $user->password)) {
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

        $expires_at = time() + 60 * 15;

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

    public static function getUserFromToken(string $token): User|false
    {
        $token = json_decode(decrypt($token), true);

        if (! $token || $token['expires_at'] < time()) {
            return false;
        }
        
        return User::findById($token['user_id']);
    }

    public static function user(User $user = null): User|false
    {
        if ($user) {
            $GLOBALS[Self::AUTHENTICATED_USER] = $user;
        }

        return $GLOBALS[Self::AUTHENTICATED_USER];
    }
}
