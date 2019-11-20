<!--<div class="page-header">-->
<div class="row clearfix row-deck">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h2>Notification #<?php echo $notification['id']; ?> </h2>
                <h4 style="color: #6c88ee"><?php echo tools_class::convertDateTime($notification['create_date'], 'UTC', 'Asia/Bangkok'); ?></h4>
                <h4><?php echo $notification['short_text']; ?></h4>
                <div id="text">
                    <?php echo $notification['full_text']; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!--pa-->
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(document).ready(function () {
        <?php if($notification['status_id'] == notifications_service::STATUS_NEW): ?>
        setTimeout(function() {
            Admin.markRead(App.getURLParameter('id'));
        }, 3000);
        <?php endif; ?>
    });
</script>
<!--pa-->
<style>
    #text {
        padding: 30px;
        border: 1px solid #eee;
    }
</style>