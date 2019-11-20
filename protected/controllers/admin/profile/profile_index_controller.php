<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 24.02.18
 * Time: 16:54
 */
class profile_index_controller extends admin_project
{
    public function content()
    {
        $this->view('profile/index');
    }

    public function save()
    {
        if(!empty($_POST['password'])) {
            if($this->model('system_users')->insert([
                'id' => registry::get('user')['id'],
                'user_password' => hash_hmac('sha256', $_POST['password'], APP_SECRET)
            ])) {
                $this->success();
            }
        }
        $this->fail();
    }
}