<?php

namespace EnglishTochka\Db;

class Factory {

    public static function init(string $type, array $config)
    {
        switch ($type){
            case 'mysql':
                return new Mysql(
                    $config['hostname'] ?? '',
                    $config['username'] ?? '',
                    $config['password'] ?? '',
                    $config['database'] ?? '',
                );
        }
    }
}