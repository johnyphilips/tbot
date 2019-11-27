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

    private static function sendRequest($function, $params = [])
    {
        $params['api_id'] = PAYKASSA_API_ID;
        $params['api_key'] = PAYKASSA_API_KEY;
        $params['shop'] = PAYKASSA_MERCHANT_ID;
        $params['func'] = $function;
        return json_decode(self::send(self::base_url, $params, 'POST'));
    }
}