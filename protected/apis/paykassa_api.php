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
        self::writeLog('test', $send);
        self::writeLog('test', $res);
        return isset($res['addrStr']);
    }

    public static function generateAddress($payment_id)
    {
        $params = [

        ];
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
        $params['func'] = $function;
        return json_decode(self::send(self::base_url, $params, 'POST'), true);
    }


}