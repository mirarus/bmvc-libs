<?php

/**
 * Monolog
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Monolog
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Monolog;

use BMVC\Libs\FS;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Monolog
{

  /**
   * @var
   */
  public static $log;

  /**
   * @var string
   */
  private static $dir = 'Logs';

  /**
   * @var string
   */
  private static $name = 'bmvc';

  /**
   * @param bool $LineFormatter
   */
  public function __construct(bool $LineFormatter = true)
  {
    self::init($LineFormatter);
  }

  /**
   * @param bool $LineFormatter
   * @return void
   */
  public static function init(bool $LineFormatter = true): void
  {
    $file = FS::implode([FS::app(self::$dir), 'app.log']);
    $stream = new StreamHandler($file);

    if ($LineFormatter) {

      $formatter = new LineFormatter(LineFormatter::SIMPLE_FORMAT, LineFormatter::SIMPLE_DATE);
      $formatter->includeStacktraces(true);
      $stream->setFormatter($formatter);
    }

    $log = new Logger(strtoupper(self::$name));
    $log->pushHandler($stream);

    self::$log = $log;
  }

  /**
   * @param string $key
   * @param string|null $val
   * @param bool $new
   * @return Monolog|void
   */
  public static function set(string $key, string $val = null, bool $new = false)
  {
    self::${$key} = $val;
    if ($new) return new self;
  }
}