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
        $this->render('stats', $this->get_stats());
        $this->render('auto_20', $this->model('system_config')->getByField('config_key', 'auto_20')['config_value']);
        $this->render('auto_ref', $this->model('system_config')->getByField('config_key', 'auto_ref')['config_value']);
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
        foreach ($this->model('deposits')->getBalancesAndDepositsByDate() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['deposits'] = $item['deposits'] ? $item['deposits'] : 0;
                $stats[$date]['balances'] = $item['balances'] ? $item['balances'] : 0;
            }
        }
        foreach ($this->model('deposits')->getProfitsByDate() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['profits'] = $item;
            }
        }
        foreach ($this->model('deposits')->getReferralPayoutsByDate() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['referrals'] = $item;
            }
        }
        foreach ($this->model('withdrawals')->count30DaysWithdrawals() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['withdrawals'] = $item;
            }
        }
        foreach ($this->model('bot_users')->count30DaysUsers() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['new_users'] = $item ? $item : 0;
            }
        }
        foreach ($this->model('bot_users')->count30DaysBlockedUsers() as $date => $item) {
            if($stats[$date]) {
                $stats[$date]['blocked_users'] = $item ? $item : 0;
            }
        }
//        foreach ($this->model('lotteries')->count30DaysLotteries() as $date => $item) {
//            if($stats[$date]) {
//                $stats[$date]['lotteries'] = $item;
//            }
//        }
//        foreach ($this->model('lotteries')->count30DaysFreeLotteries() as $date => $item) {
//            if($stats[$date]) {
//                $stats[$date]['free_lotteries'] = $item;
//            }
//        }
//        foreach ($this->model('roulettes')->count30DaysRoulettes() as $date => $item) {
//            if($stats[$date]) {
//                $stats[$date]['roulettes'] = $item;
//            }
//        }
        return $stats;
//        print_r($stats);
    }

    public function set_config()
    {
        if(!empty($_POST['20'])) {
            $config = $this->model('system_config')->getByField('config_key', 'auto_20');
            $config['config_key'] = 'auto_20';
            $config['config_value'] = $_POST['auto_20'] == 'true' ? 1 : 0;
            $this->model('system_config')->insert($config);
        } else {
            $config = $this->model('system_config')->getByField('config_key', 'auto_ref');
            $config['config_key'] = 'auto_ref';
            $config['config_value'] = $_POST['auto_ref'] == 'true' ? 1 : 0;
            $this->model('system_config')->insert($config);
        }
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
//        $info = bitcoin_api::getWalletInfo();
        $info = paykassa_api::getBalance();
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
         echo time() - (24/deposit_service::UPDATE_PROFIT_PER_DAY) * 3600;
    }
}