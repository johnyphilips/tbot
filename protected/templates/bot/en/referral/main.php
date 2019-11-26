<?php if ($referrals): ?>
    <?php foreach ($referrals as $level => $level_referrals): ?>
        Level <?php echo $level; ?>:
        <?php foreach ($level_referrals as $referral): ?>
            <?php echo $referral['t_user_name']; ?> (You earned <?php echo $referral['deposits']/100 * deposit_service::REFERRER_PAYOUTS[$level]; ?> BTC)
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>