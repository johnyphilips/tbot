<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 24.10.17
 * Time: 1:33
 */
class authorization extends base
{
    public static function generateToken($user_id)
    {
        $alg = 'sha256';
        $header = [
            'alg' => $alg,
            'typ' => 'jwt'
        ];
        $payload = [
            'user' => $user_id,
            'exp' => time() + 3660
        ];
        $body = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($payload));
        $signature = base64_encode(hash_hmac($alg, $body, APP_SECRET));
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
            $signature2 = hash_hmac($header['alg'], $body, APP_SECRET);
            if($signature === $signature2 && time() < $payload['exp']) {
                return $payload['user'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}