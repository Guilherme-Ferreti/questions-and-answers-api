<?php

namespace App\Models;

use App\Collections\AnswerCollection;

class Answer extends BaseModel
{
    public function refresh(): self
    {
        $answer = $this->findById($this->id);

        return $this->setAttributes($answer->toArray());
    }

    public function save(): self
    {
        db()->query('INSERT INTO answers(body, question_id, user_id) VALUES (:body, :question_id, :user_id)', [
            ':body' => $this->body,
            ':question_id' => $this->question_id,
            ':user_id' => $this->user_id,
        ]);
        
        $this->id = db()->lastInsertId();

        return $this;
    }

    public static function findById($id): self|false
    {
        $result = db()->select('SELECT * FROM answers WHERE id = :id LIMIT 1', [':id' => $id]);

        return empty($result) 
                ? false
                : new Answer($result[0]);
    }

    public static function allWhereQuestionId($question_id): AnswerCollection
    {
        $results = db()->select('SELECT * FROM answers WHERE question_id = :question_id', [
            ':question_id' => $question_id
        ]);

        return new AnswerCollection(array_map(fn ($row) => new Answer($row), $results));
    }
}
