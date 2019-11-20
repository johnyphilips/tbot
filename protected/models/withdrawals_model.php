<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 27/08/2019
 * Time: 18:32
 */
class withdrawals_model extends model
{
    public function countWithdrawalsByDate($date)
    {
        $stm = $this->pdo->prepare('
            SELECT 
                SUM(amount_btc) qty
            FROM
                withdrawals
            WHERE 
                tx_id IS NOT NULL
                    AND 
                DATE(create_date) = :date
        ');
        $res = $this->get_row($stm, ['date' => $date])['qty'];
        return $res ? $res : 0;
    }

    public function countTotalWithdrawals()
    {
        $stm = $this->pdo->prepare('
            SELECT 
                SUM(amount_btc) qty
            FROM
                withdrawals
            WHERE 
                tx_id IS NOT NULL
        ');
        $res = $this->get_row($stm)['qty'];
        return $res ? $res : 0;
    }

    public function count30DaysWithdrawals()
    {
        $stm = $this->pdo->prepare('
            SELECT 
                DATE(create_date) date, 
                SUM(amount_btc) sum
            FROM
                withdrawals
            WHERE
                tx_id IS NOT NULL
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
