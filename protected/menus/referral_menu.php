<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 15:59
 */
class referral_menu extends bot_commands_class
{
    public function main()
    {
        $in = [];
        $referrals = [];
        foreach ($this->model('bot_users')->getByFields([
            'referrer_id' => $this->user['id'],
            'status_id' => bot_commands_class::USER_ACTIVE_STATUS
        ], true) as $referral) {
            $referrals[1][] = $referral;
            $deposits = 0;
            foreach ($this->model('deposits')->getByField('user_id', $referral['id'], true) as $item) {
                $deposits += $item['amount_btc'];
            }
            $in[] = $referral['id'];
        }
        if($in) {
            $in2 = [];
            foreach ($this->model('bot_users')->getReferralsReferrals($in) as $referral) {
                $referrals[2][] = $referral;
                $in2[] = $referral['id'];
            }
            if($in2) {
                foreach ($this->model('bot_users')->getReferralsReferrals($in2) as $referral) {
                    $referrals[3][] = $referral;
                }
            }
        }
        $this->render('referrals', $referrals);
        $this->render('referral_link', tools_class::getReferralLink($this->user));
        $this->sendHTML($this->fetch('referral/referral_link'));
        $this->sendHTML($this->fetch('referral/main'));
    }
}