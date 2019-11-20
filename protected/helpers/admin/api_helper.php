<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 15.05.18
 * Time: 23:38
 */
class api_helper extends admin_project
{
    function getDefaultRules()
    {
        return [
            'auth' => false,
            'allowed_methods' => ['POST', 'GET', 'PUT', 'DELETE', 'OPTIONS'],
        ];
    }
}