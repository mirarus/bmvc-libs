<?php

/**
 * IBenchmark
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Benchmark
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Benchmark;

interface IBenchmark
{

  /**
   * @param bool $text
   * @param bool $bmvc
   * @return string
   */
  public static function memory(bool $text = false, bool $bmvc = true): string;

  /**
   * @return string
   */
  public static function run(): string;
}