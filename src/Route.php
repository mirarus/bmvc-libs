<?php

/**
 * Route
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-core
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 4.2
 */

namespace BMVC\Libs;

use Closure;

final class Route
{

	/**
	 * @var string
	 */
	public static $notFound = '';

	/**
	 * @var array
	 */
	private static $routes = [];

	/**
	 * @var array
	 */
	private static $groups = [];

	/**
	 * @var string
	 */
	private static $prefix = '/';

	/**
	 * @var string
	 */
	private static $ip;

	/**
	 * @var string
	 */
	private static $return;

	/**
	 * @var array
	 */
	private static $namespaces = [];

	/**
	 * @var integer
	 */
	private static $groupped = 0;

	/**
	 * @var string
	 */
	private static $mainRoute = '/';

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
		':uppercase'  => '([A-Z]+)',

		'{all}'       => '(.*)',
		'{num}'       => '([0-9]+)',
		'{id}'        => '([0-9]+)',
		'{alpha}'	    => '([a-zA-Z]+)',
		'{alpnum}'    => '([a-zA-Z0-9_-]+)',
		'{lowercase}' => '([a-z]+)',
		'{uppercase}' => '([A-Z]+)',
	];

	/**
	 * @param &$return
	 */
	public static function Run(&$return=null)
	{
		$routes = (array) self::routes();

		if (isset($routes) && !empty($routes)) {
			$match = 0;

			foreach ($routes as $route) {

				$method			= $route['method'];
				$action			= $route['callback'];
				$url				= $route['pattern'];
				$ip					= (isset($route['ip']) ? $route['ip'] : null);
				$_return		= (isset($route['return']) ? $route['return'] : null);
				$namespaces = (isset($route['namespaces']) ? $route['namespaces'] : null);

				if (preg_match("#^{$url}$#", ('/' . Util::get_url()), $params)) {

					if ($method === @Request::getRequestMethod() && @Request::checkIp($ip)) {

						$match++;
						array_shift($params);

						return $return = [
							'method'     => $method,
							'action'     => $action,
							'params'     => $params,
							'namespaces' => $namespaces,
							'url'        => $url,
							'_url'       => Util::get_url(),
							'_return'    => $_return,
						];
					}
				}
			}
			if ($match === 0) {
				self::get_404();
			}
		}
	}

	/**
	 * @param string      $method
	 * @param string|null $pattern
	 * @param mixed       $callback
	 */
	private static function Route(string $method, string $pattern=null, $callback): void
	{		
		$closure = null;
		if ($pattern == '/') {
			$pattern = self::$prefix . trim($pattern, '/');
		} else {
			if (self::$prefix == '/') {
				$pattern = self::$prefix . trim($pattern, '/');
			} else {
				$pattern = self::$prefix . $pattern;
			}
		}

		foreach (self::$patterns as $key => $value) {
			$pattern = @strtr($pattern, [$key => $value]);
		}
		if (is_callable($callback)) {
			$closure = $callback;
		} elseif (is_string($callback)) {
			if (stripos($callback, '@') !== false) {
				$closure = $callback;
			} elseif (stripos($callback, '/') !== false) {
				$closure = $callback;
			} elseif (stripos($callback, '.') !== false) {
				$closure = $callback;
			} elseif (stripos($callback, '::') !== false) {
				$closure = $callback;
			} elseif (stripos($callback, ':') !== false) {
				$closure = $callback;
			}
		} elseif (is_array($callback)) {
			$closure = $callback[0] . ':' . $callback[1];
		}

		if ($closure) {
			$route_ = [
				'method'   => $method,
				'pattern'  => $pattern,
				'callback' => @$closure
			];

			if (self::$ip) $route_['ip'] = self::$ip;
			if (self::$return) $route_['return'] = self::$return;
			if (self::$namespaces) $route_['namespaces'] = self::$namespaces;

			self::$routes[] = $route_;
		}
	}

	/**
	 * @param Closure $callback
	 */
	public static function group(Closure $callback): void
	{
		self::$groupped++;
		self::$groups[] = [
			'baseRoute'  => self::$prefix,
			'ip'         => self::$ip,
			'return'     => self::$return,
			'namespaces' => self::$namespaces
		];
		call_user_func($callback);
		if (self::$groupped > 0) {
			self::$prefix	= self::$groups[self::$groupped-1]['baseRoute'];
			self::$ip			= self::$groups[self::$groupped-1]['ip'];
			self::$return = self::$groups[self::$groupped-1]['return'];
		//self::$namespaces = self::$groups[self::$groupped-1]['namespaces'];
		}
		self::$groupped--;
		if (self::$groupped <= 0) {
			self::$prefix		  = '/';
			self::$ip				  = '';
			self::$return			= '';
			self::$namespaces = [];
		}
		self::$prefix = @self::$groups[self::$groupped-1]['baseRoute'];
	}

	/**
	 * @param  string|null $prefix
	 * @return Route
	 */
	public static function prefix(string $prefix=null): Route
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
	public static function namespace(array $arg, string $sub=null): Route
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
	public static function get(string $pattern=null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::Route('GET', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function post(string $pattern=null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::Route('POST', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function patch(string $pattern=null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::Route('PATCH', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function delete(string $pattern=null, $callback, string $type=null): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::Route('DELETE', self::$mainRoute . $pattern, $callback, $type);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function put(string $pattern=null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::Route('PUT', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function options(string $pattern=null, $callback): Route
	{
		$pattern = trim($pattern);
		$pattern = ($pattern == '/' ? null : $pattern);
		self::Route('OPTIONS', self::$mainRoute . $pattern, $callback);
		return new self;
	}

	/**
	 * @param  array       $methods
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function match(array $methods, string $pattern=null, $callback): Route
	{
		foreach ($methods as $method) {
			$pattern = trim($pattern);
			$pattern = ($pattern == '/' ? null : $pattern);
			self::Route(strtoupper($method), self::$mainRoute . $pattern, $callback);
		}
		return new self;
	}

	/**
	 * @param  string|null $pattern
	 * @param  mixed       $callback
	 * @return Route
	 */
	public static function any(string $pattern=null, $callback): Route
	{
		$methods = ['GET', 'POST', 'PATCH', 'DELETE', 'PUT', 'OPTIONS'];
		foreach ($methods as $method) {
			$pattern = trim($pattern);
			$pattern = ($pattern == '/' ? null : $pattern);
			self::Route($method, self::$mainRoute . $pattern, $callback);
		}
		return new self;
	}

	/**
	 * @param  mixed $expressions
	 * @return Route
	 */
	public static function where($expressions): Route
	{
		$routeKey = array_search(end(self::$routes), self::$routes);
		$pattern = self::_parseUri(self::$routes[$routeKey]['pattern'], $expressions);
		$pattern = '/' . implode('/', $pattern);
		$pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
		self::$routes[$routeKey]['pattern'] = $pattern;
		return new self;
	}

	/**
	 * @param  string $name
	 * @param  array  $params
	 * @return Route
	 */
	public static function name(string $name, array $params=[]): Route
	{
		$routeKey = array_search(end(self::$routes), self::$routes);
		self::$routes[$routeKey]['name'] = $name;
		return new self;
	}

	/**
	 * @param  string $name
	 * @param  array  $params
	 * @return string
	 */
	public static function url(string $name, array $params=[])
	{
		foreach (self::$routes as $route) {
			if (array_key_exists('name', $route) && $route['name'] == $name) {
				$pattern = $route['pattern'];
				$pattern = Util::parse_uri($pattern, $params);
				$pattern = implode('/', $pattern);
				break;
			}
		}
		return $pattern;
	}

	/**
	 * @return array
	 */
	public static function routes(): array
	{
		return self::$routes;
	}

	/**
	 * @param  mixed $callback
	 * @return Route
	 */
	public static function error($callback): Route
	{
		self::$notFound = $callback;
		return new self;
	}

	/**
	 * @param  mixed $callback
	 * @return Route
	 */
	public static function set_404($callback): Route
	{
		self::$notFound = $callback;
		return new self;
	}

	/**
	 * @return mixed
	 */
	public static function get_404()
	{
		if (@self::$notFound) {
			return self::$notFound;
		} else {
			if (Request::isGet()) {
				MError::print('404 Page Not Found!', (Util::get_url() ? 'Page: ' . Util::get_url() : null), true, 'Page Error!', null, true, 404);
			} else {
				echo Response::_json((Util::get_url() ? [
					'message' => '404 Page Not Found!',
					'page' => Util::get_url()
				] : [
					'message' => '404 Page Not Found!'
				]), 404);
			}
		}
	}

	/**
	 * @param  array  $urls
	 * @param  string $url
	 * @return mixed
	 */
	public static function url_check(array $urls=[], string $url)
	{
		if (!in_array($url, $urls)) {
			self::get_404();
		}
	}
}