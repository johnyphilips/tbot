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
            $in[] = $referral['id'];
        }
        if($in) {
            $in2 = [];
            foreach ($this->model('bot_users')->getReferralsReferrals($in) as $referral) {
                $referrals[2][] = $referral;
                $in2[] = $referral['id'];
            }
            if($in2) {
                $in3 = [];
                foreach ($this->model('bot_users')->getReferralsReferrals($in) as $referral) {
                    $referrals[3][] = $referral;
                    $in3[] = $referral['id'];
                }
            }
        }
        $this->render('referrals', $referrals);

        $this->sendHTML($this->fetch('referral/main'));
    }
}