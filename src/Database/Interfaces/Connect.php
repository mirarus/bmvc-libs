<?php

/**
 * Connect
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces;

interface Connect
{

  public function __construct();
	public static function driver(string $driver): void;
  public static function dsn(...$dsn): void; // @phpstan-ignore-line
  public static function get(string $par = null); // @phpstan-ignore-line
  public function __debugInfo(): array; // @phpstan-ignore-line
}