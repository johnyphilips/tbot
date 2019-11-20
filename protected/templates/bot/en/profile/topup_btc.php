💡 <b>In order to make a bet in any lottery or play Jocker by Bingo Club, you need Bingo Coins.</b>

✅ In this section you can purchase <b>Bingo Coins</b> through Bitcoin transaction.

💰 <b>1 coin =</b><code> <?php echo balance_service::COIN_COST; ?> BTC</code> or <code><?php echo balance_service::COIN_COST * 100000000; ?> satoshi</code> (around <b>$<?php echo round(balance_service::COIN_COST * $btc_rate, 2); ?></b> by current rate)

🧾 Minimum number of coins to buy -<b> <?php echo balance_service::MIN_TOPUP; ?></b><b> or <?php echo balance_service::MIN_TOPUP * balance_service::COIN_COST; ?> BTC</b> (around <b>$<?php echo round(balance_service::MIN_TOPUP * balance_service::COIN_COST * $btc_rate, 2); ?></b> by current rate)

❗️<i>If you don't have Bitcoin yet,  You can buy it with your credit/debit card or in any other convenient way in a </i><a href="https://localbitcoins.com">trusted market</a>

<?php if ($discount): ?>
🎉 <b>Congratulations! You will get <?php echo $discount; ?>% Coins more for free!</b>
<?php endif; ?>

👇 <b>Enter the amount of Bingo coins you would like to purchase below:</b>