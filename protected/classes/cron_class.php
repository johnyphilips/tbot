<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 22/08/2019
 * Time: 12:13
 */
class cron_class extends staticBase
{
    public static function updateBTCRate()
    {
        if($rate = bitcoin_api::getBTCRate()) {
            self::model('system_config')->updateByField([
                'config_key' => 'btc_rate',
                'config_value' => $rate
            ], 'config_key');
        }
    }
}