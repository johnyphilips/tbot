Congratulations!
Your <?php echo $payment['referrer_level'] == 1 ? 'First' : $payment['referrer_level'] == 2 ? 'Second' : 'Third' ?> Level Referral <?php echo $user_name; ?> has just made a deposit and you got <b><?php echo bitcoin_service::formatBTC($payment['amount_btc']); ?> BTC</b> on your balance