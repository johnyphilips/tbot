<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 29/05/2019
 * Time: 15:29
 */
class api_project extends controller
{
    const NONCE_LIFETIME = 60;
    public function generateToken($params)
    {
        $alg = 'sha256';
        $header = [
            'alg' => $alg,
            'typ' => 'jwt'
        ];
        $payload = [
            'body' => $params,
            'nonce' => time()
        ];
        $body = base64_encode(json_encode($header)) . '.' . base64_encode(json_encode($payload));
        $signature = base64_encode(hash_hmac($alg, $body, API_SECRET));
        return $body  . '.' .  $signature;
    }

    public function checkToken($token)
    {
        $arr = explode('.', $token);
        $header = json_decode(base64_decode($arr[0]), true);
        if($header['typ'] === 'jwt') {
            $payload = json_decode(base64_decode($arr[1]), true);
            $signature = base64_decode($arr[2]);
            $body = $arr[0] . '.' . $arr[1];
            $signature2 = hash_hmac($header['alg'], $body, API_SECRET);
            if($signature === $signature2 && self::checkNonce($payload['nonce'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function checkNonce($nonce)
    {
        return true;
        $time = array_pop(explode('_', $nonce));
        $model = new redis_class();
        if((time() - $time) > self::NONCE_LIFETIME) {
            $model->set($nonce, 1);
            return false;
        }
        $nonce = $model->get($nonce);
        if($nonce) {
            $model->set($nonce, 1);
            return false;
        }
        $model->set($nonce, 1);
        return true;
    }

    public function checkAuth($rules)
    {
        if($token = $this->request->getHeader('Authorization')) {
            if($this->checkToken($token)) {
                registry::set('auth', true);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}