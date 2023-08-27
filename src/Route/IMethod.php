<?php

/**
 * IRoute
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace BMVC\Libs\Route;

interface IMethod
{

  /**
   * @paray $middlewares
   * @return Route
   */
  public static function middleware($middlewares): Route;

  /**
   * @param string|null $prefix
   * @param bool $mainRoute
   * @return Route
   */
  public static function prefix(string $prefix = null, bool $mainRoute = true): Route;

  /**
   * @param string $ip
   * @return Route
   */
  public static function ip(string $ip): Route;

  /**
   * @param string $return
   * @return Route
   */
  public static function return(string $return): Route;

  /**
   * @param array $arg
   * @param string|null $sub
   * @return Route
   */
  public static function namespace(array $arg, string $sub = null): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function get(string $pattern = null, $callback): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function post(string $pattern = null, $callback): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function patch(string $pattern = null, $callback): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function delete(string $pattern = null, $callback): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function put(string $pattern = null, $callback): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function options(string $pattern = null, $callback): Route;

  /**
   * @param array $methods
   * @param string|null $pattern
   * @param $callback
   * @return Route
   */
  public static function match(array $methods, string $pattern = null, $callback): Route;

  /**
   * @param string|null $pattern
   * @param $callback
   * @return mixed
   */
  public static function any(string $pattern = null, $callback);

  /**
   * @param int|null $code
   * @param $callback
   */
  public static function error(int $code = null, $callback);
}