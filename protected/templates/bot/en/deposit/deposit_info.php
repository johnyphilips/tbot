💰 You are going to deposit a total value of <code><?php echo round(number_format($sum, 8), 8); ?></code> BTC.

You will purchase <b><?php echo $plan['plan_name']; ?> plan</b>.

💡 <b>Reminder:</b> The address is active for <b><?php echo (bitcoin_service::WAIT_FOR_PAYMENT/60); ?> minutes</b>.

♻️ <b>After this time has expired, please request a new BTC address.</b>

👇 To complete the deposit transfer <code><?php echo round(number_format($sum, 8), 8); ?></code> BTC to the address below.