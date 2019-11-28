<b>Current Balance:</b> <code><?php echo bitcoin_service::formatBTC($user['balance']); ?> BTC</code>
<b>Total invested:</b>  <code><?php echo bitcoin_service::formatBTC($invested); ?> BTC</code>
<b>Total earned:</b> <code><?php echo bitcoin_service::formatBTC($earned); ?> BTC</code>
<b>Active Investment plans:</b> <?php echo $deposits; ?> plan<?php echo $deposit > 1 ? 's' : '' ?>
➖➖➖➖➖➖➖➖➖
👫 <b>Total Referrals Invited:</b> <?php echo $referrals; ?>
<b>Active Referrals:</b> <?php echo $active_referrals; ?>
<b>Earned from Referrals:</b>  <?php echo $earned_referrals; ?> BTC

♾ <b>My referral link:</b> <?php echo $referral_link; ?>
➖➖➖➖➖➖➖➖➖

<?php if ($deposits): ?>
💰 <b>My Active Investment(s):</b>
<?php foreach ($deposits as $plan => $plan_deposits): ?>
<b> - <?php echo strtoupper($plan); ?></b>, payouts every 3 hours.
<?php foreach ($plan_deposits as $deposit): ?>
<b>Daily profit</b> <?php echo deposit_service::PLANS[strtolower($plan)]['percent']; ?>%, <b>duration</b> <?php echo deposit_service::PLANS[strtolower($plan)]['term']; ?> days, <b>Profit</b>:  <?php echo deposit_service::PLANS[strtolower($plan)]['term'] * deposit_service::PLANS[strtolower($plan)]['precent']; ?>%
<?php if ($deposit['status_id'] == 1): ?>
Next payment: <?php echo $deposit['next_payment']; ?> (UTC)
<?php else: ?>
<b>Closed</b>
<?php endif; ?>

<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>
