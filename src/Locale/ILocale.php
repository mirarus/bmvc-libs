<?php

/**
 * ILocale
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Locale
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Locale;

interface ILocale
{

  /**
   * @return void
   */
  public static function init(): void;

	/**
	 * @param null $index
	 * @return mixed
	 */
  public static function list($index = null);
}