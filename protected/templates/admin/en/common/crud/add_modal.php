<div class="modal fade" id="add_<?php echo $object; ?>_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="<?php echo $object; ?>_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Добавление</h4>
                </div>
                <div class="modal-body with-padding" id="<?php echo $object; ?>_form_container">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>