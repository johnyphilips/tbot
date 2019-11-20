<?php
class webhook_bot1_controller extends bot_project
{
    private $text_commands = [
        '🌐 Лотерея' => 'lottery',
        '🎰 Lottery' => 'lottery',
        '👥 Личный Кабинет' => 'profile',
        '👥 My Profile' => 'profile',
        '🍀 My Profile' => 'profile',
        '🍀 Личный кабинет' => 'profile',
        '❓Информация' => 'information',
        '⚙️ About Us' => 'information',
        '❓ About Us' => 'information',
        '❓ Information' => 'information',
        '🌟 Бесплатная Лотерея' => 'free_lottery',
        '🎉 Бесплатная Лотерея' => 'free_lottery',
        '🌟 Free Lottery' => 'free_lottery',
        '🎉 Free Lottery' => 'free_lottery',
        '🧿 Mini Roulette' => 'roulette',
        '💰 Get Coins' => 'profile@/topup_btc',
        '📤 Withdraw' => 'profile@/withdraw',
        '🃏 Joker' => 'slots@/slots',
        '🃏 Joker Free Demo' => 'slots@/slots_demo',
        '🏁 Finish Game' => 'slots@/finish'
    ];

	public function end()
	{
		$raw = file_get_contents('php://input');
		$arr = json_decode($raw, true);

		$this->writeLog('bot_income/' . date('Ymd'), $raw);
		if($arr['callback_query']) {
		    $arr = $arr['callback_query'];
		    $arr['message']['data'] = $arr['data'];
        }
        $message = $arr['message'];
        if(!$message['chat']['id'] || $message['chat']['id'] < 0) {
            return;
        }
		if(bot_commands_class::EMERGENCY) {
            new bot_commands_class($message);
            exit;
        }
        if(strpos($message['text'],'@')) {
            $arr = explode('@', $message['text']);
            $class_name = array_shift($arr) . '_menu';
            if(class_exists($class_name)) {
                $message['text'] = implode('@', $arr);
                new $class_name($message);
                $this->success();
            }
        }
        if($message['data'] && strpos($message['data'],'@')) {
            $arr = explode('@', $message['data']);
            $class_name = array_shift($arr) . '_menu';
            if(class_exists($class_name)) {
                $message['data'] = implode('@', $arr);
                new $class_name($message);
                $this->success();
            }
        }
        if(array_key_exists($message['text'], $this->text_commands)) {
            if(strpos( $this->text_commands[$message['text']],'@')) {
                $arr = explode('@', $this->text_commands[$message['text']]);
                $class_name = $arr[0] . '_menu';

                if(class_exists($class_name)) {
                    $message['text'] = $arr[1];
                    new $class_name($message);
                    $this->success();
                }
            } else {
                $class_name = $this->text_commands[$message['text']] . '_menu';
                if(class_exists($class_name)) {
                    $message['text'] = '/' . $this->text_commands[$message['text']];
                    new $class_name($message);
                    $this->success();
                }
            }

        }
        new bot_commands_class($message);
        $this->success();
	}
}