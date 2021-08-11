<?php

namespace App\Policies;

class QuestionPolicy
{
    public function update($user, $question): bool
    {
        return $user->id == $question->user_id;
    }
}
