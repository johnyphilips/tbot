<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 22/10/2019
 * Time: 16:15
 */
class buttons_class
{
    public static function getMenu($user)
    {
        return [
            'ru' => [
                [
                    ['text' => hex2bin('F09F8C90') . ' '],
                ]
            ],
            'en' => [
                [
                    ['text' => hex2bin('F09F8C90') . ' My Account (Balance 0$)'],
                    ['text' => hex2bin('F09F8C90') . ' Deposit'],
                    ['text' => hex2bin('F09F8C90') . ' Withdraw Funds'],
                    ['text' => hex2bin('F09F8C90') . ' Referral Program'],
                    ['text' => hex2bin('F09F8C90') . ' About Us'],
                ]
            ]
        ];
    }
}
