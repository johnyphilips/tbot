<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 03.04.18
 * Time: 15:50
 */
class crud_module extends base
{
    public static function getForm($controller, $table, $object = null, $template = null)
    {
        if(!$object) {
            $object = rtrim($table, 's');
        }
        if(!$template) {
            $template = $table . '/ajax/' . $object . '_form';
        }
        if($_POST['id']) {
            $item = $controller->model($table)->getById($_POST['id']);
            $controller->render($object, $item);
        }
        $controller->template($template);
    }

    public static function save($controller, $table, $object = null, $checkboxes = [])
    {
        if(!$object) {
            $object = rtrim($table, 's');
        }
        $row = $_POST[$object];
        if(!$row) {
            return false;
        }
        if(!$row['id']) {
            $row['create_date'] = gmdate('Y-m-d H:i:s');
        }
        foreach ($checkboxes as $checkbox) {
            if(!$row[$checkbox]) {
                $row[$checkbox] = 0;
            }
        }
        if($row['id'] = $controller->model($table)->insert($row)) {
            return $row;
        }
        return false;
    }

    public static function delete($controller, $table)
    {
        if($controller->model($table)->deleteById($_POST['id'])) {
            return true;
        }
        return false;
    }

    public static function getDeleteModal($controller, $object)
    {
        $controller->render('object', $object);
        echo $controller->fetch('common/crud/delete_modal');

    }

    public static function getAddModal($controller, $object)
    {
        $controller->render('object', $object);
        echo $controller->fetch('common/crud/add_modal');
    }
}