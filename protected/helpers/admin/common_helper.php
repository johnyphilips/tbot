<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 18.09.17
 * Time: 0:48
 */
class common_helper extends controller
{
    public function __construct()
    {

    }

    public function init()
    {
        $this->breadcrumbs($this->breadcrumbs);
    }

    public function breadcrumbs($params)
    {
        $this->render('breadcrumbs', $params['breadcrumbs']);
    }
}