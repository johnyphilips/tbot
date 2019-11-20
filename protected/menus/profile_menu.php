<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 11/07/2019
 * Time: 21:49
 */
class profile_menu extends bot_commands_class
{
    public function profile()
    {
        $this->render('referral_link', $this->getReferralLink($this->user['id']));
        $this->render('user', $this->user);
        $buttons['en'] = [
            [
                ['text' => '🎲 My Lotteries',  'callback_data' => 'profile@/lotteries'],

            ],
            [
                ['text' => '💰 Get Coins',  'callback_data' => 'profile@/topup_btc'],
                ['text' => '📤 Withdraw',  'callback_data' => 'profile@/withdraw'],
            ],
//            [['text' => '♻️Transfer Coins from Winning Balance',  'callback_data' => 'profile@/topup_prize']],
            [
                ['text' => '👫 Referral Program',  'callback_data' => 'profile@/referral'],
//                ['text' => 'Language',  'callback_data' => 'profile@/language'],
            ],
//            [
//                ['text' => '🌟 Withdraw Free Lottery',  'callback_data' => 'profile@/withdraw_free'],
////                ['text' => 'Language',  'callback_data' => 'profile@/language'],
//            ]
        ];
        $buttons['ru'] = [
            [
                ['text' => '🔝 Пополнить баланс',  'callback_data' => 'profile@/topup']
            ],
            [
                ['text' => '🎲 Мои Лотереи',  'callback_data' => 'profile@/lotteries'],
                ['text' => '📤 Вывести выигрыши',  'callback_data' => 'profile@/withdraw']
            ],
            [
                ['text' => '👫 Реферальная Программа',  'callback_data' => 'profile@/referral'],
//                ['text' => 'Язык',  'callback_data' => 'profile@/language']
            ]
        ];
        $this->render('roulettes', count($this->model('roulettes')->getByFields(['user_id' => $this->user['id'], 'status_id' => roulette_service::STATUS_CLOSED], true)));
        $this->render('referrals', count($this->model('bot_users')->getByField('referrer_id', $this->user['id'], true)));
        $this->render('win', $this->model('bot_users')->getUserTotalWin($this->user['id']));
        $this->render('lotteries', $this->model('bot_users')->getUserTotalLotteries($this->user['id']));
        $template = $this->fetch('profile/profile');
        $this->sendHTML($template, $buttons);
    }

    public function lotteries()
    {
        $lotteries = $this->model('lotteries')->getUserActiveLotteries($this->user);
        $this->render('lotteries', $lotteries);
        $this->sendHTML($this->fetch('profile/lotteries'));
    }

    public function topup()
    {
        $buttons['en'] = [
            [['text' => 'Buy Coins for Bitcoin',  'callback_data' => 'profile@/topup_btc']],
//            [['text' => 'Buy BTC with card',  'url' => 'https://localbitcoins.com']]
        ];
        $buttons['ru'] = [
            [
                ['text' => 'Пополнить Биткоином',  'callback_data' => 'profile@/topup_btc'],
//                ['text' => 'Пополнить с Выигрышей',  'callback_data' => 'profile@/topup_prize'],
            ]
        ];
        $this->sendHTML($this->fetch('profile/topup'), $buttons);
    }

    public function withdraw($message = 'withdraw')
    {
        $this->render('sum', $this->user['balance']);
        if($this->user['balance'] >= balance_service::MIN_WITHDRAW) {
            $this->setExpect('profile@/get_withdraw_sum');
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
        } else {
            $this->sendHTML($this->fetch('profile/withdraw'));
            $this->menu();
        }
    }

    public function get_withdraw_sum($message = 'withdraw_address', $withdrawal = null)
    {
        $this->setExpect();
        $coins_sum = $this->message['text'];
        if($withdrawal) {
            $coins_sum = $withdrawal['amount'];
        }
        if (in_array($coins_sum, ['Отмена', 'Cancel'])) {
            if($withdrawal) {
                $this->model('withdrawals')->deleteById($withdrawal['id']);
            }

            $this->menu();
            exit;
        }
        if (!is_numeric($coins_sum) || $coins_sum < balance_service::MIN_WITHDRAW || $coins_sum > $this->user['balance']) {
            $this->render('min_sum', balance_service::MIN_WITHDRAW);
            $this->render('max_sum', $this->user['balance']);
            $this->withdraw('topup_need_number');
        } else {
            if(!$withdrawal) {
                $withdrawal = bitcoin_service::createWithdrawal($this->user['id'], $coins_sum);
            }
            $this->render('sum', $withdrawal['amount_btc']);
            $this->render('coins', $withdrawal['amount']);
            $this->setExpect('profile@/get_withdraw_address_' . $withdrawal['id']);
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
                balance_service::balanceMinus($this->user['id'], $withdrawal['amount']);
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

    public function withdraw_free($message = 'withdraw_free')
    {
        $this->render('sum', $this->user['free_lottery_prize']);
        if($this->user['free_lottery_prize'] >= balance_service::MIN_FREE_WITHDRAW) {
            $this->setExpect('profile@/get_withdraw_sum_free');
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
        } else {
            $this->sendHTML($this->fetch('profile/withdraw_free'));
            $this->menu();
        }
    }

    public function get_withdraw_sum_free($message = 'withdraw_address_free', $withdrawal = null)
    {
        $this->setExpect();
        $coins_sum = $this->message['text'];
        if($withdrawal) {
            $coins_sum = $withdrawal['amount'];
        }
        if (in_array($coins_sum, ['Отмена', 'Cancel'])) {
            if($withdrawal) {
                $this->model('withdrawals')->deleteById($withdrawal['id']);
            }

            $this->menu();
            exit;
        }
        if (!is_numeric($coins_sum) || $coins_sum < balance_service::MIN_FREE_WITHDRAW || $coins_sum > $this->user['free_lottery_prize']) {
            $this->render('min_sum', balance_service::MIN_FREE_WITHDRAW);
            $this->render('max_sum', $this->user['free_lottery_prize']);
            $this->withdraw_free('topup_need_number');
        } else {
            if(!$withdrawal) {
                $withdrawal = bitcoin_service::createWithdrawal($this->user['id'], $coins_sum, 1);
            }
            $this->render('sum', $withdrawal['amount_btc']);
            $this->render('coins', $withdrawal['amount']);
            $this->setExpect('profile@/get_withdraw_free_address_' . $withdrawal['id']);
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

    public function get_withdraw_free_address($withdrawal_id)
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
            $this->get_withdraw_sum_free('incorrect_address_free', $withdrawal);
            exit;
        } else {
            $withdrawal['address'] = $address;
            $this->model('withdrawals')->insert($withdrawal);
            if($withdrawal['tx_id'] = bitcoin_service::sendFunds($withdrawal)) {
                $this->render('withdrawal', $withdrawal);
                balance_service::prizeMinus($this->user['id'], $withdrawal['amount'], true);
                $this->render('sum', $withdrawal['amount_btc']);
                $this->render('tx_id', $withdrawal['tx_id']);
                queue_service::add(WITHDRAWAL_CHANNEL, $this->fetch('queue/withdrawal_channel_goods'));
                $this->sendHTML($this->fetch('profile/withdrawal_success'));
            } else {
                $this->sendHTML($this->fetch('profile/withdrawal_unsuccess'));
            }
            $this->menu();
        }
    }

    public function referral()
    {
        $this->render('referrals', $this->model('bot_users')->getByFields([
            'referrer_id' => $this->user['id'],
            'status_id' => bot_commands_class::USER_ACTIVE_STATUS
        ], true));
        $this->render('referral_link', $this->getReferralLink($this->user['id']));
        $this->sendHTML($this->fetch('profile/referral'));
    }

    public function useCoupon($id)
    {
        $coupon = self::model('coupons')->getById($id);
        if($coupon['status_id'] == 0) {
            balance_service::activateCoupon($coupon['id']);
            $buttons['en'] = [
                [
                    ['text' => '💰 Get Bingo Coins',  'callback_data' => 'profile@/topup_btc'],
                ]
            ];
            $this->sendHTML($this->fetch('profile/coupon'), $buttons);
        } elseif($coupon['status_id'] == balance_service::COUPON_STATUS_ACTIVATED) {
            $expire = round((balance_service::COUPON_EXPIRATION_TIME - (strtotime(tools_class::gmDate()) - strtotime($coupon['activated'])))/ 60);
            if($expire > 60) {
                $expire = floor($expire/60) . ' hours';
            } else {
                $expire .= ' minutes';
            }
            $buttons['en'] = [
                [
                    ['text' => '💰 Get Bingo Coins',  'callback_data' => 'profile@/topup_btc'],
                ]
            ];
            $this->render('expire', $expire);
            $this->sendHTML($this->fetch('profile/coupon_activated'), $buttons);
        } else {
            $this->sendHTML($this->fetch('profile/coupon_used'));
        }
    }

    public function topup_btc($message = 'topup_btc')
    {
        $this->render('discount', balance_service::getUserBonus($this->user['id'])['profit']);
        $this->render('btc_rate', $this->model('system_config')->getByField('config_key','btc_rate')['config_value']);
        $this->setExpect('profile@/get_topup_sum_btc');
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
    }

    public function topup_prize($message = 'topup_prize')
    {
        $this->setExpect('profile@/get_topup_sum_prize');
        $this->render('user', $this->user);
        if($this->user['prize_balance'] >= balance_service::MIN_TOPUP_PRIZE) {
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
        } else {
            $this->sendHTML($this->fetch('profile/' . $message));
        }
    }

    public function get_topup_sum_btc()
    {
        $coupon = balance_service::getUserBonus($this->user['id']);
        $this->setExpect();
        $coins_sum = $this->message['text'];
        if(in_array($coins_sum, ['Отмена', 'Cancel'])) {
            $this->menu();
            $this->topup();
            exit;
        }
        if(!is_numeric($coins_sum) || $coins_sum < balance_service::MIN_TOPUP) {
            $this->render('min_sum', balance_service::MIN_TOPUP);
            $this->topup_btc('topup_need_number');
            $this->setExpect('profile@/get_topup_sum_btc');
        } else {

            $btc_sum = $coins_sum * balance_service::COIN_COST;
            if($coupon) {
                $coins_sum += $coins_sum/100 * $coupon['profit'];
            }
            $this->render('sum', $coins_sum);
            $this->render('discount', $coupon['profit']);
            $this->render('btc_sum', $btc_sum);
            if($payment = bitcoin_service::createPayment($this->user, $coins_sum, $btc_sum, $coupon['id'])) {
                $buttons['en'] = [
                    [
                        ['text' => 'I paid',  'callback_data' => 'profile@/paid'],
                        ['text' => 'Cancel',  'callback_data' => 'profile@/cancel_payment_' . $payment['id']],
                    ]
                ];
                $buttons['ru'] = [
                    [
                        ['text' => 'Я оплатил',  'callback_data' => 'profile@/paid'],
                        ['text' => 'Отмена',  'callback_data' => 'profile@cancel_payment_' . $payment['id']],
                    ]
                ];
                $this->menu($this->fetch('profile/topup_info'));
                $this->sendHTML('<code>' . $payment['address'] . '</code>', $buttons);
//                $this->sendHTML($this->fetch('profile/topup_btc_warning'), $buttons);
            } else {
                $this->sendHTML($this->fetch('profile/topup_failed'));
                $this->menu();
                $this->topup();
            }
        }
    }

    public function get_topup_sum_prize()
    {
        $this->setExpect();
        $coins_sum = $this->message['text'];
        if(in_array($coins_sum, ['Отмена', 'Cancel'])) {
            $this->menu();
            $this->topup();
            exit;
        }
        if(!is_numeric($coins_sum) || $coins_sum > $this->user['prize_balance'] || $coins_sum < balance_service::MIN_TOPUP_PRIZE) {
            $this->render('min_sum', balance_service::MIN_TOPUP_PRIZE);
            $this->render('max_sum', $this->user['prize_balance']);
            $this->topup_prize('topup_need_number');
            $this->setExpect('profile@/get_topup_sum_prize');
        } else {
            $this->render('sum', $coins_sum);
            if(balance_service::prizeMinus($this->user['id'], $coins_sum)) {
                balance_service::balancePlus($this->user['id'], $coins_sum);
                $this->sendHTML($this->fetch('profile/topped_up_prize'));
            }
            $this->menu();
        }
    }

    public function paid()
    {
        $this->setExpect();
        $this->menu($this->fetch('profile/paid'));
    }

    private function cancelPayment($payment_id)
    {
        $this->model('payments')->insert([
            'id' => $payment_id,
            'status_id' => bitcoin_service::PAYMENT_STATUS_CANCELLED
        ]);
        $this->setExpect();
        $this->topup_btc();
    }

    public function language()
    {
        $buttons['en'] = [
            [
                ['text' => '🇷🇺 Русский',  'callback_data' => 'profile@/language_ru'],
                ['text' => '🇬🇧 English',  'callback_data' => 'profile@/language_en'],
            ]
        ];
        $this->sendHTML($this->fetch('profile/language'), $buttons);
    }

    public function __call($name, $params)
    {
        if(strpos($name, 'cancel_payment_') === 0) {
            $id = str_replace('cancel_payment_', '', $name);
            $this->cancelPayment($id);
        }
        if(strpos($name, 'get_withdraw_address_') === 0) {
            $id = str_replace('get_withdraw_address_', '', $name);
            $this->get_withdraw_address($id);
        }
        if(strpos($name, 'get_withdraw_free_address_') === 0) {
            $id = str_replace('get_withdraw_free_address_', '', $name);
            $this->get_withdraw_free_address($id);
        }
        if(strpos($name, 'language_') === 0) {
            $lang = str_replace('language_', '', $name);
            $this->model('bot_users')->insert([
                'id' => $this->user['id'],
                'lang' => $lang
            ]);
            $this->user = $this->model('bot_users')->getById($this->user['id']);
            $this->menu();
        }
        if(strpos($name, 'use_coupon_') === 0) {
            $id = str_replace('use_coupon_', '', $name);
            $this->useCoupon($id);
        }
    }
}