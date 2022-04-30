<?php

/**
 * IFS
 *
 * Mirarus BMVC
 * @package BMVC\Libs\FS
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\FS;

interface IFS
{

  /**
   * @param string $path
   * @return void
   */
  public static function setPath(string $path): void;

  /**
   * @param string|null $dir
   * @return string
   */
  public static function base(string $dir = null): string;

  /**
   * @param string|null $dir
   * @return string
   */
  public static function app(string $dir = null): string;

  /**
   * @param string|null $type
   * @param string|null $dir
   * @return mixed
   */
  public static function get(string $type = null, string $dir = null);

  /**
   * @param $arg
   * @return mixed
   */
  public static function replace($arg = null);

  /**
   * @param array $arg
   * @return string
   */
  public static function implode(array $arg): string;

  /**
   * @param string $arg
   * @return array
   */
  public static function explode(string $arg): array;

  /**
   * @param string|null $arg
   * @return string
   */
  public static function trim(string $arg = null): string;
}