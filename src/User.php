<?php

namespace EnglishTochka;

class User {

    private $user;
    private $db;

    public function __construct(array $user, \EnglishTochka\Db\Interfaces $db)
    {
        $this->user = $user;

        $this->db = $db;
    }

    public function get_id(): int
    {
        return $this->user['id'];
    }

    public function get_login(): string
    {
        return $this->user['login'];
    }

    public function user_orders(): array
    {
        $ret = [];

        try {
            $ret = $this->db->select(
"SELECT
	p.id,
	p.`description`,
	p.price
FROM
	`orders_users` as ou
	JOIN products as p ON (p.id = ou.`product_id`)
WHERE
	user_id = {$this->get_id()}
;"
            );
        } catch (\Exception $e){

        }

        return $ret;
    }

    public function get_products(): array
    {
        $ret = [];

        try {
            $ret = $this->db->select(
"SELECT
    p.id,
    p.`description`,
    p.price,
    IFNULL(ou.user_id, 0) as iuid
FROM
    products as p
     LEFT JOIN `orders_users` as ou ON (p.id = ou.`product_id` AND ou.`user_id` = {$this->get_id()})
;"
            );
        } catch (\Exception $e){

        }

        return array_map(
            function($v){
                $v['is_buy'] = $v['iuid'] != 0;
                unset($v['iuid']);

                return $v;
            },
            $ret
        );
    }

    public function balance(): int
    {
        $ret = 0;

        try {
            $db_ret = $this->db->select(
"SELECT 
    IFNULL(`a1`.`sum`, 0) - IFNULL(`a2`.`price_count`, 0) as `balance`
FROM 
    (
        SELECT SUM(`price`) as `sum`, {$this->get_id()} as `user_id` FROM (
            SELECT distinct `action`, `price` FROM `coins` WHERE `user_id` = {$this->get_id()}
        ) as `x`
    ) as `a1`
    JOIN (
        SELECT
            SUM(`p`.`price`) as `price_count`,
            {$this->get_id()} as `user_id`
        FROM
            `products` as `p`
            RIGHT JOIN `orders_users` as `ou` ON (`ou`.`product_id` = `p`.`id`)
        WHERE
            `ou`.`user_id` = {$this->get_id()}
    ) as `a2` ON (`a1`.`user_id` = `a2`.`user_id`)"
            );

            $ret = (int) ( $db_ret[0]['balance'] ?? $ret );
        } catch (\Exception $e){

        }

        return $ret;
    }

    /**
     * Сумма всех заработанных монел
     * @return int
     */
    public function all_coins_count(): int
    {
        $ret = 0;

        try {
            $db_ret = $this->db->select(
                "SELECT SUM(price) as sum FROM (
	SELECT distinct `action`, `price` FROM coins WHERE user_id = {$this->get_id()}
) as x"
            );

            $ret = (int) ( $db_ret[0]['sum'] ?? $ret );
        } catch (\Exception $e){

        }

        return $ret;
    }

    public function buy_product(int $product_id)
    {
        $product_db = $this->db->select("SELECT `id`, `description`, `price` FROM `products` WHERE `id` = {$product_id};");
        $product = $product_db[0] ?? [];

        if(empty($product)){
            throw new \Exception('Продукт не найден');
        }

        if($this->balance() < $product['price']){
            throw new \Exception('Недостаточно монет для покупки');
        }

        return $this->db->insert("INSERT INTO `orders_users` (`user_id`, `product_id`) VALUES ({$this->get_id()},{$product['id']});");
    }
}