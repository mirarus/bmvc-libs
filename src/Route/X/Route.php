<?php

/**
 * Route
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Route;

use BMVC\Libs\Request;
use BMVC\Libs\Util;

final class Route extends Methods
{

	/**
	 * @var array
	 */
	protected static $routes = [];

	/**
	 * @return array
	 */
	public static function routes(): array
	{
		return (array) self::$routes;
	}

	public static function get_error()
	{
		return static::$notFound;
	}

	/**
	 * TODO;
	public static function url(string $name, array $params = []): string
	{
		$array_keymap = Util::array_search($name, self::routes());
		
		if ($array_keymap[2] == 'name') {

			$pattern = Util::parse_uri($array_keymap[1], $params);
			$pattern = implode('/', $pattern);
		}

		return $pattern ?? "";
	}
	*/

	/**
	 * @return array|null
	 */
	public static function run()
	{
		$routes = (array) self::routes()[Request::getRequestMethod()];

		if (isset($routes) && !empty($routes)) {

			foreach ($routes as $route) {

				$method 	= $route['method'];
				$uri			= $route['uri'];
				$callback	= $route['callback'];
				$ip				= ($route['ip'] ?? null);
				$name			= ($route['name'] ?? null);

				if (preg_match("#^{$uri}$#", Util::get_url(), $params)) {

					if ($ip && !Request::checkIp($ip)) return false;

					$url = array_shift($params);

					return [
						'method' => $method,
						'uri' => $uri,
						'url' => $url,
						'params' => $params,
						'callback' => $callback
					];
				}
			}
		}
	}
}