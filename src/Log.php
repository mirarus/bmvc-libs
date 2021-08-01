<?php

/**
 * Log
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 2.7
 */

namespace BMVC\Libs;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Exception;
use DateTime;

class Log
{

	/**
	 * @var string
	 */
	private static $dir = 'Logs';
	/**
	 * @var string
	 */
	private static $name = 'bmvc';

	public static $monolog;

	/**
	 * @param string      $key
	 * @param string|null $val
	 */
	public static function set(string $key, string $val=null, bool $new=false)
	{
		self::${$key} = $val;
		if ($new == true) return new self;	
	}

	public static function monolog(): void
	{
		#
		$formatter = new LineFormatter(LineFormatter::SIMPLE_FORMAT, LineFormatter::SIMPLE_DATE);
		$formatter->includeStacktraces(true);
		#
		$file   = FS::implode([FS::app(self::$dir), 'app.log']);
		$stream = new StreamHandler($file);
		$stream->setFormatter($formatter);
		#
		$log = new Logger(strtoupper(self::$name));
		$log->pushHandler($stream);

		self::$monolog = $log;
	}

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