<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 23/05/2019
 * Time: 13:22
 */
class notifications_service extends staticBase
{
    const STATUS_NEW = 0;
    const STATUS_READ = 1;
    const TG_CHAT_ID = 143220442;
    public static function createNotification($short, $full, $send = true, $color = 'red')
    {
        $id = self::model('system_notifications')->insert([
            'status_id' => self::STATUS_NEW,
            'color' => $color,
            'short_text' => $short,
            'full_text' => $full,
            'create_date' => tools_class::gmDate()
        ]);
        if($send) {
            tbot_class::send(self::TG_CHAT_ID, $short . "\n" . $full);
            self::model('system_notifications')->insert([
                'id' => $id,
                'tg_sent' => 1
            ]);
        }
    }

    public static function getNewNotifications()
    {
        return self::model('system_notifications')->getByField('status_id', self::STATUS_NEW, true, 'create_date DESC', 10);
    }

    public static function markRead($id)
    {
        self::model('system_notifications')->insert([
            'id' => $id,
            'status_id' => self::STATUS_READ
        ]);
    }

    public static function markAllRead()
    {
        foreach (self::model('system_notifications')->getByField('status_id' , self::STATUS_NEW, true) as $item) {
            self::model('system_notifications')->insert([
                'id' => $item['id'],
                'status_id' => self::STATUS_READ
            ]);
        }
    }

}