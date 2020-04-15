<?php

/**
 * Расширенные функции для работы с массивами
 * 
 * @author Oleg Isaev (PandCar)
 * @contacts vk.com/id50416641, t.me/pandcar, github.com/pandcar
 * 
 * @author Vasily Heartfelt (wnull)
 * @contacts github.com/wnull
 */

/**
 * @param $arr
 * @param $key
 * @param null $default_value
 * @return mixed|null
 */
function array_get($arr, $key, $default_value = null)
{
    return isset($arr[ $key ]) ? $arr[ $key ] : $default_value;
}

/**
 * @param $arr
 * @return array
 */
function array_items_int($arr)
{
    return array_map('intval', $arr);
}

/**
 * @param $arr
 * @param $value
 * @param bool $save_keys
 */
function array_unset_value(&$arr, $value, $save_keys = true)
{
    $keys = array_keys($arr, $value);
    
    foreach ($keys as $key) {
        unset($arr[ $key ]);
    }
    
    if (! $save_keys) {
        $arr = array_values($arr);
    }
}

/**
 * @param $arr
 * @param bool $unique
 * @return array
 */
function array_ids($arr, $unique = true)
{
    $arr = array_items_int($arr);
    
    if ($unique) {
        $arr = array_unique($arr);
    }
    
    return array_filter(
        $arr, 
        function ($var) {
            return $var > 0;
        }
    );
}

/**
 * @param $arr
 * @param $pattern
 * @param bool $by_keys
 * @return array
 */
function array_grep($arr, $pattern, $by_keys = false)
{
    if ($by_keys)
    {
        return array_intersect_key(
            $arr, 
            array_flip(
                preg_grep($pattern, array_keys($arr))
            )
        );
    }
    else {
        return preg_grep($pattern, $arr);
    }
}

/**
 * @param $arr
 * @param $columns
 * @param bool $list
 * @return array|mixed
 */
function array_select_columns($arr, $columns, $list = false)
{
    if (empty($list)) {
        $arr = [
            $arr
        ];
    }
    
    $result = [];
    $tmp = array_flip($columns);
    
    foreach ($arr as $key1 => $item)
    {
        $row = [];
        
        foreach ($tmp as $key2 => $val)
        {
            if (isset($item[ $key2 ])) {
                $row[ $key2 ] = $item[ $key2 ];
            }
        }
        
        $result[ $key1 ] = $row;
    }
    
    return empty($list) ? $result[0] : $result; 
}

/**
 * @param $arr
 * @param $search_data
 * @param bool $all_rows
 * @return array|bool|mixed
 */
function array_select($arr, $search_data, $all_rows = false)
{
    if (! is_array($arr) || empty($search_data) || (! is_array($search_data) && ! is_callable($search_data))) {
        return false;
    }
    
    if (is_callable($search_data))
    {
        $ret = array_values(
            array_filter($arr, $search_data)
        );
    }
    else
    {
        $ret = [];
        
        foreach ($arr as $row)
        {
            foreach ($search_data as $and)
            {
                $and = array_pad($and, 4, null);
                list($key, $op, $val, $p1) = $and;
                
                $keys = explode('.', $key);
                $var = &$row;
                
                $isset = true;
                
                foreach ($keys as $key)
                {
                    if (! isset($var[ $key ])) {
                        $isset = false;
                        $var = null;
                        break;
                    }
                    
                    $var = &$var[ $key ];
                }
                
                $bool = false;
                
                switch ($op)
                {
                    case '==':  $bool = ($var == $val);  break;
                    
                    case '===': $bool = ($var === $val); break;
                    
                    case '!=':  $bool = ($var != $val);  break;
                    
                    case '>':   $bool = ($var > $val);   break;
                    
                    case '<':   $bool = ($var < $val);   break;
                    
                    case '>=':  $bool = ($var >= $val);  break;
                    
                    case '<=':  $bool = ($var <= $val);  break;
                    
                    case 'in':     $bool = in_array($var, $val, ! empty($p1));   break;
                    case 'not-in': $bool = ! in_array($var, $val, ! empty($p1)); break;
                    
                    case 'isset':     $bool = ! empty($isset); break;
                    case 'not-isset': $bool = empty($isset);   break;
                    
                    case 'empty':     $bool = empty($var);   break;
                    case 'not-empty': $bool = ! empty($var); break;
                    
                    case 'array':
                    case 'int':
                    case 'numeric':
                    case 'float':
                    case 'null':
                    case 'object':
                    case 'string':
                    case 'bool':
                        $bool = call_user_func( 'is_'.$op, $var );
                    break;
                    
                    case 'not-array':
                    case 'not-int':
                    case 'not-numeric':
                    case 'not-float':
                    case 'not-null':
                    case 'not-object':
                    case 'not-string':
                    case 'not-bool':
                        $bool = ! call_user_func( 
                            'is_'.str_replace('not-', '', $op), 
                            $var
                        );
                    break;
                    
                    case 'regexp':
                        $flags = ! empty($p1) ? $p1 : 'isu';
                        
                        preg_match('~'.$val.'~'.$flags, $var, $preg);
                        
                        $bool = ! empty($preg);
                    break;
                }
                
                if (! $bool) {
                    continue 2;
                }
            }
            
            $ret []= $row;
            
            if (! $all_rows) {
                break;
            }
        }
    }
    
    if (empty($ret)) {
        return $all_rows ? [] : false;
    }
    
    return $all_rows ? $ret : $ret[0];
}

/**
 * @param $arr
 * @param $order_by
 * @param bool $save_keys
 * @param null $default_not_isset
 * @return mixed
 */
function array_order($arr, $order_by, $save_keys = false, $default_not_isset = null)
{
    $sort_opt = function($one, $two) use ($order_by, $default_not_isset) {

        $res = 0;
        
        foreach ($order_by as $key => $type_sort)
        {
            $keys = explode('.', $key);
            
            $var1 = &$one;
            $var2 = &$two;
            
            foreach ($keys as $item)
            {
                if (isset($var1[ $item ])) {
                    $var1 = &$var1[ $item ];
                } else {
                    $var1 = $default_not_isset;
                }
                
                if (isset($var2[ $item ])) {
                    $var2 = &$var2[ $item ];
                } else {
                    $var2 = $default_not_isset;
                }
            }
            
            if ($var1 == $var2) {
                continue;
            }
            
            $res = ($var1 < $var2 ? -1 : 1);
            
            if ($type_sort == SORT_DESC) {
                $res = -$res;
            }
            
            break;
        }
        
        return $res;
    };
    
    if ($save_keys) {
        uasort($arr, $sort_opt);
    } else {
        usort($arr, $sort_opt);
    }
    
    return $arr;
}

/**
 * @param $arr
 * @param $columns
 * @param bool $in_lists
 * @param string $index_implode_glue
 * @return array
 */
function array_index($arr, $columns, $in_lists = false, $index_implode_glue = ',')
{
    if (is_string($columns)) {
        $columns = [
            $columns
        ];
    }
    
    $params = [];
    
    $params []= function() use ($index_implode_glue) {
        return implode($index_implode_glue, func_get_args());
    };
    
    foreach ($columns as $val) {
        $params []= array_column($arr, $val);
    }
    
    $tmp = call_user_func_array('array_map', $params);
    $ret = [];
    
    foreach ($arr as $key => $item)
    {
        if ($in_lists)
        {
            if (! isset($ret[ $tmp[ $key ] ])) {
                $ret[ $tmp[ $key ] ] = [];
            }
            
            $ret[ $tmp[ $key ] ][ $key ] = $item;
        }
        else {
            $ret[ $tmp[ $key ] ] = $item;
        }
    }
    
    return $ret;
}

/**
 * @param $arr1
 * @param $arr2
 * @param bool $all_columns
 * @param array $rule
 * @return array
 */
function array_merge_by_rule($arr1, $arr2, $all_columns = true, $rule = [])
{
    $tmp_arr1 = $arr1;
    
    if ($all_columns) {
        $arr1 = array_merge($arr1, $arr2);
    }
    
    foreach ($rule as $key => $method)
    {
        switch ($method)
        {
            case '+': $arr1[ $key ] = $tmp_arr1[ $key ] + $arr2[ $key ]; break;
                
            case '-': $arr1[ $key ] = $tmp_arr1[ $key ] - $arr2[ $key ]; break;
                
            case 'save-1':  $arr1[ $key ] = $tmp_arr1[ $key ]; break;
                
            case 'save-2':  $arr1[ $key ] = $arr2[ $key ]; break;
                
            case 'merge-1': $arr1[ $key ] = array_merge($tmp_arr1[ $key ], $arr2[ $key ]); break;
                
            case 'merge-2': $arr1[ $key ] = array_merge($arr2[ $key ], $tmp_arr1[ $key ]); break;
        }
    }
    
    return $arr1;
}

/**
 * @param $arr1
 * @param $arr2
 * @param $columns
 * @param bool $all_columns
 * @param array $rule
 * @return array
 */
function array_dimens_merge($arr1, $arr2, $columns, $all_columns = true, $rule = [])
{
    $arr1 = array_index($arr1, $columns, false);
    $arr2 = array_index($arr2, $columns, false);
    
    foreach ($arr1 as $key => $val1)
    {
        if (! isset($arr2[ $key ])) {
            continue;
        }
        
        $arr1[ $key ] = array_merge_by_rule($val1, $arr2[ $key ], $all_columns, $rule);
    }
    
    $arr1 = array_values($arr1);
    
    return $arr1;
}

/**
 * @param $arr
 * @param $columns
 * @param array $rule
 * @return array|mixed
 */
function array_fold($arr, $columns, $rule = [])
{
    $arr = array_index($arr, $columns, true);
    
    foreach ($arr as $key => $row)
    {
        $start = [
            array_shift($row)
        ];
        
        foreach ($row as $item)
        {
            $new = [
                $item
            ];
            
            $start = array_dimens_merge($start, $new, $columns, true, $rule);
        }
        
        $arr[ $key ] = $start;
    }
    
    $arr = array_values($arr);
    
    $arr = call_user_func_array('array_merge', $arr);
    
    return $arr;
}

