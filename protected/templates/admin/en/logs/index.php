<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">Системные Логи</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Логи</h3>
            </div>
            <div class="card-body" id="logs">
            </div>
        </div>
    </div>
</div>
<?php crud_module::getDeleteModal($controller, 'log'); ?>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    var Logs = {
        "getLogs": function() {
            App.ajax.json('get_logs', {}, function(response) {
                $("#logs").html(response.template);
            });
        }
    };
    $(document).ready(function () {
        Logs.getLogs();
        App.crud.delete('log', function() {
            Logs.getLogs();
        });
    });
</script>
<!--pa-->
<style>
    .preview {
        max-height: 100px;
    }
    table.dataTable tbody td {
        padding: 1px 10px;
    }
</style>