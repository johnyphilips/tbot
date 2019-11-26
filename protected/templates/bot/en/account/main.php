Account Menu
<b>Your Balance: </b><code><?php echo bitcoin_service::formatBTC($user['balance']); ?> BTC</code>

<b>Your referral link: </b><?php echo $referral_link; ?>

<?php if ($deposits): ?>
<b>Your deposits</b>
<?php foreach ($deposits as $plan => $plan_deposits): ?>
<b><?php echo ucfirst($plan); ?></b>
<?php foreach ($plan_deposits as $deposit): ?>
Deposit: <?php echo bitcoin_service::formatBTC($deposit['amount_btc']); ?> BTC
Profit: <?php echo bitcoin_service::formatBTC($deposit['profit']); ?> BTC
            <?php echo deposit_service::PLANS[$plan]['term']; ?>
End in <?php echo (deposit_service::PLANS[$plan]['term'] - tools_class::dateDiff(date('Y-m-d', strtotime($deposit['create_date'])))->days); ?> days

<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>
