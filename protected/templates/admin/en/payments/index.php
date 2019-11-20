<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Платежи</h1>
        </div>
    </div>

</div>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список платежей</h3>
            </div>
            <div class="card-body custom-datatable">
                <table class="table table-bordered" id="get_payments">
                    <thead>
                    <tr>
                        <th>
                            <input type="text" name="p.id" data-sign="=" class="form-control filter-field">
                        </th>
                        <th>
                            <select class="form-control filter-field" name="p.status_id" data-sign="=">
                                <option value="">Все</option>
                                <option value="<?php echo bitcoin_service::PAYMENT_STATUS_NEW; ?>">Новая</option>
                                <option value="<?php echo bitcoin_service::PAYMENT_STATUS_NO_CONFIRMATIONS; ?>">Неподтвержд</option>
                                <option value="<?php echo bitcoin_service::PAYMENT_STATUS_CONFIRMED; ?>">Выполнена</option>
                                <option value="<?php echo bitcoin_service::PAYMENT_STATUS_CANCELLED; ?>">Отменена</option>
                            </select>
                        </th>
                        <th><input type="text" name="u.t_user_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="p.amount" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="p.amount_btc" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="p.address" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="DATE(p.create_date)" data-sign="=" class="form-control filter-field dp"></th>
                        <th><input type="text" name="DATE(p.pay_date)" data-sign="=" class="form-control filter-field dp"></th>
                    </tr>
                    <tr>
                        <th>Id</th>
                        <th>Статус</th>
                        <th>Клиент</th>
                        <th>Сумма</th>
                        <th>Сумма BTC</i></th>
                        <th>Адрес</th>
                        <th>Дата</th>
                        <th>Дата Оплаты</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        App.dataTable('get_payments', 100);
    });
</script>
<!--pa-->