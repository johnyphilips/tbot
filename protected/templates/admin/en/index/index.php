<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Главная</h1>
        </div>
    </div>
<!--    <div class="row clearfix">-->
<!--        <div class="col-md-2">-->
<!--            <input id="stats_date" class="form-control dp" value="--><?php //echo date('Y-m-d'); ?><!--">-->
<!--        </div>-->
<!--    </div>-->
    <br>
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Новые пользователи</th>
                            <th>На балансе</th>
                            <th>Депозитов</th>
                            <th>Выводов</th>
                            <th>Деп/выв</th>
                            <th>Всего профита</th>
                            <th>Выплаты рефералам</th>
                            <th>Наш профит</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($stats as $date => $stat): ?>
                            <tr>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $stat['new_users']; ?></td>
                                <td><?php echo $stat['balances']; ?></td>
                                <td><?php echo $stat['deposits']; ?></td>
                                <td><?php echo $stat['lotteries']; ?></td>
                                <td><?php echo $stat['free_lotteries']; ?></td>
                                <td><?php echo $stat['roulettes']['qty']; ?></td>
                                <td><?php echo $stat['roulettes']['won']; ?></td>
                                <td><?php echo $stat['roulettes']['spent']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<!--
<!--        <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--            <div class="card">-->
<!--                <div class="card-body">-->
<!--                    <div class="details">-->
<!--                        <h6 class="mb-0">Пользователи</h6>-->
<!--                        <h3 class="mb-0" id="users_today">0</h3>-->
<!--                    </div>-->
<!--                    <div class="w_chart">-->
<!--                        <span id="users_total">0</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--            <div class="card">-->
<!--                <div class="card-body">-->
<!--                    <div class="details">-->
<!--                        <h6 class="mb-0">Выплаты</h6>-->
<!--                        <h3 class="mb-0" id="withdrawals_today">0</h3>-->
<!--                    </div>-->
<!--                    <div class="w_chart">-->
<!--                        <span id="withdrawals_total">0</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--            <div class="card">-->
<!--                <div class="card-body">-->
<!--                    <div class="details">-->
<!--                        <h6 class="mb-0">Поступления</h6>-->
<!--                        <h3 class="mb-0" id="payments_today">0</h3>-->
<!--                    </div>-->
<!--                    <div class="w_chart">-->
<!--                        <span id="payments_total">0</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
    <br>
    <div class="row clearfix">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="details">
                        <h6 class="mb-0">Баланс</h6>
                        <h3 class="mb-0"><span id="balance">0</span> BTC</h3>
                    </div>
                    <div class="w_chart">
<!--                        <span class="chart_1"><canvas width="73" height="80" style="display: inline-block; width: 73px; height: 80px; vertical-align: top;"></canvas></span> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="details">
                        <h6 class="mb-0">Скидка 10%</h6>
                        <h3 class="mb-0"></h3>
                    </div>
                    <div class="w_chart">
                            <input type="checkbox" id="discount" <?php if($discount) echo 'checked'; ?>>
<!--                        <span class="chart_1"><canvas width="73" height="80" style="display: inline-block; width: 73px; height: 80px; vertical-align: top;"></canvas></span>-->
                    </div>
                </div>
            </div>
        </div>
<!--
<!--        <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--            <div class="card">-->
<!--                <div class="card-body w_sparkline">-->
<!--                    <div class="details">-->
<!--                        <h6 class="mb-0">Page Views</h6>-->
<!--                        <h3 class="mb-0">3,025</h3>-->
<!--                    </div>-->
<!--                    <div class="w_chart">-->
<!--                        <span class="chart_3"><canvas width="73" height="80" style="display: inline-block; width: 73px; height: 80px; vertical-align: top;"></canvas></span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--            <div class="card">-->
<!--                <div class="card-body w_sparkline">-->
<!--                    <div class="details">-->
<!--                        <h6 class="mb-0">Growth</h6>-->
<!--                        <h3 class="mb-0">$35M</h3>-->
<!--                    </div>-->
<!--                    <div class="w_chart">-->
<!--                        <span class="chart_4"><canvas width="83" height="80" style="display: inline-block; width: 83px; height: 80px; vertical-align: top;"></canvas></span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        App.ajax.json('get_balance', {}, function(response) {
            $("#balance").html(response.balance);
            setInterval(function() {
                $("#balance").html(response.balance)
            }, 10000 * 60);
        });

        $("body").on("change", "#discount", function () {
            App.ajax.json('set_discount', {'discount': $(this).prop('checked')})
        });

//        App.ajax.json('get_users_stats', {'date': $("#stats_date").val()}, function(response) {
//            $("#users_today").html(response.today);
//            $("#users_total").html(response.total);
//        });
//
//        $("body").on("change", "#stats_date", function () {
//            App.ajax.json('get_users_stats', {'date': $(this).val()}, function(response) {
//                $("#users_today").html(response.today);
//                $("#users_total").html(response.total);
//            });
//            App.ajax.json('get_withdrawals_stats', {'date': $(this).val()}, function(response) {
//                $("#withdrawals_today").html(response.today);
//                $("#withdrawals_total").html(response.total);
//            });
//            App.ajax.json('get_payments_stats', {'date': $("#stats_date").val()}, function(response) {
//                $("#payments_today").html(response.today);
//                $("#payments_total").html(response.total);
//            });
//        });
//
//        App.ajax.json('get_withdrawals_stats', {'date': $("#stats_date").val()}, function(response) {
//            $("#withdrawals_today").html(response.today);
//            $("#withdrawals_total").html(response.total);
//        });
//
//        App.ajax.json('get_payments_stats', {'date': $("#stats_date").val()}, function(response) {
//            $("#payments_today").html(response.today);
//            $("#payments_total").html(response.total);
//        })
    });
</script>
<!--pa-->
<style>
    .w_chart {
        height: 40px;
    }
</style>