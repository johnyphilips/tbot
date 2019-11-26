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
                            <input type="text" name="d.id" data-sign="=" class="form-control filter-field">
                        </th>
                        <th>
                            <select name="d.status_id" data-sign="=" class="form-control filter-field">
                                <option value=""></option>
                                <option value="1">Открыт</option>
                                <option value="2">Закрыт</option>
                            </select>
                        </th>
                        <th><input type="text" name="DATEDIFF(NOW(), d.create_date)" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="d.amount_btc" data-sign="=" class="form-control filter-field"></th>
                        <th><input type="text" name="d.profit" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="u.t_user_name" data-sign="like" class="form-control filter-field"></th>
                        <th><input type="text" name="DATE(d.create_date)" data-sign="=" class="form-control filter-field dp"></th>
                    </tr>
                    <tr>
                        d.id',
                        'IF(d.status_id = 1, "Открыт", "Закрыт")',
                        'd.plan',
                        'DATEDIFF(NOW(), d.create_date)',
                        'd.amount_btc',
                        'd.profit',
                        '<a href=\"/clients/id?id=", r.id, "\">u.t_user_name</a>',
                        "d.create_date"
                        <th>Id</th>
                        <th>Статус</th>
                        <th>План</th>
                        <th>Срок</th>
                        <th>Депозит</th>
                        <th>Профит</th>
                        <th>Юзер</th>
                        <th>Дата</th>
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
        App.dataTable('get_clients', 100);
    });
</script>
<!--pa-->