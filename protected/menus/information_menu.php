<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 15:59
 */
class information_menu extends bot_commands_class
{
    public function account()
    {
        $this->sendHTML('information/menu');
    }
}