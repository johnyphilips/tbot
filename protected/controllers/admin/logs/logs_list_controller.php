<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 22.02.18
 * Time: 13:22
 */
class logs_list_controller extends admin_project
{
    public function content()
    {
        $this->view('logs/list');
    }

    public function get_blacklist()
    {
        $params = [];
        $params['table'] = 'ip_list l';
        $params['select'] = [
            'l.ip',
            'IF(u.user_name IS NOT NULL, u.user_name, "Автоматически")',
            'l.create_date',
            'CONCAT("
            <a href=\"#delete_blacklist_modal\" class=\"delete_blacklist btn btn-light\" data-id=\"", l.id, "\" data-toggle=\"modal\">
                <i class=\"fas fa-angle-right\"></i> В белый список
            </a> 
            ")'
        ];
        $params['where']['list_type'] = 1;
        $params['join']['system_users'] = [
            'as' => 'u',
            'on' => 'u.id = l.actor',
            'left' => true
        ];
        $params['order'] = 'create_date DESC';
        $response = $this->module('data_table')->init($params, false, [7]);
        $this->json($response);
    }

    public function get_whitelist()
    {
        $params = [];
        $params['table'] = 'ip_list l';
        $params['select'] = [
            'l.ip',
            'IF(u.user_name IS NOT NULL, u.user_name, "Автоматически")',
            'l.create_date',
            'CONCAT("
            <a href=\"#delete_whitelist_modal\" class=\"delete_whitelist btn btn-light\" data-id=\"", l.id, "\" data-toggle=\"modal\">
                <i class=\"fas fa-remove\"></i> Удалить
            </a> 
            ")'
        ];
        $params['where']['list_type'] = 2;
        $params['join']['system_users'] = [
            'as' => 'u',
            'on' => 'u.id = l.actor',
            'left' => true
        ];
        $params['order'] = 'create_date DESC';
        $response = $this->module('data_table')->init($params, false, [7]);
        $this->json($response);
    }

    public function get_blacklist_form()
    {
        $this->template('logs/ajax/list_form');
    }

    public function get_whitelist_form()
    {
        $this->template('logs/ajax/list_form');
    }

    public function save_blacklist()
    {
        $_POST['ip_list']['actor'] = registry::get('user')['id'];
        $_POST['ip_list']['list_type'] = 1;
        if($this->model('ip_list')->getByField('ip', $_POST['ip_list']['ip'])) {
            $this->model('ip_list')->delete('ip', $_POST['ip_list']['ip']);
        }
        if(crud_module::save($this, 'ip_list', 'ip_list')) {
            $this->success();
        }
    }

    public function save_whitelist()
    {
        $_POST['ip_list']['actor'] = registry::get('user')['id'];
        $_POST['ip_list']['list_type'] = 2;
        if($this->model('ip_list')->getByField('ip', $_POST['ip_list']['ip'])) {
            $this->model('ip_list')->delete('ip', $_POST['ip_list']['ip']);
        }
        if(crud_module::save($this, 'ip_list', 'ip_list')) {
            $this->success();
        }
    }

    public function delete_blacklist()
    {
        if(!empty($_POST['id'])) {
            if($this->model('ip_list')->insert([
                'id' => $_POST['id'],
                'list_type' => 2,
                'create_date' => tools_class::gmdate()
            ])) {
                $this->success();
            }
        }
        $this->fail(['error' => 'Не удалось внести, неизвестная ошибка']);
    }

    public function delete_whitelist()
    {
        if(!empty($_POST['id'])) {
            if($this->model('ip_list')->deleteById($_POST['id'])) {
                $this->success();
            }
        }
        $this->fail(['error' => 'Не удалось сохранить, неизвестная ошибка']);
    }

    function getDefaultRules()
    {
        return [
            'auth' => true,
            'allowed_methods' => ['POST'],
            'allowed_roles' => [1]
        ];
    }

    protected function rules()
    {
        $this->rules = [
            'get_blacklist' => [
                'allowed_methods' => ['GET']
            ],
            'get_whitelist' => [
                'allowed_methods' => ['GET']
            ]
        ];
    }
}