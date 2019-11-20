<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Списки IP</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Черный список</h3>
                <div class="card-options">
                    <button data-target="#add_blacklist_modal" id="add_blacklist" data-toggle="modal" class="btn-outline-secondary btn"><i class="fas fa-user-secret"></i> Внести в черный список</button>
                </div>
            </div>
            <div class="card-body custom-datatable">
                <table class="table table-bordered" id="get_blacklist">
                    <thead>
                    <tr>
                        <td>
                            <input type="text" class="form-control filter-field" name="l.ip" data-sign="like" placeholder="Поиск">
                        </td>
                        <td>
                            <input type="text" class="form-control filter-field" name="u.user_name" data-sign="like" placeholder="Поиск">
                        </td>
                        <td>
                            <input type="text" class="form-control filter-field dp" name="DATE(l.create_date)" data-sign="like" placeholder="Поиск">
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <th>IP</th>
                        <th>Кто внес</th>
                        <th>Дата Внесения</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Белый список</h3>
                <div class="card-options">
                    <button data-target="#add_whitelist_modal" id="add_whitelist" data-toggle="modal" class="btn-outline-secondary btn"><i class="fa fa-plus"></i> Внести в белый список</button>
                </div>
            </div>
            <div class="card-body custom-datatable">
                <table class="table table-bordered" id="get_whitelist">
                    <thead>
                    <tr>
                        <td>
                            <input type="text" class="form-control filter-field" name="l.ip" data-sign="like" placeholder="Поиск">
                        </td>
                        <td>
                            <input type="text" class="form-control filter-field" name="u.user_name" data-sign="like" placeholder="Поиск">
                        </td>
                        <td>
                            <input type="text" class="form-control filter-field dp" name="DATE(l.create_date)" data-sign="like" placeholder="Поиск">
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <th>IP</th>
                        <th>Кто внес</th>
                        <th>Дата Внесения</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<?php crud_module::getDeleteModal($controller, 'blacklist'); ?>
<?php crud_module::getDeleteModal($controller, 'whitelist'); ?>
<?php crud_module::getAddModal($controller, 'whitelist'); ?>
<?php crud_module::getAddModal($controller, 'blacklist'); ?>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        App.dataTable('get_blacklist');
        App.dataTable('get_whitelist');
        App.crud.delete('whitelist', function() {
            App.dataTable('get_whitelist');
        });
        App.crud.delete('blacklist', function() {
            App.dataTable('get_blacklist');
            App.dataTable('get_whitelist');
        });

        App.crud.addEdit('whitelist', function() {
            App.dataTable('get_whitelist');
            $("#add_whitelist_modal").modal('hide');
        });
        App.crud.addEdit('blacklist', function() {
            App.dataTable('get_blacklist');
            $("#add_blacklist_modal").modal('hide');
        });
    });
</script>
<!--pa-->
<style>

</style>