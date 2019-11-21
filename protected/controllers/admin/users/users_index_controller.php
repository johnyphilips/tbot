<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 22.02.18
 * Time: 13:22
 */
class users_index_controller extends admin_project
{
    public function content()
    {
        $this->render('roles', $this->model('user_roles')->getAll('id'));
        $this->view('users/index');
    }

    public function get_users()
    {
        $params = [];
        $params['table'] = 'system_users u';
        $params['select'] = [
            'u.id',
            'u.user_name',
            'r.role_name',
            'CONCAT("
            <a href=\"#add_modal\" data-toggle=\"modal\" data-id=", u.id, " class=\"btn btn-xs btn-light edit_user\">
                <i class=\"fas fa-edit\"></i>
            </a>
            <a href=\"#delete_modal\" data-toggle=\"modal\" data-id=", u.id, " class=\"btn btn-xs btn-light delete_user\">
                <i class=\"fa fa-trash text-danger\"></i>
            </a>
            ")'
        ];
        $params['join']['user_roles'] = [
            'as' => 'r',
            'on' => 'u.role_id = r.id',
            'left' => true
        ];
        $response = $this->module('data_table')->init($params);
        $this->json($response);
    }

    protected function rules()
    {
        $this->rules = [
            'content' => [
                'auth' => true,
                'allowed_roles' => [1]
            ],
            'get_users' => [
                'auth' => false,
                'allowed_methods' => ['GET']
            ]
        ];
    }

    public function get_user_form()
    {
        $tz_list = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $this->render('timezones', $tz_list);
        $this->render('roles', $this->model('user_roles')->getAll('id'));
        if($_POST['id']) {
            $user = $this->model('system_users')->getById($_POST['id']);
            $this->render('user', $user);
        }
        $this->template('users/ajax/user_form');
    }

    public function save()
    {
        $user = $_POST['user'];
        unset($user['password']);
        $namesake = $this->model('system_users')->getByField('user_name', $user['user_name']);
        if(!$user['id']) {
            if($namesake) {
                $this->fail(['error' => 'not_unique']);
            }
            $user['create_date'] = gmdate('Y-m-d H:i:s');
            if(!$_POST['user']['password']) {
                $this->fail(['error' => 'no_password']);
            }
        } else if($namesake && $namesake['id'] != $user['id']) {
            $this->fail(['error' => 'not_unique']);
        }
        if($_POST['user']['password']) {
            $user['user_password'] = hash_hmac('sha256', $_POST['user']['password'], APP_SECRET);
        }
        $this->model('system_users')->insert($user);
        $this->success();
    }

    public function delete_user()
    {
        $this->model('system_users')->deleteById($_POST['id']);
        $this->success();
    }
}