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

interface IMethod
{

  public static function middleware(array $middlewares = []): Route;
  public static function prefix(string $prefix = null): Route;
  public static function ip(string $ip): Route;
  public static function return(string $return): Route;
  public static function namespace(array $arg, string $sub = null): Route;
  public static function get(string $pattern = null, $callback): Route;
  public static function post(string $pattern = null, $callback): Route;
  public static function patch(string $pattern = null, $callback): Route;
  public static function delete(string $pattern = null, $callback): Route;
  public static function put(string $pattern = null, $callback): Route;
  public static function options(string $pattern = null, $callback): Route;
  public static function match(array $methods, string $pattern = null, $callback): Route;
  public static function any(string $pattern = null, $callback): Route;
}