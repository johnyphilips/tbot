Your deposit <b><?php echo ucfirst($deposit['plan']); ?></b> has been successfully closed.

You got your deposit on your balance in the amount of <?php echo bitcoin_service::formatBTC($deposit['amount_btc']); ?> BTC
Profit: <?php echo bitcoin_service::formatBTC($deposit['profit']); ?> BTC