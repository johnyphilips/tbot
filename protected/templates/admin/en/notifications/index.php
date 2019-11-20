<div class="page-header">
    <div class="row row-deck">
        <div class="col-md-6 col-sm-12">
            <h1 class="page-title">System Notifications</h1>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">List</h2>
            </div>
            <div class="card-body custom-datatable">
                <table class="table-bordered table" id="get_notifications">
                    <thead>
                    <tr class="filters">
                        <th><input class="form-control filter-field" name="n.id" data-sign="like" placeholder="Search.."></th>
                        <th>
                            <select class="form-control filter-field" name="n.status_id" data-sign="=">
                                <option value="">All</option>
                                <option value="<?php echo notifications_service::STATUS_NEW; ?>">New</option>
                                <option value="<?php echo notifications_service::STATUS_READ; ?>">Read</option>
                            </select>
                        </th>
                        <th><input class="form-control filter-field" name="n.short_text" data-sign="like" placeholder="Search.."></th>
                        <th><input class="form-control filter-field dp" name="DATE(n.create_date)" data-sign="=" placeholder="Search.."></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Short Text</th>
                        <th>Create Date</th>
                        <th></th>
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
        App.dataTable('get_notifications');
    });
</script>
<!--pa-->
<style>

</style>