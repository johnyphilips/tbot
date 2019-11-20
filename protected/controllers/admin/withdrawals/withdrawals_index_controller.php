<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 20/08/2019
 * Time: 10:57
 */
class withdrawals_index_controller extends admin_project
{
    public function content()
    {
        $this->view('withdrawals/index');
    }

    public function get_withdrawals()
    {
        $params = [];
        $params['table'] = 'withdrawals w';
        $params['select'] = [
            'w.id',
            'CONCAT(
            "<a href=\"/clients/id/?id=", u.id, "\">", u.t_user_name, "</a>"
            )',
            'w.address',
            'w.amount',
            'w.amount_btc',
            'IF(w.tx_id IS NOT NULL, CONCAT("
            <a href=\"' . CHECK_TRANSACTION_URL . '", w.tx_id, "\" class=\"btn btn-icon btn-default\">
                <i class=\"fas fa-link\"></i>
            </a>
            "), " - ")',
            'w.create_date'
        ];
        $params['join']['bot_users'] = [
            'as' => 'u',
            'on' => 'u.id = w.user_id'
        ];
        $params['order'] = 'w.create_date DESC';
        $this->success($this->module('data_table')->init($params, false, [6]));
    }

    protected function rules()
    {
        $this->rules = [
            'get_withdrawals' => [
                'allowed_methods' => [
                    'GET'
                ]
            ]
        ];
    }
}