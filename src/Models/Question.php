<?php

namespace App\Models;

class Question extends BaseModel
{
    public static function findById($id)
    {
        $result = db()->select('SELECT * FROM questions WHERE id = :id LIMIT 1', [':id' => $id]);

        if (empty($result)) {
            return false;
        }

        return new Question($result[0]);
    }

    public static function create(array $attributes)
    {
        $question = new Question($attributes);

        $question->save();

        return $question;
    }

    public function save(): bool
    {
        db()->query('INSERT INTO questions(body, user_id) VALUES (:body, :user_id)', [
            'body' => $this->body,
            'user_id' => $this->user_id,
        ]);

        $this->id = db()->lastInsertId();

        return true;
    }

    public function refresh(): self
    {
        return $this->findById($this->id);
    }
}
