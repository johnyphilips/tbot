<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 28/03/2019
 * Time: 18:55
 */
class queue_service extends staticBase
{
    private static $bot;
    const SEND_LIMIT = 20;
    const STATUS_UNSENT = 0;
    const STATUS_SENT = 1;
    const STATUS_ERROR = 2;
    const QUEUE_LIMIT = 30;
    private static function bot($chat_id = null)
    {
        if(null === self::$bot) {
            self::$bot = new bot_class();
        }
        if($chat_id) {
            self::$bot->setUser([
                'chat_id' => $chat_id
            ]);
        }
        return self::$bot;
    }

    /**
     * @param $chat_id
     * @param null $message
     * @param array $buttons
     * @param array $keyboard
     * @param bool $template
     * @param bool $html
     * @param int $priority
     */

    public static function add($chat_id, $message = null, $buttons = [], $keyboard = [], $template = false, $html = true, $priority = 1)
    {
        $queue = [
            'chat_id' => $chat_id,
            'message' => $message,
            'create_date' => tools_class::gmDate()
        ];
        if($buttons) {
            $queue['buttons'] = json_encode($buttons);
        }
        if($keyboard) {
            $queue['keyboard'] = json_encode($keyboard);
        }
        $queue['html'] = $html ? 1 : 0;
        $queue['template'] = $template ? 1 : 0;
        $queue['priority'] = $priority;
        self::model('queue')->insert($queue);
    }

    public static function send()
    {
        foreach (self::model('queue')->getByField('status_id', self::STATUS_UNSENT, true, 'priority, create_date', self::QUEUE_LIMIT) as $item) {
            if($item['template']) {
                $message = self::fetch($item['message']);
            } else {
                $message = $item['message'];
                preg_match_all("/smile_([A-Z0-9]+)/", $message, $matches);
                if($matches) {
                    foreach ($matches[0] as $k => $match) {
                        $message = str_replace($match, hex2bin($matches[1][$k]), $message);
                    }
                }
            }
            if(strlen($item['chat_id']) > 3) {
                if($item['html']) {
                    $res = self::bot($item['chat_id'])->sendHTML($message, json_decode($item['buttons'],true), json_decode($item['keyboard'], true));
                } else {
                    $res = self::bot($item['chat_id'])->sendMessage($message, json_decode($item['buttons'],true), json_decode($item['keyboard'], true));
                }
            } else {
                $res = true;
                echo $message;
            }

            if($res) {
                self::model('queue')->insert([
                    'id' => $item['id'],
                    'status_id' => self::STATUS_SENT
                ]);
            } else {
                self::model('queue')->insert([
                    'id' => $item['id'],
                    'status_id' => self::STATUS_ERROR
                ]);
                notifications_service::createNotification('Could Not Send Queue Notification', json_encode([
                    'queue_id' => $item['id'],
                    'message' => $item['message'],
                    'error' => self::bot($item['chat_id'])->last_error
                ]), false, 'green');
            }
        }
    }
}