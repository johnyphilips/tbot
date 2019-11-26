You have made a deposit of <b><?php echo $sum; ?> BTC</b> which is less then minimum sum of <b><?php echo deposit_service::PLANS['intro']['from']; ?> BTC</b>

Please deposit <b><?php echo deposit_service::PLANS['intro']['from'] - $sum; ?> BTC</b> or more to start making profit.