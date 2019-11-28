<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 27/11/2019
 * Time: 20:05
 */
class paykassa_api extends staticApi
{
    const base_url = 'https://paykassa.pro/api/0.5/index.php';
    const sci_url = 'https://paykassa.pro/sci/0.4/index.php';
    public static function getBalance()
    {
        $res = self::sendRequest('api_get_shop_balance')['data']['bitcoin_btc'];
        if($res) {
            return [
                'response' => ['balance' => $res]
            ];
        }
        return false;
    }

    public static function validateBTCAddress($address)
    {
        $url = 'https://blockexplorer.com/api/addr/' . $address;
        $send = self::send($url);
        $res = json_decode($send, true);
        return isset($res['addrStr']);
    }

    public static function generateAddress($payment_id, $sum)
    {
        $params = [
            'order_id' => $payment_id,
            'amount' => $sum,
            'currency' => 'BTC',
            'system' => 11,
            'test' => DEVELOPMENT_MODE,
            'phone' => false
        ];
        $res = self::sendRequest('sci_create_order_get_data', $params, true);
        self::writeLog('test_req', $res);
        if($res['data']['wallet']) {
            return $res['data']['wallet'];
        }
        return false;
    }

    public static function checkTransaction($hash)
    {
        $params = [
            'test' => DEVELOPMENT_MODE,
            'private_hash' => $hash
        ];
        $res = self::sendRequest('sci_confirm_order', $params, true);
        self::writeLog('test_req', $res);
        self::writeLog('test_req', $res['error']);
        self::writeLog('test_req', $res['data']['amount']);
        if($res['error'] === false && $res['data']['amount']) {
            return $res['data']['amount'];
        }
        return false;
    }

    public static function sendBTC($to, $amount)
    {
        $params = [
            'test' => DEVELOPMENT_MODE,
            'amount' => $amount,
            'currency' => 'BTC',
            'system' => 11,
            'real_fee' => true,
            'priority' => 'low',
            'wallet' => $to
        ];
        $res = self::sendRequest('api_payment', $params);
        if($res['error'] === false && $res['data']) {
            return $res['data'];
        }
        return false;
    }

    private static function sendRequest($function, $params = [], $merch = false)
    {
        if(!$merch) {
            $params['api_id'] = PAYKASSA_API_ID;
            $params['api_key'] = PAYKASSA_API_KEY;
            $params['shop'] = PAYKASSA_MERCHANT_ID;
        } else {
            $params['sci_id'] = PAYKASSA_MERCHANT_ID;
            $params['sci_key'] = PAYKASSA_MERCHANT_KEY;
        }
        $url = $merch ? self::sci_url : self::base_url;
        $params['func'] = $function;
        self::writeLog('test_req', $params);
        return json_decode(self::send($url, $params, 'POST'), true);
    }


}