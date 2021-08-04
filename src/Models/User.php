<?php

namespace App\Models;

class User extends BaseModel
{
    public function findById($id)
    {
        $results = db()->select('SELECT * FROM users WHERE id = :id', [':id' => $id]);

        if (empty($results)) {
            return false;
        }

        return new User($results[0]);
    }
    public static function create(array $attributes = [])
    {
        $user = new User($attributes);

        $user->save();

        return $user->refresh();
    }

    public function save(): bool
    {
        $result = db()->query(
            "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)", 
            [
                ':username' => $this->username,
                ':email' => $this->email,
                ':password' => password_hash($this->password, PASSWORD_DEFAULT),
            ]
        );

        if ($result) {
            $this->id = db()->lastInsertId();
        }

        return $result;
    }

    public function refresh()
    {
        return $this->findById($this->id);
    }
}
