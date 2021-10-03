<?php

/**
 * Log
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Log
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Log;

use Exception;
use DateTime;
use BMVC\Libs\FS;
use BMVC\Libs\Request;

class Log
{

	private static $dir = 'Logs';
	private static $name = 'bmvc';

	/**
	 * @param mixed $msg
	 */
	public static function emergency($msg): void
	{
		self::write('EMERGENCY', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function alert($msg): void
	{
		self::write('ALERT', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function critical($msg): void
	{
		self::write('CRITICAL', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function error($msg): void
	{
		self::write('ERROR', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function warning($msg): void
	{
		self::write('WARNING', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function notice($msg): void
	{
		self::write('NOTICE', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function info($msg): void
	{
		self::write('INFO', $msg);
	}

	/**
	 * @param mixed $msg
	 */
	public static function debug($msg): void
	{
		self::write('DEBUG', $msg);
	}

	/**
	 * @param string $level
	 * @param mixed $msg
	 */
	private static function write(string $level, $msg): void
	{
		if (is_array($msg)) {
			$msg = @implode(', ', $msg);
		}
		self::save('[' . date(DateTime::ISO8601) . '] ' . $level . '.' . Request::getRequestMethod() . ': ' . $msg);
	}

	/**
	 * @param string $text
	 */
	private static function save(string $text): void
	{
		$dir = FS::app(self::$dir);
		FS::mk_dir($dir);
		$file = FS::implode([$dir, self::$name . '.log']);

		$file = fopen($file, 'a');
		if (fwrite($file, $text . "\r\n") === false) {
			throw new Exception('Log Error! | Failed to create log file. - Check the write permissions.');
		}
		fclose($file);
	}
}