<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Выплаты</h1>
        </div>
    </div>

</div>
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список выплат пользователям</h3>
            </div>
            <div class="card-body custom-datatable">
                <table class="table table-bordered" id="get_withdrawals">
                    <thead>
                    <tr>
                        <th>
                            <input type="text" name="w.id" data-sign="=" class="form-control filter-field">
                        </th>
                        <th><input type="text" name="u.t_user_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="w.address" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="w.amount" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="p.amount_btc" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="w.tx_id" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="DATE(p.create_date)" data-sign="=" class="form-control filter-field dp"></th>
                    </tr>
                    <tr>
                        <th>Id</th>
                        <th>Клиент</th>
                        <th>Адрес</th>
                        <th>Сумма</th>
                        <th>Сумма BTC</i></th>
                        <th>Транзацкия</th>
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
        App.dataTable('get_withdrawals', 100);
    });
</script>
<!--pa-->