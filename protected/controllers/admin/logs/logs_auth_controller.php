<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 22.02.18
 * Time: 13:22
 */
class logs_auth_controller extends admin_project
{
    public function content()
    {
        $this->render('roles', $this->model('user_roles')->getAll('id'));
        $this->view('logs/auth');
    }

    public function get_logs()
    {
        $params = [];
        $params['table'] = 'authorizations a';
        $params['select'] = [
            'a.id',
            'IF(a.auth_status = 1, "Success", "Fail")',
            'a.user_id',
            'a.login',
            'a.ip',
            'a.geo_data',
            'a.ua',
            'a.create_date',
        ];
        $params['order'] = 'create_date DESC';
        $response = $this->module('data_table')->init($params, false, [7]);
        $this->json($response);
    }

    public function common()
    {

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
            'get_logs' => [
                'allowed_methods' => ['GET']
            ]
        ];
    }
}