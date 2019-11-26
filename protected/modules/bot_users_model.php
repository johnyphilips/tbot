<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 26/11/2019
 * Time: 13:50
 */
class bot_users_model extends model
{
    public function getReferralsReferrals($ids)
    {
        $stm = $this->pdo->prepare('
            SELECT
                *,
                sum(d.amount_btc) deposits
            FROM
                bot_users u 
            LEFT JOIN
                deposits d ON u.id = d.user_id
            WHERE
                u.id IN(' . implode(',', $ids) . ') 
                    AND
                u.status_id = ' . bot_commands_class::USER_ACTIVE_STATUS . '
            GROUP BY u.id
        ');
        return $this->get_all($stm);
    }
}