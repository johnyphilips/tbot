<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 14.09.17
 * Time: 16:44
 */
class index_index_controller extends index_helper
{
    public function content()
    {
        $this->render('discount', $this->model('system_config')->getByField('config_key', 'discount')['config_value']);
        $this->view('index/index');
    }

    public function get_stats()
    {
        $start = '2019-08-19';
        $dates = [];
        for($i = 0; $i <= 30; $i ++) {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . ($i ? ' - ' . $i . ' days' : '')));
            $dates[] = $date;
            if($start === $date) {
                break;
            }
        }
        $stats = [];
        foreach ($dates as $date) {
            $stats[$date] = [
                'withdrawals' => 0,
                'payments' => 0,
                'new_users' => 0,
                'lotteries' => 0,
                'free_lotteries' => 0
            ];
        }
        foreach ($this->model('withdrawals')->count30DaysWithdrawals() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['withdrawals'] = $item;
            }
        }
        foreach ($this->model('payments')->count30DaysPayments() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['payments'] = $item;
            }
        }
        foreach ($this->model('bot_users')->count30DaysUsers() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['new_users'] = $item;
            }
        }
        foreach ($this->model('lotteries')->count30DaysLotteries() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['lotteries'] = $item;
            }
        }
        foreach ($this->model('lotteries')->count30DaysFreeLotteries() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['free_lotteries'] = $item;
            }
        }
        foreach ($this->model('roulettes')->count30DaysRoulettes() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['roulettes'] = $item;
            }
        }
        return $stats;
//        print_r($stats);
    }

    public function set_discount()
    {
        $config = $this->model('system_config')->getByField('config_key', 'discount');
        $config['config_key'] = 'discount';
        $config['config_value'] = $_POST['discount'] == 'true' ? 1 : 0;
        $this->model('system_config')->insert($config);
    }

    protected function rules()
    {
        $this->rules = [
            'content' => [
                'auth' => true,
                'allowed_roles' => 'all'
            ],
            'test' => [
                'allowed_methods' => ['GET', 'POST'],
                'auth' => false,
            ],
        ];
    }

    public function get_balance()
    {
        $info = bitcoin_api::getWalletInfo();
        $this->success(['balance' => $info['response']['balance']]);
    }

    public function get_users_stats()
    {
        $this->success([
            'today' => $this->model('bot_users')->countNewUsersByDate($_POST['date']),
            'total' => $this->model('bot_users')->countTotalUsers()
        ]);
    }

    public function get_withdrawals_stats()
    {
        $this->success([
            'today' => $this->model('withdrawals')->countWithdrawalsByDate($_POST['date']),
            'total' => $this->model('withdrawals')->countTotalWithdrawals()
        ]);
    }

    public function get_payments_stats()
    {
        $this->success([
            'today' => $this->model('payments')->countPaymentsByDate($_POST['date']),
            'total' => $this->model('payments')->countTotalPayments()
        ]);
    }

    public function test()
    {
        var_dump(deposit_service::PLANS['intro']['term'] - tools_class::dateDiff(date('Y-m-d', strtotime('2019-11-01')))->days);
    }
}