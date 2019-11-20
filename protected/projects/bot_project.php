<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 31.03.18
 * Time: 16:57
 */
class bot_project extends controller
{
    public function checkAuth($rules)
    {
       return true;
    }

    public function view_only($template)
    {
        $content = $this->fetch($template);
        $this->html($content);
    }

    public function view($template)
    {
        return;
    }

    protected function unauthorized()
    {
        if(!registry::get('endpoint')) {
            header('Location: ' . '/login?redirect=' . $_SERVER['REQUEST_URI']);
            exit;
        }
        $this->response->withStatus(401);
        $this->response->withContentType('application/json');
        $this->response->withJson(['status' => 'fail', 'error' => 'unauthorized']);
        $this->response->respond();
    }

    function getDefaultRules()
    {
        return [
            'auth' => false,
            'allowed_methods' => ['POST', 'GET'],
        ];
    }


}