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
        foreach ($this->model('deposits')->getByField('user_id', $this->user['id'], true) as $k => $item) {
            $deposits[$item['plan']][$k] = $item;
            $deposits[$item['plan']][$k]['next_payment'] = date('d/m/Y, H:i', $item['last_profit'] + 3* 3600);
            $qty ++;
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
            if($referral['deposits']) {
                $active_referrals += 1;
                $earned_referrals += $referral['deposits'];
            }
            $in[] = $referral['id'];
        }
        if($in) {
            $in2 = [];
            foreach ($this->model('bot_users')->getReferralsReferrals($in) as $referral) {
                $referrals += 1;
                if($referral['deposits']) {
                    $active_referrals += 1;
                }
                $earned_referrals += $referral['deposits'];
                $in2[] = $referral['id'];
            }
            if($in2) {
                foreach ($this->model('bot_users')->getReferralsReferrals($in2) as $referral) {
                    $referrals += 1;
                    if($referral['deposits']) {
                        $active_referrals += 1;
                    }
                    $earned_referrals += $referral['deposits'];
                }
            }
        }
        $this->render('earned_referrals', $earned_referrals);
        $this->render('active_referrals', $active_referrals);
        $this->render('referrals', $referrals);
        $this->sendHTML($this->fetch('account/main'));
    }
}