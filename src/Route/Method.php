<?php

/**
 * Method
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Route;

use BMVC\Libs\CL;

trait Method
{

	/**
	 * @param array $middlewares
	 *
	 * @phpstan-ignore-next-line
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
	 */
	public static function prefix(string $prefix = null): self
	{
		self::$prefix = self::$mainRoute . $prefix;
		return new self;
	}

	/**
	 * @param string $ip
	 */
	public static function ip(string $ip): self
	{
		self::$ip = $ip;
		return new self;
	}

	/**
	 * @param string $return
	 */
	public static function return(string $return): self
	{
		self::$return = $return;
		return new self;
	}

	/**
	 * @param array       $arg
	 * @param string|null $sub
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function namespace(array $arg, string $sub = null): self
	{
		foreach (@$arg as $key => $val) {

			$sub = ($sub != null) ? (CL::trim(CL::replace($sub)) . '\\') : null;
			self::$namespaces[$key] = CL::trim(CL::replace(($sub . $val))) . '\\';
		}
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function get(string $pattern = null, $callback): self
	{
		$pattern = trim((string) $pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('GET', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function post(string $pattern = null, $callback): self
	{
		$pattern = trim((string) $pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('POST', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function patch(string $pattern = null, $callback): self
	{
		$pattern = trim((string) $pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('PATCH', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function delete(string $pattern = null, $callback): self
	{
		$pattern = trim((string) $pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('DELETE', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function put(string $pattern = null, $callback): self
	{
		$pattern = trim((string) $pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('PUT', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function options(string $pattern = null, $callback): self
	{
		$pattern = trim((string) $pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::set('OPTIONS', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param array       $methods
	 * @param string|null $pattern
	 * @param mixed       $callback
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function match(array $methods, string $pattern = null, $callback): self
	{
		foreach ($methods as $method) {
			$pattern = trim((string) $pattern);
			$pattern = ($pattern == '/' ? null : $pattern);
			self::set(strtoupper($method), self::$mainRoute . $pattern, $callback);
		}
		return new self;
	}

	/**
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	public static function any(string $pattern = null, $callback): self
	{
		$methods = ['GET', 'POST', 'PATCH', 'DELETE', 'PUT', 'OPTIONS'];
		foreach ($methods as $method) {
			$pattern = trim((string) $pattern);
			$pattern = ($pattern == '/' ? null : $pattern);
			self::set($method, self::$mainRoute . $pattern, $callback);
		}
		return new self;
	}
}