<?php

namespace App\Http\Resources;

use App\Collections\QuestionCollection;
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
            $attributes['topics'] = TopicResource::collection($question->topics);
        }

        return $attributes;
    }

    public static function collection(QuestionCollection $questions) 
    {
        $array = [];

        foreach ($questions as $question) {
            $array[] = self::toArray($question);
        }

        return $array;
    } 
}
