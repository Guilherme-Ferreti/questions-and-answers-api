<?php

namespace App\Http\Resources;

use App\Models\User;

class UserResource
{
    public static function toArray(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'created' => $user->created,
            'updated' => $user->updated,
        ];
    }
}
