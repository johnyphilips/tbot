<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Пользователи</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список Системных Пользователей</h3>
                <div class="card-options">
                    <button data-target="#add_modal" data-toggle="modal" id="add_user" class="btn-outline-secondary btn"><i class="fa fa-plus"></i> Добавить Пользователя</button>
                </div>
            </div>
            <div class="card-body custom-datatable">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="get_users">
                        <thead>
                        <tr>
                            <th><input type="text" class="form-control filter-field" name="u.id" data-sign="=" placeholder="Поиск"></th>
                            <th><input type="text" class="form-control filter-field" name="u.user_name" data-sign="like" placeholder="Поиск"></th>
                            <th>
                                <select name="u.role_id" class="form-control filter-field" data-sign="=">
                                    <option value="">Все группы</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Имя</th>
                            <th>Группа</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_modal">
    <div class="modal-dialog">
        <div class="modal-content" id="user_form_container">
        </div>
    </div>
</div>
<div class="modal fade" id="delete_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body with-padding">
                Точно удалить?
            </div>
            <div class="modal-footer">
                <button type="button" id="delete_btn" class="btn btn-primary">Удалить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    var User = {
        'save': function(id) {
            App.ajax.form('#user_form', 'save', function(msg) {
                App.ajax.response(msg, function() {
                    App.dataTable('get_users', 25);
                    $("#add_modal").modal('hide');
                    App.notify.success('Данные пользователя сохранены')
                }, function(response) {
                    if(response.error !== undefined) {
                        switch (response.error) {
                            case "not_unique":
                                $("#not_unique").slideDown();
                                break;
                            case "no_password":
                                $("#require_password").slideDown();
                                break;
                            default:
                                App.notify.error('Похоже у нас неизвестная ошибка');
                                break;
                        }
                    }
                })
            })
        },
        'getForm': function(id) {
            var data = {};
            if(id !== undefined) {
                data.id = id;
            }
            App.ajax.post('get_user_form', data, function(msg) {
                App.ajax.response(msg, function(response) {
                    $("#user_form_container").html(response.template);
                    if(undefined === id) {
                        $("#password").attr('data-require', 1);
                    } else {
                        $("#password").attr('data-require', 0);
                    }
                })
            });
        },
        'getDeleteForm': function(id) {
            $("#delete_btn").attr('data-id', id);
        },
        'deleteUser': function() {
            var id = $("#delete_btn").attr('data-id');
            App.ajax.post('delete_user', {id: id}, function() {
                $("#delete_modal").modal('hide');
                App.dataTable('get_users', 25);
                App.notify.success('Пользователь удален')
            })

        }
    };
    $(document).ready(function () {
        App.dataTable('get_users', 25);
        $("body").on("submit", "#user_form", function (e) {
            if(App.validate("#user_form")) {
                User.save();
            }
            return false;
        });
        $("#add_user").click(function() {
            User.getForm();
        });

        $("body").on("click", ".edit_user", function () {
            var id = $(this).attr('data-id');
            User.getForm(id);
        });

        $("body").on("click", ".delete_user", function () {
            var id = $(this).attr('data-id');
            User.getDeleteForm(id);
        });

        $("body").on("click", "#delete_btn", function () {
            User.deleteUser();
        });
    });
</script>
<!--pa-->