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
    const UPDATE_PROFIT_PER_DAY = 8;
    const MIN_WITHDRAW = 0.0001;
    const FORWARD_PRIZE_PERCENT = 3;
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

    public static function getPlanBySum($sum, $payment = null)
    {
        if($payment) {
            $sum += $payment['paid'];
        }
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
                    if($referrer_2 = self::model('bot_users')->getById($referrer_1['referrer_id'])) {
                        $res[] = [
                            'id' => $referrer_2['id'],
                            'chat_id' => $referrer_2['chat_id'],
                            'level' => 2
                        ];
                        if($referrer_2['referrer_id']) {
                            if($referrer_3 = self::model('bot_users')->getById($referrer_2['referrer_id'])) {
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

    public static function forwardWithdrawalPrize($tx_id)
    {
        if($withdrawal = self::model('withdrawals')->getByField('tx_id', $tx_id)) {
            if(!$withdrawal['forward_prize']) {
                $user = self::model('bot_users')->getByid($withdrawal['user_id']);
                self::model('withdrawals')->insert([
                    'id' => $withdrawal['id'],
                    'forward_prize' => $user['id']
                ]);
                $sum = round($withdrawal['amount_btc'] / 100 * self::FORWARD_PRIZE_PERCENT, 8);
                deposit_service::balancePlus($user['id'], $sum);
                self::render('sum', bitcoin_service::formatBTC($sum));
                $message = self::fetch('queue/forward_withdrawal_prize');
                $user['balance'] += $sum;
                queue_service::add($user['chat_id'], $message, null, buttons_class::getMenu($user));
                return true;
            }
        }
        return false;
    }

    public static function referrerPayout($payment, $referrer, $sum, $referral_name)
    {
        $user = self::model('bot_users')->getById($referrer['id']);
        $auto = false;
        $amount = round( $sum/100 * self::REFERRER_PAYOUTS[$referrer['level']], 8);
        if($referrer['level'] == 1 && $user['wallet'] && self::getConfig('auto_ref')) {
            $withdrawal = bitcoin_service::createWithdrawal($referrer['id'], $amount);
            $withdrawal['address'] = $user['wallet'];
            self::model('withdrawals')->insert($withdrawal);
            if($withdrawal['tx_id'] = bitcoin_service::sendFunds($withdrawal)) {
                self::render('withdrawal', $withdrawal);
                self::render('sum', $withdrawal['amount_btc']);
                self::render('tx_id', $withdrawal['tx_id']);
                $auto = true;
            }
        } else {
            self::balancePlus($referrer['id'], $amount);
        }
        if(!$auto) {
            self::balancePlus($referrer['id'], $amount);
            $user = self::model('bot_users')->getById($referrer['id']);
        }
        $row = [
            'user_id' => $referrer['id'],
            'referral_id' => $payment['user_id'],
            'payment_id' => $payment['id'],
            'amount_btc' => $amount,
            'referrer_level' => $referrer['level'],
            'create_date' => tools_class::gmDate()
        ];
        self::model('referral_payouts')->insert($row);
        self::render('sum', $amount);
        self::render('user_name', $referral_name);
        self::render('payout', $row);
        self::render('referral_link', tools_class::getReferralLink($referrer));
        self::render('auto', $auto);
        queue_service::add($referrer['chat_id'], self::fetch('templates/bot/en/queue/referral_payout', true), null, buttons_class::getMenu($user));
        if($auto) {
            queue_service::add($user['chat_id'], self::fetch('templates/bot/en/withdraw/withdrawal_success', true));
            queue_service::add($user['chat_id'], self::fetch('templates/bot/en/queue/forward', true));
            queue_service::add(WITHDRAWAL_CHANNEL, self::fetch('templates/bot/en/queue/withdrawal_channel', true));
        }
    }

    public static function topUp($payment, $sum)
    {
        if($sum > 1) {
            $plan  = self::PLANS['professional'];
            $plan['name'] = 'Professional';
        } else {
            $plan = self::getPlanBySum($sum);
        }
        if($plan) {
            if(self::createDeposit($sum, $payment, $plan)) {
                $user = self::model('bot_users')->getById($payment['user_id']);
                foreach (self::getReferrers($user) as $referrer) {
                    self::referrerPayout($payment, $referrer, $sum, $user['t_user_name']);
                }
                return true;
            }
        } else {
            self::model('payments')->insert([
                'id' => $payment['id'],
                'status_id' => bitcoin_service::PAYMENT_STATUS_LOW
            ]);
            self::render('sum', $sum);
            $buttons['en'] = [
                [['text' => 'Deposit Lacking Funds',  'callback_data' => 'deposit@/deposit_' . $payment['id']]],
            ];
            queue_service::add($payment['chat_id'], self::fetch('templates/bot/en/queue/min_sum', true), $buttons);
            return false;
        }
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
            'amount' => bitcoin_service::btcToUsd($sum),
            'last_profit' => time(),
            'create_date' => tools_class::gmDate()
        ];
        $deposit['id'] = self::model('deposits')->insert($deposit);
        return $deposit;
    }

    public static function makeProfits()
    {
        $deposits = self::model('deposits')->getProfitDeposits();
        foreach ($deposits as $deposit) {
            $plan = self::PLANS[strtolower($deposit['plan'])];
            $user = self::model('bot_users')->getById($deposit['user_id']);
            self::render('deposit', $deposit);
            self::writeLog('test', time() - strtotime($deposit['create_date']));
            if(time() - strtotime($deposit['create_date']) >= $plan['term'] * 24 * 3600) {
                self::model('deposits')->insert([
                    'id' => $deposit['id'],
                    'status_id' => 2
                ]);
                self::balancePlus($deposit['user_id'], $deposit['amount_btc']);
                $user['balance'] += $deposit['amount_btc'];
                queue_service::add($deposit['chat_id'], self::fetch('queue/deposit_closed'), null, buttons_class::getMenu($user));
                break;
            }
            $profit = round(($deposit['amount_btc'] / 100 * $plan['percent']) / self::UPDATE_PROFIT_PER_DAY, 8);
            self::balancePlus($deposit['user_id'], $profit);
            self::model('deposits')->insert([
                'id' => $deposit['id'],
                'profit' => $deposit['profit'] + $profit,
                'last_profit' => time()
            ]);
            self::model('profits')->insert([
                'user_id' => $user['id'],
                'deposit_id' => $deposit['id'],
                'amount_btc' => $profit,
                'create_date' => tools_class::gmDate()
            ]);
            self::render('profit', $profit);
            queue_service::add($deposit['chat_id'], self::fetch('queue/profit'), null, buttons_class::getMenu($user));
        }
    }

}