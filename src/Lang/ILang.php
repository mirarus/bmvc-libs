<?php

/**
 * ILang
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Lang
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Lang;

interface ILang
{

  /**
   * @param string $lang
   * @return array
   */
  public static function get_lang(string $lang): array;

  /**
   * @return array
   */
  public static function get_langs(): array;

  /**
   * @return string
   */
  public static function get(): string;

  /**
   * @param string|null $lang
   * @return void
   */
  public static function set(string $lang = null): void;

  /**
   * @param string $text
   * @param $replace
   * @return mixed
   */
  public static function __(string $text, $replace = null);

  /**
   * @param string $text
   * @param $replace
   * @return mixed
   */
  public static function ___(string $text, $replace = null);
}