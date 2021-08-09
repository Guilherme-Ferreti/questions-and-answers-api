<?php

namespace App\Models;

class Question extends BaseModel
{
    public static function all()
    {
        $results = db()->select('SELECT * FROM questions');

        return array_map(fn ($row) => new Question($row), $results);
    }
    
    public static function findById($id)
    {
        $result = db()->select('SELECT * FROM questions WHERE id = :id LIMIT 1', [':id' => $id]);

        if (empty($result)) {
            return false;
        }

        $question = new Question($result[0]);

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

    public static function loadUser(Question|array $questions)
    {
        if (! is_array($questions)) {
            $questions = [$questions];
        }

        $ids = array_map(fn ($question) => $question->id, $questions);

        $users = User::AllByIds($ids);

        foreach ($questions as $question) {
            foreach ($users as $user) {
                if ($user->id == $question->user_id) {
                    $question->user = $user;

                    break;
                }
            }
        }
    }

    public static function loadTopics(Question|array $questions): void
    {
        if (! is_array($questions)) {
            $questions = [$questions];
        }

        $ids = array_map(fn ($question) => $question->id, $questions);

        $topics = db()->select('
            SELECT 
                topics.*,
                topic_question.question_id AS question_id
            FROM
                topics
                    INNER JOIN
                topic_question ON topics.id = topic_question.topic_id
            WHERE 
                topic_question.question_id IN (' . implode(', ', $ids) . ')
        ');

        foreach ($questions as $question) {
            foreach ($topics as $topic) {
                if ($topic['question_id'] == $question->id) {
                    $current_topics = $question->topics;

                    $current_topics[] = new Topic($topic);

                    $question->topics = $current_topics;
                }
            }
        }
    }
}
