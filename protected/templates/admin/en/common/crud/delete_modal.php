<div class="modal fade" id="delete_<?php echo $object; ?>_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Удаление</h4>
            </div>
            <div class="modal-body with-padding">
                Вы уверены?
            </div>
            <div class="modal-footer">
                <button type="button" id="delete_<?php echo $object; ?>_btn" class="btn btn-primary">Удалить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>