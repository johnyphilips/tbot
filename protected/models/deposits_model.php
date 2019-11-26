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
        $stm = $this->pdo->prepare('
            SELECT
                * 
            FROM
                deposits WHERE last_profit <= ' . time() - (24/deposit_service::UPDATE_PROFIT_PER_DAY) * 3600 . '
        ');
        return $this->get_all($stm);
    }
}