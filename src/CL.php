<?php

/**
 * CL
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 1.3
 */

namespace BMVC\Libs;

class CL
{

	/**
	 * @param  string       $class
	 * @param  bool|boolean $autoload
	 * @return boolean
	 */
	public static function is_class(string $class, bool $autoload=true): bool
	{
		$class = self::replace($class);
		return class_exists($class, $autoload);
	}

	/**
	 * @param mixed $arg
	 */
	public static function replace($arg=null)
	{
		return @str_replace(['/', '//'], '\\', $arg);
	}

	/**
	 * @param  array  $arg
	 * @return string
	 */
	public static function implode(array $arg): string
	{
		return @implode('\\', $arg);
	}

	/**
	 * @param  string $arg
	 * @return array
	 */
	public static function explode(string $arg): array
	{
		return @explode('\\', $arg);
	}

	/**
	 * @param  string|null $arg
	 * @return string
	 */
	public static function trim(string $arg=null): string
	{
		return @trim(self::replace($arg), '\\');
	}
}