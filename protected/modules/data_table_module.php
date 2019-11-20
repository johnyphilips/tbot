<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 18.09.17
 * Time: 1:02
 */
class data_table_module extends base
{
    public function init($params, $print = null, $date_fields = [], $date_params = [])
    {
        $decode = json_decode($_REQUEST['params']);
        if(is_array($decode)) {
            $search = $decode;
        } else {
            $search = get_object_vars($decode);
        }

        foreach($search as $k=>$v)
        {
            $params['where'][$k] = array(
                'sign' => $v->sign,
                'value' => $v->value
            );
        }
        $params['limits'] = isset($_REQUEST['iDisplayStart']) ? $_REQUEST['iDisplayStart'].','.$_REQUEST['iDisplayLength'] : '';
        $params['order'] =  $_REQUEST['iSortCol_0'] ? $params['select'][$_REQUEST['iSortCol_0']] . ($_REQUEST['sSortDir_0'] ? ' ' . $_REQUEST['sSortDir_0'] : $params['order']) : $params['order'];
        $res = $this->model('default')->getFilteredData($params, $print, $date_fields, $date_params);
        $rows['aaData'] = $res['rows'];
        $rows['iTotalRecords'] = $this->model(explode(' ', $params['table'])[0])->countByField();
        $rows['iTotalDisplayRecords'] = $res['count'];
        return($rows);
    }
}