<?php

/**
 * IRoute
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.6
 */

namespace BMVC\Libs\Route;

use Closure;

interface IRoute
{

  /**
   * @param array|null $args
   */
  public static function args(array $args = null);

  /**
   * @param array|null $return
   * @return mixed
   */
  public static function Run(array &$return = null);

  /**
   * @param Closure $callback
   * @return mixed
   */
  public static function group(Closure $callback);

  /**
   * @param array $expressions
   * @return Route
   */
  public static function where(array $expressions): Route;

  /**
   * @param string $name
   * @param array|null $params
   * @return Route
   */
  public static function name(string $name, array $params = null): Route;

	/**
	 * @param string $name
	 * @param array|null $params
	 * @param array|null $patternParams
	 *
	 * @return string
	 */
	public static function url(string $name, array $params = null, array $patternParams = null): string;

  /**
   * @return array
   */
  public static function routes(): array;

  /**
   * @param int|null $code
   * @return mixed
   */
  public static function getErrors(int $code = null);

  /**
   * @param string $origin
   * @param string $destination
   * @param bool $permanent
   * @return void
   */
  public static function redirect($origin, $destination, $permanent = true);
}