<?php

/**
 * Monolog
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Monolog
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Monolog;

use BMVC\Libs\FS;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Monolog
{

	public static $log;
	private static $dir = 'Logs';
	private static $name = 'bmvc';

	/**
	 * @param string      $key
	 * @param string|null $val
	 */
	public static function set(string $key, string $val = null, bool $new = false)
	{
		self::${$key} = $val;
		if ($new == true) return new self;	
	}

	public static function run(): void
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

		self::$log = $log;
	}
}