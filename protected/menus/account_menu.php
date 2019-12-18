<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 15:59
 */
class account_menu extends bot_commands_class
{
    public function main()
    {
        $this->render('referral_link', tools_class::getReferralLink($this->user));
        $deposits = [];
        $qty = 0;
        $invested = 0;
        foreach ($this->model('deposits')->getByField('user_id', $this->user['id'], true) as $k => $item) {
            $deposits[$item['plan']][$k] = $item;
            $deposits[$item['plan']][$k]['next_payment'] = date('d/m/Y, H:i', $item['last_profit'] + 3* 3600);
            $qty ++;
            $invested += $item['amount_btc'];
        }
        $earned = 0;
        foreach ($this->model('profits')->getByField('user_id', $this->user['id'], true) as $item) {
            $earned += $item['amount_btc'];
        }
        $this->render('deposits', $deposits);
        $this->render('deposits_qty', $qty);
        $this->render('user', $this->user);
        $in = [];
        $earned_referrals = 0;
        $referrals = 0;
        $active_referrals = 0;
        foreach ($this->model('bot_users')->getByFields([
            'referrer_id' => $this->user['id'],
            'status_id' => bot_commands_class::USER_ACTIVE_STATUS
        ], true) as $referral) {
            $referrals += 1;
            $deposits = $this->model('deposits')->getByField('user_id', $referral['id'], true);
            foreach ($deposits as $item) {
                $earned_referrals += $item['amount_btc']/100 * deposit_service::REFERRER_PAYOUTS[1];
            }
            if($deposits) {
                $active_referrals += 1;
            }
            $in[] = $referral['id'];
        }
        if($in) {
            $in2 = [];
            foreach ($this->model('bot_users')->getReferralsReferrals($in, 2) as $referral) {
                $referrals += 1;
                if($referral['deposits']) {
                    $active_referrals += 1;
                }
//                $deposits = $this->model('deposits')->getByField('user_id', $referral['id'], true);
//                foreach ($deposits as $item) {
//                }
                $earned_referrals += $referral['payouts'] ? $referral['payouts'] : 0;

                $in2[] = $referral['id'];
            }
            if($in2) {
                foreach ($this->model('bot_users')->getReferralsReferrals($in2, 3) as $referral) {
                    $referrals += 1;
                    if($referral['deposits']) {
                        $active_referrals += 1;
                    }
//                    $deposits = $this->model('deposits')->getByField('user_id', $referral['id'], true);
//                    foreach ($deposits as $item) {
//                        $earned_referrals += $item['amount_btc']/100 * deposit_service::REFERRER_PAYOUTS[1];
//                    }
                    $earned_referrals += $referral['payouts'] ? $referral['payouts'] : 0;
                }
            }
        }
        $earned += $earned_referrals;
        $this->render('earned_referrals', $earned_referrals);
        $this->render('active_referrals', $active_referrals);
        $this->render('referrals', $referrals);
        $this->render('invested', $invested);
        $this->render('earned', $earned);
        $buttons['en'] = [
            [
                ['text' => 'SET/CHANGE BITCOIN ADDRESS',  'callback_data' => 'account@/set_wallet']
            ]
        ];
        $this->sendHTML($this->fetch('account/main'), $buttons);
    }

    public function set_wallet()
    {
        $keyboard = [
            'en' => [
                [
                    ['text' => ' Cancel']
                ]
            ]
        ];
        $this->setExpect('account@/get_wallet');
        $this->sendHTML($this->fetch('account/set_wallet'), null, $keyboard);
        exit;
    }

    public function get_wallet()
    {
        if(paykassa_api::validateBTCAddress($this->message['text'])) {
            $this->model('bot_users')->insert([
                'id' => $this->user['id'],
                'wallet' => $this->message['text']
            ]);
            $this->render('wallet', $this->message['text']);
            $this->sendHTML($this->fetch('account/wallet_was_set'));
            sleep(2);
            $this->menu();
        } else {
            $this->setExpect('account@/get_wallet');
            $this->sendHTML($this->fetch('account/incorrect_wallet'));
            exit;
        }
    }
}