<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 20/08/2019
 * Time: 10:57
 */
class clients_id_controller extends admin_project
{
    public function content()
    {
        if(!$_GET['id']) {
            $this->forbidden();
        }
        $user = $this->model('bot_users')->getById($_GET['id']);
        $this->render('user', $user);
        $this->render('active_lotteries', $this->model('lotteries')->getUserActiveLotteries($user));
        $this->render('closed_lotteries', $this->model('lotteries')->getUserClosedLotteries($user));
        $this->render('payments', $this->model('payments')->getByField('user_id', $_GET['id'], true));
        $this->render('withdrawals', $this->model('withdrawals')->getByField('user_id', $_GET['id'], true));
        $this->view('clients/id');
    }

    public function get_roulettes()
    {
        $params = [];
        $params['table'] = 'roulettes r';
        $params['select'] = [
            'r.id',
            'IF(r.status_id = ' . roulette_service::STATUS_ACTIVE . ', "Активная", "Завершена")',
            'COUNT(b.id)',
            'IF(r.status_id = ' . roulette_service::STATUS_ACTIVE . ', r.balance, won)',
            'max_win',
            'spent',
            'r.create_date',
            'r.close_date'
        ];
        $params['join']['roulette_bets'] = [
            'as' => 'b',
            'on' => 'b.roulette_id = r.id AND b.status_id = 3',
            'left' => true
        ];
        $params['order'] = 'r.create_date DESC';
        $params['group'] = 'r.id';
        $this->success($this->module('data_table')->init($params, false, [6,7]));
    }

    protected function rules()
    {
        $this->rules = [
            'get_roulettes' => [
                'allowed_methods' => ['GET']
            ]
        ];
    }
}