<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Клиент - <?php echo $user['t_user_name']; ?></h1>
        </div>
    </div>

</div>
<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Инфо</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Chat Id</td>
                        <td><?php echo $user['chat_id']; ?></td>
                    </tr>
                    <tr>
                        <td>Имя</td>
                        <td><?php echo $user['first_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Фамилия</td>
                        <td><?php echo $user['last_name']; ?></td>
                    </tr>
                    <?php if ($referrer): ?>
                        <tr>
                            <td>Referrer</td>
                            <td><a href="/clients/id?id=<?php echo $referrer['id']; ?>"><?php echo $referrer['t_user_name']; ?></a> </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Баланс</td>
                        <td><?php echo $user['balance']; ?></td>
                    </tr>
                    <tr>
                        <td>Дата</td>
                        <td><?php echo $user['create_date']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Депозиты</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <td>План</td>
                        <td>Сумма</td>
                        <td>Статус</td>
                        <td>Дата</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($deposits): ?>
                        <?php foreach ($deposits as $deposit): ?>
                            <tr>
                                <td><?php echo $deposit['plan']; ?></td>
                                <td><?php echo $deposit['amount_btc']; ?></td>
                                <td><?php echo $deposit['status_id']; ?></td>
                                <td><?php echo $deposit['create_date']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Выводы</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Сумма</td>
                        <td>BTC</td>
                        <td>Дата</td>
                        <td>Ссылка</td>
                    </tr>
                    <tbody>
                    <?php if ($withdrawals): ?>
                        <?php foreach ($withdrawals as $withdrawal): ?>
                            <tr>
                                <td><?php echo $withdrawal['amount']; ?></td>
                                <td><?php echo $withdrawal['amount_btc']; ?></td>
                                <td><?php echo $withdrawal['create_date']; ?></td>
                                <td>
                                    <?php if ($withdrawal['tx_id']): ?>
                                        <a href="<?php echo CHECK_TRANSACTION_URL; ?><?php echo $withdrawal['tx_id']; ?>" class="btn btn-default btn-icon">
                                            <i class="fas fa-link"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Рефералы</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Уровень</td>
                        <td>Юзер</td>
                        <td>Депозиты</td>
                        <td>Выплаты</td>
                    </tr>
                    <tbody>
                    <?php if ($referrals): ?>
                        <?php foreach ($referrals as $level => $referral_level): ?>
                            <?php foreach ($referral_level as $referral): ?>
                                <tr>
                                    <td><?php echo $level; ?></td>
                                    <td><a href="/cliets/id?id=<?php echo $referral['id']; ?>"><?php echo $referral['t_user_name']; ?></a></td>
                                    <td><?php echo $referral['deposits']; ?></td>
                                    <td><?php echo $referral['payouts']; ?></td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="message_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Сообщение для <span id="message_name"></span></h4>
            </div>
            <div class="modal-body with-padding">
                <textarea class="form-control" id="message"></textarea>
                <input type="hidden" id="chat_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="send_message">Отправить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>

<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        App.dataTable('get_roulettes', 10);
        $("body").on("click", ".send_message", function () {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $("#message").val('');
            $("#send_message").prop('disabled', false);
            $("#chat_id").val(id);
            $("#message_name").html(name);
        });

        $("body").on("click", "#send_message", function () {
            $(this).prop('disabled', true);
            var data = {
                'id' : $("#chat_id").val()
            };
            data.val = $("#message").val();
            if(data.val) {
                App.ajax.json('send_message', data, function() {
                    Notifier.success('Сообщение отправлено');
                    $("#message").val('');
                    $("#message_modal").modal('hide');
                }, function() {
                    Notifier.error('Сообщение не было отправлено');
                    $("#message").val('');
                    $("#message_modal").modal('hide');
                }, function() {
                    Notifier.error('Сообщение не было отправлено');
                    $("#message").val('');
                    $("#message_modal").modal('hide');
                });
            }
        });
    });
</script>
<!--pa-->