<?php

namespace EnglishTochka\Db;

class Mysql extends Interfaces {

    /**
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $database
     * @throws \Exception
     */
    public function __construct(string $hostname = 'localhost', string $username = '', string $password = '', string $database = '')
    {
        $this->config['hostname'] = $hostname;
        $this->config['username'] = $username;
        $this->config['password'] = $password;
        $this->config['database'] = $database;

        $this->connect();
        $this->setCharset();
    }

    /**
     * Создает подключчение к БД
     * @return void
     * @throws \Exception
     */
    protected function connect(){
        $this->connect = \mysqli_connect("mysql", "app_user", "ak#rDb5dLg", "mburnaevg4_fulls");

        if ($this->connect == false){
            throw new \Exception("Ошибка: Невозможно подключиться к MySQL " . \mysqli_connect_error());
        }
    }

    /**
     * Устанавливает кодировку работы с БД
     * @param string $charset
     * @return bool
     */
    public function setCharset(string $charset = 'utf8'): bool
    {
        return \mysqli_set_charset($this->connect, $charset);
    }

    /**
     * Делает запрос к БД
     * @param string $sql
     * @return \mysqli_result
     * @throws \Exception
     */
    public function query(string $sql): \mysqli_result
    {
        $result = \mysqli_query($this->connect, $sql);

        if ($result == false) {
            throw new  \Exception("Произошла ошибка при выполнении запроса" . \mysqli_error($this->connect));
        }

        return $result;
    }

    /**
     * Запрос на insert
     * @param string $sql
     * @return string|int
     * @throws \Exception
     */
    public function insert(string $sql): bool
    {
        $result = \mysqli_query($this->connect, $sql);

        if ($result == false) {
            throw new  \Exception("Произошла ошибка при выполнении запроса" . \mysqli_error($this->connect));
        }

        return true;
    }

    /**
     * запрос на select
     * @param string $sql
     * @return array
     * @throws \Exception
     */
    public function select(string $sql, array $stmt_params = []): array
    {
        $ret = [];

        $stmt = $this->connect->prepare($sql);

        if(!empty($stmt_params)) {
            $stmt->bind_param(
                array_reduce(
                    $stmt_params,
                    function ($carry, $item) {
                        return $carry . $item['type'];
                    },
                    ''
                ),
                ...array_map(
                    function ($v) {
                        return $v['value'];
                    },
                    $stmt_params
                )
            );
        }

        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row;
        }

        return $ret;
    }
}