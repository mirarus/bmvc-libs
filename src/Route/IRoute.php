<?php

/**
 * IRoute
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Route;

use Closure;

interface IRoute
{

  public static function Run(array &$return = null); // @phpstan-ignore-line
  public static function group(Closure $callback); // @phpstan-ignore-line
  public static function where(array $expressions): Route; // @phpstan-ignore-line
  public static function name(string $name, array $params): Route; // @phpstan-ignore-line
  public static function url(string $name, array $params = null): string; // @phpstan-ignore-line
  public static function routes(): array; // @phpstan-ignore-line
  public static function error($callback): Route; // @phpstan-ignore-line
  public static function set_404($callback): Route; // @phpstan-ignore-line
  public static function get_404(); // @phpstan-ignore-line
  public static function url_check(array $urls, string $url); // @phpstan-ignore-line
}