$ = jQuery.noConflict();
var Admin = {
    'getNotifications': function() {
        App.ajax.template('notifications/index/notifications', '#notifications_container')
    },
    'markRead': function(id) {
        App.ajax.json('notifications/index/mark_read', {'id': id}, function() {
            Admin.getNotifications();
        });
    },
    'markAllRead': function() {
        App.ajax.json('notifications/index/mark_all_read', {}, function() {
            Admin.getNotifications();
        });
    }
};
$(document).ready(function() {
    $("body").on("click", "#log_out", function () {
        App.deleteCookie('Authorization');
        location.href = '/login/';
    });
    App.dp('.dp');
    Admin.getNotifications();
    $("body").on("click", "#mark_all_read", function () {
        Admin.markAllRead();
    });
    setInterval(function() {
        Admin.getNotifications();
    }, 30000);

    $("body").on("click", ".mobile_menu_toggler", function () {
        $(".mobile_menu").slideToggle(100);
    });

    $("body").on("click", ".mobile_menu .menu-item", function () {
        location.href = $(this).find('a').attr('href');
    });
});
