<?php

namespace App\Models;

class Question extends BaseModel
{
    public static function all()
    {
        $results = db()->select('SELECT * FROM questions');

        return array_map(fn($row) => new Question($row), $results);
    }
    
    public static function findById($id)
    {
        $result = db()->select('SELECT * FROM questions WHERE id = :id LIMIT 1', [':id' => $id]);

        if (empty($result)) {
            return false;
        }

        $question = new Question($result[0]);

        $question->loadUser();

        return $question;
    }

    public static function create(array $attributes)
    {
        $question = new Question($attributes);

        $question->save();

        return $question->refresh();
    }

    public function save(): bool
    {
        $result = db()->query('INSERT INTO questions(title, body, user_id) VALUES (:title, :body, :user_id)', [
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => $this->user_id,
        ]);

        if ($result) {
            $this->id = db()->lastInsertId();
        }

        return $result;
    }

    public function update(): bool
    {
        return db()->query('
            UPDATE questions 
            SET 
                title = :title,
                body = :body,
                user_id = :user_id,
                updated = :updated
            WHERE
                id = :id 
            ', 
            [
                'title' => $this->title,
                ':body' => $this->body,  
                ':user_id' => $this->user_id,  
                ':updated' => now(),  
                ':id' => $this->id,  
            ]
        );
    }

    public function delete()
    {
        return db()->query('DELETE FROM questions WHERE id = :id', [':id' => $this->id]);
    }

    public function refresh(): self
    {
        return $this->findById($this->id);
    }

    public function loadUser()
    {
        $this->attributes['user'] = User::findById($this->attributes['user_id']);
    }
}
