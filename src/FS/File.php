<?php

/**
 * File
 *
 * Mirarus BMVC
 * @package BMVC\Libs\FS
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 3.0
 */

namespace BMVC\Libs\FS;

trait File
{

	/**
	 * @param  string      $file
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function is_file(string $file, string $type = null): bool
	{
		if ($type == 'app') {
			$file = self::app($file);
		} elseif ($type == 'base') {
			$file = self::base($file);
		}
		return (bool) (is_file($file) && file_exists($file));
	}

	/**
	 * @param  string      $file
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function mk_file(string $file, string $type = null): bool
	{
		if ($type == 'app') {
			$file = self::app($file);
		} elseif ($type == 'base') {
			$file = self::base($file);
		}

		if (!self::is_file($file)) {
			return (bool) @fopen($file, "w");
		} else {
			return false;
		}
	}

	/**
	 * @param  string      $file
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function rm_file(string $file, string $type = null): bool
	{
		if ($type == 'app') {
			$file = self::app($file);
		} elseif ($type == 'base') {
			$file = self::base($file);
		}

		if (self::is_file($file)) {
			return (bool) @unlink($file);
		} else {
			return false;
		}
	}

	/**
	 * @param string|null $dir
	 * @param string|null $type
	 *
	 * @return (false|string)[]
	 *
	 * @phpstan-ignore-next-line
	 *
	 * @psalm-return list<false|string>
	 */
	public static function files(string $dir = null, string $type = null): array
	{
		if ($type == 'app') {
			$dir = self::app($dir);
		} elseif ($type == 'base') {
			$dir = self::base($dir);
		} else {
			$dir = (!$dir ? self::app() : $dir);
		}

		$adir = @array_slice((array) @scandir($dir), 2);
		
		$array = [];
		if ($adir) {
			foreach ($adir as $d) {
				if (self::is_file((string) $d)) $array[] = $d;
			}
		}
		return $array;
	}
}