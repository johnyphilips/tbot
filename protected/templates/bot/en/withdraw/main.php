<?php if ($sum): ?>
💵 Withdrawals are available from <b>Your Balance</b>.

💰 You have <code><?php echo bitcoin_service::formatBTC($sum); ?> BTC</code> on <b>Your Balance</b>.
<?php if ($sum >= deposit_service::MIN_WITHDRAW): ?>
👇 Please write below the number of coins that you are going to withdraw

❗️The minimum withdrawal amount is <code><?php echo deposit_service::MIN_WITHDRAW; ?> BTC</code>.
<?php else: ?>
🚫 Sorry, withdrawal is not possible because you do not have enough coins.

❗️The minimum withdrawal amount is <code><?php echo deposit_service::MIN_WITHDRAW; ?> BTC</code>.
<?php endif; ?>
<?php else: ?>
💵 Withdrawals are available from <b>Your Balance</b>.

💰 You don't have bitcoins on <b>Your Balance</b>.

🚫 Sorry, withdrawal is not possible because you do not have bitcoins.

<?php endif; ?>


