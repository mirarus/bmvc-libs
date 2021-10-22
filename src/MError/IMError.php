<?php

/**
 * IMError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\MError;

interface IMError
{

	public static function print($text, $message = null, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $response_code = 200): void;
  public static function set(array $array): _MError;
  public static function color(string $color): _MError;
  public static function html(bool $bool = false): _MError;
  public static function title(string $title): _MError;
  public static function stop(bool $stop = true): _MError;
}