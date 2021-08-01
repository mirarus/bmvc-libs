<?php

/**
 * Composer
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace BMVC\Libs;

class Composer
{
	
	public static function folderDelete()
	{
		FS::rm_dir_sub(".git");
		FS::rm_dir_sub(".github");
	}
}