💰 You are going to buy <b><?php echo $sum; ?> Bingo Coins</b> a total value of <code><?php echo round(number_format($btc_sum, 8), 8); ?></code> BTC.

💡 <b>Reminder:</b> The address is active for <b><?php echo (bitcoin_service::WAIT_FOR_PAYMENT/60); ?> minutes</b>.

♻️ <b>After this time has expired, please request a new BTC address.</b>

👇 To complete the purchase of <b><?php echo $sum; ?> Bingo Coins</b> transfer <code><?php echo round(number_format($btc_sum, 8), 8); ?></code> BTC to the address below.