<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 01/06/2019
 * Time: 21:04
 */
class sendings_index_controller extends admin_project
{
    public function content()
    {
        $this->view('sendings/index');
    }

    public function send()
    {
        if($_POST['user_name']) {
            if(is_numeric($_POST['user_name'])) {
                $chat_id = $_POST['user_name'];
                $user = self::model('bot_users')->getByField('chat_id', $_POST['user_name']);
            } else {
                $user = self::model('bot_users')->getByField('t_user_name', $_POST['user_name']);
                $chat_id = $user['chat_id'];
            }
            if(in_array($_POST['user_name'], [CHANNEL_NAME, WITHDRAWAL_CHANNEL])) {
                $user = true;
                $chat_id = $_POST['user_name'];
            }
            if(!$user) {

                $this->fail(['error' => 'No User']);
            }
            queue_service::add($chat_id, $_POST['message']);
            $this->success(['count' => 1]);
        }
        if($_POST['type'] == 'all') {
            $users = $this->model('bot_users')->getAll();
        } else if($_POST['type'] == 'no_matrix') {
            $users = $this->model('bot_users')->getUsersWithNoActiveMatrices();
        } else if($_POST['type'] == 'with_matrix') {
            $tmp = $this->model('bot_users')->getUsersWithActiveMatrices($_POST['matrix_type']);
            $users = [];
            foreach ($tmp as $item) {
                $users[$item['id']] = $item;
            }
        } else if($_POST['type'] == 'with_first_level') {
            $tmp = $this->model('bot_users')->getUsersWithFilledFirstLevel($_POST['matrix_type']);
            $users = [];
            foreach ($tmp as $item) {
                $users[$item['id']] = $item;
            }
        }
        if(!empty($users)) {
            foreach ($users as $user) {
                if($user['status_id'] == bot_commands_class::USER_ACTIVE_STATUS) {
                    queue_service::add($user['chat_id'], $_POST['message'], [], [], false, true, 2);
                }
            }
            $this->success(['count' => count($users)]);
        } else {
            $this->fail(['error' => 'No Users']);
        }

    }
}