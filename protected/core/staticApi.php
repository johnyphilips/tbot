<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 14.09.17
 * Time: 12:55
 */
class staticApi extends staticBase
{
    public static $last_error;
    public static $last_code;
    public static function send($url = '', array $params = array(), $method = 'GET', $type = null, $headers = []) {
        switch($method) {
            case "GET":
                $arr = explode('?', $url);
                if($arr[1]) {
                     parse_str($arr[1], $data);
                    foreach ($data as $k => $datum) {
                        if(!$params[$k]) {
                            $params[$k] = $datum;
                        }
                     }
                }
                if($params) {
                    $arr[0] .= '?' . http_build_query($params);
                }
                $curl = curl_init($arr[0]);
                break;
            case "POST":
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                if($type == 'json') {
                    $data = json_encode($params);
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Content-Length: ' . strlen($data);

                } else {
                    if($params) {
                        $data = http_build_query($params);
                    } else {
                        $data = [];
                    }
                    $headers[] = 'Content-Type:application/x-www-form-urlencoded';
                }
//                $this->writeLog('test', $data);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PATCH":
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "PUT":
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "DELETE":
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            default:
                $curl = curl_init($url);

                self::writeLog('EXCHANGE', 'INVALID METHOD ' . $method);
                exit;
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if(DEVELOPMENT_MODE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $response = curl_exec($curl);
        self::$last_code = curl_getinfo($curl)['http_code'];
        self::$last_error = curl_error($curl);
//        echo $response;
//        $this->writeLog('api_test', $response);
        curl_close($curl);
        return $response;
    }

    public function post($url, $params = [], $headers = [])
    {

    }

    public function json($url, $params = [], $headers = [])
    {
        $response = $this->send($url, $params, 'POST', 'json', $headers);
        if($res = json_decode($response, true)) {
            return $res;
        } else {
            $this->last_error = [
                'incorrect json'
            ];
            return false;
        }
    }
}