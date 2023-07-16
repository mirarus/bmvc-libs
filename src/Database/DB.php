<?php

/**
 * DB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.5
 */

namespace Mirarus\DB;

use Mirarus\DB\Interfaces\DB as IDB;
use Mirarus\DB\Interfaces\Connect as IConnect;

@set_time_limit(0);

class DB implements IDB
{

	/**
	 * @var boolean
	 */
	private static $active = false;

	/**
	 * Class Time
	 * @var float
	 */
	private static $time = 0.0;

	/**
	 * Driver Time
	 * @var array
	 */
	private static $_time = []; // @phpstan-ignore-line

	/**
	 * Connection Link
	 * @var array
	 */
	protected static $connect = []; // @phpstan-ignore-line

	/**
	 * Selected Driver Namespace Name
	 * @var string
	 */
	private static $dNamespace;

	/**
	 * Selected Driver Class Name
	 * @var string
	 */
	private static $dClass;

	/**
	 * Selected Driver Class
	 * @var object
	 */
	private static $db;

	/**
	 * @param IConnect    $connector
	 * @param object|null &$return
	 */
	public function __construct(IConnect $connector, object &$return = null)
	{
		if (self::$active == true) return;

		self::$time				= microtime(true);
		self::$connect		= $connector->get();
		self::$dNamespace = (__NAMESPACE__ . '\\Driver\\' . self::$connect['driver']);
		self::$dClass			= (self::$dNamespace . '\\Driver');
		self::$db					= new self::$dClass();

		self::setGlobal('_DB', self::$db);
		self::setGlobal(('_DB__' . self::$connect['driver']), self::$db);
		self::setGlobal(('_DB__' . @mb_strtolower(self::$connect['driver'], "UTF-8")), self::$db);

		self::setTime(microtime(true), __METHOD__);

		// @phpstan-ignore-next-line
		$return = self::$db;

		self::$active = true;

		return $return;
	}

	/**
	 * @param float       $time
	 * @param string      $method
	 * @param string|null $namespace
	 */
  public static function setTime(float $time, string $method, string $namespace = null): void
  {
    if ($namespace) {
      $ns = str_replace((__NAMESPACE__ . '\\'), null, $namespace);
      $m = str_replace(($namespace . '\\'), null, $method);
      self::$_time[__NAMESPACE__][$ns][$m] = ($time - self::$time);
    } else {
      $method = str_replace((__NAMESPACE__ . '\\'), null, $method);
      self::$_time[__NAMESPACE__][$method] = ($time - self::$time);
    }
  }

	/**
	 * @param string|null $func
	 * @param string|null $ns
	 */
	public static function getTime(string $func = null, string $ns = null) // @phpstan-ignore-line
	{
		if ($ns) {
			foreach (self::$_time as $key => $val) {
				return strstr($key, $ns) ? [$key => $val] : null;
			}
		}

		# $func = $func ? ( __NAMESPACE__ . '\\') . $func : null;
		return $func ? self::$_time[$func] : self::$_time;
	}

	/**
	 * @param string $key
	 * @param mixed	 $val
	 */
	private static function setGlobal(string $key, $val): void
	{
		@$GLOBALS[$key] = $val;
	}

	/**
	 * @param string $method
	 * @param array  $args
	 */
	public function __call(string $method, array $args = []) // @phpstan-ignore-line
	{
		$function = call_user_func_array([self::$db, $method], $args); // @phpstan-ignore-line
		self::setTime(microtime(true), __METHOD__);
		return $function;
	}

	/**
	 * @param string $method
	 * @param array  $args
	 */
	public static function __callStatic(string $method, array $args = []) // @phpstan-ignore-line
	{
		$function = call_user_func_array([self::$db, $method], $args); // @phpstan-ignore-line
		self::setTime(microtime(true), __METHOD__);
		return $function;
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array // @phpstan-ignore-line
	{
		return [
			'_driver' => self::$connect['driver'],
			'_dsn' => self::$connect['dsn'],
			'_db' => self::$db,
			'_time' => self::getTime()
		];
	}
}