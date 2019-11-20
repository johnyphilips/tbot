<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 28/03/2019
 * Time: 16:51
 */
class bitcoin_service extends staticBase
{
    const   PAYMENT_STATUS_NEW = 0;
    const   PAYMENT_STATUS_NO_CONFIRMATIONS = 1;
    const   PAYMENT_STATUS_CONFIRMED = 2;
    const   PAYMENT_STATUS_CANCELLED = 3;
    const   WAIT_FOR_PAYMENT = 60 * 60; //in seconds
    const   MIN_CONFIRMATIONS = 3;

    public static function generateWallet()
    {
        return bitcoin_api::generateAddress();
    }

    public static function createPayment($user, $sum, $btc_sum, $coupon_id = null)
    {
        if(!$address = self::generateWallet()) {
            return false;
        }
        $payment = [
            'status_id' => self::PAYMENT_STATUS_NEW,
            'user_id' => $user['id'],
            'chat_id' => $user['chat_id'],
            'amount' => $sum,
            'amount_btc' => $btc_sum,
            'address' => $address,
            'coupon_id' => $coupon_id,
            'create_date' => tools_class::gmDate()
        ];
        $payment['id'] = self::model('payments')->insert($payment);
        return $payment;
    }

    public static function checkTransactions()
    {
        foreach (self::model('payments')->getByField('status_id', self::PAYMENT_STATUS_NEW, true) as $payment) {
            $res = bitcoin_api::getReceivedByAddress($payment['address'], 0);
            if($res['response'] && $res['status'] === 'success' || DEVELOPMENT_MODE === true) {
                self::model('payments')->insert([
                    'id' => $payment['id'],
                    'pay_date' => gmdate('Y-m-d H:i:s'),
                    'status_id' => self::PAYMENT_STATUS_NO_CONFIRMATIONS
                ]);
            } else {
                if(time() - strtotime($payment['create_date']) >= self::WAIT_FOR_PAYMENT) {
                    self::model('payments')->insert([
                        'id' => $payment['id'],
                        'pay_date' => gmdate('Y-m-d H:i:s'),
                        'status_id' => self::PAYMENT_STATUS_CANCELLED
                    ]);
                    self::render('address', $payment['address']);
                    queue_service::add($payment['chat_id'], self::fetch('queue/payment_cancelled'));
                }
            }
        }
        foreach (self::model('payments')->getByField('status_id', self::PAYMENT_STATUS_NO_CONFIRMATIONS, true) as $payment) {
            $res = bitcoin_api::getReceivedByAddress($payment['address'], self::MIN_CONFIRMATIONS);
            if($res['response'] && $res['status'] === 'success'  || DEVELOPMENT_MODE === true) {
                self::model('payments')->insert([
                    'id' => $payment['id'],
                    'pay_date' => gmdate('Y-m-d H:i:s'),
                    'status_id' => self::PAYMENT_STATUS_CONFIRMED
                ]);
                if(DEVELOPMENT_MODE === true) {
                    $res['response'] = $payment['amount_btc'];
                }
                if($payment['coupon_id']) {
                    $coupon = self::model('coupons')->getById($payment['coupon_id']);
                    balance_service::useCoupon($coupon['id']);
                    self::render('discount', $coupon['profit']);
                }
                balance_service::topUp($payment, $res['response']);
                self::render('sum', $payment['amount']);

//
                $user = self::model('bot_users')->getById($payment['user_id']);
                queue_service::add($payment['chat_id'], self::fetch('queue/topped_up'), null, buttons_class::getMenu($user));
            }
        }
    }

    public static function createWithdrawal($user_id, $sum, $free = 0)
    {
        $withdrawal = [
            'user_id' => $user_id,
            'amount' => $sum,
            'free' => $free,
            'amount_btc' => $sum * balance_service::COIN_COST,
            'create_date' => tools_class::gmDate()
        ];
        $withdrawal['id'] = self::model('withdrawals')->insert($withdrawal);
        return $withdrawal;
    }

    public static function sendFunds($withdrawal)
    {
        if(DEVELOPMENT_MODE === true) {
            $tx_id = 'f702374e18b1334e3ab429c1b5c5df3158f92c0756a080135358ab1d96429499';

        }
        if($tx_id = bitcoin_api::sendBTC($withdrawal['address'], $withdrawal['amount_btc'])['tx_id']) {
            $withdrawal['tx_id'] = $tx_id;
            self::model('withdrawals')->insert([
                'id' => $withdrawal['id'],
                'tx_id' => $tx_id
            ]);
            return $tx_id;
        }
        return false;
    }

    public static function validateBTCAddress($address)
    {
        return bitcoin_api::validateAddress($address);
    }

    public static function getProfits($sum, $address)
    {
        if($tx_id = bitcoin_api::sendBTC($address, $sum)['tx_id']) {
            $withdrawal = [
                'address' => $address,
                'amount' => $sum,
                'tx_id' => $tx_id,
                'create_date' => tools_class::gmDate()
            ];
            $withdrawal['id'] = self::model('withdrawals')->insert($withdrawal);
            $transaction = bitcoin_api::getTransaction($tx_id);
            if($tx_fee = $transaction['response']['fee']) {
                self::model('withdrawals')->insert([
                    'id' => $withdrawal['id'],
                    'commission' => $tx_fee
                ]);
            }
            return true;
        }
        return false;
    }
}