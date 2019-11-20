<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Логи</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Попытки Авторизации</h3>
            </div>
            <div class="card-body custom-datatable">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="get_logs">
                        <thead>
                        <tr>
                            <th><input type="text" class="form-control filter-field" name="a.id" data-sign="=" placeholder="Поиск"></th>
                            <th>
                                <select name="a.auth_status" class="form-control filter-field" data-sign="=">
                                    <option value="">Все</option>
                                    <option value="1">Success</option>
                                    <option value="2">Fail</option>
                                </select>
                            </th>
                            <th><input type="text" class="form-control filter-field" name="a.user_id" data-sign="=" placeholder="Поиск"></th>
                            <th><input type="text" class="form-control filter-field" name="a.login" data-sign="like" placeholder="Поиск"></th>
                            <th><input type="text" class="form-control filter-field" name="a.ip" data-sign="like" placeholder="Поиск"></th>
                            <th><input type="text" class="form-control filter-field" name="a.ua" data-sign="like" placeholder="Поиск"></th>
                            <th><input type="text" class="form-control filter-field" name="a.geo_data" data-sign="like" placeholder="Поиск"></th>
                            <th><input type="text" class="form-control filter-field datepicker" name="a.create_date" data-sign="like" placeholder="Поиск"></th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Статус</th>
                            <th>User Id</th>
                            <th>Логин</th>
                            <th>IP</th>
                            <th>Гео</th>
                            <th>User-Agent</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        App.dataTable('get_logs', 25);
    });
</script>
<!--pa-->