<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 14.09.17
 * Time: 12:55
 */
class coreApi extends base
{
    protected $last_error;
    public function send($url = '', array $params = array(), $method = 'GET', $type = null, $headers = []) {
        $curl = curl_init($url);
        switch($method) {
            case "GET":
                break;
            case "POST":
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
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            default:
                self::writeLog('EXCHANGE', 'INVALID METHOD ' . $method);
                exit;
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
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