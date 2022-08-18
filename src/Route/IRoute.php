<?php

/**
 * IRoute
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.6
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
   * @return void
   */
  public static function trashMiddlewares();

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
   * @param int $code
   * @param Closure $callback
   * @return mixed
   */
  public static function setErrors(int $code, Closure $callback);

  /**
   * @param int|null $code
   * @return mixed
   */
  public static function getErrors(int $code = null);

  /**
   * @param string $origin
   * @param string $destination
   * @param bool $permanent
   * @return void
   */
  public static function redirect($origin, $destination, $permanent = true);
}