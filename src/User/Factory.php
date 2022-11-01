<?php

namespace EnglishTochka\User;

use EnglishTochka\User;

class Factory {

    private $db;

    public function __construct(\EnglishTochka\Db\Interfaces $db)
    {
        $this->db = $db;
    }

    public function by_id(int $id):\EnglishTochka\User
    {
        try {
            $user_db = $this->db->select(
"SELECT
	id,
	name,
	login
FROM
	users
WHERE
	id = {$id}
LIMIT 1
;"
            );

            if(!isset($user_db[0])) {
                throw new \Exception("Пользователь не найден");
            }

            $user = new \EnglishTochka\User($user_db[0], $this->db);
        } catch (\Exception $e){
            throw new \Exception("Пользователь не найден");
        }

        return $user;
    }

    public function by_login(string $login):\EnglishTochka\User
    {
        try {
            $user_db = $this->db->select(
                "SELECT
                        `id`,
                        `name`,
                        `login`
                    FROM
                        `users`
                    WHERE
                        `login` = ?
                    LIMIT 1
                    ;",
                [
                    [
                        'type' => 's',
                        'value' => $login
                    ]
                ]
            );

            if(!isset($user_db[0])) {
                throw new \Exception("Пользователь не найден");
            }

            $user = new \EnglishTochka\User($user_db[0], $this->db);
        } catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return $user;
    }
}