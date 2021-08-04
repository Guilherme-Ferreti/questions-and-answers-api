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

    public static function createAuthToken(int $user_id): array
    {
        $expires_at = time() + 10;

        $payload = [
            'user_id' => $user_id,
            'expires_at' => $expires_at,
        ];

        return [
            'value' => encrypt(json_encode($payload)),
            'expires_at' => $expires_at,
        ];
    }

    public static function updateRefreshToken(User $user): string
    {
        $user->refresh_token = md5(uniqid(rand(), true));

        $user->update();

        return $user->refresh_token;
    }
}
