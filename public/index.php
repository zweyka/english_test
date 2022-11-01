<?php

include_once "../src/init.php";

$user = null;
$products = [];
$error = "";

// Для теста верстки - показываем пользователя по умолчанию
if(!isset($_GET['user'])){
    $_GET['user'] = 'test1';
}

if(!empty($_GET['user'])) {
    try {
        $user_factory = new EnglishTochka\User\Factory($db);
        $user = $user_factory->by_login($_GET['user']);

        $products = array_map(
            function ($v) use ($user) {
                $v = array_merge(
                    $v,
                    [
                        'title_preapre' => [
                            'title' => $v['description'],
                            'percent' => ''
                        ],
                        'buy_link' => '/buy.php?' . http_build_query(
                            [
                                'user' => $user->get_id(),
                                'product' => $v['id']
                            ]
                        )
                    ]
                );

                if (preg_match('/^([0-9]{1,3}%) (.*)/', $v['description'], $matches)) {
                    $v['title_preapre']['title'] = $matches[2] ?? '';
                    $v['title_preapre']['percent'] = (count($matches) == 3) ? $matches[1] : '';
                }

                return $v;
            },
            $user->get_products()
        );
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include "template.html";