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
        $this->sendHTML('referral/main');
    }
}