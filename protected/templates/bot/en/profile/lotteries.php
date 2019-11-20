<?php if ($lotteries): ?>
🎰 <b>Lotteries you are participating in:</b>
<?php $i = 1; ?>

<?php foreach ($lotteries as $k => $lottery): ?>
<b><?php echo $i ++; ?>. Lottery #<?php echo $lottery['edition_id'] ?></b>
🎁 The main prize – <b><?php echo $lottery['lottery_type'] == 1 ? number_format($lottery['amount'], 0, '.', ',') : $lottery['title']; ?></b>
<?php if (count($lottery['bets']) < 20): ?>
🎫 Your active ticket(s) - <b>#<?php echo implode(', #', $lottery['bets']); ?></b>
<?php else: ?>
🎫 Your active ticket(s) numbers are from <b><?php echo $lottery['bets'][0]; ?></b> to <b><?php echo $lottery['bets'][count($lottery['bets']) - 1]; ?></b>
<?php endif; ?>
🎯 Tickets bought already by all participants: <b><?php echo $lottery['total_bets']; ?></b> out of total <b><?php echo $lottery['cells_qty']; ?></b>

<?php endforeach; ?>
<?php else: ?>
⭕️ <b>At the moment you are not participating in any of the lotteries.</b>

👇 Click the button below to see all the active lotteries and participate.

🎁 <i>Almost everyone wins with us.</i>
<?php endif; ?>
💬 <b>Visit our chat</b> – <?php echo CHAT_NAME; ?>

✅ <b>Check past lottery results</b> - <?php echo CHANNEL_NAME; ?>