📈 By participating in our <b>Referral program</b>, you can earn extra profit.
💡 <b>The higher your activity, the higher your additional profit! At least it's worth a try.</b>

Each time your referral buys <b>Bingo coins</b>, you get <code>5%</code> of each coin purchase on Your Balance.

👫 <b>Your referral link:</b> <?php echo $referral_link; ?>

🤝 <b>Total number of friends invited by you: <?php echo count($referrals); ?></b>

<?php if ($referrals): ?>
<?php if ($referrals): ?>
<?php foreach ($referrals as $referral): ?>
@<?php echo $referral['t_user_name']; ?>

<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>