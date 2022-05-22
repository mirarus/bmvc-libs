<?php

/**
 * IMError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\MError;

interface IMError
{

  /**
   * @param $text
   * @param $message
   * @param bool $html
   * @param string|null $title
   * @param string|null $color
   * @param bool $stop
   * @param int $code
   * @return void
   */
  public static function print($text, $message = null, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $code = 200): void;

  /**
   * @param string|null $title
   * @param $text
   * @param $message
   * @param bool $html
   * @param bool $stop
   * @param string|null $color
   * @param int $code
   * @return void
   */
  public static function p(string $title = null, $text, $message = null, bool $html = false, bool $stop = false, string $color = null, int $code = 200): void;

  /**
   * @param array $array
   * @return _MError
   */
  public static function set(array $array): _MError;

  /**
   * @param bool $html
   * @return _MError
   */
  public static function html(bool $html = true): _MError;

  /**
   * @param bool $stop
   * @return _MError
   */
  public static function stop(bool $stop = true): _MError;

  /**
   * @param string $title
   * @return _MError
   */
  public static function title(string $title): _MError;

  /**
   * @param string $color
   * @return _MError
   */
  public static function color(string $color): _MError;

  /**
   * @param int $code
   * @return _MError
   */
  public static function code(int $code = 200): _MError;
}