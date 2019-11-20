<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 23/05/2019
 * Time: 13:48
 */
class notifications_index_controller extends admin_project
{
    public function content()
    {
        if(!$_GET['id']) {
            $this->view('notifications/index');
        } else {
            $this->render('notification', $this->model('system_notifications')->getById($_GET['id']));
            $this->view('notifications/id');
        }
    }

    public function mark_read()
    {
        if($_POST['id']) {
            notifications_service::markRead($_POST['id']);
            $this->success();
        }
        $this->fail();
    }

    public function mark_all_read()
    {
        notifications_service::markAllRead();
        $this->success();
        $this->fail();
    }

    public function notifications()
    {
        $this->render('system_notifications', notifications_service::getNewNotifications());
        $this->template('common/ajax/notifications');
    }

    public function get_notifications()
    {
        $params = [];
        $params['table'] = 'system_notifications n';
        $params['select'] = [
            'n.id',
            'if(n.status_id = ' . notifications_service::STATUS_NEW . ', "New", "Read")',
            'n.short_text',
            'n.create_date',
            'CONCAT("
            <a href=\"/notifications?id=", n.id, "\" class=\"btn btn-icon btn-default\"><i class=\"fas fa-eye\"></i></a>
            ")'
        ];
        $params['order'] = 'create_date DESC';
        $this->success($this->module('data_table')->init($params, false, [3]));
    }

    public function rules()
    {
        $this->rules = [
            'get_notifications' => [
                'allowed_methods' => ['GET']
            ]
        ];
    }
}