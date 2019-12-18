<?php if ($sum): ?>
<?php if ($sum >= deposit_service::MIN_WITHDRAW): ?>
You are about to withdraw <code><?php echo bitcoin_service::formatBTC($sum); ?> BTC</code> from your balance.
✅ Your current balance: 0.1 BTC.
Commission for withdrawal of funds - 4%.
You will receive in your wallet: .... BTC
Confirm withdrawal of funds?

 Your current balance is <code><?php echo bitcoin_service::formatBTC($sum); ?> BTC</code>.

👇🏽Enter the amount of BTC you wish to withdraw on your personal BTC wallet.
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


