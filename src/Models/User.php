<?php

namespace App\Models;

use App\Collections\UserCollection;

class User extends BaseModel
{
    public static function allWhereIdIn(array $ids): UserCollection
    {
        $results = db()->select('SELECT * FROM users WHERE id IN (' . implode(',', $ids) . ')');

        return new UserCollection(array_map(fn ($row) => new User($row), $results));
    }

    public static function findById($id): User|false
    {
        $results = db()->select('SELECT * FROM users WHERE id = :id', [':id' => $id]);

        if (empty($results)) {
            return false;
        }

        return new User($results[0]);
    }

    public static function findByEmail(string $email): User|false
    {
        $results = db()->select('SELECT * FROM users WHERE email = :email', [':email' => $email]);

        if (empty($results)) {
            return false;
        }

        return new User($results[0]);
    }

    public static function findByRefreshToken(string $refresh_token): User|false
    {
        $results = db()->select('SELECT * FROM users WHERE refresh_token = :refresh_token LIMIT 1', 
            [':refresh_token' => $refresh_token]
        );

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

    public function update(): bool
    {
        return db()->query('
            UPDATE users 
            SET 
                username = :username,
                email = :email,
                refresh_token = :refresh_token
            WHERE
                id = :id
            ', 
            [
                ':username' => $this->username,
                ':email' => $this->email,
                ':refresh_token' => $this->refresh_token,
                ':id' => $this->id,
            ]
        );
    }

    public function refresh()
    {
        return $this->findById($this->id);
    }
}
