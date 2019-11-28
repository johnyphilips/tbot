<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 15:59
 */
class deposit_menu extends bot_commands_class
{
    public function main()
    {
//        $buttons['en'] = [
//            [['text' => 'Deposit Funds',  'callback_data' => 'deposit@/deposit']],
//        ];
//        $this->sendHTML('deposit/main', $buttons);
        $this->deposit();
    }

    public function deposit($message = 'deposit', $payment_id = null)
    {
        $this->render('btc_rate', $this->model('system_config')->getByField('config_key','btc_rate')['config_value']);
        if($payment_id) {
            $payment = $this->model('payments')->getById($payment_id);
            $min_sum = deposit_service::PLANS['intro']['from'] - $payment['paid'];
            $max_sum = deposit_service::PLANS['professional']['to'] - $payment['paid'];
            $this->setExpect('deposit@/get_deposit_sum_' . $payment_id);
            $this->writeLog('test','get_deposit_sum_' . $payment_id);
        } else {
            $min_sum = deposit_service::PLANS['intro']['from'];
            $max_sum = deposit_service::PLANS['professional']['to'];
            $this->setExpect('deposit@/get_deposit_sum');
        }
        $this->render('min_sum', $min_sum);
        $this->render('max_sum', $max_sum);
        $keyboard = [
            'en' => [
                [
                    ['text' => ' Cancel']
                ]
            ]
        ];
        $this->sendHTML($this->fetch('deposit/' . $message), null, $keyboard);
        exit;
    }

    public function get_deposit_sum($payment_id = null)
    {
        $this->setExpect();
        $sum = $this->message['text'];
        if(in_array($sum, ['Отмена', 'Cancel'])) {
            $this->menu();
            $this->deposit();
            exit;
        }
        if($payment_id) {
            $payment = $this->model('payments')->getById($payment_id);
        }
        if(!$plan = deposit_service::getPlanBySum($sum, $payment)) {
            $this->render('min_sum', deposit_service::PLANS['intro']['from'] - $payment['paid']);
            $this->render('max_sum', deposit_service::PLANS['professional']['to'] - $payment['paid']);
            if($payment) {
                $this->deposit('deposit_need_number', $payment['id']);
                $this->setExpect('deposit@/get_deposit_sum_' . $payment['id']);
            } else {
                $this->deposit('deposit_need_number');
                $this->setExpect('deposit@/get_deposit_sum');
            }
        } else {
            $this->render('sum', $sum);
            $this->render('plan', $plan);
            if(!$payment) {
                $payment = bitcoin_service::createPayment($this->user, $sum);
            } else {
                $payment = bitcoin_service::createPayment($this->user, $sum, $payment);
            }
            if($payment) {
                $buttons['en'] = [
                    [
                        ['text' => 'I paid',  'callback_data' => 'deposit@/paid'],
                        ['text' => 'Cancel',  'callback_data' => 'deposit@/cancel_payment_' . $payment['id']],
                    ]
                ];
                $buttons['ru'] = [
                    [
                        ['text' => 'Я оплатил',  'callback_data' => 'deposit@/paid'],
                        ['text' => 'Отмена',  'callback_data' => 'deposit@cancel_payment_' . $payment['id']],
                    ]
                ];
                $this->menu($this->fetch('deposit/deposit_info'));
                $this->sendHTML('<code>' . $payment['address'] . '</code>', $buttons);
            } else {
                $this->sendHTML($this->fetch('deposit/deposit_failed'));
                $this->menu();
                $this->deposit();
            }
        }
    }

    public function paid()
    {
        $this->setExpect();
        $this->menu($this->fetch('deposit/paid'));
    }

    private function cancelPayment($payment_id)
    {
        $this->model('payments')->insert([
            'id' => $payment_id,
            'status_id' => bitcoin_service::PAYMENT_STATUS_CANCELLED
        ]);
        $this->setExpect();
        $this->deposit();
    }

    public function __call($name, $params)
    {
        if(strpos($name, 'cancel_payment_') === 0) {
            $id = str_replace('cancel_payment_', '', $name);
            $this->cancelPayment($id);
        }

        if(strpos($name, 'deposit_') === 0) {
            $id = str_replace('deposit_', '', $name);
            $this->deposit('deposit', $id);
        }

        if(strpos($name, 'get_deposit_sum_') === 0) {
            $id = str_replace('get_deposit_sum_', '', $name);
            $this->get_deposit_sum($id);
        }
    }
}