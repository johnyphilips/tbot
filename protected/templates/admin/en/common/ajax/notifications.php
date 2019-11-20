<a class="nav-link icon d-none d-md-flex" data-toggle="dropdown" aria-expanded="true"><i class="icon-bell"></i>
    <?php if ($system_notifications): ?>
        <span class="nav-unread"></span>
    <?php endif; ?>
</a>
<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow vivify swoopInTop" x-placement="bottom-end" style="position: absolute; transform: translate3d(-187px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
    <?php if ($system_notifications): ?>
        <?php foreach ($system_notifications as $notification): ?>
            <a href="/notifications/?id=<?php echo $notification['id']; ?>" class="dropdown-item d-flex">
                <span class="avatar mr-3 align-self-center avatar-<?php echo $notification['color']; ?>">S</span>
                <div>
                    <?php echo $notification['short_text']; ?>
                    <div class="small text-muted"><?php echo tools_class::convertDateTime($notification['create_date'], 'UTC', 'Asia/Bangkok'); ?></div>
                </div>
            </a>
        <?php endforeach; ?>
        <div class="dropdown-divider"></div>
        <a id="mark_all_read" class="dropdown-item text-center text-muted-dark readall">Mark all as read</a>
    <?php endif; ?>
    <a href="/notifications" class="dropdown-item text-center text-muted-dark readall">See All</a>
</div>