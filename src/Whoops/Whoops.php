<?php

/**
 * Whoops
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Whoops
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Whoops;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

abstract class Whoops
{

	/**
	 * @var array
	 */
	private static $blacklist = [
		'_GET' => [],
		'_POST' => [],
		'_FILES' => [],
		'_COOKIE' => [],
		'_SESSION' => [],
		'_SERVER' => [],
		'_ENV' => []
	];

	public static $environment;
	public static $whoops;

	/**
	 * @param string      $key
	 * @param string|null $val
	 */
	public static function set(string $key, string $val = null, bool $new = false)
	{
		self::${$key} = $val;
		if ($new == true) return new self;	
	}

	public static function init(): void
	{
		$PPH = new PrettyPageHandler;
		foreach (self::$blacklist as $key => $val) {
			foreach ($val as $arg) {
				$PPH->blacklist($key, $arg);
			}
		}
		#
		$whoops = new Run;

		if (self::$environment == 'development') {
			$whoops->pushHandler($PPH);
		}

		$whoops->register();

		self::$whoops = $whoops;
	}

	/**
	 * @param string $name
	 * @param mixed  $keys
	 */
	public static function blacklist(string $name, $keys): void
	{
		if (is_array($keys)) {
			foreach ($keys as $key) {
				self::$blacklist[$name][] = $key;
			}
		} elseif (is_string($keys)) {
			self::$blacklist[$name][] = $keys;
		}
	}
}