<?php

/**
 * IRoute
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Route;

interface IRoute
{

	public static function Run(&$return = null);
	public static function group(Closure $callback): void;
	public static function where($expressions): self;
	public static function name(string $name, array $params = []): self;
	public static function url(string $name, array $params = []);
	public static function routes(): array;
	public static function error($callback): self;
	public static function set_404($callback): self;
	public static function get_404();
	public static function url_check(array $urls = [], string $url);
}