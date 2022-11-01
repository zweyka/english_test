<?php

namespace EnglishTochka\Db;

abstract class Interfaces
{

    // ресурс подключения к бД
    protected $connect;

    // конфиг для подключения к БД
    protected $config = [
        'hostname' => '',
        'username' => '',
        'password' => '',
        'database' => '',
    ];

    abstract public function __construct(string $hostname = 'localhost', string $username = '', string $password = '', string $database = '');

    abstract protected function connect();

    abstract public function query(string $sql);
    abstract public function insert(string $sql);

}