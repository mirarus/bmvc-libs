<?php

/**
 * IMethods
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Route;

interface IMethods
{

	public static function prefix(string $prefix): Route;
	public static function ip(string $ip): Route;
	public static function name(string $name): Route;
	public static function get(string $uri = null, $callback): Route;
	public static function post(string $uri = null, $callback): Route;
	public static function put(string $uri = null, $callback): Route;
	public static function delete(string $uri = null, $callback): Route;
	public static function connect(string $uri = null, $callback): Route;
	public static function options(string $uri = null, $callback): Route;
	public static function trace(string $uri = null, $callback): Route;
	public static function patch(string $uri = null, $callback): Route;
	public static function match(array $methods, string $uri = null, $callback): Route;
	public static function any(string $uri = null, $callback): Route;
}