<?php

/**
 * Composer
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Composer
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Composer;

use BMVC\Libs\FS;

class Composer
{
	
	public static function folderDelete()
	{
		FS::rm_dir_sub(".git");
		FS::rm_dir_sub(".github");
	}
}