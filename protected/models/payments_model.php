<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 27/08/2019
 * Time: 18:32
 */
class payments_model extends model
{
    public function countPaymentsByDate($date)
    {
        $stm = $this->pdo->prepare('
            SELECT 
                SUM(amount_btc) qty
            FROM
                payments
            WHERE 
                status_id = ' . bitcoin_service::PAYMENT_STATUS_CONFIRMED . '
                    AND 
                DATE(create_date) = :date
        ');
        $res = $this->get_row($stm, ['date' => $date])['qty'];
        return $res ? $res : 0;
    }

    public function countTotalPayments()
    {
        $stm = $this->pdo->prepare('
            SELECT 
                SUM(amount_btc) qty
            FROM
                payments
            WHERE 
                status_id = ' . bitcoin_service::PAYMENT_STATUS_CONFIRMED . '
        ');
        $res = $this->get_row($stm)['qty'];
        return $res ? $res : 0;
    }

    public function count30DaysPayments()
    {
        $stm = $this->pdo->prepare('
            SELECT 
                DATE(create_date) date, 
                SUM(amount_btc) sum
            FROM
                payments
            WHERE
                status_id = ' . bitcoin_service::PAYMENT_STATUS_CONFIRMED . '
                    AND DATE(create_date) > DATE(NOW()) - INTERVAL 30 DAY
            GROUP BY DATE(create_date)
            ORDER BY DATE(create_date)
        ');
        $tmp = $this->get_all($stm);
        $res = [];
        foreach ($tmp as $item) {
            $res[$item['date']] = $item['sum'];
        }
        return $res;
    }
}