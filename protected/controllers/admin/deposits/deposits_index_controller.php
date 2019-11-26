<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 20/08/2019
 * Time: 10:57
 */
class deposits_index_controller extends admin_project
{
    public function content()
    {
        $this->view('deposits/index');
    }

    public function get_clients()
    {
        $params = [];
        $params['table'] = 'deposits d';
        $params['select'] = [
            'd.id',
            'IF(d.status_id = 1, "Открыт", "Закрыт")',
            'd.plan',
            'DATEDIFF(NOW(), d.create_date)',
            'd.amount_btc',
            'd.profit',
            '<a href=\"/clients/id?id=", r.id, "\">u.t_user_name</a>',
            "d.create_date"
        ];
        $params['join']['bot_users'] = [
            'as' => 'u',
            'left' => true,
            'on' => 'u.id = d.user_id'
        ];
        $params['order'] = 'd.create_date DESC';
        $this->success($this->module('data_table')->init($params, false, [7]));
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