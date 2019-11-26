<div id="left-sidebar" class="sidebar">
    <nav id="left-sidebar-nav" class="sidebar-nav">
        <ul id="main-menu" class="metismenu">
            <li class="g_heading">Меню</li>
            <li<?php if(registry::get('route') == '' ) echo ' class="active"' ?>>
                <a href="/"><i class="icon-home"></i> <span>Главная</span></a>
            </li>
            <li<?php if(registry::get('route') == 'deposits' ) echo ' class="active"' ?>>
                <a href="/deposits"><i class="fas fa-atom"></i> <span> Депозиты</span></a>
            </li>
            <li<?php if(registry::get('route') == 'withdrawals' ) echo ' class="active"' ?>>
                <a href="/withdrawals"><i class="fas fa-cart-arrow-down"></i> <span> Выплаты</span></a>
            </li>
            <li<?php if(registry::get('route') == 'clients' ) echo ' class="active"' ?>>
                <a href="/clients/"><i class="fas fa-user"></i> <span>Клиенты</span></a>
            </li>
            <li<?php if(registry::get('route') == 'sendings/index' ) echo ' class="active"' ?>>
                <a href="/sendings/index"><i class="fas fa-envelope"></i> <span>Рассылки</span></a>
            </li>
            <li<?php if(registry::get('route') == 'users' ) echo ' class="active"' ?>>
                <a href="/users/"><i class="icon-users"></i> <span>Пользователи</span></a>
            </li>
            <?php if (in_array(registry::get('user')['role_id'], [1])): ?>
                <li <?php if(in_array(registry::get('route'), ['logs', 'logs/auth', 'logs/list'])) echo 'class="active"'; ?>>
                    <a href="javascript:void(0)" class="has-arrow"><i class="fas fa-list-ol"></i> Логи</a>
                    <ul>
                        <?php if (in_array(registry::get('user')['role_id'], [1])): ?>
                            <li <?php if(registry::get('route') == 'logs') echo 'class="active"'; ?>><a href="/logs/">Системные логи</a></li>
                            <li <?php if(registry::get('route') == 'logs/auth') echo 'class="active"'; ?>><a href="/logs/auth/">Авторизации</a></li>
                            <li <?php if(registry::get('route') == 'logs/list') echo 'class="active"'; ?>><a href="/logs/list/">Черный список</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
