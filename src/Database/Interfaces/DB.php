<?php

/**
 * DB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces;

interface DB
{

	public function __construct(Connect $connector);
	public static function setTime(float $time, string $func): void;
  public static function getTime(string $func = null, string $ns = null); // @phpstan-ignore-line
	public function __call(string $method, array $args = []); // @phpstan-ignore-line
	public static function __callStatic(string $method, array $args = []); // @phpstan-ignore-line
	public function __debugInfo(): array; // @phpstan-ignore-line
}