<?php

/**
 * Hook
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Hook
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Hook;

use Closure;

class Hook
{

	/**
	 * @param string      $name
	 * @param mixed      $callback
	 * @param string|null $value
	 */
	public static function hook_load(string $name, $callback = null, string $value = null)
	{
		static $events = [];
		if ($callback) {
			$events[$name][] = $callback;
		} elseif (isset($events[$name])) {
			asort($events[$name]);
			foreach ($events[$name] as $callback) {
				$value = call_user_func($callback, $value);
			}
			return $value;
		} else {
			unset($events[$name]);
		}
	}

	/**
	 * @param string       $name
	 * @param Closure|null $callback
	 */
	public static function add_action(string $name, Closure $callback = null)
	{
		return self::hook_load($name, $callback, null);
	}

	/**
	 * @param string      $name
	 * @param string|null $value
	 */
	public static function do_action(string $name, string $value = null)
	{
		return self::hook_load($name, null, $value);
	}

	/**
	 * @param string $name
	 */
	public static function remove_action(string $name)
	{
		return self::hook_load($name, false);
	}
}