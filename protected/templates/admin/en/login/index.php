<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="/assets/images/icon.png"  type="image/png">

    <title>Bingo Club</title>

    <!-- Bootstrap Core and Font Icon -->
    <link rel="stylesheet" href="<?php echo SITE_DIR; ?>assets/theme/assets/plugins/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo SITE_DIR; ?>assets/theme/assets/plugins/animate-css/vivify.min.css" />
    <link rel="stylesheet" href="<?php echo SITE_DIR; ?>assets/theme/assets/plugins/font-awesome/css/font-awesome.min.css">

    <!-- Core css -->
    <link rel="stylesheet" href="<?php echo SITE_DIR; ?>assets/theme/admin/css/main.css"/>
    <link rel="stylesheet" href="<?php echo SITE_DIR; ?>assets/css/common.css"/>

</head>
<body class="theme-cyan">

<div class="page auth">
    <div class="page-single">
        <div class="container-fluid">
            <div class="row">
                <div class="col col-login mx-auto">
                    <div class="text-center">
                        <img src="<?php echo SITE_DIR; ?>assets/images/icon.png" style="width: 40px;" alt=""><span style="font-size: 40px; vertical-align: middle"> Bingo Club</span>
                    </div>
                    <form class="card" id="login_form" method="post">
                        <div class="card-body">
                            <div class="card-title">Авторизация</div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="text" name="user_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Пароль
                                </label>
                                <input type="password" name="password"  class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="form-footer">
                                <div id="common_error" class="validate-message text-center"></div>
                                <button type="submit" id="login_btn" class="btn btn-primary btn-block" title="">Войти</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script type="text/javascript" src="/assets/libs/Notifier.js"></script>
<script type="text/javascript" src="/assets/js/common.js"></script>
<script src='https://www.google.com/recaptcha/api.js?render=6Le3M60UAAAAAAphgu5e8p-CnnyDo0lFyzuS8c9C'></script>

<script type="text/javascript">
    $ = jQuery.noConflict();

    $(document).ready(function () {
        var $commonError = $("#common_error");
        var $form = $("#login_form");

        $form.submit(function (e) {
            e.preventDefault();
            $("#login_btn").prop('disabled', true);
            <?php if(DEVELOPMENT_MODE): ?>
            App.ajax.form('#login_form', 'login',
                function (response) {
                    location.href = response.url ? response.url : '/';
                }, function (response) {
                    $("#login_btn").prop('disabled', false);
                    if (undefined !== response.error) {
                        $commonError.html(response.error);
                        $commonError.slideDown();
                    }
                }, function () {
                    $("#login_btn").prop('disabled', false);
                    Notifier.error('Неизвестная ошибка');
                });
            <?php else: ?>
            grecaptcha.ready(function () {
                grecaptcha.execute('6Le3M60UAAAAAAphgu5e8p-CnnyDo0lFyzuS8c9C', {action: 'action_name'})
                    .then(function (token) {
                        App.ajax.json('captcha', {'token': token}, function (response) {
                            App.ajax.form('#login_form', 'login',
                                function (response) {
                                    location.href = response.url ? response.url : '/';
                                }, function (response) {
                                    $("#login_btn").prop('disabled', false);
                                    if (undefined !== response.error) {
                                        $commonError.html(response.error);
                                        $commonError.slideDown();
                                    }
                                }, function () {
                                    $("#login_btn").prop('disabled', false);
                                    Notifier.error('Неизвестная ошибка');
                                });
                        }, function () {
                            $("#login_btn").prop('disabled', false);
                            alert('Похоже, вы робот!')
                        });
                        return false;
                    });
            });
            <?php endif; ?>

            setTimeout(function () {
                $("#login_btn").prop('disabled', false);
            }, 5000)
        });

        $("body").on("click", "#login_btn", function () {
            $commonError.slideUp();
        });
    });
</script>
</html>

