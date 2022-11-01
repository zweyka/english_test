<?php
spl_autoload_register(function ($class) {
    $prefix = 'EnglishTochka\\';

    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$db = \EnglishTochka\Db\Factory::init(
    'mysql',
    [
        'mysql',
        'app_user',
        'ak#rDb5dLg',
        'mburnaevg4_fulls'
    ]
);