<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 02/06/2019
 * Time: 15:25
 */
define('ROOT_DIR', str_replace('protected' . DIRECTORY_SEPARATOR . 'cron', '', __DIR__));
define('PROJECT', 'bot');
require_once ROOT_DIR . '/protected/config.php';
require_once CORE_DIR . 'autoload.php';
