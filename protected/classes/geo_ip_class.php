<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 12.04.18
 * Time: 10:14
 */
class geo_ip_class extends base
{
    /**
     * @param $ip
     * @return mixed
     */

    public static function getCountry($ip)
    {
        require_once PROTECTED_DIR . 'vendor/autoload.php';
        $reader = new GeoIp2\Database\Reader(PROTECTED_DIR . 'libs/geo_ip/GeoLite2-Country.mmdb');
        try {
            $geo = $reader->country($ip);
            return $geo->country->names['en'];
        } catch (Exception $exception) {
            return false;
        }
    }

    public static function getRegion($ip)
    {
        require_once PROTECTED_DIR . 'vendor/autoload.php';
        $reader = new GeoIp2\Database\Reader(PROTECTED_DIR . 'libs/geo_ip/GeoLite2-City.mmdb');
        $geo = $reader->city($ip);
        return $geo->subdivisions->names['en'];
    }

    public static function getCity($ip)
    {
        require_once PROTECTED_DIR . 'vendor/autoload.php';
        $reader = new GeoIp2\Database\Reader(PROTECTED_DIR . 'libs/geo_ip/GeoLite2-City.mmdb');
        try {
            $geo = $reader->city($ip);
            return $geo->city->names['en'];
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $country_code
     * @return bool
     */
    public function is_country($country_code)
    {
        return $this->detect() == strtoupper($country_code);
    }
}