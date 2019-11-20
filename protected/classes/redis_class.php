<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 07/04/2019
 * Time: 19:29
 */
class redis_class extends base
{
    private $client;
    function __construct()
    {
        require_once PROTECTED_DIR . 'vendor/autoload.php';
        $this->client = $client = new Predis\Client();
    }

    /**
     * @param $key
     * @param $val
     * @param int $expire
     */

    public function set($key, $val, $expire = 60)
    {
        $this->client->set($key, $val, 'EX', $expire);
    }

    /**
     * @param $key
     * @return string
     */

    public function get($key)
    {
        return $this->client->get($key);
    }
}
