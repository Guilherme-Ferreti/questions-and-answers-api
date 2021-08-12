<?php

namespace App\Collections;

use App\Models\Question;
use App\Models\User;

class QuestionCollection extends BaseCollection
{
    public function getType(): string
    {
        return Question::class;
    }

    public function loadTopics(): self
    {
        $ids = array_map(fn ($question) => $question->id, $this->data);

        $topics = Question::getTopicsFromQuestionIds($ids);

        foreach ($this->data as $question) {
            $question->topics = $topics->filter(fn ($topic) => $topic->question_id == $question->id);
        }

        return $this;
    }

    public function loadUser(): self
    {
        $ids = array_map(fn ($question) => $question->user_id, $this->data);

        $users = User::allWhereIdIn(array_unique($ids));

        foreach ($this->data as $question) {
            $question->user = $users->filter(fn ($user) => $user->id == $question->user_id)->first();
        }
        
        return $this;
    }
}
