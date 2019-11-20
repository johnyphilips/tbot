<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Профиль</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-md-6">
        <div class="card">
            <form id="profile_form" method="post">
                <div class="card-header">
                    <h3 class="card-title">Ваш Профиль</h3>
                    <div class="card-options">
                        <button type="submit" class="btn-outline-secondary btn"><i class="fa fa-save"></i> Сохранить </button>
                    </div>
                </div>
                <div class="card-body custom-datatable">
                    <div class="form-group">
                        <label class="control-label">Пароль</label>
                        <input type="text" name="password" id="password" autocomplete="off" class="form-control" value="" placeholder="Оставьте пустым, если не хотите менять">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $("body").on("submit", "#profile_form", function (e) {
            e.preventDefault();
            App.ajax.form("#profile_form", "save", function(msg) {
                App.ajax.response(msg, function() {
                    App.notify.success('Ваш Профиль сохранен');
                    $("#password").attr('type', 'password');
                });

            });
            return false;
        });
        $("#password").focus(function() {
            $(this).attr('type', 'text');
        });

    });
</script>