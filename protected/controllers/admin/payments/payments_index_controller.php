<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 20/08/2019
 * Time: 10:57
 */
class payments_index_controller extends admin_project
{
    public function content()
    {
        $this->view('payments/index');
    }

    public function get_payments()
    {
        $params = [];
        $params['table'] = 'payments p';
        $params['select'] = [
            'p.id',
            'IF(
                p.status_id = "' . bitcoin_service::PAYMENT_STATUS_NEW . '", "Новая",
                    IF(p.status_id = "' . bitcoin_service::PAYMENT_STATUS_NO_CONFIRMATIONS . '", "Неподтвржд",
                        IF(p.status_id = "' . bitcoin_service::PAYMENT_STATUS_CONFIRMED . '", "Выполнена", 
                        "Отменена")))',
            'CONCAT(
            "<a href=\"/clients/id/?id=", u.id, "\">", u.t_user_name, "</a>"
            )',
            'p.amount',
            'p.amount_btc',
            'p.address',
            'p.create_date',
            'p.pay_date'
        ];
        $params['join']['bot_users'] = [
            'as' => 'u',
            'on' => 'u.id = p.user_id'
        ];
        $params['order'] = 'p.create_date DESC';
        $this->success($this->module('data_table')->init($params, false, [6,7]));
    }

    protected function rules()
    {
        $this->rules = [
            'get_payments' => [
                'allowed_methods' => [
                    'GET'
                ]
            ]
        ];
    }
}