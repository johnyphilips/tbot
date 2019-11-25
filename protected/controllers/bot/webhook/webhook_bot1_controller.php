<?php
class webhook_bot1_controller extends bot_project
{
    private $text_commands = [
        '🌐 My Account' => 'account',
        '🌐 Deposit' => 'deposit',
        '🌐 Withdraw Funds' => 'withdraw',
        '🌐 Referral Program' => 'referral',
        '🌐 About Us' => 'information'
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
        if(strpos($message['text'], '🌐 My Account') === 0) {
            $command = 'account';
        }
        if(!empty($command) || array_key_exists($message['text'], $this->text_commands)) {
            if(empty($command)) {
                $command = $this->text_commands[$message['text']];
            }
            if(strpos( $command,'@')) {
                $arr = explode('@', $command);
                $class_name = $arr[0] . '_menu';

                if(class_exists($class_name)) {
                    $message['text'] = $arr[1];
                    new $class_name($message);
                    $this->success();
                }
            } else {
                $class_name = $command . '_menu';
                if(class_exists($class_name)) {
                    $message['text'] = '/main';
                    new $class_name($message);
                    $this->success();
                }
            }

        }
        new bot_commands_class($message);
        $this->success();
	}
}