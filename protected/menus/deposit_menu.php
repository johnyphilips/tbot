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
        $buttons['en'] = [
            [['text' => 'Deposit Funds',  'callback_data' => 'deposit@/deposit']],
        ];
        $this->sendHTML('deposit/main', $buttons);
    }

    public function deposit($message = 'deposit')
    {
        $this->render('btc_rate', $this->model('system_config')->getByField('config_key','btc_rate')['config_value']);
        $this->setExpect('deposit@/get_deposit_sum');
        $keyboard = [
            'en' => [
                [
                    ['text' => ' Cancel']
                ]
            ]
        ];
        $this->sendHTML($this->fetch('deposit/' . $message), null, $keyboard);
    }

    public function get_deposit_sum()
    {
        $this->setExpect();
        $sum = $this->message['text'];
        if(in_array($sum, ['Отмена', 'Cancel'])) {
            $this->menu();
            $this->deposit();
            exit;
        }
        if(!$plan = deposit_service::getPlanBySum($sum)) {
            $this->deposit('deposit_need_number');
            $this->setExpect('deposit@/get_deposit_sum');
        } else {
            $this->render('sum', $sum);
            $this->render('plan', $plan);
            if($payment = bitcoin_service::createPayment($this->user, $sum)) {
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
}