<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 07/04/2019
 * Time: 16:29
 */
class bitcoin_api extends staticApi
{
    const NONCE_LIFETIME = 30;
    const NONCE_TIMEOUT = 60;
    public static function generateAddress()
    {
        return self::sendRequest('addresses/generate')['address'];
    }

    public static function getAddressList()
    {
        return self::sendRequest('addresses/list');
    }

    public static function getWalletInfo()
    {
        return self::sendRequest('wallet/info');
    }

    public static function getReceivedByAddress($address, $min_confirmations = 2)
    {
        return self::sendRequest('addresses/received', ['address' => $address, 'min_confirmations' => $min_confirmations]);
    }

    public static function sendBTC($to, $amount, $tx_fee = 0.00001)
    {
        return self::sendRequest('addresses/send', ['to' => $to, 'amount' => $amount, 'tx_fee' => $tx_fee]);
    }

    public static function validateAddress($address)
    {
        $res = self::sendRequest('addresses/validate', ['address' => $address]);
        if(null === $res) {
            return null;
        }
        return $res['status'] === 'success';
    }

    public static function getTransaction($tx_id)
    {
        $res = self::sendRequest('transactions/info', ['tx_id' => $tx_id]);
        if(null === $res) {
            return null;
        }
        return $res;
    }

    public static function fake($last_block)
    {
        $res = self::sendRequest('fake/proceed', ['last_block' => $last_block]);
        if(null === $res) {
            return null;
        }
        return $res;
    }

    private static function sendRequest($method, $params = [])
    {
        $token = self::generateToken($params);
        $res = self::send(BTC_URL . $method, $params, 'GET', null, [
            'Authorization: ' . $token
        ]);
        if(self::$last_code != 200) {
            self::$last_error = $res;
            notifications_service::createNotification('BTC Service Error', self::$last_error, false);
            return null;
        }
        $res = json_decode($res, true);
        if($res['status'] === 'error') {
            if(!self::$last_error && $res['error']) {
                self::$last_error = $res['error'];
            }
            notifications_service::createNotification('BTC Service Error', self::$last_error, false);
            return null;
        }

        return $res;
    }

    private static function generateToken($params)
    {
        $alg = 'sha256';
        $header = [
            'alg' => $alg,
            'typ' => 'jwt'
        ];
        $payload = [
            'body' => $params,
            'nonce' => time() . '_' . rand() . rand()
        ];
        $body = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($payload));
        $signature = base64_encode(hash_hmac($alg, $body, BTC_SECRET));
        return $body  . '.' .  $signature;
    }

    public static function checkToken($token)
    {
        $arr = explode('.', $token);
        $header = json_decode(base64_decode($arr[0]), true);
        if($header['typ'] === 'jwt') {
            $payload = json_decode(base64_decode($arr[1]), true);
            $signature = base64_decode($arr[2]);
            $body = $arr[0] . '.' . $arr[1];
            $signature2 = hash_hmac($header['alg'], $body, BTC_SECRET);
            if($signature === $signature2 && self::checkNonce($payload['nonce'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private static function checkNonce($nonce)
    {
        $model = new redis_class();
        if((time() - $nonce) > self::NONCE_LIFETIME) {
            $model->set(time(), 1, self::NONCE_TIMEOUT);
            echo $model->get($nonce);
            return false;
        }
        $nonce = $model->get($nonce);
        if($nonce) {
            $model->set(time(), 1, self::NONCE_TIMEOUT);
            echo $model->get($nonce);
            return false;
        }
        $model->set(time(), 1, self::NONCE_TIMEOUT);
        return true;
    }

    public static function getBTCRate()
    {
        $res = self::send('https://api.coinmarketcap.com/v1/ticker/bitcoin/');
        $res = json_decode($res, 1);
        if($res[0]['price_btc']) {
            return $res[0]['price_usd'];
        }
        return false;
    }
}