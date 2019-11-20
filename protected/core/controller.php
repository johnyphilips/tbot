<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 14.09.17
 * Time: 14:46
 */
class controller extends base
{
    public $breadcrumbs = [];
    protected $request;
    protected $response;
    private $modules = [];
    protected $rules = [];
    protected $user;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->render('controller', $this);
        $this->common();
        $this->init();
        $this->rules();
        $this->getRules();
        if(!$this->checkAuth($this->rules)) {
            $this->unauthorized();
        }
        if(!$this->checkRules()) {

            $this->forbidden();
        }
    }

    protected function common()
    {

    }

    protected function unauthorized()
    {
        $this->response->withStatus(401);
        $this->response->withContentType('application/json');
        $this->response->withJson(['status' => 'fail', 'error' => 'unauthorized']);
        $this->response->respond();
    }

    protected function forbidden()
    {
        $this->response->withStatus(403);
        $this->response->withContentType('application/json');
        $this->response->withJson(['status' => 'fail', 'error' => 'forbidden']);
        $this->response->respond();
    }

    protected function error($error = 'Unexpected Error!')
    {
        $this->response->withStatus(500);
        $this->response->withContentType('application/json');
        $this->response->withJson(['status' => 'fail', 'error' => $error]);
        $this->response->respond();
    }

    protected function badRequest()
    {
        $this->response->withStatus(400);
        $this->response->withContentType('application/json');
        $this->response->withJson(['status' => 'fail', 'error' => 'bad request']);
        $this->response->respond();
    }

    protected function rules()
    {

    }

    protected function init()
    {

    }

    public function template($template)
    {
        $html = $this->fetch($template);
        $this->success(['template' => $html]);
    }

    public function json(array $response)
    {
        $this->response->withJson($response);
        $this->response->withStatus(200);
        $this->response->withContentType('application/json');
        $this->response->respond();
    }

    public function success($response = null)
    {
        $res = [
            'status' => 'success'
        ];
        if(is_array($response)) {
            $res = array_merge($res, $response);
        } elseif($response !== null) {
            $res['template'] = $response;
        }
        $this->json($res);
    }

    public function fail($response = null)
    {
        $res = [
            'status' => 'fail'
        ];
        if(is_array($response)) {
            $res = array_merge($res, $response);
        } elseif($response !== null) {
            $res['template'] = $response;
        }
        $this->json($res);
    }

    public function html($html)
    {
        $this->response->withHtml($html);
        $this->response->withStatus(200);
        $this->response->withContentType('text/html');
        $this->response->respond();
    }

    public function text($text)
    {
        $this->response->withHtml($text);
        $this->response->withStatus(200);
        $this->response->withContentType('text/plain');
        $this->response->respond();
    }

    public function withJson(array $response)
    {
        $this->response->withJson($response);
    }

    public function view($template)
    {
        $this->render('template_content', $this->fetch($template));
        $content = $this->fetch('common/content');
        $this->success($content);
    }

    public function view_only($template)
    {
        $content = $this->fetch($template);
        $this->success($content);
    }

    public function get_content()
    {
        $this->content();
        $content = $this->fetch('common/frame');
        $this->response->withHtml($content);
        $this->response->withStatus(200);
        $this->response->withContentType('text/html');
        $this->response->respond();
    }

    public function content()
    {

    }

    public function module($module)
    {
        if(null === $this->modules[$module]) {
            $class_name = $module . '_module';
            $this->modules[$module] = new $class_name;
        }
        return $this->modules[$module];

    }

    protected function checkRules()
    {
        if(registry::get('endpoint')) {
            if(!$this->checkAllowedMethods($this->rules) ||
                !$this->checkForbiddenMethods($this->rules)) {
                return false;
            }
            if(!$this->checkRoles()) {
                return false;
            }
            return true;

        } else {
            if(!$this->checkRoles()) {
                return false;
            }
            return true;
        }
    }

    private function checkRoles()
    {
        if(empty(registry::get('user')['id'])) {
            return true;
        }
        if(isset($this->rules['allowed_roles'])) {
            if(is_array($this->rules['allowed_roles'])) {
                $check = false;
                foreach ($this->rules['allowed_roles'] as $allowed_role) {
                    if(registry::get('user')['role_id'] == $allowed_role) {
                        $check = true;
                    }
                }
                return $check;
            }
            if($this->rules['allowed_roles'] == 'all') {
                return true;
            }
        }
        if(isset($this->rules['forbidden_roles'])) {
            $check = true;
            foreach ($this->rules['forbidden_roles'] as $forbidden_role) {
                if($this->user['role_id'] == $forbidden_role) {
                    $check = false;
                }
            }
            return $check;
        }
        return true;
    }

    public function checkAuth($rules)
    {
        if (!empty(registry::get('endpoint'))) {
            if ($rules['auth']) {
                if($token = $this->request->getHeader('Authorization')) {
                    if($user_id = authorization::checkToken($token)) {
                        if($user = $this->model('system_users')->getById($user_id)) {
                            unset($user['user_password']);
                            registry::set('user', $user);
                            registry::set('auth', true);
                            $this->user = $user;
                            $token = authorization::generateToken($user['id']);
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

                if($token = $this->request->getHeader('Authorization')) {
                    if($user_id = authorization::checkToken($token)) {
                        if($user = $this->model('system_users')->getById($user_id)) {
                            $this->user = $user;
                            unset($user['user_password']);
                            registry::set('user', $user);
                            registry::set('auth', true);
                        }
                        $token = authorization::generateToken($user_id);
                        $this->response->withHeader('Authorization', $token);
                    }
                }
                return true;
            }
        } else {
            return true;
        }
    }

    public function getRules()
    {
        $rules = $this->getDefaultRules();

        $endpoint = empty(registry::get('endpoint')) ? 'content' : registry::get('endpoint');
        if(!empty($this->rules[$endpoint])) {
            foreach ($this->rules[$endpoint] as $k => $v) {
                $rules[$k] = $v;
            }
        }
        $this->rules = $rules;
    }

    function getDefaultRules()
    {
        return [
            'auth' => true,
            'allowed_methods' => ['POST'],
        ];
    }

    private function checkAllowedMethods($rules)
    {
        if(!empty($rules['allowed_methods'])) {
            return in_array($this->request->getMethod(), $rules['allowed_methods']);
        } else {
            return true;
        }
    }

    private function checkForbiddenMethods($rules)
    {
        if(!empty($rules['forbidden_methods'])) {
            return !in_array($this->request->getMethod(), $rules['forbidden_methods']);
        } else {
            return true;
        }
    }
}