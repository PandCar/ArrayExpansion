<?php

/**
 * ArrayExpansion class.
 *
 * Advanced functions for working with arrays
 *
 * @author Oleg Isaev <pandcar@mail.ru>
 * @author Vasily Heartfelt <morepusto@yandex.ru>
 * @license MIT
 */
class ArrayExpansion
{
	/**
	 * @param array $arr
	 * @param string|int $key
	 * @param ?string $default_value
	 * @return ?mixed
	 */
	public static function array_get($arr, $key, $default_value = null)
	{
		return isset($arr[$key]) ? $arr[$key] : $default_value;
	}

	/**
	 * @param array $arr
	 * @return array
	 */
	public static function array_items_int($arr)
	{
		return array_map('intval', $arr);
	}

	/**
	 * @param array $arr
	 * @param mixed $value
	 * @param bool $save_keys
	 */
	public static function array_unset_value(&$arr, $value, $save_keys = true)
	{
		$keys = array_keys($arr, $value);

		foreach ($keys as $key) {
			unset($arr[$key]);
		}

		if (! $save_keys) {
			$arr = array_values($arr);
		}
	}


	/**
	 * @param array $arr
	 * @param bool $unique
	 * @return array
	 */
	public static function array_ids($arr, $unique = true)
	{
		$arr = static::array_items_int($arr);

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
	 * @param array $arr
	 * @param string $pattern
	 * @param bool $by_keys
	 * @return array
	 */
	public static function array_grep($arr, $pattern, $by_keys = false)
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
	 * @param array $arr
	 * @param array $columns
	 * @param false $list
	 * @return mixed
	 */
	public static function array_select_columns($arr, $columns, $list = false)
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
				if (isset($item[$key2])) {
					$row[$key2] = $item[$key2];
				}
			}

			$result[$key1] = $row;
		}

		return empty($list) ? $result[0] : $result;
	}

	/**
	 * @param array$arr
	 * @param ?callable|mixed $search_data
	 * @param false $all_rows
	 * @return mixed
	 */
	public static function array_select($arr, $search_data, $all_rows = false)
	{
		if (! is_array($arr) || empty($search_data) || (! is_array($search_data) && ! is_callable($search_data))) {
			return false;
		}

		if (is_callable($search_data)) {
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
						if (! isset($var[$key])) {
							$isset = false;
							$var = null;
							break;
						}

						$var = &$var[$key];
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

							preg_match('~'. $val .'~'. $flags, $var, $preg);

							$bool = ! empty($preg);
							break;
					}

					if (! $bool) {
						continue 2;
					}
				}

				$ret[] = $row;

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
	public static function array_order($arr, $order_by, $save_keys = false, $default_not_isset = null)
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
					if (isset($var1[$item])) {
						$var1 = &$var1[$item];
					} else {
						$var1 = $default_not_isset;
					}

					if (isset($var2[$item])) {
						$var2 = &$var2[$item];
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
	 * @param array $arr
	 * @param string|array $columns
	 * @param bool $in_lists
	 * @param string $index_implode_glue
	 * @return array
	 */
	public static function array_index($arr, $columns, $in_lists = false, $index_implode_glue = ',')
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
				if (! isset($ret[$tmp[ $key] ])) {
					$ret[$tmp[ $key] ] = [];
				}

				$ret[$tmp[ $key] ][$key] = $item;
			}
			else {
				$ret[$tmp[ $key] ] = $item;
			}
		}

		return $ret;
	}

	/**
	 * @param array $arr1
	 * @param array $arr2
	 * @param bool $all_columns
	 * @param array $rule
	 * @return array
	 */
	public static function array_merge_by_rule($arr1, $arr2, $all_columns = true, $rule = [])
	{
		$tmp_arr1 = $arr1;

		if ($all_columns) {
			$arr1 = array_merge($arr1, $arr2);
		}

		foreach ($rule as $key => $method)
		{
			switch ($method)
			{
				case '+': $arr1[$key] = $tmp_arr1[$key] + $arr2[$key]; break;

				case '-': $arr1[$key] = $tmp_arr1[$key] - $arr2[$key]; break;

				case 'save-1':  $arr1[$key] = $tmp_arr1[$key]; break;

				case 'save-2':  $arr1[$key] = $arr2[$key]; break;

				case 'merge-1': $arr1[$key] = array_merge($tmp_arr1[$key], $arr2[$key]); break;

				case 'merge-2': $arr1[$key] = array_merge($arr2[$key], $tmp_arr1[$key]); break;
			}
		}

		return $arr1;
	}

	/**
	 * @param array $arr1
	 * @param array $arr2
	 * @param array|string $columns
	 * @param bool $all_columns
	 * @param array $rule
	 * @return array
	 */
	public static function array_dimens_merge($arr1, $arr2, $columns, $all_columns = true, $rule = [])
	{
		$arr1 = static::array_index($arr1, $columns);
		$arr2 = static::array_index($arr2, $columns);

		foreach ($arr1 as $key => $val1)
		{
			if (! isset($arr2[$key])) {
				continue;
			}

			$arr1[$key] = static::array_merge_by_rule($val1, $arr2[$key], $all_columns, $rule);
		}

		return array_values($arr1);
	}

	/**
	 * @param array $arr
	 * @param array|string $columns
	 * @param array $rule
	 * @return mixed
	 */
	public static function array_fold($arr, $columns, $rule = [])
	{
		$arr = static::array_index($arr, $columns, true);

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

				$start = static::array_dimens_merge($start, $new, $columns, true, $rule);
			}

			$arr[$key] = $start;
		}

		$arr = array_values($arr);

		return call_user_func_array('array_merge', $arr);
	}
}
