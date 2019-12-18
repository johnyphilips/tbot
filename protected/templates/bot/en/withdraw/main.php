<?php if ($sum): ?>
<?php if ($sum >= deposit_service::MIN_WITHDRAW): ?>
✅ Your current balance is <code><?php echo bitcoin_service::formatBTC($sum); ?> BTC</code>.

👇🏽Enter the amount of BTC you wish to withdraw.
<?php else: ?>
💵 To withdraw funds, your account must have at least <code><?php echo deposit_service::MIN_WITHDRAW; ?> BTC</code>. This is our minimum withdrawal.

🚫 Your current balance is <code><?php echo bitcoin_service::formatBTC($sum); ?> BTC</code>.

💰 Open a deposit and earn money with us stably and reliably every day!
<?php endif; ?>
<?php else: ?>
💵 To withdraw funds, your account must have at least <code><?php echo deposit_service::MIN_WITHDRAW; ?> BTC</code>. This is our minimum withdrawal.

🚫 Your current balance is <code>0.0000 BTC</code>.

💰 Open a deposit and earn money with us stably and reliably every day!
<?php endif; ?>


