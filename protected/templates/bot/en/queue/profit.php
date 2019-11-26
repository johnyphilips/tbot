You have received profit in the amount of <b><?php echo bitcoin_service::formatBTC($profit); ?> BTC</b>

Plan: <?php echo $deposit['plan']; ?>

Deposit: <?php echo bitcoin_service::formatBTC($deposit['amount_btc']); ?>

Ends in <?php echo (deposit_service::PLANS[strtolower($deposit['plan'])]['term'] - tools_class::dateDiff(date('Y-m-d', strtotime($deposit['create_date'])))->days); ?> days