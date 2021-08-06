<?php

namespace App\Http\Resources;

use App\Models\Question;

class QuestionResource
{
    public static function toArray(Question $question)
    {
        $attributes = [
            'id' => $question->id,
            'title' => $question->title,
            'body' => $question->body,
            'user_id' => $question->user_id,
            'created' => $question->created,
            'updated' => $question->updated,
        ];

        if ($question->user) {
            $attributes['user'] = UserResource::toArray($question->user);
        }

        return $attributes;
    }
}
