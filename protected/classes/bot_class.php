<?php
class bot_class extends base
{
	protected $user;
    private static $instance;
    const SALT = '(&BAKJS^';
    const REFERRER_SALT = 1220;
    const USER_INACTIVE_STATUS = 1;
    const USER_ACTIVE_STATUS = 2;
    const USER_BLOCKED_STATUS = 3;
    public  $last_error;
    public $last_response;
    protected function getUser($message)
	{
		$user = $this->model('bot_users')->getByField('chat_id', $message['chat']['id']);
		if($user) {
			$this->user = $user;
			if($this->user['status_id'] == self::USER_BLOCKED_STATUS) {
			    $this->model('bot_users')->insert([
			        'id' => $user['id'],
                    'status_id' => self::USER_ACTIVE_STATUS
                ]);
            }
			return true;
		} elseif($message) {
			return $this->createUser($message);
		} else {
			return false;
		}
	}

	public function setUser($user)
    {
        $this->user = $user;
    }

	protected function createUser($message)
	{
	    if(!$message['chat']['id'] || $message['chat']['id'] < 0) {
	        return false;
        }
		$user = [
			'chat_id' => $message['chat']['id'],
            'lang' => 'en', //$message['from']['language_code'] == 'ru' ? 'ru' : 'en',
            't_user_name' => $message['chat']['username']  ? $message['chat']['username'] : $message['from']['first_name'],
			'tg_user_id' => $message['from']['id'],
            'status_id' => self::USER_INACTIVE_STATUS,
			'user_name' => $message['chat']['username'] ? $message['chat']['first_name'] : $message['from']['username'],
			'first_name' => $message['chat']['first_name'] ? $message['chat']['first_name'] : $message['from']['first_name'],
			'last_name' => $message['chat']['last_name'] ? $message['chat']['last_name'] : $message['from']['last_name'],
			'create_date' => date('Y-m-d H:i:s')
		];
		if($user['id'] = $this->model('bot_users')->insert($user)) {
			$this->user = $user;
			return true;
		}
		return false;
	}

    public function sendMessage($message, $buttons = [], $keyboard = [], $chat_id = null, $lang = null)
    {
        if(!$chat_id) {
            $chat_id = $this->user['chat_id'];
        }
        if(!$lang) {
            $lang = $this->user['lang'];
        }
        if(strlen($chat_id) < 4) {
            echo $message;
            return true;
        }
        if($buttons) {
            $params = [
                'inline_keyboard' => $buttons[$lang] ? $buttons[$lang] : $buttons['en'],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ];
            $reply_markup = self::bot()->replyKeyboardMarkup($params);
            $this->send($chat_id, $message, $reply_markup);
        } elseif($keyboard) {
            $params = [
                'keyboard' => $keyboard[$lang] ? $keyboard[$lang] : $keyboard['en'],
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ];
            $reply_markup = self::bot()->replyKeyboardMarkup($params);
            return $this->send($chat_id, $message, $reply_markup);
        } else {
            return $this->send($chat_id, $message);
        }
        return false;
	}

    /**
     * @param $template
     * @param bool $html
     * @param array $buttons
     * @param array $keyboard
     * @return bool
     */

    public function sendTemplate($template, $html = false, $buttons = [], $keyboard = [], $chat_id = null, $lang = null)
    {
        if(!$chat_id) {
            $chat_id = $this->user['chat_id'];
        }
        $message = $this->fetch($template, null, $lang);
        if(strlen($chat_id) < 4) {
            echo $message;
            return true;
        }
        if($html) {
            return $this->sendHTML($message, $buttons, $keyboard, $chat_id);
        } else {
            return $this->sendMessage($message, $buttons, $keyboard, $chat_id);
        }
	}

    /**
     * @param $message
     * @param array $buttons
     * @param array $keyboard
     * @param $chat_id
     * @return bool
     */

    public function sendHTML($message, $buttons = [], $keyboard = [], $chat_id = null, $lang = null)
    {
        if(!$chat_id) {
            $chat_id = $this->user['chat_id'];
        }
        if(strlen($chat_id) < 4) {
            echo $message;
            return true;
        }
        if(!$lang) {
            $lang = $this->user['lang'];
        }
        if(false === $keyboard) {
            $params = [
                'remove_keyboard' => true
            ];
            $reply_markup = self::bot()->replyKeyboardMarkup($params);
            $res = $this->send($chat_id, $message, $reply_markup, true);
            return $res;
        }
        if($buttons) {
            $params = [
                'inline_keyboard' => $buttons[$lang] ? $buttons[$lang] : $buttons['en'],
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ];
            $reply_markup = self::bot()->replyKeyboardMarkup($params);
            return $this->send($chat_id, $message, $reply_markup, true);
        } elseif($keyboard) {
            $params = [
                'keyboard' => $keyboard[$lang] ? $keyboard[$lang] : $keyboard['en'],
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ];
            $reply_markup = self::bot()->replyKeyboardMarkup($params);
            $res = $this->send($chat_id, $message, $reply_markup, true);
            return $res;
        } else {
            $res = $this->send($chat_id, $message, null, true);
            return $res;
        }
    }

    public function deleteMessage($message_id, $chat_id = null)
    {
        if(!$chat_id) {
            $chat_id = $this->user['chat_id'];
        }
        $res = bot_api::deleteMessage([
            'message_id' => $message_id,
            'chat_id' => $chat_id
        ]);
        return $res;
    }


    private static function bot()
    {
        if(null === self::$instance) {
            require_once PROTECTED_DIR . 'vendor/autoload.php';
            self::$instance = new Telegram\Bot\Api(BOT_1);
        }
        return self::$instance;
    }

    public function sendPhoto($photo, $caption = null, $buttons = [], $chat_id = null, $html = true)
    {
        if(!$chat_id) {
            $chat_id = $this->user['chat_id'];
        }
        if(strlen($chat_id) < 4) {
            echo $photo;
            return true;
        }
//        $base_url = PROTECTED_DIR . 'assets/images/';
        if(!empty($chat_id)) {
            try {
                $message = [
                    'chat_id' => $chat_id,
                    'photo' => $photo,
                    'disable_web_page_preview' => true
                ];
                if($caption) {
                    $message['caption'] = $caption;
                }
                if($html) {
                    $message['parse_mode'] = 'HTML';
                }
                if($buttons) {
                    $params = [
                        'inline_keyboard' => $buttons['en'],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ];
                    $reply_markup = self::bot()->replyKeyboardMarkup($params);
                    $message['reply_markup'] = $reply_markup;
                }
                return self::bot()->sendPhoto($message);
            } catch (Exception $e) {
                echo $e;
                $this->last_error = $e;
                return false;
            }
        }
        return false;
    }

    public function send($chat_id, $message, $reply_markup = null, $html = false)
    {
        $res = false;
        if(!empty($chat_id)) {
            try {
                $message = [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'disable_web_page_preview' => true
                ];
                if($html) {
                    $message['parse_mode'] = 'HTML';
                }
                if($reply_markup) {
                    $message['reply_markup'] = $reply_markup;
                }
                $res = self::bot()->sendMessage($message);
                $this->last_response = self::bot()->getLastResponse()->getDecodedBody();
            } catch (Exception $e) {

                $this->last_error = $e;
                $error_message = $e->getMessage();
                if($error_message == 'Forbidden: bot was blocked by the user') {
                    if($user = self::model('bot_users')->getByField('chat_id', $chat_id)) {
                        self::model('bot_users')->insert([
                            'id' => $user['id'],
                            'status_id' => bot_commands_class::USER_BLOCKED_STATUS,
                            'blocked_date' => tools_class::gmDate()
                        ]);
                    }

                }
                echo $e;
                $res = false;
            }
        }
        return $res;
    }

    public function fetch($template, $lang = 'en', $full_path = false)
    {
        if($this->user['lang']) {
            $lang = $this->user['lang'];
        }
        if(registry::get('common_vars')) {
            $this->render('common_vars', registry::get('common_vars'));
        }
        if(!$full_path) {
            $template_file = PROTECTED_DIR . 'templates' . DS . PROJECT . DS . $lang . DS . $template . '.php';
        } else {
            $template_file = PROTECTED_DIR . $template . '.php';
        }
        if($lang !== 'en' && !file_exists($template_file) && !$full_path) {
            $template_file = PROTECTED_DIR . 'templates' . DS . PROJECT . DS . 'en' . DS . $template . '.php';
        }
        if(!file_exists($template_file)) {
            throw new Exception('cannot find template in ' . $template_file);
        }
        foreach($this->vars as $k => $v) {
            $$k = $v;
        }
        ob_start();
        @require($template_file);
        return ob_get_clean();
    }
}