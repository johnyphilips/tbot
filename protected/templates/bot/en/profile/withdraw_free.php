<?php if ($sum): ?>
💵 Withdrawals are available from <b>Your Balance</b>.

💰 You have <code><?php echo $sum; ?> coins</code> on <b>Your Balance</b>.
<?php if ($sum >= balance_service::MIN_FREE_WITHDRAW): ?>
👇 Please write below the number of coins that you are going to withdraw

❗️The minimum withdrawal amount is <code><?php echo balance_service::MIN_FREE_WITHDRAW; ?> coins</code>.
<?php else: ?>
🚫 Sorry, withdrawal is not possible because you do not have enough coins.

❗️The minimum withdrawal amount is <code><?php echo balance_service::MIN_FREE_WITHDRAW; ?> coins</code>.
<?php endif; ?>
<?php else: ?>
💵 Withdrawals are available from <b>Your Balance</b>.

💰 You don't have coins on <b>Your Balance</b>.

🚫 Sorry, withdrawal is not possible because you did not win free lottery.

<?php endif; ?>


