<?php

/**
 * FS / File System
 *
 * Mirarus BMVC
 * @package BMVC\Libs\FS
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\FS;

abstract class FS implements IFS
{

	use Dir, File;

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
	public static function base(string $dir = null): string
	{
		$path = (self::$path ? self::$path : dirname(__DIR__));
		$path = ($dir ? self::implode([$path, $dir]) : ($path . DIRECTORY_SEPARATOR));

		return self::replace($path);
	}

	/**
	 * @param  string|null $dir
	 * @return string
	 */
	public static function app(string $dir = null): string
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
	public static function get(string $type = null, string $dir = null)
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
	 * @param mixed $arg
	 */
	public static function replace($arg = null)
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
	public static function trim(string $arg = null): string
	{
		return @trim(self::replace($arg), DIRECTORY_SEPARATOR);
	}
}