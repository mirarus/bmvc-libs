<?php

/**
 * Method
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Route;

use BMVC\Libs\CL;

trait Method
{

	/**
	 * @param  array $middlewares
	 * @return Route
	 */
	public static function middleware(array $middlewares = []): Route
	{
		foreach ($middlewares as $middleware) {
			self::$middlewares[$middleware] =[
				'callback' => $middleware . '@handle'
			];
		}
		return new self;
	}

	/**
	 * @param  string|null $prefix
	 * @return Route
	 */
	public static function prefix(string $prefix = null): Route
	{
		self::$prefix = self::$mainRoute . $prefix;
		return new self;
	}

	/**
	 * @param  string $ip
	 * @return Route
	 */
	public static function ip(string $ip): Route
	{
		self::$ip = $ip;
		return new self;
	}

	/**
	 * @param  string $return
	 * @return Route
	 */
	public static function return(string $return): Route
	{
		self::$return = $return;
		return new self;
	}

	/**
	 * @param  array       $arg
	 * @param  string|null $sub
	 * @return Route
	 */
	public static function namespace(array $arg, string $sub = null): Route
	{
		foreach (@$arg as $key => $val) {

			$sub = ($sub != null) ? (CL::trim(CL::replace($sub)) . '\\') : null;
			self::$namespaces[$key] = CL::trim(CL::replace(($sub . $val))) . '\\';
		}
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function get(string $pattern = null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('GET', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function post(string $pattern = null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('POST', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function patch(string $pattern = null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('PATCH', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function delete(string $pattern = null, $callback, string $type = null): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('DELETE', self::$mainRoute . $pattern, $callback, $type);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function put(string $pattern = null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('PUT', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function options(string $pattern = null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('OPTIONS', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  array       $methods
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function match(array $methods, string $pattern = null, $callback): Route
	{
		foreach ($methods as $method) {
			$pattern = trim($pattern);
			$pattern = ($pattern == '/' ? null : $pattern);
			self::set(strtoupper($method), self::$mainRoute . $pattern, $callback);
		}
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function any(string $pattern = null, $callback): Route
	{
		$methods = ['GET', 'POST', 'PATCH', 'DELETE', 'PUT', 'OPTIONS'];
		foreach ($methods as $method) {
			$pattern = trim($pattern);
			$pattern = ($pattern == '/' ? null : $pattern);
			self::set($method, self::$mainRoute . $pattern, $callback);
		}
		return new self;
	}
}