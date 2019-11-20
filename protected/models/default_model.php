<?php
/**
 * Created by PhpStorm.
 * User: novichkov
 * Date: 07.03.15
 * Time: 18:33
 */

class default_model extends model
{
    public function getFilteredData(array $params, $show = false, $date_fields = [], $date_params = [])
    {
        $select = array();
        if($params['select'])
        {
            foreach($params['select'] as $k=>$v)
            {
                $select[] = (!is_numeric($k) ? $k : $v).''.($v && !is_numeric($k) ? ' as '.$v : '');
            }
        }
        $where = array();
        if($params['where'])
        {
            foreach($params['where'] as $k=>$v)
            {
                if(isset($v['value'])) {
                    if ($v['sign'] != 'between' && $v['sign'] != 'like' && $v['sign'] != 'in') {
                        if (in_array($k, $date_params)) {
                            $v['value'] = tools_class::convertDateTime($v['value'] . ' 00:00:00', registry::get('timezone'));//strtotime($v['value']);
                        }
                        $where[] = $k . ' ' . ($v['sign'] ? $v['sign'] . ($v['value'] == 'NULL' || $v['noquotes'] ? ' ' . $v['value'] : ' "' . $v['value'] . '"') : '"' . $v . '"');
                    } elseif ($v['sign'] == 'like') {
                        if (in_array($k, $date_params)) {
                            $v['value'] = tools_class::convertDateTime($v['value'] . ' 00:00:00', registry::get('timezone'));//strtotime($v['value']);
                        }
                        $where[] = $k . ' LIKE  "%' . $v['value'] . '%"';
                    } elseif ($v['sign'] == 'in') {
                        if (is_array($v['value'])) {
                            if ($v['noquotes']) {
                                $where[] = $k . ' IN (' . implode($v['value']) . ')';
                            } else {
                                $in = [];
                                foreach ($v['value'] as $item) {
                                    if (in_array($k, $date_params)) {
                                        $item = strtotime($item);
                                    }
                                    $in[] = '"' . $item . '"';
                                }
                                $where[] = $k . ' IN (' . implode(',', $in) . ')';
                            }
                        } else {
                            if ($v['noquotes']) {
                                $where[] = $k . ' IN (' . $v['value'] . ')';
                            } else {
                                $in = [];
                                foreach (explode(',', $v['value']) as $item) {
                                    if (in_array($k, $date_params)) {
                                        $item = strtotime($item);
                                    }
                                    $in[] = '"' . $item . '"';
                                }
                                $where[] = $k . ' IN (' . implode(',', $in) . ')';
                            }
                        }
                    } else {
                        $v['value'] = explode(' - ', $v['value']);
                        if (in_array($k, $date_params)) {
                            $v['value'][0] = tools_class::convertDateTime($v['value'][0] . ' 00:00:00', registry::get('timezone'));//strtotime($v['value'][0] . ' 00:00:00');
                            $v['value'][1] = tools_class::convertDateTime($v['value'][1] . ' 23:59:59', registry::get('timezone'));//strtotime($v['value'][1] . '23:59:59');
                        }
                        $where[] = $k . ' BETWEEN ' . ($v['noquotes'] ? '' : '"') . $v['value'][0] . ($v['noquotes'] ? '' : '"') . ' AND ' . ($v['noquotes'] ? '' : '"') . $v['value'][1] . ($v['noquotes'] ? '' : '"');
                    }
                } elseif (!is_array($v)) {
                    if (in_array($k, $date_params)) {
                        $v = tools_class::convertDateTime($v . ' 00:00:00', registry::get('timezone'));
                    }
                    $where[] = '`' . $k . '` = "' . $v . '"';
                } else {
                    $str = array();
                    for($i=0;$i<count($v);$i++) {
                        if(is_array($v[$i])) {
                            if($v[$i]['sign'] != 'between')
                                $str[] = $k.' '.($v[$i]['sign'] ? $v[$i]['sign'].($v[$i]['value'] == 'NULL' || $v['noquotes'] ? ' '.$v[$i]['value'] : ' "'.$v[$i]['value'].'"') : '"'.$v[$i].'"');
                            else {
                                $tmp = explode(' - ',$v[$i]['value']);
                                $str[] = $k.' BETWEEN "'.$tmp[0].'" AND "'.$tmp[1].'"';
                            }
                        }
                    }
                    $where[] = '('.implode(' '.($v['sign'] ? $v['sign'] : 'AND').' ',$str).')';
                }

            }
        }
        if($params['where_or'])
        {
            foreach($params['where_or'] as $row)
            {
                $temp = array();
                foreach($row as $k=>$v)
                {
                    if(isset($v['value']))
                    {
                        if($v['sign'] != 'between')
                            $temp[] = $k.' '.($v['sign'] ? $v['sign'].($v['value'] == 'NULL' || $v['noquotes'] ? ' '.$v['value'] : ' "'.$v['value'].'"') : '"'.$v.'"');
                        else
                        {
                            $v['value'] = explode(' - ',$v['value']);
                            $temp[] = $k.' BETWEEN "'.$v['value'][0].'" AND "'.$v['value'][1].'"';
                        }
                    }
                    else
                    {
                        $str = array();
                        for($i=0;$i<count($v);$i++)
                        {
                            if(is_array($v[$i]))
                            {
                                if($v[$i]['sign'] != 'between')
                                    $str[] = $k.' '.($v[$i]['sign'] ? $v[$i]['sign'].($v[$i]['value'] == 'NULL' || $v['noquotes'] ? ' '.$v[$i]['value'] : ' "'.$v[$i]['value'].'"') : '"'.$v[$i].'"');
                                else
                                {
                                    $tmp = explode(' - ',$v[$i]['value']);
                                    $str[] = $k.' BETWEEN "'.$tmp[0].'" AND "'.$tmp[1].'"';
                                }
                            }
                        }
                        $temp[] = '('.implode(' '.($v['sign'] ? $v['sign'] : 'OR').' ',$str).')';
                    }
                }
                $where[] = '('.implode(' OR ',$temp).')';
            }
        }
        $join = array();
        if($params['join'])
        {
            foreach($params['join'] as $k=>$v)
            {
                $join[] = ( $v['left'] ? 'LEFT ' : '').'JOIN '.$k. ($v['as']? ' AS '.$v['as']:''). ($v['on']? ' ON '.$v['on']:'') . ($v['using']? ' USING ('.$v['using'] . ')' :'');
            }
        }

        $order = $params['order'] ? $params['order'] : '';
        $group = $params['group'];
        $having = $params['having'];
        $limits = $params['limits'] ? $params['limits'] : '0,15';
        $stm = $this->pdo->prepare('
        SELECT
        '.($select ? implode(',',$select) : '*').'
        FROM
        ' . $params['table'] . '
        '.($join ? ''.implode(' ', $join) : '' ).'
        '.($where ? ' WHERE '.implode(' AND ',$where) : '').'
        '.($group ? ($group == 'nogroup' ? '' : ' GROUP BY ' . $group ): '').'
        '.($having ? ' HAVING '.$having : '').'
        '.($order ? ' ORDER BY ' . $order : '').'

        ');
        $result = array();
        if($show == 1) {
            echo $stm->queryString;
        }
        if($show == 2) {
            $this->writeLog('MYSQL', $stm->queryString);
        }
        $result['count'] = $this->getCountFromStm($stm);
        $stm = $this->pdo->prepare('
        SELECT
        '.($select ? implode(',',$select) : '*').'
        FROM
        ' . $params['table'] . '
        '.($join ? ''.implode(' ', $join) : '' ).'
        '.($where ? ' WHERE '.implode(' AND ',$where) : '').'
        '.($group ? ($group == 'nogroup' ? '' : ' GROUP BY ' . $group ): '').'
        '.($having ? ' HAVING '.$having : '').'
        '.($order ? ' ORDER BY ' . $order : '').'
                LIMIT '.$limits);
        $result['rows'] = $this->get_all($stm, array(), false);
        if($date_fields) {
            foreach ($result['rows'] as $k => $row) {
                foreach ($date_fields as $date_field) {
                    $result['rows'][$k][$date_field] = tools_class::convertDateTime($row[$date_field], 'UTC', registry::get('timezone'));
                }
            }
        }
        $result['query'] = $stm->queryString;

        return $result;
    }

    private function getCountFromStm(PDOStatement $stm)
    {
        return count($this->get_all($stm));
    }
}