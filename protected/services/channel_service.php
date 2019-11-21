<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 28/05/2019
 * Time: 15:06
 */
class channel_service extends staticBase
{

    public static function proceed()
    {
        $last_checked = self::model('checked_blocks')->getAll('create_date DESC', 1)[0]['block_no'];
        $block_no = $last_checked + 1;
        self::checkBlock($block_no);
    }

    private static function checkBlock($block_no)
    {
        $res = bitcoin_api::fake($block_no);
//        $res = json_decode('{"status":"success","res":[{"tx":"8d461c5e2dd9739af33af30ef16995ba2e71f962d05c40fb25184736663f39b8","value":0.002},{"tx":"33fbfdf8794b9565b630eee77fd3747be58e86f509cd3062b7734f728149c24d","value":0.002},{"tx":"90d187375f32fdbe2c32a6e09b267330d172d49db3b6e93df5a5805a91158398","value":0.002}]}', true);
        if($res['res']) {
            foreach ($res['res'] as $tx) {
                if($tx['value'] && $tx['tx']) {
                    self::render('amount', $tx['value']);
                    self::render('tx_id', $tx['tx']);
                    if(in_array($tx['value'], [
                        0.1,
                        0.025,
                        0.005,
                        0.01,
                        0.001
                    ])) {
                        $tx = [
                            'tx_id' => $tx['tx'],
                            'tx_sum' => $tx['value'],
                            'create_date' => tools_class::gmDate()
                        ];
                        self::writeLog('fake', $tx);
                        self::model('fake_transactions')->insert($tx);
                    }
                }
            }
        }
        self::model('checked_blocks')->insert([
            'block_no' => $block_no,
            'create_date' => tools_class::gmDate()
        ]);
    }

    public static function sendPastWiathdrawals()
    {
        foreach (self::model('withdrawals')->getAll('id desc' ,1) as $item) {
            if($item['tx_id']) {
                self::render('tx_id', $item['tx_id']);
                self::render('sum', $item['amount_btc']);
//                queue_service::add(WITHDRAWAL_CHANNEL, self::fetch('templates/bot/en/queue/withdrawal_channel', true));
            }
        }
    }
}