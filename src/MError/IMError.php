<?php

/**
 * IMError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
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
   * @param int $response_code
   * @return void
   */
  public static function print($text, $message = null, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $response_code = 200): void;

  /**
   * @param array $array
   * @return _MError
   */
  public static function set(array $array): _MError;

  /**
   * @param string $color
   * @return _MError
   */
  public static function color(string $color): _MError;

  /**
   * @param bool $bool
   * @return _MError
   */
  public static function html(bool $bool = false): _MError;

  /**
   * @param string $title
   * @return _MError
   */
  public static function title(string $title): _MError;

  /**
   * @param bool $stop
   * @return _MError
   */
  public static function stop(bool $stop = true): _MError;
}