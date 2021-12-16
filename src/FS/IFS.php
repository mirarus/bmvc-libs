<?php

/**
 * IFS
 *
 * Mirarus BMVC
 * @package BMVC\Libs\FS
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\FS;

interface IFS
{

	public static function setPath(string $path): void;
	public static function base(string $dir = null): string;
	public static function app(string $dir = null): string;
	public static function get(string $type = null, string $dir = null); // @phpstan-ignore-line
	public static function replace($arg = null); // @phpstan-ignore-line
	public static function implode(array $arg): string; // @phpstan-ignore-line
	public static function explode(string $arg): array; // @phpstan-ignore-line
	public static function trim(string $arg = null): string;
}