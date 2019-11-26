You have received profit in the amount of <b><?php echo $profit; ?> BTC</b>

Plan: <?php echo $deposit['plan']; ?>

Deposit: <?php echo $deposit['plan']; ?>

Ends in <?php echo (deposit_service::PLANS[strtolower($plan)]['term'] - tools_class::dateDiff(date('Y-m-d', strtotime($deposit['create_date'])))->days); ?> days