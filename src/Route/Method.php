<?php

/**
 * Method
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Route;

use BMVC\Libs\Request\Request;

trait Method
{

  /**
   * @param array $middlewares
   * @return static
   */
  public static function middleware(array $middlewares): self
  {
    foreach ($middlewares as $middleware) {
      self::$middlewares[$middleware] = [
        'callback' => $middleware . '@handle'
      ];
    }
    return new self;
  }

  /**
   * @param string|null $prefix
   * @return static
   */
  public static function prefix(string $prefix = null): self
  {
    self::$prefix = self::$mainRoute . $prefix;
    return new self;
  }

  /**
   * @param string $ip
   * @return static
   */
  public static function ip(string $ip): self
  {
    self::$ip = $ip;
    return new self;
  }

  /**
   * @param string $return
   * @return static
   */
  public static function return(string $return): self
  {
    self::$return = $return;
    return new self;
  }

  /**
   * @param array $arg
   * @param string|null $sub
   * @return static
   */
  public static function namespace(array $arg, string $sub = null): self
  {
    foreach (@$arg as $key => $val) {
      $sub = ($sub != null) ? (@trim(@str_replace(['/', '//'], '\\', $sub), '\\') . '\\') : null;
      self::$namespaces[$key] = (@trim(@str_replace(['/', '//'], '\\', $sub), '\\') . '\\');
    }
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function get(string $pattern = null, $callback): self
  {
    $pattern = trim((string)$pattern);
    $pattern = ($pattern == '/' ? null : $pattern);
    self::set(Request::METHOD_GET, self::$mainRoute . $pattern, $callback);
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function post(string $pattern = null, $callback): self
  {
    $pattern = trim((string)$pattern);
    $pattern = ($pattern == '/' ? null : $pattern);
    self::set(Request::METHOD_POST, self::$mainRoute . $pattern, $callback);
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function patch(string $pattern = null, $callback): self
  {
    $pattern = trim((string)$pattern);
    $pattern = ($pattern == '/' ? null : $pattern);
    self::set(Request::METHOD_PATCH, self::$mainRoute . $pattern, $callback);
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function delete(string $pattern = null, $callback): self
  {
    $pattern = trim((string)$pattern);
    $pattern = ($pattern == '/' ? null : $pattern);
    self::set(Request::METHOD_DELETE, self::$mainRoute . $pattern, $callback);
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function put(string $pattern = null, $callback): self
  {
    $pattern = trim((string)$pattern);
    $pattern = ($pattern == '/' ? null : $pattern);
    self::set(Request::METHOD_PUT, self::$mainRoute . $pattern, $callback);
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function options(string $pattern = null, $callback): self
  {
    $pattern = trim((string)$pattern);
    $pattern = ($pattern == '/' ? null : $pattern);
    self::set(Request::METHOD_OPTIONS, self::$mainRoute . $pattern, $callback);
    return new self;
  }

  /**
   * @param array $methods
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function match(array $methods, string $pattern = null, $callback): self
  {
    foreach ($methods as $method) {
      $pattern = trim((string)$pattern);
      $pattern = ($pattern == '/' ? null : $pattern);
      self::set(strtoupper($method), self::$mainRoute . $pattern, $callback);
    }
    return new self;
  }

  /**
   * @param string|null $pattern
   * @param $callback
   * @return static
   */
  public static function any(string $pattern = null, $callback): self
  {
    $methods = [Request::METHOD_GET, Request::METHOD_POST, Request::METHOD_PATCH, Request::METHOD_DELETE, Request::METHOD_PUT, Request::METHOD_OPTIONS];
    foreach ($methods as $method) {
      $pattern = trim((string)$pattern);
      $pattern = ($pattern == '/' ? null : $pattern);
      self::set($method, self::$mainRoute . $pattern, $callback);
    }
    return new self;
  }
}