<?php

include_once "../src/init.php";

$user_id = (int)$_GET['user'];
$product_id = (int)$_GET['product'];

if(empty($user_id) || empty($product_id)){
    echo 'Неверные параметры';
} else {
    try {
        $user_factory = new EnglishTochka\User\Factory($db);
        $user = $user_factory->by_id($user_id);

        if($user->buy_product($product_id) === true){
            echo "Покупка оформленна";
        } else {
            echo 'Ошибка во время покупки';
        }
    } catch (Exception $e){
         echo $e->getMessage();
    }
}

echo "<br /><br /><a href='/index.php" . ( (is_null($user)) ? '' : '?user=' . $user->get_login() ) . "'>Назад</a>";