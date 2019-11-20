<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 20/08/2019
 * Time: 10:57
 */
class clients_index_controller extends admin_project
{
    public function content()
    {
        $this->view('clients/index');
    }

    public function get_clients()
    {
        $params = [];
        $params['table'] = 'bot_users u';
        $params['select'] = [
            'u.id',
//            'IF(u.status_id = 1, "Не актив", "Актив")',
            'u.t_user_name',
            'u.first_name',
            'u.last_name',
            'u.chat_id',
            'IF(r.id != NULL, CONCAT("
            <a href=\"/clients/id?id=", r.id, "\">r.t_user_name</a>
            "), "")',
            "u.balance",
            "u.prize_balance",
            "u.create_date",
            'CONCAT("
            <div style=\"width: 107px;\">
            <a href=\"/clients/id?id=", u.id, "\" class=\"btn btn-default btn-icon\">
                <i class=\"fas fa-eye\"></i>
            </a>
            <a data-id=\"", u.chat_id, "\" data-name=\"", u.t_user_name, "\" href=\"#message_modal\" data-toggle=\"modal\" class=\"btn btn-default btn-icon send_message\">
                <i class=\"fab fa-telegram-plane\"></i>
            </a>
            </div>
            ")',
        ];
        $params['join']['bot_users'] = [
            'as' => 'r',
            'left' => true,
            'on' => 'r.id = u.referrer_id'
        ];
        $params['order'] = 'u.create_date DESC';
        $this->success($this->module('data_table')->init($params, false, [8]));
    }

    public function send_message()
    {
        $bot = new bot_class();
        if($bot->sendHTML($_POST['val'], [], [], $_POST['id'])) {
            $this->success();
        }
        $this->fail();
    }

    protected function rules()
    {
        $this->rules = [
            'get_clients' => [
                'allowed_methods' => [
                    'GET'
                ]
            ]
        ];
    }
}