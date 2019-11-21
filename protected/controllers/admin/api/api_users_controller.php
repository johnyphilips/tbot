<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 15.05.18
 * Time: 23:38
 */
class api_users_controller extends api_project
{
    public function referrers()
    {
        if(!$_GET['user_id']) {
            $this->badRequest();
        }
        $user = $this->model('bot_users')->getByField('chat_id', $_GET['user_id']);
        if(!$user) {
            $this->fail(['error' => 'Wrong User Id']);
        }
        $count = $this->model('bot_users')->countByField('referrer_id', $user['id']);
        $this->success(['referrers' => $count]);
    }

    protected function rules()
    {
        $this->rules = [
            'referrers' => [
                'allowed_methods' => ['GET']
            ]
        ];
    }
}
