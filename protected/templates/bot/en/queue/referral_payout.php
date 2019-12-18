smile_F09F8F86 A referral from your <b>Level <?php echo $payout['referrer_level']; ?></b> has just made an investment, your profit of <b><?php echo bitcoin_service::formatBTC($payout['amount_btc']); ?> BTC</b> (<?php echo deposit_service::REFERRER_PAYOUTS[$payout['referrer_level']]; ?>%) has been transferred to your balance.

smile_F09F9A80 <b>Congratulations!</b>

Do not stop and you will earn much more!

<b>Your referral link is: <?php echo $referral_link; ?></b>