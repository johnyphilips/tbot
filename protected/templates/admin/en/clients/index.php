<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Клиенты</h1>
        </div>
    </div>

</div>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список Клиентов</h3>
            </div>
            <div class="card-body custom-datatable">
                <table class="table table-bordered" id="get_clients">
                    <thead>
                    <tr>
                        <th>
                            <input type="text" name="u.id" data-sign="=" class="form-control filter-field">
                        </th>
                        <th><input type="text" name="u.t_user_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="u.first_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="u.last_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="u.chat_id" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="r.t_user_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="u.balance" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="u.prize_balance" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="DATE(u.create_date)" data-sign="=" class="form-control filter-field dp"></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Id</th>
                        <th>Логин</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Chat Id</th>
                        <th>Реферер</th>
                        <th>Баланс</th>
                        <th>Призовой</th>
                        <th>Дата</th>
                        <th></th>
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
        App.dataTable('get_clients', 100);
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