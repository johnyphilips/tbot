<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 16/08/2019
 * Time: 00:29
 */
class bot_api extends staticApi
{
    const BASE_URL = 'https://api.telegram.org/bot' . BOT_1 . '/';
    public static function sendRequest($method, array $params = [])
    {
        $url = self::BASE_URL . $method;
        if($params[0]) {
            $url .= '?' . http_build_query($params[0]);
        }
        $res = json_decode(self::send($url), true);
        if($res['ok']) {
            return true;
        } else {
            return false;
        }
    }

    public static function __callStatic($method, $params)
    {
        self::sendRequest($method, $params);
    }
}