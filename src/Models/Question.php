<?php

namespace App\Models;

use App\Collections\TopicCollection;
use App\Collections\QuestionCollection;

class Question extends BaseModel
{
    public static function all(): QuestionCollection
    {
        $results = db()->select('SELECT * FROM questions');

        return new QuestionCollection(array_map(fn ($row) => new Question($row), $results));
    }
    
    public static function findById($id)
    {
        $result = db()->select('SELECT * FROM questions WHERE id = :id LIMIT 1', [':id' => $id]);

        return empty($result) 
                ? false
                : new Question($result[0]);
    }

    public static function create(array $attributes)
    {
        $question = new Question($attributes);

        db()->beginTransaction();

        $question->save();
        $question->syncTopics();

        db()->commit();

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

    public function loadUser(): self
    {
        $this->user = User::findById($this->user_id);

        return $this;
    }

    public function loadTopics(): self
    {
        $this->topics = $this->getTopicsFromQuestionIds($this->id);

        return $this;
    }

    public static function getTopicsFromQuestionIds(int|array $ids): TopicCollection
    {
        $results = db()->select('
            SELECT 
                topics.*,
                topic_question.question_id AS question_id
            FROM
                topics
                    INNER JOIN
                topic_question ON topics.id = topic_question.topic_id
            WHERE 
                topic_question.question_id IN (' . implode(', ', (array) $ids) . ')
        ');

        return new TopicCollection(array_map(fn ($row) => new Topic($row), $results));
    }

    public function syncTopics(): bool
    {
        db()->query('DELETE FROM topic_question WHERE question_id = :id', [':id' => $this->id]);

        $query = 'INSERT INTO topic_question (question_id, topic_id) VALUES ';

        foreach ($this->topics as $topic) {
            $query .= "({$this->id},$topic),";
        }

        $query = rtrim($query, ',');

        return db()->query($query);
    }
}
