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

        if ($question->topics) {
            $attributes['topics'] = array_map(fn ($topic) => $topic->toArray(), $question->topics);
        }

        return $attributes;
    }

    public static function manyToArray($questions) {
        return array_map(fn ($question) => Self::toArray($question), $questions);
    } 
}
