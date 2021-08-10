<?php

namespace App\Collections;

use App\Models\User;

class UserCollection extends BaseCollection
{
    public function getType(): string
    {
        return User::class;
    }
}
