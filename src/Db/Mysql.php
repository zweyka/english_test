<?php

namespace EnglishTochka\Db;

class Mysql extends Interfaces {

    /**
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param $database
     * @throws Exception
     */
    public function __construct(string $hostname = 'localhost', string $username = '', string $password = '', $database = '')
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
     * @throws Exception
     */
    protected function connect(){
        $this->connect = \mysqli_connect("mysql", "app_user", "ak#rDb5dLg", "mburnaevg4_fulls");

        if ($this->connect == false){
            throw new Exception("Ошибка: Невозможно подключиться к MySQL " . \mysqli_connect_error());
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
     * @return mysqli_result
     * @throws Exception
     */
    public function query(string $sql): \mysqli_result
    {
        $result = \mysqli_query($this->connect, $sql);

        if ($result == false) {
            throw new  Exception("Произошла ошибка при выполнении запроса" . \mysqli_error($this->connect));
        }

        return $result;
    }

    /**
     * Запрос на insert
     * @param string $sql
     * @return string|int
     * @throws Exception
     */
    public function insert(string $sql): bool
    {
        $result = \mysqli_query($this->connect, $sql);

        if ($result == false) {
            throw new  Exception("Произошла ошибка при выполнении запроса" . \mysqli_error($this->connect));
        }

        return true;
    }

    /**
     * запрос на select
     * @param string $sql
     * @return array
     * @throws Exception
     */
    public function select(string $sql): array
    {
        $result = $this->query($sql);

        return \mysqli_fetch_all($result, \MYSQLI_ASSOC);
    }
}