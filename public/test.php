<?php
echo strtotime(date('Y-m-d H:i:s') . ' + ' . 24/deposit_service::UPDATE_PROFIT_PER_DAY . ' + hours');
//require_once '../protected/cron/test.php';