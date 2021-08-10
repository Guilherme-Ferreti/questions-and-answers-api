<?php

namespace App\Http\Resources;

use App\Models\Topic;
use App\Collections\TopicCollection;

class TopicResource
{
    public static function toArray(Topic $topic): array
    {
        return [
            'id' => $topic->id,
            'name' => $topic->name,
            'created' => $topic->created,
            'updated' => $topic->updated,
        ];
    }

    public static function collection(TopicCollection $topics) 
    {
        $array = [];

        foreach ($topics as $topic) {
            $array[] = self::toArray($topic);
        }

        return $array;
    } 
}
