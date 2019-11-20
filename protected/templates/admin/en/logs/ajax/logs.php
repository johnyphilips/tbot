<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php if ($logs['folders']): ?>
        <?php foreach ($logs['folders'] as $folder): ?>
            <div class="panel panel-default panel-collapse">
                <div class="panel-heading" role="tab" id="heading_<?php echo $folder['name']; ?>">
                    <h4 class="panel-title">
                        <i class="fa fa-folder"></i>
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $folder['name']; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $folder['name']; ?>">
                            <?php echo $folder['name']; ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse_<?php echo $folder['name']; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo $folder['name']; ?>">
                    <div class="panel-body">
                        <?php if ($folder['logs']): ?>
                            <?php foreach ($folder['logs'] as $log): ?>
                                <div class="log row">
                                    <div class="col-md-4" style="padding-left: 50px;">
                                        <a href="<?php echo SITE_DIR . 'tmp/logs/' .$folder['name']; ?>/<?php echo $log; ?>" target="__blank"><?php echo $log; ?></a>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="#delete_log_modal" data-toggle="modal" data-id="<?php echo $folder['name']; ?>/<?php echo $log; ?>" class="btn btn-light btn-xs btn-icon delete_log">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            Нет логов
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($logs['logs']): ?>
        <?php foreach ($logs['logs'] as $log): ?>
            <div class="log row">
                <div class="col-md-4">
                    <a href="<?php echo SITE_DIR; ?>tmp/logs/<?php echo $log; ?>" target="__blank"><?php echo $log; ?></a>
                </div>
                <div class="col-md-2">
                    <a href="#delete_log_modal" data-toggle="modal" data-id="<?php echo $log; ?>" class="btn btn-light btn-xs btn-icon delete_log">
                        <i class="fa fa-trash text-danger"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>