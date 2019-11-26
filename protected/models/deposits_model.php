<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 26/11/2019
 * Time: 16:47
 */
class deposits_model extends model
{
    public function getProfitDeposits()
    {
        $time = time() - (24/deposit_service::UPDATE_PROFIT_PER_DAY) * 3600;
        $stm = $this->pdo->prepare('
            SELECT
                * 
            FROM
                deposits WHERE last_profit <= "' . $time . '" AND status_id = 1
        ');
        return $this->get_all($stm);
    }

    public function getBalancesAndDepositsByDate()
    {
        $stm = $this->pdo->prepare('
            select
            sum(u.balance) balances,
            sum(d.amount_btc) deposits,
            date(d.create_date) date
        FROM
            bot_users u JOIN deposits d on d.user_id = u.id
        GROUP BY date(d.create_date);
        ');
        $res = [];
        $tmp = $this->get_all($stm);
        foreach ($tmp as $item) {
            $res[$item['date']] = [
                'balances' => $item['balances'],
                'deposits' => $item['deposits']
            ];
        }
        return $res;
    }
}