<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 31.03.18
 * Time: 16:57
 */
class site_project extends controller
{
    const SCRIPT_SEPARATOR = '<!--pa-->';
    public function checkAuth($rules)
    {
        if($rules['auth']) {
            if($token = $_COOKIE['Authorization']) {
                if($user_id = authorization::checkToken($token)) {
                    if($user = $this->model('system_users')->getById($user_id)) {
                        if($user['role_id'] == 3) {
                            $this->unauthorized();
                        }
                        unset($user['user_password']);
                        registry::set('user', $user);
                        registry::set('auth', true);
                        $this->user = $user;
                        $token = authorization::generateToken($user['id']);
                        setcookie('Authorization', $token, time() + 3600 * 24 * 30, '/');
                        $this->response->withHeader('Authorization', $token);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return false;
        } else {
            if($token = $_COOKIE['Authorization']) {
                if($user_id = authorization::checkToken($token)) {
                    if($user = $this->model('system_users')->getById($user_id)) {

                        $this->user = $user;
                    }
                    $token = authorization::generateToken($user_id);
                    setcookie('Authorization', $token, time() + 3600 * 24 * 30, '/');
                    $this->response->withHeader('Authorization', $token);
                }
            }
            return true;
        }
    }

    public function view_only($template)
    {
        $content = $this->fetch($template);
        $this->html($content);
    }

    public function view($template)
    {
        $this->common();
        $this->render('breadcrumbs', $this->breadcrumbs);
        $content = $this->fetch($template);
        if(strpos($content, self::SCRIPT_SEPARATOR) !== false) {
            $arr = explode(self::SCRIPT_SEPARATOR, $content);
            $content = array_shift($arr);
            $this->render('template_script', array_shift($arr));
            if($arr) {
                $this->render('template_style', array_shift($arr));
            }
        }
        $this->render('template_content', $content);

        $content = $this->fetch('common/frame');
        $this->html($content);
    }

    protected function common()
    {

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
            'allowed_methods' => ['POST'],
        ];
    }


}