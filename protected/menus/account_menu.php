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
        foreach ($this->model('deposits')->getByField('user_id', $this->user['id'], true) as $k => $item) {
            $deposits[$item['plan']][$k] = $item;
            $deposits[$item['plan']][$k]['next_payment'] = date('d/m/Y, H:i', $item['last_profit'] + 3* 3600);
        }
        $this->render('deposits', $deposits);
        $this->render('user', $this->user);
        $this->sendHTML($this->fetch('account/main'));
    }
}