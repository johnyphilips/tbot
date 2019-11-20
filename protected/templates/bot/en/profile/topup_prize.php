💡 <b>In this section, you transfer Bingo coins from your Winning balance to the Lottery balance (game balance).</b>

🎰 <b>Lottery balance</b> <i>is intended for participation in lotteries.</i>
🍀 <b>Winning balance </b><i>is designed to receive prizes from lotteries.</i>
🔶 Withdrawal of funds is available from this account.

⚠️ At the moment, your Winning balance has <code><?php echo $user['prize_balance']; ?> coins</code>.
<?php if (balance_service::MIN_TOPUP_PRIZE): ?>
📈 <b>Minimum amount of coins to transfer is 200 coins.</b>
<?php endif; ?>

<?php if ($user['prize_balance'] >= balance_service::MIN_TOPUP_PRIZE): ?>
How many coins do you want to transfer to a Lottery account?
👇 <b>Please, enter number of coins:</b>
<?php else: ?>
♻️ <b>Please use another way to replenish your balance.</b>
<?php endif; ?>
