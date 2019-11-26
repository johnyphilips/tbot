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
        $referrer = $this->model('bot_users')->getById($user['referrer_id']);
        $this->render('referrer', $referrer);
        $this->render('user', $user);
        $this->render('deposits', $this->model('deposits')->getByField('user_id', $user['id'], true));
        $this->render('withdrawals', $this->model('withdrawals')->getByField('user_id', $user['id'], true));
        $in = [];
        $referrals = [];
        foreach ($this->model('bot_users')->getByFields([
            'referrer_id' => $user['id'],
            'status_id' => bot_commands_class::USER_ACTIVE_STATUS
        ], true) as $referral) {
            $referrals[1][$referral['id']] = $referral;
            $deposits = 0;
            $payouts = 0;
            foreach ($this->model('deposits')->getByField('user_id', $referral['id'], true) as $item) {
                $deposits += $item['amount_btc'];
                $payouts += $item['amount_btc']/100 * deposit_service::REFERRER_PAYOUTS[1];
            }
            $referrals[1][$referral['id']]['deposits'] = $deposits;
            $referrals[1][$referral['id']]['payouts'] = $payouts;
            $in[] = $referral['id'];
        }
        if($in) {
            $payouts = 0;
            $deposits = 0;
            foreach ($this->model('deposits')->getByFieldIn('user_id', $in, true) as $item) {
                $deposits += $item['amount_btc'];
                $payouts += $item['amount_btc']/100 * deposit_service::REFERRER_PAYOUTS[2];
            }
            $in2 = [];
            foreach ($this->model('bot_users')->getReferralsReferrals($in) as $referral) {
                $referrals[2][$referral['id']] = $referral;
                $referrals[2][$referral['id']]['deposits'] = $deposits;
                $referrals[2][$referral['id']]['payouts'] = $payouts;
                $in2[] = $referral['id'];
            }
            if($in2) {
                $deposits = 0;
                $payouts = 0;
                foreach ($this->model('deposits')->getByFieldIn('user_id', $in2, true) as $item) {
                    $deposits += $item['amount_btc'];
                    $payouts += $item['amount_btc']/100 * deposit_service::REFERRER_PAYOUTS[3];
                }
                foreach ($this->model('bot_users')->getReferralsReferrals($in2) as $referral) {
                    $referrals[3][$referral['id']] = $referral;
                    $referrals[3][$referral['id']]['deposits'] = $deposits;
                    $referrals[3][$referral['id']]['payouts'] = $payouts;
                }
            }
        }
        $this->render('referrals', $referrals);
        $this->view('clients/id');
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