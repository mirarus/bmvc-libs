<?php

/**
 * Composer
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Composer
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Composer;

use BMVC\Libs\FS;

class Composer
{

  /**
   * @return void
   */
  public static function folderDelete(): void
	{
		FS::rm_dir_sub(".git");
		FS::rm_dir_sub(".github");
	}
}