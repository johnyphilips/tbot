<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-12">
            <h1 class="page-title">Notifications</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-md-9">

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Form</h2>
            </div>
            <div class="card-body">
                <form id="send_form" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>User Name/Chat id</label>
                                <input type="text" class="form-control" name="user_name">
                            </div>

                            <div class="form-group">
                                <select name="type" class="form-control" id="type">
                                    <option value="">Одному</option>
                                    <option value="all">Всем</option>
                                </select>
                            </div>
                            <div class="form-group" style="display: none;" id="matrix_type_div">
                                <label>План Матрицы</label>
                                <select name="matrix_type" class="form-control" >
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Text</label>
                                <textarea rows="10" class="form-control" name="message"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default btn-icon"><i class="fas fa-envelope"></i> Send</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div class="col-md-3">
😎smile_F09F988E<br>
❗smile_E29D97<br>
💰smile_F09F92B0<br>
✅smile_E29C85<br>
🏄smile_F09F8F84<br>
🤩smile_F09FA4A9<br>
🚀smile_F09F9A80<br>
👫smile_F09F91AB<br>
🔗smile_F09F9497<br>
💵smile_F09F92B5<br>
📈smile_F09F9388<br>
🥁smile_F09FA581<br>
⚠smile_E29AA0<br>
✌🏽smile_E29C8C<br>
🍀smile_F09F8D80<br>
🌟smile_F09F8C9F<br>
🏆smile_F09F8F86<br>
🎁smile_F09F8E81<br>
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        $("body").on("change", "#type", function () {
            if($(this).val() !== 'all' && $(this).val() !== 'no_matrix') {
                $("#matrix_type_div").slideDown();
            } else {
                $("#matrix_type_div").slideUp();
            }
        });
        $("body").on("submit", "#send_form", function () {

            App.ajax.form('#send_form', 'send', function(response) {
                Notifier.success('Сообщения будут отправлены ' + response.count + ' пользователям(ю)');
            }, function(response) {
                if(response.error) {
                    Notifier.error(response.error);
                }
            });
            return false;
        });
    });
</script>