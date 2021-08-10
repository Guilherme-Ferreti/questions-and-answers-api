<?php

namespace App\Collections;

use App\Models\Topic;

class TopicCollection extends BaseCollection
{
    public function getType(): string
    {
        return Topic::class;
    }
}
