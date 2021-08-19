<?php

namespace App\Collections;

use App\Models\User;
use App\Models\Answer;

class AnswerCollection extends BaseCollection
{
    public function getType(): string
    {
        return Answer::class;
    }

    public function loadUsers(): Self
    {
        $ids = array_map(fn ($answer) => $answer->user_id, $this->data);

        if (count($ids) < 1) {
            return $this;
        }

        $users = User::allWhereIdIn(array_unique($ids));

        foreach ($this->data as $answer) {
            $answer->user = $users->filter(fn ($user) => $user->id == $answer->user_id)->first();
        }

        return $this;
    }
}
