<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 23/05/2019
 * Time: 12:37
 */
//exit;
define('ROOT_DIR', str_replace('protected' . DIRECTORY_SEPARATOR . 'manual', '', __DIR__));
define('PROJECT', 'bot');
require_once ROOT_DIR . '/protected/config.php';
require_once CORE_DIR . 'autoload.php';
$sum = 0.027;
$address = '';
bitcoin_service::getProfits($sum, $address);