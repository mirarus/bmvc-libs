<?php

/**
 * ILang
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Lang
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Lang;

interface ILang
{

	public static function get_lang(string $lang): array; // @phpstan-ignore-line
	public static function get_langs(): array; // @phpstan-ignore-line
  public static function get(): string;
  public static function set(string $lang = null): void;
  public static function __(string $text, $replace = null); // @phpstan-ignore-line
  public static function ___(string $text, $replace = null); // @phpstan-ignore-line
}