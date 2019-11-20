<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 13.06.18
 * Time: 20:14
 */
class ip_class extends staticBase
{
    public static function getIpFromHeaders()
    {
        $forwardIp = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
        $remoteIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $clientIP = ip_class::Translate($forwardIp, $remoteIp);
        return $clientIP;
    }

    public static function translate($ipForwardedFor, $ipRemoteAddr) {
        $clientIpString = self::GetRelevantIpString($ipForwardedFor, $ipRemoteAddr);
        $clientIps = self::SplitIpList($clientIpString);
        return self::GetFirstPublicIp($clientIps);
    }

    private static function getFirstPublicIp($clientIps) {
        $count = count($clientIps);
        for ($i = 0; $i < $count; $i++) {
            if (!self::IsPrivateNetworkIp(trim($clientIps[$i]))) return trim($clientIps[$i]);
        }

        return '6.0.0.1';
    }

    private static function isPrivateNetworkIp($ip) {
        //10.0.0.0
        if (substr($ip, 0, strlen('10.')) == '10.') return true;
        //192.168.0.0
        if (substr($ip, 0, strlen('192.168.')) == '192.168.') return true;
        //172.16.0.0 - 172.31.255.255
        if (substr($ip, 0, strlen('172.')) != '172.')  return false;
        if (substr($ip, strlen('172.31'), 1) != '.')  return false;
        $subClass = substr($ip, strlen('172.'), 2);
        return ($subClass >= 16 && $subClass <= 31);
    }

    private static function splitIpList($clientIpString) {
        return explode(',', $clientIpString);
    }

    private static function getRelevantIpString($ipForwardedFor, $ipRemoteAddr) {
        if (!empty($ipForwardedFor)) return $ipForwardedFor;

        if (!empty($ipRemoteAddr)) return $ipRemoteAddr;

        return '6.0.0.1';
    }
}