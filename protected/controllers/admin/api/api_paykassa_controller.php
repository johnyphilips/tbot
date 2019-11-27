<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 27/11/2019
 * Time: 21:06
 */
class api_paykassa_controller extends api_helper
{
    public function handler()
    {
        $hash = json_decode(file_get_contents('php://input'), true)['private_hash'];
        self::writeLog('test_paykassa', $hash);

    }

    public function successful()
    {
        self::writeLog('test_paykassa', 's');
        self::writeLog('test_paykassa', $_GET);
        self::writeLog('test_paykassa', $_POST);
        self::writeLog('test_paykassa', file_get_contents('php://input'));
    }

    public function unsuccessful()
    {
        self::writeLog('test_paykassa', 'u');
        self::writeLog('test_paykassa', $_GET);
        self::writeLog('test_paykassa', $_POST);
        self::writeLog('test_paykassa', file_get_contents('php://input'));
    }
}