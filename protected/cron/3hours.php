<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 22/08/2019
 * Time: 12:17
 */
define('ROOT_DIR', str_replace('protected' . DIRECTORY_SEPARATOR . 'cron', '', __DIR__));
define('PROJECT', 'bot');
require_once ROOT_DIR . '/protected/config.php';
require_once CORE_DIR . 'autoload.php';
cron_class::updateBTCRate();