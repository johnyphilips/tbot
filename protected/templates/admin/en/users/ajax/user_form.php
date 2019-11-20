<form method="post" action="" id="user_form">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
    </div>
    <div class="modal-body with-padding">
        <div class="form-group">
            <label class="control-label">Email</label>
            <input type="text"  autocomplete="off" class="form-control" name="user[user_name]" value="<?php echo $user['user_name']; ?>" data-require="1">
            <div class="error-require validate-message">Обязательное Поле</div>
            <div id="not_unique" class="validate-message">Уже используется</div>
        </div>
        <div class="form-group">
            <label class="control-label">Группа</label>
            <select class="form-control" name="user[role_id]">
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>"
                    <?php if ($role['id'] == $user['role_id']): ?>
                        selected
                    <?php endif; ?>>
                        <?php echo $role['role_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Пароль</label>
            <input type="text" autocomplete="off" class="form-control" id="password" name="user[password]" data-require="1">
            <div class="error-require validate-message" id="require_password">Обязательное Поле</div>
        </div>
        <div class="form-group">
            <label class="control-label">Временная Зона</label>
            <select name="user[timezone]" class="form-control">
                <?php foreach ($timezones as $timezone): ?>
                    <option value="<?php echo $timezone; ?>" <?php echo $user['timezone'] == $timezone ? 'selected' : ''; ?>><?php echo $timezone; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <?php if ($user['id']): ?>
            <input type="hidden" name="user[id]" value="<?php echo $user['id']; ?>">
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    </div>
</form>