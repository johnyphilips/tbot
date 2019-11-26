<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 15:59
 */
class withdraw_menu extends bot_commands_class
{
    public function main($message = 'main')
    {
        $this->render('sum', $this->user['balance']);
        if($this->user['balance'] >= deposit_service::MIN_WITHDRAW) {
            $this->setExpect('withdraw@/get_withdraw_sum');
            $keyboard = [
                'ru' => [
                    [
                        ['text' => ' Отмена'],
                    ]
                ],
                'en' => [
                    [
                        ['text' => ' Cancel']
                    ]
                ]
            ];
            $this->sendHTML($this->fetch('withdraw/' . $message), null, $keyboard);
        } else {
            $this->sendHTML($this->fetch('withdraw/main'));
            $this->menu();
        }
    }

    public function get_withdraw_sum($message = 'withdraw_address', $withdrawal = null)
    {
        $this->setExpect();
        $sum = $this->message['text'];
        if($withdrawal) {
            $sum = $withdrawal['amount_btc'];
        }
        if (in_array($sum, ['Отмена', 'Cancel'])) {
            if($withdrawal) {
                $this->model('withdrawals')->deleteById($withdrawal['id']);
            }

            $this->menu();
            exit;
        }
        if (!is_numeric($sum) || $sum < deposit_service::MIN_WITHDRAW || $sum > $this->user['balance']) {
            $this->render('min_sum', balance_service::MIN_WITHDRAW);
            $this->render('max_sum', $this->user['balance']);
            $this->main('need_number');
        } else {
            if(!$withdrawal) {
                $withdrawal = bitcoin_service::createWithdrawal($this->user['id'], $sum);
            }
            $this->render('sum', $withdrawal['amount_btc']);
            $this->setExpect('withdraw@/get_withdraw_address_' . $withdrawal['id']);
            $keyboard = [
                'ru' => [
                    [
                        ['text' => ' Отмена'],
                    ]
                ],
                'en' => [
                    [
                        ['text' => ' Cancel']
                    ]
                ]
            ];
            $this->sendHTML($this->fetch('profile/' . $message), null, $keyboard);
            exit;
        }
    }

    public function get_withdraw_address($withdrawal_id)
    {
        $withdrawal = $this->model('withdrawals')->getById($withdrawal_id);
        $this->setExpect();
        $address = $this->message['text'];
        if (in_array($address, ['Отмена', 'Cancel'])) {
            $this->model('withdrawals')->deleteById($withdrawal_id);
            $this->menu();
            exit;
        }
        if (!bitcoin_service::validateBTCAddress($address)) {
            $this->get_withdraw_sum('incorrect_address', $withdrawal);
            exit;
        } else {
            $withdrawal['address'] = $address;
            $this->model('withdrawals')->insert($withdrawal);
            if($withdrawal['tx_id'] = bitcoin_service::sendFunds($withdrawal)) {
                $this->render('withdrawal', $withdrawal);
                balance_service::balanceMinus($this->user['id'], $withdrawal['amount_btc']);
                $this->render('sum', $withdrawal['amount_btc']);
                $this->render('tx_id', $withdrawal['tx_id']);
                $this->sendHTML($this->fetch('profile/withdrawal_success'));
                queue_service::add(WITHDRAWAL_CHANNEL, $this->fetch('queue/withdrawal_channel'));
            } else {
                $this->sendHTML($this->fetch('profile/withdrawal_unsuccess'));
            }
            $this->menu();
        }
    }

    public function __call($name, $params)
    {
        if(strpos($name, 'get_withdraw_address_') === 0) {
            $id = str_replace('get_withdraw_address_', '', $name);
            $this->get_withdraw_address($id);
        }
    }


}