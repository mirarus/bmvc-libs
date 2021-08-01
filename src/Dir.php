<?php

/**
 * Dir
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 2.8
 */

namespace BMVC\Libs;

trait Dir
{
	
	/**
	 * @param  string      $dir
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function is_dir(string $dir, string $type=null): bool
	{
		if ($type == 'app') {
			$dir = self::app($dir);
		} elseif ($type == 'base') {
			$dir = self::base($dir);
		}
		return (is_dir($dir) && opendir($dir));
	}

	/**
	 * @param  string       $dir
	 * @param  string|null  $type
	 * @param  int|integer  $perms
	 * @param  bool|boolean $recursive
	 * @return boolean
	 */
	public static function mk_dir(string $dir, string $type=null, int $perms=0777, bool $recursive=true): bool
	{
		if ($type == 'app') {
			$dir = self::app($dir);
		} elseif ($type == 'base') {
			$dir = self::base($dir);
		}

		if (!self::is_dir($dir)) {
			return (bool) @mkdir($dir, $perms, $recursive);
		} else {
			return false;
		}
	}

	/**
	 * @param  string      $dir
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function rm_dir(string $dir, string $type=null): bool
	{
		if ($type == 'app') {
			$dir = self::app($dir);
		} elseif ($type == 'base') {
			$dir = self::base($dir);
		}

		if (self::is_dir($dir)) {
			return (bool) rmdir($dir);
		} else {
			return false;
		}
	}

	/**
	 * @param  string      $dir
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function rm_dir_sub(string $dir, string $type=null): bool
	{
		if ($type == 'app') {
			$dir = self::app($dir);
		} elseif ($type == 'base') {
			$dir = self::base($dir);
		}

		if (self::is_dir($dir)) {
			if (PHP_OS_FAMILY === 'Windows') {
				return (bool) (null !== exec(sprintf("rd /s /q %s", escapeshellarg($dir))));
			} else {
				return (bool) (null !== exec(sprintf("rm -rf %s", escapeshellarg($dir))));
			}
		} else {
			return false;
		}
	}

	/**
	 * @param  string|null $dir
	 * @param  string|null $type
	 * @return array
	 */
	public static function directories(string $dir=null, string $type=null): array
	{
		if ($type == 'app') {
			$dir = self::app($dir);
		} elseif ($type == 'base') {
			$dir = self::base($dir);
		} else {
			$dir = (!$dir ? self::app() : $dir);
		}

		$adir = @array_slice(@scandir($dir), 2);
		
		$array = [];
		if ($adir) {
			foreach ($adir as $d) {
				if (self::is_dir($d)) $array[] = $d;
			}
		}
		return $array;
	}
}