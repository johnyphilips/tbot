<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 25/11/2019
 * Time: 16:20
 */
class deposit_service extends staticBase
{
    const PLANS = [
        'intro' => [
            'percent' => 3,
            'from' => '0.0005',
            'to' => '0.035',
            'term' => 60
        ],
        'popular' => [
            'percent' => 5,
            'from' => '0.035',
            'to' => '0.1',
            'term' => 45
        ],
        'professional' => [
            'percent' => 9,
            'from' => '0.1',
            'to' => '1',
            'term' => 30
        ]
    ];

    public static function getPlanBySum($sum)
    {
        if($sum == 1) {
            $plan  = self::PLANS['professional'];
            $plan['name'] = 'Professional';
            return $plan;
        }
        foreach (self::PLANS as $key => $plan) {
            if($sum >= $plan['from'] && $sum < $plan['to']) {
                $plan['name'] = ucfirst($key);
                return $plan;
            }
        }
        return false;
    }

}