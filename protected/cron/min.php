<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 16.05.18
 * Time: 10:17
 */
define('ROOT_DIR', str_replace('protected' . DIRECTORY_SEPARATOR . 'cron', '', __DIR__));
define('PROJECT', 'bot');
require_once ROOT_DIR . '/protected/config.php';
require_once CORE_DIR . 'autoload.php';
queue_service::send();
deposit_service::makeProfits();
bitcoin_service::checkTransactions();