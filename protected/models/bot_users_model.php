<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 30/03/2019
 * Time: 14:04
 */
class bot_users_model extends model
{
    public function getReferralsReferrals($ids)
    {
        $stm = $this->pdo->prepare('
            SELECT
                b.*,
                sum(d.amount_btc) deposits
            FROM
                bot_users u 
            LEFT JOIN
                deposits d ON u.id = d.user_id
            WHERE
                u.referrer_id IN(' . implode(',', $ids) . ') 
                    AND
                u.status_id = ' . bot_commands_class::USER_ACTIVE_STATUS . '
            GROUP BY u.id
        ');
        return $this->get_all($stm);
    }

    public function count24HoursUsers()
    {
        $stm = $this->pdo->prepare('
            SELECT
                count(id) qty
            FROM
                bot_users
            WHERE
                create_date > NOW() -  INTERVAL 24 HOUR
        ');
        return $this->get_row($stm)['qty'];
    }

    public function getUserTotalWin($user_id)
    {
        $stm = $this->pdo->prepare('
            SELECT 
                sum(l.amount) win
            FROM
                editions e
                        JOIN
                lotteries l on e.lottery_id = l.id
            WHERE
                winner_id = :user_id
        ');
        $res = $this->get_row($stm, ['user_id' => $user_id])['win'];
        $stm = $this->pdo->prepare('
            SELECT 
                sum(won) win
            FROM
                roulettes
            WHERE
                user_id = :user_id AND status_id = ' . roulette_service::STATUS_CLOSED . '
        ');
        $roulette = $this->get_row($stm, ['user_id' => $user_id])['win'];
        $res += $roulette;
        return $res ? $res : 0;
    }

    public function getUserTotalLotteries($user_id)
    {
        $stm = $this->pdo->prepare('
            SELECT 
                e.id
            FROM
                editions e
                        JOIN
                bets b on b.edition_id = e.id
            WHERE
                b.user_id = :user_id
            GROUP BY e.id
        ');
        return count($this->get_all($stm, ['user_id' => $user_id]));
    }

    public function countFreeLotteries($user_id)
    {
        $stm = $this->pdo->prepare('
            SELECT 
                COUNT(u.id) qty
            FROM
                bot_users u
                    JOIN
                free_bets b ON b.user_id = u.id
            WHERE
                u.id = :user_id
        ');
        $res = $this->get_row($stm, ['user_id' => $user_id])['qty'];
        return $res ? $res : 0;
    }

    public function countNewUsersByDate($date)
    {
        $stm = $this->pdo->prepare('
            SELECT 
                COUNT(id) qty
            FROM
                bot_users
            WHERE 
                status_id = 2
                    AND 
                DATE(create_date) = :date
        ');
        $res = $this->get_row($stm, ['date' => $date])['qty'];
        return $res ? $res : 0;
    }

    public function countTotalUsers()
    {
        $stm = $this->pdo->prepare('
            SELECT 
                COUNT(id) qty
            FROM
                bot_users
            WHERE 
                status_id = 2
        ');
        $res = $this->get_row($stm)['qty'];
        return $res ? $res : 0;

    }

    public function count30DaysUsers()
    {
        $stm = $this->pdo->prepare('
            SELECT 
                DATE(create_date) date, 
                COUNT(id) sum
            FROM
                bot_users
            WHERE
                status_id = ' . bot_commands_class::USER_ACTIVE_STATUS . '
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