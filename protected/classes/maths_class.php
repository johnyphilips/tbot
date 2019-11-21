<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 28/05/2019
 * Time: 15:59
 */
class maths_class
{
    public function __construct()
    {
        require_once PROTECTED_DIR . 'vendor/autoload.php';
//        $a = '38577436816494942484';
//        $b = Litipk\BigNumbers\Decimal::create($a, 0);
//        $c = Litipk\BigNumbers\Decimal::create(10, 0);
//        $d = Litipk\BigNumbers\Decimal::create(-18, 0);
//        var_dump($b->mul($c->pow($d, 18)));
    }

    public function getEthFromInt($int)
    {
        $b = Litipk\BigNumbers\Decimal::create($int, 0);
        $c = Litipk\BigNumbers\Decimal::create(10, 0);
        $d = Litipk\BigNumbers\Decimal::create(-18, 0);
        return $b->mul($c->pow($d, 18));
    }

    public function getLtcFromInt($int)
    {
        $b = Litipk\BigNumbers\Decimal::create($int, 0);
        $c = Litipk\BigNumbers\Decimal::create(10, 0);
        $d = Litipk\BigNumbers\Decimal::create(-18, 0);
        return $b->mul($c->pow($d, 18));
    }

    public function getBtcFromInt($int)
    {
        $b = Litipk\BigNumbers\Decimal::create($int, 0);
        $c = Litipk\BigNumbers\Decimal::create(10, 0);
        $d = Litipk\BigNumbers\Decimal::create(-8, 0);
        return $b->mul($c->pow($d, 8));
    }

    public function countPnt($currency_value, $rate)
    {
        $currency_value = Litipk\BigNumbers\Decimal::create($currency_value, 18);
        $rate = Litipk\BigNumbers\Decimal::create($rate, 2);
        $b = $currency_value->div($rate);
        return $b;
    }

    public function countUsd($currency_value, $rate)
    {
        $currency_value = Litipk\BigNumbers\Decimal::create($currency_value, 18);
        $rate = Litipk\BigNumbers\Decimal::create($rate, 2);
        $b = $currency_value->mul($rate);
        return $b;
    }

    public function mulCurrencies($a, $b)
    {
        $a = Litipk\BigNumbers\Decimal::create($a, 18);
        $b = Litipk\BigNumbers\Decimal::create($b, 18);
        return $a->mul($b)->innerValue();
    }

    public function addCurrencies($a, $b)
    {
        $a = Litipk\BigNumbers\Decimal::create($a, 18);
        $b = Litipk\BigNumbers\Decimal::create($b, 18);
        return $a->add($b)->innerValue();
    }

    public function subCurrencies($a, $b)
    {
        $a = Litipk\BigNumbers\Decimal::create($a, 18);
        $b = Litipk\BigNumbers\Decimal::create($b, 18);
        return $a->sub($b)->innerValue();
    }

    public function divCurrencies($a, $b)
    {
        $a = Litipk\BigNumbers\Decimal::create($a, 18);
        $b = Litipk\BigNumbers\Decimal::create($b, 18);
        return $a->div($b)->innerValue();
    }

    public function addBonus($pnt_value, $bonus_percent)
    {
        $a = Litipk\BigNumbers\Decimal::create($pnt_value, 18);
        $b = Litipk\BigNumbers\Decimal::create($bonus_percent/100, 3);
        $sum = $a->mul($b);
        return $a->add($sum)->innerValue();
    }

    public function getPercent($pnt_value, $percent)
    {
        $a = Litipk\BigNumbers\Decimal::create($pnt_value, 18);
        $b = Litipk\BigNumbers\Decimal::create($percent/100, 3);
        $sum = $a->mul($b)->innerValue();
        return $sum;
    }
}