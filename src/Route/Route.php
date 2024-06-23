<?php

/**
 * Route
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.17
 */

namespace BMVC\Libs\Route;

use BMVC\Libs\CL;
use BMVC\Libs\MError;
use BMVC\Libs\Request;
use BMVC\Libs\Util;
use Closure;

class Route implements IRoute, IMethod
{
	use Method;

	/**
	 * @var array
	 */
	private static $routes = [];

	/**
	 * @var array
	 */
	private static $groups = [];

	/**
	 * @var array
	 */
	private static $middlewares = [];

	/**
	 * @var array
	 */
	private static $errors = [];

	/**
	 * @var array
	 */
	private static $args = [];

	/**
	 * @var array
	 */
	private static $trashMiddlewares = [];

	/**
	 * @var string
	 */
	private static $prefix = '/';

	/**
	 * @var
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
	 * @var int
	 */
	private static $groupped = 0;

	/**
	 * @var string
	 */
	private static $mainRoute = '/';

	/**
	 * @var string[]
	 */
	private static $patterns = [
		':all' => '(.*)',
		':num' => '([0-9]+)',
		':id' => '([0-9]+)',
		':alpha' => '([a-zA-Z]+)',
		':alpnum' => '([a-zA-Z0-9_-]+)',
		':lowercase' => '([a-z]+)',
		':uppercase' => '([A-Z]+)',

		'{all}' => '(.*)',
		'{num}' => '([0-9]+)',
		'{id}' => '([0-9]+)',
		'{alpha}' => '([a-zA-Z]+)',
		'{alpnum}' => '([a-zA-Z0-9_-]+)',
		'{lowercase}' => '([a-z]+)',
		'{uppercase}' => '([A-Z]+)',
	];

	/**
	 * @var string[]
	 */
	private static $separators = ['@', '/', '.', '::', ':'];

	/**
	 * @param array|null $args
	 */
	public static function args(array $args = null)
	{
		self::$args = $args;
	}

	/**
	 * @param array|null $return
	 *
	 * @return array|null
	 */
	public static function Run(array &$return = null)
	{
		$routes = (array)self::$routes;
		$match = false;

		if ($routes) {

			foreach ($routes as $route) {

				$method = $route['method'];
				$action = $route['closure'];
				$callback = $route['callback'];
				$url = $route['pattern'];
				$ip = ($route['ip'] ? $route['ip'] : null);
				$_return = ($route['return'] ? $route['return'] : null);
				$namespaces = ($route['namespaces'] ? $route['namespaces'] : null);
				$middlewares = ($route['middlewares'] ? $route['middlewares'] : null);

				if (preg_match("#^{$url}$#", ('/' . Util::get_url()), $params)) {

					if ($method === @Request::getRequestMethod() && @Request::checkIp($ip)) {

						$match = true;
						array_shift($params);

						return $return = [
							'route' => $route,
							'method' => $method,
							'action' => $action,
							'callback' => $callback,
							'params' => $params,
							'namespaces' => $namespaces,
							'middlewares' => $middlewares,
							'url' => $url,
							'_url' => Util::get_url(),
							'_return' => $_return,
						];
					}
				}
			}
		}

		if (!$match)
			self::getErrors(404);
	}

	/**
	 * @param string $method
	 * @param string|null $pattern
	 * @param $callback
	 *
	 * @return void
	 */
	private static function set(string $method, string $pattern = null, $callback): void
	{
		$closure = null;
		if ($pattern == '/') {
			$pattern = self::$prefix . trim((string)$pattern, '/');
		} else {
			if (self::$prefix == '/') {
				$pattern = self::$prefix . trim((string)$pattern, '/');
			} else {
				$pattern = self::$prefix . $pattern;
			}
		}

		foreach (self::$patterns as $key => $value) {
			$pattern = @strtr($pattern, [$key => $value]);
		}
		if (is_callable($callback)) {
			$closure = $callback;
		} else if (is_string($callback)) {
			if (stripos($callback, '@') !== false) {
				$closure = $callback;
			} else if (stripos($callback, '/') !== false) {
				$closure = $callback;
			} else if (stripos($callback, '.') !== false) {
				$closure = $callback;
			} else if (stripos($callback, '::') !== false) {
				$closure = $callback;
			} else if (stripos($callback, ':') !== false) {
				$closure = $callback;
			}
		} else if (is_array($callback)) {
			$closure = $callback[0] . ':' . $callback[1];
		}

		if ($closure) {
			$route_ = [
				'method' => $method,
				'pattern' => $pattern,
				'callback' => @$callback,
				'closure' => @$closure
			];

			if (self::$ip)
				$route_['ip'] = self::$ip;
			if (self::$middlewares)
				$route_['middlewares'] = self::$middlewares;
			if (self::$return)
				$route_['return'] = self::$return;
			if (self::$namespaces)
				$route_['namespaces'] = self::$namespaces;

			self::$routes[] = $route_;
		}
	}

	/**
	 * @param Closure $callback
	 *
	 * @return void
	 */
	public static function group(Closure $callback): void
	{
		self::$groupped++;
		self::$groups[] = [
			'baseRoute' => self::$prefix,
			'middlewares' => self::$middlewares,
			'ip' => self::$ip,
			'return' => self::$return,
			'namespaces' => self::$namespaces
		];

		call_user_func_array($callback, [new self, [
			'baseRoute' => self::$prefix,
			'middlewares' => self::$middlewares,
			'ip' => self::$ip,
			'return' => self::$return,
			'namespaces' => self::$namespaces
		]]);

		if (self::$groupped > 0) {
			self::$prefix = self::$groups[self::$groupped - 1]['baseRoute'];
			self::$middlewares = self::$groups[self::$groupped - 1]['middlewares'];
			self::$ip = self::$groups[self::$groupped - 1]['ip'];
			self::$return = self::$groups[self::$groupped - 1]['return'];
			//self::$namespaces		= self::$groups[self::$groupped-1]['namespaces'];
		}
		self::$groupped--;
		if (self::$groupped <= 0) {
			self::$prefix = '/';
			self::$middlewares = [];
			self::$ip = '';
			self::$return = '';
			self::$namespaces = [];
		}
		self::$prefix = @self::$groups[self::$groupped - 1]['baseRoute'];
	}

	/**
	 * @param array $expressions
	 *
	 * @return static
	 */
	public static function where(array $expressions): self
	{
		$routeKey = array_search(end(self::$routes), self::$routes);
		$pattern = Util::parse_uri(self::$routes[$routeKey]['pattern'], $expressions);
		$pattern = implode('/', $pattern);
		self::$routes[$routeKey]['pattern'] = $pattern;
		return new self;
	}

	/**
	 * @param string $name
	 * @param array|null $params
	 *
	 * @return static
	 */
	public static function name(string $name, array $params = null): self
	{
		$routeKey = array_search(end(self::$routes), self::$routes);
		self::$routes[$routeKey]['name'] = [$name, $params];
		return new self;
	}

	/**
	 * @param string $name
	 * @param array|null $params
	 * @param array|null $patternParams
	 *
	 * @return string
	 */
	public static function url(string $name, array $params = null, array $patternParams = null): string
	{
		$pattern = "";
		foreach (self::$routes as $route) {
			if (array_key_exists('name', $route) && $route['name'][0] == $name && $route['name'][1] == $params) {
				$pattern = $route['pattern'];
				$pattern = Util::parse_uri($pattern, $patternParams);
				foreach ($patternParams as $parK => $parV) {
					if (self::$patterns[$parK]) {
						$pattern = str_replace(self::$patterns[$parK], $parV, $pattern);
					} else {
						$pattern = str_replace($parK, $parV, $pattern);
					}
				}
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
	 * @param int|null $code
	 * @param $callback
	 *
	 * @return void
	 */
	private static function setError(int $code = null, $callback): void
	{
		$closure = null;

		if (is_callable($callback)) {
			$closure = $callback;
		} else if (is_string($callback)) {
			$callback = CL::replace($callback);
			if (stripos($callback, '@') !== false) {
				$closure = $callback;
			} else if (stripos($callback, '/') !== false) {
				$closure = $callback;
			} else if (stripos($callback, '.') !== false) {
				$closure = $callback;
			} else if (stripos($callback, '::') !== false) {
				$closure = $callback;
			} else if (stripos($callback, ':') !== false) {
				$closure = $callback;
			}
			if (self::$separators != null) {
				foreach (self::$separators as $separator) {
					if (@strstr($closure, $separator)) {
						$closure = @explode($separator, $closure);
					}
				}
			}
			if (is_array($closure)) {
				$class = $closure[0];
				$method = $closure[1];
				$closure = (new $class)->{$method}();
			}
		} else if (is_array($closure)) {
			$class = $closure[0];
			$method = $closure[1];
			$closure = (new $class)->{$method}();
		}

		if ($closure) {
			self::$errors[$code] = $closure;
		}
	}

	/**
	 * @param int|null $code
	 *
	 * @return array|mixed
	 */
	public static function getErrors(int $code = null)
	{
		self::$errors[404] = self::$errors[404] ?: function () {
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				MError::p('Not Found', '404 Not Found', null, true, true, 'danger', 404);
			} else {
				http_response_code(404);
				@header("Content-Type: application/json; charset=utf-8");
				return json_encode(['message' => '404 Not Found', 'page' => Util::get_url()]);
			}
		};
		self::$errors[500] = self::$errors[500] ?: function () {
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				MError::p('Internal Server Error', '500 Internal Server Error', null, true, true, 'danger', 500);
			} else {
				http_response_code(500);
				@header("Content-Type: application/json; charset=utf-8");
				return json_encode(['message' => '404 Internal Server Error', 'page' => Util::get_url()]);
			}
		};

		return $code ? self::$errors[$code]() : self::$errors;
	}

	/**
	 * @param string $origin
	 * @param string $destination
	 * @param bool $permanent
	 *
	 * @return void
	 */
	public static function redirect($origin, $destination, $permanent = true)
	{
		if (Util::get_url() == $origin) {
			if (headers_sent() == false) {
				header('Location: ' . Util::url($destination), true, ($permanent ? 301 : 302));
			}
			exit();
		}
	}
}