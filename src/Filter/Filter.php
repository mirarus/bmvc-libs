<?php

/**
 * Filter
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Filter
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Filter;

use stdClass;

class Filter
{

	/**
	 * @param object $object
	 *
	 * @phpstan-ignore-next-line
	 */
	private static function filterDataValue(object $object)
	{
		if (is_object($object)) {
			if (isset($object->string) && $object->string != '') {
				$string = $object->string;
				if (isset($object->filters) && is_array($object->filters)) {
					foreach ($object->filters as $key => $value) {
						if (is_callable($key)) {
							array_unshift($value, $string);
							$string = call_user_func_array($key, $value);
						}
					}
				}
				return $string;
			}
		}
		return false;
	}

	/**
	 * @param string $string
	 * @param array  $array
	 *
	 * @phpstan-ignore-next-line
	 */
	private static function prepareDataObject(string $string, array $array): stdClass
	{
		$object = new stdClass();
		$object->string = $string;
		$object->filters = [
			'strip_tags' => [], 
			'addslashes' => [], 
			'htmlspecialchars' => [ENT_QUOTES]
		];

		if (count($array) > 0) {
			$object->filters = $array;
		}
		return $object;
	}

	/**
	 * @param array|null $filter
	 * @param array|null $skip
	 *
	 * @phpstan-ignore-next-line
	 *
	 * @return array|false
	 */
	public static function filterXSS(array $filter = null, array $skip = null)
	{
		if (is_array($filter) && count($filter) > 0) {
			foreach ($filter as $key => $value) {
				if (!in_array($key, $skip)) { // @phpstan-ignore-line
					if ($value != '' && !is_array($value) && !is_object($value)) {

						$objectStr = self::prepareDataObject($value, [
							'htmlspecialchars' => [ENT_QUOTES]
						]);
						$filter[$key] = self::filterDataValue($objectStr);
					}
				}
			}
			return $filter;
		}
		return false;
	}

	/**
	 * @param mixed $text
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function filterDB($text)
	{
		$check[1] = chr(34);
		$check[2] = chr(39);
		$check[3] = chr(92);
		$check[4] = chr(96);
		$check[5] = "drop table";
		$check[6] = "update";
		$check[7] = "alter table";
		$check[8] = "drop database";
		$check[9] = "drop";
		$check[10] = "select";
		$check[11] = "delete";
		$check[12] = "insert";
		$check[13] = "alter";
		$check[14] = "destroy";
		$check[15] = "table";
		$check[16] = "database";
		$check[17] = "union";
		$check[18] = "TABLE_NAME";
		$check[19] = "1=1";
		$check[20] = 'or 1';
		$check[21] = 'exec';
		$check[22] = 'INFORMATION_SCHEMA';
		$check[23] = 'like';
		$check[24] = 'COLUMNS';
		$check[25] = 'into';
		$check[26] = 'VALUES';
		$check[27] = 'kill';
		$check[28] = 'union';
		$check[29] = '$';
		$check[30] = '<?php';
		$check[31] = '?>';

		if (is_string($text)) {
			$y = 1;
			$x = sizeof($check);
			while ($y <= $x) {
				$target = strpos($text, $check[$y]);
				if ($target !== false)
					$text = str_replace($check[$y], "", $text);
				$y++;
			}
			return $text;
		} elseif (is_array($text)) {
			$data = [];
			foreach ($text as $t) {
				$data[] = self::filterDB($t);
			}
			return $data;
		} else {
			return $text;
		}
	}
}