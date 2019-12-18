<?php
class bot_commands_class extends bot_class 
{
	protected $message;
	const EMERGENCY = false;
	public function __construct($message)
	{
		$this->message = $message;
        $this->getUser($this->message);
        if(self::EMERGENCY) {
            $this->sendHTML('<b>We are updating the System, please, try again in 20 minutes</b>');
            exit;
        }
        $this->init();
        if(!$message['data']) {
            $command = ltrim($message['text'], '/');
//            exit;

            if(method_exists($this, $command)) {
                if($command != 'begin_bonus' && $command != 'begin' && $command != 'start') {
                    self::checkUserStatus();
                }
                $this->setExpect();
                $this->$command();
            } elseif(strpos($command, 'start ') === 0) {
                $this->setExpect();
                if(!$this->user['referrer_id']) {
                    $referral_parameter = array_pop(explode(' ', $command));
                    if(is_numeric($referral_parameter)) {
                        if($referrer = $this->model('bot_users')->getByid($referral_parameter + self::REFERRER_SALT)) {
                            $this->model('bot_users')->insert([
                                'id' => $this->user['id'],
                                'referrer_id' => $referrer['id']
                            ]);
                            $this->user['referrer_id'] = $referrer['id'];
                        }
                    } else {
//                        if($promo = $this->model('promos')->getByFields([
//                            'promo_code' => $referral_parameter,
//                            'status_id' => 1
//                        ])) {
//                            $promo_res = $this->promo($promo);
//                        } else {
//                            $this->model('bot_users')->insert([
//                                'id' => $this->user['id'],
//                                'referrer_id' => 1
//                            ]);
//                        }
                    }

                }
                $this->start();
            } else if($this->user['expect_message']) {
                self::checkUserStatus();
                $command = $this->user['expect_message'];
                if(strpos($command, '@')) {
                    $arr = explode('@', $command);
                    $method = ltrim($arr[1], '/');
                    $this->setExpect($method);
                    $menu_class = $arr[0] . '_menu';
                    new $menu_class($message);
                } else {
                    if(method_exists($this, $command)) {
                        if($this->$command()) {
                            $this->setExpect();
                        }
                    } else {
                        $this->$command();
                        $this->setExpect();
                    }
                }

            } else {
                $this->setExpect();
                if($command != 'begin_bonus' && $command != 'begin' && $command != 'start') {
                    self::checkUserStatus();
                }
                $this->$command();
                $this->fallback($this->message);
            }
        } else {
            $command = ltrim($message['data'], '/');
            if(method_exists($this, $command)) {
                if($command != 'begin_bonus' && $command != 'begin' && $command != 'start') {
                    self::checkUserStatus();
                }
                $this->$command();
            } else {
                if($command != 'begin_bonus' && $command != 'begin' && $command != 'start') {
                    self::checkUserStatus();
                }
                $this->$command();
                $this->fallback($this->message);
            }
        }
	}

    private function promo($promo)
    {
        $promo['qty'] ++;
        $row = [
            'id' => $promo['id'],
            'qty' => $promo['qty']
        ];
        if($promo['qty'] == $promo['max_qty']) {
            $row['status_id'] = 0;
        }
        $this->model('promos')->insert($row);
        if($this->user['status_id'] == self::USER_INACTIVE_STATUS) {
            balance_service::balancePlus($this->user['id'], $promo['prize'], $promo['prize']);
            return $promo['prize'];
        }
        return false;
	}

	private function start($promo_res = null)
	{
	    $this->render('promo_res', $promo_res);
        $text = $this->fetch('start');
        $this->sendHTML($text);

        if($this->user['status_id'] == self::USER_INACTIVE_STATUS) {
            $text = $this->fetch('captcha');
            if($promo_res) {
                $buttons['en'][] = [
                    ['text' => '✅ Let’s Start',  'callback_data' => 'begin_bonus'],
                ];
            } else {
                $buttons['en'][] = [
                    ['text' => '✅ Let’s Start',  'callback_data' => 'begin'],
                ];
            }
            $this->sendHTML($text, $buttons);
        } else {
            $this->menu();
        }
	}

    protected function init()
    {

	}

    private function checkUserStatus()
    {
        if($this->user['status_id'] == self::USER_INACTIVE_STATUS) {
            $text = $this->fetch('captcha');
            $buttons['en'][] = [
                ['text' => '✅ Let’s Start', 'callback_data' => 'begin'],
            ];
            $this->sendHTML($text, $buttons, []);
            exit;
        }
	}

    public function begin()
    {
        if($this->user['status_id'] == self::USER_ACTIVE_STATUS) {
            return;
        }
        $this->user['status_id'] = self::USER_ACTIVE_STATUS;
        $this->model('bot_users')->insert([
            'id' => $this->user['id'],
            'status_id' => self::USER_ACTIVE_STATUS
        ]);
        if($referrer = $this->model('bot_users')->getById($this->user['referrer_id'])) {
            $this->render('referral_link', tools_class::getReferralLink($this->user));
            $this->render('referral', $this->user);
            $this->render('level', 1);
            $message = $this->fetch('queue/new_referral');
            queue_service::add($referrer['chat_id'], $message);
            if($referrer = $this->model('bot_users')->getById($referrer['referrer_id'])) {
                $this->render('referral', $this->user);
                $this->render('level', 2);
                $message = $this->fetch('queue/new_referral');
                queue_service::add($referrer['chat_id'], $message);
                if($referrer = $this->model('bot_users')->getById($referrer['referrer_id'])) {
                    $this->render('referral', $this->user);
                    $this->render('level', 3);
                    $message = $this->fetch('queue/new_referral');
                    queue_service::add($referrer['chat_id'], $message);
                }
            }
        }
        self::menu();
	}

    public function begin_bonus()
    {
        if($this->user['status_id'] == self::USER_ACTIVE_STATUS) {
            return;
        }
        $this->render('bonus', $this->user['bonus']);
        queue_service::add($this->user['chat_id'], $this->fetch('bonus'));
        $this->begin();
    }

    protected function menu($text = null)
    {
        if(!$text) {
            $texts = [
                'ru' => '👇🏽 Click on the section you are interested in',
                'en' => '👇🏽 Click on the section you are interested in'
            ];
            $text = $texts[$this->user['lang']];
        } else {
            if(is_array($text)) {
                $text = $text[$this->user['lang']];
            }
        }

        $this->sendHTML($text, [], buttons_class::getMenu($this->user));
	}

    public function cancel()
    {
        $this->setExpect();
        $this->menu([
            'en' => $this->fetch('cancel', 'en'),
            'ru' => $this->fetch('cancel', 'ru')
        ]);
        exit;
    }

    public function getReferralLink($user_id)
    {
        return BOT_LINK . '?start=' . ($user_id + self::REFERRER_SALT);
    }

    protected function fallback($message)
	{
		return false;
	}

    protected function setExpect($expect_message = null)
    {
        $this->model('bot_users')->insert([
            'id' => $this->user['id'],
            'expect_message' => $expect_message
        ]);
    }
}