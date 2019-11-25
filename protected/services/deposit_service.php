<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 16:20
 */
class deposit_service extends staticBase
{
    const PLANS = [
        'intro' => [
            'percent' => 3,
            'from' => '0.0005',
            'to' => '0.035',
            'term' => 60
        ],
        'popular' => [
            'percent' => 5,
            'from' => '0.035',
            'to' => '0.1',
            'term' => 45
        ],
        'professional' => [
            'percent' => 9,
            'from' => '0.1',
            'to' => '1',
            'term' => 30
        ]
    ];
    const REFERRER_PAYOUTS = [
        1 => 10,
        2 => 2,
        3 => 1
    ];
    static $last_error;

    public static function balancePlus($user_id, $sum)
    {
        $user = self::model('bot_users')->getById($user_id);
        if(self::model('bot_users')->insert([
            'id' => $user['id'],
            'balance' => $user['balance'] + $sum
        ])) {
            return true;
        }
        return false;
    }

    public static function balanceMinus($user_id, $sum)
    {
        $user = self::model('bot_users')->getById($user_id);
        if($sum <= $user['balance']) {
            if(self::model('bot_users')->insert([
                'id' => $user['id'],
                'balance' => $user['balance'] - $sum
            ])) {
                return true;
            }
        }
        return false;
    }

    public static function getPlanBySum($sum)
    {
        if($sum == 1) {
            $plan  = self::PLANS['professional'];
            $plan['name'] = 'Professional';
            return $plan;
        }
        foreach (self::PLANS as $key => $plan) {
            if($sum >= $plan['from'] && $sum < $plan['to']) {
                $plan['name'] = ucfirst($key);
                return $plan;
            }
        }
        return false;
    }

    public static function getReferrers($user)
    {
        $res = [];
        if($user['referrer_id']) {
            if($referrer_1 = self::model('bot_users')->getById($user['referrer_id'])) {
                $res[] = [
                    'id' => $referrer_1['id'],
                    'chat_id' => $referrer_1['chat_id'],
                    'level' => 1
                ];
                if($referrer_1['referrer_id']) {
                    if($referrer_2= self::model('bot_users')->getById($referrer_1['referrer_1'])) {
                        $res[] = [
                            'id' => $referrer_2['id'],
                            'chat_id' => $referrer_2['chat_id'],
                            'level' => 2
                        ];
                        if($referrer_2['referrer_id']) {
                            if($referrer_3 = self::model('bot_users')->getById($referrer_2['referrer_1'])) {
                                $res[] = [
                                    'id' => $referrer_3['id'],
                                    'chat_id' => $referrer_3['chat_id'],
                                    'level' => 3
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $res;
    }

    public static function referrerPayout($payment, $referrer, $sum, $referral_name)
    {
        $amount = round( $sum/100 * self::REFERRER_PAYOUTS[$referrer['level']]);
        self::balancePlus($referrer['id'], $amount);
        $row = [
            'user_id' => $referrer['id'],
            'referral_id' => $payment['user_id'],
            'payment_id' => $payment['id'],
            'amount_btc' => $amount,
            'level' => $referrer['level'],
            'create_date' => tools_class::gmDate()
        ];
        self::model('referral_payouts')->insert($row);
        self::render('sum', $amount);
        self::render('user_name', $referral_name);
        queue_service::add($referrer['chat_id'], self::fetch('queue/referral_payout'));
    }

    public static function topUp($payment, $sum)
    {
        if($plan = self::getPlanBySum($sum) || $sum > 1) {
            if($sum > 1) {
                $plan  = self::PLANS['professional'];
                $plan['name'] = 'Professional';
            }
            if(self::createDeposit($sum, $payment, $plan)) {
                $user = self::model('bot_users')->getById($payment['user_id']);
                foreach (self::getReferrers($user) as $referrer) {
                    self::referrerPayout($payment, $referrer, $sum, $user['t_user_name']);
                }
            }
        }
        self::model('payments')->insert([
            'id' => $payment['id'],
            'status_id' => bitcoin_service::PAYMENT_STATUS_LOW
        ]);
        self::render('sum', $sum);
        $buttons['en'] = [
            [['text' => 'Deposit Funds',  'callback_data' => 'deposit@/deposit_' . $payment['id']]],
        ];
        queue_service::add($payment['chat_id'], self::fetch('queue/min_sum'), $buttons);
        return false;
    }

    public static function createDeposit($sum, $payment, $plan)
    {
        $deposit = [
            'plan' => $plan['name'],
            'user_id' => $payment['user_id'],
            'chat_id' => $payment['chat_id'],
            'payment_id' => $payment['id'],
            'amount_btc' => $sum,
            'last_profit' => time(),
            'create_date' => tools_class::gmDate()
        ];
        $deposit['id'] = self::model('deposits')->insert($deposit);
        return $deposit;
    }

}