<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 05.07.18
 * Time: 22:11
 */
class logs_index_controller extends admin_project
{
    public function content()
    {
        $this->view('logs/index');
    }

    public function get_logs()
    {
        $this->render('logs', $this->getLogs());
        $this->template('logs/ajax/logs');
    }

    private function getLogs() {
        return logs_class::getLogs();
    }

    public function delete_log()
    {
        if($_POST['id']) {
            $file = PUBLIC_DIR . 'tmp/logs/' . $_POST['id'];
            if(file_exists($file)) {
                if(unlink($file)) {
                    $this->success();
                }
            }
        }
        $this->fail(['error' => 'error']);
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

}