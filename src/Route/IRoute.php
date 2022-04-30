<?php

/**
 * IRoute
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Route;

use Closure;

interface IRoute
{

  /**
   * @param array|null $return
   * @return mixed
   */
  public static function Run(array &$return = null);

  /**
   * @param Closure $callback
   * @return mixed
   */
  public static function group(Closure $callback);

  /**
   * @param array $expressions
   * @return Route
   */
  public static function where(array $expressions): Route;

  /**
   * @param string $name
   * @param array|null $params
   * @return Route
   */
  public static function name(string $name, array $params = null): Route;

  /**
   * @param string $name
   * @param array|null $params
   * @return string
   */
  public static function url(string $name, array $params = null): string;

  /**
   * @return array
   */
  public static function routes(): array;

  /**
   * @param $callback
   * @return Route
   */
  public static function set_404($callback): Route;

  /**
   * @return mixed
   */
  public static function get_404();

  /**
   * @param $callback
   * @return Route
   */
  public static function set_500($callback): Route;

  /**
   * @return mixed
   */
  public static function get_500();

  /**
   * @param array $urls
   * @param string $url
   * @return mixed
   */
  public static function url_check(array $urls, string $url);
}