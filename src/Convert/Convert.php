<?php

/**
 * Convert
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Convert
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\Convert;

use SimpleXMLElement;
use stdClass;

class Convert
{

	/**
	 * @param array $array
	 *
	 * @return stdClass
	 */
	public static function arr_obj(array $array): stdClass
	{
		$object = new stdClass();
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$value = self::arr_obj($value);
				}
				$object->$key = $value;
			}
		}
		return $object;
	}

	/**
	 * @param mixed $data
	 *
	 * @return array
	 */
	public static function obj_arr($data): array
	{
		$result = [];
		foreach ($data as $key => $value) {
			$result[$key] = (is_array($value) || is_object($value)) ? self::obj_arr($value) : $value;
		}
		return $result;

	}

	/**
	 * @param array $array
	 * @param object|null $xml
	 *
	 * @return bool|string
	 */
	public static function arr_xml(array $array, object $xml = null)
	{
		if ($xml == null) {
			$xml = new SimpleXMLElement('<result/>');
		}
		if (is_array($array)) {
			foreach ($array as $key => $val) {
				if (is_array($val)) {
					self::arr_xml($val, $xml->addChild($key));
				} else {
					$xml->addChild($key, $val);
				}
			}
		}
		return $xml->asXML();
	}
}