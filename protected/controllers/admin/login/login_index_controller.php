<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 24.10.17
 * Time: 12:53
 */
class login_index_controller extends login_helper
{
    public function content()
    {
        $this->view_only('login/index');
    }

    public function login()
    {
        if(!empty($_POST['user_name']) && !empty($_POST['password'])) {
            $ip = ip_class::getIpFromHeaders();
            $in_list = $this->model('ip_list')->getByField('ip', $ip);
            if($in_list['list_type'] == 1) {
                $this->fail(['error' => 'Доступ заблокирован, обратитесь к администратору']);
            }
            if($in_list['list_type'] != 2) {
                if($this->checkAttempts($ip)) {
                    $this->fail(['error' => 'Доступ заблокирован, обратитесь к администратору']);
                }
            }
            if($user = $this->model('system_users')->getByFields([
                    'user_name' => $_POST['user_name'],
                    'user_password' => hash_hmac('sha256', $_POST['password'], APP_SECRET)
                ]
            )) {
                registry::set('user', $user);
                $token = authorization::generateToken($user['id']);
                setcookie('Authorization', $token, time() + 3600 * 24 * 30, '/');
                $this->response->withHeader('Authorization', $token);
                $this->model('authorizations')->insert([
                    'auth_status' => 1,
                    'ip' => $ip,
                    'user_id' => $user['id'],
                    'login' => $_POST['user_name'],
                    'geo_data' => $this->getGeoData($ip),
                    'ua' => $_SERVER['HTTP_USER_AGENT'],
                    'create_date' => gmdate('Y-m-d H:i:s')
                ]);
                $this->success(['url' => urldecode($_GET['redirect'])]);
            } else {
                $this->model('authorizations')->insert([
                    'auth_status' => 2,
                    'ip' => $ip,
                    'login' => $_POST['user_name'],
                    'geo_data' => $this->getGeoData($ip),
                    'ua' => $_SERVER['HTTP_USER_AGENT'],
                    'create_date' => gmdate('Y-m-d H:i:s')
                ]);
                $this->fail(['error' => 'Неверный логин/пароль']);
            }
        }
        $this->fail(['error' => 'Введите логин/пароль']);
    }

    private function checkAttempts($ip)
    {
        $authorizations = $this->model('authorizations')->getByField('ip', $ip, true, 'create_date DESC', 5);
        $fail_attempts = 0;
        foreach ($authorizations as $authorization) {
            if($authorization['auth_status'] == 2) {
                $fail_attempts += 1;
            }
        }
        if($fail_attempts === 5) {
            $this->model('ip_list')->insert([
                'ip' => $ip,
                'actor' => 0,
                'list_type' => 1,
                'create_date' => tools_class::gmdate()
            ]);
            return true;
        }
        return false;
    }

    private function getGeoData($ip)
    {
        $arr = [];
        if($country = geo_ip_class::getCountry($ip)) {
            $arr[] = $country;
        }
        if($city = geo_ip_class::getCity($ip)) {
            $arr[] = $city;
        }
        return implode(' - ', $arr);
    }

    public function captcha()
    {
        $response = google_api::verifyCaptcha($_POST['token'], ip_class::getIpFromHeaders());
        $score = $response['score'];
        if(!empty($score) && $score < 5) {
            $this->success();
        }
        $this->fail(['response' => $response]);
    }


    protected function rules()
    {
        $this->rules = [
            'content' => [
                'auth' => false
            ],
            'login' => [
                'auth' => false
            ],
            'captcha' => [
                'auth' => false
            ]
        ];
    }
}