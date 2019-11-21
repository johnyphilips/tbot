<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 15.05.18
 * Time: 23:53
 */
class tbot_class extends staticBase
{
    private static $instance = [];

    private static function bot($bot)
    {
        if(null === self::$instance[$bot]) {
            require_once PROTECTED_DIR . 'vendor/autoload.php';
            self::$instance[$bot] = new Telegram\Bot\Api($bot);
        }
        return self::$instance[$bot];
    }

    public static function sendToRole($role_id, $message, $bot = BOT_1)
    {
        $users = self::model('system_users')->getByField('role_id', $role_id, true);
        foreach ($users as $user) {
            if($user['telegram_chat_id']) {
                try {
                    self::bot($bot)->sendMessage([
                        'chat_id' => $user['telegram_chat_id'],
                        'text' => $message
                    ]);
                } catch (Exception $e) {
                    return false;
                }

            }
        }
        return true;
    }

    public static function send($chat_id, $message, $bot = BOT_1)
    {
        if(!empty($chat_id)) {
            try {
                self::bot($bot)->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $message
                ]);
                return true;
            } catch (Exception $e) {
                echo $e;
                return false;
            }
        }
        return false;
    }

    public static function reply($chat_id, $message, $message_id, $bot = BOT_1)
    {
        if(!empty($chat_id)) {
            try {
                self::bot($bot)->sendMessage([
                    'chat_id' => $chat_id,
                    'reply_to_message_id' => $message_id,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true
                ]);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}