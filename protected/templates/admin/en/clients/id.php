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
                            <td><?php echo $referrer['t_user_name']; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Игровой баланс</td>
                        <td><?php echo $user['balance']; ?></td>
                    </tr>
                    <tr>
                        <td>Призовой баланс</td>
                        <td><?php echo $user['prize_balance']; ?></td>
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
                <h3 class="card-title">Пополнения</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <td>Сумма</td>
                        <td>BTC</td>
                        <td>Статус</td>
                        <td>Дата</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($payments): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo $payment['amount']; ?></td>
                                <td><?php echo $payment['amount_btc']; ?></td>
                                <td><?php echo $payment['status_id']; ?></td>
                                <td><?php echo $payment['create_date']; ?></td>
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
                <h3 class="card-title">Активные лотереи</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td>Тираж</td>
                        <td>Приз</td>
                        <td>Тип</td>
                        <td>Номера</td>
                        <td></td>
                    </tr>
                    <?php foreach ($active_lotteries as $lottery): ?>
                        <tr>
                            <td>
                                <?php echo $lottery['edition_id']; ?>
                            </td>
                            <td>
                                <?php echo $lottery['amount']; ?>
                            </td>
                            <td>
                                <?php echo $lottery['total_bets']; ?> из <?php echo $lottery['cells_qty']; ?> за <?php echo $lottery['cell_price']; ?>
                            </td>
                            <td>
                                <?php echo implode(',', $lottery['bets']); ?>
                            </td>
                            <td>
                                <a href="/editions/id?id=<?php echo $lottery['edition_id']; ?>"><i class="fas fa-link"></i> </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Старые лотереи</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td>Тираж</td>
                        <td>Приз</td>
                        <td>Цена</td>
                        <td>Номера</td>
                        <td>Победитель</td>
                        <td></td>
                    </tr>
                    <?php foreach ($closed_lotteries as $lottery): ?>
                        <tr>
                            <td>
                                <?php echo $lottery['edition_id']; ?>
                            </td>
                            <td>
                                <?php echo $lottery['amount']; ?>
                            </td>
                            <td>
                                <?php echo $lottery['cells_qty']; ?> по <?php echo $lottery['cell_price']; ?>
                            </td>
                            <td>
                                <?php echo implode(',', $lottery['bets']); ?>
                            </td>
                            <td>
                                <a href="/cliens/id?id=<?php echo $lottery['winner_id']; ?>">@<?php echo $lottery['t_user_name']; ?></a>
                            </td>
                            <td>
                                <a href="/editions/id?id=<?php echo $lottery['edition_id']; ?>"><i class="fas fa-link"></i> </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Рулетки</h3>
            </div>
            <div class="card-body custom-datatable">
                <table class="table table-bordered" id="get_roulettes">
                    <thead>
                    <tr>
                        <td>
                            <input type="text" class="form-control filter-field" name="r.id" data-sign="=" autocomplete="off">
                            <input type="hidden" class="filter-field" name="r.user_id" data-sign="=" value="<?php echo $_GET['id']; ?>">
                        </td>
                        <td>
                            <select class="form-control filter-field" name="r.id" data-sign="=">
                                <option value=""></option>
                                <option value="<?php echo roulette_service::STATUS_ACTIVE; ?>">Активная</option>
                                <option value="<?php echo roulette_service::STATUS_CLOSED; ?>">Закончена</option>

                            </select>
                        </td>
                        <td></td>
                        <td><input type="text" class="form-control filter-field" name="r.won" data-sign="=" autocomplete="off"> </td>
                        <td> </td>
                        <td><input type="text" class="form-control filter-field" name="r.spent" data-sign="=" autocomplete="off"> </td>
                        <td><input type="text" class="form-control filter-field dp" name="DATE(r.create_date)" data-sign="=" autocomplete="off"> </td>
                        <td><input type="text" class="form-control filter-field dp" name="DATE(r.close_date)" data-sign="=" autocomplete="off"> </td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Статус</th>
                        <th>Спинов</th>
                        <th>Выигрыш</th>
                        <th>Макс</th>
                        <th>Потрачено</th>
                        <th>Дата начала</th>
                        <th>Дата завершения</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
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