<?php

/**
 * Env
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Env
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Env;

use BMVC\Libs\FS;
use Dotenv\Dotenv;

class Env
{

  /**
   * @var object
   */
  public static $env;

  public function __construct()
  {
    self::init();
  }

  /**
   * @return Dotenv
   */
  public static function init(): Dotenv
  {
	  $env = Dotenv::createImmutable(FS::app());
	  $env->safeLoad();
    self::$env = $env;
		return $env;
  }
}