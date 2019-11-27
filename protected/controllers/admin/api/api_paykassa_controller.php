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
        $res = json_decode(file_get_contents('php://input'), true);
        $payment = $this->model('payments')->getById($_POST['order_id']);
        if($payment) {
            if($payment['status_id'] == bitcoin_service::PAYMENT_STATUS_CONFIRMED) {
                echo $payment['id'];
                exit;
            }
            if($amount = paykassa_api::checkTransaction($_POST['private_hash'])) {
                self::writeLog('test_req', $amount);
                $payment['paid'] = $payment['paid'] + $amount;
                self::model('payments')->insert([
                    'id' => $payment['id'],
                    'pay_date' => gmdate('Y-m-d H:i:s'),
                    'status_id' => bitcoin_service::PAYMENT_STATUS_CONFIRMED,
                    'paid' => $payment['paid']
                ]);
                if(deposit_service::topUp($payment, $payment['paid'])) {
                    self::render('sum', $payment['paid']);
                    $user = self::model('bot_users')->getById($payment['user_id']);
                    queue_service::add($payment['chat_id'], self::fetch('queue/topped_up', 'bot/en/queue/topped_up'), null, buttons_class::getMenu($user));
                }
            }
//            echo $payment['id'];
        }
        self::writeLog('test_paykassa', $res);
        self::writeLog('test_paykassa', $_POST['private_hash']);
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