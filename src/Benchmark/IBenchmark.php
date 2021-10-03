<?php

/**
 * IBenchmark
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Benchmark
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Benchmark;

interface IBenchmark
{

	public static function memory(bool $text = false, bool $bmvc = true): string;
	public static function run(): string;
}