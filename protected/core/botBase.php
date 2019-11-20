<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 13/07/2019
 * Time: 23:48
 */
class botBase extends staticBase
{
    public static function fetch($template, $lang = 'en', $full_path = false)
    {
        if(registry::get('common_vars')) {
            self::render('common_vars', registry::get('common_vars'));
        }
        if(!$full_path) {
            $template_file = PROTECTED_DIR . 'templates' . DS . PROJECT . DS . $lang . DS . $template . '.php';
        } else {
            $template_file = PROTECTED_DIR . $template . '.php';
        }
        if($lang !== 'en' && !file_exists($template_file) && !$full_path) {
            $template_file = PROTECTED_DIR . 'templates' . DS . PROJECT . DS . 'en' . DS . $template . '.php';
        }
        if(!file_exists($template_file)) {
            throw new Exception('cannot find template in ' . $template_file);
        }
        foreach(self::$vars as $k => $v) {
            $$k = $v;
        }
        ob_start();
        @require($template_file);
        return ob_get_clean();
    }
}