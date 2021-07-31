<?php

/**
 * File
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 2.7
 */

namespace BMVC\Libs;

class File
{

	private static $path;

	/**
	 * @param string $path
	 */
	public static function setPath(string $path): void
	{
		self::$path = $path;
	}

	/**
	 * @param  string|null $dir
	 * @return string
	 */
	public static function base(string $dir=null): string
	{
		$path = (self::$path ? self::$path : dirname(__DIR__));
		$path = ($dir ? self::implode([$path, $dir]) : ($path . DIRECTORY_SEPARATOR));

		return self::replace($path);
	}

	/**
	 * @param  string|null $dir
	 * @return string
	 */
	public static function app(string $dir=null): string
	{
		$path = (self::$path ? dirname(self::base()) : self::base());
		$path = dirname(dirname(dirname($path)));
		$path = ($dir ? self::implode([$path, $dir]) : ($path . DIRECTORY_SEPARATOR));

		return self::replace($path);
	}

	/**
	 * @param  string|null $type
	 * @param  string|null $dir
	 * @return string|array
	 */
	public static function get(string $type=null, string $dir=null)
	{
		if ($type == 'base') {
			return self::base($dir);
		} elseif ($type == 'app') {
			return self::app($dir);
		} else {
			return [
				'base' => self::get('base', $dir),
				'app' => self::get('app', $dir)
			];
		}
	}

	/**
	 * @param  string|null $arg
	 * @return string
	 */
	public static function replace(string $arg=null): string
	{
		return @str_replace(['/', '//', '\\'], DIRECTORY_SEPARATOR, $arg);
	}
	
	/**
	 * @param  array  $arg
	 * @return string
	 */
	public static function implode(array $arg): string
	{
		return @implode(DIRECTORY_SEPARATOR, $arg);
	}

	/**
	 * @param  string $arg
	 * @return array
	 */
	public static function explode(string $arg): array
	{
		return @explode(DIRECTORY_SEPARATOR, $arg);
	}

	/**
	 * @param  string|null $arg
	 * @return string
	 */
	public static function trim(string $arg=null): string
	{
		return @trim(self::replace($arg), DIRECTORY_SEPARATOR);
	}

	/**
	 * * Directory Methods *
	 * 
	 */

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

	/**
	 * * File Methods *
	 * 
	 */

	/**
	 * @param  string      $file
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function mk_file(string $file, string $type=null): bool
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
	public static function rm_file(string $file, string $type=null): bool
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
	 * @param  string      $file
	 * @param  string|null $type
	 * @return boolean
	 */
	public static function is_file(string $file, string $type=null): bool
	{
		if ($type == 'app') {
			$file = self::app($file);
		} elseif ($type == 'base') {
			$file = self::base($file);
		}
		return (is_file($file) && file_exists($file));
	}

	/**
	 * @param  string|null $dir
	 * @param  string|null $type
	 * @return array
	 */
	public static function files(string $dir=null, string $type=null): array
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
				if (self::is_file($d)) $array[] = $d;
			}
		}
		return $array;
	}
}