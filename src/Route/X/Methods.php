<?php

/**
 * Methods
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Route;

class Methods implements IMethods
{

	/**
	 * @var string
	 */
	private static $mainRoute = '/';

	/**
	 * @var string
	 */
	private static $prefix = '/';

	/**
	 * @var string|null
	 */
	private static $ip;

	/**
	 * @var string|null
	 */
	private static $name;

	/**
	 * @var string|callable
	 */
	protected static $notFound;

	/**
	 * @var array
	 */
	private static $patterns = [
		':all'        => '(.*)',
		':num'        => '([0-9]+)',
		':id'         => '([0-9]+)',
		':alpha'	  	=> '([a-zA-Z]+)',
		':alpnum'     => '([a-zA-Z0-9_-]+)',
		':lowercase'  => '([a-z]+)',
		':uppercase'  => '([A-Z]+)'
	];

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function prefix(string $prefix): Route
	{			
		self::$prefix = $prefix;
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function ip(string $ip): Route
	{
		self::$ip = $ip;
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function name(string $name): Route
	{
		self::$name = $name;
		return new Route;
	}

	/**
	 * @param  string|callable $callback
	 * @return Route
	 */
	public static function error($callback): Route
	{
		self::$notFound = $callback;
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function get(string $uri = null, $callback): Route
	{
		self::set('GET', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function post(string $uri = null, $callback): Route
	{
		self::set('POST', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function put(string $uri = null, $callback): Route
	{
		self::set('PUT', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function delete(string $uri = null, $callback): Route
	{
		self::set('DELETE', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function connect(string $uri = null, $callback): Route
	{
		self::set('CONNECT', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function options(string $uri = null, $callback): Route
	{
		self::set('OPTIONS', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function trace(string $uri = null, $callback): Route
	{
		self::set('TRACE', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function patch(string $uri = null, $callback): Route
	{
		self::set('PATCH', $uri, $callback);
		return new Route;
	}

	/**
	 * @param  array								 $methods
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function match(array $methods, string $uri = null, $callback): Route
	{
		foreach ($methods as $method) {
			self::set(strtoupper($method), $uri, $callback);
		}
		return new Route;
	}

	/**
	 * @param  string|null					 $uri
	 * @param  string|array|callable $callback
	 * @return Route
	 */
	public static function any(string $uri = null, $callback): Route
	{
		$methods = ['GET', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'];
		foreach ($methods as $method) {
			self::set($method, $uri, $callback);
		}
		return new Route;
	}

	/**
	 * @param string								$method
	 * @param string|null						$uri
	 * @param string|array|callable $callback
	 */
	private static function set(string $method, string $uri = null, $callback): void
	{
		// if (in_array(gettype($callback), ["string", "array", "callable"])) {
			
			$uri		= self::$mainRoute . $uri;
			$prefix = self::$mainRoute . trim(trim(self::$prefix), '/');

			$uri = $prefix . (($uri == '/' || $prefix == '/') ? trim($uri, '/') : $uri);

			$uri 		= trim(trim($uri), '/');
			$prefix = trim(trim($prefix), '/');

			foreach (self::$patterns as $key => $val) {
				$uri 		= strtr($uri, [$key => $val]);
				$prefix = strtr($prefix, [$key => $val]);
			}

			$route = [
				'method' => $method,
				'uri' => $uri,
				'callback' => $callback
			];

			if (trim($prefix, '/')) $route['prefix'] = $prefix;
			if (self::$ip) $route['ip'] = self::$ip;
			if (self::$name) $route['name'] = self::$name;

			Route::$routes[$method][$uri] = $route;
		// }
	}
}