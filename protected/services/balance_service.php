<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 13/07/2019
 * Time: 20:15
 */
class balance_service extends staticBase
{
    const MIN_TOPUP = '200';
    const MIN_TOPUP_PRIZE = '200';
    const MIN_WITHDRAW = '550';
    const MIN_FREE_WITHDRAW = '100';
    const COIN_COST = '0.000001';
    const DISCOUNT = 10;
    const REFERRAL_PERCENT = 5;
    const FORWARD_PRIZE = 30;
    const COUPON_STATUS_NEW = 0;
    const COUPON_STATUS_ACTIVATED = 1;
    const COUPON_STATUS_USED = 2;
    const COUPON_STATUS_EXPIRED = 3;
    const COUPON_EXPIRATION_TIME = 10 * 3600;
    const COUPON_REMINDER_TIME = 7 * 3600;
    public static function balancePlus($user_id, $sum, $bonus = 0, $demo = false)
    {
        $user = self::model('bot_users')->getById($user_id);
        $field = $demo ? 'demo_balance' : 'balance';
        if(self::model('bot_users')->insert([
            'id' => $user['id'],
            $field => $user[$field] + $sum,
            'bonuses_constant' => $user['bonuses_constant'] + $bonus,
            'bonus' => $user['bonus'] + $bonus
        ])) {
            return true;
        }
        return false;
    }

    public static function balanceMinus($user_id, $sum, $demo = false)
    {
        $user = self::model('bot_users')->getById($user_id);
        $field = $demo ? 'demo_balance' : 'balance';
        if($sum <= $user[$field]) {
            if(self::model('bot_users')->insert([
                'id' => $user['id'],
                $field => $user[$field] - $sum
            ])) {
                return true;
            }
        }
        return false;
    }

    public static function prizePlus($user_id, $sum, $free = false)
    {
        $user = self::model('bot_users')->getById($user_id);
        if($free) {
            if(self::model('bot_users')->insert([
                'id' => $user['id'],
                'free_lottery_prize' => $user['free_lottery_prize'] + $sum
            ])) {
                return true;
            }
        } else {
            if(self::model('bot_users')->insert([
                'id' => $user['id'],
                'prize_balance' => $user['prize_balance'] + $sum
            ])) {
                return true;
            }
        }
        return false;
    }

    public static function prizeMinus($user_id, $sum, $free = false)
    {
        $user = self::model('bot_users')->getById($user_id);
        if($free) {
            if($sum <= $user['free_lottery_prize']) {
                $row = [
                    'id' => $user['id'],
                    'free_lottery_prize' => $user['free_lottery_prize'] - $sum
                ];
                if(self::model('bot_users')->insert($row)) {
                    return true;
                }
            }
        } else {
            if($sum <= $user['prize_balance']) {
                $row = [
                    'id' => $user['id'],
                    'prize_balance' => $user['prize_balance'] - $sum
                ];
                if(self::model('bot_users')->insert($row)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function topUp($payment, $btc_sum, $coupon = null)
    {
        if($payment['amount_btc'] == $btc_sum) {
            $sum = $payment['amount'];
        } else {
            $sum = floor($btc_sum/self::COIN_COST);
        }
        if($coupon) {
            $sum += $sum / 100 * $coupon['profit'];
        }
        self::balancePlus($payment['user_id'], $sum);
        $user = self::model('bot_users')->getById($payment['user_id']);
        if($user['referrer_id'] && $user['referrer_id'] != 1) {
            if($referrer = self::model('bot_users')->getById($user['referrer_id'])) {
                $referral_sum = floor($sum / 100 * self::REFERRAL_PERCENT);
                self::balancePlus($referrer['id'], $referral_sum);
                self::render('sum', $referral_sum);
                self::render('user_name', $user['t_user_name']);
                queue_service::add($referrer['chat_id'], self::fetch('queue/referral_topup'));
            }
        }
    }

    public static function createCoupon($user_id, $profit)
    {
        $user_coupons = self::model('coupons')->getByField('user_id', $user_id, true);
        foreach ($user_coupons as $user_coupon) {
            if($user_coupon) {
                return false;
            }
        }
        $coupon = [
            'user_id' => $user_id,
            'profit' => $profit,
            'create_date' => tools_class::gmDate()
        ];
        $coupon['id'] = self::model('coupons')->insert($coupon);
        return $coupon;
    }

    public static function activateCoupon($coupon_id)
    {
        self::model('coupons')->insert([
            'id' => $coupon_id,
            'status_id' => self::COUPON_STATUS_ACTIVATED,
            'activated' => tools_class::gmDate()
        ]);
    }

    public static function useCoupon($coupon_id)
    {
        self::model('coupons')->insert([
            'id' => $coupon_id,
            'status_id' => self::COUPON_STATUS_USED,
            'used' => tools_class::gmDate()
        ]);
    }

    public static function checkCoupons()
    {
        foreach (self::model('coupons')->getByField('status_id', self::COUPON_STATUS_ACTIVATED, true) as $item) {
            if(strtotime(tools_class::gmDate()) - strtotime($item['activated']) >= self::COUPON_EXPIRATION_TIME) {
                self::model('coupons')->insert([
                    'id' => $item['id'],
                    'status_id' => self::COUPON_STATUS_EXPIRED
                ]);
                $user = self::model('bot_users')->getById($item['user_id']);
                queue_service::add($user['chat_id'], self::fetch('profile/coupon_expired'));
            }
            if(strtotime(tools_class::gmDate()) - strtotime($item['activated']) >= self::COUPON_REMINDER_TIME) {
                if(!$item['reminder_1']) {
                    self::model('coupons')->insert([
                        'id' => $item['id'],
                        'reminder_1' => 1
                    ]);
                    $user = self::model('bot_users')->getById($item['user_id']);
                    self::render('coupon', $item);
                    self::render('expiration', self::COUPON_EXPIRATION_TIME/3600 - self::COUPON_REMINDER_TIME/3600);
                    $buttons['en'][] = [
                        ['text' => '💰 Buy Bingo Coins',  'callback_data' => 'profile@/topup_btc']
                    ];
                    queue_service::add($user['chat_id'], self::fetch('profile/coupon_reminder'), $buttons);
                }
            }
        }
    }

    public static function checkUserReferrals($user)
    {
//        if(strtotime($user['create_date']) < strtotime('2019-08-23 12:00:00')) {
//            return false;
//        }
        if($user['free_lottery']) {
            return false;
        }
        $qty = lottery_service::countReferrals($user['id']);
        if($qty >= 3 && $user['free_lottery'] == 0) {
            self::model('bot_users')->insert([
                'id' => $user['id'],
                'free_lottery' => 1
            ]);
            queue_service::add($user['chat_id'], self::fetch('queue/free_lottery'));
            return true;
        }
        return false;
    }

    public static function getUserBonus($user_id)
    {
        if($coupon = self::model('coupons')->getByFields([
            'status_id' => self::COUPON_STATUS_ACTIVATED,
            'user_id' => $user_id
        ])) {
            return $coupon;
        }
        return false;
    }

    public static function forwardPrize($chat_id, $edition_id)
    {
        $edition = self::model('editions')->getById($edition_id);
        if($edition && !$edition['forward_prize']) {
            $user = self::model('bot_users')->getByField('chat_id', $chat_id);
            self::model('editions')->insert([
                'id' => $edition['id'],
                'forward_prize' => $user['id']
            ]);
            self::prizePlus($user['id'], self::FORWARD_PRIZE);
            $message = self::fetch('queue/forward_prize');
            queue_service::add($user['chat_id'], $message);
            return true;
        }
        return false;
    }

    public static function forwardWithdrawalPrize($tx_id)
    {
        if($withdrawal = self::model('withdrawals')->getByField('tx_id', $tx_id)) {
            if(!$withdrawal['forward_prize']) {
                $user = self::model('bot_users')->getByid($withdrawal['user_id']);
                self::model('withdrawals')->insert([
                    'id' => $withdrawal['id'],
                    'forward_prize' => $user['id']
                ]);
                self::prizePlus($user['id'], self::FORWARD_PRIZE);
                $message = self::fetch('queue/forward_withdrawal_prize');
                queue_service::add($user['chat_id'], $message);
                return true;
            }
        }
        return false;
    }

    public static function forwardFreePrize($chat_id, $edition_id)
    {
        $edition = self::model('free_editions')->getById($edition_id);
        if($edition && !$edition['forward_prize']) {
            $user = self::model('bot_users')->getByField('chat_id', $chat_id);
            self::model('free_editions')->insert([
                'id' => $edition['id'],
                'forward_prize' => $user['id']
            ]);
            self::prizePlus($user['id'], self::FORWARD_PRIZE);
            $message = self::fetch('queue/forward_prize');
            queue_service::add($user['chat_id'], $message);
            return true;
        }
        return false;
    }
}